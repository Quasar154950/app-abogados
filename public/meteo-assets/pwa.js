console.log("MCTandil PWA iniciada");

const API_URL = "/meteo/datos";

function setValue(id, value) {
    const el = document.getElementById(id);

    if (!el) return;

    el.textContent =
        value === null ||
        value === undefined ||
        value === ""
            ? "--"
            : value;
}

function ocultarLoader() {

    const loader = document.getElementById("appLoader");

    if (!loader) return;

    const step1 = document.getElementById("loadStep1");
    const step2 = document.getElementById("loadStep2");
    const step3 = document.getElementById("loadStep3");
    const step4 = document.getElementById("loadStep4");

    setTimeout(() => {
        step1.textContent = "✅ Estación conectada";
        step1.classList.add("done");
    }, 300);

    setTimeout(() => {
        step2.textContent = "✅ Astronomía cargada";
        step2.classList.add("done");
    }, 700);

    setTimeout(() => {
        step3.textContent = "✅ Meteorología actualizada";
        step3.classList.add("done");
    }, 1100);

    setTimeout(() => {
        step4.style.opacity = "1";
        step4.classList.add("done");
    }, 1500);

    setTimeout(() => {
        loader.classList.add("hidden");
    }, 2200);
}

function parseTimestamp(ts) {
    if (!ts) return NaN;

    let s = String(ts).trim();

    if (s.includes(".")) {
        s = s.split(".")[0];
    }

    s = s.replace(" ", "T");

    return Date.parse(s);
}

function actualizarEstado(tiempo) {

    const connText = document.getElementById("connText");
    const statusDot = document.getElementById("statusDot");
    const updatedAt = document.getElementById("updatedAt");

    const ms = parseTimestamp(tiempo);

    if (Number.isNaN(ms)) {

        connText.textContent = "🟠 Datos recibidos";
        statusDot.style.background = "orange";
        updatedAt.textContent = "Última actualización: " + tiempo;

        return;
    }

    const diffMin = (Date.now() - ms) / 60000;

    if (diffMin >= 10) {

        connText.textContent = "🔴 Estación sin enviar";
        statusDot.style.background = "crimson";

    } else if (diffMin >= 2) {

        connText.textContent = "🟠 Datos atrasados";
        statusDot.style.background = "orange";

    } else {

        connText.textContent = "🟢 Estación conectada";
        statusDot.style.background = "limegreen";
    }

    const fecha = new Date(ms).toLocaleString("es-AR", {
        hour12: false
    });

    updatedAt.textContent =
        "Última actualización: " + fecha;
}

async function cargarDatos() {

    try {

        const res = await fetch(API_URL, {
            cache: "no-store"
        });

        const data = await res.json();

        if (!data.ok) return;

        // Meteorología
        setValue("temperatura", data.temperatura);
        setValue("sensacion_termica", data.sensacion_termica);
        setValue("humedad", data.humedad);
        setValue("punto_rocio", data.punto_rocio);
        setValue("presion", data.presion);
        setValue("techo_nubes", data.techo_nubes);

        setValue("direccion_viento", data.direccion_viento);
        setValue("angulo_viento", data.angulo_viento);
        setValue("velocidad_viento", data.velocidad_viento);
        setValue("rafagas_viento", data.rafagas_viento);

        setValue("lluvia_acumulada", data.lluvia_acumulada);
        setValue("intensidad_lluvia_maxima_diaria", data.intensidad_lluvia_maxima_diaria);
        setValue("intensidad_lluvia_promedio_diaria", data.intensidad_lluvia_promedio_diaria);

        setValue("indice_uv", data.indice_uv);
        setValue("uv_peligrosidad", data.uv_peligrosidad);
        setValue("luminosidad", data.luminosidad);

        // GPS
        setValue("latitud", data.latitud);
        setValue("longitud", data.longitud);
        setValue("altitud", data.altitud);

        // Astronomía
        setValue("alba", data.alba);
        setValue("salida_sol", data.salida_sol);
        setValue("puesta_sol", data.puesta_sol);
        setValue("anochecer", data.anochecer);
        setValue("duracion_dia", data.duracion_dia);

        setValue("salida_luna", data.salida_luna);
        setValue("puesta_luna", data.puesta_luna);
        setValue("fase_lunar", data.fase_lunar);
        setValue("ciclo_lunar", data.ciclo_lunar);
        setValue("visibilidad_lunar", data.visibilidad_lunar);

        actualizarEstado(data.tiempo);

        ocultarLoader();

    } catch (e) {

        document.getElementById("connText").textContent =
            "🔴 Sin conexión";

        document.getElementById("statusDot").style.background =
            "crimson";
    }
}

cargarDatos();
setInterval(cargarDatos, 10000);















































