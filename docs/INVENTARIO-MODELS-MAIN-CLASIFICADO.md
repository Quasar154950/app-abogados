\# Inventario Models - main - Clasificado



\## MCTandil / Meteo



No se detectan Models Laravel propios.



Meteo usa PDO directo desde:



\- app/Http/Controllers/MeteoController.php



Bases externas:



\- meteotandil

\- almanaque



\## Abogados



\- app/Models/Actividad.php

\- app/Models/Cliente.php

\- app/Models/Etiqueta.php

\- app/Models/Expediente.php

\- app/Models/MensajeCliente.php

\- app/Models/Nota.php

\- app/Models/SaasPago.php

\- app/Models/Seguimiento.php

\- app/Models/User.php



\## Gimnasios / restos detectados en main



\- app/Models/Asistencia.php

\- app/Models/Pago.php

\- app/Models/ReservaTurno.php

\- app/Models/Turno.php



\## Conclusión



La rama main contiene Models de Abogados y restos de Gimnasios.



MCTandil/Meteo no depende de Models Laravel propios.



Para futura limpieza de abogados-app, los candidatos claros a extraer o eliminar son:



\- Asistencia.php

\- Pago.php

\- ReservaTurno.php

\- Turno.php



No borrar todavía.

Solo documentar.

