# Modelo Entidad-Relación

Diagrama de la base de datos del Sistema de Gestión Hotelera Decameron.

```mermaid
erDiagram
    cities ||--o{ hotels : "ubica"
    hotels ||--o{ hotel_rooms : "tiene"
    room_types ||--o{ hotel_rooms : "clasifica"
    accommodations ||--o{ hotel_rooms : "define"
    room_types ||--o{ room_type_accommodations : "permite"
    accommodations ||--o{ room_type_accommodations : "permite"

    cities {
        int id PK
        varchar name
        timestamp created_at
    }

    room_types {
        int id PK
        varchar name UK "ESTANDAR, JUNIOR, SUITE"
    }

    accommodations {
        int id PK
        varchar name UK "SENCILLA, DOBLE, TRIPLE, CUADRUPLE"
    }

    room_type_accommodations {
        int id PK
        int room_type_id FK
        int accommodation_id FK
    }

    hotels {
        int id PK
        varchar name UK "único"
        varchar address
        int city_id FK
        varchar nit UK "único"
        int max_rooms "CHECK > 0"
        timestamp created_at
        timestamp updated_at
    }

    hotel_rooms {
        int id PK
        int hotel_id FK
        int room_type_id FK
        int accommodation_id FK
        int quantity "CHECK > 0"
        timestamp created_at
        timestamp updated_at
    }
```

## Notas de diseño

- **`room_type_accommodations`** es la matriz de compatibilidad. Modelar las
  reglas como datos (y no como código) permite cambiarlas sin tocar la lógica
  (principio Open/Closed).
- **`hotels.name`** y **`hotels.nit`** tienen índice único: no hay hoteles
  repetidos.
- **`hotel_rooms`** tiene un índice único compuesto
  `(hotel_id, room_type_id, accommodation_id)`: no se repite una combinación
  en el mismo hotel.
- Los `CHECK (quantity > 0)` y `CHECK (max_rooms > 0)` protegen la integridad
  a nivel de base de datos.
- La regla "la suma de habitaciones no supera `max_rooms`" se valida en la capa
  de servicio (`RoomAssignmentService`) porque es un cálculo agregado por hotel,
  dentro de una transacción con bloqueo de fila para evitar condiciones de
  carrera.
