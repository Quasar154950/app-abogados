\# Auditoría Dashboard main - 22/06/2026



\## Estado detectado



El dashboard de la rama main no está limpio para Abogados.



Actualmente mezcla lógica de:



\- Abogados

\- Gimnasios



\## DashboardController.php



Importa Models de Gimnasios:



\- App\\Models\\ReservaTurno

\- App\\Models\\Asistencia



Calcula métricas de Gimnasios:



\- reservasHoy

\- cuposOcupadosHoy

\- pagosPendientes

\- presentesAhora

\- sociosActivos



También usa campo de gimnasio/cuota:



\- fecha\_vencimiento\_cuota en clientes



\## dashboard.blade.php



La vista actual está orientada a Gimnasios.



Textos detectados:



\- Panel del Gimnasio

\- Socios activos

\- Reservas de hoy

\- Cupos ocupados

\- Pagos pendientes

\- Presentes ahora

\- Actividades del gimnasio

\- Spinning

\- Pilates

\- Musculación

\- Ver socios

\- Nuevo socio

\- Turnos / Reservas

\- Asistencias



\## Clasificación



\- app/Http/Controllers/DashboardController.php → HÍBRIDO

\- resources/views/dashboard.blade.php → GIMNASIOS / no apto para Abogados limpio



\## Impacto



Para dejar abogados-app limpio, el dashboard debe reconstruirse o restaurarse a versión jurídica.



Candidatos a eliminar del dashboard de Abogados:



\- ReservaTurno

\- Asistencia

\- reservasHoy

\- cuposOcupadosHoy

\- pagosPendientes

\- presentesAhora

\- sociosActivos

\- textos de gimnasio



\## No hacer todavía



\- No modificar dashboard

\- No borrar rutas

\- No eliminar Models

\- No tocar migraciones

\- No hacer deploy



Solo documentar.

