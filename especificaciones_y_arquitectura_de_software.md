# Especificación de Arquitectura de Software y Requerimientos
## Sistema de Gestión Hotelera - Hoteles Decameron

---

## 1. Visión General del Proyecto

El sistema tiene como objetivo permitir a la Dirección de Operaciones Hoteleras administrar la información de los hoteles de la compañía, sus datos tributarios básicos y la asignación de inventario de habitaciones según reglas estrictas de tipo de habitación y acomodación.

---

## 2. Requerimientos Funcionales y Reglas de Negocio

### 2.1. Gestión de Hoteles
- **Campos obligatorios**: Nombre, Dirección, Ciudad, NIT, Número Máximo de Habitaciones.
- **Validación de unicidad**: No pueden existir dos hoteles con el mismo **NIT** o el mismo **Nombre**.

### 2.2. Asignación de Habitaciones y Acomodaciones
- **Tipos de Habitación permitidos**: `Estándar`, `Junior`, `Suite`.
- **Acomodaciones permitidas**: `Sencilla`, `Doble`, `Triple`, `Cuádruple`.
- **Matriz de Compatibilidad Estricta**:
  - `Estándar` $\rightarrow$ `Sencilla` | `Doble`
  - `Junior` $\rightarrow$ `Triple` | `Cuádruple`
  - `Suite` $\rightarrow$ `Sencilla` | `Doble` | `Triple`
- **Reglas de Integridad**:
  1. **Límite de Habitaciones**: La suma de la cantidad de habitaciones configuradas para un hotel **no puede superar** el número máximo de habitaciones registradas en la cabecera del hotel.
  2. **No Duplicidad de Combinación**: No se permite repetir la misma combinación de `(Tipo de Habitación, Acomodación)` para el mismo hotel.

### 2.3. Catálogos
- Las ciudades, tipos de habitación y acomodaciones son estáticos y precargados vía Seeders/Migrations. No requieren módulo CRUD administrativo.

---

## 3. Arquitectura Técnica y Stack Tecnológico

### 3.1. Stack Tecnológico
- **Backend**: PHP 8.2+ / Laravel 11+
- **Frontend**: React 18+ / Vite / Tailwind CSS
- **Base de Datos**: PostgreSQL 15+
- **Testing**: PHPUnit / Pest (Backend), Jest / React Testing Library (Frontend)
- **CI/CD**: GitHub Actions
- **Nube**: Render / Railway / AWS (Instancia pública HTTPS)

### 3.2. Arquitectura Backend (Laravel - Clean Architecture / Service-Repository Pattern)

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── HotelController.php
│   │   │   ├── HotelRoomController.php
│   │   │   └── CatalogController.php
│   ├── Requests/
│   │   ├── StoreHotelRequest.php
│   │   ├── UpdateHotelRequest.php
│   │   └── StoreHotelRoomRequest.php
│   └── Resources/
│       ├── HotelResource.php
│       └── RoomAssignmentResource.php
├── Models/
│   ├── Hotel.php
│   ├── RoomType.php
│   ├── Accommodation.php
│   └── HotelRoom.php
├── Services/
│   ├── HotelService.php
│   └── RoomAssignmentService.php
└── Repositories/
    ├── Contracts/
    │   ├── HotelRepositoryInterface.php
    │   └── RoomAssignmentRepositoryInterface.php
    └── Eloquent/
        ├── HotelRepository.php
        └── RoomAssignmentRepository.php
```

### 3.3. Arquitectura Frontend (React - Component-Based Architecture)

```
src/
├── components/
│   ├── common/
│   │   ├── Navbar.jsx
│   │   ├── Modal.jsx
│   │   └── Alert.jsx
│   ├── hotel/
│   │   ├── HotelList.jsx
│   │   ├── HotelForm.jsx
│   │   └── HotelCard.jsx
│   └── room/
│       ├── RoomAssignmentForm.jsx
│       └── RoomDistributionTable.jsx
├── services/
│   └── api.js
├── hooks/
│   ├── useHotels.js
│   └── useCatalogs.js
└── utils/
    └── validators.js
```

---

## 4. Diseño de Base de Datos (PostgreSQL)

### 4.1. Esquema Entidad-Relación (DDL SQL)

```sql
-- Tabla de Ciudades (Catálogo)
CREATE TABLE cities (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de Tipos de Habitación (Catálogo)
CREATE TABLE room_types (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

-- Tabla de Acomodaciones (Catálogo)
CREATE TABLE accommodations (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

-- Tabla de Matriz de Reglas (Compatibilidad Tipo-Acomodación)
CREATE TABLE room_type_accommodations (
    id SERIAL PRIMARY KEY,
    room_type_id INT REFERENCES room_types(id) ON DELETE CASCADE,
    accommodation_id INT REFERENCES accommodations(id) ON DELETE CASCADE,
    UNIQUE(room_type_id, accommodation_id)
);

-- Tabla Principal de Hoteles
CREATE TABLE hotels (
    id SERIAL PRIMARY KEY,
    name VARCHAR(150) UNIQUE NOT NULL,
    address VARCHAR(200) NOT NULL,
    city_id INT REFERENCES cities(id),
    nit VARCHAR(20) UNIQUE NOT NULL,
    max_rooms INT NOT NULL CHECK (max_rooms > 0),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de Asignación de Habitaciones por Hotel
CREATE TABLE hotel_rooms (
    id SERIAL PRIMARY KEY,
    hotel_id INT REFERENCES hotels(id) ON DELETE CASCADE,
    room_type_id INT REFERENCES room_types(id),
    accommodation_id INT REFERENCES accommodations(id),
    quantity INT NOT NULL CHECK (quantity > 0),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT unique_hotel_room_accommodation UNIQUE (hotel_id, room_type_id, accommodation_id)
);
```

---

## 5. Endpoints API RESTful

| Método | Endpoint | Descripción | Body (JSON) |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/v1/hotels` | Obtener listado de hoteles con sus habitaciones | N/A |
| `POST` | `/api/v1/hotels` | Registrar un nuevo hotel | `{ "name": "...", "address": "...", "city_id": 1, "nit": "123-4", "max_rooms": 50 }` |
| `GET` | `/api/v1/hotels/{id}` | Obtener detalle de un hotel específico | N/A |
| `POST` | `/api/v1/hotels/{id}/rooms` | Asignar habitaciones a un hotel | `{ "room_type_id": 1, "accommodation_id": 2, "quantity": 10 }` |
| `GET` | `/api/v1/catalogs` | Obtener catálogos (Ciudades, Tipos, Acomodaciones y Reglas) | N/A |

---

## 6. Pruebas Unitarias y de Integración

### 6.1. Casos de Prueba Backend (`tests/Feature/HotelRoomTest.php`)
1. **Validar inserción exitosa** cuando la acomodación corresponde al tipo de habitación.
2. **Rechazar inserción (HTTP 422)** cuando el tipo de habitación y acomodación sean incompatibles (Ej: `Estándar` + `Triple`).
3. **Rechazar inserción (HTTP 422)** cuando la suma acumulada de habitaciones supere `max_rooms`.
4. **Rechazar registros duplicados** de la combinación `(room_type_id, accommodation_id)` para el mismo hotel.

---

## 7. Integración Continua (CI Pipeline - GitHub Actions)

Fichero: `.github/workflows/ci.yml`

```yaml
name: Continuous Integration

on:
  push:
    branches: [ main, develop ]
  pull_request:
    branches: [ main, develop ]

jobs:
  backend-tests:
    runs-on: ubuntu-latest
    services:
      postgres:
        image: postgres:15
        env:
          POSTGRES_DB: decameron_test
          POSTGRES_USER: postgres
          POSTGRES_PASSWORD: secretpassword
        ports:
          - 5432:5432
        options: >-
          --health-cmd pg_isready
          --health-interval 10s
          --health-timeout 5s
          --health-retries 5

    steps:
      - uses: actions/checkout@v3

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          extensions: mbstring, pdo, pdo_pgsql

      - name: Install Backend Dependencies
        run: composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist
        working-directory: ./backend

      - name: Run Backend Tests
        env:
          DB_CONNECTION: pgsql
          DB_HOST: 127.0.0.1
          DB_PORT: 5432
          DB_DATABASE: decameron_test
          DB_USERNAME: postgres
          DB_PASSWORD: secretpassword
        run: ./vendor/bin/phpunit
        working-directory: ./backend

  frontend-tests:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3

      - name: Setup Node.js
        uses: actions/setup-node@v3
        with:
          node-version: 18

      - name: Install Frontend Dependencies
        run: npm ci
        working-directory: ./frontend

      - name: Run Frontend Tests
        run: npm run test
        working-directory: ./frontend
```

---

## 8. Guía Paso a Paso de Instalación y Despliegue Local ("Paso a Paso Fácil")

### Requisitos Previos Necesarios
- Docker Desktop instalado y corriendo (o PHP 8.2, Composer, Node.js 18 y PostgreSQL de forma nativa).

### Paso 1: Clonar el Repositorio
```bash
git clone https://github.com/tu-usuario/prueba-decameron.git
cd prueba-decameron
```

### Paso 2: Configurar y Desplegar Backend (Laravel)
1. Ingrese a la carpeta del backend:
   ```bash
   cd backend
   ```
2. Copie el archivo de entorno:
   ```bash
   cp .env.example .env
   ```
3. Instale las dependencias de PHP:
   ```bash
   composer install
   ```
4. Genere la clave de la aplicación:
   ```bash
   php artisan key:generate
   ```
5. Configure la base de datos en el archivo `.env`:
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=decameron_db
   DB_USERNAME=postgres
   DB_PASSWORD=tu_clave
   ```
6. Ejecute las migraciones y Seeders de los catálogos:
   ```bash
   php artisan migrate --seed
   ```
7. Inicie el servidor backend:
   ```bash
   php artisan serve
   ```

### Paso 3: Configurar y Desplegar Frontend (React)
1. Abrir otra terminal e ingresar a la carpeta del frontend:
   ```bash
   cd frontend
   ```
2. Copiar el archivo de variables de entorno:
   ```bash
   cp .env.example .env
   ```
3. Instalar dependencias de Node.js:
   ```bash
   npm install
   ```
4. Iniciar el servidor de desarrollo:
   ```bash
   npm run dev
   ```
5. Abrir el navegador en `http://localhost:5173`.

---

## 9. Dump de Base de Datos de Prueba (`database_dump.sql`)

```sql
-- Dump inicial con datos de prueba y catálogos requeridos

INSERT INTO cities (id, name) VALUES 
(1, 'CARTAGENA'),
(2, 'SAN ANDRES'),
(3, 'SANTA MARTA'),
(4, 'BOGOTA');

INSERT INTO room_types (id, name) VALUES 
(1, 'ESTANDAR'),
(2, 'JUNIOR'),
(3, 'SUITE');

INSERT INTO accommodations (id, name) VALUES 
(1, 'SENCILLA'),
(2, 'DOBLE'),
(3, 'TRIPLE'),
(4, 'CUADRUPLE');

-- Matriz de Compatibilidad
INSERT INTO room_type_accommodations (room_type_id, accommodation_id) VALUES 
(1, 1), -- ESTANDAR -> SENCILLA
(1, 2), -- ESTANDAR -> DOBLE
(2, 3), -- JUNIOR -> TRIPLE
(2, 4), -- JUNIOR -> CUADRUPLE
(3, 1), -- SUITE -> SENCILLA
(3, 2), -- SUITE -> DOBLE
(3, 3); -- SUITE -> TRIPLE

-- Hotel de Ejemplo
INSERT INTO hotels (id, name, address, city_id, nit, max_rooms) VALUES 
(1, 'DECAMERON CARTAGENA', 'CALLE 23 58-25', 1, '12345678-9', 42);

-- Asignación de Ejemplo
INSERT INTO hotel_rooms (hotel_id, room_type_id, accommodation_id, quantity) VALUES 
(1, 1, 1, 25), -- 25 ESTANDAR SENCILLA
(1, 2, 3, 12), -- 12 JUNIOR TRIPLE
(1, 1, 2, 5);  -- 5 ESTANDAR DOBLE
```