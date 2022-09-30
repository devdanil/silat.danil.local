/*
 Navicat Premium Data Transfer

 Source Server         : postgreSQL
 Source Server Type    : PostgreSQL
 Source Server Version : 140003
 Source Host           : localhost:5432
 Source Catalog        : silat
 Source Schema         : public

 Target Server Type    : PostgreSQL
 Target Server Version : 140003
 File Encoding         : 65001

 Date: 22/09/2022 14:48:31
*/


-- ----------------------------
-- Sequence structure for silat_bahan_pelatihan_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."silat_bahan_pelatihan_id_seq";
CREATE SEQUENCE "public"."silat_bahan_pelatihan_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for silat_bobot_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."silat_bobot_id_seq";
CREATE SEQUENCE "public"."silat_bobot_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for silat_jabatan_pelatihan_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."silat_jabatan_pelatihan_id_seq";
CREATE SEQUENCE "public"."silat_jabatan_pelatihan_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for silat_katalog_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."silat_katalog_id_seq";
CREATE SEQUENCE "public"."silat_katalog_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for silat_log_katalog_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."silat_log_katalog_id_seq";
CREATE SEQUENCE "public"."silat_log_katalog_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for silat_menus_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."silat_menus_id_seq";
CREATE SEQUENCE "public"."silat_menus_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for silat_rolemenus_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."silat_rolemenus_id_seq";
CREATE SEQUENCE "public"."silat_rolemenus_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for silat_roles_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."silat_roles_id_seq";
CREATE SEQUENCE "public"."silat_roles_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for silat_status_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."silat_status_id_seq";
CREATE SEQUENCE "public"."silat_status_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for silat_user_roles_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."silat_user_roles_id_seq";
CREATE SEQUENCE "public"."silat_user_roles_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for silat_users_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."silat_users_id_seq";
CREATE SEQUENCE "public"."silat_users_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Table structure for silat_bahan_pelatihan
-- ----------------------------
DROP TABLE IF EXISTS "public"."silat_bahan_pelatihan";
CREATE TABLE "public"."silat_bahan_pelatihan" (
  "id" int4 NOT NULL DEFAULT nextval('silat_bahan_pelatihan_id_seq'::regclass),
  "pelatihan_id" int4,
  "name" varchar(255) COLLATE "pg_catalog"."default",
  "file" text COLLATE "pg_catalog"."default",
  "created_at" timestamp(0) DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp(0) DEFAULT CURRENT_TIMESTAMP
)
;

-- ----------------------------
-- Records of silat_bahan_pelatihan
-- ----------------------------

-- ----------------------------
-- Table structure for silat_bobot_pelatihan
-- ----------------------------
DROP TABLE IF EXISTS "public"."silat_bobot_pelatihan";
CREATE TABLE "public"."silat_bobot_pelatihan" (
  "id" int4 NOT NULL DEFAULT nextval('silat_bobot_id_seq'::regclass),
  "pelatihan_id" int4,
  "key" varchar(50) COLLATE "pg_catalog"."default",
  "bobot" int2,
  "created_at" timestamp(0),
  "updated_at" timestamp(6)
)
;

-- ----------------------------
-- Records of silat_bobot_pelatihan
-- ----------------------------

-- ----------------------------
-- Table structure for silat_jabatan_pelatihan
-- ----------------------------
DROP TABLE IF EXISTS "public"."silat_jabatan_pelatihan";
CREATE TABLE "public"."silat_jabatan_pelatihan" (
  "id" int4 NOT NULL DEFAULT nextval('silat_jabatan_pelatihan_id_seq'::regclass),
  "pelatihan_id" int4,
  "kd_jabatan" varchar(50) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0) DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp(6) DEFAULT CURRENT_TIMESTAMP
)
;

-- ----------------------------
-- Records of silat_jabatan_pelatihan
-- ----------------------------

-- ----------------------------
-- Table structure for silat_log_pelatihan
-- ----------------------------
DROP TABLE IF EXISTS "public"."silat_log_pelatihan";
CREATE TABLE "public"."silat_log_pelatihan" (
  "id" int4 NOT NULL DEFAULT nextval('silat_log_katalog_id_seq'::regclass),
  "pelatihan_id" int4,
  "status_id" int4,
  "keterangan" varchar(255) COLLATE "pg_catalog"."default",
  "user_id" int4,
  "created_at" timestamp(0),
  "updated_at" timestamp(0)
)
;

-- ----------------------------
-- Records of silat_log_pelatihan
-- ----------------------------

-- ----------------------------
-- Table structure for silat_menus
-- ----------------------------
DROP TABLE IF EXISTS "public"."silat_menus";
CREATE TABLE "public"."silat_menus" (
  "id" int4 NOT NULL DEFAULT nextval('silat_menus_id_seq'::regclass),
  "parent_id" int4,
  "name" varchar(100) COLLATE "pg_catalog"."default",
  "controller" varchar(150) COLLATE "pg_catalog"."default",
  "method" varchar(50) COLLATE "pg_catalog"."default",
  "route" varchar(150) COLLATE "pg_catalog"."default",
  "order" int4,
  "icon" text COLLATE "pg_catalog"."default",
  "created_at" timestamp(0) DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp(0) DEFAULT CURRENT_TIMESTAMP
)
;

-- ----------------------------
-- Records of silat_menus
-- ----------------------------
INSERT INTO "public"."silat_menus" VALUES (7, 5, 'Roles', 'RoleController', 'index', 'roles.index', 2, '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
</svg>
', '2022-09-19 14:05:36', '2022-09-19 14:05:36');
INSERT INTO "public"."silat_menus" VALUES (1, NULL, 'Dashboard', 'PageController', 'dashboard', 'dashboard', 1, '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
</svg>
', '2022-09-19 12:45:27', '2022-09-19 12:45:27');
INSERT INTO "public"."silat_menus" VALUES (6, 5, 'Menu', 'MenuController', 'index', 'menus.index', 1, '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
</svg>
', '2022-09-19 14:05:07', '2022-09-19 14:05:07');
INSERT INTO "public"."silat_menus" VALUES (9, NULL, 'Pengaturan', 'PengaturanController', 'index', 'roles-menus.index', 5, '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
</svg>
', '2022-09-19 14:12:03', '2022-09-19 14:12:03');
INSERT INTO "public"."silat_menus" VALUES (8, 5, 'Users', 'UserController', 'index', 'users.index', 3, '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
</svg>
', '2022-09-19 14:06:44', '2022-09-19 14:06:44');
INSERT INTO "public"."silat_menus" VALUES (10, 9, 'Aplikasi', 'AplikasiController', 'index', 'aplikasi.index', 1, '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
</svg>
', '2022-09-19 14:12:29', '2022-09-19 14:12:29');
INSERT INTO "public"."silat_menus" VALUES (11, 9, 'Roles Menu', 'RoleMenuController', 'index', 'roles-menus.index', 2, '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
</svg>
', '2022-09-19 14:13:19', '2022-09-19 14:13:19');
INSERT INTO "public"."silat_menus" VALUES (2, NULL, 'Pelatihan', 'Pelatihan', 'index', 'pelatihan.index', 2, '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
</svg>
', '2022-09-19 13:20:16', '2022-09-19 13:20:16');
INSERT INTO "public"."silat_menus" VALUES (5, NULL, 'Master Data', 'Master', 'index', 'menus.index', 4, '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
</svg>
', '2022-09-19 14:04:10', '2022-09-19 14:04:10');
INSERT INTO "public"."silat_menus" VALUES (12, 9, 'Users Roles', 'UserRoleController', 'index', 'users-roles.index', 3, '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
</svg>
', '2022-09-19 14:13:39', '2022-09-19 14:13:39');

-- ----------------------------
-- Table structure for silat_password_resets
-- ----------------------------
DROP TABLE IF EXISTS "public"."silat_password_resets";
CREATE TABLE "public"."silat_password_resets" (
  "email" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "token" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "created_at" timestamp(0)
)
;

-- ----------------------------
-- Records of silat_password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for silat_pelatihan
-- ----------------------------
DROP TABLE IF EXISTS "public"."silat_pelatihan";
CREATE TABLE "public"."silat_pelatihan" (
  "id" int4 NOT NULL DEFAULT nextval('silat_katalog_id_seq'::regclass),
  "judul" varchar(255) COLLATE "pg_catalog"."default",
  "slug" varchar(255) COLLATE "pg_catalog"."default",
  "deskripsi" text COLLATE "pg_catalog"."default",
  "silabus" text COLLATE "pg_catalog"."default",
  "persyaratan" text COLLATE "pg_catalog"."default",
  "tgl_mulai" date,
  "tgl_selesai" date,
  "kuota" int4,
  "is_publish" bool,
  "status_id" int4,
  "keterangan" varchar(255) COLLATE "pg_catalog"."default",
  "created_by" int4,
  "updated_by" int4,
  "created_at" timestamp(6) DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp(6) DEFAULT CURRENT_TIMESTAMP,
  "mulai_pendaftaran" date,
  "selesai_pendaftaran" date,
  "batas_konfirmasi" date
)
;

-- ----------------------------
-- Records of silat_pelatihan
-- ----------------------------

-- ----------------------------
-- Table structure for silat_roles
-- ----------------------------
DROP TABLE IF EXISTS "public"."silat_roles";
CREATE TABLE "public"."silat_roles" (
  "id" int4 NOT NULL DEFAULT nextval('silat_roles_id_seq'::regclass),
  "name" varchar(50) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0) DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp(6) DEFAULT CURRENT_TIMESTAMP
)
;

-- ----------------------------
-- Records of silat_roles
-- ----------------------------
INSERT INTO "public"."silat_roles" VALUES (1, 'Admin Pelatihan', '2022-09-16 17:29:32', '2022-09-16 17:29:32.290266');
INSERT INTO "public"."silat_roles" VALUES (2, 'Admin SISDMK', '2022-09-16 17:29:47', '2022-09-16 17:29:47.39108');
INSERT INTO "public"."silat_roles" VALUES (3, 'Ketua Tim', '2022-09-16 17:29:55', '2022-09-16 17:29:55.213996');

-- ----------------------------
-- Table structure for silat_roles_menus
-- ----------------------------
DROP TABLE IF EXISTS "public"."silat_roles_menus";
CREATE TABLE "public"."silat_roles_menus" (
  "id" int4 NOT NULL DEFAULT nextval('silat_rolemenus_id_seq'::regclass),
  "role_id" int4,
  "menu_id" int4,
  "created_at" timestamp(6) DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp(6) DEFAULT CURRENT_TIMESTAMP
)
;

-- ----------------------------
-- Records of silat_roles_menus
-- ----------------------------
INSERT INTO "public"."silat_roles_menus" VALUES (7, 1, 1, '2022-09-19 14:15:45.239282', '2022-09-19 14:15:45.239282');
INSERT INTO "public"."silat_roles_menus" VALUES (8, 1, 2, '2022-09-19 14:15:45.242874', '2022-09-19 14:15:45.242874');
INSERT INTO "public"."silat_roles_menus" VALUES (11, 1, 5, '2022-09-19 14:15:45.244796', '2022-09-19 14:15:45.244796');
INSERT INTO "public"."silat_roles_menus" VALUES (12, 1, 6, '2022-09-19 14:15:45.245316', '2022-09-19 14:15:45.245316');
INSERT INTO "public"."silat_roles_menus" VALUES (13, 1, 7, '2022-09-19 14:15:45.245895', '2022-09-19 14:15:45.245895');
INSERT INTO "public"."silat_roles_menus" VALUES (14, 1, 8, '2022-09-19 14:15:45.246477', '2022-09-19 14:15:45.246477');
INSERT INTO "public"."silat_roles_menus" VALUES (15, 1, 9, '2022-09-19 14:15:45.247213', '2022-09-19 14:15:45.247213');
INSERT INTO "public"."silat_roles_menus" VALUES (16, 1, 10, '2022-09-19 14:15:45.247706', '2022-09-19 14:15:45.247706');
INSERT INTO "public"."silat_roles_menus" VALUES (17, 1, 11, '2022-09-19 14:15:45.248246', '2022-09-19 14:15:45.248246');
INSERT INTO "public"."silat_roles_menus" VALUES (18, 1, 12, '2022-09-19 14:15:45.248738', '2022-09-19 14:15:45.248738');

-- ----------------------------
-- Table structure for silat_sessions
-- ----------------------------
DROP TABLE IF EXISTS "public"."silat_sessions";
CREATE TABLE "public"."silat_sessions" (
  "id" varchar COLLATE "pg_catalog"."default" NOT NULL,
  "user_id" int4,
  "ip_address" varchar(45) COLLATE "pg_catalog"."default",
  "user_agent" text COLLATE "pg_catalog"."default",
  "payload" text COLLATE "pg_catalog"."default",
  "last_activity" int4
)
;

-- ----------------------------
-- Records of silat_sessions
-- ----------------------------
INSERT INTO "public"."silat_sessions" VALUES ('EAvTaPUivUgg4WFydrME6RMxHLrEKhWWgkWWOzmu', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUm44SDFtckJNRmtzSFdKZUhwcWd0aThjSGV5NUcwRTJ1QVJ2Zmx6RyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyNDoiaHR0cDovL3NpbGF0LmRhbmlsLmxvY2FsIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1OiJmbGFzaCI7YTowOnt9fQ==', 1663832647);

-- ----------------------------
-- Table structure for silat_status
-- ----------------------------
DROP TABLE IF EXISTS "public"."silat_status";
CREATE TABLE "public"."silat_status" (
  "id" int4 NOT NULL DEFAULT nextval('silat_status_id_seq'::regclass),
  "name" varchar(50) COLLATE "pg_catalog"."default",
  "slug" varchar(60) COLLATE "pg_catalog"."default",
  "role_id" int4,
  "created_by" int4,
  "updated_by" int4,
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "flash" varchar(255) COLLATE "pg_catalog"."default",
  "notification" varchar(255) COLLATE "pg_catalog"."default",
  "next_id" int4,
  "prev_id" int4
)
;

-- ----------------------------
-- Records of silat_status
-- ----------------------------
INSERT INTO "public"."silat_status" VALUES (3, 'Katalog Ditolak', 'rejected-catalog', 1, 1, 1, '2022-09-21 08:04:59', '2022-09-21 08:04:59', 'Data berhasil ditolak!', 'Silahkan perbaiki data dan submit pelatihan untuk memproses data.', 2, 3);
INSERT INTO "public"."silat_status" VALUES (1, 'Draft Katalog', 'draft', 1, 1, 1, '2022-09-21 08:04:59', '2022-09-21 08:04:59', 'Data berhasil disimpan!', 'Silahkan submit pelatihan untuk memproses data.', 2, 1);
INSERT INTO "public"."silat_status" VALUES (4, 'Review Pembobotan', 'reviewed', 3, 1, 1, '2022-09-21 08:04:59', '2022-09-21 08:04:59', 'Data berhasil dikirim!', 'Silahkan periksa data pelatihan lalu approve atau reject pelatihan untuk memproses data.', 6, 5);
INSERT INTO "public"."silat_status" VALUES (2, 'Review Katalog', 'submitted', 2, 1, 1, '2022-09-21 08:04:59', '2022-09-21 08:04:59', 'Data berhasil dikirim!', 'Silahkan isi pembobotan nilai dan submit atau reject pelatihan untuk memproses data.', 4, 3);
INSERT INTO "public"."silat_status" VALUES (9, 'Undangan Tidak Terpenuhi', 'cancel-2', 1, 1, 1, '2022-09-22 12:07:24', '2022-09-22 12:07:24', 'Data berhasil dibatalkan!', 'Silahkan ubah tanggal konfirmasi dan lanjutkan pelatihan untuk memproses data.', NULL, NULL);
INSERT INTO "public"."silat_status" VALUES (5, 'Pembobotan ditolak', 'rejected-rating', 2, 1, 1, '2022-09-21 08:04:59', '2022-09-21 08:04:59', 'Data berhasil ditolak!', 'Silahkan perbaiki pembobotan nilai dan submit atau reject pelatihan memproses data.', 4, 5);
INSERT INTO "public"."silat_status" VALUES (6, 'Pembobotan Diterima', 'approved', 1, 1, 1, '2022-09-21 08:04:59', '2022-09-21 08:04:59', 'Data berhasil diterima!', 'Silahkan lanjutkan atau batalkan pelatihan untuk memproses data.', 8, 7);
INSERT INTO "public"."silat_status" VALUES (8, 'Menunggu Konfirmasi Peserta', 'waiting-participants', 1, 1, 1, '2022-09-22 12:02:47', '2022-09-22 12:02:47', 'Data berhasil dilanjutkan!', 'Mohon menunggu konfirmasi dari peserta sampai batas yang telah ditentukan, lalu lanjutkan atau batalkan pelatihan untuk proses memproses data.', 9, NULL);
INSERT INTO "public"."silat_status" VALUES (10, 'Penetapan Peserta', 'participants determination', 2, 1, 1, '2022-09-22 12:19:14', '2022-09-22 12:19:16', 'Data berhasil dilanjutkan!', NULL, NULL, NULL);
INSERT INTO "public"."silat_status" VALUES (7, 'Kuota Tidak Terpenuhi', 'cancel-1', 1, 1, 1, '2022-09-22 11:52:51', '2022-09-22 11:52:51', 'Data berhasil dibatalkan!', 'Silahkan ubah tanggal pendaftaran dan submit pelatihan untuk memproses data.', 6, 7);

-- ----------------------------
-- Table structure for silat_users
-- ----------------------------
DROP TABLE IF EXISTS "public"."silat_users";
CREATE TABLE "public"."silat_users" (
  "id" int4 NOT NULL DEFAULT nextval('silat_users_id_seq'::regclass),
  "nama" varchar(150) COLLATE "pg_catalog"."default",
  "foto" varchar(255) COLLATE "pg_catalog"."default",
  "email" varchar(150) COLLATE "pg_catalog"."default",
  "nip" varchar(30) COLLATE "pg_catalog"."default",
  "password" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "remember_token" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0) DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp(0) DEFAULT CURRENT_TIMESTAMP
)
;

-- ----------------------------
-- Records of silat_users
-- ----------------------------
INSERT INTO "public"."silat_users" VALUES (1, 'Danil', NULL, 'devdanil14@gmail.com', '111111111111111111', '$2y$10$ZvBqNR6XwsyHRSkxHUlGceihsnj7qOQvkPGcioktHWB44Vs8PZ72i', 'pZoPrSGhSBmvDaE1u08ZKNoYGhbJrvKOEkLFiRS7m60GwGqOMRnsUEEOIehE', NULL, NULL);

-- ----------------------------
-- Table structure for silat_users_roles
-- ----------------------------
DROP TABLE IF EXISTS "public"."silat_users_roles";
CREATE TABLE "public"."silat_users_roles" (
  "id" int4 NOT NULL DEFAULT nextval('silat_user_roles_id_seq'::regclass),
  "user_id" int4,
  "role_id" int4,
  "created_by" int4,
  "updated_by" int4,
  "created_at" timestamp(0),
  "updated_at" timestamp(0)
)
;

-- ----------------------------
-- Records of silat_users_roles
-- ----------------------------
INSERT INTO "public"."silat_users_roles" VALUES (1, 1, 1, 1, 1, '2022-09-16 17:30:42', '2022-09-16 17:30:42');
INSERT INTO "public"."silat_users_roles" VALUES (2, 1, 2, 1, 1, '2022-09-16 17:30:42', '2022-09-16 17:30:42');
INSERT INTO "public"."silat_users_roles" VALUES (3, 1, 3, 1, 1, '2022-09-16 17:30:42', '2022-09-16 17:30:42');


-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."silat_bahan_pelatihan_id_seq"
OWNED BY "public"."silat_bahan_pelatihan"."id";
SELECT setval('"public"."silat_bahan_pelatihan_id_seq"', 28, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."silat_bobot_id_seq"
OWNED BY "public"."silat_bobot_pelatihan"."id";
SELECT setval('"public"."silat_bobot_id_seq"', 59, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."silat_jabatan_pelatihan_id_seq"
OWNED BY "public"."silat_jabatan_pelatihan"."id";
SELECT setval('"public"."silat_jabatan_pelatihan_id_seq"', 180, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."silat_katalog_id_seq"
OWNED BY "public"."silat_pelatihan"."id";
SELECT setval('"public"."silat_katalog_id_seq"', 28, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."silat_log_katalog_id_seq"
OWNED BY "public"."silat_log_pelatihan"."id";
SELECT setval('"public"."silat_log_katalog_id_seq"', 84, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."silat_menus_id_seq"
OWNED BY "public"."silat_menus"."id";
SELECT setval('"public"."silat_menus_id_seq"', 14, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."silat_rolemenus_id_seq"
OWNED BY "public"."silat_roles_menus"."id";
SELECT setval('"public"."silat_rolemenus_id_seq"', 20, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."silat_roles_id_seq"
OWNED BY "public"."silat_roles"."id";
SELECT setval('"public"."silat_roles_id_seq"', 4, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."silat_status_id_seq"
OWNED BY "public"."silat_status"."id";
SELECT setval('"public"."silat_status_id_seq"', 11, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."silat_user_roles_id_seq"
OWNED BY "public"."silat_users_roles"."id";
SELECT setval('"public"."silat_user_roles_id_seq"', 4, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."silat_users_id_seq"
OWNED BY "public"."silat_users"."id";
SELECT setval('"public"."silat_users_id_seq"', 2, true);

-- ----------------------------
-- Primary Key structure for table silat_bahan_pelatihan
-- ----------------------------
ALTER TABLE "public"."silat_bahan_pelatihan" ADD CONSTRAINT "silat_bahan_pelatihan_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table silat_bobot_pelatihan
-- ----------------------------
ALTER TABLE "public"."silat_bobot_pelatihan" ADD CONSTRAINT "silat_bobot_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table silat_jabatan_pelatihan
-- ----------------------------
ALTER TABLE "public"."silat_jabatan_pelatihan" ADD CONSTRAINT "silat_jabatan_pelatihan_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table silat_log_pelatihan
-- ----------------------------
ALTER TABLE "public"."silat_log_pelatihan" ADD CONSTRAINT "silat_log_katalog_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table silat_menus
-- ----------------------------
ALTER TABLE "public"."silat_menus" ADD CONSTRAINT "silat_menus_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Indexes structure for table silat_password_resets
-- ----------------------------
CREATE INDEX "password_resets_email_index" ON "public"."silat_password_resets" USING btree (
  "email" COLLATE "pg_catalog"."default" "pg_catalog"."text_ops" ASC NULLS LAST
);

-- ----------------------------
-- Uniques structure for table silat_pelatihan
-- ----------------------------
ALTER TABLE "public"."silat_pelatihan" ADD CONSTRAINT "silat_katalog_slug_key" UNIQUE ("slug");

-- ----------------------------
-- Primary Key structure for table silat_pelatihan
-- ----------------------------
ALTER TABLE "public"."silat_pelatihan" ADD CONSTRAINT "silat_katalog_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table silat_roles
-- ----------------------------
ALTER TABLE "public"."silat_roles" ADD CONSTRAINT "silat_roles_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table silat_roles_menus
-- ----------------------------
ALTER TABLE "public"."silat_roles_menus" ADD CONSTRAINT "silat_rolemenus_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table silat_sessions
-- ----------------------------
ALTER TABLE "public"."silat_sessions" ADD CONSTRAINT "silat_sessions_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Uniques structure for table silat_status
-- ----------------------------
ALTER TABLE "public"."silat_status" ADD CONSTRAINT "silat_status_slug_key" UNIQUE ("slug");

-- ----------------------------
-- Primary Key structure for table silat_status
-- ----------------------------
ALTER TABLE "public"."silat_status" ADD CONSTRAINT "silat_status_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Uniques structure for table silat_users
-- ----------------------------
ALTER TABLE "public"."silat_users" ADD CONSTRAINT "silat_users_email_key" UNIQUE ("email");
ALTER TABLE "public"."silat_users" ADD CONSTRAINT "silat_users_nip_key" UNIQUE ("nip");

-- ----------------------------
-- Primary Key structure for table silat_users
-- ----------------------------
ALTER TABLE "public"."silat_users" ADD CONSTRAINT "silat_users_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table silat_users_roles
-- ----------------------------
ALTER TABLE "public"."silat_users_roles" ADD CONSTRAINT "silat_user_roles_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table tbk_jabatan
-- ----------------------------
ALTER TABLE "public"."tbk_jabatan" ADD CONSTRAINT "tbk_jabatan_pkey" PRIMARY KEY ("kd_jabatan");

-- ----------------------------
-- Primary Key structure for table tbl_riwayat_pelatihan
-- ----------------------------
ALTER TABLE "public"."tbl_riwayat_pelatihan" ADD CONSTRAINT "tbl_riwayat_pelatihan_pkey" PRIMARY KEY ("id_pelatihan");

-- ----------------------------
-- Primary Key structure for table tbm_pegawai
-- ----------------------------
ALTER TABLE "public"."tbm_pegawai" ADD CONSTRAINT "tbm_pegawai_pkey" PRIMARY KEY ("nip");

-- ----------------------------
-- Foreign Keys structure for table silat_bahan_pelatihan
-- ----------------------------
ALTER TABLE "public"."silat_bahan_pelatihan" ADD CONSTRAINT "silat_bahan_pelatihan_pelatihan_id_fkey" FOREIGN KEY ("pelatihan_id") REFERENCES "public"."silat_pelatihan" ("id") ON DELETE SET NULL ON UPDATE SET NULL;

-- ----------------------------
-- Foreign Keys structure for table silat_bobot_pelatihan
-- ----------------------------
ALTER TABLE "public"."silat_bobot_pelatihan" ADD CONSTRAINT "silat_bobot_pelatihan_id_fkey" FOREIGN KEY ("pelatihan_id") REFERENCES "public"."silat_pelatihan" ("id") ON DELETE SET NULL ON UPDATE SET NULL;

-- ----------------------------
-- Foreign Keys structure for table silat_jabatan_pelatihan
-- ----------------------------
ALTER TABLE "public"."silat_jabatan_pelatihan" ADD CONSTRAINT "silat_jabatan_pelatihan_kd_jabatan_fkey" FOREIGN KEY ("kd_jabatan") REFERENCES "public"."tbk_jabatan" ("kd_jabatan") ON DELETE SET NULL ON UPDATE SET NULL;
ALTER TABLE "public"."silat_jabatan_pelatihan" ADD CONSTRAINT "silat_jabatan_pelatihan_pelatihan_id_fkey" FOREIGN KEY ("pelatihan_id") REFERENCES "public"."silat_pelatihan" ("id") ON DELETE SET NULL ON UPDATE SET NULL;

-- ----------------------------
-- Foreign Keys structure for table silat_log_pelatihan
-- ----------------------------
ALTER TABLE "public"."silat_log_pelatihan" ADD CONSTRAINT "silat_log_katalog_katalog_id_fkey" FOREIGN KEY ("pelatihan_id") REFERENCES "public"."silat_pelatihan" ("id") ON DELETE SET NULL ON UPDATE SET NULL;
ALTER TABLE "public"."silat_log_pelatihan" ADD CONSTRAINT "silat_log_katalog_status_id_fkey" FOREIGN KEY ("status_id") REFERENCES "public"."silat_status" ("id") ON DELETE SET NULL ON UPDATE SET NULL;
ALTER TABLE "public"."silat_log_pelatihan" ADD CONSTRAINT "silat_log_katalog_user_id_fkey" FOREIGN KEY ("user_id") REFERENCES "public"."silat_users" ("id") ON DELETE SET NULL ON UPDATE SET NULL;

-- ----------------------------
-- Foreign Keys structure for table silat_pelatihan
-- ----------------------------
ALTER TABLE "public"."silat_pelatihan" ADD CONSTRAINT "silat_katalog_status_id_fkey" FOREIGN KEY ("status_id") REFERENCES "public"."silat_status" ("id") ON DELETE SET NULL ON UPDATE SET NULL;

-- ----------------------------
-- Foreign Keys structure for table silat_roles_menus
-- ----------------------------
ALTER TABLE "public"."silat_roles_menus" ADD CONSTRAINT "silat_role_menus_role_id_fkey" FOREIGN KEY ("role_id") REFERENCES "public"."silat_roles" ("id") ON DELETE SET NULL ON UPDATE SET NULL;
ALTER TABLE "public"."silat_roles_menus" ADD CONSTRAINT "silat_rolemenus_menu_id_fkey" FOREIGN KEY ("menu_id") REFERENCES "public"."silat_menus" ("id") ON DELETE SET NULL ON UPDATE SET NULL;

-- ----------------------------
-- Foreign Keys structure for table silat_status
-- ----------------------------
ALTER TABLE "public"."silat_status" ADD CONSTRAINT "silat_status_role_id_fkey" FOREIGN KEY ("role_id") REFERENCES "public"."silat_roles" ("id") ON DELETE SET NULL ON UPDATE SET NULL;

-- ----------------------------
-- Foreign Keys structure for table silat_users_roles
-- ----------------------------
ALTER TABLE "public"."silat_users_roles" ADD CONSTRAINT "silat_user_roles_role_id_fkey" FOREIGN KEY ("role_id") REFERENCES "public"."silat_roles" ("id") ON DELETE SET NULL ON UPDATE SET NULL;
ALTER TABLE "public"."silat_users_roles" ADD CONSTRAINT "silat_users_roles_user_id_fkey" FOREIGN KEY ("user_id") REFERENCES "public"."silat_users" ("id") ON DELETE SET NULL ON UPDATE SET NULL;
