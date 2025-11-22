-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2025 at 12:45 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `proyecto_suplementos`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carritos`
--

CREATE TABLE `carritos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carritos`
--

INSERT INTO `carritos` (`id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 3, '2025-11-18 12:00:53', '2025-11-18 12:00:53'),
(2, 13, '2025-11-19 12:47:37', '2025-11-19 12:47:37');

-- --------------------------------------------------------

--
-- Table structure for table `carrito_items`
--

CREATE TABLE `carrito_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `carrito_id` bigint(20) UNSIGNED NOT NULL,
  `producto_id` bigint(20) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `precio` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `slug` varchar(120) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Proteínas', 'Suplementos de proteína para desarrollo y recuperación muscular', 'proteinas', '2025-11-18 09:25:29', '2025-11-18 09:25:29'),
(2, 'Creatinas', 'Creatina para aumentar la fuerza y el rendimiento', 'creatinas', '2025-11-18 09:25:29', '2025-11-18 09:25:29'),
(3, 'Pre-Entreno', 'Suplementos pre-entreno para máxima energía y rendimiento', 'pre-entreno', '2025-11-18 09:25:29', '2025-11-18 09:25:29'),
(4, 'Vitaminas', 'Vitaminas y minerales para tu salud general', 'vitaminas', '2025-11-18 09:25:29', '2025-11-18 09:25:29');

-- --------------------------------------------------------

--
-- Table structure for table `compras`
--

CREATE TABLE `compras` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `proveedor_id` bigint(20) UNSIGNED NOT NULL,
  `producto_id` bigint(20) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `notas` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
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
-- Table structure for table `favoritos`
--

CREATE TABLE `favoritos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `producto_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `favoritos`
--

INSERT INTO `favoritos` (`id`, `user_id`, `producto_id`, `created_at`, `updated_at`) VALUES
(1, 3, 6, '2025-11-18 12:44:04', '2025-11-18 12:44:04');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
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
-- Table structure for table `job_batches`
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
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_14_081437_create_categorias_table', 1),
(5, '2025_11_14_081437_create_productos_table', 1),
(6, '2025_11_14_081523_add_rol_to_users_table', 1),
(7, '2025_11_18_020420_create_carritos_table', 1),
(8, '2025_11_18_020421_create_carrito_items_table', 1),
(9, '2025_11_18_020422_create_pedidos_table', 1),
(10, '2025_11_18_020423_create_pedido_detalles_table', 1),
(11, '2025_11_18_020424_create_favoritos_table', 1),
(12, '2025_11_18_020425_create_proveedors_table', 1),
(14, '2025_11_17_000001_add_social_auth_to_users_table', 2),
(15, '2025_11_18_065557_create_compras_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('admin@nutrishop.com', '$2y$12$khnXSpEUudFLcrLwG5s/reRA.wwAQWX2ZCS.QqUMKRWG.ercv3RT6', '2025-11-18 09:36:01'),
('keyimot464@chaineor.com', '$2y$12$GwcOAfS94pepP6me3XfOlOmoQVSqkaF5mFXeE9YyQtcSgDVoA4D92', '2025-11-18 11:25:49'),
('truman@test.com', '$2y$12$nr6kO21Be.PUFpODvbdJyOITRyBCTUtIEco.hBj91e13QV9d7XnWS', '2025-11-18 11:23:51');

-- --------------------------------------------------------

--
-- Table structure for table `pedidos`
--

CREATE TABLE `pedidos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `numero_orden` varchar(255) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','procesando','enviado','entregado','cancelado') NOT NULL DEFAULT 'pendiente',
  `nombre_completo` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefono` varchar(255) NOT NULL,
  `direccion` text NOT NULL,
  `ciudad` varchar(255) NOT NULL,
  `codigo_postal` varchar(10) NOT NULL,
  `metodo_pago` varchar(255) DEFAULT NULL,
  `notas` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pedidos`
--

INSERT INTO `pedidos` (`id`, `user_id`, `numero_orden`, `subtotal`, `total`, `estado`, `nombre_completo`, `email`, `telefono`, `direccion`, `ciudad`, `codigo_postal`, `metodo_pago`, `notas`, `created_at`, `updated_at`) VALUES
(1, 13, 'ORD-691D6AA77892E', 1980.00, 2327.00, 'entregado', 'QUINCENA afafafd', 'correo@prueba.com', '99665074', 'Marias boutique hn, Mall Megaplaza, El Progroso yoro.', 'Tegucigalpa', '33126', 'tarjeta_credito', '\n--- Datos de Pago PixelPay ---\nTransaction ID: 1f694eee-4715-45b8-a545-000000000000\nPayment UUID: S-a064847d-6373-413d-8b8a-c58b57effd47\nMensaje: Pago realizado exitosamente', '2025-11-19 12:58:47', '2025-11-19 14:00:58'),
(2, 13, 'ORD-691D6D7BC76BB', 1400.00, 1660.00, 'entregado', 'TRUMAN CASTAÑEDA', 'correo@prueba.com', '89643972', 'Marias boutique hn, Mall Megaplaza, El Progroso yoro.', 'Tegucigalpa', '33126', 'efectivo', NULL, '2025-11-19 13:10:51', '2025-11-19 13:56:01'),
(3, 3, 'ORD-691D79EBBA8B2', 1980.00, 2327.00, 'entregado', 'TRUMAN CASTAÑEDA B', 'trumanhernan@gmail.com', '89643972', 'Colonia el hogar porton numero 2', 'Tegucigalpa', '33126', 'transferencia', NULL, '2025-11-19 14:03:55', '2025-11-19 14:10:47');

-- --------------------------------------------------------

--
-- Table structure for table `pedido_detalles`
--

CREATE TABLE `pedido_detalles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pedido_id` bigint(20) UNSIGNED NOT NULL,
  `producto_id` bigint(20) UNSIGNED NOT NULL,
  `nombre_producto` varchar(255) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pedido_detalles`
--

INSERT INTO `pedido_detalles` (`id`, `pedido_id`, `producto_id`, `nombre_producto`, `cantidad`, `precio_unitario`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'Mass Gainer', 1, 1980.00, 1980.00, '2025-11-19 12:58:47', '2025-11-19 12:58:47'),
(2, 2, 8, 'Pre-War', 1, 1400.00, 1400.00, '2025-11-19 13:10:51', '2025-11-19 13:10:51'),
(3, 3, 3, 'Mass Gainer', 1, 1980.00, 1980.00, '2025-11-19 14:03:55', '2025-11-19 14:03:55');

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `precio_oferta` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `imagen` varchar(255) DEFAULT NULL,
  `slug` varchar(220) NOT NULL,
  `categoria_id` bigint(20) UNSIGNED NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `destacado` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `precio_oferta`, `stock`, `imagen`, `slug`, `categoria_id`, `activo`, `destacado`, `created_at`, `updated_at`) VALUES
(1, 'Whey Protein', 'Proteina de suero de alta calidad', 2700.00, NULL, 50, 'ProteinaWhey.png', 'whey-protein', 1, 1, 1, '2025-11-18 09:25:29', '2025-11-18 09:25:29'),
(2, 'Iso 100', 'Proteina aislada de rapida absorcion', 3200.00, 2800.00, 1, 'iso100.png', 'iso-100', 1, 1, 1, '2025-11-18 09:25:29', '2025-11-19 12:50:24'),
(3, 'Mass Gainer', 'Ganador de peso con proteinas y carbohidratos', 1980.00, NULL, 48, 'mass_gainer.png', 'mass-gainer', 1, 1, 0, '2025-11-18 09:25:29', '2025-11-19 14:03:55'),
(4, 'Creatina Evolution', 'Creatina monohidrato pura', 890.00, NULL, 100, 'creatina_evolution.png', 'creatina-evolution', 2, 1, 0, '2025-11-18 09:25:29', '2025-11-18 09:25:29'),
(5, 'Creatina Basic', 'Creatina micronizada para mejor absorcion', 750.00, 650.00, 80, 'creatine_basic.png', 'creatina-basic', 2, 1, 1, '2025-11-18 09:25:29', '2025-11-18 09:25:29'),
(6, 'Creatina Epiq', 'Formula avanzada de creatina', 1100.00, NULL, 36, 'creatina_epiq.png', 'creatina-epiq', 2, 1, 0, '2025-11-18 09:25:29', '2025-11-19 12:18:04'),
(7, 'Pre-Entreno C4', 'Energia explosiva para tus entrenamientos', 1450.00, 1300.00, 60, 'Pre-Entreno_C4.png', 'pre-entreno-c4', 3, 1, 0, '2025-11-18 09:25:29', '2025-11-19 12:30:07'),
(8, 'Pre-War', 'Pre-entreno de alta potencia', 1650.00, 1400.00, 44, 'Pre-Entreno_PreWar.png', 'pre-war', 3, 1, 1, '2025-11-18 09:25:29', '2025-11-19 13:10:51'),
(9, 'Pre-Entreno Gold Standard', 'Pre-entreno de calidad premium', 1850.00, NULL, 7, 'Pre-Entreno_GoldStandard.png', 'pre-entreno-gold-standard', 3, 1, 0, '2025-11-18 09:25:29', '2025-11-19 12:51:12'),
(10, 'Omega-3', 'Acidos grasos esenciales para salud cardiovascular', 580.00, NULL, 80, 'omega-3.png', 'omega-3', 4, 1, 0, '2025-11-18 09:25:29', '2025-11-18 09:25:29'),
(11, 'Vitamina D3', 'Suplemento de vitamina D para huesos fuertes', 420.00, 350.00, 70, 'vitaminaD3.png', 'vitamina-d3', 4, 1, 1, '2025-11-18 09:25:29', '2025-11-18 09:25:29'),
(12, 'Vitamina C', 'Fortalece el sistema inmunologico', 350.00, NULL, 55, 'vitaminaC.png', 'vitamina-c', 4, 1, 0, '2025-11-18 09:25:29', '2025-11-18 09:25:29');

-- --------------------------------------------------------

--
-- Table structure for table `proveedors`
--

CREATE TABLE `proveedors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `empresa` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `ciudad` varchar(255) DEFAULT NULL,
  `pais` varchar(255) DEFAULT NULL,
  `notas` text DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `proveedors`
--

INSERT INTO `proveedors` (`id`, `nombre`, `empresa`, `email`, `telefono`, `direccion`, `ciudad`, `pais`, `notas`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'Optimum Nutrition', 'Glanbia Performance Nutrition', 'ventas@optimumnutrition.com', '+1-800-705-5226', '600 S. Cherry Street, Suite 200', 'Denver, CO', 'Estados Unidos', 'Proveedor principal de proteínas y suplementos deportivos', 1, '2025-11-18 09:25:29', '2025-11-18 09:25:29'),
(2, 'MuscleTech', 'Iovate Health Sciences', 'contacto@muscletech.com', '+1-888-334-4448', '3880 Jeffrey Blvd', 'Blasdell, NY', 'Estados Unidos', 'Especialista en creatinas y pre-entrenos', 1, '2025-11-18 09:25:29', '2025-11-18 09:25:29'),
(3, 'Cellucor', 'Nutrabolt', 'info@cellucor.com', '+1-866-927-9686', '3780 Kilroy Airport Way', 'Long Beach, CA', 'Estados Unidos', 'Conocido por la línea C4 de pre-entrenos', 1, '2025-11-18 09:25:29', '2025-11-18 09:25:29'),
(5, 'TRUMAN CASTAÑEDA', 'tcuth2025', 'trumanhernan@gmail.com', '89643972', 'Colonia el hogar porton numero 2', 'Tegucigalpa', 'Honduras', 'ESTE ES EL MEJOR', 1, '2025-11-19 11:42:12', '2025-11-19 11:42:12');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('6tJ6dtnaMpjwVLsYqV44QfBA2UcGjbchutRqI5Go', 13, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiY2Z0VnptdUVJTDJoRDZtZ3hHSFFuV2hLOW9KVDJpN3psUGNYNU5BciI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi92ZW50YXMiO3M6NToicm91dGUiO3M6MTM6InBlZGlkb3MuYWRtaW4iO31zOjU6InN0YXRlIjtzOjQwOiJKWTJIaW53Q2ZDSTcybWNXWG9qSlNIRk9FcHZTUEhyaGJueXVaUGVpIjtzOjM6InVybCI7YTowOnt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTM7fQ==', 1763539909),
('f197iDDC2PYlFRWdI0DVBIymqX1SnrV2pzXoqDn1', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQWxOMm1OZTJxT0JSeWpvaEhvN0VMS0R4UmlLb0ZzR0hoQzVReWFmaiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9mYXZvcml0b3MiO3M6NToicm91dGUiO3M6MTU6ImZhdm9yaXRvcy5pbmRleCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7fQ==', 1763575504),
('WCbbjlUygboUMMKXXzrLp6jMPNlWBPNuNfWJY4dS', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieEpQbVl5WjNQeDQwMHJIamUwa1FNZlpjampLM3g3S0VjblNiYjZRQyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wZWRpZG9zIjtzOjU6InJvdXRlIjtzOjEzOiJwZWRpZG9zLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1763539459);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `provider_id` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `rol` varchar(20) NOT NULL DEFAULT 'user',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `provider`, `provider_id`, `avatar`, `rol`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@nutrishop.com', NULL, NULL, NULL, 'admin', NULL, '$2y$12$7eXJ7gfKtmvgjZ/YAUKpdOB.Y.5vdkVpWuZjeilDuxZXpqF21iia2', NULL, '2025-11-18 09:25:29', '2025-11-18 13:36:05'),
(2, 'Usuario Demo', 'usuario@nutrishop.com', NULL, NULL, NULL, 'user', NULL, '$2y$12$8RPK8CJBLu2.oCeUBU5.eOtGiVVsPXSJ2gDo/eXgtly3ejUt0y1LK', NULL, '2025-11-18 09:25:29', '2025-11-18 09:25:29'),
(3, '[value-2]', 'trumanhernan@gmail.com', 'google', '107367980799157520930', 'https://lh3.googleusercontent.com/a/ACg8ocIjcj7CJYQcTjJOs5rVFTcYHex9h93JofrtYP4ewsCGdcwQFQ=s96-c', '[value-4]', '0000-00-00 00:00:00', '$2y$12$yvZIQ8q4GjCeVemFqnDZ/uZC5Ih8x19jPcmd4OezEklDI5wCS6UAG', 'DpJomcFlHdKLLEpC138intlGwbtG8CTWebel09cYLRznWODUpzxsEpXGsGCe', '0000-00-00 00:00:00', '2025-11-18 13:27:04'),
(4, 'Hector', 'truman@test.com', NULL, NULL, NULL, 'user', NULL, '$2y$12$6HSTBm.zU.OndGbqfxXTkO0VCoYH.psTFD2cUT8O/50ZBvN9umDsy', NULL, '2025-11-18 11:11:28', '2025-11-18 11:11:28'),
(5, 'Truman Castañeda', 'keyimot464@chaineor.com', NULL, NULL, NULL, 'user', NULL, '$2y$12$ZpevIbGBFlcpULVJwnlmUuVU2dP/Ozh1my0WEBZ5LzvwjDwIpi.pK', NULL, '2025-11-18 11:25:10', '2025-11-18 11:25:10'),
(6, 'Admin NutriShop', 'adminnutrishop@example.com', NULL, NULL, NULL, 'admin', '2025-11-18 07:29:54', '$2y$10$e0NRZzjvZK1Z9Z9Z9Z9Z9uZ9Z9Z9Z9Z9Z9Z9Z9Z9Z9Z9Z9Z9Z9Z9', NULL, '2025-11-18 07:29:54', '2025-11-18 07:29:54'),
(10, 'Cajero Demo', 'cajero@nutrishop.com', NULL, NULL, NULL, 'cajero', '2025-11-18 14:09:03', '$2y$12$K3Hp7nHRPn1MQTO3zFwYzOlFfHkpITHBh1BM377lUmaZ5Bv4uW7Na', NULL, '2025-11-18 14:09:03', '2025-11-18 14:09:03'),
(12, 'CHRIS', 'chris@gmail.com', NULL, NULL, NULL, 'admin', '2025-11-19 11:39:39', '$2y$12$2xS2/CIMtSKVTcg2L4QB5.3VL3Plv79B5qWSe1YoaZFh2vRoX5XA.', NULL, '2025-11-19 11:39:39', '2025-11-19 11:39:39'),
(13, 'QUINCENA', 'correo@prueba.com', NULL, NULL, 'avatars/teYq0jGuA7sUgBd2DhuZDzohPeN68PkFfJFo4Y9s.jpg', 'admin', '2025-11-19 12:42:25', '$2y$12$4xu.KkXOlHmk9DSuLixBzOhfqGhCt1eistDgEkqPyiWMkWDL1laSe', NULL, '2025-11-19 12:42:25', '2025-11-19 12:42:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `carritos`
--
ALTER TABLE `carritos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carritos_user_id_foreign` (`user_id`);

--
-- Indexes for table `carrito_items`
--
ALTER TABLE `carrito_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carrito_items_carrito_id_foreign` (`carrito_id`),
  ADD KEY `carrito_items_producto_id_foreign` (`producto_id`);

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categorias_slug_unique` (`slug`);

--
-- Indexes for table `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `compras_proveedor_id_foreign` (`proveedor_id`),
  ADD KEY `compras_producto_id_foreign` (`producto_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `favoritos_user_id_producto_id_unique` (`user_id`,`producto_id`),
  ADD KEY `favoritos_producto_id_foreign` (`producto_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pedidos_numero_orden_unique` (`numero_orden`),
  ADD KEY `pedidos_user_id_foreign` (`user_id`);

--
-- Indexes for table `pedido_detalles`
--
ALTER TABLE `pedido_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_detalles_pedido_id_foreign` (`pedido_id`),
  ADD KEY `pedido_detalles_producto_id_foreign` (`producto_id`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `productos_slug_unique` (`slug`),
  ADD KEY `productos_categoria_id_foreign` (`categoria_id`);

--
-- Indexes for table `proveedors`
--
ALTER TABLE `proveedors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `proveedors_email_unique` (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carritos`
--
ALTER TABLE `carritos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `carrito_items`
--
ALTER TABLE `carrito_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `compras`
--
ALTER TABLE `compras`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pedido_detalles`
--
ALTER TABLE `pedido_detalles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `proveedors`
--
ALTER TABLE `proveedors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carritos`
--
ALTER TABLE `carritos`
  ADD CONSTRAINT `carritos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carrito_items`
--
ALTER TABLE `carrito_items`
  ADD CONSTRAINT `carrito_items_carrito_id_foreign` FOREIGN KEY (`carrito_id`) REFERENCES `carritos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carrito_items_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `compras_proveedor_id_foreign` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoritos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pedido_detalles`
--
ALTER TABLE `pedido_detalles`
  ADD CONSTRAINT `pedido_detalles_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pedido_detalles_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
