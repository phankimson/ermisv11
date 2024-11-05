-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 05, 2024 lúc 07:10 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `ermis`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `area`
--

CREATE TABLE `area` (
  `id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `regions` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `name_en` varchar(100) DEFAULT NULL,
  `active` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chat`
--

CREATE TABLE `chat` (
  `id` varchar(36) NOT NULL,
  `user_send` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `user_receipt` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chat`
--

INSERT INTO `chat` (`id`, `user_send`, `user_receipt`, `message`, `created_at`, `updated_at`) VALUES
('164d890d-8d40-4c90-a070-f73439e469e9', '6affb799-6bbf-11ec-a352-309c23e68112', '6affb799-6bbf-11ec-a352-309c23e68112', 'test 33', '2022-02-26 12:41:42', '2022-02-26 12:41:42'),
('5a6b2709-4aed-4ef9-90a6-b692d236f237', '6affb799-6bbf-11ec-a352-309c23e68112', '6affb799-6bbf-11ec-a352-309c23e68112', 'test 10', '2022-02-26 12:41:46', '2022-02-26 12:41:46'),
('d71ee443-24a8-4051-a770-b5f096212f08', '6affb799-6bbf-11ec-a352-309c23e68112', '6affb799-6bbf-11ec-a352-309c23e68112', 'test 22', '2022-02-26 12:41:37', '2022-02-26 12:41:37'),
('dfb35674-ec3b-4d97-84ee-db10f6a0697d', '6affb799-6bbf-11ec-a352-309c23e68112', '6affb799-6bbf-11ec-a352-309c23e68112', 'test 11', '2022-02-26 12:41:34', '2022-02-26 12:41:34');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `company`
--

CREATE TABLE `company` (
  `id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tax_code` varchar(15) NOT NULL,
  `director` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `full_name_contact` varchar(50) DEFAULT NULL,
  `address_contact` varchar(100) DEFAULT NULL,
  `title_contact` varchar(100) DEFAULT NULL,
  `email_contact` varchar(50) DEFAULT NULL,
  `telephone1_contact` varchar(20) DEFAULT NULL,
  `telephone2_contact` varchar(20) DEFAULT NULL,
  `country` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `regions` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `area` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `distric` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `marketing` varchar(100) DEFAULT NULL,
  `company_size` tinyint(4) DEFAULT NULL,
  `level` int(4) DEFAULT NULL,
  `active` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `company`
--

INSERT INTO `company` (`id`, `code`, `name`, `address`, `email`, `tax_code`, `director`, `phone`, `fax`, `full_name_contact`, `address_contact`, `title_contact`, `email_contact`, `telephone1_contact`, `telephone2_contact`, `country`, `regions`, `area`, `distric`, `marketing`, `company_size`, `level`, `active`, `created_at`, `updated_at`) VALUES
('9aecf6c6-827d-11ec-aea9-b42e9986cd6c', '4201663371', 'Công ty TNHH ACB', '9 Phan Chu Trinh', 'phankimson1988@gmail.com', '4201663371', 'Phan Kim Sơn', '02583829926', '02583829926', NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', NULL, NULL, 1, 1, '2019-06-16 05:50:59', '2019-06-16 05:50:59');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `company_software`
--

CREATE TABLE `company_software` (
  `id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `company_id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `software_id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL DEFAULT '0',
  `license_id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `free` int(11) NOT NULL DEFAULT 0,
  `database` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `active` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `company_software`
--

INSERT INTO `company_software` (`id`, `company_id`, `software_id`, `license_id`, `free`, `database`, `username`, `password`, `active`, `created_at`, `updated_at`) VALUES
('145436df-827e-11ec-aea9-b42e9986cd6c', '9aecf6c6-827d-11ec-aea9-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', 'c09ce83e-827e-11ec-aea9-b42e9986cd6c', 60, 'ermis', 'root', ' ', 1, '2019-12-31 21:39:08', '2022-02-24 17:10:04'),
('145556b5-827e-11ec-aea9-b42e9986cd6c', '9aecf6c6-827d-11ec-aea9-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'c09ce977-827e-11ec-aea9-b42e9986cd6c', 60, 'acc_ermis', 'root', '', 1, '2019-12-31 21:39:08', '2022-02-24 17:10:04');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `country`
--

CREATE TABLE `country` (
  `id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `code` varchar(3) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phonecode` int(11) DEFAULT NULL,
  `active` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `country`
--

INSERT INTO `country` (`id`, `code`, `name`, `phonecode`, `active`, `created_at`, `updated_at`) VALUES
('424163d3-6be1-11ec-a352-309c23e68112', 'AF', 'Afghanistan', 93, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42420ca4-6be1-11ec-a352-309c23e68112', 'AR', 'Argentina', 54, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42420d3e-6be1-11ec-a352-309c23e68112', 'IS', 'Iceland', 354, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42420d89-6be1-11ec-a352-309c23e68112', 'IN', 'India', 91, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42420dcb-6be1-11ec-a352-309c23e68112', 'ID', 'Indonesia', 62, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42420e0d-6be1-11ec-a352-309c23e68112', 'IR', 'Iran', 98, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42420e47-6be1-11ec-a352-309c23e68112', 'IQ', 'Iraq', 964, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42420e80-6be1-11ec-a352-309c23e68112', 'IE', 'Ireland', 353, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42420eb7-6be1-11ec-a352-309c23e68112', 'IL', 'Israel', 972, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42420eed-6be1-11ec-a352-309c23e68112', 'IT', 'Italy', 39, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42420f23-6be1-11ec-a352-309c23e68112', 'JM', 'Jamaica', 1876, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42420f58-6be1-11ec-a352-309c23e68112', 'JP', 'Japan', 81, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42420fbd-6be1-11ec-a352-309c23e68112', 'AM', 'Armenia', 374, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42420ffa-6be1-11ec-a352-309c23e68112', 'XJ', 'Jersey', 44, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42421030-6be1-11ec-a352-309c23e68112', 'JO', 'Jordan', 962, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242106a-6be1-11ec-a352-309c23e68112', 'KZ', 'Kazakhstan', 7, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424210a0-6be1-11ec-a352-309c23e68112', 'KE', 'Kenya', 254, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424210d6-6be1-11ec-a352-309c23e68112', 'KI', 'Kiribati', 686, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242110c-6be1-11ec-a352-309c23e68112', 'KP', 'Korea North', 850, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42421143-6be1-11ec-a352-309c23e68112', 'KR', 'Korea South', 82, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242117a-6be1-11ec-a352-309c23e68112', 'KW', 'Kuwait', 965, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424211b2-6be1-11ec-a352-309c23e68112', 'KG', 'Kyrgyzstan', 996, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424211ea-6be1-11ec-a352-309c23e68112', 'LA', 'Laos', 856, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42421221-6be1-11ec-a352-309c23e68112', 'AW', 'Aruba', 297, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42421257-6be1-11ec-a352-309c23e68112', 'LV', 'Latvia', 371, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242128c-6be1-11ec-a352-309c23e68112', 'LB', 'Lebanon', 961, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424212de-6be1-11ec-a352-309c23e68112', 'LS', 'Lesotho', 266, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42421315-6be1-11ec-a352-309c23e68112', 'LR', 'Liberia', 231, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242134c-6be1-11ec-a352-309c23e68112', 'LY', 'Libya', 218, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42421381-6be1-11ec-a352-309c23e68112', 'LI', 'Liechtenstein', 423, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424213cc-6be1-11ec-a352-309c23e68112', 'LT', 'Lithuania', 370, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42421404-6be1-11ec-a352-309c23e68112', 'LU', 'Luxembourg', 352, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242143a-6be1-11ec-a352-309c23e68112', 'MO', 'Macau S.A.R.', 853, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242159a-6be1-11ec-a352-309c23e68112', 'MK', 'Macedonia', 389, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424215d4-6be1-11ec-a352-309c23e68112', 'AU', 'Australia', 61, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242160a-6be1-11ec-a352-309c23e68112', 'MG', 'Madagascar', 261, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242163f-6be1-11ec-a352-309c23e68112', 'MW', 'Malawi', 265, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42421676-6be1-11ec-a352-309c23e68112', 'MY', 'Malaysia', 60, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424216ad-6be1-11ec-a352-309c23e68112', 'MV', 'Maldives', 960, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424216e9-6be1-11ec-a352-309c23e68112', 'ML', 'Mali', 223, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42421721-6be1-11ec-a352-309c23e68112', 'MT', 'Malta', 356, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42421758-6be1-11ec-a352-309c23e68112', 'XM', 'Man (Isle of)', 44, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42421792-6be1-11ec-a352-309c23e68112', 'MH', 'Marshall Islands', 692, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42422109-6be1-11ec-a352-309c23e68112', 'MQ', 'Martinique', 596, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242219a-6be1-11ec-a352-309c23e68112', 'MR', 'Mauritania', 222, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424221e0-6be1-11ec-a352-309c23e68112', 'AT', 'Austria', 43, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242221a-6be1-11ec-a352-309c23e68112', 'MU', 'Mauritius', 230, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42422254-6be1-11ec-a352-309c23e68112', 'YT', 'Mayotte', 269, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42422289-6be1-11ec-a352-309c23e68112', 'MX', 'Mexico', 52, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424222be-6be1-11ec-a352-309c23e68112', 'FM', 'Micronesia', 691, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424222f4-6be1-11ec-a352-309c23e68112', 'MD', 'Moldova', 373, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242232a-6be1-11ec-a352-309c23e68112', 'MC', 'Monaco', 377, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242235e-6be1-11ec-a352-309c23e68112', 'MN', 'Mongolia', 976, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42422394-6be1-11ec-a352-309c23e68112', 'MS', 'Montserrat', 1664, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424223cd-6be1-11ec-a352-309c23e68112', 'MA', 'Morocco', 212, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42422405-6be1-11ec-a352-309c23e68112', 'MZ', 'Mozambique', 258, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242243a-6be1-11ec-a352-309c23e68112', 'AZ', 'Azerbaijan', 994, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42422471-6be1-11ec-a352-309c23e68112', 'MM', 'Myanmar', 95, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424224a9-6be1-11ec-a352-309c23e68112', 'NA', 'Namibia', 264, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424224df-6be1-11ec-a352-309c23e68112', 'NR', 'Nauru', 674, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42422514-6be1-11ec-a352-309c23e68112', 'NP', 'Nepal', 977, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42422549-6be1-11ec-a352-309c23e68112', 'AN', 'Netherlands Antilles', 599, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42422596-6be1-11ec-a352-309c23e68112', 'NL', 'Netherlands The', 31, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424225cf-6be1-11ec-a352-309c23e68112', 'NC', 'New Caledonia', 687, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42422607-6be1-11ec-a352-309c23e68112', 'NZ', 'New Zealand', 64, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242263f-6be1-11ec-a352-309c23e68112', 'NI', 'Nicaragua', 505, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42422677-6be1-11ec-a352-309c23e68112', 'NE', 'Niger', 227, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424226af-6be1-11ec-a352-309c23e68112', 'BS', 'Bahamas The', 1242, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424226e3-6be1-11ec-a352-309c23e68112', 'NG', 'Nigeria', 234, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42422718-6be1-11ec-a352-309c23e68112', 'NU', 'Niue', 683, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42422d00-6be1-11ec-a352-309c23e68112', 'NF', 'Norfolk Island', 672, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42422d51-6be1-11ec-a352-309c23e68112', 'MP', 'Northern Mariana Islands', 1670, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42422d8f-6be1-11ec-a352-309c23e68112', 'NO', 'Norway', 47, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42422ddd-6be1-11ec-a352-309c23e68112', 'OM', 'Oman', 968, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42422e1b-6be1-11ec-a352-309c23e68112', 'PK', 'Pakistan', 92, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42422e59-6be1-11ec-a352-309c23e68112', 'PW', 'Palau', 680, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42422e90-6be1-11ec-a352-309c23e68112', 'PS', 'Palestinian Territory Occupied', 970, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42422ec9-6be1-11ec-a352-309c23e68112', 'PA', 'Panama', 507, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42422f02-6be1-11ec-a352-309c23e68112', 'BH', 'Bahrain', 973, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42422f3c-6be1-11ec-a352-309c23e68112', 'PG', 'Papua new Guinea', 675, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42422f74-6be1-11ec-a352-309c23e68112', 'PY', 'Paraguay', 595, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42422fac-6be1-11ec-a352-309c23e68112', 'PE', 'Peru', 51, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42422fe3-6be1-11ec-a352-309c23e68112', 'PH', 'Philippines', 63, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242301c-6be1-11ec-a352-309c23e68112', 'PN', 'Pitcairn Island', 0, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423053-6be1-11ec-a352-309c23e68112', 'PL', 'Poland', 48, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242308a-6be1-11ec-a352-309c23e68112', 'PT', 'Portugal', 351, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424230c4-6be1-11ec-a352-309c23e68112', 'PR', 'Puerto Rico', 1787, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424230fc-6be1-11ec-a352-309c23e68112', 'QA', 'Qatar', 974, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423133-6be1-11ec-a352-309c23e68112', 'RE', 'Reunion', 262, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242316b-6be1-11ec-a352-309c23e68112', 'BD', 'Bangladesh', 880, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424231b5-6be1-11ec-a352-309c23e68112', 'RO', 'Romania', 40, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424231f0-6be1-11ec-a352-309c23e68112', 'RU', 'Russia', 70, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242323e-6be1-11ec-a352-309c23e68112', 'RW', 'Rwanda', 250, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423278-6be1-11ec-a352-309c23e68112', 'SH', 'Saint Helena', 290, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424232b4-6be1-11ec-a352-309c23e68112', 'KN', 'Saint Kitts And Nevis', 1869, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424232ec-6be1-11ec-a352-309c23e68112', 'LC', 'Saint Lucia', 1758, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423325-6be1-11ec-a352-309c23e68112', 'PM', 'Saint Pierre and Miquelon', 508, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242335e-6be1-11ec-a352-309c23e68112', 'VC', 'Saint Vincent And The Grenadines', 1784, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423399-6be1-11ec-a352-309c23e68112', 'WS', 'Samoa', 684, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424233d2-6be1-11ec-a352-309c23e68112', 'SM', 'San Marino', 378, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242340a-6be1-11ec-a352-309c23e68112', 'BB', 'Barbados', 1246, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423443-6be1-11ec-a352-309c23e68112', 'ST', 'Sao Tome and Principe', 239, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242347c-6be1-11ec-a352-309c23e68112', 'SA', 'Saudi Arabia', 966, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424234b6-6be1-11ec-a352-309c23e68112', 'SN', 'Senegal', 221, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424234ed-6be1-11ec-a352-309c23e68112', 'RS', 'Serbia', 381, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423524-6be1-11ec-a352-309c23e68112', 'SC', 'Seychelles', 248, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242355e-6be1-11ec-a352-309c23e68112', 'SL', 'Sierra Leone', 232, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424235a6-6be1-11ec-a352-309c23e68112', 'SG', 'Singapore', 65, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424235e0-6be1-11ec-a352-309c23e68112', 'SK', 'Slovakia', 421, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423618-6be1-11ec-a352-309c23e68112', 'SI', 'Slovenia', 386, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423651-6be1-11ec-a352-309c23e68112', 'XG', 'Smaller Territories of the UK', 44, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242368d-6be1-11ec-a352-309c23e68112', 'AL', 'Albania', 355, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424236c6-6be1-11ec-a352-309c23e68112', 'BY', 'Belarus', 375, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424236ff-6be1-11ec-a352-309c23e68112', 'SB', 'Solomon Islands', 677, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423739-6be1-11ec-a352-309c23e68112', 'SO', 'Somalia', 252, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423774-6be1-11ec-a352-309c23e68112', 'ZA', 'South Africa', 27, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424237ac-6be1-11ec-a352-309c23e68112', 'GS', 'South Georgia', 0, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424237e5-6be1-11ec-a352-309c23e68112', 'SS', 'South Sudan', 211, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242381e-6be1-11ec-a352-309c23e68112', 'ES', 'Spain', 34, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423856-6be1-11ec-a352-309c23e68112', 'LK', 'Sri Lanka', 94, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242388d-6be1-11ec-a352-309c23e68112', 'SD', 'Sudan', 249, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424238c4-6be1-11ec-a352-309c23e68112', 'SR', 'Suriname', 597, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423900-6be1-11ec-a352-309c23e68112', 'SJ', 'Svalbard And Jan Mayen Islands', 47, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423939-6be1-11ec-a352-309c23e68112', 'BE', 'Belgium', 32, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423983-6be1-11ec-a352-309c23e68112', 'SZ', 'Swaziland', 268, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424239bc-6be1-11ec-a352-309c23e68112', 'SE', 'Sweden', 46, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424239f4-6be1-11ec-a352-309c23e68112', 'CH', 'Switzerland', 41, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423a2c-6be1-11ec-a352-309c23e68112', 'SY', 'Syria', 963, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423a63-6be1-11ec-a352-309c23e68112', 'TW', 'Taiwan', 886, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423a9a-6be1-11ec-a352-309c23e68112', 'TJ', 'Tajikistan', 992, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423ad2-6be1-11ec-a352-309c23e68112', 'TZ', 'Tanzania', 255, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423b0b-6be1-11ec-a352-309c23e68112', 'TH', 'Thailand', 66, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423b43-6be1-11ec-a352-309c23e68112', 'TG', 'Togo', 228, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423b7b-6be1-11ec-a352-309c23e68112', 'TK', 'Tokelau', 690, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423bb4-6be1-11ec-a352-309c23e68112', 'BZ', 'Belize', 501, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423bed-6be1-11ec-a352-309c23e68112', 'TO', 'Tonga', 676, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423c24-6be1-11ec-a352-309c23e68112', 'TT', 'Trinidad And Tobago', 1868, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423c5c-6be1-11ec-a352-309c23e68112', 'TN', 'Tunisia', 216, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423c95-6be1-11ec-a352-309c23e68112', 'TR', 'Turkey', 90, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423ccd-6be1-11ec-a352-309c23e68112', 'TM', 'Turkmenistan', 7370, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423d05-6be1-11ec-a352-309c23e68112', 'TC', 'Turks And Caicos Islands', 1649, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423d3c-6be1-11ec-a352-309c23e68112', 'TV', 'Tuvalu', 688, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423d90-6be1-11ec-a352-309c23e68112', 'UG', 'Uganda', 256, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423dcb-6be1-11ec-a352-309c23e68112', 'UA', 'Ukraine', 380, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423e03-6be1-11ec-a352-309c23e68112', 'AE', 'United Arab Emirates', 971, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423e3c-6be1-11ec-a352-309c23e68112', 'BJ', 'Benin', 229, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423e75-6be1-11ec-a352-309c23e68112', 'GB', 'United Kingdom', 44, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423eae-6be1-11ec-a352-309c23e68112', 'US', 'United States', 1, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423ee7-6be1-11ec-a352-309c23e68112', 'UM', 'United States Minor Outlying Islands', 1, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423f21-6be1-11ec-a352-309c23e68112', 'UY', 'Uruguay', 598, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423f5a-6be1-11ec-a352-309c23e68112', 'UZ', 'Uzbekistan', 998, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423f92-6be1-11ec-a352-309c23e68112', 'VU', 'Vanuatu', 678, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42423fc9-6be1-11ec-a352-309c23e68112', 'VA', 'Vatican City State (Holy See)', 39, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42424002-6be1-11ec-a352-309c23e68112', 'VE', 'Venezuela', 58, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4242403b-6be1-11ec-a352-309c23e68112', 'VN', 'Vietnam', 84, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d6ad0-6be1-11ec-a352-309c23e68112', 'VG', 'Virgin Islands (British)', 1284, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d6bd8-6be1-11ec-a352-309c23e68112', 'BM', 'Bermuda', 1441, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d6ca0-6be1-11ec-a352-309c23e68112', 'VI', 'Virgin Islands (US)', 1340, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d6d17-6be1-11ec-a352-309c23e68112', 'WF', 'Wallis And Futuna Islands', 681, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d6d81-6be1-11ec-a352-309c23e68112', 'EH', 'Western Sahara', 212, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d6de8-6be1-11ec-a352-309c23e68112', 'YE', 'Yemen', 967, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d6e4a-6be1-11ec-a352-309c23e68112', 'YU', 'Yugoslavia', 38, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d6eae-6be1-11ec-a352-309c23e68112', 'ZM', 'Zambia', 260, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d6f10-6be1-11ec-a352-309c23e68112', 'ZW', 'Zimbabwe', 263, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d6f73-6be1-11ec-a352-309c23e68112', 'BT', 'Bhutan', 975, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d6fd4-6be1-11ec-a352-309c23e68112', 'BO', 'Bolivia', 591, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d7037-6be1-11ec-a352-309c23e68112', 'BA', 'Bosnia and Herzegovina', 387, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d7097-6be1-11ec-a352-309c23e68112', 'BW', 'Botswana', 267, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d70fd-6be1-11ec-a352-309c23e68112', 'BV', 'Bouvet Island', 0, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d7429-6be1-11ec-a352-309c23e68112', 'DZ', 'Algeria', 213, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d749b-6be1-11ec-a352-309c23e68112', 'BR', 'Brazil', 55, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d74ff-6be1-11ec-a352-309c23e68112', 'IO', 'British Indian Ocean Territory', 246, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d7569-6be1-11ec-a352-309c23e68112', 'BN', 'Brunei', 673, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d75cb-6be1-11ec-a352-309c23e68112', 'BG', 'Bulgaria', 359, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d7648-6be1-11ec-a352-309c23e68112', 'BF', 'Burkina Faso', 226, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d76a9-6be1-11ec-a352-309c23e68112', 'BI', 'Burundi', 257, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d7709-6be1-11ec-a352-309c23e68112', 'KH', 'Cambodia', 855, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d7766-6be1-11ec-a352-309c23e68112', 'CM', 'Cameroon', 237, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d77cb-6be1-11ec-a352-309c23e68112', 'CA', 'Canada', 1, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d782a-6be1-11ec-a352-309c23e68112', 'CV', 'Cape Verde', 238, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d788b-6be1-11ec-a352-309c23e68112', 'AS', 'American Samoa', 1684, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d78eb-6be1-11ec-a352-309c23e68112', 'KY', 'Cayman Islands', 1345, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d794b-6be1-11ec-a352-309c23e68112', 'CF', 'Central African Republic', 236, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d79ac-6be1-11ec-a352-309c23e68112', 'TD', 'Chad', 235, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d7a0e-6be1-11ec-a352-309c23e68112', 'CL', 'Chile', 56, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d7a7a-6be1-11ec-a352-309c23e68112', 'CN', 'China', 86, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d7ad7-6be1-11ec-a352-309c23e68112', 'CX', 'Christmas Island', 61, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d7b36-6be1-11ec-a352-309c23e68112', 'CC', 'Cocos (Keeling) Islands', 672, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('424d7b96-6be1-11ec-a352-309c23e68112', 'CO', 'Colombia', 57, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252835d-6be1-11ec-a352-309c23e68112', 'KM', 'Comoros', 269, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42528655-6be1-11ec-a352-309c23e68112', 'CG', 'Republic Of The Congo', 242, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('425287c2-6be1-11ec-a352-309c23e68112', 'AD', 'Andorra', 376, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('425288ec-6be1-11ec-a352-309c23e68112', 'CD', 'Democratic Republic Of The Congo', 242, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42528a18-6be1-11ec-a352-309c23e68112', 'CK', 'Cook Islands', 682, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42528b30-6be1-11ec-a352-309c23e68112', 'CR', 'Costa Rica', 506, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42528c54-6be1-11ec-a352-309c23e68112', 'CI', 'Cote D\'Ivoire (Ivory Coast)', 225, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42528d6a-6be1-11ec-a352-309c23e68112', 'HR', 'Croatia (Hrvatska)', 385, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42528e7f-6be1-11ec-a352-309c23e68112', 'CU', 'Cuba', 53, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42528f8e-6be1-11ec-a352-309c23e68112', 'CY', 'Cyprus', 357, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('425290ab-6be1-11ec-a352-309c23e68112', 'CZ', 'Czech Republic', 420, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('425291bc-6be1-11ec-a352-309c23e68112', 'DK', 'Denmark', 45, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252932b-6be1-11ec-a352-309c23e68112', 'DJ', 'Djibouti', 253, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42529829-6be1-11ec-a352-309c23e68112', 'AO', 'Angola', 244, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42529af5-6be1-11ec-a352-309c23e68112', 'DM', 'Dominica', 1767, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42529c79-6be1-11ec-a352-309c23e68112', 'DO', 'Dominican Republic', 1809, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42529dc2-6be1-11ec-a352-309c23e68112', 'TP', 'East Timor', 670, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('42529f04-6be1-11ec-a352-309c23e68112', 'EC', 'Ecuador', 593, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252a040-6be1-11ec-a352-309c23e68112', 'EG', 'Egypt', 20, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252a236-6be1-11ec-a352-309c23e68112', 'SV', 'El Salvador', 503, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252a369-6be1-11ec-a352-309c23e68112', 'GQ', 'Equatorial Guinea', 240, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252a48a-6be1-11ec-a352-309c23e68112', 'ER', 'Eritrea', 291, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252a59e-6be1-11ec-a352-309c23e68112', 'EE', 'Estonia', 372, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252a6bd-6be1-11ec-a352-309c23e68112', 'ET', 'Ethiopia', 251, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252a7cb-6be1-11ec-a352-309c23e68112', 'AI', 'Anguilla', 1264, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252a8f2-6be1-11ec-a352-309c23e68112', 'XA', 'External Territories of Australia', 61, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252aa1d-6be1-11ec-a352-309c23e68112', 'FK', 'Falkland Islands', 500, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252ab3d-6be1-11ec-a352-309c23e68112', 'FO', 'Faroe Islands', 298, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252ac4c-6be1-11ec-a352-309c23e68112', 'FJ', 'Fiji Islands', 679, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252ad54-6be1-11ec-a352-309c23e68112', 'FI', 'Finland', 358, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252ae6b-6be1-11ec-a352-309c23e68112', 'FR', 'France', 33, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252af7e-6be1-11ec-a352-309c23e68112', 'GF', 'French Guiana', 594, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252b08f-6be1-11ec-a352-309c23e68112', 'PF', 'French Polynesia', 689, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252b19c-6be1-11ec-a352-309c23e68112', 'TF', 'French Southern Territories', 0, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252b2b4-6be1-11ec-a352-309c23e68112', 'GA', 'Gabon', 241, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252b3c8-6be1-11ec-a352-309c23e68112', 'AQ', 'Antarctica', 0, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252b531-6be1-11ec-a352-309c23e68112', 'GM', 'Gambia The', 220, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252b64f-6be1-11ec-a352-309c23e68112', 'GE', 'Georgia', 995, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252b76a-6be1-11ec-a352-309c23e68112', 'DE', 'Germany', 49, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252b87f-6be1-11ec-a352-309c23e68112', 'GH', 'Ghana', 233, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252ba16-6be1-11ec-a352-309c23e68112', 'GI', 'Gibraltar', 350, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252bb36-6be1-11ec-a352-309c23e68112', 'GR', 'Greece', 30, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252bc44-6be1-11ec-a352-309c23e68112', 'GL', 'Greenland', 299, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252bd53-6be1-11ec-a352-309c23e68112', 'GD', 'Grenada', 1473, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252be5c-6be1-11ec-a352-309c23e68112', 'GP', 'Guadeloupe', 590, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252bf6d-6be1-11ec-a352-309c23e68112', 'GU', 'Guam', 1671, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252c07d-6be1-11ec-a352-309c23e68112', 'AG', 'Antigua And Barbuda', 1268, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252c192-6be1-11ec-a352-309c23e68112', 'GT', 'Guatemala', 502, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252c29e-6be1-11ec-a352-309c23e68112', 'XU', 'Guernsey and Alderney', 44, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252c3b2-6be1-11ec-a352-309c23e68112', 'GN', 'Guinea', 224, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252c4c2-6be1-11ec-a352-309c23e68112', 'GW', 'Guinea-Bissau', 245, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252c5d1-6be1-11ec-a352-309c23e68112', 'GY', 'Guyana', 592, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252c6e7-6be1-11ec-a352-309c23e68112', 'HT', 'Haiti', 509, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252c7f2-6be1-11ec-a352-309c23e68112', 'HM', 'Heard and McDonald Islands', 0, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252c965-6be1-11ec-a352-309c23e68112', 'HN', 'Honduras', 504, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252ca7b-6be1-11ec-a352-309c23e68112', 'HK', 'Hong Kong S.A.R.', 852, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17'),
('4252cb93-6be1-11ec-a352-309c23e68112', 'HU', 'Hungary', 36, 1, '2022-01-02 15:37:17', '2022-01-02 15:37:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `distric`
--

CREATE TABLE `distric` (
  `id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `area` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `name_en` varchar(100) DEFAULT NULL,
  `active` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `document`
--

CREATE TABLE `document` (
  `id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `type` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `name_en` varchar(100) DEFAULT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `active` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `document`
--

INSERT INTO `document` (`id`, `type`, `code`, `name`, `name_en`, `date_start`, `date_end`, `description`, `content`, `active`, `created_at`, `updated_at`) VALUES
('2f62920d-723e-11ec-80d2-309c23e68112', 'd9439bf6-7162-11ec-821b-309c23e68112', 'ACC_SYSTEM_133', 'Thông tư 133', 'Account System 133', '0000-00-00', '0000-00-00', '', '', 1, '2020-07-28 04:17:06', '2022-01-10 16:26:54'),
('2f64b191-723e-11ec-80d2-309c23e68112', 'd9439bf6-7162-11ec-821b-309c23e68112', 'ACC_SYSTEM_200', 'Thông tư 200', 'Account System 200', '0000-00-00', '0000-00-00', '', '', 1, '2020-07-28 04:17:21', '2020-07-28 04:17:21');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `document_type`
--

CREATE TABLE `document_type` (
  `id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `name_en` varchar(100) DEFAULT NULL,
  `active` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `document_type`
--

INSERT INTO `document_type` (`id`, `code`, `name`, `name_en`, `active`, `created_at`, `updated_at`) VALUES
('d9439bf6-7162-11ec-821b-309c23e68112', 'ACC_SYSTEM', 'Chế độ kế toán', 'Accounting System', 1, '2020-07-28 04:03:30', '2020-07-28 04:14:36');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `error`
--

CREATE TABLE `error` (
  `id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `type` tinyint(4) NOT NULL,
  `menu_id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `user_id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `url` varchar(50) NOT NULL,
  `error` text NOT NULL,
  `check` smallint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `group_users`
--

CREATE TABLE `group_users` (
  `id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `company_id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `active` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `group_users`
--

INSERT INTO `group_users` (`id`, `company_id`, `code`, `name`, `active`, `created_at`, `updated_at`) VALUES
('6a9dc3e8-8f4f-460c-a13f-97b72bd45837', '9aecf6c6-827d-11ec-aea9-b42e9986cd6c', 'zz', 'rr', 1, '2022-06-12 17:27:49', '2022-06-12 17:27:49');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `group_users_permission`
--

CREATE TABLE `group_users_permission` (
  `id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `group_user_id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `menu_id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `permission` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `history_action`
--

CREATE TABLE `history_action` (
  `id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `type` int(11) NOT NULL,
  `user` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `menu` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `url` varchar(50) NOT NULL,
  `dataz` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `history_action`
--

INSERT INTO `history_action` (`id`, `type`, `user`, `menu`, `url`, `dataz`, `created_at`, `updated_at`) VALUES
('04551bc1-5089-488f-be96-95be702d79f9', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-07-03 04:06:36', '2022-07-03 04:06:36'),
('06b10b0a-ae2a-4a60-8a5d-af44420d3b02', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2023-11-14 09:33:21', '2023-11-14 09:33:21'),
('0704fee7-a969-444c-b9c6-49dc6fa51630', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-04-21 13:56:15', '2022-04-21 13:56:15'),
('09821f60-b420-402d-8391-2118850b89d6', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-03-13 09:47:38', '2022-03-13 09:47:38'),
('098662ba-4f93-43f7-9bd3-2f43a6e717c7', 2, '6affb799-6bbf-11ec-a352-309c23e68112', 'ee24fe91-8334-11ec-8b82-b42e9986cd6c', 'menu-save', '{\"type\":\"a79aca14-827e-11ec-aea9-b42e9986cd6c\",\"parent_id\":\"ee2504fc-8334-11ec-8b82-b42e9986cd6c\",\"code\":\"number-voucher-format\",\"name\":\"\\u0110\\u1ecbnh d\\u1ea1ng s\\u1ed1 th\\u1ee9 t\\u1ef1\",\"name_en\":\"Number Voucher Format\",\"icon\":\"\",\"link\":\"acc\\/number-voucher-format\",\"position\":\"0\",\"active\":1,\"id\":\"5b61b5e3-f451-4d71-9036-fc627290246c\",\"updated_at\":\"2023-06-23T14:07:25.000000Z\",\"created_at\":\"2023-06-23T14:07:25.000000Z\"}', '2023-06-23 14:07:25', '2023-06-23 14:07:25'),
('12d62471-47c0-4561-8a36-241ed688e26e', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-03-03 10:26:54', '2022-03-03 10:26:54'),
('1685d3b6-61f3-4dbc-9db3-e711e5908b9c', 0, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'logout', '', '2023-06-23 10:56:06', '2023-06-23 10:56:06'),
('1a2b767b-3e7e-4a5e-a70d-d62497075df0', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-06-19 08:04:01', '2022-06-19 08:04:01'),
('1d538d76-d3de-4341-9776-77ec819feed8', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-06-04 04:30:07', '2022-06-04 04:30:07'),
('1e843f00-2990-4b2d-8d74-b02ca1c5712f', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2023-04-03 10:46:07', '2023-04-03 10:46:07'),
('202a6703-5dae-4cf4-8190-bf14c1a09c32', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2023-06-22 10:45:10', '2023-06-22 10:45:10'),
('21e5c87d-db48-495f-9ae7-145929fd8448', 0, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'logout', '', '2023-06-22 10:44:25', '2023-06-22 10:44:25'),
('234fecd8-7bcb-48cd-95c8-b2c254dc4a88', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-04-06 14:35:15', '2022-04-06 14:35:15'),
('2355377f-5540-4cb5-8400-2e2f52f7bf58', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2023-06-23 11:01:49', '2023-06-23 11:01:49'),
('2650168c-22a6-4ea9-8b91-cdca8d6ca77c', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-06-25 13:25:19', '2022-06-25 13:25:19'),
('2688acb5-37ce-4c55-a5c4-ea2d4716f753', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-03-01 12:18:39', '2022-03-01 12:18:39'),
('28537993-7607-41c9-8be0-dd03fceb07f3', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-03-28 15:13:56', '2022-03-28 15:13:56'),
('2a68e479-5ccf-4f12-9243-53c78a9505de', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-04-12 15:32:46', '2022-04-12 15:32:46'),
('2b0b0861-1a4f-4fb6-97dd-df033e0c8c29', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-06-16 16:37:17', '2022-06-16 16:37:17'),
('2c37996f-721c-45f1-ab98-685f995da21c', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-06-14 15:01:14', '2022-06-14 15:01:14'),
('2f45c992-64c6-4454-b83e-f378c4b4f808', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2023-06-23 14:05:22', '2023-06-23 14:05:22'),
('3016559e-dc48-463a-a7d8-e02e89a305a2', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2023-11-08 16:16:30', '2023-11-08 16:16:30'),
('32c3ac38-d1f6-46af-90e2-3ec70a769855', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-03-03 16:40:18', '2022-03-03 16:40:18'),
('339fcd6e-326d-4bf8-bcdd-b41b90ac8847', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2023-06-23 10:56:58', '2023-06-23 10:56:58'),
('360747f0-88eb-42b5-b869-ade0bed55f6d', 4, '6affb799-6bbf-11ec-a352-309c23e68112', 'ee25009e-8334-11ec-8b82-b42e9986cd6c', 'area-delete', '{\"id\":\"c138b58c-4b13-4175-a6c6-d170662cb9ea\",\"regions\":\"0\",\"code\":\"NCC0001\",\"name\":\"C\\u00f4ng ty TNHH ACB\",\"name_en\":\"Keyword Ai\",\"active\":1,\"created_at\":\"2022-03-01T13:15:15.000000Z\",\"updated_at\":\"2022-03-01T13:15:15.000000Z\"}', '2022-03-01 13:15:19', '2022-03-01 13:15:19'),
('37079a02-e407-41ef-8c6a-4d6b9079d29c', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-04-07 15:36:20', '2022-04-07 15:36:20'),
('3a4614b0-9a6d-4516-8325-3b8ef48bf0d5', 3, '6affb799-6bbf-11ec-a352-309c23e68112', 'ee24fe91-8334-11ec-8b82-b42e9986cd6c', 'menu-save', '{\"id\":\"5b61b5e3-f451-4d71-9036-fc627290246c\",\"type\":\"a79aca14-827e-11ec-aea9-b42e9986cd6c\",\"parent_id\":\"ee2504fc-8334-11ec-8b82-b42e9986cd6c\",\"code\":\"number-format\",\"name\":\"\\u0110\\u1ecbnh d\\u1ea1ng s\\u1ed1 th\\u1ee9 t\\u1ef1\",\"name_en\":\"Number Format\",\"icon\":\"\",\"link\":\"acc\\/number-format\",\"position\":0,\"active\":1,\"created_at\":\"2023-06-23T14:07:25.000000Z\",\"updated_at\":\"2023-06-23T14:07:25.000000Z\"}', '2024-10-28 13:22:02', '2024-10-28 13:22:02'),
('3c53d6ce-e48a-40ee-a3cc-d5c973c394dc', 0, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'logout', '', '2022-03-01 12:46:41', '2022-03-01 12:46:41'),
('3ed996b3-1056-4057-b8f1-cf878a70022e', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-05-02 05:32:48', '2022-05-02 05:32:48'),
('4275ba2a-7564-4150-8fb0-2f9b116baa3d', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-03-07 17:27:43', '2022-03-07 17:27:43'),
('439f7d3e-d600-4860-aa3c-fbfab7f1e6c8', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-06-22 14:29:01', '2022-06-22 14:29:01'),
('49ee699e-943d-4542-863c-c5b6e736a1c9', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-03-02 04:38:01', '2022-03-02 04:38:01'),
('4b7f9f35-e2f2-4140-bba6-d4e3a1847ccd', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-03-13 04:31:45', '2022-03-13 04:31:45'),
('4ef84bcd-90ac-4ef0-aaf3-804c8dde63a5', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-06-12 08:04:07', '2022-06-12 08:04:07'),
('520348d7-380f-428c-a8aa-d30a4f489637', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-05-23 16:55:37', '2022-05-23 16:55:37'),
('556575a1-c1a1-407d-95e7-f9ff22a5bb28', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2024-11-01 15:18:38', '2024-11-01 15:18:38'),
('560247fe-dd14-4010-90fe-334b0fe6bf6b', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-06-20 14:12:13', '2022-06-20 14:12:13'),
('5727b748-c5bc-4500-a4dd-21d8b7012fd9', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-05-07 15:06:29', '2022-05-07 15:06:29'),
('5d89072a-766b-4ad3-a773-c7406fb22f72', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-06-10 12:55:10', '2022-06-10 12:55:10'),
('6202e049-4800-46e5-b813-bc4fb7d6bd88', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-03-01 12:46:56', '2022-03-01 12:46:56'),
('62059acd-a1c7-49dc-89e3-2b857093b376', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-03-22 15:52:55', '2022-03-22 15:52:55'),
('638f14fd-a50b-4fc7-ab79-2fc394f849b1', 0, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'logout', '', '2022-03-01 12:43:44', '2022-03-01 12:43:44'),
('6cb3de5a-e504-483d-8461-1f8fb345fcc5', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-03-01 12:18:25', '2022-03-01 12:18:25'),
('70771c2b-7daa-4b68-aa1b-f78077f502e5', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-03-14 15:32:16', '2022-03-14 15:32:16'),
('71039c6f-4b03-4f09-ac94-fe5ad3cd1ff5', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-05-15 14:48:51', '2022-05-15 14:48:51'),
('7180c100-17b1-41e5-a5be-1189234bd689', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-06-05 15:33:29', '2022-06-05 15:33:29'),
('719e3705-dc3b-4907-836c-75d624994fc8', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2023-11-04 14:34:06', '2023-11-04 14:34:06'),
('723f77ba-e36e-4a97-bb6d-8ee916b6a42a', 2, '6affb799-6bbf-11ec-a352-309c23e68112', 'ee250263-8334-11ec-8b82-b42e9986cd6c', 'group-users-save', '{\"company_id\":\"9aecf6c6-827d-11ec-aea9-b42e9986cd6c\",\"code\":\"test\",\"name\":\"zzz\",\"active\":1,\"id\":\"b91cd445-b8d0-467a-99ac-4f29b2fc7aa9\",\"updated_at\":\"2022-06-12T14:37:33.000000Z\",\"created_at\":\"2022-06-12T14:37:33.000000Z\"}', '2022-06-12 14:37:33', '2022-06-12 14:37:33'),
('752844d1-b840-462b-9d67-a53a09729b52', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-06-12 13:23:50', '2022-06-12 13:23:50'),
('76df9795-3832-4827-bd65-6880b03dee84', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-06-29 15:50:36', '2022-06-29 15:50:36'),
('771a54e0-2ba8-4510-8d46-979649afba1f', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-03-01 12:43:56', '2022-03-01 12:43:56'),
('78f616f5-9191-494f-aacd-f69a800008e7', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-04-03 15:03:39', '2022-04-03 15:03:39'),
('79653824-0abd-4ff8-a29b-f676e54036c2', 0, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'logout', '', '2023-06-23 11:01:30', '2023-06-23 11:01:30'),
('7c9f1323-7599-4ab6-88b9-73f6bfd623b3', 0, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'logout', '', '2023-04-02 16:46:15', '2023-04-02 16:46:15'),
('81b91be6-7fa2-4b14-ad2a-8b081b79f35b', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-03-04 11:29:32', '2022-03-04 11:29:32'),
('83473b72-cf18-44eb-b0a4-831b8e0d2daf', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-05-24 15:03:35', '2022-05-24 15:03:35'),
('83d8ae22-81c0-469c-81f4-d7db65033511', 2, '6affb799-6bbf-11ec-a352-309c23e68112', 'ee250263-8334-11ec-8b82-b42e9986cd6c', 'group-users-save', '{\"company_id\":\"9aecf6c6-827d-11ec-aea9-b42e9986cd6c\",\"code\":\"test\",\"name\":\"z\",\"active\":1,\"id\":\"2baae485-92aa-43ce-a201-2ffedea649fb\",\"updated_at\":\"2022-06-12T15:05:58.000000Z\",\"created_at\":\"2022-06-12T15:05:58.000000Z\"}', '2022-06-12 15:05:58', '2022-06-12 15:05:58'),
('84557590-c895-47eb-af88-519c78f2853e', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2023-04-02 15:46:28', '2023-04-02 15:46:28'),
('855ce33e-28c0-40d3-a415-86d6f129bfca', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2023-06-22 10:44:43', '2023-06-22 10:44:43'),
('894a7653-a680-4ce2-9b40-3d8d83077558', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2023-06-23 14:09:01', '2023-06-23 14:09:01'),
('8d89c657-2598-48bf-ba91-f1211f2a88b9', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-03-05 11:37:23', '2022-03-05 11:37:23'),
('905bb3a4-9d7c-4625-9c82-905c9f486725', 0, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'logout', '', '2023-06-23 11:04:23', '2023-06-23 11:04:23'),
('943d290b-9a20-4147-ad24-3024d95efc80', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-03-05 03:35:07', '2022-03-05 03:35:07'),
('972feb28-1d1d-45c7-9298-8e680d8cd6c9', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-03-01 13:15:38', '2022-03-01 13:15:38'),
('99136a37-c865-4adf-9d4f-9a701e3b9946', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-06-18 16:25:22', '2022-06-18 16:25:22'),
('9b46b487-c3ac-4d26-a1ff-b88e116dced2', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-06-19 15:57:32', '2022-06-19 15:57:32'),
('9bbe79db-420b-4824-9887-05b21894610c', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-05-08 00:44:35', '2022-05-08 00:44:35'),
('9c65f0c4-5d16-4bb6-93f6-fab3a42effd4', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-03-23 14:54:00', '2022-03-23 14:54:00'),
('9cba85fe-8403-4e51-845e-94f94cd9440c', 0, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'logout', '', '2023-06-22 10:44:59', '2023-06-22 10:44:59'),
('9cc63a63-3656-4ec5-a54c-ccde5c6c1b6c', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-06-21 01:41:47', '2022-06-21 01:41:47'),
('9dfa74c2-f1f9-40c1-9736-d1123fcc3c13', 2, '6affb799-6bbf-11ec-a352-309c23e68112', 'ee250263-8334-11ec-8b82-b42e9986cd6c', 'group-users-save', '{\"company_id\":\"9aecf6c6-827d-11ec-aea9-b42e9986cd6c\",\"code\":\"test2\",\"name\":\"az\",\"active\":1,\"id\":\"756a3353-a2fb-43df-b164-70f944d38abf\",\"updated_at\":\"2022-06-12T14:38:26.000000Z\",\"created_at\":\"2022-06-12T14:38:26.000000Z\"}', '2022-06-12 14:38:26', '2022-06-12 14:38:26'),
('a5410d60-56fb-432f-94db-aef1005d3b87', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-06-21 13:55:38', '2022-06-21 13:55:38'),
('a63e8b10-241b-4b74-b62f-d8655003aa58', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-06-12 02:25:16', '2022-06-12 02:25:16'),
('a99b20cf-eef6-4928-8e2e-2e1c03ce2a20', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-03-24 16:07:55', '2022-03-24 16:07:55'),
('ab340921-7482-4e1d-bc8d-f98431c4a83d', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-03-02 14:02:38', '2022-03-02 14:02:38'),
('ab6bec1f-4f72-466c-8e6c-19fc68a18ea3', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2023-11-14 14:29:02', '2023-11-14 14:29:02'),
('acb65ee5-dfb6-4e4a-abe5-e7f55bd37bde', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2023-06-22 10:42:24', '2023-06-22 10:42:24'),
('ae9d6945-a6a8-4719-8eb9-90216a4ece84', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-03-06 03:17:57', '2022-03-06 03:17:57'),
('b0b1d5f9-8937-47c7-b212-df0d6a33a1e9', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2023-06-23 14:05:53', '2023-06-23 14:05:53'),
('b14df26f-58cd-4fcf-851a-a10fd4460ebf', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2023-11-02 07:04:23', '2023-11-02 07:04:23'),
('b5a3476a-c54c-47df-9ff6-1f44db7536ac', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-05-06 14:47:02', '2022-05-06 14:47:02'),
('b600b2f2-4c20-480b-9e3d-2111f3d2ea5a', 0, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'logout', '', '2023-06-23 14:05:39', '2023-06-23 14:05:39'),
('b778abd9-9ac1-4164-b210-0329060e3fca', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-05-05 15:33:13', '2022-05-05 15:33:13'),
('b8eb83bc-605d-4ac0-aa58-57ac8a75316e', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2023-12-24 07:13:53', '2023-12-24 07:13:53'),
('b9d4d4da-1ffd-44c6-b3a5-fa7d081975e5', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-06-05 09:34:34', '2022-06-05 09:34:34'),
('bc06662a-0074-4b13-b679-99760a7a4c5b', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-05-07 17:27:26', '2022-05-07 17:27:26'),
('be2a795b-bd3b-42ec-b184-d1ee79197be6', 0, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'logout', '', '2023-06-23 14:07:39', '2023-06-23 14:07:39'),
('c214743d-52b8-4a12-ad7c-688dbc61b8ea', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-07-02 17:55:28', '2022-07-02 17:55:28'),
('c3e20521-f764-4942-8c4d-b34c05d34bfa', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-03-07 16:10:54', '2022-03-07 16:10:54'),
('c5d7452c-9513-40ea-acf6-e2c7c4ef0eb5', 0, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'logout', '', '2022-03-01 13:15:24', '2022-03-01 13:15:24'),
('c67b3850-c372-4d54-9a42-00379972688d', 2, '6affb799-6bbf-11ec-a352-309c23e68112', 'ee25009e-8334-11ec-8b82-b42e9986cd6c', 'area-save', '{\"regions\":\"0\",\"code\":\"NCC0001\",\"name\":\"C\\u00f4ng ty TNHH ACB\",\"name_en\":\"Keyword Ai\",\"active\":1,\"id\":\"c138b58c-4b13-4175-a6c6-d170662cb9ea\",\"updated_at\":\"2022-03-01T13:15:15.000000Z\",\"created_at\":\"2022-03-01T13:15:15.000000Z\"}', '2022-03-01 13:15:15', '2022-03-01 13:15:15'),
('c78e57bb-0ab2-47c7-a917-8afed167cac5', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2023-11-12 10:31:55', '2023-11-12 10:31:55'),
('c8699460-ca25-4671-a9c3-9deecf152d64', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-06-02 13:22:09', '2022-06-02 13:22:09'),
('cb23bd28-74b1-4135-ac72-4297101a47f1', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-06-23 14:18:58', '2022-06-23 14:18:58'),
('ccb30603-44f1-4d66-a845-b2cb7aab7988', 4, '6affb799-6bbf-11ec-a352-309c23e68112', 'ee250263-8334-11ec-8b82-b42e9986cd6c', 'group-users-delete', '{\"id\":\"756a3353-a2fb-43df-b164-70f944d38abf\",\"company_id\":\"9aecf6c6-827d-11ec-aea9-b42e9986cd6c\",\"code\":\"test2\",\"name\":\"az\",\"active\":1,\"created_at\":\"2022-06-12T14:38:26.000000Z\",\"updated_at\":\"2022-06-12T14:38:26.000000Z\"}', '2022-06-12 15:01:01', '2022-06-12 15:01:01'),
('ce1fd59e-9beb-423f-a2ed-60cc1c40b65b', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2023-12-31 07:35:43', '2023-12-31 07:35:43'),
('cfd1ccdb-80c7-44d1-89d8-6cc7cdacbcfa', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-03-23 11:15:00', '2022-03-23 11:15:00'),
('d011ec0e-5652-4e29-81bd-438138f40779', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2024-10-31 11:27:47', '2024-10-31 11:27:47'),
('d086d1e1-0d9a-4d87-a556-2681e922780d', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2023-11-13 15:59:09', '2023-11-13 15:59:09'),
('d0b70c65-d987-451e-a1a9-cff7d60624c9', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2023-06-23 10:55:32', '2023-06-23 10:55:32'),
('d1e26f9f-a2f3-4eea-92f8-59a2b72f96e6', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2023-04-02 14:55:00', '2023-04-02 14:55:00'),
('da2f8d6a-55a1-462b-b96b-7f76cd9ec0b2', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-06-11 15:01:42', '2022-06-11 15:01:42'),
('dbc95d2b-e8e2-4fc0-97d9-5ab811a99036', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-11-06 12:58:30', '2022-11-06 12:58:30'),
('dcfafa26-c2d4-48f7-8c54-00d98ecca478', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2023-11-12 06:20:08', '2023-11-12 06:20:08'),
('de0eb1bf-f667-449d-bb99-2710dc4072a1', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-03-01 12:33:09', '2022-03-01 12:33:09'),
('df5fab57-43b7-4189-b0ad-9b9a2602de98', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2023-12-25 12:05:35', '2023-12-25 12:05:35'),
('e4752c5d-17ed-4a5a-a0df-d3dd4784524b', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-04-03 10:56:43', '2022-04-03 10:56:43'),
('e604501c-97d6-45cc-bef5-c28f462666a9', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-06-27 15:32:10', '2022-06-27 15:32:10'),
('e8b9d2b9-bfcc-4f59-9593-f7dca388c9b7', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2024-11-04 13:26:03', '2024-11-04 13:26:03'),
('e9019b40-cc06-4adc-a2e8-1c13da6299f2', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2024-10-30 14:05:15', '2024-10-30 14:05:15'),
('eaf609a1-6fb4-4ada-ba5c-f4d252a8a975', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-06-07 15:24:57', '2022-06-07 15:24:57'),
('f2f6dbe4-72d2-4077-942a-e6865e05cfd1', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-05-08 14:20:54', '2022-05-08 14:20:54'),
('fa622c47-f6c1-48b0-a192-5c15cc1d2b4f', 1, '6affb799-6bbf-11ec-a352-309c23e68112', '0', 'login', '', '2022-04-01 12:23:22', '2022-04-01 12:23:22');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `key_ai`
--

CREATE TABLE `key_ai` (
  `id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `name_en` varchar(100) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `content` varchar(200) DEFAULT NULL,
  `field` varchar(50) DEFAULT NULL,
  `crit` varchar(80) DEFAULT NULL,
  `crit_en` varchar(80) DEFAULT NULL,
  `active` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `key_ai`
--

INSERT INTO `key_ai` (`id`, `code`, `name`, `name_en`, `count`, `content`, `field`, `crit`, `crit_en`, `active`, `created_at`, `updated_at`) VALUES
('618bcd8e-713b-11ec-821b-309c23e68112', '00001', 'Nhà cung cấp mã', 'Supplier code', 2, 'subject', 'code', '', '', 1, '2021-07-29 11:43:53', '2021-09-03 22:07:13'),
('618e3817-713b-11ec-821b-309c23e68112', '00010', 'Khách hàng tên', 'Customer name', 2, 'subject', 'name', '', '', 1, '2021-08-12 09:27:25', '2021-09-03 21:38:05'),
('618e39b2-713b-11ec-821b-309c23e68112', '00011', 'Nhân viên tên', 'Employer name', 2, 'subject', 'name', '', '', 1, '2021-08-12 09:27:40', '2021-09-03 21:37:55'),
('618e3a26-713b-11ec-821b-309c23e68112', '00012', 'Khác tên', 'Other name', 2, 'subject', 'name', '', '', 1, '2021-08-12 09:28:09', '2021-09-03 21:37:47'),
('618e3a8c-713b-11ec-821b-309c23e68112', '00013', 'Nhà cung cấp hộp thư', 'Supplier email', 2, 'subject', 'email', '', '', 1, '2021-08-12 10:18:42', '2021-09-03 21:34:55'),
('618e3b84-713b-11ec-821b-309c23e68112', '00014', 'Khách hàng hộp thư', 'Customer email', 2, 'subject', 'email', '', '', 1, '2021-08-12 10:19:03', '2021-09-03 21:34:40'),
('618e3bf7-713b-11ec-821b-309c23e68112', '00015', 'Nhân viên hộp thư', 'Employer email', 2, 'subject', 'email', '', '', 1, '2021-08-12 10:21:35', '2021-09-03 21:34:28'),
('618e3c41-713b-11ec-821b-309c23e68112', '00016', 'Khác hộp thư', 'Other email', 2, 'subject', 'email', '', '', 1, '2021-08-12 10:21:54', '2021-09-03 21:34:18'),
('618e3c8a-713b-11ec-821b-309c23e68112', '00017', 'Nhà cung cấp địa chỉ', 'Supplier address', 2, 'subject', 'address', '', '', 1, '2021-08-12 10:22:11', '2021-09-03 21:33:45'),
('618e3ccc-713b-11ec-821b-309c23e68112', '00018', 'Khách hàng địa chỉ', 'Customer address', 2, 'subject', 'address', '', '', 1, '2021-08-12 10:22:31', '2021-09-03 21:33:37'),
('618e3d11-713b-11ec-821b-309c23e68112', '00019', 'Nhân viên địa chỉ', 'Employer address', 2, 'subject', 'address', '', '', 1, '2021-08-12 10:22:53', '2021-09-03 21:33:27'),
('618e3d57-713b-11ec-821b-309c23e68112', '00002', 'Khách hàng mã', 'Customer code', 2, 'subject', 'code', '', '', 1, '2021-07-29 21:28:26', '2021-09-03 22:07:04'),
('618e3d99-713b-11ec-821b-309c23e68112', '00020', 'Khác địa chỉ', 'Other address', 2, 'subject', 'address', '', '', 1, '2021-08-12 10:23:38', '2021-09-03 21:32:44'),
('618e3ddd-713b-11ec-821b-309c23e68112', '00021', 'Nhà cung cấp số điện thoại', 'Supplier phone', 2, 'subject', 'phone', '', '', 1, '2021-08-12 11:26:42', '2021-09-03 21:32:36'),
('618e3e21-713b-11ec-821b-309c23e68112', '00022', 'Khách hàng điện thoại', 'Customer phone', 2, 'subject', 'phone', '', '', 1, '2021-08-12 21:24:30', '2021-09-03 21:32:27'),
('618e3e6c-713b-11ec-821b-309c23e68112', '00023', 'Nhân viên điện thoại', 'Employer phone', 2, 'subject', 'phone', '', '', 1, '2021-08-12 21:24:56', '2021-09-03 21:32:14'),
('618e3ec8-713b-11ec-821b-309c23e68112', '00024', 'Khác điện thoại', 'Other phone', 2, 'subject', 'phone', '', '', 1, '2021-08-12 21:25:17', '2021-09-03 21:32:05'),
('618e3f0a-713b-11ec-821b-309c23e68112', '00025', 'Người nộp', 'Payer', 1, 'traders', '', '', '', 1, '2021-08-12 21:55:41', '2021-08-12 22:11:59'),
('618e3f4f-713b-11ec-821b-309c23e68112', '00026', 'Diễn giải', 'Description', 1, 'description', '', '', '', 1, '2021-08-12 21:56:23', '2021-08-12 21:56:23'),
('618e3fa6-713b-11ec-821b-309c23e68112', '00027', 'Tự động mã', 'Auto code', 2, 'accounted_auto', 'code', '', '', 1, '2021-08-13 02:31:09', '2021-09-03 21:31:47'),
('618e4045-713b-11ec-821b-309c23e68112', '00028', 'Ngày', 'Date', 1, 'date', '', '', '', 1, '2021-08-15 07:59:01', '2021-08-15 07:59:01'),
('618e40e7-713b-11ec-821b-309c23e68112', '00029', 'Hạch toán nhanh mã', 'Fast code', 2, 'accounted_fast', 'code', '', '', 1, '2021-08-19 10:16:00', '2021-09-04 02:05:55'),
('618e414f-713b-11ec-821b-309c23e68112', '00003', 'Nhân viên mã', 'Employer code', 2, 'subject', 'code', '', '', 1, '2021-07-29 21:59:09', '2021-09-03 22:06:56'),
('618e4196-713b-11ec-821b-309c23e68112', '00030', 'Nợ Mã', 'Debit Code', 1, 'debit', 'code', 'dòng', 'row', 1, '2021-08-19 10:16:23', '2021-09-03 21:31:28'),
('618e41dd-713b-11ec-821b-309c23e68112', '00031', 'Có Mã', 'Credit Code', 1, 'credit', 'code', 'dòng', 'row', 1, '2021-08-19 10:16:39', '2021-09-03 21:03:26'),
('618e421d-713b-11ec-821b-309c23e68112', '00032', 'Tiền', 'Amount', 1, 'amount', '', 'dòng', 'row', 1, '2021-08-19 10:45:50', '2021-08-19 10:45:50'),
('618e4267-713b-11ec-821b-309c23e68112', '00033', 'Hạch toán nhanh tên', 'Fast name', 2, 'accounted_fast', 'name', '', '', 1, '2021-08-20 21:40:06', '2021-09-03 21:03:03'),
('618e42ab-713b-11ec-821b-309c23e68112', '00034', 'Xóa dòng', 'Remove row', 1, 'remove_row', '', '', '', 1, '2021-08-25 08:54:03', '2021-08-25 08:54:03'),
('618e42f2-713b-11ec-821b-309c23e68112', '00035', 'Thêm dòng', 'Add row', 1, 'add_row', '', '', '', 1, '2021-08-25 08:57:36', '2021-08-25 08:57:36'),
('618e4335-713b-11ec-821b-309c23e68112', '00036', 'Copy dòng', 'Copy row', 1, 'copy_row', '', '', '', 1, '2021-08-25 08:58:02', '2021-08-25 08:58:02'),
('618e4378-713b-11ec-821b-309c23e68112', '00037', 'Có Tên', 'Credit Name', 1, 'credit', 'name', 'dòng', 'row', 1, '2021-08-31 21:22:23', '2021-09-03 21:01:54'),
('618e43bb-713b-11ec-821b-309c23e68112', '00038', 'Nợ Tên', 'Debit name', 1, 'debit', 'name', 'dòng', 'row', 1, '2021-08-31 21:22:41', '2021-09-03 21:01:32'),
('618e43fd-713b-11ec-821b-309c23e68112', '00004', 'Khác mã', 'Other code', 2, 'subject', 'code', '', '', 1, '2021-07-29 22:00:18', '2021-09-03 21:39:06'),
('618e4455-713b-11ec-821b-309c23e68112', '00005', 'Nhà cung cấp mã số thuế', 'Supplier tax code', 2, 'subject', 'tax_code', '', '', 1, '2021-08-11 21:07:38', '2021-09-03 21:38:52'),
('618e449c-713b-11ec-821b-309c23e68112', '00006', 'Khách hàng mã số thuế', 'Customer tax code', 2, 'subject', 'tax_code', '', '', 1, '2021-08-11 21:07:59', '2021-09-03 21:38:43'),
('618e44df-713b-11ec-821b-309c23e68112', '00007', 'Nhân viên mã số thuế', 'Employer tax code', 2, 'subject', 'tax_code', '', '', 1, '2021-08-11 21:08:20', '2021-09-03 21:38:34'),
('618e4525-713b-11ec-821b-309c23e68112', '00008', 'Khác mã số thuế', 'Other tax code', 2, 'subject', 'tax_code', '', '', 1, '2021-08-11 21:08:38', '2021-09-03 21:38:25'),
('618e456e-713b-11ec-821b-309c23e68112', '00009', 'Nhà cung cấp tên', 'Supplier name', 2, 'subject', 'name', '', '', 1, '2021-08-12 09:27:03', '2021-09-03 21:38:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `license`
--

CREATE TABLE `license` (
  `id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `keygen` varchar(50) DEFAULT NULL,
  `company_use` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci DEFAULT NULL,
  `software_use` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci DEFAULT NULL,
  `active` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `license`
--

INSERT INTO `license` (`id`, `date_start`, `date_end`, `keygen`, `company_use`, `software_use`, `active`, `created_at`, `updated_at`) VALUES
('3414dabe-57a0-4051-855b-417bd2b1d7b3', '2022-02-20', '2022-02-27', 'GgtQN90YcV', '9aecf6c6-827d-11ec-aea9-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 1, '2022-02-27 14:30:45', '2022-02-27 14:30:45'),
('61c7242e-fd9a-4a05-b33a-5d609265e744', '2022-01-05', '2022-01-27', 'RBqnfQ3bxW', '9aecf6c6-827d-11ec-aea9-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 1, '2022-01-31 11:30:09', '2022-01-31 11:30:09'),
('c09ce83e-827e-11ec-aea9-b42e9986cd6c', '2020-01-01', '2020-01-02', 'mWvBPXSJy1', '9aecf6c6-827d-11ec-aea9-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 1, '2020-02-29 23:36:48', '2020-02-29 23:40:35'),
('c09ce977-827e-11ec-aea9-b42e9986cd6c', '1970-01-01', '2021-12-31', '8h8uAn7zGV', '9aecf6c6-827d-11ec-aea9-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 1, '2022-01-31 09:07:40', '2022-01-31 09:07:40'),
('c09ce9d0-827e-11ec-aea9-b42e9986cd6c', '2020-01-01', '2020-01-12', '2aKMSC8mPH', '9aecf6c6-827d-11ec-aea9-b42e9986cd6c', 'a79acae4-827e-11ec-aea9-b42e9986cd6c', 1, '2020-03-10 01:37:33', '2020-03-10 01:57:09'),
('c09cea28-827e-11ec-aea9-b42e9986cd6c', '2020-07-15', '2020-08-15', 'FSGsvXjdH0', '9aecf6c6-827d-11ec-aea9-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 1, '2022-01-31 08:36:47', '2022-01-31 08:36:47'),
('c09ceabd-827e-11ec-aea9-b42e9986cd6c', '2020-07-15', '2020-08-15', 'tQU60hkcmZ', '9aecf6c6-827d-11ec-aea9-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 1, '2022-01-31 08:38:39', '2022-01-31 08:38:39');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `menu`
--

CREATE TABLE `menu` (
  `id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `type` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `parent_id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `name_en` varchar(100) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `link` varchar(50) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `active` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `menu`
--

INSERT INTO `menu` (`id`, `type`, `parent_id`, `code`, `name`, `name_en`, `icon`, `link`, `position`, `active`, `created_at`, `updated_at`) VALUES
('5b61b5e3-f451-4d71-9036-fc627290246c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee2504fc-8334-11ec-8b82-b42e9986cd6c', 'count-voucher', 'Số đếm chứng từ', 'Voucher Count', '', 'acc/count-voucher', 0, 1, '2023-06-23 14:07:25', '2024-10-28 13:22:02'),
('ee24ee11-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', '0', 'index', 'Trang chủ', 'Index', 'dashboard', 'manage/index', -1, 1, '2020-06-12 11:34:02', '2020-06-12 11:34:06'),
('ee24fd46-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', 'ee250417-8334-11ec-8b82-b42e9986cd6c', 'history-action', 'Lịch sử người dùng', 'History action', '', 'manage/history-action', 0, 1, '2020-06-12 11:34:02', '2020-06-12 11:34:06'),
('ee24fe2b-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', 'ee2500e7-8334-11ec-8b82-b42e9986cd6c', 'general_manage', 'Quản lý chung', 'General Manage', 'subject', '', 0, 1, '2020-06-12 11:34:02', '2020-06-12 11:34:06'),
('ee24fe91-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', 'ee24fe2b-8334-11ec-8b82-b42e9986cd6c', 'menu', 'Thư mục', 'Menu', '', 'manage/menu', 0, 1, '2020-06-12 11:34:02', '2020-06-12 11:34:06'),
('ee24fee5-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', 'ee24fe2b-8334-11ec-8b82-b42e9986cd6c', 'software', 'Phần mềm', 'Software', '', 'manage/software', 0, 1, '2020-02-27 08:45:48', '2020-02-27 08:45:48'),
('ee24ff39-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', 'ee24fe2b-8334-11ec-8b82-b42e9986cd6c', 'license', 'Bản quyền phần mềm', 'License', '', 'manage/license', 0, 1, '2020-02-29 11:50:22', '2020-02-29 11:50:22'),
('ee24ff84-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', 'ee24fe2b-8334-11ec-8b82-b42e9986cd6c', 'systems', 'Thiết lập hệ thống', 'Systems', '', 'manage/systems', 0, 1, '2020-03-02 11:37:32', '2020-03-02 11:37:32'),
('ee24ffca-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', 'ee2500e7-8334-11ec-8b82-b42e9986cd6c', 'province', 'Khu vực', 'Province', 'add_location', '', 0, 1, '2020-03-06 21:32:12', '2020-03-06 21:36:01'),
('ee250010-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', 'ee24ffca-8334-11ec-8b82-b42e9986cd6c', 'country', 'Quốc gia', 'Country', '', 'manage/country', 0, 1, '2020-03-06 21:33:51', '2020-03-06 21:33:51'),
('ee250055-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', 'ee24ffca-8334-11ec-8b82-b42e9986cd6c', 'regions', 'Tỉnh', 'Regions', '', 'manage/regions', 0, 1, '2020-03-11 11:05:40', '2020-03-20 08:49:11'),
('ee25009e-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', 'ee24ffca-8334-11ec-8b82-b42e9986cd6c', 'area', 'Thành phố', 'Area', '', 'manage/area', 0, 1, '2020-03-20 08:48:54', '2020-03-20 08:48:54'),
('ee2500e7-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', '0', 'systems', 'Quản lý hệ thống', 'Systems', '', '', 1, 1, '2020-06-12 11:34:02', '2020-06-12 11:34:06'),
('ee250147-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', 'ee24ffca-8334-11ec-8b82-b42e9986cd6c', 'distric', 'Quận huyện', 'Distric', '', 'manage/distric', 0, 1, '2020-03-20 10:06:01', '2020-03-20 10:06:01'),
('ee25018e-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', 'ee24fe2b-8334-11ec-8b82-b42e9986cd6c', 'company', 'Công ty', 'Company', '', 'manage/company', 0, 1, '2020-03-21 09:17:06', '2020-03-21 09:17:06'),
('ee2501d7-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', 'ee24fe2b-8334-11ec-8b82-b42e9986cd6c', 'company-software', 'Phần mềm công ty', 'Company Software', '', 'manage/company-software', 0, 1, '2020-03-24 08:28:05', '2020-03-24 11:14:32'),
('ee25021f-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', 'ee24fe2b-8334-11ec-8b82-b42e9986cd6c', 'query', 'Truy vấn', 'Query', '', 'manage/query', 0, 1, '2020-04-30 08:30:46', '2020-04-30 08:30:46'),
('ee250263-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', 'ee250417-8334-11ec-8b82-b42e9986cd6c', 'group-users', 'Nhóm người dùng', 'Group Users', '', 'manage/group-users', 0, 1, '2020-05-10 11:06:32', '2020-05-10 11:06:32'),
('ee2502ae-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', 'ee250417-8334-11ec-8b82-b42e9986cd6c', 'users', 'Người dùng', 'Users', '', 'manage/users', 0, 1, '2020-05-17 03:45:33', '2020-05-17 03:45:33'),
('ee2502f4-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', 'ee24fe2b-8334-11ec-8b82-b42e9986cd6c', 'error', 'Lỗi', 'Error', '', 'manage/error', 0, 1, '2020-05-29 00:20:18', '2020-05-29 00:20:18'),
('ee250340-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', '0', 'index', 'Trang chủ', 'Index', 'dashboard', 'acc/index', -1, 1, '2020-06-12 11:34:02', '2020-06-12 11:34:06'),
('ee250387-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', '0', 'systems', 'Quản lý hệ thống', 'Systems', '', '', 1, 1, '2020-06-12 11:34:02', '2020-06-12 11:34:06'),
('ee2503ce-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250387-8334-11ec-8b82-b42e9986cd6c', 'user_control', 'Quản lý người dùng', 'User control', 'group', '', 0, 1, '2020-06-12 11:34:02', '2020-06-12 11:34:06'),
('ee250417-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', 'ee2500e7-8334-11ec-8b82-b42e9986cd6c', 'user_control', 'Quản lý người dùng', 'User control', 'group', '', 0, 1, '2020-06-12 11:34:02', '2020-06-12 11:34:06'),
('ee25045c-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', '0', 'profile', 'Thông tin cá nhân', 'Profile', '', 'acc/profile', 0, 0, '2020-06-12 11:34:02', '2020-06-12 11:34:06'),
('ee2504b3-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee2503ce-8334-11ec-8b82-b42e9986cd6c', 'permission', 'Phân quyền', 'Permission', '', 'acc/permission', 0, 1, '2020-06-12 11:34:02', '2020-06-12 11:34:06'),
('ee2504fc-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250387-8334-11ec-8b82-b42e9986cd6c', 'general_manage', 'Quản lý chung', 'General Manage', 'subject', '', 0, 1, '2020-06-19 17:23:14', '2020-06-19 17:23:19'),
('ee250996-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee2504fc-8334-11ec-8b82-b42e9986cd6c', 'systems', 'Thiết lập hệ thống', 'Systems', '', 'acc/systems', 0, 1, '2020-06-24 11:06:17', '2020-06-24 11:06:17'),
('ee250a01-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee2504fc-8334-11ec-8b82-b42e9986cd6c', 'number-voucher', 'Số thứ tự chứng từ', 'Number Voucher', '', 'acc/number-voucher', 0, 1, '2020-06-28 09:18:18', '2020-06-28 09:18:18'),
('ee250a54-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee2503ce-8334-11ec-8b82-b42e9986cd6c', 'group-users', 'Nhóm người dùng', 'Group Users', '', 'acc/group-users', 0, 1, '2020-05-10 11:06:32', '2020-05-10 11:06:32'),
('ee250aa7-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee2503ce-8334-11ec-8b82-b42e9986cd6c', 'users', 'Người dùng', 'Users', '', 'acc/users', 0, 1, '2020-07-17 04:19:53', '2020-07-17 04:19:53'),
('ee250afc-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', '0', 'category', 'Danh mục', 'Category', '', '', 0, 1, '2020-07-19 03:32:07', '2020-07-19 03:37:14'),
('ee250b45-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250bd4-8334-11ec-8b82-b42e9986cd6c', 'unit', 'Đơn vị tính', 'Unit', '', 'acc/unit', 0, 1, '2020-07-19 03:32:41', '2020-07-19 03:32:41'),
('ee250b8b-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250bd4-8334-11ec-8b82-b42e9986cd6c', 'stock', 'Kho', 'Stock', '', 'acc/stock', 0, 1, '2020-07-19 03:32:41', '2020-07-19 03:32:41'),
('ee250bd4-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250afc-8334-11ec-8b82-b42e9986cd6c', 'supplies_goods', 'Vật tư hàng hóa', 'Supplies and Goods', 'extension', NULL, 0, 1, '2020-07-22 10:29:36', '2020-07-22 10:29:38'),
('ee250c20-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250afc-8334-11ec-8b82-b42e9986cd6c', 'account', 'Tài khoản', 'Account', 'account_balance_wallet', NULL, 0, 1, '2020-07-23 10:39:57', '2020-07-23 10:40:00'),
('ee250c7c-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250c20-8334-11ec-8b82-b42e9986cd6c', 'account-systems', 'Hệ thống tài khoản', 'Account Systems', NULL, 'acc/account-systems', 0, 1, '2020-07-23 10:44:40', '2020-07-23 10:44:44'),
('ee250cca-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250c20-8334-11ec-8b82-b42e9986cd6c', 'account-type', 'Loại tài khoản', 'Account Type', '', 'acc/account-type', 0, 1, '2020-07-25 18:44:14', '2020-07-25 18:44:14'),
('ee250d12-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250c20-8334-11ec-8b82-b42e9986cd6c', 'account-nature', 'Tính chất tài khoản', 'Account Nature', '', 'acc/account-nature', 0, 1, '2020-07-25 18:45:02', '2020-07-25 18:45:02'),
('ee250d5b-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', 'ee24fe2b-8334-11ec-8b82-b42e9986cd6c', 'document-type', 'Loại tài liệu', 'Document Type', '', 'manage/document-type', 0, 1, '2020-07-26 09:51:34', '2020-07-26 09:51:34'),
('ee250da6-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', 'ee24fe2b-8334-11ec-8b82-b42e9986cd6c', 'document', 'Tài liệu', 'Document', '', 'manage/document', 0, 1, '2020-07-26 09:51:34', '2020-07-26 09:51:34'),
('ee250ded-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250afc-8334-11ec-8b82-b42e9986cd6c', 'bank_category', 'Ngân hàng', 'Bank', 'account_balance', '', 0, 1, '2020-07-28 08:13:40', '2020-07-28 08:16:25'),
('ee250e35-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250ded-8334-11ec-8b82-b42e9986cd6c', 'bank', 'Ngân hàng', 'Bank', '', 'acc/bank', 0, 1, '2020-07-28 08:16:55', '2020-07-28 08:16:55'),
('ee250e7c-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250ded-8334-11ec-8b82-b42e9986cd6c', 'bank-account', 'Tài khoản ngân hàng', 'Bank Account', '', 'acc/bank-account', 0, 1, '2020-07-28 08:17:23', '2020-07-28 08:17:23'),
('ee250ec5-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', '0', 'profile', 'Thông tin cá nhân', 'Profile', '', 'manage/profile', 0, 0, '2020-06-12 11:34:02', '2020-06-12 11:34:06'),
('ee250f16-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250afc-8334-11ec-8b82-b42e9986cd6c', 'statistical_category', 'Thống kê', 'Statistical', 'assessment', '', 0, 1, '2020-07-30 01:40:07', '2020-07-30 01:48:17'),
('ee250f60-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250f16-8334-11ec-8b82-b42e9986cd6c', 'statistical-code', 'Mã thống kê', 'Statistical code', '', 'acc/statistical-code', 0, 1, '2020-07-30 01:41:09', '2020-07-30 01:41:09'),
('ee250fbc-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250f16-8334-11ec-8b82-b42e9986cd6c', 'work-code', 'Mã công việc', 'Work code', '', 'acc/work-code', 0, 1, '2020-07-30 01:41:39', '2020-07-30 01:41:39'),
('ee251006-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250f16-8334-11ec-8b82-b42e9986cd6c', 'case-code', 'Mã vụ việc', 'Case code', '', 'acc/case-code', 0, 1, '2020-07-30 01:42:21', '2020-07-30 01:42:21'),
('ee25104e-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250f16-8334-11ec-8b82-b42e9986cd6c', 'cost-code', 'Mã chi phí', 'Cost code', '', 'acc/cost-code', 0, 1, '2020-07-30 01:42:59', '2020-07-30 01:42:59'),
('ee251096-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250ded-8334-11ec-8b82-b42e9986cd6c', 'currency', 'Loại tiền', 'Currency', '', 'acc/currency', 0, 1, '2020-07-31 10:24:10', '2020-07-31 10:24:10'),
('ee2510dd-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250f16-8334-11ec-8b82-b42e9986cd6c', 'revenue-expenditure', 'Mục thu chi', 'Revenue and expenditure', '', 'acc/revenue-expenditure', 0, 1, '2020-08-12 02:28:39', '2020-08-12 02:28:39'),
('ee251129-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250f16-8334-11ec-8b82-b42e9986cd6c', 'revenue-expenditure-type', 'Loại mục thu chi', 'Revenue and expenditure type', '', 'acc/revenue-expenditure-type', 0, 1, '2020-08-12 02:29:38', '2020-08-12 02:30:28'),
('ee251173-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee2504fc-8334-11ec-8b82-b42e9986cd6c', 'setting-voucher', 'Thiết lập chứng từ', 'Setting Voucher', '', 'acc/setting-voucher', 0, 1, '2020-08-13 08:12:41', '2020-08-13 08:12:41'),
('ee2511c2-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250afc-8334-11ec-8b82-b42e9986cd6c', 'tax_category', 'Thuế', 'Tax', 'monetization_on', '', 0, 1, '2020-08-14 22:40:48', '2020-08-14 22:40:48'),
('ee251209-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee2511c2-8334-11ec-8b82-b42e9986cd6c', 'vat', 'Thuế Vat', 'Vat Tax', '', 'acc/vat', 0, 1, '2020-08-15 04:58:29', '2020-08-15 04:58:29'),
('ee251252-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee2511c2-8334-11ec-8b82-b42e9986cd6c', 'excise', 'Thuế tiêu thụ đặc biệt', 'Excise Tax', '', 'acc/excise', 0, 1, '2020-08-15 09:48:22', '2020-08-15 09:48:22'),
('ee25129a-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee2511c2-8334-11ec-8b82-b42e9986cd6c', 'natural-resources', 'Thuế tài nguyên', 'Natural Resources Tax', '', 'acc/natural-resources', 0, 1, '2020-08-16 01:07:26', '2020-08-16 01:07:26'),
('ee2512e1-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250afc-8334-11ec-8b82-b42e9986cd6c', 'object_category', 'Đối tượng', 'Object', 'filter_center_focus', '', 0, 1, '2020-08-20 20:25:11', '2020-08-20 20:25:11'),
('ee251338-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee2512e1-8334-11ec-8b82-b42e9986cd6c', 'object-type', 'Loại đối tượng', 'Object Type', '', 'acc/object-type', 0, 1, '2020-08-20 20:25:50', '2020-08-20 20:25:50'),
('ee251383-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee2512e1-8334-11ec-8b82-b42e9986cd6c', 'department', 'Bộ phận', 'Department', '', 'acc/department', 0, 1, '2020-08-20 21:37:57', '2020-08-20 21:37:57'),
('ee2513cb-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee2512e1-8334-11ec-8b82-b42e9986cd6c', 'object', 'Đối tượng', 'Object', '', 'acc/object', 0, 1, '2020-08-26 03:08:37', '2020-08-26 03:08:37'),
('ee251413-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee2512e1-8334-11ec-8b82-b42e9986cd6c', 'object-group', 'Nhóm đối tượng', 'Object Group', '', 'acc/object-group', 0, 1, '2020-08-20 20:25:50', '2020-08-20 20:25:50'),
('ee251459-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250bd4-8334-11ec-8b82-b42e9986cd6c', 'supplies-goods-type', 'Loại vật tư hàng hóa', 'Supplies Goods Type', '', 'acc/supplies-goods-type', 0, 1, '2020-09-05 08:47:29', '2020-09-05 08:48:08'),
('ee2514a1-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250bd4-8334-11ec-8b82-b42e9986cd6c', 'supplies-goods-group', 'Nhóm vật tư hàng hóa', 'Supplies Goods Group', '', 'acc/supplies-goods-group', 0, 1, '2020-09-05 08:47:58', '2020-09-05 08:47:58'),
('ee2514e9-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', 'ee250417-8334-11ec-8b82-b42e9986cd6c', 'permission', 'Phân quyền', 'Permission', '', 'manage/permission', 0, 1, '2020-06-12 11:34:02', '2020-06-12 11:34:06'),
('ee251534-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250bd4-8334-11ec-8b82-b42e9986cd6c', 'supplies-goods', 'Vật tư hàng hóa', 'Supplies and Goods', '', 'acc/supplies-goods', 0, 1, '2020-09-05 08:48:48', '2020-09-05 08:48:48'),
('ee25157d-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250bd4-8334-11ec-8b82-b42e9986cd6c', 'warranty-period', 'Bảo hành', 'Warranty period', '', 'acc/warranty-period', 0, 1, '2020-09-10 21:01:30', '2020-09-10 21:01:30'),
('ee25f9de-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250c20-8334-11ec-8b82-b42e9986cd6c', 'setting-account-group', 'Thiết lập nhóm tài khoản', 'Setting Account Group', '', 'acc/setting-account-group', 0, 1, '2020-09-18 08:33:32', '2020-09-18 08:33:32'),
('ee25fb0e-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee2504fc-8334-11ec-8b82-b42e9986cd6c', 'number-code', 'Số thứ tự mã', 'Number Code', '', 'acc/number-code', 0, 1, '2020-09-26 08:48:30', '2020-09-26 08:48:30'),
('ee25fb78-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250c20-8334-11ec-8b82-b42e9986cd6c', 'accounted-fast', 'Hạch toán nhanh', 'Accounted Fast', '', 'acc/accounted-fast', 0, 1, '2020-11-09 04:19:58', '2020-11-09 04:45:40'),
('ee25fbce-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', '0', 'major', 'Nghiệp vụ', 'Major', '', '', 1, 1, '2020-11-22 08:35:46', '2020-11-22 08:36:16'),
('ee25fc25-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee25fbce-8334-11ec-8b82-b42e9986cd6c', 'revenue_expenditure', 'Thu chi', 'Revenue expenditure', 'monetization_on', '', 0, 1, '2020-11-22 08:48:29', '2020-11-22 08:50:30'),
('ee25fc75-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee25fc25-8334-11ec-8b82-b42e9986cd6c', 'cash-receipts-general', 'Phiếu thu', 'Cash receipts', '', 'acc/cash-receipts-general', 0, 1, '2020-11-22 08:56:22', '2020-11-22 08:56:22'),
('ee25fcc5-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee25fc25-8334-11ec-8b82-b42e9986cd6c', 'cash-payment-general', 'Phiếu chi', 'Cash payment', '', 'acc/cash-payment-general', 0, 1, '2020-11-22 08:57:06', '2020-11-22 09:22:07'),
('ee25fd11-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee25fc25-8334-11ec-8b82-b42e9986cd6c', 'cash-payment-voucher', 'Phiếu chi', 'Cash payment', '', 'acc/cash-payment-detail', 0, 0, '2020-11-22 09:21:57', '2021-04-22 08:55:06'),
('ee25fd5e-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', 'ee250417-8334-11ec-8b82-b42e9986cd6c', 'setting', 'Thiết lập hệ thống', 'Setting', '', 'manage/setting', 0, 1, '2020-06-12 11:34:02', '2020-06-12 11:34:06'),
('ee25fdac-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee2504fc-8334-11ec-8b82-b42e9986cd6c', 'period', 'Kỳ kế toán', 'Accounting period', '', 'acc/period', 0, 1, '2021-01-02 06:17:59', '2021-01-02 06:17:59'),
('ee25fdfa-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee2504fc-8334-11ec-8b82-b42e9986cd6c', 'print-template', 'Mẫu in', 'Print Template', '', 'acc/print-template', 0, 1, '2021-03-04 09:50:49', '2021-03-04 09:50:49'),
('ee25fe47-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee25fc25-8334-11ec-8b82-b42e9986cd6c', 'cash-receipts-voucher', 'Phiếu thu', 'Cash receipts', '', 'acc/cash-receipts-voucher', 0, 0, '2021-04-22 08:55:26', '2021-04-22 08:55:26'),
('ee25fe91-8334-11ec-8b82-b42e9986cd6c', 'a79aca14-827e-11ec-aea9-b42e9986cd6c', 'ee250c20-8334-11ec-8b82-b42e9986cd6c', 'accounted-auto', 'Hạch toán tự động', 'Accounted Auto', '', 'acc/accounted-auto', 0, 1, '2021-06-18 19:22:58', '2021-06-18 19:22:58'),
('ee25feef-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', 'ee24fe2b-8334-11ec-8b82-b42e9986cd6c', 'key-ai', 'Từ khóa Ai', 'Keyword Ai', '', 'manage/key-ai', 0, 1, '2021-07-29 09:34:02', '2021-07-29 09:34:02'),
('ee25ff42-8334-11ec-8b82-b42e9986cd6c', 'a799b192-827e-11ec-aea9-b42e9986cd6c', 'ee250417-8334-11ec-8b82-b42e9986cd6c', 'notes', 'Ghi chú', 'Notes', '', 'manage/notes', 0, 1, '2020-06-12 11:34:02', '2020-06-12 11:34:06');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_05_31_044726_create_permission_table', 1),
(2, '2019_06_02_003900_create_company_table', 1),
(3, '2019_06_02_010601_create_menu_table', 1),
(4, '2019_06_03_115450_create_license_table', 2),
(5, '2019_06_18_042142_create_software_table', 3),
(6, '2019_06_18_043017_create_company_software_table', 3),
(7, '2019_06_20_080647_create_systems_table', 4),
(8, '2019_06_22_091701_create_messages_table', 5),
(9, '2019_06_30_135636_create_timeline_table', 6),
(10, '2019_07_07_125519_create_chat_table', 7),
(11, '2019_07_21_132558_create_history_action_table', 8);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notes`
--

CREATE TABLE `notes` (
  `id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `title` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `user_id` varchar(36) NOT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `permission`
--

CREATE TABLE `permission` (
  `id` varchar(36) NOT NULL,
  `user_id` varchar(36) NOT NULL,
  `menu_id` varchar(36) NOT NULL,
  `permission` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `permission`
--

INSERT INTO `permission` (`id`, `user_id`, `menu_id`, `permission`, `created_at`, `updated_at`) VALUES
('07dbe811-540b-4330-83f8-2c863f444e82', '6affb799-6bbf-11ec-a352-309c23e68112', 'ee24fd46-8334-11ec-8b82-b42e9986cd6c', 15, '2022-02-26 05:05:00', '2022-02-26 05:05:00'),
('25052e42-33d6-44a4-bd33-18e8e50331e0', '6affb799-6bbf-11ec-a352-309c23e68112', 'ee24ff39-8334-11ec-8b82-b42e9986cd6c', 15, '2022-02-26 05:04:59', '2022-02-26 05:15:43'),
('45df5494-d23d-4513-b72f-dc5bd95ff937', '6affb799-6bbf-11ec-a352-309c23e68112', 'ee25feef-8334-11ec-8b82-b42e9986cd6c', 15, '2022-02-26 05:04:59', '2022-02-26 05:04:59'),
('6bc0dc58-65be-4817-81da-eb5270e98d41', '6affb799-6bbf-11ec-a352-309c23e68112', 'ee25fd5e-8334-11ec-8b82-b42e9986cd6c', 15, '2022-02-26 05:05:00', '2022-02-26 05:05:00'),
('7b16f0e1-9e6b-48be-a792-8ae7de0817bb', '6affb799-6bbf-11ec-a352-309c23e68112', 'ee25009e-8334-11ec-8b82-b42e9986cd6c', 15, '2022-02-26 05:05:00', '2022-02-26 05:05:00'),
('83ba85a3-564b-4acd-b8f6-093a35f47368', '6affb799-6bbf-11ec-a352-309c23e68112', 'ee2502ae-8334-11ec-8b82-b42e9986cd6c', 15, '2022-02-26 05:05:00', '2022-02-26 05:05:00'),
('95053791-44e4-4718-9b8e-7c7d563cc602', '6affb799-6bbf-11ec-a352-309c23e68112', 'ee250263-8334-11ec-8b82-b42e9986cd6c', 15, '2022-02-26 05:05:00', '2022-02-26 05:05:00'),
('9db1adfe-6b45-4125-951f-b30de27dbcca', '6affb799-6bbf-11ec-a352-309c23e68112', 'ee24ff84-8334-11ec-8b82-b42e9986cd6c', 15, '2022-02-26 05:04:59', '2022-02-26 05:04:59'),
('aa45b843-0bde-4ce2-add3-f1fb9bc64df2', '6affb799-6bbf-11ec-a352-309c23e68112', 'ee2502f4-8334-11ec-8b82-b42e9986cd6c', 15, '2022-02-26 05:04:59', '2022-02-26 05:04:59'),
('aa526a02-0833-47e9-9f0d-8ee0076aa608', '6affb799-6bbf-11ec-a352-309c23e68112', 'ee24fe91-8334-11ec-8b82-b42e9986cd6c', 15, '2022-02-26 05:04:59', '2022-02-26 05:15:43'),
('ad2e9f08-f4ac-4b70-bada-defc1b74f9a1', '6affb799-6bbf-11ec-a352-309c23e68112', 'ee25018e-8334-11ec-8b82-b42e9986cd6c', 15, '2022-02-26 05:04:59', '2022-02-26 05:04:59'),
('d7c662ce-1baa-4d08-8c21-11479c913361', '6affb799-6bbf-11ec-a352-309c23e68112', 'ee250147-8334-11ec-8b82-b42e9986cd6c', 15, '2022-02-26 05:05:00', '2022-02-26 05:05:00'),
('d91ce7a4-a1da-4526-aaa2-51b5f082a088', '6affb799-6bbf-11ec-a352-309c23e68112', 'ee25ff42-8334-11ec-8b82-b42e9986cd6c', 15, '2022-02-26 05:05:00', '2022-02-26 05:05:00'),
('dc4d9c57-e664-4204-9597-095ddac6ae39', '6affb799-6bbf-11ec-a352-309c23e68112', 'ee250d5b-8334-11ec-8b82-b42e9986cd6c', 15, '2022-02-26 05:04:59', '2022-02-26 05:04:59'),
('dcd1c149-287b-4192-ba61-083163bdfa27', '6affb799-6bbf-11ec-a352-309c23e68112', 'ee250da6-8334-11ec-8b82-b42e9986cd6c', 15, '2022-02-26 05:04:59', '2022-02-26 05:04:59'),
('e22a9060-d192-4490-9bfa-2bc84114b3cf', '6affb799-6bbf-11ec-a352-309c23e68112', 'ee24ee11-8334-11ec-8b82-b42e9986cd6c', 15, '2022-02-26 05:04:59', '2022-02-26 05:15:43'),
('e3729b6c-6068-480d-96cb-c902a679b477', '6affb799-6bbf-11ec-a352-309c23e68112', 'ee250055-8334-11ec-8b82-b42e9986cd6c', 15, '2022-02-26 05:05:00', '2022-02-26 05:05:00'),
('e6d19d52-b117-45a5-b64d-e2451fe2475e', '6affb799-6bbf-11ec-a352-309c23e68112', 'ee2514e9-8334-11ec-8b82-b42e9986cd6c', 15, '2022-02-26 05:05:00', '2022-02-26 05:05:00'),
('e8e6713a-7d70-45dd-ae3f-f60c492a831b', '6affb799-6bbf-11ec-a352-309c23e68112', 'ee250010-8334-11ec-8b82-b42e9986cd6c', 15, '2022-02-26 05:05:00', '2022-02-26 05:05:00'),
('ed3e55d5-3599-458f-a616-a49dcaddc6ab', '6affb799-6bbf-11ec-a352-309c23e68112', 'ee24fee5-8334-11ec-8b82-b42e9986cd6c', 15, '2022-02-26 05:04:59', '2022-02-26 05:15:43'),
('f15b6aaa-24a4-43ac-ab9a-725d87bf737b', '6affb799-6bbf-11ec-a352-309c23e68112', 'ee25021f-8334-11ec-8b82-b42e9986cd6c', 15, '2022-02-26 05:04:59', '2022-02-26 05:04:59'),
('f658b6e2-4c55-4556-84e9-4e8534eb84be', '6affb799-6bbf-11ec-a352-309c23e68112', 'ee2501d7-8334-11ec-8b82-b42e9986cd6c', 15, '2022-02-26 05:04:59', '2022-02-26 05:04:59');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `regions`
--

CREATE TABLE `regions` (
  `id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL DEFAULT '0',
  `country` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `name_en` varchar(100) DEFAULT NULL,
  `active` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `regions`
--

INSERT INTO `regions` (`id`, `country`, `code`, `name`, `name_en`, `active`, `created_at`, `updated_at`) VALUES
('28afd945-ac35-4aca-b6e2-7c5dc7a3ece7', '42420d3e-6be1-11ec-a352-309c23e68112', 'TP0002', 'a', 'a', 1, '2022-01-09 17:28:11', '2022-02-27 10:22:35'),
('8a6c1939-f935-4b82-895f-82e6fff6597e', '4242403b-6be1-11ec-a352-309c23e68112', 'TP0001', 'Thành phố Nha Trang', 'Nha Trang', 1, '2022-01-07 18:37:57', '2022-01-07 18:37:57'),
('c3857005-32fe-4cfa-9f2e-f65c9be29264', '4242403b-6be1-11ec-a352-309c23e68112', 'TP0003', 'TP Hà Nội', 'Hà Nội', 1, '2022-02-27 10:22:20', '2022-02-27 10:22:20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `software`
--

CREATE TABLE `software` (
  `id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `name_en` varchar(100) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `url` varchar(60) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `database_temp` varchar(100) DEFAULT NULL,
  `username_temp` varchar(100) DEFAULT NULL,
  `password_temp` varchar(50) DEFAULT NULL,
  `active` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `software`
--

INSERT INTO `software` (`id`, `name`, `name_en`, `image`, `url`, `note`, `database_temp`, `username_temp`, `password_temp`, `active`, `created_at`, `updated_at`) VALUES
('a799b192-827e-11ec-aea9-b42e9986cd6c', 'Phần quản lý', 'Manage', '', 'manage', '', NULL, NULL, NULL, 0, '2019-06-18 09:32:26', '2019-06-18 09:32:29'),
('a79aca14-827e-11ec-aea9-b42e9986cd6c', 'Phần mềm kế toán', 'ERMIS ACC', 'addon/img/ermis-account-logo.png', 'acc', 'Chưa cập nhật', 'acc_ermis', NULL, NULL, 1, '2019-06-18 04:40:06', '2019-06-18 04:40:09'),
('a79acae4-827e-11ec-aea9-b42e9986cd6c', 'Phần mềm bán hàng', 'ERMIS POS', 'addon/img/ermis-pos.png', 'pos', 'Chưa cập nhật', NULL, NULL, NULL, 1, '2019-06-18 04:40:53', '2019-06-18 04:40:56'),
('a79acb41-827e-11ec-aea9-b42e9986cd6c', 'Phần mềm nhân sự', 'ERMIS HRM', 'addon/img/ermis-hrm.png', 'hrm', 'Chưa cập nhật', NULL, NULL, NULL, 1, '2019-06-18 04:43:15', '2019-06-18 04:43:26'),
('a79acb87-827e-11ec-aea9-b42e9986cd6c', 'Phần mềm khách sạn', 'ERMIS HOTEL', 'addon/img/ermis-hotel.png', 'hotel', 'Chưa cập nhật', NULL, NULL, NULL, 1, '2019-06-18 04:44:09', '2019-06-18 04:44:16'),
('a79acbcf-827e-11ec-aea9-b42e9986cd6c', 'Phần mềm giáo dục', 'ERMIS EDU', 'addon/img/ermis-education.png', 'edu', 'Chưa cập nhật', NULL, NULL, NULL, 1, '2019-06-18 04:45:37', '2019-06-18 04:45:40');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `systems`
--

CREATE TABLE `systems` (
  `id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `value` varchar(100) NOT NULL,
  `value1` varchar(100) DEFAULT NULL,
  `value2` varchar(100) DEFAULT NULL,
  `value3` varchar(100) DEFAULT NULL,
  `value4` varchar(100) DEFAULT NULL,
  `value5` varchar(100) DEFAULT NULL,
  `active` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `systems`
--

INSERT INTO `systems` (`id`, `code`, `name`, `value`, `value1`, `value2`, `value3`, `value4`, `value5`, `active`, `created_at`, `updated_at`) VALUES
('f0bad85a-8253-11ec-aea9-b42e9986cd6c', 'DATE_USE_FREE', 'Ngày xài free phần mềm', '30', NULL, NULL, NULL, NULL, NULL, 1, '2019-06-20 13:36:14', '2022-01-30 14:49:22'),
('f0be0d6f-8253-11ec-aea9-b42e9986cd6c', 'MAX_TIMELINE', 'Số hiển thị timeline', '7', NULL, NULL, NULL, NULL, NULL, 1, '2019-06-30 15:54:25', '2019-06-30 15:54:32'),
('f0be0ea5-8253-11ec-aea9-b42e9986cd6c', 'MAX_LOAD_CHAT', 'Số hiển thị chat', '15', NULL, NULL, NULL, NULL, NULL, 1, '2019-07-07 13:04:13', '2019-07-07 13:04:21'),
('f0be0f28-8253-11ec-aea9-b42e9986cd6c', 'PATH_UPLOAD_AVATAR', 'Đường dẫn upload avatar', 'uploads/avatar/', NULL, NULL, NULL, NULL, NULL, 1, '2019-07-26 11:19:12', '2019-07-26 11:19:14'),
('f0be1001-8253-11ec-aea9-b42e9986cd6c', 'MAX_NOTES', 'Số hiển thị notes', '20', NULL, NULL, NULL, NULL, NULL, 1, '2019-07-26 11:19:12', '2019-07-26 11:19:12'),
('f0be1073-8253-11ec-aea9-b42e9986cd6c', 'MAX_RANDOM', 'Max random key', '10', NULL, NULL, NULL, NULL, NULL, 1, '2020-03-01 02:01:36', '2020-03-01 02:01:41'),
('f0be10ef-8253-11ec-aea9-b42e9986cd6c', 'PATH_UPLOAD_SOFTWARE', 'Đường dẫn upload software', 'uploads/sw/', '', '', '', '', '', 1, '2020-05-27 04:22:34', '2020-05-27 04:22:34'),
('f0be11ce-8253-11ec-aea9-b42e9986cd6c', 'PAGESIZE', 'Số hiển thị bảng', '50', '', '', '', '', '', 1, '2022-01-20 13:52:53', '2022-02-26 05:00:27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `timeline`
--

CREATE TABLE `timeline` (
  `id` varchar(36) NOT NULL,
  `user_id` varchar(36) NOT NULL,
  `type` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `timeline`
--

INSERT INTO `timeline` (`id`, `user_id`, `type`, `message`, `created_at`, `updated_at`) VALUES
('254ff32a-19b7-43dc-8e35-c75a273d3ac6', '6affb799-6bbf-11ec-a352-309c23e68112', '2', 'test', '2022-03-01 12:39:24', '2022-03-01 12:39:24'),
('66ec9a1a-794f-42c0-8b01-e36ee4be8f6e', '6affb799-6bbf-11ec-a352-309c23e68112', '', 'test', '2022-03-01 12:08:32', '2022-03-01 12:08:32'),
('7504f796-4642-4a7e-9796-a1f0c42fc237', '6affb799-6bbf-11ec-a352-309c23e68112', '4', 'abc', '2022-02-26 12:27:58', '2022-02-26 12:27:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(60) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `firstname` varchar(20) DEFAULT NULL,
  `lastname` varchar(20) DEFAULT NULL,
  `identity_card` varchar(20) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `jobs` varchar(30) DEFAULT NULL,
  `country` varchar(36) NOT NULL,
  `avatar` varchar(100) DEFAULT NULL,
  `about` varchar(100) DEFAULT NULL,
  `active` tinyint(4) NOT NULL,
  `active_code` varchar(30) DEFAULT NULL,
  `role` tinyint(4) NOT NULL,
  `group_users_id` varchar(36) DEFAULT NULL,
  `barcode` varchar(30) DEFAULT NULL,
  `stock_default` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci DEFAULT NULL,
  `company_default` varchar(36) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `api_token` varchar(60) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Role : // 2 :: User\r\n          // 1 :: Admin\r\n          // 0 :: Manage';

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `fullname`, `firstname`, `lastname`, `identity_card`, `birthday`, `phone`, `email`, `email_verified_at`, `address`, `city`, `jobs`, `country`, `avatar`, `about`, `active`, `active_code`, `role`, `group_users_id`, `barcode`, `stock_default`, `company_default`, `remember_token`, `api_token`, `created_at`, `updated_at`) VALUES
('6affb799-6bbf-11ec-a352-309c23e68112', 'admins', '$2y$10$.g0CdD8wjhMI2VHRUKd2.OcNoMXfo825UUAn65EIXeNFCbK/Ii1wa', 'Phan Kim Sơn', 'Phan', 'Sơn', '225386789', '2019-01-17', '23232323232', 'chicken_no_die@yahoo.com', NULL, '123', '123', 'abc', '42424002-6be1-11ec-a352-309c23e68112', 'addon/img/avatar.png', '', 1, 'GITXmRQdFXDv6AXnTDn4RKBDgqVd4j', 0, '0', '', '0', '9aecf6c6-827d-11ec-aea9-b42e9986cd6c', '', '', '2019-06-20 06:33:41', '2022-02-27 02:34:32'),
('6affb81e-6bbf-11ec-a352-309c23e68112', 'admins12', '$2y$10$.g0CdD8wjhMI2VHRUKd2.OcNoMXfo825UUAn65EIXeNFCbK/Ii1wa', 'Phan Kim Sơn', 'Phan', 'Sơn', '22323232323', '2019-06-20', '23232323232', 'chicken_no_die@yahoo.com', NULL, '123', '123', 'abc', '4242403b-6be1-11ec-a352-309c23e68112', 'addon/img/avatar.png', '', 1, 'fFtT5xM0vsBlYCK7v4TvRZuh8IfsAr', 1, '0', '', '0', '9aecf6c6-827d-11ec-aea9-b42e9986cd6c', 'I7mmXcLB89h5vOuCtY06QouGtdRJWqXZ4S4t2JkieHaYFan1l91lwsHysEaA', 'I7mmXcLB89h5vOuCtY06QouGtdRJWqXZ4S4t2JkieHaYFan1l91lwsHysEaA', '2019-06-21 04:39:46', '2022-02-26 16:17:20'),
('6affb89d-6bbf-11ec-a352-309c23e68112', 'eyJpd_test1', '$2y$10$.g0CdD8wjhMI2VHRUKd2.OcNoMXfo825UUAn65EIXeNFCbK/Ii1wa', 'Phan Kim Sơn', 'dsa', 'dsa', '22323232323', '2019-01-18', '23232323232', 'phankimson1988@gmail.com', NULL, '123', '123', 'abc', '4242403b-6be1-11ec-a352-309c23e68112', 'uploads/avatar/6/1564147342.png', '', 1, '', 2, '', '', '0', '9aecf6c6-827d-11ec-aea9-b42e9986cd6c', NULL, NULL, '2020-07-18 09:05:06', '2022-02-26 16:17:32'),
('6affba99-6bbf-11ec-a352-309c23e68112', 'eyJpd_test23', '$2y$10$.g0CdD8wjhMI2VHRUKd2.OcNoMXfo825UUAn65EIXeNFCbK/Ii1wa', 'abc', 'zz', 'yy', '223323', '2019-01-18', '168669211', 'phankimson@gmail.com', NULL, '123456 abc', '1', '2', '4242403b-6be1-11ec-a352-309c23e68112', 'addon/img/avatar.png', '', 1, '', 2, '0', '', '0', '9aecf6c6-827d-11ec-aea9-b42e9986cd6c', NULL, NULL, '2020-07-19 00:15:24', '2022-02-26 16:17:44');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`id`),
  ADD KEY `regions` (`regions`);

--
-- Chỉ mục cho bảng `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_send` (`user_send`),
  ADD KEY `user_receipt` (`user_receipt`);

--
-- Chỉ mục cho bảng `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`),
  ADD KEY `country` (`country`),
  ADD KEY `regions` (`regions`),
  ADD KEY `area` (`area`),
  ADD KEY `distric` (`distric`);

--
-- Chỉ mục cho bảng `company_software`
--
ALTER TABLE `company_software`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `software_id` (`software_id`),
  ADD KEY `license_id` (`license_id`);

--
-- Chỉ mục cho bảng `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Chỉ mục cho bảng `distric`
--
ALTER TABLE `distric`
  ADD PRIMARY KEY (`id`),
  ADD KEY `area` (`area`);

--
-- Chỉ mục cho bảng `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`);

--
-- Chỉ mục cho bảng `document_type`
--
ALTER TABLE `document_type`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `error`
--
ALTER TABLE `error`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_id` (`menu_id`),
  ADD KEY `error_ibfk_2` (`user_id`);

--
-- Chỉ mục cho bảng `group_users`
--
ALTER TABLE `group_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`);

--
-- Chỉ mục cho bảng `group_users_permission`
--
ALTER TABLE `group_users_permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_user_id` (`group_user_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Chỉ mục cho bảng `history_action`
--
ALTER TABLE `history_action`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`),
  ADD KEY `menu` (`menu`);

--
-- Chỉ mục cho bảng `key_ai`
--
ALTER TABLE `key_ai`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `license`
--
ALTER TABLE `license`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `country` (`country`);

--
-- Chỉ mục cho bảng `software`
--
ALTER TABLE `software`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `systems`
--
ALTER TABLE `systems`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `timeline`
--
ALTER TABLE `timeline`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_id_unique` (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
