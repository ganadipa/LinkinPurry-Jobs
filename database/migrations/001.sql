-- Creating types
CREATE TYPE public.jenis_lokasi AS ENUM ('on-site', 'hybrid', 'remote');
CREATE TYPE public.status_lamaran AS ENUM ('accepted', 'rejected', 'waiting');
CREATE TYPE public.user_role AS ENUM ('jobseeker', 'company');

-- Creating users table
CREATE TABLE public.users (
    user_id integer NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    role public.user_role NOT NULL,
    nama character varying(255) NOT NULL
);

-- Creating lowongan table
CREATE TABLE public.lowongan (
    lowongan_id integer NOT NULL,
    company_id integer NOT NULL,
    posisi character varying(255) NOT NULL,
    deskripsi character varying(255),
    jenis_pekerjaan character varying(255),
    jenis_lokasi public.jenis_lokasi NOT NULL,
    is_open boolean DEFAULT true,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);

-- Creating lamaran table
CREATE TABLE public.lamaran (
    lamaran_id integer NOT NULL,
    user_id integer NOT NULL,
    lowongan_id integer NOT NULL,
    cv_path character varying(255),
    video_path character varying(255),
    status public.status_lamaran NOT NULL,
    status_reason character varying(255),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);

-- Creating attachment_lowongan table
CREATE TABLE public.attachment_lowongan (
    attachment_id integer NOT NULL,
    lowongan_id integer NOT NULL,
    file_path character varying(255) NOT NULL
);

-- Creating company_detail table
CREATE TABLE public.company_detail (
    user_id integer NOT NULL,
    lokasi character varying(255),
    about character varying(255)
);

-- Adding primary keys
ALTER TABLE ONLY public.users ADD CONSTRAINT users_pkey PRIMARY KEY (user_id);
ALTER TABLE ONLY public.lowongan ADD CONSTRAINT lowongan_pkey PRIMARY KEY (lowongan_id);
ALTER TABLE ONLY public.lamaran ADD CONSTRAINT lamaran_pkey PRIMARY KEY (lamaran_id);
ALTER TABLE ONLY public.attachment_lowongan ADD CONSTRAINT attachment_lowongan_pkey PRIMARY KEY (attachment_id);
ALTER TABLE ONLY public.company_detail ADD CONSTRAINT company_detail_pkey PRIMARY KEY (user_id);

-- Adding foreign keys
ALTER TABLE ONLY public.lowongan ADD CONSTRAINT fk_company_id FOREIGN KEY (company_id) REFERENCES public.users(user_id) ON DELETE CASCADE;
ALTER TABLE ONLY public.lamaran ADD CONSTRAINT fk_lowongan_id FOREIGN KEY (lowongan_id) REFERENCES public.lowongan(lowongan_id) ON DELETE CASCADE;
ALTER TABLE ONLY public.lamaran ADD CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES public.users(user_id) ON DELETE CASCADE;
ALTER TABLE ONLY public.attachment_lowongan ADD CONSTRAINT fk_lowongan_id FOREIGN KEY (lowongan_id) REFERENCES public.lowongan(lowongan_id) ON DELETE CASCADE;
ALTER TABLE ONLY public.company_detail ADD CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES public.users(user_id) ON DELETE CASCADE;

-- Setting sequences
CREATE SEQUENCE public.users_user_id_seq START WITH 1 INCREMENT BY 1;
CREATE SEQUENCE public.lowongan_lowongan_id_seq START WITH 1 INCREMENT BY 1;
CREATE SEQUENCE public.lamaran_lamaran_id_seq START WITH 1 INCREMENT BY 1;
CREATE SEQUENCE public.attachment_lowongan_attachment_id_seq START WITH 1 INCREMENT BY 1;

-- Setting default sequences for the primary keys
ALTER TABLE ONLY public.users ALTER COLUMN user_id SET DEFAULT nextval('public.users_user_id_seq'::regclass);
ALTER TABLE ONLY public.lowongan ALTER COLUMN lowongan_id SET DEFAULT nextval('public.lowongan_lowongan_id_seq'::regclass);
ALTER TABLE ONLY public.lamaran ALTER COLUMN lamaran_id SET DEFAULT nextval('public.lamaran_lamaran_id_seq'::regclass);
ALTER TABLE ONLY public.attachment_lowongan ALTER COLUMN attachment_id SET DEFAULT nextval('public.attachment_lowongan_attachment_id_seq'::regclass);
