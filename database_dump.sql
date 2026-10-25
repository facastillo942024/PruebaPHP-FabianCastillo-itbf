-- =====================================================================
-- DUMP DE BASE DE DATOS · Sistema de Gestión Hotelera Decameron
-- =====================================================================
-- Motor      : PostgreSQL 15+ (generado con 16.15)
-- Contenido  : Esquema completo + catálogos precargados + hotel de ejemplo
-- Idempotente: Sí. Usa DROP ... IF EXISTS, puede ejecutarse varias veces.
--
-- Cómo restaurar (desde cero):
--   1) createdb -U postgres decameron_db
--   2) psql -U postgres -d decameron_db -f database_dump.sql
--
-- Datos de ejemplo incluidos:
--   - 6 ciudades, 3 tipos de habitación, 4 acomodaciones, matriz de 7 reglas.
--   - Hotel "DECAMERON CARTAGENA" (NIT 12345678-9) con 42 habitaciones:
--       25 ESTANDAR SENCILLA · 12 JUNIOR TRIPLE · 5 ESTANDAR DOBLE
-- =====================================================================

--
-- PostgreSQL database dump
--


-- Dumped from database version 16.15
-- Dumped by pg_dump version 16.15

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

ALTER TABLE IF EXISTS ONLY public.room_type_accommodations DROP CONSTRAINT IF EXISTS room_type_accommodations_room_type_id_foreign;
ALTER TABLE IF EXISTS ONLY public.room_type_accommodations DROP CONSTRAINT IF EXISTS room_type_accommodations_accommodation_id_foreign;
ALTER TABLE IF EXISTS ONLY public.hotels DROP CONSTRAINT IF EXISTS hotels_city_id_foreign;
ALTER TABLE IF EXISTS ONLY public.hotel_rooms DROP CONSTRAINT IF EXISTS hotel_rooms_room_type_id_foreign;
ALTER TABLE IF EXISTS ONLY public.hotel_rooms DROP CONSTRAINT IF EXISTS hotel_rooms_hotel_id_foreign;
ALTER TABLE IF EXISTS ONLY public.hotel_rooms DROP CONSTRAINT IF EXISTS hotel_rooms_accommodation_id_foreign;
ALTER TABLE IF EXISTS ONLY public.hotel_rooms DROP CONSTRAINT IF EXISTS unique_hotel_room_accommodation;
ALTER TABLE IF EXISTS ONLY public.room_types DROP CONSTRAINT IF EXISTS room_types_pkey;
ALTER TABLE IF EXISTS ONLY public.room_types DROP CONSTRAINT IF EXISTS room_types_name_unique;
ALTER TABLE IF EXISTS ONLY public.room_type_accommodations DROP CONSTRAINT IF EXISTS room_type_accommodations_room_type_id_accommodation_id_unique;
ALTER TABLE IF EXISTS ONLY public.room_type_accommodations DROP CONSTRAINT IF EXISTS room_type_accommodations_pkey;
ALTER TABLE IF EXISTS ONLY public.migrations DROP CONSTRAINT IF EXISTS migrations_pkey;
ALTER TABLE IF EXISTS ONLY public.hotels DROP CONSTRAINT IF EXISTS hotels_pkey;
ALTER TABLE IF EXISTS ONLY public.hotels DROP CONSTRAINT IF EXISTS hotels_nit_unique;
ALTER TABLE IF EXISTS ONLY public.hotels DROP CONSTRAINT IF EXISTS hotels_name_unique;
ALTER TABLE IF EXISTS ONLY public.hotel_rooms DROP CONSTRAINT IF EXISTS hotel_rooms_pkey;
ALTER TABLE IF EXISTS ONLY public.cities DROP CONSTRAINT IF EXISTS cities_pkey;
ALTER TABLE IF EXISTS ONLY public.accommodations DROP CONSTRAINT IF EXISTS accommodations_pkey;
ALTER TABLE IF EXISTS ONLY public.accommodations DROP CONSTRAINT IF EXISTS accommodations_name_unique;
ALTER TABLE IF EXISTS public.room_types ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.room_type_accommodations ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.migrations ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.hotels ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.hotel_rooms ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.cities ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.accommodations ALTER COLUMN id DROP DEFAULT;
DROP SEQUENCE IF EXISTS public.users_id_seq;
DROP SEQUENCE IF EXISTS public.room_types_id_seq;
DROP TABLE IF EXISTS public.room_types;
DROP SEQUENCE IF EXISTS public.room_type_accommodations_id_seq;
DROP TABLE IF EXISTS public.room_type_accommodations;
DROP SEQUENCE IF EXISTS public.personal_access_tokens_id_seq;
DROP SEQUENCE IF EXISTS public.migrations_id_seq;
DROP TABLE IF EXISTS public.migrations;
DROP SEQUENCE IF EXISTS public.jobs_id_seq;
DROP SEQUENCE IF EXISTS public.hotels_id_seq;
DROP TABLE IF EXISTS public.hotels;
DROP SEQUENCE IF EXISTS public.hotel_rooms_id_seq;
DROP TABLE IF EXISTS public.hotel_rooms;
DROP SEQUENCE IF EXISTS public.failed_jobs_id_seq;
DROP SEQUENCE IF EXISTS public.cities_id_seq;
DROP TABLE IF EXISTS public.cities;
DROP SEQUENCE IF EXISTS public.accommodations_id_seq;
DROP TABLE IF EXISTS public.accommodations;
SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: accommodations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.accommodations (
    id bigint NOT NULL,
    name character varying(50) NOT NULL
);


--
-- Name: accommodations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.accommodations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: accommodations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.accommodations_id_seq OWNED BY public.accommodations.id;


--
-- Name: cities; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cities (
    id bigint NOT NULL,
    name character varying(100) NOT NULL,
    created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: cities_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cities_id_seq OWNED BY public.cities.id;


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: hotel_rooms; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.hotel_rooms (
    id bigint NOT NULL,
    hotel_id bigint NOT NULL,
    room_type_id bigint NOT NULL,
    accommodation_id bigint NOT NULL,
    quantity integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT hotel_rooms_quantity_check CHECK ((quantity > 0))
);


--
-- Name: hotel_rooms_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.hotel_rooms_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: hotel_rooms_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.hotel_rooms_id_seq OWNED BY public.hotel_rooms.id;


--
-- Name: hotels; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.hotels (
    id bigint NOT NULL,
    name character varying(150) NOT NULL,
    address character varying(200) NOT NULL,
    city_id bigint NOT NULL,
    nit character varying(20) NOT NULL,
    max_rooms integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT hotels_max_rooms_check CHECK ((max_rooms > 0))
);


--
-- Name: hotels_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.hotels_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: hotels_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.hotels_id_seq OWNED BY public.hotels.id;


--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: room_type_accommodations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.room_type_accommodations (
    id bigint NOT NULL,
    room_type_id bigint NOT NULL,
    accommodation_id bigint NOT NULL
);


--
-- Name: room_type_accommodations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.room_type_accommodations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: room_type_accommodations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.room_type_accommodations_id_seq OWNED BY public.room_type_accommodations.id;


--
-- Name: room_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.room_types (
    id bigint NOT NULL,
    name character varying(50) NOT NULL
);


--
-- Name: room_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.room_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: room_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.room_types_id_seq OWNED BY public.room_types.id;


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: accommodations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.accommodations ALTER COLUMN id SET DEFAULT nextval('public.accommodations_id_seq'::regclass);


--
-- Name: cities id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cities ALTER COLUMN id SET DEFAULT nextval('public.cities_id_seq'::regclass);


--
-- Name: hotel_rooms id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hotel_rooms ALTER COLUMN id SET DEFAULT nextval('public.hotel_rooms_id_seq'::regclass);


--
-- Name: hotels id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hotels ALTER COLUMN id SET DEFAULT nextval('public.hotels_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: room_type_accommodations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.room_type_accommodations ALTER COLUMN id SET DEFAULT nextval('public.room_type_accommodations_id_seq'::regclass);


--
-- Name: room_types id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.room_types ALTER COLUMN id SET DEFAULT nextval('public.room_types_id_seq'::regclass);


--
-- Data for Name: accommodations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.accommodations (id, name) FROM stdin;
1	SENCILLA
2	DOBLE
3	TRIPLE
4	CUADRUPLE
\.


--
-- Data for Name: cities; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.cities (id, name, created_at) FROM stdin;
1	CARTAGENA	2026-10-25 15:36:24
2	SAN ANDRES	2026-10-25 15:36:24
3	SANTA MARTA	2026-10-25 15:36:24
4	BOGOTA	2026-10-25 15:36:24
5	MEDELLIN	2026-10-25 15:36:24
6	CALI	2026-10-25 15:36:24
\.


--
-- Data for Name: hotel_rooms; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.hotel_rooms (id, hotel_id, room_type_id, accommodation_id, quantity, created_at, updated_at) FROM stdin;
1	1	1	1	25	2026-10-25 15:36:33	2026-10-25 15:36:33
2	1	2	3	12	2026-10-25 15:36:33	2026-10-25 15:36:33
3	1	1	2	5	2026-10-25 15:36:33	2026-10-25 15:36:33
\.


--
-- Data for Name: hotels; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.hotels (id, name, address, city_id, nit, max_rooms, created_at, updated_at) FROM stdin;
1	DECAMERON CARTAGENA	CALLE 23 58-25	1	12345678-9	42	2026-10-25 15:36:33	2026-10-25 15:36:33
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2025_01_01_000001_create_cities_table	1
5	2025_01_01_000002_create_room_types_table	1
6	2025_01_01_000003_create_accommodations_table	1
7	2025_01_01_000004_create_room_type_accommodations_table	1
8	2025_01_01_000005_create_hotels_table	1
9	2025_01_01_000006_create_hotel_rooms_table	1
10	2026_10_25_151547_create_personal_access_tokens_table	1
\.


--
-- Data for Name: room_type_accommodations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.room_type_accommodations (id, room_type_id, accommodation_id) FROM stdin;
1	1	1
2	1	2
3	2	3
4	2	4
5	3	1
6	3	2
7	3	3
\.


--
-- Data for Name: room_types; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.room_types (id, name) FROM stdin;
1	ESTANDAR
2	JUNIOR
3	SUITE
\.


--
-- Name: accommodations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.accommodations_id_seq', 4, true);


--
-- Name: cities_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.cities_id_seq', 6, true);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: hotel_rooms_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.hotel_rooms_id_seq', 3, true);


--
-- Name: hotels_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.hotels_id_seq', 1, true);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 10, true);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 1, false);


--
-- Name: room_type_accommodations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.room_type_accommodations_id_seq', 7, true);


--
-- Name: room_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.room_types_id_seq', 3, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.users_id_seq', 1, false);


--
-- Name: accommodations accommodations_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.accommodations
    ADD CONSTRAINT accommodations_name_unique UNIQUE (name);


--
-- Name: accommodations accommodations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.accommodations
    ADD CONSTRAINT accommodations_pkey PRIMARY KEY (id);


--
-- Name: cities cities_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cities
    ADD CONSTRAINT cities_pkey PRIMARY KEY (id);


--
-- Name: hotel_rooms hotel_rooms_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hotel_rooms
    ADD CONSTRAINT hotel_rooms_pkey PRIMARY KEY (id);


--
-- Name: hotels hotels_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hotels
    ADD CONSTRAINT hotels_name_unique UNIQUE (name);


--
-- Name: hotels hotels_nit_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hotels
    ADD CONSTRAINT hotels_nit_unique UNIQUE (nit);


--
-- Name: hotels hotels_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hotels
    ADD CONSTRAINT hotels_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: room_type_accommodations room_type_accommodations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.room_type_accommodations
    ADD CONSTRAINT room_type_accommodations_pkey PRIMARY KEY (id);


--
-- Name: room_type_accommodations room_type_accommodations_room_type_id_accommodation_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.room_type_accommodations
    ADD CONSTRAINT room_type_accommodations_room_type_id_accommodation_id_unique UNIQUE (room_type_id, accommodation_id);


--
-- Name: room_types room_types_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.room_types
    ADD CONSTRAINT room_types_name_unique UNIQUE (name);


--
-- Name: room_types room_types_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.room_types
    ADD CONSTRAINT room_types_pkey PRIMARY KEY (id);


--
-- Name: hotel_rooms unique_hotel_room_accommodation; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hotel_rooms
    ADD CONSTRAINT unique_hotel_room_accommodation UNIQUE (hotel_id, room_type_id, accommodation_id);


--
-- Name: hotel_rooms hotel_rooms_accommodation_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hotel_rooms
    ADD CONSTRAINT hotel_rooms_accommodation_id_foreign FOREIGN KEY (accommodation_id) REFERENCES public.accommodations(id);


--
-- Name: hotel_rooms hotel_rooms_hotel_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hotel_rooms
    ADD CONSTRAINT hotel_rooms_hotel_id_foreign FOREIGN KEY (hotel_id) REFERENCES public.hotels(id) ON DELETE CASCADE;


--
-- Name: hotel_rooms hotel_rooms_room_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hotel_rooms
    ADD CONSTRAINT hotel_rooms_room_type_id_foreign FOREIGN KEY (room_type_id) REFERENCES public.room_types(id);


--
-- Name: hotels hotels_city_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hotels
    ADD CONSTRAINT hotels_city_id_foreign FOREIGN KEY (city_id) REFERENCES public.cities(id);


--
-- Name: room_type_accommodations room_type_accommodations_accommodation_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.room_type_accommodations
    ADD CONSTRAINT room_type_accommodations_accommodation_id_foreign FOREIGN KEY (accommodation_id) REFERENCES public.accommodations(id) ON DELETE CASCADE;


--
-- Name: room_type_accommodations room_type_accommodations_room_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.room_type_accommodations
    ADD CONSTRAINT room_type_accommodations_room_type_id_foreign FOREIGN KEY (room_type_id) REFERENCES public.room_types(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--


