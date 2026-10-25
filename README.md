# 🏨 Sistema de Gestión Hotelera — Decameron

Aplicación web para que la Dirección de Operaciones Hoteleras administre los
hoteles de la compañía, sus datos tributarios y la asignación de habitaciones
por tipo y acomodación, con validación estricta de las reglas de negocio.

Proyecto de prueba técnica. Backend y frontend **desacoplados**, API **RESTful**.

---

## 🧱 Stack tecnológico

| Capa        | Tecnología                                   |
| ----------- | -------------------------------------------- |
| Backend     | PHP 8.2 · Laravel 11                         |
| Frontend    | React 18 · Vite 5 · Tailwind CSS 3           |
| Base de datos | PostgreSQL 15+                             |
| Pruebas     | PHPUnit (backend) · Vitest (frontend)        |
| Arquitectura | Service–Repository (SOLID) en el backend    |

---

## 📂 Estructura del repositorio

```
.
├── backend/              # API Laravel 11 (PHP)
│   ├── app/
│   │   ├── Http/         # Controllers, Requests, Resources
│   │   ├── Models/       # Eloquent (Hotel, RoomType, Accommodation, HotelRoom, City)
│   │   ├── Repositories/ # Contracts + implementaciones Eloquent
│   │   └── Services/     # Reglas de negocio (HotelService, RoomAssignmentService)
│   ├── database/
│   │   ├── migrations/   # Esquema de la base de datos
│   │   └── seeders/      # Catálogos precargados
│   └── tests/Feature/    # Pruebas de integración de la API
├── frontend/             # SPA React (Vite + Tailwind)
│   └── src/
│       ├── components/   # common / hotel / room
│       ├── hooks/        # useHotels, useCatalogs
│       ├── services/     # Cliente axios de la API
│       └── utils/        # Validadores
├── database_dump.sql     # Dump idempotente listo para instalar
├── docs/                 # Diagramas (UML entidad-relación y arquitectura)
└── README.md             # Este archivo
```

---

## 📏 Reglas de negocio

- **Tipos de habitación:** Estándar, Junior, Suite.
- **Acomodaciones:** Sencilla, Doble, Triple, Cuádruple.
- **Matriz de compatibilidad (estricta):**

  | Tipo     | Acomodaciones permitidas        |
  | -------- | ------------------------------- |
  | Estándar | Sencilla, Doble                 |
  | Junior   | Triple, Cuádruple               |
  | Suite    | Sencilla, Doble, Triple         |

- La suma de habitaciones asignadas **no puede superar** el máximo del hotel.
- No pueden existir **hoteles repetidos** (NIT y Nombre son únicos).
- No se puede **repetir la combinación** (tipo, acomodación) en un mismo hotel.
- Los catálogos (ciudades, tipos, acomodaciones) vienen **precargados**, sin CRUD.

---

## 🧩 Endpoints de la API

Base: `http://localhost:8000/api/v1`

| Método | Endpoint                 | Descripción                                   |
| ------ | ------------------------ | --------------------------------------------- |
| GET    | `/catalogs`              | Ciudades, tipos, acomodaciones y matriz       |
| GET    | `/hotels`                | Listar hoteles con sus habitaciones           |
| POST   | `/hotels`                | Registrar un hotel                            |
| GET    | `/hotels/{id}`           | Detalle de un hotel                           |
| PUT    | `/hotels/{id}`           | Actualizar un hotel                           |
| DELETE | `/hotels/{id}`           | Eliminar un hotel                             |
| POST   | `/hotels/{id}/rooms`     | Asignar habitaciones a un hotel               |

Los errores de validación y de negocio responden **HTTP 422** con el formato:

```json
{
  "message": "La cantidad supera el máximo de habitaciones del hotel. Disponibles: 17 de 42.",
  "errors": { "quantity": ["La cantidad supera el máximo de habitaciones del hotel. Disponibles: 17 de 42."] }
}
```

---

## 🚀 Instalación paso a paso (guía fácil)

> La idea es que, siguiendo estos pasos **en orden**, cualquier persona pueda
> dejar la aplicación funcionando aunque nunca haya programado. Copie y pegue
> cada comando tal cual.

### Lo que necesita tener instalado antes

1. **PHP 8.2** y **Composer** → suele venir con [XAMPP](https://www.apachefriends.org/).
2. **Node.js 18 o superior** → [descargar aquí](https://nodejs.org/).
3. **PostgreSQL 15 o superior** → [descargar aquí](https://www.postgresql.org/download/).

> 💡 Para comprobar que están instalados, abra una terminal y escriba:
> `php -v`, `composer -V`, `node -v` y `psql --version`.
> Si cada uno responde con un número de versión, va bien.

---

### Paso 1 · Descargar el proyecto

```bash
git clone <URL-del-repositorio>
cd PruebaPHP-FabianCastillo-itbf
```

### Paso 2 · Crear la base de datos

Abra la terminal de PostgreSQL y cree la base de datos vacía:

```bash
createdb -U postgres decameron_db
```

> Si `createdb` le pide contraseña, escriba la que configuró al instalar
> PostgreSQL. Si el comando no existe, abra **pgAdmin** y cree una base de
> datos llamada `decameron_db` con clic derecho → *Create* → *Database*.

### Paso 3 · Cargar los datos de ejemplo (opcional pero recomendado)

El proyecto incluye un dump listo para instalar:

```bash
psql -U postgres -d decameron_db -f database_dump.sql
```

> Esto deja la base con los catálogos y un hotel de ejemplo. Si prefiere
> empezar vacío y dejar que Laravel cree todo, **sáltese este paso** (el Paso 5
> lo hará por usted).

### Paso 4 · Configurar y arrancar el BACKEND (Laravel)

```bash
cd backend
composer install
copy .env.example .env        # en Mac/Linux: cp .env.example .env
php artisan key:generate
```

Abra el archivo `backend/.env` y confirme que los datos de conexión coinciden
con su PostgreSQL (ajuste `DB_PORT` y `DB_PASSWORD` si hace falta):

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=decameron_db
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

> ⚠️ **Nota sobre el puerto:** el valor estándar de PostgreSQL es `5432`. Si al
> instalar PostgreSQL ya tenía otra instancia ocupando ese puerto, el instalador
> pudo asignar `5433` (fue el caso en el equipo de desarrollo original). Si la
> conexión falla, revise el puerto real de su PostgreSQL y ajústelo en `.env`.

Cree las tablas y cargue los catálogos (haga esto **solo si NO ejecutó el Paso 3**):

```bash
php artisan migrate --seed
```

Arranque el servidor del backend:

```bash
php artisan serve
```

Déjelo corriendo. La API quedará en **http://localhost:8000**.

### Paso 5 · Configurar y arrancar el FRONTEND (React)

Abra **otra terminal** (deje la anterior corriendo) y ejecute:

```bash
cd frontend
npm install
copy .env.example .env        # en Mac/Linux: cp .env.example .env
npm run dev
```

Abra el navegador en **http://localhost:5173** (Chrome o Firefox).
¡Listo! Ya puede registrar hoteles y asignarles habitaciones.

---

## ✅ ¿Cómo sé que todo quedó bien?

- En `http://localhost:5173` debería ver el formulario "Registrar hotel" y,
  a la derecha, la lista de hoteles.
- Si abre `http://localhost:8000/api/v1/catalogs` debería ver un JSON con
  ciudades, tipos y acomodaciones.

---

## 🧪 Ejecutar las pruebas

Backend (PHPUnit):

```bash
cd backend
php artisan test
```

Frontend (Vitest):

```bash
cd frontend
npm run test
```

---

## 🗄️ Sobre el dump de la base de datos

El archivo `database_dump.sql` es **idempotente**: incluye sentencias
`DROP ... IF EXISTS`, por lo que puede ejecutarlo varias veces sobre la misma
base sin que arroje errores. Contiene el esquema completo, los catálogos
precargados y el hotel de ejemplo del enunciado (42 habitaciones).

---

## 📮 Colección de Postman

En `docs/Decameron.postman_collection.json` encontrará una colección lista para
importar en Postman. Incluye todos los endpoints y ejemplos de los casos de
error 422 (incompatibilidad, exceso de habitaciones, hotel duplicado).

Para usarla: abra Postman → **Import** → seleccione el archivo. La variable
`base_url` ya apunta a `http://localhost:8000/api/v1`; ajústela si usa otro host.

---

## 📊 Documentación y diagramas

En la carpeta [`docs/`](./docs) encontrará:

- `modelo-entidad-relacion.md` — diagrama ER de la base de datos.
- `arquitectura.md` — diagrama de la arquitectura Service–Repository y del flujo
  de una petición.
- `Decameron.postman_collection.json` — colección de Postman de la API.
