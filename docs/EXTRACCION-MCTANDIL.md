\# Extracción MCTandil - Junio 2026



\## Concepto correcto



MCTandil no es solo una landing.



MCTandil es la marca principal y contiene varios brazos:



\- Meteorología / Meteo Tandil

\- IoT

\- SaaS

\- Impresión 3D

\- Cartelería inteligente

\- Contacto institucional



Por lo tanto, Meteo no debe separarse de MCTandil.

Meteo debe salir de abogados-app junto con MCTandil.



\---



\## Objetivo real



Separar MCTandil completo del repositorio abogados-app.



El futuro debería quedar así:



\- mctandil-web

\- mctandil-abogados

\- mctandil-gimnasios



\---



\## MCTandil actual en main



\### Views



\- resources/views/mctandil/home.blade.php

\- resources/views/mctandil/saas.blade.php



\### Meteo views



\- resources/views/meteo/index.blade.php

\- resources/views/meteo/graficas.blade.php

\- resources/views/meteo/pwa.blade.php

\- resources/views/meteo/reporte-pdf.blade.php



\### Controller



\- app/Http/Controllers/MeteoController.php



\### Assets MCTandil



\- public/manifest-mctandil.json

\- public/service-worker-mctandil.js

\- public/images/logo-mctandil-transparent.png

\- public/images/mctandil-background.png



\### Assets Meteo



\- public/meteo-assets/pwa.css

\- public/meteo-assets/pwa.js

\- public/meteo-assets/script.js

\- public/meteo-assets/styles.css

\- public/meteo-assets/icons/icon-192.png

\- public/meteo-assets/icons/icon-512.png

\- public/meteo-assets/img/logo-mctandil.png

\- public/meteo-assets/img/luna\_llena.png

\- public/meteo-assets/img/sol-horizonte.png

\- public/meteo-assets/img/sol-horizonte-transparente.png



\---



\## Rutas MCTandil



\- /

\- /saas

\- /iot

\- /apps

\- /contacto



\## Rutas Meteo



\- /meteo

\- /meteo/app

\- /meteo/graficas

\- /meteo/reporte

\- /meteo/datos

\- /meteo/lectura-actual

\- /meteo/actualizar-astronomia



\---



\## Dependencias de Meteo



MeteoController depende de:



\- PDO

\- PostgreSQL

\- DomPDF

\- Base de datos meteotandil

\- Base de datos almanaque

\- API USNO para astronomía

\- Variables ENV:

&#x20; - METEO\_DB\_HOST

&#x20; - METEO\_DB\_PORT

&#x20; - METEO\_DB\_USERNAME

&#x20; - METEO\_DB\_PASSWORD

&#x20; - METEO\_DB\_DATABASE

&#x20; - ASTRO\_DB\_DATABASE

&#x20; - ASTRO\_TOKEN



No depende de:



\- Clientes

\- Expedientes

\- Usuarios

\- Login

\- Mercado Pago

\- Gimnasios

\- Abogados



\---



\## Receptor ESP32



El archivo PHP que recibe los datos de la ESP32 no está dentro de abogados-app.



La arquitectura correcta es:



ESP32 -> PHP receptor externo -> DB meteotandil



Luego:



MCTandil Web -> lee DB meteotandil



Ese receptor también pertenece al ecosistema MCTandil/Meteo y deberá documentarse cuando se ubique.



\---



\## Qué NO pertenece a MCTandil



No pertenecen a MCTandil:



\- ClienteController

\- ExpedienteController

\- DashboardController

\- NotaController

\- SeguimientoController

\- ActividadController

\- TurnoController

\- AsistenciaController

\- SaasPagoController

\- MercadoPagoSaasWebhookController



Tampoco pertenecen:



\- Clientes

\- Expedientes

\- Notas

\- Seguimientos

\- Turnos

\- Asistencias

\- Cuotas

\- Suscripción SaaS interna

\- Panel soporte



\---



\## Conclusión



MCTandil + Meteo están bastante desacoplados de Abogados.



La separación parece de dificultad baja/media.



El error histórico fue alojar MCTandil dentro de abogados-app.



La prioridad es extraer MCTandil completo a un repositorio propio antes de seguir agregando nuevas ramas como IoT, impresión 3D o cartelería inteligente.



\---



\## No hacer todavía



\- No borrar archivos

\- No mover carpetas

\- No tocar Railway

\- No cambiar dominios

\- No hacer deploy

\- No tocar Mercado Pago

\- No tocar producción

