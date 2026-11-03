-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3309
-- Tiempo de generación: 10-08-2026 a las 07:26:01
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `inventori_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ventas`
--

CREATE TABLE `detalle_ventas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `venta_id` bigint(20) UNSIGNED NOT NULL,
  `producto_id` bigint(20) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_ventas`
--

INSERT INTO `detalle_ventas` (`id`, `venta_id`, `producto_id`, `cantidad`, `precio_unitario`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 1, 23.00, 23.00, '2026-07-31 12:45:12', '2026-07-31 12:45:12'),
(2, 1, 2, 1, 17.50, 17.50, '2026-07-31 12:45:12', '2026-07-31 12:45:12'),
(3, 2, 2, 1, 17.50, 17.50, '2026-08-10 07:26:48', '2026-08-10 07:26:48'),
(4, 2, 3, 1, 24.00, 24.00, '2026-08-10 07:26:48', '2026-08-10 07:26:48'),
(5, 2, 5, 4, 18.50, 74.00, '2026-08-10 07:26:48', '2026-08-10 07:26:48'),
(6, 2, 1, 1, 16.00, 16.00, '2026-08-10 07:26:48', '2026-08-10 07:26:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_06_21_202833_add_id_rol_to_users_table', 2),
(5, '2026_07_10_055501_add_aviso_privacidad_to_users_table', 3),
(6, '2026_07_17_061637_add_arco_fields_to_users_table', 4),
(8, '2026_07_21_061624_create_personal_access_tokens_table', 6),
(9, '2026_07_21_060001_create_productos_table', 7),
(10, '2026_07_31_035410_create_ventas_table', 8),
(11, '2026_07_31_035417_create_detalle_ventas_table', 9),
(12, '2026_07_31_073950_add_divisa_to_productos_table', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('alexandersantoslopez15@gmail.com', '$2y$12$NxS8AvYm.54ta.b6qGg.b.lVZYKsY215xp/W1Qhy1N10DcnunS.tO', '2026-08-09 11:48:16'),
('omarsanlop1509@gmail.com', '$2y$12$Gg5o/sWI.aYK1RmcjfxrmuA57jLnUlxjzyRXX9ME54//11PL9NE5q', '2026-08-09 11:48:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `tipo_caducidad` enum('perecedero','no_perecedero','semiperecedero') NOT NULL DEFAULT 'no_perecedero',
  `divisa` varchar(10) NOT NULL DEFAULT 'USD',
  `precio_costo_original` decimal(10,2) DEFAULT NULL,
  `precio_usd` decimal(10,2) NOT NULL,
  `precio_mxn` decimal(10,2) NOT NULL,
  `precio_venta` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `fecha_llegada` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `tipo_caducidad`, `divisa`, `precio_costo_original`, `precio_usd`, `precio_mxn`, `precio_venta`, `stock`, `fecha_llegada`, `created_at`, `updated_at`) VALUES
(1, 'Sabritas Sabor Natural', 'no_perecedero', 'USD', NULL, 25.99, 453.33, 16.00, 29, '2026-07-21', '2026-07-21 12:58:18', '2026-08-10 07:26:48'),
(2, 'Champú Pantene', 'no_perecedero', 'USD', NULL, 20.00, 347.21, 17.50, 18, '2026-07-31', '2026-07-31 12:32:08', '2026-08-10 07:26:48'),
(3, 'Doritos Sabor Original', 'no_perecedero', 'USD', 55.00, 55.00, 954.84, 24.00, 49, '2026-07-31', '2026-07-31 12:34:26', '2026-08-10 07:26:48'),
(5, 'Refresco Fanta 600ml', 'no_perecedero', 'USD', 18.00, 18.00, 308.54, 18.50, 16, '2026-08-10', '2026-08-10 07:13:06', '2026-08-10 07:26:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `id_rol` int(11) NOT NULL DEFAULT 2,
  `status` varchar(255) NOT NULL DEFAULT 'activo',
  `arco_requested_at` timestamp NULL DEFAULT NULL,
  `arco_resolved_at` timestamp NULL DEFAULT NULL,
  `acepto_aviso` tinyint(4) NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `id_rol`, `status`, `arco_requested_at`, `arco_resolved_at`, `acepto_aviso`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Omar Alejandro Santos López', 'alexandersantoslopez15@gmail.com', 1, 'activo', NULL, NULL, 0, '2026-08-08 23:57:58', '$2y$12$LuY2yTQ8SNP9laepozASF.ddVWAal6XVrp55i.hgTQoxevGarBpgm', NULL, '2026-06-12 13:04:21', '2026-06-26 12:55:36'),
(4, 'Mario', 'mario@correo.com', 2, 'activo', NULL, NULL, 0, '2026-08-08 23:57:58', '$2y$12$JhhzKh7kvGC0ZTek.fdZZOfA0p0nm3g98cOFf8jVGukWb6TnkL5Pa', NULL, '2026-06-17 08:26:33', '2026-06-17 08:26:33'),
(5, 'Omar Alejandro Santos López', 'alexandersantos@gmail.com', 2, 'activo', NULL, NULL, 0, '2026-08-08 23:57:58', '$2y$12$2nRJ7qzfuNQG.BR5lkaYIeAvxVu7C8qTSF9m1tZqAxQ6z57TOrE8G', NULL, '2026-06-17 08:27:47', '2026-06-17 08:27:47'),
(6, 'Super Administrador', 'superadmin@inventori.com', 3, 'activo', NULL, NULL, 1, '2026-08-08 23:57:58', '$2y$12$yrg40YxiLMkhkd4VPwn6IuMnExQ7ZPBqF.zMjDt/QTMhu83jTtOXq', NULL, '2026-06-22 02:33:53', '2026-07-10 13:45:50'),
(7, 'Admin Proyecto', 'admin@inventori.com', 1, 'activo', NULL, NULL, 1, '2026-08-08 23:57:58', '$2y$12$CYIRW5SUmS1kUsdoAnEA.ORfeyLkoyU8oD8ApKp3H23Sf9Rq7TnXy', NULL, '2026-06-22 02:33:53', '2026-07-21 12:24:43'),
(8, 'Empleado Mostrador', 'empleado@inventori.com', 2, 'solicitado_arco', '2026-08-09 12:06:50', NULL, 1, '2026-08-08 23:57:58', '$2y$12$1BFGVjcA8vRTeiUlznADneVa2pcSF27zhKPBIXd9uwBuCsDlBf7AC', NULL, '2026-06-22 02:33:53', '2026-08-09 12:06:50'),
(11, 'Omar Alejandro Camarillo Rodriguez', 'alexomaralabama@gmail.com', 2, 'activo', NULL, NULL, 0, '2026-08-09 10:00:35', '$2y$12$bElnN7SEc./Zgfj.wfKCuucHxx1gcsEOxxhcSbj70F2VPYO8J5.1O', NULL, '2026-08-09 10:00:03', '2026-08-09 10:00:35'),
(12, 'Elias', 'elias@gmail.com', 2, 'inactivo', NULL, NULL, 0, NULL, '$2y$12$afbhleqN/AG5VMfarn1DY.zUo5Tu89D6WrUFEWUqSI6ETJt/xDzES', NULL, '2026-08-09 11:31:31', '2026-08-09 11:31:31'),
(13, 'Alejandro Santos', 'omarsanlop1509@gmail.com', 2, 'activo', NULL, NULL, 1, '2026-08-09 11:38:06', '$2y$12$tJ2oXMJSFcAjDWyj.fXYsuEzOhFhd9UFhylUyS0TvpTvwP2vTGKYu', NULL, '2026-08-09 11:33:52', '2026-08-10 07:20:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `user_id`, `total`, `created_at`, `updated_at`) VALUES
(1, 8, 40.50, '2026-07-31 12:45:12', '2026-07-31 12:45:12'),
(2, 13, 131.50, '2026-08-10 07:26:48', '2026-08-10 07:26:48');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indices de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_ventas_venta_id_foreign` (`venta_id`),
  ADD KEY `detalle_ventas_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ventas_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD CONSTRAINT `detalle_ventas_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `detalle_ventas_venta_id_foreign` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
