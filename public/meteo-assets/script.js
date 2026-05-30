document.addEventListener('DOMContentLoaded', () => {
  /* ===========================
     Lupa / barra de búsqueda
  ============================ */
  const searchIcon = document.getElementById('searchIcon');
  const searchBar  = document.getElementById('searchBar');
  if (searchIcon && searchBar) {
    searchIcon.addEventListener('click', () => {
      searchBar.classList.toggle('active');
      if (searchBar.classList.contains('active')) {
        const input = searchBar.querySelector('.search-input');
        input && input.focus();
      }
    });
    document.addEventListener('click', (e) => {
      if (!searchBar.contains(e.target) && !searchIcon.contains(e.target)) {
        searchBar.classList.remove('active');
      }
    });
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') searchBar.classList.remove('active');
    });

    const btn = document.querySelector('.search-button');
    if (btn) btn.addEventListener('click', () => {
      const q = (searchBar.querySelector('.search-input')?.value || '').trim();
      if (q) window.open(`https://www.google.com/search?q=site:${location.origin}+${encodeURIComponent(q)}`, '_blank');
    });

    const input = searchBar.querySelector('.search-input');
    if (input) {
      input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
          const q = (input.value || '').trim();
          if (q) window.open(`https://www.google.com/search?q=site:${location.origin}+${encodeURIComponent(q)}`, '_blank');
        }
      });
    }
  }

  /* ===========================
     Menú desplegable (Tandil)
  ============================ */
  const tandilBtn  = document.getElementById('tandilDropdownBtn');
  const tandilMenu = document.getElementById('tandilDropdownMenu');
  if (tandilBtn && tandilMenu) {
    tandilBtn.addEventListener('click', (e) => {
      e.preventDefault();
      tandilMenu.style.display = tandilMenu.style.display === 'block' ? 'none' : 'block';
    });
    document.addEventListener('click', (e) => {
      if (!tandilBtn.contains(e.target) && !tandilMenu.contains(e.target)) {
        tandilMenu.style.display = 'none';
      }
    });
  }

  /* ===========================
     Menú desplegable (Descargas)
  ============================ */
  const descBtn  = document.getElementById('descargasDropdownBtn');
  const descMenu = document.getElementById('descargasDropdownMenu');
  if (descBtn && descMenu) {
    descBtn.addEventListener('click', (e) => {
      e.preventDefault();
      descMenu.style.display = descMenu.style.display === 'block' ? 'none' : 'block';
    });
    document.addEventListener('click', (e) => {
      if (!descBtn.contains(e.target) && !descMenu.contains(e.target)) {
        descMenu.style.display = 'none';
      }
    });
  }

  /* ===========================
     Menú desplegable (NASA)
     → MISMA lógica que Tandil/Descargas
  ============================ */
  const nasaBtn  = document.getElementById('nasaDropdownBtn');
  const nasaMenu = document.getElementById('nasaDropdownMenu');
  if (nasaBtn && nasaMenu) {
    nasaBtn.addEventListener('click', (e) => {
      e.preventDefault();
      nasaMenu.style.display = nasaMenu.style.display === 'block' ? 'none' : 'block';

      // Cerrar otros menús si estaban abiertos (opcional pero prolijo)
      if (nasaMenu.style.display === 'block') {
        if (tandilMenu) tandilMenu.style.display = 'none';
        if (descMenu)   descMenu.style.display   = 'none';
      }
    });
    document.addEventListener('click', (e) => {
      if (!nasaBtn.contains(e.target) && !nasaMenu.contains(e.target)) {
        nasaMenu.style.display = 'none';
      }
    });
  }

  /* ===========================
     Helpers
  ============================ */
  const $ = id => document.getElementById(id);
  const setText = (id, v) => { const el = $(id); if (el) el.textContent = (v ?? '--'); };

  const fmtComa = (v, dec = 0) => {
    if (v == null || v === '' || isNaN(v)) return '--';
    return Number(v).toFixed(dec).replace('.', ',');
    };

  const hhmm = t => (!t || typeof t !== 'string')
    ? null
    : (t.match(/^(\d{2}):(\d{2})/) || []).slice(1,3).join(':') || t;

  const withHs = s => (s && s !== '--') ? `${s} hs` : '--';

  const clasificarUV = (n) => {
    if (n == null || isNaN(n)) return '';
    if (n < 3.0)  return 'Bajo';
    if (n < 6.0)  return 'Moderado';
    if (n < 8.0)  return 'Alto';
    if (n < 11.0) return 'Muy alto';
    return 'Extremo';
  };

  /* ===========================
     Leaflet (mapa coordenadas)
  ============================ */
  const mapBtn  = $('openMapBtn');
  const mapWrap = $('mapWrapper');
  const mapMsg  = $('mapMsg');
  let map, marker;

  mapBtn?.addEventListener('click', () => {
    mapWrap.classList.toggle('is-open');
    if (mapWrap.classList.contains('is-open')) {
      if (!map) {
        try {
          if (!window.L) throw new Error('Leaflet no disponible');
          map = L.map('map');
          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
          }).addTo(map);
          marker = L.marker([0, 0]).addTo(map);
          map.setView([0, 0], 2);
        } catch (err) {
          console.warn('Mapa deshabilitado:', err.message);
        }
      }
      if (map && map.invalidateSize) setTimeout(() => map.invalidateSize(), 50);
    }
  });

  /* ===========================
     Carga de datos periódica
  ============================ */
  async function cargarDatos() {
    try {
      const res = await fetch('/estacionmeteotandil/consulta.php', { cache: 'no-store' });
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const d = await res.json();

      const n = {
        ...d,
        lluvia:     d.lluvia ?? d.lluvia_acumulada,
        uv:         d.uv ?? d.indice_uv,
        uv_peligrosidad: d.uv_peligrosidad ?? d.peligrosidad ?? '',
        rocio:      d.rocio ?? d.punto_rocio,
        direccion:  d.direccion ?? d.direccion_viento,
        velocidad:  d.velocidad ?? d.velocidad_viento,
        rafagas:    d.rafagas ?? d.rafagas_viento,
        sensacion:  d.sensacion ?? d.sensacion_termica,
        salida_del_sol: d.salida_del_sol ?? d.salida_sol,
        puesta_del_sol: d.puesta_del_sol ?? d.puesta_sol
      };

      pintar(n);
    } catch (e) {
      console.warn('No pude cargar datos:', e.message);
    }
  }

  /* ===========================
     Pintar datos en UI
  ============================ */
  function pintar(d) {
    // 📍 Coordenadas
    setText('latitud',  fmtComa(d.latitud, 5));
    setText('longitud', fmtComa(d.longitud, 5));
    setText('altitud',  fmtComa(d.altitud, 1));

    // Mapa
    if (map && marker) {
      const lat = Number(d.latitud);
      const lon = Number(d.longitud);
      const invalid = !isFinite(lat) || !isFinite(lon) || (lat === 0 && lon === 0);

      if (invalid) {
        if (mapMsg) mapMsg.style.display = 'block';
        marker.setLatLng([0, 0]);
        map.setView([0, 0], 2);
      } else {
        if (mapMsg) mapMsg.style.display = 'none';
        marker.setLatLng([lat, lon]);
        if (mapWrap.classList.contains('is-open')) {
          map.setView([lat, lon], 13);
        }
      }
    }

    // ☀ Astronomía
    setText('alba',           withHs(hhmm(d.alba)));
    setText('salida_sol',     withHs(hhmm(d.salida_del_sol)));
    setText('puesta_sol',     withHs(hhmm(d.puesta_del_sol)));
    setText('duracion_dia',   withHs(hhmm(d.duracion_dia)));
    setText('anochecer',      withHs(hhmm(d.anochecer)));
    setText('salida_luna',    withHs(hhmm(d.salida_luna)));
    setText('puesta_luna',    withHs(hhmm(d.puesta_luna)));
    setText('fase_lunar',     d.fase_lunar ?? '--');
    setText('ciclo_lunar',    d.ciclo_lunar ?? '--');

    // 🌙 Luna (normalización + clamps)
    if (d.visibilidad_lunar != null && !isNaN(d.visibilidad_lunar)) {
      const visNum = Number(d.visibilidad_lunar);
      setText('visibilidad_lunar', `${fmtComa(visNum, 1)} %`);

      const cicloRaw = (d.ciclo_lunar || 'Creciente').toString();
      const ciclo = /meng/i.test(cicloRaw) ? 'Menguante' : 'Creciente';

      const k = Math.min(0.999, Math.max(0.001, visNum / 100));
      mlSetPhase(k, ciclo, 0);
    } else {
      setText('visibilidad_lunar', '--');
      mlSetPhase(0.001, 'Creciente', 0);
    }

    // 🌡 Meteorología
    setText('temperatura-value', fmtComa(d.temperatura, 1));
    setText('sensacion-value',   fmtComa(d.sensacion, 1));
    setText('rocio-value',       fmtComa(d.rocio, 1));
    setText('humedad-value',     fmtComa(d.humedad, 0));
    setText('presion-value',     fmtComa(d.presion, 0));
    setText('lluvia-value',      fmtComa(d.lluvia, 1));
    setText('luminosidad-value', fmtComa(d.luminosidad, 0));
    setText('nubes-value',       fmtComa(d.techo_nubes, 0));

    // Lluvia (mm)
    setText('intensidad-lluvia-maxima-value',   fmtComa(d.intensidad_lluvia_maxima_diaria, 1));
    setText('intensidad-lluvia-promedio-value', fmtComa(d.intensidad_lluvia_promedio_diaria, 1));

    // 🌬 Dirección del viento
    if (d.direccion && d.angulo_viento != null && !isNaN(d.angulo_viento)) {
      setText('direccion-value', `${d.direccion} (${fmtComa(d.angulo_viento, 0)}°)`);
    } else {
      setText('direccion-value', d.direccion ?? '--');
    }

    // 🧭 Flecha
    const flecha = document.getElementById('flecha-viento');
    if (flecha) {
      if (!flecha.dataset.init) {
        flecha.textContent = '↑';
        flecha.dataset.init = '1';
        flecha.style.display = 'inline-block';
        flecha.style.transition = 'transform 200ms ease';
        flecha.style.userSelect = 'none';
        flecha.style.marginLeft = '6px';
        flecha.style.lineHeight = '1';
      }
      if (d.angulo_viento != null && !isNaN(d.angulo_viento)) {
        const ang = Number(d.angulo_viento);
        flecha.style.transform = `rotate(${ang}deg)`;
        const label = `${d.direccion ?? ''} (${Math.round(ang)}°)`;
        flecha.setAttribute('aria-label', label);
        flecha.title = label;
      } else {
        flecha.removeAttribute('style');
        flecha.textContent = '↑';
      }
    }

    setText('velocidad-value', fmtComa(d.velocidad, 1));
    setText('rafagas-value',   fmtComa(d.rafagas, 1));

    // ☀ UV
    const uvTxtRaw = ((d.uv_peligrosidad ?? '').toString().trim()) || clasificarUV(d.uv);
    const uvTxtMin = uvTxtRaw ? uvTxtRaw.replace(/[()]/g, '').toLocaleLowerCase('es-AR') : '';
    setText('uv-value', `${fmtComa(d.uv, 2)}${uvTxtMin ? ` (${uvTxtMin})` : ''}`);
  }

  cargarDatos();
  setInterval(cargarDatos, 10000);
}); // DOMContentLoaded


/* ============================================================
   🌙 Terminador con "cuarto recto" + transición gradual
============================================================ */
function mlSetPhase(k=0.033, ciclo="Creciente", pa=0){
  // clamps robustos
  k = Math.min(0.999, Math.max(0.001, Number(k) || 0.001));

  const svg    = document.getElementById('ml-svg');
  const c1     = document.getElementById('ml-c1');
  const c2     = document.getElementById('ml-c2');
  const imgLit = document.getElementById('ml-img-lit');
  const base   = document.getElementById('ml-img-base');
  if (!svg || !c1 || !c2 || !imgLit) return;

  // Parámetros
  const ORIENT = -1;   // hemisferio sur (creciente: sombra a la derecha)
  const FLAT   = 1.25; // curvatura “natural”
  const R      = 110;

  const EPSQ   = 0.008; // 0.8%: recto puro
  const BLENDQ = 0.020; // 2.0%: mezcla recto→curvo

  // Helpers
  const waxing = typeof ciclo === "string" ? !/^meng/i.test(ciclo) : !!ciclo;
  const lerp   = (a,b,t) => a + (b - a)*t;
  const clamp01= (x) => Math.max(0, Math.min(1, x));
  const sstep  = (x) => x*x*(3 - 2*x); // smoothstep

  imgLit.setAttribute('clip-path', 'url(#ml-disc)');

  // 1) Solver general
  const a = R * FLAT, b = R;
  const modeCres = (k < 0.5);
  const signCres = (waxing ? -1 : +1) * ORIENT; // restar sombra
  const signGibb = (waxing ? +1 : -1) * ORIENT; // intersección

  const GRID = 96;
  const STEP = (2*R)/GRID;

  function fracLit(d){
    const cx = (modeCres ? signCres : signGibb) * d;
    let lit=0, tot=0;
    for (let iy=0; iy<GRID; iy++){
      const y  = -R + (iy+0.5)*STEP; const y2 = y*y;
      for (let ix=0; ix<GRID; ix++){
        const x = -R + (ix+0.5)*STEP;
        if (x*x + y2 <= R*R){
          tot++;
          const ex = x - cx;
          const insideEll = ((ex*ex)/(a*a) + (y*y)/(b*b)) <= 1.0;
          const isLit = modeCres ? !insideEll : insideEll;
          if (isLit) lit++;
        }
      }
    }
    return lit / tot;
  }

  // Bisección
  let lo = 0, hi = 3*R*FLAT;
  for (let i=0; i<20; i++){
    const mid = (lo + hi)/2;
    const f   = fracLit(mid);
    if (modeCres){ if (f < k) lo = mid; else hi = mid; }
    else         { if (f > k) lo = mid; else hi = mid; }
  }
  const dSolver  = (lo + hi)/2;
  const cxSolver = (modeCres ? signCres : signGibb) * dSolver;
  const sxSolver = FLAT;
  const sySolver = 1.35;
  const rSolver  = R;

  // 2) Geometría recta (half-plane)
  const BIG = 10000;
  const dir = (waxing ? -BIG : BIG) * ORIENT;
  const cxRect = dir;
  const sxRect = 1.0;
  const syRect = 1.0;
  const rRect  = BIG;

  // 3) Mezcla
  const dk   = Math.abs(k - 0.5);
  let tBlend = 1.0; // 1 → solver, 0 → recto
  if (dk <= EPSQ) {
    tBlend = 0.0;
  } else if (dk <= BLENDQ) {
    const u = clamp01((dk - EPSQ) / (BLENDQ - EPSQ));
    tBlend = sstep(u);
  } else {
    tBlend = 1.0;
  }

  // Interpolación de c2
  const cx = lerp(cxRect, cxSolver, tBlend);
  const sx = lerp(sxRect, sxSolver, tBlend);
  const sy = lerp(syRect, sySolver, tBlend);
  const rr = lerp(rRect , rSolver , tBlend);

  // Fills
  if (modeCres){ c1.setAttribute('fill','white'); c2.setAttribute('fill','black'); }
  else          { c1.setAttribute('fill','black'); c2.setAttribute('fill','white'); }

  // Atributos
  c2.setAttribute('r', rr);
  c2.setAttribute('cx', cx);
  c2.setAttribute('cy', 0);
  c2.setAttribute('transform', `translate(${cx},0) scale(${sx},${sy}) translate(${-cx},0)`);

  // Rotación global
  svg.style.transform = `rotate(${pa}deg)`;

  // Luz cenicienta
  const coef = waxing ? (tBlend < 0.5 ? 0.12 : 0.18) : 0.52;
  const op   = Math.max(0.04, coef * Math.pow(1-k, 1.15));
  base && base.setAttribute('opacity', op.toFixed(3));
}











































