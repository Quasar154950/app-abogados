\# Arquitectura MCTandil - Junio 2026



\## Servicios Railway



\### MCTandil



\* Repo: Quasar154950/app-abogados

\* Rama: main

\* Dominio: mctandil.com

\* Servicio Railway: app-abogados-production-0b1c



Contiene:



\* Landing MCTandil

\* SaaS

\* Meteo

\* IoT



\---



\### Abogados (Vairo)



\* Repo: Quasar154950/app-abogados

\* Rama producción actual: abogados-produccion-restaurada

\* Rama candidata limpia: produccion-sana-mp

\* Dominio: rare-prosperity-production-a81a.up.railway.app



Contiene:



\* Clientes

\* Expedientes

\* Notas

\* Seguimientos

\* Suscripción SaaS



\---



\### Gimnasios



\* Repo: Quasar154950/gimnasios-app

\* Rama: main

\* Servicio Railway: app-abogados-production.up.railway.app



Contiene:



\* Socios

\* Turnos

\* Asistencias

\* Cuotas

\* Mercado Pago gimnasios



\---



\## Reglas



1\. No desarrollar funcionalidades nuevas hasta terminar el ordenamiento.

2\. No usar la rama main para cambios de abogados.

3\. No mezclar código de gimnasios dentro de abogados.

4\. Antes de cualquier deploy, identificar:



&#x20;  \* servicio Railway

&#x20;  \* rama

&#x20;  \* commit



\---



\## Objetivos de limpieza



\### Etapa 1



\* Documentar todo el ecosistema.



\### Etapa 2



\* Separar MCTandil del repo abogados.



\### Etapa 3



\* Dejar abogados solamente con módulos jurídicos.



\### Etapa 4



\* Retomar nuevas funcionalidades.



