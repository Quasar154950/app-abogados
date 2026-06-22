\# Inventario



\## Rama main



Contiene:



\* MCTandil

\* Meteo

\* Abogados

\* Restos de Gimnasio



\## Rama produccion-sana-mp



Contiene:



\* Abogados

\* Mercado Pago SaaS



\## Repo gimnasios-app



Contiene:



\* Socios

\* Turnos

\* Asistencias

\* Cuotas

\* Mercado Pago gimnasios

## Resultado del Inventario Técnico - 21/06/2026

### Rama main

Contiene:
- MCTandil
- Meteo
- Abogados
- Restos de gimnasio: turnos, asistencias y pagos

Archivos de referencia:
- inventario-controllers-main.txt
- inventario-models-main.txt
- inventario-migrations-main.txt
- rutas-main.txt

### Rama produccion-sana-mp

Contiene:
- Abogados
- Suscripción SaaS
- Mercado Pago SaaS

No contiene:
- Meteo
- MCTandil
- Turnos
- Asistencias

Archivos de referencia:
- inventario-controllers-abogados-limpio.txt
- inventario-models-abogados-limpio.txt
- inventario-migrations-abogados-limpio.txt
- rutas-abogados-limpio.txt

## Diferencias detectadas entre main y produccion-sana-mp

### Controllers extra en main

- AsistenciaController.php → Gimnasios
- TurnoController.php → Gimnasios
- MeteoController.php → MCTandil

### Models extra en main

- Asistencia.php → Gimnasios
- Pago.php → Gimnasios
- ReservaTurno.php → Gimnasios
- Turno.php → Gimnasios

### Migraciones extra en main

#### Gimnasios

- 2026_05_15_083722_add_fecha_vencimiento_cuota_to_clientes_table.php
- 2026_05_16_000302_create_pagos_table.php
- 2026_05_16_015948_create_turnos_table.php
- 2026_05_16_020000_create_reserva_turnos_table.php
- 2026_05_18_193211_create_asistencias_table.php

#### SaaS

- 2026_05_24_104039_add_planes_to_users_table.php
- 2026_05_24_125730_create_saas_pagos_table.php
- 2026_05_24_220856_add_checkout_url_to_saas_pagos_table.php
