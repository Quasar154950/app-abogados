<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Gráficas – Tiempo real (Tandil)</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Chart.js + Annotation plugin -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@3"></script>
  <style>
    body { font-family: Arial, sans-serif; background:#F0F0F0; color:#333; margin:16px; }
    .wrap { max-width: 1100px; margin: 0 auto; display: grid; gap: 16px; }
    h1 { font-size: 1.25rem; margin:.2rem 0 .5rem; }
    .hint { font-size:.9rem; color:#555; margin-bottom:.7rem; }
    .card { padding:14px; border:1px solid #ddd; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,.05); background:#fff; }
    .chart-box { height: 420px; }
    .status { margin-top:8px; font-size:.9rem; color:#666; display:flex; flex-wrap:wrap; gap:8px; align-items:center; }
    .title-row { display:flex; align-items:center; justify-content:space-between; gap:10px; flex-wrap: wrap; }
    .badge { font-size:.85rem; padding:4px 8px; border-radius:999px; border:1px solid transparent; }

    /* Presión (nivel) */
    .badge.low    { background:#ffe5e5; color:#8b0000; border-color:#ffb3b3; }
    .badge.normal { background:#e8f5e9; color:#1b5e20; border-color:#c8e6c9; }
    .badge.high   { background:#fff3e0; color:#6d4c00; border-color:#ffe0b2; }

    /* Estado por punto de rocío (para T/H) */
    .badge.dp-dry        { background:#e3f2fd; color:#0d47a1; border-color:#bbdefb; }   /* Seco */
    .badge.dp-comfy      { background:#e8f5e9; color:#1b5e20; border-color:#c8e6c9; }   /* Confortable */
    .badge.dp-mild       { background:#f1f8e9; color:#33691e; border-color:#dcedc8; }   /* Templado */
    .badge.dp-humid      { background:#fff8e1; color:#e65100; border-color:#ffe0b2; }   /* Húmedo */
    .badge.dp-heavy      { background:#ffecb3; color:#6d4c00; border-color:#ffe082; }   /* Pesado */
    .badge.dp-veryheavy  { background:#ffd9d9; color:#b71c1c; border-color:#ffb3b3; }   /* Muy pesado */

    .note { font-size:.85rem; color:#777; }
  </style>
</head>
<body>
  <div class="wrap">
    <div>
      <h1>Gráficas en tiempo real</h1>
      <p class="hint">Actualiza cada 10 segundos. Líneas suavizadas para leer la tendencia; ejes en 24 h.</p>
    </div>

    <!-- Temperatura / Humedad -->
    <div class="card">
      <div class="title-row">
        <h2 style="font-size:1.05rem;margin:0">Temperatura/Humedad – Tendencia</h2>
        <span class="note">Rangos orientativos: T 18–26 °C · H 30–60 %</span>
      </div>
      <div class="chart-box">
        <canvas id="chartTH"></canvas>
      </div>
      <div class="status" id="statusTH">
        Estado: <span id="badgeComfort" class="badge">Calculando…</span>
        <span id="dpText" class="note"></span>
        <span id="stText" class="note"></span>
        <span id="clockTH" class="note" style="margin-left:auto;"></span>
      </div>
    </div>

    <!-- Presión -->
    <div class="card" id="cardP">
      <div class="title-row">
        <h2 style="font-size:1.05rem;margin:0">Presión atmosférica – Tendencia</h2>
        <div class="note">Referencia: 1013 hPa ≈ presión media a nivel del mar</div>
      </div>
      <div class="chart-box">
        <canvas id="chartP"></canvas>
      </div>
      <div class="status" id="statusP">
        Estado: <span id="badgeP" class="badge">Calculando…</span>
        <span id="clockP" class="note" style="margin-left:auto;"></span>
      </div>
    </div>
  </div>

  <script>
    // === Parámetros ===
    const ENDPOINT_TH = '/meteo/lectura-actual'; // temp/hum
    const ENDPOINT_P  = '/meteo/datos';       // presión + punto de rocío + sensación térmica
    const POLL_MS  = 10000;   // cada 10 s
    const MAX_PTOS = 180;     // ~30 min si POLL_MS=10 s
    const SMOOTH_K = 3;       // media móvil simple (tendencia)

    // Umbrales presión
    const P_LOW   = 1009;
    const P_HIGH  = 1017;
    const REF_1013 = 1013;
    const TREND_UP = +0.5, TREND_DOWN = -0.5; // ~30 min

    // Buffers (crudos)
    const labelsTH = [], temp = [], hum = [];
    const labelsP  = [], pres = [];

    // DOM
    const $badgeP       = document.getElementById('badgeP');
    const $badgeComfort = document.getElementById('badgeComfort');
    const $dpText       = document.getElementById('dpText');
    const $stText       = document.getElementById('stText');
    const $clockTH      = document.getElementById('clockTH');
    const $clockP       = document.getElementById('clockP');

    // Helpers
    function parseTs(tsStr){
      if (!tsStr) return new Date();
      const m = String(tsStr).match(/^(\d{4}-\d{2}-\d{2})[ T](\d{2}:\d{2}:\d{2})(\.\d+)?([+-]\d{2}:?\d{2}|[+-]\d{2}|Z)?$/);
      if (m){
        const date=m[1], time=m[2], frac=m[3]?m[3].slice(0,4):''; let off=m[4]||'';
        if (off && /^[+-]\d{2}$/.test(off)) off += ':00';
        const d = new Date(`${date}T${time}${frac}${off}`); if (!isNaN(d)) return d;
      }
      const d2 = new Date(String(tsStr).replace(' ','T').replace(/(\.\d{3})\d+/, '$1'));
      return isNaN(d2) ? new Date() : d2;
    }
    function fmt24h(d){ return d.toLocaleTimeString('es-AR',{hour12:false,hour:'2-digit',minute:'2-digit'}); }
    function movingAverage(arr,k){
      if (k<=1 || arr.length<k) return [...arr];
      const out=[]; for(let i=0;i<arr.length;i++){ const ini=Math.max(0,i-(k-1)); const s=arr.slice(ini,i+1); out.push(s.reduce((a,b)=>a+b,0)/s.length); }
      return out;
    }
    function clip(arrs){ arrs.forEach(a=>{ while(a.length>MAX_PTOS) a.shift(); }); }

    // Clasificación por punto de rocío → “Estado”
    function classifyDew(dp){
      if (!Number.isFinite(dp)) return { text: '—', cls: '' };
      if (dp < 10) return { text: 'Seco',        cls: 'dp-dry' };
      if (dp < 16) return { text: 'Confortable', cls: 'dp-comfy' };
      if (dp < 19) return { text: 'Templado',    cls: 'dp-mild' };
      if (dp < 22) return { text: 'Húmedo',      cls: 'dp-humid' };
      if (dp < 25) return { text: 'Pesado',      cls: 'dp-heavy' };
      return { text: 'Muy pesado', cls: 'dp-veryheavy' };
    }

    // === CHART T/H ===
    const ctxTH = document.getElementById('chartTH').getContext('2d');
    const chartTH = new Chart(ctxTH, {
      type: 'line',
      data: {
        labels: labelsTH,
        datasets: [
          { label: 'Temperatura (°C)', data: [], yAxisID: 'y1', tension: 0.25 },
          { label: 'Humedad (%)',     data: [], yAxisID: 'y2', tension: 0.25 }
        ]
      },
      options: {
        animation: false, responsive: true, maintainAspectRatio: false,
        scales: {
          y1: { type: 'linear', position: 'left',
                suggestedMin: -10, suggestedMax: 40,
                title: { display: true, text: 'Temperatura (°C)' } },
          y2: { type: 'linear', position: 'right',
                suggestedMin: 0, suggestedMax: 100,
                title: { display: true, text: 'Humedad (%)' },
                grid: { drawOnChartArea: false } },
          x:  { ticks: { maxTicksLimit: 8 }, title: { display: true, text: 'Hora (24 h)' } }
        },
        plugins: { legend: { display: true } }
      }
    });

    // === CHART Presión ===
    const ctxP = document.getElementById('chartP').getContext('2d');
    const chartP = new Chart(ctxP, {
      type: 'line',
      data: { labels: labelsP, datasets: [ { label: 'Presión (hPa)', data: [], yAxisID: 'yP', tension: 0.25 } ] },
      options: {
        animation: false, responsive: true, maintainAspectRatio: false,
        scales: {
          yP: { type: 'linear', position: 'left',
                suggestedMin: 980, suggestedMax: 1040,
                title: { display: true, text: 'Presión (hPa)' } },
          x:  { ticks: { maxTicksLimit: 8 }, title: { display: true, text: 'Hora (24 h)' } }
        },
        plugins: {
          legend: { display: true },
          annotation: { annotations: {
            refLine: { type:'line', yMin:REF_1013, yMax:REF_1013, borderWidth:1, borderDash:[6,6], borderColor:'#888',
              label:{ enabled:true, content:'1013 hPa', position:'start',
                      backgroundColor:'rgba(255,255,255,0.8)', color:'#444', padding:4 } }
          }}
        }
      }
    });

    // === LOOP: T/H + Estado (por punto de rocío) + Sensación térmica ===
    async function tickTH(){
      try{
        const r = await fetch(ENDPOINT_TH, { cache: 'no-store' });
        if (!r.ok) throw new Error(`HTTP ${r.status}`);
        const j = await r.json();

        const when = parseTs(j.ts);
        labelsTH.push(fmt24h(when));
        temp.push(Number(j.temp ?? NaN));
        hum.push(Number(j.hum ?? NaN));
        clip([labelsTH, temp, hum]);

        const tempS = movingAverage(temp, SMOOTH_K);
        const humS  = movingAverage(hum,  SMOOTH_K);

        // Escalas dinámicas
        const tVals = temp.filter(Number.isFinite);
        if (tVals.length){
          let lo=Math.min(...tVals), hi=Math.max(...tVals);
          if (lo===hi){ lo-=1; hi+=1; }
          lo=Math.floor(lo-2); hi=Math.ceil(hi+2);
          chartTH.options.scales.y1.min = Math.max(-30, lo);
          chartTH.options.scales.y1.max = Math.min( 50, hi);
        } else { delete chartTH.options.scales.y1.min; delete chartTH.options.scales.y1.max; }

        const hVals = hum.filter(Number.isFinite);
        if (hVals.length){
          let loH=Math.min(...hVals), hiH=Math.max(...hVals);
          if (loH===hiH){ loH-=1; hiH+=1; }
          loH=Math.floor(loH-3); hiH=Math.ceil(hiH+3);
          chartTH.options.scales.y2.min = Math.max(0, loH);
          chartTH.options.scales.y2.max = Math.min(100, hiH);
        } else { delete chartTH.options.scales.y2.min; delete chartTH.options.scales.y2.max; }

        chartTH.data.datasets[0].data = tempS;
        chartTH.data.datasets[1].data = humS;
        chartTH.update('none');

        // Estado + Punto de rocío + Sensación térmica desde consulta.php
        try {
          const r2 = await fetch(ENDPOINT_P, { cache: 'no-store' });
          if (r2.ok) {
            const j2 = await r2.json();
            const dp = Number(j2.punto_rocio ?? NaN);
            const st = Number(j2.sensacion_termica ?? j2.sensacion ?? NaN);

            // Estado por punto de rocío
            const cls = classifyDew(dp);
            $badgeComfort.className = 'badge ' + (cls.cls || '');
            $badgeComfort.textContent = cls.text;

            // Texto de punto de rocío
            $dpText.textContent = Number.isFinite(dp) ? `· Punto de rocío: ${dp.toFixed(1)} °C` : '';

            // Sensación térmica (con diferencia vs T actual)
            const tLast = temp.length ? temp[temp.length-1] : NaN;
            if (Number.isFinite(st)) {
              let extra = '';
              if (Number.isFinite(tLast)) {
                const dif = st - tLast;
                const sign = dif > 0 ? '+' : '';
                extra = ` (${sign}${dif.toFixed(1)} vs T)`;
              }
              $stText.textContent = `· Sensación térmica: ${st.toFixed(1)} °C${extra}`;
            } else {
              $stText.textContent = '';
            }
          } else {
            $badgeComfort.className = 'badge';
            $badgeComfort.textContent = '—';
            $dpText.textContent = '';
            $stText.textContent = '';
          }
        } catch(_e){
          $badgeComfort.className = 'badge';
          $badgeComfort.textContent = '—';
          $dpText.textContent = '';
          $stText.textContent = '';
        }

        $clockTH.textContent = `Última actualización: ${
          new Date().toLocaleTimeString('es-AR', { hour12:false, hour:'2-digit', minute:'2-digit', second:'2-digit' })
        }`;

      }catch(e){
        console.warn('tickTH error', e);
        $clockTH.textContent = `Error T/H (${e.message}). Reintentando…`;
      }finally{
        setTimeout(tickTH, POLL_MS);
      }
    }

    // === LOOP: Presión (con línea 1013 y badge de nivel+tendencia) ===
    async function tickP(){
      try{
        const r = await fetch(ENDPOINT_P, { cache: 'no-store' });
        if (!r.ok) throw new Error(`HTTP ${r.status}`);
        const j = await r.json();

        const when = parseTs(j.tiempo || j.ts || new Date());
        labelsP.push(fmt24h(when));
        pres.push(Number(j.presion ?? NaN));
        clip([labelsP, pres]);

        const presS = movingAverage(pres, SMOOTH_K);

        // Escala dinámica
        const pVals = pres.filter(Number.isFinite);
        if (pVals.length){
          let loP=Math.min(...pVals), hiP=Math.max(...pVals);
          if (loP===hiP){ loP-=0.5; hiP+=0.5; }
          loP=Math.floor(loP-1); hiP=Math.ceil(hiP+1);
          chartP.options.scales.yP.min = Math.max(950, loP);
          chartP.options.scales.yP.max = Math.min(1050, hiP);
        } else { delete chartP.options.scales.yP.min; delete chartP.options.scales.yP.max; }

        chartP.data.datasets[0].data = presS;
        chartP.update('none');

        // Badge presión + tendencia
        const last = pVals.length ? pVals[pVals.length-1] : null;
        const first = pVals.length ? pVals[0] : null;
        const delta = (last!=null && first!=null) ? (last - first) : 0;

        let trend = '→ estable';
        if (delta >= TREND_UP) trend = '↗︎ subiendo';
        else if (delta <= TREND_DOWN) trend = '↘︎ bajando';

        if (last != null) {
          let cls = 'normal', txt = 'Normal';
          if (last < P_LOW)      { cls = 'low';  txt = 'Baja'; }
          else if (last > P_HIGH){ cls = 'high'; txt = 'Alta'; }
          $badgeP.className = 'badge ' + cls;
          $badgeP.textContent = `Nivel: ${txt} · ${last.toFixed(1)} hPa · Tendencia: ${trend}`;
        } else {
          $badgeP.className = 'badge';
          $badgeP.textContent = 'Sin datos';
        }

        $clockP.textContent = `Última actualización: ${
          new Date().toLocaleTimeString('es-AR', { hour12:false, hour:'2-digit', minute:'2-digit', second:'2-digit' })
        }`;

      }catch(e){
        console.warn('tickP error', e);
        $clockP.textContent = `Error Presión (${e.message}). Reintentando…`;
      }finally{
        setTimeout(tickP, POLL_MS);
      }
    }

    // Iniciar loops
    tickTH();
    tickP();
  </script>
</body>
</html>
