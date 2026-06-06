console.log("AgroControl demo iniciada");

document.addEventListener("DOMContentLoaded", () => {

  const $ = (id) => document.getElementById(id);

  const el = {
    soilTemp: $("soilTempValue"),
    soilHum: $("soilHumValue"),
    ph: $("phValue"),
    ec: $("ecValue"),
    sal: $("salValue"),
    tds: $("tdsValue"),
    n: $("nValue"),
    p: $("pValue"),
    k: $("kValue"),

    upd: $("updatedAt"),
    connText: $("connText"),
    connDot: $("connDot"),
    stationSub: $("stationSub"),
    weatherIcon: $("weatherIcon")
  };

  function num(v, d = 1) {
    const n = Number(v);
    return Number.isFinite(n) ? n.toFixed(d) : "--";
  }

  function randomBetween(min, max, decimals = 1) {
    return Number(
      (Math.random() * (max - min) + min).toFixed(decimals)
    );
  }

  function generarDatosDemo() {
    return {
      temperatura_suelo: randomBetween(16, 27, 1),
      humedad_suelo: randomBetween(35, 78, 0),
      ph: randomBetween(5.3, 7.2, 1),
      electroconductividad: randomBetween(450, 1800, 0),
      salinidad: randomBetween(180, 820, 0),
      tds: randomBetween(300, 1200, 0),
      nitrogeno: randomBetween(25, 95, 0),
      fosforo: randomBetween(8, 45, 0),
      potasio: randomBetween(80, 260, 0)
    };
  }

  function render(data) {
    if (el.soilTemp) el.soilTemp.textContent = num(data.temperatura_suelo, 1);
    if (el.soilHum) el.soilHum.textContent = num(data.humedad_suelo, 0);

    if (el.ph) el.ph.textContent = num(data.ph, 1);
    if (el.ec) el.ec.textContent = num(data.electroconductividad, 0);
    if (el.sal) el.sal.textContent = num(data.salinidad, 0);
    if (el.tds) el.tds.textContent = num(data.tds, 0);

    if (el.n) el.n.textContent = num(data.nitrogeno, 0);
    if (el.p) el.p.textContent = num(data.fosforo, 0);
    if (el.k) el.k.textContent = num(data.potasio, 0);

    if (el.connText) el.connText.textContent = "Demo online";
    if (el.connDot) el.connDot.style.background = "limegreen";

    if (el.stationSub) {
      el.stationSub.textContent = "Demo SaaS · Monitoreo de suelo agrícola";
    }

    if (el.weatherIcon) el.weatherIcon.textContent = "🌱";

    if (el.upd) {
      const d = new Date();
      el.upd.textContent =
        `Última actualización demo: ${d.toLocaleString("es-AR", { hour12: false })}`;
    }
  }

  function cargarDemo() {
    const data = generarDatosDemo();
    render(data);
  }

  cargarDemo();
  setInterval(cargarDemo, 10000);

  /* ===== SPLASH ===== */

  const loadingText = document.getElementById("loadingText");

  const loadingMessages = [
    "Inicializando sensores...",
    "Verificando humedad del suelo...",
    "Analizando pH y nutrientes...",
    "Cargando panel AgroControl..."
  ];

  let loadingIndex = 0;

  const loadingInterval = setInterval(() => {
    if (!loadingText) return;

    loadingIndex = (loadingIndex + 1) % loadingMessages.length;
    loadingText.textContent = loadingMessages[loadingIndex];
  }, 700);

  setTimeout(() => {
    const splash = document.getElementById("splash-screen");

    clearInterval(loadingInterval);

    if (splash) {
      splash.classList.add("splash-hidden");

      setTimeout(() => {
        splash.remove();
      }, 800);
    }
  }, 3000);

});









































