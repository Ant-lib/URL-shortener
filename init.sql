

-- create role
CREATE ROLE ushr_user LOGIN PASSWORD '4nHdzMNy'
NOSUPERUSER INHERIT NOCREATEDB NOCREATEROLE NOREPLICATION;

-- create DB
CREATE DATABASE "USHR"
  WITH OWNER = ushr_user
       ENCODING = 'UTF8'
       TABLESPACE = pg_default
       LC_COLLATE = 'English_United States.1252'
       LC_CTYPE = 'English_United States.1252'
       CONNECTION LIMIT = -1;

COMMENT ON DATABASE "USHR" IS 'URL shortener DB';

-- create schema
CREATE SCHEMA ushr AUTHORIZATION ushr_user;

GRANT ALL ON SCHEMA ushr TO ushr_user;

COMMENT ON SCHEMA ushr IS 'URL shortener schema';

-- create sequence for id field
CREATE SEQUENCE ushr.seqtshorturls
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 999999999999
  START 1
  CACHE 1;
ALTER TABLE ushr.seqtshorturls OWNER TO ushr_user;


-- create table
CREATE TABLE ushr.tshorturls (
  iid integer NOT NULL DEFAULT nextval('ushr.seqtshorturls'::regclass),
  slongurl character varying(255) NOT NULL,
  sshorturl character varying(255),
  dtregistered timestamp without time zone NOT NULL DEFAULT now(),
  dtexpired timestamp without time zone NOT NULL DEFAULT now()+'3 days',
  CONSTRAINT pk_tshorturls PRIMARY KEY (iid)
)
WITH (OIDS=FALSE);
ALTER TABLE ushr.tshorturls
  OWNER TO ushr_user;


