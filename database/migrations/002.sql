CREATE TYPE public.jenis_pekerjaan AS ENUM (
    'full-time',
    'part-time',
    'internship'
);

ALTER TABLE public.lowongan
    ALTER COLUMN jenis_pekerjaan TYPE public.jenis_pekerjaan
    USING jenis_pekerjaan::public.jenis_pekerjaan;

ALTER TYPE public.jenis_pekerjaan OWNER TO "user";