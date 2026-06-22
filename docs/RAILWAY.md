\# Railway



\## MCTandil



\* Repo: app-abogados

\* Rama: main

\* Dominio: mctandil.com

\* Servicio Railway: app-abogados-production-0b1c



\## Abogados



\* Repo: app-abogados

\* Rama: abogados-produccion-restaurada

\* Dominio: rare-prosperity-production-a81a.up.railway.app



\## Gimnasios



\* Repo: gimnasios-app

\* Rama: main

\* Dominio: app-abogados-production.up.railway.app



\## Observaciones



\* No usar la rama main para cambios de abogados.

\* Verificar siempre rama y servicio antes de deploy.



\## Auditoría Railway - 21/06/2026



\### Servicio: MCTandil



Nombre Railway:

app-abogados-production-0b1c



Repositorio:

app-abogados



Rama:

main



Dominio:

mctandil.com



Función real:



\* Landing MCTandil

\* Meteo

\* SaaS

\* Demos



\### Servicio: Abogados



Nombre Railway:

rare-prosperity



Repositorio:

app-abogados



Rama:

abogados-produccion-restaurada



Dominio:

rare-prosperity-production-a81a.up.railway.app



Función real:



\* Sistema abogados

\* Vairo

\* Clientes

\* Expedientes

\* Seguimientos



\### Servicio: Gimnasios



Nombre Railway:

app-abogados-production



Repositorio:

gimnasios-app



Rama:

main



Dominio:

app-abogados-production.up.railway.app



Función real:



\* Sistema gimnasios

\* Socios

\* Turnos

\* Asistencias

\* Cuotas



\## Problemas detectados



\* Nombres Railway no representan la función real.

\* Dos servicios distintos contienen "app-abogados" en el nombre.

\* Genera confusión al desplegar.



\## Renombres futuros sugeridos



MCTandil:



\* mctandil-web



Abogados:



\* abogados-vairo



Gimnasios:



\* gimnasios-saas



