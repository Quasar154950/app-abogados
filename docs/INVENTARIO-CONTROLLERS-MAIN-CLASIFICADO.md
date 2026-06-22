\# Inventario Controllers - main - Clasificado



\## MCTandil / Meteo



\- app/Http/Controllers/MeteoController.php



\## Abogados



\- app/Http/Controllers/ActividadController.php

\- app/Http/Controllers/ClienteController.php

\- app/Http/Controllers/DashboardController.php

\- app/Http/Controllers/ExpedienteController.php

\- app/Http/Controllers/NotaController.php

\- app/Http/Controllers/SaasPagoController.php

\- app/Http/Controllers/SearchController.php

\- app/Http/Controllers/SeguimientoController.php



\## Gimnasios / restos detectados en main



\- app/Http/Controllers/AsistenciaController.php

\- app/Http/Controllers/TurnoController.php



\## Base Laravel



\- app/Http/Controllers/Controller.php



\## Conclusión



La rama main contiene controladores mezclados de MCTandil, Abogados y restos de Gimnasios.



Para futura limpieza de abogados-app, los candidatos claros a extraer o eliminar de Abogados son:



\- MeteoController.php

\- AsistenciaController.php

\- TurnoController.php



No borrar todavía.

Solo documentar.

