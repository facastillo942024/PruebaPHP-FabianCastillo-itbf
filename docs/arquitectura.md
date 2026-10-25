# Arquitectura

## Visión general

El sistema está compuesto por dos aplicaciones **desacopladas** que se comunican
exclusivamente por una API RESTful en formato JSON.

```mermaid
flowchart LR
    subgraph Cliente["Navegador (Chrome / Firefox)"]
        UI["React 18 + Vite + Tailwind"]
    end

    subgraph API["Backend Laravel 11"]
        direction TB
        C["Controllers (Api)"]
        FR["Form Requests (validación)"]
        S["Services (reglas de negocio)"]
        R["Repositories (contratos + Eloquent)"]
        RES["API Resources (JSON)"]
    end

    DB[("PostgreSQL")]

    UI -- "HTTP /api/v1 (JSON)" --> C
    C --> FR
    C --> S
    S --> R
    R --> DB
    C --> RES
    RES -- "JSON" --> UI
```

## Patrón Service–Repository (backend)

La lógica se separa en capas con responsabilidades claras (SOLID):

- **Controller:** recibe la petición HTTP y delega. No contiene lógica de negocio.
- **Form Request:** valida el formato de entrada (tipos, campos obligatorios,
  unicidad simple).
- **Service:** contiene las reglas de negocio del dominio. `RoomAssignmentService`
  concentra las cuatro reglas de asignación de habitaciones.
- **Repository:** abstrae el acceso a datos tras una interfaz. El `Service`
  depende de la **interfaz**, no de Eloquent (Inversión de Dependencias). Cambiar
  de ORM solo requeriría una nueva implementación del contrato.
- **Resource:** transforma los modelos en la respuesta JSON pública.

## Flujo de una asignación de habitaciones

```mermaid
sequenceDiagram
    participant U as React (UI)
    participant C as HotelRoomController
    participant V as StoreHotelRoomRequest
    participant S as RoomAssignmentService
    participant R as RoomAssignmentRepository
    participant DB as PostgreSQL

    U->>C: POST /hotels/{id}/rooms
    C->>V: validar formato
    V-->>C: datos válidos
    C->>S: assign(hotel, datos)
    S->>DB: BEGIN + lockForUpdate(hotel)
    S->>R: ¿combinación compatible? (matriz)
    S->>R: ¿combinación duplicada?
    S->>R: ¿supera max_rooms?
    alt alguna regla falla
        S-->>C: BusinessRuleException
        C-->>U: HTTP 422 + errores
    else todo correcto
        S->>R: create(asignación)
        R->>DB: INSERT + COMMIT
        S-->>C: HotelRoom
        C-->>U: HTTP 201 + recurso
    end
```

## Decisiones clave

- **Validación en dos niveles:** el `Form Request` cubre el formato; el `Service`
  cubre las reglas de negocio que necesitan contexto (estado del hotel, matriz).
- **Transacción con bloqueo:** la verificación de `max_rooms` y la inserción
  ocurren dentro de una transacción con `lockForUpdate` para evitar que dos
  peticiones simultáneas excedan el límite.
- **Respuestas JSON garantizadas:** cualquier error bajo `/api/*` se renderiza
  como JSON, nunca como HTML, para mantener la API predecible.
