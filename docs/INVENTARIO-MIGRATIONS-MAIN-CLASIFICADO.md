\# Inventario Migrations - main - Clasificado



\## Base Laravel / Auth



\- 0001\_01\_01\_000000\_create\_users\_table.php

\- 0001\_01\_01\_000001\_create\_cache\_table.php

\- 0001\_01\_01\_000002\_create\_jobs\_table.php

\- 2025\_08\_14\_170933\_add\_two\_factor\_columns\_to\_users\_table.php



\## Abogados



\- 2026\_03\_12\_121709\_create\_clientes\_table.php

\- 2026\_03\_17\_031504\_create\_notas\_table.php

\- 2026\_03\_17\_222639\_add\_campos\_to\_seguimientos\_table.php

\- 2026\_03\_17\_224608\_create\_seguimientos\_table.php

\- 2026\_03\_18\_220236\_add\_estado\_y\_fecha\_recordatorio\_to\_seguimientos\_table.php

\- 2026\_03\_20\_095337\_add\_prioridad\_to\_seguimientos\_table.php

\- 2026\_03\_21\_111325\_add\_is\_pinned\_to\_notas\_table.php

\- 2026\_03\_21\_125735\_create\_etiquetas\_table.php

\- 2026\_03\_22\_110721\_add\_archivado\_to\_clientes\_table.php

\- 2026\_03\_23\_211700\_create\_actividades\_table.php

\- 2026\_03\_26\_104913\_create\_media\_table.php

\- 2026\_04\_02\_104217\_create\_expedientes\_table.php

\- 2026\_04\_02\_114431\_add\_expediente\_id\_to\_seguimientos\_table.php

\- 2026\_04\_03\_104627\_update\_expedientes\_table.php

\- 2026\_04\_19\_140751\_add\_role\_and\_cliente\_id\_to\_users\_table.php

\- 2026\_04\_20\_193041\_add\_user\_id\_to\_clientes\_table.php

\- 2026\_04\_23\_225526\_remove\_cliente\_id\_from\_users\_table.php

\- 2026\_04\_30\_222208\_add\_abogado\_id\_to\_clientes\_table.php

\- 2026\_05\_01\_142930\_add\_activo\_to\_users\_table.php

\- 2026\_05\_02\_130231\_add\_fecha\_vencimiento\_to\_users\_table.php

\- 2026\_05\_03\_223923\_add\_branding\_fields\_to\_users\_table.php

\- 2026\_05\_03\_234507\_add\_branding\_fields\_v2\_to\_users\_table.php

\- 2026\_05\_03\_235356\_add\_branding\_fields\_v2\_to\_users\_table.php

\- 2026\_05\_04\_104212\_add\_slug\_estudio\_to\_users\_table.php

\- 2026\_05\_09\_123508\_agregar\_origen\_y\_revision\_a\_media\_table.php

\- 2026\_05\_10\_112257\_create\_mensajes\_cliente\_table.php

\- 2026\_05\_11\_103501\_add\_viewed\_at\_to\_media\_table.php



\## Gimnasios / restos detectados en main



\- 2026\_05\_15\_083722\_add\_fecha\_vencimiento\_cuota\_to\_clientes\_table.php

\- 2026\_05\_16\_000302\_create\_pagos\_table.php

\- 2026\_05\_16\_015948\_create\_turnos\_table.php

\- 2026\_05\_16\_020000\_create\_reserva\_turnos\_table.php

\- 2026\_05\_18\_193211\_create\_asistencias\_table.php



\## SaaS / Suscripción plataforma



\- 2026\_05\_24\_104039\_add\_planes\_to\_users\_table.php

\- 2026\_05\_24\_125730\_create\_saas\_pagos\_table.php

\- 2026\_05\_24\_220856\_add\_checkout\_url\_to\_saas\_pagos\_table.php



\## MCTandil / Meteo



No se detectan migraciones Laravel propias.



Meteo usa bases externas:



\- meteotandil

\- almanaque



\## Conclusión



La rama main contiene migraciones de Abogados, SaaS y restos de Gimnasios.



Para una futura limpieza de abogados-app, los candidatos claros a revisar son:



\- pagos

\- turnos

\- reserva\_turnos

\- asistencias

\- fecha\_vencimiento\_cuota en clientes



No borrar todavía.

Solo documentar.

