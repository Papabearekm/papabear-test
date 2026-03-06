-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 29, 2024 at 08:01 AM
-- Server version: 8.0.35-0ubuntu0.22.04.1
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `papabear`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `house` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `landmark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `pincode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lng` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` int NOT NULL,
  `freelancer_id` int NOT NULL DEFAULT '0',
  `salon_id` int NOT NULL DEFAULT '0',
  `specialist_id` int NOT NULL DEFAULT '0',
  `appointments_to` int NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `items` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `coupon_id` int DEFAULT NULL,
  `coupon` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `discount` double(10,2) NOT NULL,
  `distance_cost` double(10,2) NOT NULL,
  `total` double(10,2) NOT NULL,
  `serviceTax` double(10,2) NOT NULL,
  `grand_total` double(10,2) NOT NULL,
  `pay_method` int NOT NULL,
  `paid` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `save_date` date NOT NULL,
  `slot` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `wallet_used` tinyint NOT NULL DEFAULT '0',
  `wallet_price` double(10,2) DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `uid`, `freelancer_id`, `salon_id`, `specialist_id`, `appointments_to`, `address`, `items`, `coupon_id`, `coupon`, `discount`, `distance_cost`, `total`, `serviceTax`, `grand_total`, `pay_method`, `paid`, `save_date`, `slot`, `wallet_used`, `wallet_price`, `notes`, `extra_field`, `status`, `created_at`, `updated_at`) VALUES
(1, 8, 0, 1, 0, 0, NULL, '[1,2]', NULL, NULL, 0.00, 0.00, 220.00, 0.00, 220.00, 1, 'paid', '2023-11-07', '1', 0, NULL, NULL, NULL, 1, NULL, NULL),
(2, 8, 3, 0, 0, 0, NULL, '[1]', NULL, NULL, 0.00, 0.00, 100.00, 0.00, 100.00, 1, 'paid', '2023-11-08', '2', 0, NULL, NULL, NULL, 1, NULL, NULL),
(3, 8, 3, 0, 0, 0, NULL, '[1,2]', NULL, NULL, 0.00, 0.00, 220.00, 0.00, 220.00, 1, 'paid', '2023-12-12', '1', 0, NULL, NULL, NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int DEFAULT NULL,
  `city_id` int DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'home',
  `price` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0.00',
  `cover` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` tinyint DEFAULT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from` date DEFAULT NULL,
  `to` date DEFAULT NULL,
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `user_id`, `city_id`, `title`, `position`, `price`, `cover`, `type`, `value`, `link`, `from`, `to`, `extra_field`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 'Test Title', 'search', '500', 'banners/P13SVu18tC8kM6bKGpr3fpDnoFctdx9JblwWwwaY.jpg', 2, '9', 'papabear.com', '2023-10-10', '2023-10-20', NULL, 1, '2023-10-03 12:58:33', '2024-01-08 00:43:59'),
(2, 1, 1, '', 'home', '0.00', 'banners/SxSYaKcX4FYEekFA6pOcyin3zRYdKHQWofwst06p.jpg', 1, '3', 'papabear.com', '2023-10-01', '2023-10-25', NULL, 1, '2023-10-03 13:03:34', '2023-10-03 14:32:01');

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` bigint UNSIGNED NOT NULL,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `cover`, `short_content`, `content`, `status`, `extra_field`, `created_at`, `updated_at`) VALUES
(1, '4 Tips To Give Your Hair a Healthy Shine', 'blogs/qe8QUbIt0ftVFSVKXpe7ZaQMkzA3fxXx3uInfQ18.jpg', 'At The Hair Company by The Skin & Body Spa, we understand the importance of having healthy and lustrous locks. That’s why our talented stylists are dedicated to providing our clients with the best haircuts and styles that suit their face shapes and personalities. In addition to our cutting-edge services, we also offer a wide range of products to help maintain optimal hair health. We’ve got you covered, from shampoo and conditioner to hair masks and treatments!', '<p>At&nbsp;<a href=\"https://www.thehaircompany.com/\" data-cke-saved-href=\"https://www.thehaircompany.com/\">The Hair Company</a>&nbsp;by The Skin &amp; Body Spa, we understand the importance of having healthy and lustrous locks. That&rsquo;s why our talented stylists are dedicated to providing our clients with the best haircuts and styles that suit their face shapes and personalities. In addition to our&nbsp;<a href=\"https://www.thehaircompany.com/services/\" data-cke-saved-href=\"https://www.thehaircompany.com/services/\">cutting-edge services</a>, we also offer a&nbsp;<a href=\"https://www.thehaircompany.com/products/\" data-cke-saved-href=\"https://www.thehaircompany.com/products/\">wide range of products</a>&nbsp;to help maintain optimal hair health. We&rsquo;ve got you covered, from shampoo and conditioner to hair masks and treatments!&nbsp;</p>\n<p>&nbsp;</p>\n<h3><strong>Conditioner is Key</strong></h3>\n<p>One of the best ways to keep your hair healthy and shiny is to use a conditioner every time you wash your hair. Conditioner helps to replenish the natural oils in your hair, which can be stripped away by standard shampoo.&nbsp;<a href=\"https://www.thehaircompany.com/\" data-cke-saved-href=\"https://www.thehaircompany.com/\">The Hair Company</a>&nbsp;uses the&nbsp;<a href=\"https://www.thehaircompany.com/products/\" data-cke-saved-href=\"https://www.thehaircompany.com/products/\">highest quality beauty products</a>&nbsp;to nourish, strengthen and maintain your hair&rsquo;s optimal style and color.</p>\n<h3><strong>Get Regular Trims</strong></h3>\n<p>Getting regular trims is one of the best ways to keep your hair looking its best. Trimming off split ends prevents them from traveling up the hair shaft and causing further damage. At&nbsp;<a href=\"https://www.thehaircompany.com/\" data-cke-saved-href=\"https://www.thehaircompany.com/\">The Hair Company</a>&nbsp;salon, our stylists will work with you to find the best trimming schedule for your individual needs to make your hair shine.</p>\n<h3><strong>Invest in a Good Brush</strong></h3>\n<p>Another way to keep your hair healthy is to invest in a good brush. A good quality brush will help to distribute the natural oils from your scalp down the length of your hair. This will help to keep your hair moisturized and prevent split ends.&nbsp;<a href=\"https://www.thehaircompany.com/\" data-cke-saved-href=\"https://www.thehaircompany.com/\">The Hair Company</a>&nbsp;offers a wide range of brushes and combs to suit all hair types.</p>\n<h3><strong>Use the Right Products</strong></h3>\n<p>Using the right&nbsp;<a href=\"https://www.thehaircompany.com/products/\" data-cke-saved-href=\"https://www.thehaircompany.com/products/\">hair products</a>&nbsp;can make all the difference in keeping your hair healthy and shiny.&nbsp;<a href=\"https://www.thehaircompany.com/\" data-cke-saved-href=\"https://www.thehaircompany.com/\">The Hair Company</a>&nbsp;offers a wide range of high-quality hair care products to suit all hair types. We&rsquo;ve covered you, from shampoo and conditioner to hair masks and treatments!</p>\n<p>&nbsp;</p>\n<p>At&nbsp;<a href=\"https://www.thehaircompany.com/\" data-cke-saved-href=\"https://www.thehaircompany.com/\">The Hair Company</a>&nbsp;by The Skin &amp; Body Spa, we understand the importance of having healthy and lustrous locks. That&rsquo;s why our talented stylists are dedicated to providing our clients with the best haircuts and styles that suit their face shapes and personalities. In addition to our&nbsp;<a href=\"https://www.thehaircompany.com/services/\" data-cke-saved-href=\"https://www.thehaircompany.com/services/\">cutting-edge services</a>, we also offer a&nbsp;<a href=\"https://www.thehaircompany.com/products/\" data-cke-saved-href=\"https://www.thehaircompany.com/products/\">wide range of products</a>&nbsp;to help maintain optimal hair health. We&rsquo;ve got you covered, from shampoo and conditioner to hair masks and treatments!&nbsp;</p>\n<p>&nbsp;</p>\n<h3><strong>Conditioner is Key</strong></h3>\n<p>One of the best ways to keep your hair healthy and shiny is to use a conditioner every time you wash your hair. Conditioner helps to replenish the natural oils in your hair, which can be stripped away by standard shampoo.&nbsp;<a href=\"https://www.thehaircompany.com/\" data-cke-saved-href=\"https://www.thehaircompany.com/\">The Hair Company</a>&nbsp;uses the&nbsp;<a href=\"https://www.thehaircompany.com/products/\" data-cke-saved-href=\"https://www.thehaircompany.com/products/\">highest quality beauty products</a>&nbsp;to nourish, strengthen and maintain your hair&rsquo;s optimal style and color.</p>\n<h3><strong>Get Regular Trims</strong></h3>\n<p>Getting regular trims is one of the best ways to keep your hair looking its best. Trimming off split ends prevents them from traveling up the hair shaft and causing further damage. At&nbsp;<a href=\"https://www.thehaircompany.com/\" data-cke-saved-href=\"https://www.thehaircompany.com/\">The Hair Company</a>&nbsp;salon, our stylists will work with you to find the best trimming schedule for your individual needs to make your hair shine.</p>\n<h3><strong>Invest in a Good Brush</strong></h3>\n<p>Another way to keep your hair healthy is to invest in a good brush. A good quality brush will help to distribute the natural oils from your scalp down the length of your hair. This will help to keep your hair moisturized and prevent split ends.&nbsp;<a href=\"https://www.thehaircompany.com/\" data-cke-saved-href=\"https://www.thehaircompany.com/\">The Hair Company</a>&nbsp;offers a wide range of brushes and combs to suit all hair types.</p>\n<h3><strong>Use the Right Products</strong></h3>\n<p>Using the right&nbsp;<a href=\"https://www.thehaircompany.com/products/\" data-cke-saved-href=\"https://www.thehaircompany.com/products/\">hair products</a>&nbsp;can make all the difference in keeping your hair healthy and shiny.&nbsp;<a href=\"https://www.thehaircompany.com/\" data-cke-saved-href=\"https://www.thehaircompany.com/\">The Hair Company</a>&nbsp;offers a wide range of high-quality hair care products to suit all hair types. We&rsquo;ve covered you, from shampoo and conditioner to hair masks and treatments!</p>\n<p>&nbsp;</p>\n<p>At&nbsp;<a href=\"https://www.thehaircompany.com/\" data-cke-saved-href=\"https://www.thehaircompany.com/\">The Hair Company</a>&nbsp;by The Skin &amp; Body Spa, we understand the importance of having healthy and lustrous locks. That&rsquo;s why our talented stylists are dedicated to providing our clients with the best haircuts and styles that suit their face shapes and personalities. In addition to our&nbsp;<a href=\"https://www.thehaircompany.com/services/\" data-cke-saved-href=\"https://www.thehaircompany.com/services/\">cutting-edge services</a>, we also offer a&nbsp;<a href=\"https://www.thehaircompany.com/products/\" data-cke-saved-href=\"https://www.thehaircompany.com/products/\">wide range of products</a>&nbsp;to help maintain optimal hair health. We&rsquo;ve got you covered, from shampoo and conditioner to hair masks and treatments!&nbsp;</p>\n<p>&nbsp;</p>\n<h3><strong>Conditioner is Key</strong></h3>\n<p>One of the best ways to keep your hair healthy and shiny is to use a conditioner every time you wash your hair. Conditioner helps to replenish the natural oils in your hair, which can be stripped away by standard shampoo.&nbsp;<a href=\"https://www.thehaircompany.com/\" data-cke-saved-href=\"https://www.thehaircompany.com/\">The Hair Company</a>&nbsp;uses the&nbsp;<a href=\"https://www.thehaircompany.com/products/\" data-cke-saved-href=\"https://www.thehaircompany.com/products/\">highest quality beauty products</a>&nbsp;to nourish, strengthen and maintain your hair&rsquo;s optimal style and color.</p>\n<h3><strong>Get Regular Trims</strong></h3>\n<p>Getting regular trims is one of the best ways to keep your hair looking its best. Trimming off split ends prevents them from traveling up the hair shaft and causing further damage. At&nbsp;<a href=\"https://www.thehaircompany.com/\" data-cke-saved-href=\"https://www.thehaircompany.com/\">The Hair Company</a>&nbsp;salon, our stylists will work with you to find the best trimming schedule for your individual needs to make your hair shine.</p>\n<h3><strong>Invest in a Good Brush</strong></h3>\n<p>Another way to keep your hair healthy is to invest in a good brush. A good quality brush will help to distribute the natural oils from your scalp down the length of your hair. This will help to keep your hair moisturized and prevent split ends.&nbsp;<a href=\"https://www.thehaircompany.com/\" data-cke-saved-href=\"https://www.thehaircompany.com/\">The Hair Company</a>&nbsp;offers a wide range of brushes and combs to suit all hair types.</p>\n<h3><strong>Use the Right Products</strong></h3>\n<p>Using the right&nbsp;<a href=\"https://www.thehaircompany.com/products/\" data-cke-saved-href=\"https://www.thehaircompany.com/products/\">hair products</a>&nbsp;can make all the difference in keeping your hair healthy and shiny.&nbsp;<a href=\"https://www.thehaircompany.com/\" data-cke-saved-href=\"https://www.thehaircompany.com/\">The Hair Company</a>&nbsp;offers a wide range of high-quality hair care products to suit all hair types. We&rsquo;ve covered you, from shampoo and conditioner to hair masks and treatments!</p>\n<p>&nbsp;</p>\n<p>At&nbsp;<a href=\"https://www.thehaircompany.com/\" data-cke-saved-href=\"https://www.thehaircompany.com/\">The Hair Company</a>&nbsp;by The Skin &amp; Body Spa, we understand the importance of having healthy and lustrous locks. That&rsquo;s why our talented stylists are dedicated to providing our clients with the best haircuts and styles that suit their face shapes and personalities. In addition to our&nbsp;<a href=\"https://www.thehaircompany.com/services/\" data-cke-saved-href=\"https://www.thehaircompany.com/services/\">cutting-edge services</a>, we also offer a&nbsp;<a href=\"https://www.thehaircompany.com/products/\" data-cke-saved-href=\"https://www.thehaircompany.com/products/\">wide range of products</a>&nbsp;to help maintain optimal hair health. We&rsquo;ve got you covered, from shampoo and conditioner to hair masks and treatments!&nbsp;</p>\n<p>&nbsp;</p>\n<h3><strong>Conditioner is Key</strong></h3>\n<p>One of the best ways to keep your hair healthy and shiny is to use a conditioner every time you wash your hair. Conditioner helps to replenish the natural oils in your hair, which can be stripped away by standard shampoo.&nbsp;<a href=\"https://www.thehaircompany.com/\" data-cke-saved-href=\"https://www.thehaircompany.com/\">The Hair Company</a>&nbsp;uses the&nbsp;<a href=\"https://www.thehaircompany.com/products/\" data-cke-saved-href=\"https://www.thehaircompany.com/products/\">highest quality beauty products</a>&nbsp;to nourish, strengthen and maintain your hair&rsquo;s optimal style and color.</p>\n<h3><strong>Get Regular Trims</strong></h3>\n<p>Getting regular trims is one of the best ways to keep your hair looking its best. Trimming off split ends prevents them from traveling up the hair shaft and causing further damage. At&nbsp;<a href=\"https://www.thehaircompany.com/\" data-cke-saved-href=\"https://www.thehaircompany.com/\">The Hair Company</a>&nbsp;salon, our stylists will work with you to find the best trimming schedule for your individual needs to make your hair shine.</p>\n<h3><strong>Invest in a Good Brush</strong></h3>\n<p>Another way to keep your hair healthy is to invest in a good brush. A good quality brush will help to distribute the natural oils from your scalp down the length of your hair. This will help to keep your hair moisturized and prevent split ends.&nbsp;<a href=\"https://www.thehaircompany.com/\" data-cke-saved-href=\"https://www.thehaircompany.com/\">The Hair Company</a>&nbsp;offers a wide range of brushes and combs to suit all hair types.</p>\n<h3><strong>Use the Right Products</strong></h3>\n<p>Using the right&nbsp;<a href=\"https://www.thehaircompany.com/products/\" data-cke-saved-href=\"https://www.thehaircompany.com/products/\">hair products</a>&nbsp;can make all the difference in keeping your hair healthy and shiny.&nbsp;<a href=\"https://www.thehaircompany.com/\" data-cke-saved-href=\"https://www.thehaircompany.com/\">The Hair Company</a>&nbsp;offers a wide range of high-quality hair care products to suit all hair types. We&rsquo;ve covered you, from shampoo and conditioner to hair masks and treatments!</p>\n<p>&nbsp;</p>', 1, NULL, '2023-10-04 03:03:44', '2023-10-04 03:05:12'),
(2, 'What Makes The Hair Company Different', 'blogs/SLNX1V8wdP4HpZhnzrmKtFbPXmUbudscXVn1L2yv.jpg', 'Getting your hair styled is usually the highlight of your week. Whether you love the process like we do or you love the way you look afterward (also like we do), you want to find a hair stylist that takes your needs into account and meets them to help you look your best. At The Hair Company in New Hampshire, our incredible stylists utilize some of the best products on the market to give you a look you and others will love. Here are four things that set us apart from other hair and beauty salons in Nashua.', '<p>Getting your hair styled is usually the highlight of your week. Whether you love the process like we do or you love the way you look afterward (also like we do), you want to find a hair stylist that takes your needs into account and meets them to help you look your best. At&nbsp;<a href=\"https://www.thehaircompany.com/\" data-cke-saved-href=\"https://www.thehaircompany.com/\">The Hair Company in New Hampshire</a>, our incredible stylists utilize some of the best products on the market to give you a look you and others will love. Here are four things that set us apart from other hair and beauty salons in Nashua.</p>\n<h3><strong>Talented Stylists</strong></h3>\n<h3>Our hair stylists are the heart and soul of The Hair Company. Each one is not only talented in working with hair but also trained and educated in the art of hair styling. If you are looking for a hair and beauty salon in Nashua,&nbsp;<a href=\"https://www.thehaircompany.com/contact/\" data-cke-saved-href=\"https://www.thehaircompany.com/contact/\">check out The Hair Company today!</a>.</h3>\n<h3><strong>Individualized for You</strong></h3>\n<p>Every person that walks through our doors has a different look and a different taste in hairstyle. Whether you are getting married and looking for wedding hair and makeup or someone just looking for a look refresh before the weekend, we have the expertise and skills to give you a unique look that brings out who you are as an individual.</p>\n<h3><strong>We Only Use the Best Products</strong></h3>\n<p>When it comes to your look, your hair might be the most important aspect of who you are &mdash; which is why we only use the best hair products. Whether you are getting your hair colored or styled, you can only expect the best from The Hair Company.</p>\n<h3><strong>Meet All of Your Hair Needs</strong></h3>\n<p>Your hair stylist should be well-rounded in every aspect of caring for your hair. Whether you are simply getting it cut and styled or getting ready for your wedding, at The Hair Company, our educated hair stylists are trained and talented in every aspect of hair care and prep.</p>\n<p>&nbsp;</p>\n<p>Getting your hair styled is usually the highlight of your week. Whether you love the process like we do or you love the way you look afterward (also like we do), you want to find a hair stylist that takes your needs into account and meets them to help you look your best. At&nbsp;<a href=\"https://www.thehaircompany.com/\" data-cke-saved-href=\"https://www.thehaircompany.com/\">The Hair Company in New Hampshire</a>, our incredible stylists utilize some of the best products on the market to give you a look you and others will love. Here are four things that set us apart from other hair and beauty salons in Nashua.</p>\n<h3><strong>Talented Stylists</strong></h3>\n<h3>Our hair stylists are the heart and soul of The Hair Company. Each one is not only talented in working with hair but also trained and educated in the art of hair styling. If you are looking for a hair and beauty salon in Nashua,&nbsp;<a href=\"https://www.thehaircompany.com/contact/\" data-cke-saved-href=\"https://www.thehaircompany.com/contact/\">check out The Hair Company today!</a>.</h3>\n<h3><strong>Individualized for You</strong></h3>\n<p>Every person that walks through our doors has a different look and a different taste in hairstyle. Whether you are getting married and looking for wedding hair and makeup or someone just looking for a look refresh before the weekend, we have the expertise and skills to give you a unique look that brings out who you are as an individual.</p>\n<h3><strong>We Only Use the Best Products</strong></h3>\n<p>When it comes to your look, your hair might be the most important aspect of who you are &mdash; which is why we only use the best hair products. Whether you are getting your hair colored or styled, you can only expect the best from The Hair Company.</p>\n<h3><strong>Meet All of Your Hair Needs</strong></h3>\n<p>Your hair stylist should be well-rounded in every aspect of caring for your hair. Whether you are simply getting it cut and styled or getting ready for your wedding, at The Hair Company, our educated hair stylists are trained and talented in every aspect of hair care and prep.</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>Getting your hair styled is usually the highlight of your week. Whether you love the process like we do or you love the way you look afterward (also like we do), you want to find a hair stylist that takes your needs into account and meets them to help you look your best. At&nbsp;<a href=\"https://www.thehaircompany.com/\" data-cke-saved-href=\"https://www.thehaircompany.com/\">The Hair Company in New Hampshire</a>, our incredible stylists utilize some of the best products on the market to give you a look you and others will love. Here are four things that set us apart from other hair and beauty salons in Nashua.</p>\n<h3><strong>Talented Stylists</strong></h3>\n<h3>Our hair stylists are the heart and soul of The Hair Company. Each one is not only talented in working with hair but also trained and educated in the art of hair styling. If you are looking for a hair and beauty salon in Nashua,&nbsp;<a href=\"https://www.thehaircompany.com/contact/\" data-cke-saved-href=\"https://www.thehaircompany.com/contact/\">check out The Hair Company today!</a>.</h3>\n<h3><strong>Individualized for You</strong></h3>\n<p>Every person that walks through our doors has a different look and a different taste in hairstyle. Whether you are getting married and looking for wedding hair and makeup or someone just looking for a look refresh before the weekend, we have the expertise and skills to give you a unique look that brings out who you are as an individual.</p>\n<h3><strong>We Only Use the Best Products</strong></h3>\n<p>When it comes to your look, your hair might be the most important aspect of who you are &mdash; which is why we only use the best hair products. Whether you are getting your hair colored or styled, you can only expect the best from The Hair Company.</p>\n<h3><strong>Meet All of Your Hair Needs</strong></h3>\n<p>Your hair stylist should be well-rounded in every aspect of caring for your hair. Whether you are simply getting it cut and styled or getting ready for your wedding, at The Hair Company, our educated hair stylists are trained and talented in every aspect of hair care and prep.</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>Getting your hair styled is usually the highlight of your week. Whether you love the process like we do or you love the way you look afterward (also like we do), you want to find a hair stylist that takes your needs into account and meets them to help you look your best. At&nbsp;<a href=\"https://www.thehaircompany.com/\" data-cke-saved-href=\"https://www.thehaircompany.com/\">The Hair Company in New Hampshire</a>, our incredible stylists utilize some of the best products on the market to give you a look you and others will love. Here are four things that set us apart from other hair and beauty salons in Nashua.</p>\n<h3><strong>Talented Stylists</strong></h3>\n<h3>Our hair stylists are the heart and soul of The Hair Company. Each one is not only talented in working with hair but also trained and educated in the art of hair styling. If you are looking for a hair and beauty salon in Nashua,&nbsp;<a href=\"https://www.thehaircompany.com/contact/\" data-cke-saved-href=\"https://www.thehaircompany.com/contact/\">check out The Hair Company today!</a>.</h3>\n<h3><strong>Individualized for You</strong></h3>\n<p>Every person that walks through our doors has a different look and a different taste in hairstyle. Whether you are getting married and looking for wedding hair and makeup or someone just looking for a look refresh before the weekend, we have the expertise and skills to give you a unique look that brings out who you are as an individual.</p>\n<h3><strong>We Only Use the Best Products</strong></h3>\n<p>When it comes to your look, your hair might be the most important aspect of who you are &mdash; which is why we only use the best hair products. Whether you are getting your hair colored or styled, you can only expect the best from The Hair Company.</p>\n<h3><strong>Meet All of Your Hair Needs</strong></h3>\n<p>Your hair stylist should be well-rounded in every aspect of caring for your hair. Whether you are simply getting it cut and styled or getting ready for your wedding, at The Hair Company, our educated hair stylists are trained and talented in every aspect of hair care and prep.</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>Getting your hair styled is usually the highlight of your week. Whether you love the process like we do or you love the way you look afterward (also like we do), you want to find a hair stylist that takes your needs into account and meets them to help you look your best. At&nbsp;<a href=\"https://www.thehaircompany.com/\" data-cke-saved-href=\"https://www.thehaircompany.com/\">The Hair Company in New Hampshire</a>, our incredible stylists utilize some of the best products on the market to give you a look you and others will love. Here are four things that set us apart from other hair and beauty salons in Nashua.</p>\n<h3><strong>Talented Stylists</strong></h3>\n<h3>Our hair stylists are the heart and soul of The Hair Company. Each one is not only talented in working with hair but also trained and educated in the art of hair styling. If you are looking for a hair and beauty salon in Nashua,&nbsp;<a href=\"https://www.thehaircompany.com/contact/\" data-cke-saved-href=\"https://www.thehaircompany.com/contact/\">check out The Hair Company today!</a>.</h3>\n<h3><strong>Individualized for You</strong></h3>\n<p>Every person that walks through our doors has a different look and a different taste in hairstyle. Whether you are getting married and looking for wedding hair and makeup or someone just looking for a look refresh before the weekend, we have the expertise and skills to give you a unique look that brings out who you are as an individual.</p>\n<h3><strong>We Only Use the Best Products</strong></h3>\n<p>When it comes to your look, your hair might be the most important aspect of who you are &mdash; which is why we only use the best hair products. Whether you are getting your hair colored or styled, you can only expect the best from The Hair Company.</p>\n<h3><strong>Meet All of Your Hair Needs</strong></h3>\n<p>Your hair stylist should be well-rounded in every aspect of caring for your hair. Whether you are simply getting it cut and styled or getting ready for your wedding, at The Hair Company, our educated hair stylists are trained and talented in every aspect of hair care and prep.</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>', 1, NULL, '2023-10-04 03:04:58', '2023-10-04 03:04:58');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `cover`, `extra_field`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Hair Cutting', 'category/kXlroyuUf5r60E9KNusqJpYDDM3qCg20dCxbSy57.jpg', NULL, 0, '2023-10-03 08:17:18', '2023-11-25 07:20:34'),
(2, 'Hair Colouring', 'category/cXpsXGjaeBljJLjU8Veo5WnZDT0aaYMPFz1VQaGc.jpg', NULL, 0, '2023-10-03 08:17:47', '2023-11-25 07:20:39'),
(3, 'Hair Styling', 'category/WZeIOKtq8v3ky5xSFPGRdJbVyG1L92o35wledrLm.jpg', NULL, 0, '2023-10-03 08:18:12', '2023-11-25 07:20:44'),
(4, 'Gym', 'category/w44ch2pXK1ytg25lmJMAPGU2uTcfFTd5HX3MT9Rg.jpg', NULL, 0, '2023-11-24 17:03:01', '2023-11-25 07:20:50'),
(5, 'Beauty Salon', 'category/iYIo0D0Q4GtwZyGc1EmdgMS0IUOmIQb9Oa5d8DhR.jpg', NULL, 1, '2023-11-25 07:21:48', '2023-11-25 07:21:48'),
(6, 'Tattoo & Piercing', 'category/KDykRCHDcl296sbVHP1hyThAZrDI4aahXZSiEWlc.jpg', NULL, 1, '2023-11-25 07:24:10', '2023-11-25 07:24:10'),
(7, 'Gym & Fitness', 'category/mITHNOSNd00QeriCvOUOwM90udeDHtZQnSDYRqRs.jpg', NULL, 1, '2023-11-25 07:25:17', '2023-11-25 07:25:17'),
(8, 'Therapy Center', 'category/2v3nt9OjcpBiRpZVYgEDnDFQx2SNaiXaoM28cFvw.jpg', NULL, 1, '2023-11-25 07:25:51', '2023-11-25 07:25:51'),
(9, 'Nail Salon', 'category/FBlHyqIEM2OrT0vsKf9LRARQ2vROwim4yhQTGW0u.jpg', NULL, 1, '2023-11-25 07:27:21', '2023-11-25 07:27:21'),
(10, 'Eyebrows & Lashes', 'category/3B0MgowD6WLlZKoljMd4bdLK11cIRVGLIxBkb8fn.jpg', NULL, 1, '2023-11-25 07:28:19', '2023-11-25 07:28:19'),
(11, 'Weight Loss', 'category/qUbKg2I7CkGTLlgVARE0yFVF86oKTHAied1QxrYa.jpg', NULL, 1, '2023-11-25 07:29:02', '2023-11-25 07:29:02'),
(12, 'Aesthetics', 'category/DcaSie4wZoL0gy5MwA49qJQ5lcNpU1uQyCJ8FXBB.jpg', NULL, 1, '2023-11-25 07:31:05', '2023-11-25 07:31:05'),
(13, 'Personal Trainer', 'category/sbcAEdacBgusBtsE14ISDUqnPKPgB5YXlvqIch0t.jpg', NULL, 1, '2023-11-25 07:37:52', '2023-11-25 07:37:52'),
(14, 'Barber Shop', 'category/vnsNRzOXBki5j2mKqVfpvL6gSiHB2mFhIViQ8VTn.jpg', NULL, 1, '2023-11-25 07:38:24', '2023-11-25 07:38:24'),
(15, 'SPA', 'category/vgMvufKpj0rEhP6gjtHGOw8Zjt6xFXqAFGUaxLAR.jpg', NULL, 1, '2023-11-25 07:38:58', '2023-11-25 07:38:58'),
(16, 'Masssage Center', 'category/wDK7zAclzCnq1XoBOJsULHy2RtT6i8p1j5gKEQZW.jpg', NULL, 1, '2023-11-25 07:40:00', '2023-11-25 07:40:00'),
(17, 'Laser Treatment Center', 'category/CR2otgDaRvu4gEXsF4O9g8O0p8tfj4MH4AeJAW2Z.jpg', NULL, 1, '2023-11-25 07:41:14', '2023-11-25 07:41:14'),
(18, 'Dental Care', 'category/I6jehYW3RDpZnHAF6cLR8TbFeCVIMK51Rmxfmmdg.jpg', NULL, 1, '2023-11-25 07:49:37', '2023-11-25 07:49:37'),
(19, 'Smile Creation Center', 'category/HDdUGSOkSi4WQ3CkPMB1YYbQWBv85ww5JeDyBgjX.jpg', NULL, 1, '2023-11-25 07:50:47', '2023-11-25 07:52:06'),
(20, 'test', NULL, NULL, 0, '2024-01-08 02:25:45', '2024-01-08 02:25:53'),
(21, 'dcvdadsv', NULL, NULL, 0, '2024-01-08 02:26:01', '2024-01-08 02:26:12');

-- --------------------------------------------------------

--
-- Table structure for table `chat_rooms`
--

CREATE TABLE `chat_rooms` (
  `id` bigint UNSIGNED NOT NULL,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `last_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_message_type` tinyint DEFAULT NULL,
  `extra_fields` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lng` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `lat`, `lng`, `country`, `extra_field`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Ahmedabad', '23.0225', '72.5714', 'India', NULL, 0, '2023-10-03 08:19:06', '2023-12-12 06:12:15'),
(2, 'Bhavnagar', '21.7645', '72.1519', 'India', NULL, 0, '2023-10-03 08:21:56', '2023-12-12 06:12:11'),
(3, 'Surat', '21.1702', '72.8311', 'India', NULL, 0, '2023-10-03 08:23:16', '2023-12-12 06:12:06'),
(4, 'Kochi', '9.9674277', '76.2454436', 'India', NULL, 1, '2023-12-12 06:11:51', '2023-12-12 06:11:51'),
(5, 'Thiruvananthapuram', '8.4882267', '76.947551', 'India', NULL, 1, '2023-12-12 06:17:12', '2023-12-12 06:17:12');

-- --------------------------------------------------------

--
-- Table structure for table `commission`
--

CREATE TABLE `commission` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` int NOT NULL,
  `rate` double NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `appointment_id` int DEFAULT NULL,
  `complaints_on` tinyint NOT NULL,
  `issue_with` tinyint NOT NULL,
  `driver_id` int DEFAULT NULL,
  `freelancer_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `reason_id` int DEFAULT NULL,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `short_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `images` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversions`
--

CREATE TABLE `conversions` (
  `id` bigint UNSIGNED NOT NULL,
  `sender_id` int NOT NULL,
  `room_id` int NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_type` tinyint NOT NULL,
  `reported` tinyint DEFAULT NULL,
  `extra_fields` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `filters`
--

CREATE TABLE `filters` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `individual`
--

CREATE TABLE `individual` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` int NOT NULL,
  `background` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `categories` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `lat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lng` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `rating` double(10,2) NOT NULL DEFAULT '0.00',
  `fee_start` double(10,2) NOT NULL DEFAULT '0.00',
  `total_rating` int NOT NULL,
  `website` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `timing` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `images` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `zipcode` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `verified` tinyint NOT NULL DEFAULT '1',
  `in_home` tinyint NOT NULL DEFAULT '1',
  `popular` tinyint NOT NULL DEFAULT '1',
  `have_shop` tinyint NOT NULL DEFAULT '1',
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `individual`
--

INSERT INTO `individual` (`id`, `uid`, `background`, `categories`, `address`, `lat`, `lng`, `cid`, `about`, `rating`, `fee_start`, `total_rating`, `website`, `timing`, `images`, `zipcode`, `verified`, `in_home`, `popular`, `have_shop`, `extra_field`, `status`, `created_at`, `updated_at`) VALUES
(1, 9, 'salon/image/76f3Arju5dgG9WDsZyGxBp5lgHCJP36ZcFNndyFE.jpg', '2', 'address', '23.98', '23.56', '2', 'test content', 0.00, 100.00, 0, NULL, NULL, NULL, '676554', 1, 1, 1, 1, NULL, 1, '2023-11-23 08:56:50', '2023-11-24 15:39:42');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2018_11_06_222923_create_transactions_table', 1),
(4, '2018_11_07_192923_create_transfers_table', 1),
(5, '2018_11_15_124230_create_wallets_table', 1),
(6, '2019_08_19_000000_create_failed_jobs_table', 1),
(7, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(8, '2021_11_02_202021_update_wallets_uuid_table', 1),
(9, '2023_03_28_094926_create_ads_plans_table', 1),
(10, '2023_03_29_045501_create_address_table', 1),
(11, '2023_03_29_045534_create_appointments_table', 1),
(12, '2023_03_29_045604_create_banners_table', 1),
(13, '2023_03_29_045625_create_blogs_table', 1),
(14, '2023_03_29_045653_create_categories_table', 1),
(15, '2023_03_29_045713_create_chat_rooms_table', 1),
(16, '2023_03_29_045734_create_cities_table', 1),
(17, '2023_03_29_045755_create_commission_table', 1),
(18, '2023_03_29_045816_create_complaints_table', 1),
(19, '2023_03_29_045836_create_contacts_table', 1),
(20, '2023_03_29_045856_create_conversions_table', 1),
(21, '2023_03_29_045919_create_individual_table', 1),
(22, '2023_03_29_045943_create_offers_table', 1),
(23, '2023_03_29_050005_create_otp_table', 1),
(24, '2023_03_29_050029_create_owner_reviews_table', 1),
(25, '2023_03_29_050050_create_packages_table', 1),
(26, '2023_03_29_050114_create_packages_reviews_table', 1),
(27, '2023_03_29_050134_create_pages_table', 1),
(28, '2023_03_29_050155_create_payments_table', 1),
(29, '2023_03_29_050215_create_products_table', 1),
(30, '2023_03_29_050236_create_products_orders_table', 1),
(31, '2023_03_29_050258_create_product_categories_table', 1),
(32, '2023_03_29_050320_create_product_reviews_table', 1),
(33, '2023_03_29_050344_create_product_sub_category_table', 1),
(34, '2023_03_29_050405_create_redeem_table', 1),
(35, '2023_03_29_050432_create_referral_table', 1),
(36, '2023_03_29_050452_create_referralcodes_table', 1),
(37, '2023_03_29_050514_create_register_request_table', 1),
(38, '2023_03_29_050537_create_salon_table', 1),
(39, '2023_03_29_050555_create_services_table', 1),
(40, '2023_03_29_050615_create_service_reviews_table', 1),
(41, '2023_03_29_050635_create_settings_table', 1),
(42, '2023_03_29_050655_create_specialist_table', 1),
(43, '2023_03_29_050717_create_timeslots_table', 1),
(45, '2023_10_11_113337_add_column_to_salon_table', 2),
(46, '2023_12_11_091053_create_salon_services_table', 3),
(47, '2024_01_08_054020_add_column_id_proof_to_salon_table', 4),
(48, '2024_01_08_060724_add_column_to_banners_table', 5),
(50, '2024_01_10_113648_create_filters_table', 6),
(51, '2024_01_10_145247_create_user_services_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_descriptions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinyint NOT NULL,
  `for` tinyint NOT NULL,
  `discount` double(10,2) NOT NULL,
  `upto` double(10,2) NOT NULL,
  `expire` date NOT NULL,
  `freelancer_ids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_usage` int NOT NULL,
  `min_cart_value` double(10,2) NOT NULL,
  `validations` tinyint NOT NULL,
  `user_limit_validation` int DEFAULT NULL,
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `otp`
--

CREATE TABLE `otp` (
  `id` bigint UNSIGNED NOT NULL,
  `otp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `otp`
--

INSERT INTO `otp` (`id`, `otp`, `email`, `status`, `extra_field`, `created_at`, `updated_at`) VALUES
(1, '984485', 'muthup611@gmail.com', 0, NULL, '2023-11-23 03:23:27', '2023-11-23 03:23:27'),
(2, '463012', 'muthup611@gmail.com', 0, NULL, '2023-11-23 03:23:55', '2023-11-23 03:23:55'),
(3, '382597', 'muthup611@gmail.com', 0, NULL, '2023-11-23 03:24:05', '2023-11-23 03:24:05'),
(4, '419784', 'muthup611@gmail.com', 0, NULL, '2023-11-23 03:39:05', '2023-11-23 03:39:05'),
(5, '524079', 'muthup611@gmail.com', 0, NULL, '2023-11-23 03:46:04', '2023-11-23 03:46:04'),
(6, '298320', 'muthup611@gmail.com', 0, NULL, '2023-11-23 03:49:44', '2023-11-23 03:49:44');

-- --------------------------------------------------------

--
-- Table structure for table `owner_reviews`
--

CREATE TABLE `owner_reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` int NOT NULL,
  `freelancer_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `rating` double(10,2) DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` int NOT NULL,
  `package_from` tinyint NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descriptions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `images` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `duration` double(10,2) DEFAULT NULL,
  `price` double(10,2) DEFAULT NULL,
  `off` double(10,2) DEFAULT NULL,
  `discount` double(10,2) DEFAULT NULL,
  `specialist_ids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packages_reviews`
--

CREATE TABLE `packages_reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` int NOT NULL,
  `package_id` int NOT NULL,
  `freelancer_id` int NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `rating` double(10,2) DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint UNSIGNED NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `name`, `content`, `extra_field`, `status`, `created_at`, `updated_at`) VALUES
(1, 'About us', 'About us', 'NA', 1, '2023-10-03 05:42:48', '2023-10-03 05:42:48'),
(2, 'Privacy', 'Privacy policy', 'NA', 1, '2023-10-03 05:42:48', '2023-11-16 15:17:14'),
(3, 'Terms & Conditions', 'Terms & Conditions', 'NA', 1, '2023-10-03 05:42:48', '2023-10-03 05:42:48'),
(4, 'Refund Policy', 'Refund Policy', 'NA', 1, '2023-10-03 05:42:48', '2023-10-03 05:42:48'),
(5, 'Frequently Asked Questions', 'Frequently Asked Questions', 'NA', 1, '2023-10-03 05:42:48', '2023-10-03 05:42:48'),
(6, 'Help', 'Help', 'NA', 1, '2023-10-03 05:42:48', '2023-10-03 05:42:48'),
(7, 'Legal Mentions', 'Legal Mentions', 'NA', 1, '2023-10-03 05:42:48', '2023-10-03 05:42:48'),
(8, 'Cookies', 'Cookies', 'NA', 1, '2023-10-03 05:42:48', '2023-10-03 05:42:48');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `env` tinyint NOT NULL,
  `status` tinyint NOT NULL,
  `currency_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creds` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `name`, `env`, `status`, `currency_code`, `cover`, `creds`, `extra_field`, `created_at`, `updated_at`) VALUES
(1, 'COD', 1, 1, 'USD', 'NA', NULL, NULL, '2023-10-03 05:42:48', '2023-10-03 05:42:48'),
(2, 'Stripe', 1, 1, 'USD', 'NA', NULL, NULL, '2023-10-03 05:42:48', '2023-10-03 05:42:48'),
(3, 'PayPal', 1, 1, 'USD', 'NA', NULL, NULL, '2023-10-03 05:42:48', '2023-10-03 05:42:48'),
(4, 'PayTM', 1, 1, 'INR', 'NA', NULL, NULL, '2023-10-03 05:42:48', '2023-10-03 05:42:48'),
(5, 'RazorPay', 1, 1, 'INR', 'NA', NULL, NULL, '2023-10-03 05:42:48', '2023-10-03 05:42:48'),
(6, 'InstaMOJO', 1, 1, 'INR', 'NA', NULL, NULL, '2023-10-03 05:42:48', '2023-10-03 05:42:48'),
(7, 'PayStack', 1, 1, 'NGN', 'NA', NULL, NULL, '2023-10-03 05:42:48', '2023-10-03 05:42:48'),
(8, 'Flutterwave', 1, 1, 'NGN', 'NA', NULL, NULL, '2023-10-03 05:42:48', '2023-10-03 05:42:48');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `freelacer_id` int NOT NULL,
  `cover` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `images` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_price` double(10,2) DEFAULT NULL,
  `sell_price` double(10,2) DEFAULT NULL,
  `discount` double(10,2) DEFAULT NULL,
  `cate_id` int DEFAULT NULL,
  `sub_cate_id` int DEFAULT NULL,
  `in_home` tinyint DEFAULT NULL,
  `is_single` tinyint DEFAULT NULL,
  `have_gram` tinyint DEFAULT NULL,
  `gram` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `have_kg` tinyint DEFAULT NULL,
  `kg` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `have_pcs` tinyint DEFAULT NULL,
  `pcs` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `have_liter` tinyint DEFAULT NULL,
  `liter` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `have_ml` tinyint DEFAULT NULL,
  `ml` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descriptions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `key_features` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `disclaimer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `exp_date` date DEFAULT NULL,
  `in_offer` tinyint NOT NULL DEFAULT '2',
  `in_stock` tinyint NOT NULL DEFAULT '0',
  `rating` double(10,2) DEFAULT NULL,
  `total_rating` int DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `freelacer_id`, `cover`, `name`, `images`, `original_price`, `sell_price`, `discount`, `cate_id`, `sub_cate_id`, `in_home`, `is_single`, `have_gram`, `gram`, `have_kg`, `kg`, `have_pcs`, `pcs`, `have_liter`, `liter`, `have_ml`, `ml`, `descriptions`, `key_features`, `disclaimer`, `exp_date`, `in_offer`, `in_stock`, `rating`, `total_rating`, `status`, `extra_field`, `created_at`, `updated_at`) VALUES
(1, 4, 'a', 'Love Butter Shampoo ', 'g', 100.00, 150.00, 0.00, 1, 1, 1, 1, 1, '500', 0, '0', 0, '0', 0, '0', 0, '0', 'some description here', 'key feature will be here', 'disclaimer on here', '2024-12-01', 2, 0, 3.00, 3, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products_orders`
--

CREATE TABLE `products_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` int NOT NULL,
  `freelancer_id` int NOT NULL DEFAULT '0',
  `salon_id` int NOT NULL DEFAULT '0',
  `date_time` datetime NOT NULL,
  `paid_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_to` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `orders` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `total` double(10,2) DEFAULT NULL,
  `tax` double(10,2) DEFAULT NULL,
  `grand_total` double(10,2) DEFAULT NULL,
  `discount` double(10,2) DEFAULT NULL,
  `driver_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_charge` double(10,2) DEFAULT NULL,
  `wallet_used` tinyint NOT NULL DEFAULT '0',
  `wallet_price` double(10,2) DEFAULT NULL,
  `coupon_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `extra` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `pay_key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payStatus` tinyint NOT NULL DEFAULT '0',
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `name`, `cover`, `extra_field`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Hair Care And Styling', 'shop/category/xxCF50zn7S8BDYi36yeP3ZbD5TbbIf6NSUyPouPl.jpg', NULL, 1, '2023-11-16 08:26:30', '2023-11-16 08:26:30'),
(2, 'Face Care', 'shop/category/bgmagC7liY7dIZoDzQS9IsjtB3Bdf3tK64TFoahc.jpg', NULL, 1, '2023-11-16 08:28:34', '2023-11-16 08:28:34');

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` int NOT NULL,
  `product_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `rating` double(10,2) DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_sub_category`
--

CREATE TABLE `product_sub_category` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cate_id` int NOT NULL,
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_sub_category`
--

INSERT INTO `product_sub_category` (`id`, `name`, `cover`, `cate_id`, `extra_field`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Face Wash ', 'shop/subcategory/1NTr3xu9A3cIQMfvNsEHXeEyAx4upCj9oEQJFI5T.jpg', 1, NULL, 1, '2023-11-16 10:17:25', '2023-11-16 10:17:25'),
(2, 'Conditioner ', 'shop/subcategory/GGjL9tCAecOiDeF0kD2U6MMa7plk7TN4ov8UB0lr.png', 1, NULL, 1, '2023-11-16 10:18:01', '2023-11-16 10:18:01');

-- --------------------------------------------------------

--
-- Table structure for table `redeem`
--

CREATE TABLE `redeem` (
  `id` bigint UNSIGNED NOT NULL,
  `owner` int NOT NULL,
  `redeemer` int NOT NULL,
  `code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referral`
--

CREATE TABLE `referral` (
  `id` bigint UNSIGNED NOT NULL,
  `amount` double(10,2) NOT NULL,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `limit` int NOT NULL,
  `who_received` tinyint NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `referral`
--

INSERT INTO `referral` (`id`, `amount`, `title`, `message`, `limit`, `who_received`, `status`, `extra_field`, `created_at`, `updated_at`) VALUES
(1, 10.00, 'Refer a friend and get $100 wallet amount', 'invite friends to handyMan Service and get $100 when your friend signup with us', 100, 3, 1, NULL, '2023-10-04 05:39:27', '2023-12-12 06:39:54');

-- --------------------------------------------------------

--
-- Table structure for table `referralcodes`
--

CREATE TABLE `referralcodes` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` int NOT NULL,
  `code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `register_request`
--

CREATE TABLE `register_request` (
  `id` bigint UNSIGNED NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` tinyint DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zipcode` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `categories` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `lat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lng` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `about` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `fee_start` double(10,2) NOT NULL DEFAULT '0.00',
  `cid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `register_request`
--

INSERT INTO `register_request` (`id`, `first_name`, `last_name`, `email`, `password`, `country_code`, `mobile`, `cover`, `gender`, `type`, `zipcode`, `categories`, `address`, `lat`, `lng`, `name`, `about`, `fee_start`, `cid`, `extra_field`, `status`, `created_at`, `updated_at`) VALUES
(1, 'aslam', 'p', 'aslam@gmail.com', '$2y$10$Ry0fAy1JO6Z.pVc2zXXec.eAaqvSBGiMjsTx.j00Ku5SfdIlsA/hW', '91', '9897676545', NULL, 1, 'salon', NULL, '[1,2]', NULL, NULL, NULL, 'aslam salon', NULL, 100.00, '1', NULL, 0, NULL, '2024-01-01 07:53:47'),
(2, 'ubaid', 'v', 'ubaid@gmail.com', '$2y$10$Ry0fAy1JO6Z.pVc2zXXec.eAaqvSBGiMjsTx.j00Ku5SfdIlsA/hW', '91', '9834565434', NULL, 1, 'freelancer', NULL, '[2]', NULL, NULL, NULL, 'ubaid salon', NULL, 80.00, '2', NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `salon`
--

CREATE TABLE `salon` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `categories` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `lat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lng` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `rating` double(10,2) NOT NULL DEFAULT '0.00',
  `total_rating` int NOT NULL,
  `website` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `timing` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `images` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `zipcode` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `service_at_home` tinyint NOT NULL DEFAULT '1',
  `verified` tinyint NOT NULL DEFAULT '1',
  `in_home` tinyint NOT NULL DEFAULT '1',
  `popular` tinyint NOT NULL DEFAULT '1',
  `have_shop` tinyint NOT NULL DEFAULT '1',
  `have_stylist` tinyint NOT NULL DEFAULT '1',
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `upgrade` int NOT NULL DEFAULT '0',
  `id_proof` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_proof_back` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_ifsc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_customer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salon`
--

INSERT INTO `salon` (`id`, `uid`, `name`, `cover`, `categories`, `address`, `lat`, `lng`, `cid`, `about`, `rating`, `total_rating`, `website`, `timing`, `images`, `zipcode`, `service_at_home`, `verified`, `in_home`, `popular`, `have_shop`, `have_stylist`, `extra_field`, `upgrade`, `id_proof`, `id_proof_back`, `bank_name`, `bank_ifsc`, `bank_account_number`, `bank_customer_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, 'Relaifing Salon', 'salon/image/sin0iFUsZ4tXOAUQiT6Kk65sbWsJDYTnENJEGVdS.jpg', '1', 'Someshwar Nagar, Vishala, Ahmedabad, Gujarat 380007', '23.0297425', '72.5077827', '1', 'Hair Salon', 0.00, 0, 'techinwallet.com', NULL, NULL, '676556', 1, 1, 1, 1, 1, 1, NULL, 1, 'salon/proof/zFZNHfDfGBgghtedKd7mWxECSGdUwXIBP9SbqwnK.jpg', NULL, 'kotak bank', '454546546345654', '544011112805', 'thamnees', 1, '2023-10-12 03:41:44', '2024-01-08 02:23:25'),
(3, 9, 'saniya', 'salon/image/76f3Arju5dgG9WDsZyGxBp5lgHCJP36ZcFNndyFE.jpg', '2', 'address', '23.98', '23.56', '2', 'test content', 100.00, 0, NULL, NULL, NULL, '676554', 1, 1, 1, 1, 1, 1, NULL, 1, 'freelancer/proof/OWcfUY7QcqlumR3vkDzKKzAEJ295mwW3tjCjuCRZ.jpg', NULL, 'kotak bank', 'kkbk0009307', '5443434321324', 'saniya', 1, '2023-11-23 08:56:50', '2023-12-15 13:24:48'),
(5, 15, 'aslam salon', 'sample', '[1,2]', NULL, NULL, NULL, '1', NULL, 100.00, 0, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 1, 1, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2024-01-01 07:53:47', '2024-01-08 02:23:24');

-- --------------------------------------------------------

--
-- Table structure for table `salon_services`
--

CREATE TABLE `salon_services` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` int NOT NULL,
  `service_id` int NOT NULL,
  `cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` double(10,2) DEFAULT NULL,
  `price` double(10,2) DEFAULT NULL,
  `off` int NOT NULL,
  `discount` double(10,2) DEFAULT NULL,
  `descriptions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `images` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salon_services`
--

INSERT INTO `salon_services` (`id`, `uid`, `service_id`, `cover`, `duration`, `price`, `off`, `discount`, `descriptions`, `images`, `extra_field`, `status`, `created_at`, `updated_at`) VALUES
(3, 9, 1, NULL, 30.00, 150.00, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL),
(4, 9, 2, NULL, 10.00, 100.00, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL),
(5, 4, 1, NULL, 20.00, 150.00, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint UNSIGNED NOT NULL,
  `cate_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descriptions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `images` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `cate_id`, `name`, `cover`, `descriptions`, `images`, `extra_field`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Buzz Cut', NULL, NULL, NULL, NULL, 2, NULL, '2023-12-12 05:29:07'),
(2, 1, 'Crew cut', NULL, NULL, NULL, NULL, 2, NULL, '2023-12-12 05:28:57'),
(5, 5, 'Haircuts', NULL, NULL, NULL, NULL, 1, '2023-12-12 05:28:43', '2024-01-08 03:51:26'),
(6, 6, 'Traditional And Old School Tatto Style', NULL, NULL, NULL, NULL, 1, '2023-12-12 05:31:21', '2023-12-12 05:31:21'),
(7, 8, 'Individual Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-12 05:33:33', '2023-12-12 05:33:33'),
(8, 7, 'Strength Training', NULL, NULL, NULL, NULL, 1, '2023-12-12 05:34:34', '2023-12-12 05:34:34'),
(9, 9, 'Manicures', NULL, NULL, NULL, NULL, 1, '2023-12-12 05:36:05', '2024-01-08 03:51:38'),
(10, 10, 'Waxing', NULL, NULL, NULL, NULL, 1, '2023-12-12 05:36:43', '2023-12-12 05:36:43'),
(11, 11, 'Diet Plan', NULL, NULL, NULL, NULL, 1, '2023-12-12 05:37:39', '2023-12-12 05:37:39'),
(12, 12, 'Hydra  Facial', NULL, NULL, NULL, NULL, 1, '2023-12-12 05:40:01', '2023-12-12 05:40:01'),
(13, 13, 'Protein Control', NULL, NULL, NULL, NULL, 1, '2023-12-12 05:41:11', '2023-12-12 05:41:11'),
(14, 14, 'Classic Haircut', NULL, NULL, NULL, NULL, 1, '2023-12-12 05:41:50', '2023-12-12 05:41:50'),
(15, 15, 'Foot And Back Massage', NULL, NULL, NULL, NULL, 1, '2023-12-12 05:42:37', '2023-12-12 05:42:37'),
(16, 16, 'Full Body Massage', NULL, NULL, NULL, NULL, 1, '2023-12-12 05:43:39', '2023-12-12 05:43:39'),
(17, 17, 'Skin Enchancement', NULL, NULL, NULL, NULL, 1, '2023-12-12 05:45:51', '2023-12-12 05:45:51'),
(18, 18, 'Teeth Cleaning', NULL, NULL, NULL, NULL, 1, '2023-12-12 05:46:47', '2023-12-12 05:46:47'),
(19, 19, 'Teeth Whitening', NULL, NULL, NULL, NULL, 1, '2023-12-12 05:47:41', '2023-12-12 05:47:41'),
(20, 5, 'Hair  Styling', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:26:58', '2023-12-14 08:26:58'),
(21, 5, 'Hair Coloring', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:27:26', '2023-12-14 08:27:26'),
(22, 5, 'Manicures', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:27:49', '2023-12-14 08:27:49'),
(23, 5, 'Pedicures', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:28:56', '2023-12-14 08:28:56'),
(24, 5, 'Facials', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:29:14', '2023-12-14 08:29:14'),
(25, 5, 'Waxing', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:29:31', '2023-12-14 08:29:31'),
(26, 5, 'Hair Spa', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:29:46', '2023-12-14 08:29:46'),
(27, 5, 'Hair Straightening', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:30:34', '2023-12-14 08:30:34'),
(28, 5, 'Body Polish', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:30:55', '2023-12-14 08:30:55'),
(29, 5, 'Clean Up', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:31:17', '2023-12-14 08:31:17'),
(30, 5, 'Hair Botox', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:31:41', '2023-12-14 08:31:41'),
(31, 5, 'Hair Highlights', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:33:14', '2023-12-14 08:33:14'),
(32, 5, 'GFC(Growth Factor Concentrate Therapy)', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:35:16', '2023-12-14 08:35:16'),
(33, 5, 'Repechage Cleanup ', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:41:00', '2023-12-14 08:41:00'),
(34, 5, 'Thglgo cleanup', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:44:17', '2023-12-14 08:44:17'),
(35, 5, 'Cheris  Cleanup', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:45:18', '2023-12-14 08:45:18'),
(36, 5, 'Hydra  Facial', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:45:39', '2023-12-14 08:45:39'),
(37, 5, 'Repechage  STD Seaweed Facial', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:46:57', '2023-12-14 08:46:57'),
(38, 5, 'Thalgo Adv .Marine  Facial ', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:47:54', '2023-12-14 08:47:54'),
(39, 5, 'Remy Laure Peral Glow Facial ', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:49:36', '2023-12-14 08:49:36'),
(40, 5, 'Adv Microdermabrasion Facial', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:51:47', '2023-12-14 08:51:47'),
(41, 5, 'Adv Oxygen Power Glow Facial', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:54:25', '2023-12-14 08:54:25'),
(42, 5, 'Skin Tightening Facial', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:55:36', '2023-12-14 08:55:36'),
(43, 5, 'STD Oxygen Power  Glow Facial ', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:56:42', '2023-12-14 08:56:42'),
(44, 5, 'Chery Facial', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:57:51', '2023-12-14 08:57:51'),
(45, 5, 'Full Body  Waxing', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:58:37', '2023-12-14 08:58:37'),
(46, 5, 'Full  Leg  Waxing ', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:59:12', '2023-12-14 08:59:12'),
(47, 5, 'Half Leg Waxing', NULL, NULL, NULL, NULL, 1, '2023-12-14 08:59:35', '2023-12-14 08:59:35'),
(48, 5, 'Full Arms Waxing', NULL, NULL, NULL, NULL, 1, '2023-12-14 09:00:38', '2023-12-14 09:00:38'),
(49, 5, 'Half Arms Waxing', NULL, NULL, NULL, NULL, 1, '2023-12-14 09:01:03', '2023-12-14 09:01:03'),
(50, 5, 'Under Arms Waxing ', NULL, NULL, NULL, NULL, 1, '2023-12-14 09:01:35', '2023-12-14 09:01:35'),
(51, 5, 'Bikini Waxing', NULL, NULL, NULL, NULL, 1, '2023-12-14 09:02:11', '2023-12-14 09:02:11'),
(52, 5, 'Full Back And Front Waxing', NULL, NULL, NULL, NULL, 1, '2023-12-14 09:03:56', '2023-12-14 09:03:56'),
(53, 5, 'Tummy And Chest Waxing', NULL, NULL, NULL, NULL, 1, '2023-12-14 09:04:35', '2023-12-14 09:04:35'),
(54, 5, 'Jawline And Upper Line Waxing', NULL, NULL, NULL, NULL, 1, '2023-12-14 09:05:19', '2023-12-14 09:05:19'),
(55, 5, 'Forehead And Eyebrow Waxing', NULL, NULL, NULL, NULL, 1, '2023-12-14 09:07:27', '2023-12-14 09:07:27'),
(56, 5, 'Nostrils Waxing ', NULL, NULL, NULL, NULL, 1, '2023-12-14 09:08:02', '2023-12-14 09:08:02'),
(57, 5, 'Nose Black Head Removal', NULL, NULL, NULL, NULL, 1, '2023-12-14 09:08:58', '2023-12-14 09:08:58'),
(58, 5, 'Ears Waxing ', NULL, NULL, NULL, NULL, 1, '2023-12-14 09:09:21', '2023-12-14 09:09:21'),
(59, 5, 'Foot Pedicure', NULL, NULL, NULL, NULL, 1, '2023-12-14 09:09:59', '2023-12-14 09:09:59'),
(60, 5, 'Luxury Spa Manicure', NULL, NULL, NULL, NULL, 1, '2023-12-14 09:11:56', '2023-12-14 09:11:56'),
(61, 5, 'STD Spa Pedicure ', NULL, NULL, NULL, NULL, 1, '2023-12-14 09:12:40', '2023-12-14 09:12:40'),
(62, 5, 'Full Body  De-Tan', NULL, NULL, NULL, NULL, 1, '2023-12-14 09:31:27', '2023-12-14 09:31:27'),
(63, 19, 'Filling', NULL, NULL, NULL, NULL, 1, '2023-12-15 05:39:56', '2023-12-15 05:39:56'),
(64, 5, 'Full Back And Front De Tan', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:24:02', '2023-12-16 04:24:02'),
(65, 5, 'Half Back And Front De Tan', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:24:38', '2023-12-16 04:24:38'),
(66, 5, 'Half Leg De Tan / Bleach', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:25:37', '2023-12-16 04:25:37'),
(67, 5, 'Full  Leg De Tan / Bleach', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:26:16', '2023-12-16 04:26:16'),
(68, 5, 'Half Arms  De Tan / Bleach', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:27:05', '2023-12-16 04:27:05'),
(69, 5, 'Full Arms De Tan / Bleach', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:27:45', '2023-12-16 04:27:45'),
(70, 5, 'Blouse Line De Tan / Bleach', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:28:30', '2023-12-16 04:28:30'),
(71, 5, 'Face And Neck De Tan / Bleach', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:29:21', '2023-12-16 04:29:21'),
(72, 5, 'Feet De Tan /Bleach', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:30:33', '2023-12-16 04:30:33'),
(73, 5, 'Under Arms De Tan /Bleach', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:31:22', '2023-12-16 04:31:22'),
(74, 5, 'Upper Lip De Tan / Bleach', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:33:01', '2023-12-16 04:33:01'),
(75, 5, 'Repechage Body Polishing ', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:34:53', '2023-12-16 04:34:53'),
(76, 5, 'Lyco Body Polishing ', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:36:48', '2023-12-16 04:36:48'),
(77, 5, 'Repechage  Back Polishing', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:37:46', '2023-12-16 04:37:46'),
(78, 5, 'Full Body Massage  Without  Steam', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:38:38', '2023-12-16 04:38:38'),
(79, 5, 'Full Body Massage With Steam', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:39:14', '2023-12-16 04:39:14'),
(80, 5, 'Foot Massage', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:39:39', '2023-12-16 04:39:39'),
(81, 5, 'Hot Oil  Massage', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:40:17', '2023-12-16 04:40:17'),
(82, 5, 'Ladies Pixie / Bob Hair Cut', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:41:10', '2023-12-16 04:41:10'),
(83, 5, 'Ladies Layer Hair Cut', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:42:11', '2023-12-16 04:42:11'),
(84, 5, 'Ladies Straight Hair Cut', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:43:04', '2023-12-16 04:43:04'),
(85, 5, 'Fringe Hair Cut', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:43:46', '2023-12-16 04:43:46'),
(86, 5, 'Mens Hair Cut ', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:44:12', '2023-12-16 04:44:12'),
(87, 5, 'Child Cut ', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:44:31', '2023-12-16 04:44:31'),
(88, 5, 'Beard Setting', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:45:13', '2023-12-16 04:45:13'),
(89, 5, 'Hair Wash', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:45:41', '2023-12-16 04:45:41'),
(90, 5, 'Deep Conditioning Hair Wash', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:46:33', '2023-12-16 04:46:33'),
(91, 5, 'Blow Dry ', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:47:06', '2023-12-16 04:47:06'),
(92, 5, 'Wash And Blow Dry', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:47:33', '2023-12-16 04:47:33'),
(93, 5, 'Basic Hair Style ', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:49:34', '2023-12-16 04:49:34'),
(94, 5, 'Advanced  Hair Style ', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:50:10', '2023-12-16 04:50:10'),
(95, 5, 'Hair BTX Keratin  Spa', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:51:26', '2023-12-16 04:51:26'),
(96, 5, 'Pure Keratin Spa ', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:51:57', '2023-12-16 04:51:57'),
(97, 5, 'Loreal Power  Spa', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:52:42', '2023-12-16 04:52:42'),
(98, 5, 'Dandraff Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:54:04', '2023-12-16 04:54:04'),
(99, 5, 'Detox Hair Spa ', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:54:42', '2023-12-16 04:54:42'),
(100, 5, 'Olaplex Hair Repair Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:55:39', '2023-12-16 04:55:39'),
(101, 5, 'K18 Hair Repair Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:56:23', '2023-12-16 04:56:23'),
(102, 5, 'Balayage Coloring', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:57:13', '2023-12-16 04:57:13'),
(103, 5, 'Ombre Coloring ', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:57:45', '2023-12-16 04:57:45'),
(104, 5, 'Global Highlights', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:58:30', '2023-12-16 04:58:30'),
(105, 5, 'Highlights', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:59:12', '2023-12-16 04:59:12'),
(106, 5, 'Global Coloring', NULL, NULL, NULL, NULL, 1, '2023-12-16 04:59:41', '2023-12-16 04:59:41'),
(107, 5, 'Keratin Cysteine Complex ', NULL, NULL, NULL, NULL, 1, '2023-12-16 05:01:12', '2023-12-16 05:01:12'),
(108, 5, 'Keratin-Kera Smooth ', NULL, NULL, NULL, NULL, 1, '2023-12-16 07:43:09', '2023-12-16 07:43:09'),
(109, 5, 'Keratin-MK Organic Keratin', NULL, NULL, NULL, NULL, 1, '2023-12-16 07:43:52', '2023-12-16 07:43:52'),
(110, 5, 'Keratin- MK Hair Botox', NULL, NULL, NULL, NULL, 1, '2023-12-16 07:45:42', '2023-12-16 07:45:42'),
(111, 5, 'Full  Hair Smooting ', NULL, NULL, NULL, NULL, 1, '2023-12-16 07:48:34', '2023-12-16 07:48:34'),
(112, 5, 'Smooting Root Touch UP', NULL, NULL, NULL, NULL, 1, '2023-12-16 07:49:29', '2023-12-16 07:49:29'),
(113, 5, 'Ear TO Ear  Smooting', NULL, NULL, NULL, NULL, 1, '2023-12-16 07:50:39', '2023-12-16 07:50:39'),
(114, 5, 'Fringe Smooting', NULL, NULL, NULL, NULL, 1, '2023-12-16 07:51:04', '2023-12-16 07:51:04'),
(115, 5, 'Hair Reduction', NULL, NULL, NULL, NULL, 1, '2023-12-16 07:51:25', '2023-12-16 07:51:25'),
(116, 5, 'HD Party Makeup', NULL, NULL, NULL, NULL, 1, '2023-12-16 07:52:17', '2023-12-16 07:52:17'),
(117, 5, 'Temptu Air Brush Party Makeup', NULL, NULL, NULL, NULL, 1, '2023-12-16 07:53:01', '2023-12-16 07:53:01'),
(118, 5, 'HD Bridal Makeup', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:01:13', '2023-12-16 08:01:13'),
(119, 5, 'Temptu Air Brush Bridal Makeup', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:03:18', '2023-12-16 08:03:18'),
(120, 5, 'Face Friming', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:04:20', '2023-12-16 08:04:20'),
(121, 5, 'Microdermabrasion', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:05:23', '2023-12-16 08:05:23'),
(122, 5, 'Power Plex  Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:06:38', '2023-12-16 08:06:38'),
(123, 5, 'Balayage', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:07:33', '2023-12-16 08:07:33'),
(124, 5, 'Eyestical  Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:08:16', '2023-12-16 08:08:16'),
(125, 5, 'Kerablast', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:08:37', '2023-12-16 08:08:37'),
(126, 5, 'Express Manicure', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:09:06', '2023-12-16 08:09:06'),
(127, 5, 'Express Pedicure', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:09:47', '2023-12-16 08:09:47'),
(128, 5, 'Classic Manicure', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:10:17', '2023-12-16 08:10:17'),
(129, 5, 'Classic Pedicure', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:10:45', '2023-12-16 08:10:45'),
(130, 5, 'Foot Logix Callus Removal', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:12:15', '2023-12-16 08:12:15'),
(131, 5, 'Keratin Express', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:12:42', '2023-12-16 08:12:42'),
(132, 5, 'Keratin Short Hair', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:13:07', '2023-12-16 08:13:07'),
(133, 5, 'Keratin Mid Length', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:13:37', '2023-12-16 08:13:37'),
(134, 5, 'Keratin Long Hair', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:14:06', '2023-12-16 08:14:06'),
(135, 5, 'Classic Mani And Pedi ', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:14:55', '2023-12-16 08:14:55'),
(136, 5, 'Line UP', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:15:19', '2023-12-16 08:15:19'),
(137, 5, 'Traditional Razor Shave', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:15:53', '2023-12-16 08:15:53'),
(138, 5, 'Light Beard Triming', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:16:32', '2023-12-16 08:16:32'),
(139, 5, 'Full Bread Trim', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:53:43', '2023-12-16 08:53:43'),
(140, 5, 'Golden Facial', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:54:42', '2023-12-16 08:54:42'),
(141, 5, 'Deep Cleansing', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:56:34', '2023-12-16 08:56:34'),
(142, 5, 'Youth Full Skin', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:57:01', '2023-12-16 08:57:01'),
(143, 6, 'Neo Traditional Tattoo Style', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:58:06', '2023-12-16 08:58:06'),
(144, 6, 'Fine  Line  Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:59:06', '2023-12-16 08:59:06'),
(145, 6, 'Tribal Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-16 08:59:37', '2023-12-16 08:59:37'),
(146, 6, 'Water color Tattoo Style', NULL, NULL, NULL, NULL, 1, '2023-12-16 09:00:24', '2023-12-16 09:00:24'),
(147, 6, 'Black Work Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-16 09:00:51', '2023-12-16 09:00:51'),
(148, 6, 'Realism Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-16 09:01:58', '2023-12-16 09:01:58'),
(149, 6, 'Japaness Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-16 09:02:41', '2023-12-16 09:02:41'),
(150, 6, 'Trash Polka Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-16 09:03:27', '2023-12-16 09:03:27'),
(151, 6, 'Geometric Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-16 09:04:08', '2023-12-16 09:04:08'),
(152, 6, 'Patch Work  Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-16 09:04:48', '2023-12-16 09:04:48'),
(153, 6, 'Black And Grey Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-16 09:05:29', '2023-12-16 09:05:29'),
(154, 6, 'Aesthetic Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-16 09:06:15', '2023-12-16 09:06:15'),
(155, 6, 'Ignorant Tattoo Style', NULL, NULL, NULL, NULL, 1, '2023-12-16 09:06:53', '2023-12-16 09:06:53'),
(156, 6, 'Anime Tattoo Style', NULL, NULL, NULL, NULL, 1, '2023-12-16 09:07:22', '2023-12-16 09:07:22'),
(157, 6, 'Small Tattoo ', NULL, NULL, NULL, NULL, 1, '2023-12-16 09:07:53', '2023-12-16 09:07:53'),
(158, 6, 'Micro Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-16 09:08:24', '2023-12-16 09:08:24'),
(159, 6, 'Abstract Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-16 09:08:44', '2023-12-16 09:08:44'),
(160, 6, '3D Tattoo Style', NULL, NULL, NULL, NULL, 1, '2023-12-16 09:09:12', '2023-12-16 09:09:12'),
(161, 6, 'Cartoon Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-16 09:09:35', '2023-12-16 09:09:35'),
(162, 6, 'Continous Line Contour Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-16 09:10:41', '2023-12-16 09:10:41'),
(163, 6, 'Portrait Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-16 09:11:20', '2023-12-16 09:11:20'),
(164, 6, 'Pet And Animal Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-16 09:11:47', '2023-12-16 09:11:47'),
(165, 6, 'Sketch Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-16 09:13:01', '2023-12-16 09:13:01'),
(166, 6, 'Temporary Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-18 04:52:35', '2023-12-18 04:52:35'),
(167, 6, 'White Ink Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-18 04:53:01', '2023-12-18 04:53:01'),
(168, 6, 'UV Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-18 04:53:20', '2023-12-18 04:53:20'),
(169, 6, 'Matching Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-18 04:53:50', '2023-12-18 04:53:50'),
(170, 6, 'Surreal Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-18 04:54:15', '2023-12-18 04:54:15'),
(171, 6, 'Script Or Lettering Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-18 04:54:58', '2023-12-18 04:54:58'),
(172, 6, 'Cover Up Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-18 04:55:46', '2023-12-18 04:55:46'),
(173, 6, 'Glow In The Dark Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-18 04:56:21', '2023-12-18 04:56:21'),
(174, 6, 'Ankle Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-18 04:56:42', '2023-12-18 04:56:42'),
(175, 6, 'Shoulder Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-18 04:57:09', '2023-12-18 04:57:09'),
(176, 6, 'Behind Ear Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-18 04:57:40', '2023-12-18 04:57:40'),
(177, 6, 'Side Body Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-18 04:57:59', '2023-12-18 04:57:59'),
(178, 6, 'Sleeve Tattoo', NULL, NULL, NULL, NULL, 1, '2023-12-18 04:58:28', '2023-12-18 04:58:28'),
(179, 7, 'Cardiovascular Training', NULL, NULL, NULL, NULL, 1, '2023-12-18 05:10:10', '2023-12-18 05:10:10'),
(180, 7, 'Time Management', NULL, NULL, NULL, NULL, 1, '2023-12-18 05:10:33', '2023-12-18 05:10:33'),
(181, 7, 'Weight Training', NULL, NULL, NULL, NULL, 1, '2023-12-18 05:11:18', '2023-12-18 05:11:18'),
(182, 7, 'Lift Training', NULL, NULL, NULL, NULL, 1, '2023-12-18 05:11:43', '2023-12-18 05:11:43'),
(183, 7, 'Body Building', NULL, NULL, NULL, NULL, 1, '2023-12-18 05:12:06', '2023-12-18 05:12:06'),
(184, 7, 'Cross Fit', NULL, NULL, NULL, NULL, 1, '2023-12-18 05:12:35', '2023-12-18 05:12:35'),
(185, 7, 'Fitness Training', NULL, NULL, NULL, NULL, 1, '2023-12-18 05:13:27', '2023-12-18 05:13:27'),
(186, 7, 'Group Training', NULL, NULL, NULL, NULL, 1, '2023-12-18 05:14:01', '2023-12-18 05:14:01'),
(187, 7, 'Muscle Building', NULL, NULL, NULL, NULL, 1, '2023-12-18 08:57:53', '2023-12-18 08:57:53'),
(188, 7, 'Nutrition Consulting', NULL, NULL, NULL, NULL, 1, '2023-12-18 08:58:21', '2023-12-18 08:58:21'),
(189, 7, 'Post  Rehab Training', NULL, NULL, NULL, NULL, 1, '2023-12-18 08:58:49', '2023-12-18 08:58:49'),
(190, 7, 'Youth Training', NULL, NULL, NULL, NULL, 1, '2023-12-18 08:59:18', '2023-12-18 08:59:18'),
(191, 7, 'Sports Specific Training', NULL, NULL, NULL, NULL, 1, '2023-12-18 09:00:22', '2023-12-18 09:00:22'),
(192, 7, 'Senior citizens Fitnes Training', NULL, NULL, NULL, NULL, 1, '2023-12-19 04:44:58', '2023-12-19 04:44:58'),
(193, 7, 'HIIT Exercise Training', NULL, NULL, NULL, NULL, 1, '2023-12-19 04:48:02', '2023-12-19 04:48:02'),
(194, 7, 'EMS Personal Training Sessions', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:03:42', '2023-12-19 05:03:42'),
(195, 7, 'Kettle Bells Training', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:04:36', '2023-12-19 05:04:36'),
(196, 7, 'Functional Fitness Routine', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:05:08', '2023-12-19 05:05:08'),
(197, 7, 'Bare Work Out', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:05:35', '2023-12-19 05:05:35'),
(198, 7, 'Rowing ', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:05:52', '2023-12-19 05:05:52'),
(199, 7, 'Spin Cycling', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:06:23', '2023-12-19 05:06:23'),
(200, 7, 'TRX Suspension Training', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:07:02', '2023-12-19 05:07:02'),
(201, 7, 'High Intensity Interval Training', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:08:58', '2023-12-19 05:08:58'),
(202, 7, 'Pilates Training', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:09:28', '2023-12-19 05:09:28'),
(203, 8, 'Group Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:10:31', '2023-12-19 05:10:31'),
(204, 8, 'Couple Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:10:55', '2023-12-19 05:10:55'),
(205, 8, 'Family Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:11:15', '2023-12-19 05:11:15'),
(206, 8, 'Counseling For Children And Adolescents', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:12:16', '2023-12-19 05:12:16'),
(207, 8, 'Substance  Abuse Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:13:12', '2023-12-19 05:13:12'),
(208, 8, 'psychiatric services', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:14:35', '2023-12-19 05:14:35'),
(209, 8, 'Specialized Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:15:29', '2023-12-19 05:15:29'),
(210, 8, 'Teletherapy', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:19:36', '2023-12-19 05:19:36'),
(211, 8, 'Wellness And Self Care Work Shop', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:20:43', '2023-12-19 05:20:43'),
(212, 8, 'Wellness And Self Care Work Shop', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:20:51', '2023-12-19 05:20:51'),
(213, 8, 'Eye Movement Desensitization And Reprocessing Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:25:11', '2023-12-19 05:25:11'),
(214, 8, 'Interpersonal Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:37:47', '2023-12-19 05:37:47'),
(215, 8, 'Mentalization Based Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:38:37', '2023-12-19 05:38:55'),
(216, 8, 'Psychodynamic Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:39:56', '2023-12-19 05:39:56'),
(217, 8, 'Animal Assisted Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:40:57', '2023-12-19 05:40:57'),
(218, 8, 'Emotional Focused Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:41:34', '2023-12-19 05:41:34'),
(219, 8, 'Family Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:41:59', '2023-12-19 05:41:59'),
(220, 8, 'Group Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:42:34', '2023-12-19 05:42:34'),
(221, 8, 'Mind Fulness Based Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:43:16', '2023-12-19 05:43:16'),
(222, 8, 'Creative Arts Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:43:55', '2023-12-19 05:43:55'),
(223, 8, 'Stress Relief ', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:45:12', '2023-12-19 05:45:12'),
(224, 8, 'Pain Relief', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:45:33', '2023-12-19 05:45:33'),
(225, 8, 'Back And Leg Relief', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:46:20', '2023-12-19 05:46:20'),
(226, 8, 'Occupational Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-19 05:46:46', '2023-12-19 05:46:46'),
(227, 8, 'Pediatric  Physiotherapy', NULL, NULL, NULL, NULL, 1, '2023-12-19 08:19:31', '2023-12-19 08:19:31'),
(228, 8, 'Special Education', NULL, NULL, NULL, NULL, 1, '2023-12-19 08:20:03', '2023-12-19 08:20:03'),
(229, 8, 'Sensory Integration Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-19 08:21:19', '2023-12-19 08:21:19'),
(230, 8, 'Neuro Development Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-19 08:22:00', '2023-12-19 08:22:00'),
(231, 8, 'speech and language therapy', NULL, NULL, NULL, NULL, 1, '2023-12-19 08:23:24', '2023-12-19 08:23:24'),
(232, 8, 'Acupuncture', NULL, NULL, NULL, NULL, 1, '2023-12-19 08:24:18', '2023-12-19 08:24:18'),
(233, 8, 'sujok therapy', NULL, NULL, NULL, NULL, 1, '2023-12-19 08:25:38', '2023-12-19 08:25:38'),
(234, 8, 'hijama (cupping Therapy) ', NULL, NULL, NULL, NULL, 1, '2023-12-19 08:29:19', '2023-12-19 08:29:19'),
(235, 8, 'Behavioral Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-19 08:30:32', '2023-12-19 08:30:32'),
(236, 8, 'physiotherapy', NULL, NULL, NULL, NULL, 1, '2023-12-19 08:31:48', '2023-12-19 08:31:48'),
(237, 8, 'Anxiety Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-20 04:33:35', '2023-12-20 04:33:35'),
(238, 8, 'Corporate Psychology Services', NULL, NULL, NULL, NULL, 1, '2023-12-20 04:36:24', '2023-12-20 04:36:24'),
(239, 8, 'marriage Counselling', NULL, NULL, NULL, NULL, 1, '2023-12-20 04:37:51', '2023-12-20 04:37:51'),
(240, 7, 'PTSD/Trauma Service', NULL, NULL, NULL, NULL, 1, '2023-12-20 04:40:51', '2023-12-20 04:40:51'),
(241, 8, 'Relationship Issue Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-20 04:41:43', '2023-12-20 04:41:43'),
(242, 8, 'Hypnosis Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-20 04:42:53', '2023-12-20 04:42:53'),
(243, 8, 'Therapy Foot And Ankle Pain', NULL, NULL, NULL, NULL, 1, '2023-12-20 04:43:44', '2023-12-20 04:43:44'),
(244, 8, 'Geriatric Physiotherapy', NULL, NULL, NULL, NULL, 1, '2023-12-20 04:44:21', '2023-12-20 04:44:21'),
(245, 8, 'Pediatric Physiotherapy', NULL, NULL, NULL, NULL, 1, '2023-12-20 04:45:03', '2023-12-20 04:45:03'),
(246, 8, 'Therapy For Shoulder Pain', NULL, NULL, NULL, NULL, 1, '2023-12-20 04:46:20', '2023-12-20 04:46:20'),
(247, 8, 'Therapy Spain', NULL, NULL, NULL, NULL, 1, '2023-12-20 04:46:47', '2023-12-20 04:46:47'),
(248, 8, 'Vestibular Rehabilitation ', NULL, NULL, NULL, NULL, 1, '2023-12-20 04:48:37', '2023-12-20 04:48:37'),
(249, 8, 'Cardiopulmonary Rehab Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-20 04:49:21', '2023-12-20 04:49:21'),
(250, 8, 'Geriatric Rehab Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-20 04:50:25', '2023-12-20 04:50:25'),
(251, 9, 'Gel Nails', NULL, NULL, NULL, NULL, 1, '2023-12-20 04:51:49', '2023-12-20 04:51:49'),
(252, 9, 'Acrylic Nails ', NULL, NULL, NULL, NULL, 1, '2023-12-20 04:52:21', '2023-12-20 04:52:21'),
(253, 9, 'Nail Art', NULL, NULL, NULL, NULL, 1, '2023-12-20 04:52:45', '2023-12-20 04:52:45'),
(254, 8, 'Nail Extension', NULL, NULL, NULL, NULL, 1, '2023-12-20 04:53:08', '2023-12-20 04:53:08'),
(255, 9, 'Nail Repairs', NULL, NULL, NULL, NULL, 1, '2023-12-20 04:53:38', '2023-12-20 04:53:38'),
(256, 9, 'Nail Spa Service', NULL, NULL, NULL, NULL, 1, '2023-12-20 04:54:00', '2023-12-20 04:54:00'),
(257, 9, 'French Manicure', NULL, NULL, NULL, NULL, 1, '2023-12-20 04:54:26', '2023-12-20 04:54:26'),
(258, 9, 'Nail Removal', NULL, NULL, NULL, NULL, 1, '2023-12-20 04:55:01', '2023-12-20 04:55:01'),
(259, 9, 'Artificial Nail', NULL, NULL, NULL, NULL, 1, '2023-12-20 04:56:20', '2023-12-20 04:56:20'),
(260, 9, 'UV Gel Overlays And Extension', NULL, NULL, NULL, NULL, 1, '2023-12-20 04:57:07', '2023-12-20 04:57:07'),
(261, 9, 'Dip Powder  Nail ', NULL, NULL, NULL, NULL, 1, '2023-12-20 04:57:50', '2023-12-20 04:57:50'),
(262, 9, 'Silk /Fiberglass Overlays And extension', NULL, NULL, NULL, NULL, 1, '2023-12-20 04:59:49', '2023-12-20 04:59:49'),
(263, 9, 'Nails Treatment ', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:01:08', '2023-12-20 05:01:08'),
(264, 9, 'Nail Polish ', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:01:27', '2023-12-20 05:01:27'),
(265, 9, 'Nail Polish ', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:01:32', '2023-12-20 05:01:32'),
(266, 9, 'Chrome Nails', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:04:32', '2023-12-20 05:04:32'),
(267, 9, 'Callus Removal', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:04:56', '2023-12-20 05:04:56'),
(268, 9, 'Synthetic Nail Removal', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:05:56', '2023-12-20 05:05:56'),
(269, 9, 'Nail Glitter Works', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:07:36', '2023-12-20 05:07:36'),
(270, 9, 'Nail Enhancement', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:08:44', '2023-12-20 05:08:44'),
(271, 9, 'Nail Polish Removal', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:09:29', '2023-12-20 05:09:29'),
(272, 9, 'Regular Nail Polish Removal', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:09:59', '2023-12-20 05:09:59'),
(273, 9, 'Over Lay With Gel Nail  Polish ', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:10:50', '2023-12-20 05:10:50'),
(274, 9, 'Over Lay Natural Nail Without Polish', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:11:39', '2023-12-20 05:11:39'),
(275, 9, 'Regular Nail Art', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:12:08', '2023-12-20 05:12:08'),
(276, 9, 'Chrome Nail Mirror Finish', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:12:41', '2023-12-20 05:12:41'),
(277, 9, 'Foil Stickers And  Strip Nails', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:14:10', '2023-12-20 05:14:10'),
(278, 9, 'In Built Decor', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:14:45', '2023-12-20 05:14:45'),
(279, 9, 'French Nail Toe Set Up', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:15:36', '2023-12-20 05:15:36'),
(280, 9, 'Glitter Gel Polish Set', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:16:34', '2023-12-20 05:16:34'),
(281, 9, 'Dry Glitter', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:17:11', '2023-12-20 05:17:11'),
(282, 9, 'Wet Manicure', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:17:40', '2023-12-20 05:17:40'),
(283, 9, 'Wet Pedicure', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:18:08', '2023-12-20 05:18:08'),
(284, 9, 'Nail Buffering', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:18:26', '2023-12-20 05:18:26'),
(285, 9, 'Nail  Strengthening Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:19:21', '2023-12-20 05:19:21'),
(286, 9, 'Nail health Consultation', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:21:13', '2023-12-20 05:21:13'),
(287, 9, 'Nail Spa Package', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:21:50', '2023-12-20 05:21:50'),
(288, 9, 'Paraffin Wax', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:22:23', '2023-12-20 05:22:23'),
(289, 9, 'shellac Or Gel Polish', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:23:55', '2023-12-20 05:23:55'),
(290, 10, 'Threading', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:25:29', '2023-12-20 05:25:29'),
(291, 10, 'Tweezing', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:25:56', '2023-12-20 05:25:56'),
(292, 10, 'Tinting', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:26:28', '2023-12-20 05:26:28'),
(293, 10, 'Microblading', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:26:54', '2023-12-20 05:26:54'),
(294, 10, 'Eye Brow Filling', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:27:19', '2023-12-20 05:27:19'),
(295, 10, 'Eye Lash Extension', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:27:53', '2023-12-20 05:27:53'),
(296, 10, 'Permanent Extension', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:29:35', '2023-12-20 05:29:35'),
(297, 10, 'Temporary Extensions', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:31:00', '2023-12-20 05:31:00'),
(298, 10, 'Eye Lash Removal', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:31:41', '2023-12-20 05:31:41'),
(299, 10, 'Eye Lash Curling', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:32:04', '2023-12-20 05:32:04'),
(300, 10, 'Eye  Brow Henna ', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:32:32', '2023-12-20 05:32:32'),
(301, 10, 'Eye Brow Lamination', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:33:00', '2023-12-20 05:33:00'),
(302, 10, 'Tail Arch Brow', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:33:25', '2023-12-20 05:33:25'),
(303, 10, 'Center Arch Brow', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:34:02', '2023-12-20 05:34:02'),
(304, 10, 'High Arch Brows', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:34:29', '2023-12-20 05:34:29'),
(305, 10, 'Minimal Arch Brow', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:34:52', '2023-12-20 05:34:52'),
(306, 10, 'Straight  Brows', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:35:56', '2023-12-20 05:35:56'),
(307, 10, 'Tapered Brows', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:37:17', '2023-12-20 05:37:17'),
(308, 10, 'Round Brows', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:38:05', '2023-12-20 05:38:05'),
(309, 10, 'Short And Tick Brows', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:38:42', '2023-12-20 05:38:42'),
(310, 10, 'Sharped Brows', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:39:08', '2023-12-20 05:39:08'),
(311, 11, 'Caloric Tracking', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:40:38', '2023-12-20 05:40:38'),
(312, 11, 'Intermittent Fasting', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:42:58', '2023-12-20 05:42:58'),
(313, 11, 'Regular Exercise', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:43:36', '2023-12-20 05:43:36'),
(314, 11, 'Low Carb Or Low Fact Diets', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:44:17', '2023-12-20 05:44:17'),
(315, 11, 'Sleep And Stress Management', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:45:01', '2023-12-20 05:45:01'),
(316, 11, 'Zumba ', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:45:38', '2023-12-20 05:45:38'),
(317, 11, 'Martial Arts', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:45:58', '2023-12-20 05:45:58'),
(318, 11, 'Custom Tailored Diet Plan', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:46:37', '2023-12-20 05:46:37'),
(319, 11, 'Free Form Refined Sugar And Trans Fat ', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:49:25', '2023-12-20 05:49:25'),
(320, 11, 'Flexibility Of Plan', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:49:57', '2023-12-20 05:49:57'),
(321, 11, 'Ongoing Nutritionist Support', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:51:42', '2023-12-20 05:51:42'),
(322, 11, 'Low Crab Diet Plan', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:52:16', '2023-12-20 05:52:16'),
(323, 11, 'Atkins Diet', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:52:44', '2023-12-20 05:52:44'),
(324, 11, 'Keto Diet', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:53:07', '2023-12-20 05:53:07'),
(325, 11, 'High Fact', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:53:34', '2023-12-20 05:53:34'),
(326, 11, 'Pilates', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:54:01', '2023-12-20 05:54:01'),
(327, 11, 'Hiking ', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:54:22', '2023-12-20 05:54:22'),
(328, 11, 'Circuit ', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:54:48', '2023-12-20 05:54:48'),
(329, 11, 'Walking', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:55:07', '2023-12-20 05:55:07'),
(330, 12, 'Oxygen Facial', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:55:54', '2023-12-20 05:55:54'),
(331, 12, 'Derma Planning', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:57:06', '2023-12-20 05:57:06'),
(332, 12, 'Bitrontix Facial(Face Lifting)', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:58:47', '2023-12-20 05:58:47'),
(333, 12, 'Skin Anatomy', NULL, NULL, NULL, NULL, 1, '2023-12-20 05:59:22', '2023-12-20 05:59:22'),
(334, 12, 'CTM Routine', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:00:27', '2023-12-20 06:00:27'),
(335, 12, 'Client Consulation', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:02:41', '2023-12-20 06:02:41'),
(336, 12, 'Medi Facial ', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:03:02', '2023-12-20 06:03:02'),
(337, 12, 'Leo Light Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:03:27', '2023-12-20 06:03:27'),
(338, 12, 'Glutathione ', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:03:49', '2023-12-20 06:03:49'),
(339, 12, 'Silk Peel Per/Infation', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:05:53', '2023-12-20 06:05:53'),
(340, 12, 'pink Estrogen peel', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:07:29', '2023-12-20 06:07:29'),
(341, 12, 'Pico Care Majesty', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:09:40', '2023-12-20 06:09:40'),
(342, 12, 'Fairness Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:10:40', '2023-12-20 06:10:40'),
(343, 12, 'Radiance Facial ', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:11:15', '2023-12-20 06:11:15'),
(344, 12, 'Skin Booster Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:11:54', '2023-12-20 06:11:54'),
(345, 12, 'Wrinkle Reduction ', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:12:32', '2023-12-20 06:12:32'),
(346, 12, 'Foton Laser Micro Peel', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:13:19', '2023-12-20 06:13:19'),
(347, 12, 'Pico Majesty', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:13:59', '2023-12-20 06:13:59'),
(348, 12, 'Cool Sculpting', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:14:34', '2023-12-20 06:14:34'),
(349, 12, 'Laser Skin Resurfacing', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:16:30', '2023-12-20 06:16:30'),
(350, 12, 'Liposuction', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:18:13', '2023-12-20 06:18:13'),
(351, 12, 'Rhinoplasty', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:19:46', '2023-12-20 06:19:46'),
(352, 12, 'Tummy Tuck (Abdominoplasty)', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:21:54', '2023-12-20 06:21:54'),
(353, 12, 'Face Lift ( Rhytidectomy)', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:35:42', '2023-12-20 06:35:42'),
(354, 12, 'Eye Lied  Surgery (Blepharoplasty)', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:37:43', '2023-12-20 06:37:43'),
(355, 12, 'Thread Lift', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:38:18', '2023-12-20 06:38:18'),
(356, 11, 'Dermal Fillers', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:38:43', '2023-12-20 06:38:43'),
(357, 13, 'Protein Control', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:40:16', '2023-12-20 06:40:16'),
(358, 13, 'Daily Diet Control', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:40:58', '2023-12-20 06:40:58'),
(359, 13, 'Time Management ', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:42:37', '2023-12-20 06:42:37'),
(360, 13, 'Exercise Evaluation', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:43:27', '2023-12-20 06:43:27'),
(361, 13, 'Supervision Of Training', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:44:00', '2023-12-20 06:44:00'),
(362, 13, 'Parental And postpartum Training', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:44:44', '2023-12-20 06:44:44'),
(363, 13, 'Weight Loss Consulting And Training', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:46:22', '2023-12-20 06:46:22'),
(364, 13, 'Online Fitness', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:47:12', '2023-12-20 06:47:12'),
(365, 13, 'Online Work Out Center', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:48:03', '2023-12-20 06:48:03'),
(366, 13, 'Kids Summer Camp', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:49:16', '2023-12-20 06:49:16'),
(367, 13, 'Circuit Training', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:50:24', '2023-12-20 06:50:24'),
(368, 13, 'Body Toning', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:50:56', '2023-12-20 06:50:56'),
(369, 13, 'Flexibility Training', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:54:18', '2023-12-20 06:54:18'),
(370, 13, 'Body Building', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:55:00', '2023-12-20 06:55:00'),
(371, 13, 'HIIT Exercise Classes', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:55:35', '2023-12-20 06:55:35'),
(372, 13, 'Zumba Classes', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:55:56', '2023-12-20 06:55:56'),
(373, 13, 'Yoga Classes ', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:56:21', '2023-12-20 06:56:21'),
(374, 13, 'Aerobics', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:56:41', '2023-12-20 06:56:41'),
(375, 13, 'Kick Boxing', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:57:07', '2023-12-20 06:57:07'),
(376, 13, 'Youth Sports ', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:57:36', '2023-12-20 06:57:36'),
(377, 13, 'Child Care', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:57:56', '2023-12-20 06:57:56'),
(378, 13, 'Adult Sports', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:58:25', '2023-12-20 06:58:25'),
(379, 13, 'Aquatics', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:58:58', '2023-12-20 06:58:58'),
(380, 13, 'Cycling', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:59:12', '2023-12-20 06:59:12'),
(381, 13, 'Dance Fitness', NULL, NULL, NULL, NULL, 1, '2023-12-20 06:59:39', '2023-12-20 06:59:39'),
(382, 13, 'Injury Prevention', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:00:25', '2023-12-20 07:00:25'),
(383, 13, 'Motivation And Support', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:00:50', '2023-12-20 07:00:50'),
(384, 13, 'customized workout plan', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:02:02', '2023-12-20 07:02:02'),
(385, 13, 'Fitness Assessment', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:03:08', '2023-12-20 07:03:08'),
(386, 14, 'Fade Hair Cut ', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:07:12', '2023-12-20 07:07:12'),
(387, 14, 'Bread Grooming', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:07:55', '2023-12-20 07:07:55'),
(388, 14, 'Bread Trim', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:09:23', '2023-12-20 07:09:23'),
(389, 14, 'Straight Razor Shave', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:10:44', '2023-12-20 07:10:44'),
(390, 14, 'Color Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:11:09', '2023-12-20 07:11:09'),
(391, 14, 'Kids Hair Cut ', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:12:03', '2023-12-20 07:12:03'),
(392, 14, 'Hair Cut Consultation', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:13:02', '2023-12-20 07:13:02'),
(393, 14, 'Scalp Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:13:28', '2023-12-20 07:13:28'),
(394, 14, 'Mobile Hair Salon ', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:13:54', '2023-12-20 07:13:54'),
(395, 14, 'Special Occlusion', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:15:37', '2023-12-20 07:15:37'),
(396, 14, 'Rebond conditioner', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:17:01', '2023-12-20 07:17:01'),
(397, 14, 'Rebond Shining', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:17:39', '2023-12-20 07:17:39'),
(398, 14, 'Beard Trim', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:20:20', '2023-12-20 07:20:20'),
(399, 14, 'Bazz Cut ', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:20:44', '2023-12-20 07:20:44'),
(400, 14, 'Scissor Cut', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:21:14', '2023-12-20 07:21:14'),
(401, 14, 'Hair Cut', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:22:03', '2023-12-20 07:22:03'),
(402, 14, 'Hair Shape Cut ', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:23:07', '2023-12-20 07:23:07'),
(403, 14, 'Pixie Cut', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:23:44', '2023-12-20 07:23:44'),
(404, 14, 'Mohawk Hair Style', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:24:18', '2023-12-20 07:24:18'),
(405, 14, 'Crew Cut ', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:24:43', '2023-12-20 07:24:43'),
(406, 14, 'Comb Over', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:25:00', '2023-12-20 07:25:00'),
(407, 14, 'Caser Cut', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:25:33', '2023-12-20 07:25:33'),
(408, 14, 'Regular Hair Cut', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:25:53', '2023-12-20 07:25:53'),
(409, 14, 'Hi Top Face Hair Cut', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:26:29', '2023-12-20 07:26:29'),
(410, 14, 'Quiff Hair Cut', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:29:20', '2023-12-20 07:29:20'),
(411, 14, 'Under Cut', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:29:41', '2023-12-20 07:29:41'),
(412, 14, 'Bangs', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:30:05', '2023-12-20 07:30:05'),
(413, 14, 'Mullet', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:30:42', '2023-12-20 07:30:42'),
(414, 14, 'Temple Fade', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:31:18', '2023-12-20 07:31:18'),
(415, 14, 'Dreadlocks', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:32:25', '2023-12-20 07:32:25'),
(416, 14, 'Bowl Cut', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:32:56', '2023-12-20 07:32:56'),
(417, 14, 'Fauxhawk Hair Cut', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:33:45', '2023-12-20 07:33:45'),
(418, 14, 'Bomb Cut', NULL, NULL, NULL, NULL, 1, '2023-12-20 07:34:09', '2023-12-20 07:34:09'),
(419, 15, 'Swedish Massage', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:28:43', '2023-12-20 08:28:43'),
(420, 15, 'Balinese', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:29:18', '2023-12-20 08:29:18'),
(421, 15, 'Deep Tissue', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:29:54', '2023-12-20 08:29:54'),
(422, 15, 'Aroma Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:30:25', '2023-12-20 08:30:25'),
(423, 15, 'Ayurvedic Massage', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:30:50', '2023-12-20 08:30:50'),
(424, 15, 'Couple Massage', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:31:12', '2023-12-20 08:31:12'),
(425, 15, 'Waxing And  Hair Removal', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:31:51', '2023-12-20 08:31:51'),
(426, 15, 'Spa Manicure', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:32:39', '2023-12-20 08:32:39'),
(427, 15, 'Spa Pedicure', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:33:03', '2023-12-20 08:33:03'),
(428, 15, 'Hair Care Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:33:45', '2023-12-20 08:33:45'),
(429, 15, 'Cryotherapy And  Cold Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:34:26', '2023-12-20 08:34:26'),
(430, 15, 'Facial And Face Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:34:59', '2023-12-20 08:34:59'),
(431, 15, 'Body Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:35:47', '2023-12-20 08:35:47'),
(432, 15, 'Oil Massage', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:36:07', '2023-12-20 08:36:07'),
(433, 15, 'Cream Massage', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:36:26', '2023-12-20 08:36:26'),
(434, 15, 'Thai Massage', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:36:44', '2023-12-20 08:36:44'),
(435, 15, 'Glow Facial', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:37:12', '2023-12-20 08:37:12'),
(436, 15, 'sampoorna abhyanga swedana', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:38:53', '2023-12-20 08:38:53'),
(437, 15, 'Massage With Kizhi', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:39:36', '2023-12-20 08:39:36'),
(438, 15, 'Massage With Shirodhara', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:41:30', '2023-12-20 08:41:30'),
(439, 15, 'Maha Karma Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:42:07', '2023-12-20 08:42:07'),
(440, 15, 'Children Massage', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:42:33', '2023-12-20 08:42:33'),
(441, 15, 'Super Sweden', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:42:52', '2023-12-20 08:42:52'),
(442, 15, 'Sports Special', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:43:24', '2023-12-20 08:43:24'),
(443, 15, 'Soul Of Oricut', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:43:59', '2023-12-20 08:43:59'),
(444, 15, 'Balines Ballad', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:44:55', '2023-12-20 08:44:55'),
(445, 15, 'Thai Way', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:45:22', '2023-12-20 08:45:22'),
(446, 15, 'Stress Solace', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:45:57', '2023-12-20 08:45:57'),
(447, 15, 'Delight  Ful Duo', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:46:40', '2023-12-20 08:46:40'),
(448, 15, 'Body Scrub And Wrap', NULL, NULL, NULL, NULL, 1, '2023-12-20 08:47:59', '2023-12-20 08:47:59'),
(449, 15, 'Signature Sense', NULL, NULL, NULL, NULL, 1, '2023-12-20 09:39:51', '2023-12-20 09:39:51'),
(450, 15, 'Foot Reboot', NULL, NULL, NULL, NULL, 1, '2023-12-20 09:40:09', '2023-12-20 09:40:09'),
(451, 15, 'Mud Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-20 09:40:35', '2023-12-20 09:40:35'),
(452, 15, 'Foot Reflexology', NULL, NULL, NULL, NULL, 1, '2023-12-20 09:41:47', '2023-12-20 09:41:47'),
(453, 15, 'Shoulder And Neck Massager', NULL, NULL, NULL, NULL, 1, '2023-12-20 09:43:29', '2023-12-20 09:43:29'),
(454, 15, 'Body Wrap', NULL, NULL, NULL, NULL, 1, '2023-12-20 09:43:55', '2023-12-20 09:43:55'),
(455, 15, 'Body Scrub', NULL, NULL, NULL, NULL, 1, '2023-12-20 09:44:12', '2023-12-20 09:44:12'),
(456, 15, 'Hydro Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-20 09:44:31', '2023-12-20 09:44:31'),
(457, 15, 'Floatation Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-20 09:46:07', '2023-12-20 09:46:07'),
(458, 15, 'Holistic Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-20 09:46:31', '2023-12-20 09:51:51'),
(459, 15, 'Fitness And Wellness Program', NULL, NULL, NULL, NULL, 1, '2023-12-20 09:47:05', '2023-12-20 09:47:05'),
(460, 15, 'Yoga And Meditation Classes', NULL, NULL, NULL, NULL, 1, '2023-12-20 09:48:44', '2023-12-20 09:48:44'),
(461, 15, 'Cellulitis Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-20 09:49:49', '2023-12-20 09:49:49'),
(462, 15, 'Sugaring', NULL, NULL, NULL, NULL, 1, '2023-12-20 09:50:15', '2023-12-20 09:50:15'),
(463, 15, 'Hydro Therapy Bath', NULL, NULL, NULL, NULL, 1, '2023-12-20 09:51:04', '2023-12-20 09:51:28'),
(464, 15, 'Reiki', NULL, NULL, NULL, NULL, 1, '2023-12-20 09:52:26', '2023-12-20 09:52:26'),
(465, 16, 'Uni Sex Massage', NULL, NULL, NULL, NULL, 1, '2023-12-20 09:53:13', '2023-12-20 09:53:13'),
(466, 16, 'Body Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-20 09:54:24', '2023-12-20 09:54:24'),
(467, 16, 'Nail Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-20 09:55:15', '2023-12-20 09:55:15'),
(468, 16, 'Foot  Massage ', NULL, NULL, NULL, NULL, 1, '2023-12-20 09:56:04', '2023-12-20 09:56:04'),
(469, 16, 'Deep Tissue Massage', NULL, NULL, NULL, NULL, 1, '2023-12-20 09:56:48', '2023-12-20 09:56:48'),
(470, 16, 'Aroma Massage', NULL, NULL, NULL, NULL, 1, '2023-12-20 09:57:11', '2023-12-20 09:57:11'),
(471, 16, 'Bali Soul Signature Monument  Massage', NULL, NULL, NULL, NULL, 1, '2023-12-20 09:58:53', '2023-12-20 09:58:53'),
(472, 16, 'Bali Euphoria Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:02:45', '2023-12-20 10:02:45'),
(473, 16, 'Stress Delight Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:03:24', '2023-12-20 10:03:24'),
(474, 16, 'Herbal Dhara', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:04:24', '2023-12-20 10:04:24'),
(475, 16, 'Herbal Massage', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:04:43', '2023-12-20 10:04:43'),
(476, 16, 'Steam Service ', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:05:13', '2023-12-20 10:05:13'),
(477, 16, 'Thai Tranquility Massage', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:06:40', '2023-12-20 10:06:40'),
(478, 16, 'abhyanga Massage', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:10:04', '2023-12-20 10:10:04'),
(479, 16, 'Ayurvedic Facial', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:10:34', '2023-12-20 10:10:34'),
(480, 16, 'Ela Kizhi', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:11:01', '2023-12-20 10:11:01'),
(481, 16, 'Herbal Kizhi', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:11:46', '2023-12-20 10:11:46'),
(482, 16, 'Janu vasti treatment', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:13:22', '2023-12-20 10:13:22'),
(483, 16, 'Lech Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:15:54', '2023-12-20 10:15:54'),
(484, 16, 'Shirodhara Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:17:12', '2023-12-20 10:17:12'),
(485, 16, 'Udwarthanam', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:18:41', '2023-12-20 10:18:41'),
(486, 16, 'Stone Massage', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:19:16', '2023-12-20 10:19:16'),
(487, 16, 'Shiatsu', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:20:03', '2023-12-20 10:20:03'),
(488, 17, 'Parental Massage', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:20:21', '2023-12-20 10:20:21');
INSERT INTO `services` (`id`, `cate_id`, `name`, `cover`, `descriptions`, `images`, `extra_field`, `status`, `created_at`, `updated_at`) VALUES
(489, 16, 'Chair Massage', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:20:38', '2023-12-20 10:20:38'),
(490, 16, 'Sports Massage', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:21:02', '2023-12-20 10:21:02'),
(491, 17, 'Laser Hair Reduction', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:22:32', '2023-12-20 10:22:32'),
(492, 17, 'Anti Aging Service', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:23:16', '2023-12-20 10:23:16'),
(493, 17, 'Chemical Peel', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:23:48', '2023-12-20 10:23:48'),
(494, 17, 'Botox And Fillers', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:24:30', '2023-12-20 10:24:30'),
(495, 17, 'Tattoo Removal', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:24:52', '2023-12-20 10:24:52'),
(496, 17, 'Vaginal Tightening', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:26:42', '2023-12-20 10:26:42'),
(497, 17, 'Breast Up Lift', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:27:42', '2023-12-20 10:27:42'),
(498, 17, 'Hair Transplant', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:28:09', '2023-12-20 10:28:09'),
(499, 17, 'Laser  Face Rejuvenation', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:29:32', '2023-12-20 10:29:32'),
(500, 17, 'Laser Carbon Peel', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:30:11', '2023-12-20 10:30:11'),
(501, 17, 'Laser Toning', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:30:38', '2023-12-20 10:30:38'),
(502, 17, 'Laser Acne Reduction', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:31:05', '2023-12-20 10:31:05'),
(503, 17, 'Laser Birth Mark Removal', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:31:53', '2023-12-20 10:31:53'),
(504, 17, 'Curettage Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:33:17', '2023-12-20 10:33:17'),
(505, 17, 'Anti Aging Solution', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:34:02', '2023-12-20 10:34:02'),
(506, 17, 'Cool Sculpting Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-20 10:34:44', '2023-12-20 10:34:44'),
(507, 17, 'Dermal Fillers', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:34:02', '2023-12-21 04:34:02'),
(508, 17, 'Mesotherapy', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:34:52', '2023-12-21 04:34:52'),
(509, 17, 'Bread And Moustache Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:35:35', '2023-12-21 04:35:35'),
(510, 17, 'Breast Augmentation', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:36:26', '2023-12-21 04:36:26'),
(511, 17, 'Chin Implant', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:36:52', '2023-12-21 04:36:52'),
(512, 17, 'Dark Circle Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:37:22', '2023-12-21 04:37:22'),
(513, 17, 'Face Lift Treatment Without Surgery', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:37:47', '2023-12-21 04:39:34'),
(514, 17, 'Liposuction Surgery', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:40:43', '2023-12-21 04:40:43'),
(515, 17, 'Micro Needling ', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:41:30', '2023-12-21 04:41:30'),
(516, 17, 'Micro Needling And Radio Frequency', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:42:54', '2023-12-21 04:42:54'),
(517, 17, 'Mole Removal', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:43:19', '2023-12-21 04:43:19'),
(518, 17, 'Skin Tan Removal Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:44:06', '2023-12-21 04:44:06'),
(519, 17, 'Sun Damage Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:44:29', '2023-12-21 04:44:29'),
(520, 17, 'Surgery Liposuction', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:44:59', '2023-12-21 04:44:59'),
(521, 17, 'Ultra Violet Blu Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:45:38', '2023-12-21 04:45:38'),
(522, 17, 'Water  And Skin Tag', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:46:15', '2023-12-21 04:46:15'),
(523, 17, 'Wart Removal  Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:47:07', '2023-12-21 04:47:07'),
(524, 17, 'Psoriasis Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:49:41', '2023-12-21 04:49:41'),
(525, 17, 'Vitiligo', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:52:49', '2023-12-21 04:52:49'),
(526, 17, 'Skin Infections', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:53:20', '2023-12-21 04:53:20'),
(527, 17, 'Freckle Removal ', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:55:08', '2023-12-21 04:55:08'),
(528, 17, 'Cone Removal', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:55:33', '2023-12-21 04:55:33'),
(529, 17, 'Rhinoplasty', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:56:19', '2023-12-21 04:56:19'),
(530, 17, 'Dimple Creation', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:56:40', '2023-12-21 04:56:40'),
(531, 17, 'Lip Reduction', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:57:07', '2023-12-21 04:57:07'),
(532, 17, 'Earlobe Repair', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:58:26', '2023-12-21 04:58:26'),
(533, 17, 'Far Grafting', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:59:08', '2023-12-21 04:59:08'),
(534, 17, 'Direct Hair Implantation', NULL, NULL, NULL, NULL, 1, '2023-12-21 04:59:46', '2023-12-21 04:59:46'),
(535, 17, 'Eye Brow Transplant', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:00:12', '2023-12-21 05:00:12'),
(536, 17, 'Facial Hair Transplant', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:00:39', '2023-12-21 05:00:39'),
(537, 17, 'Face Hair Transplant', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:06:23', '2023-12-21 05:06:23'),
(538, 17, 'Hair Grafting', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:06:55', '2023-12-21 05:06:55'),
(539, 17, 'Hair Grafting', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:06:56', '2023-12-21 05:06:56'),
(540, 17, 'Micro Pigmentation', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:07:34', '2023-12-21 05:07:34'),
(541, 17, 'RRP Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:09:29', '2023-12-21 05:09:29'),
(542, 18, 'Extractions', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:22:27', '2023-12-21 05:22:27'),
(543, 18, 'Veneers Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:23:53', '2023-12-21 05:23:53'),
(544, 18, 'Filling', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:24:14', '2023-12-21 05:24:14'),
(545, 18, 'Crows', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:24:38', '2023-12-21 05:24:38'),
(546, 18, 'Root Canal', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:24:59', '2023-12-21 05:24:59'),
(547, 18, 'Braces/Invisalign', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:25:40', '2023-12-21 05:25:40'),
(548, 18, 'Bonding', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:25:59', '2023-12-21 05:25:59'),
(549, 18, 'Tooth Removal', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:26:35', '2023-12-21 05:26:35'),
(550, 18, 'Dental Implantations', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:27:07', '2023-12-21 05:27:07'),
(551, 18, 'Dentures Or False Teeth', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:27:54', '2023-12-21 05:27:54'),
(552, 18, 'Laser Dentistry', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:34:19', '2023-12-21 05:34:19'),
(553, 18, 'Teeth Reshaping', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:35:08', '2023-12-21 05:35:08'),
(554, 18, 'Teeth X Ray', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:35:36', '2023-12-21 05:35:36'),
(555, 18, 'Pediatric Dental Care', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:36:16', '2023-12-21 05:36:16'),
(556, 18, 'Oral Surgery', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:36:40', '2023-12-21 05:36:40'),
(557, 18, 'Gum Surgery', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:37:05', '2023-12-21 05:37:05'),
(558, 18, 'Tooth Filling', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:37:50', '2023-12-21 05:37:50'),
(559, 18, 'Emergency Dental Care', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:38:55', '2023-12-21 05:38:55'),
(560, 18, 'Orthodontics', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:39:22', '2023-12-21 05:39:22'),
(561, 18, 'Gum Disease Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:40:21', '2023-12-21 05:40:21'),
(562, 19, 'Dental Implantations', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:41:50', '2023-12-21 05:41:50'),
(563, 19, 'Braces/Invisalign', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:42:22', '2023-12-21 05:42:22'),
(564, 19, 'Bonding', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:42:42', '2023-12-21 05:42:42'),
(565, 19, 'Extractions', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:43:11', '2023-12-21 05:43:11'),
(566, 19, 'Bone Grafting', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:43:39', '2023-12-21 05:43:39'),
(567, 19, 'Bone Regeneration', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:44:58', '2023-12-21 05:44:58'),
(568, 19, 'Sealing And Root Planning', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:45:39', '2023-12-21 05:45:39'),
(569, 19, 'Ridge Preservation ', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:46:35', '2023-12-21 05:46:35'),
(570, 19, 'Osseous Surgery', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:47:28', '2023-12-21 05:47:28'),
(571, 19, 'Non Surgery Periodontal Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:48:28', '2023-12-21 05:48:28'),
(572, 19, 'Preventive Care', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:49:02', '2023-12-21 05:49:02'),
(573, 19, 'Cosmetic Procedure', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:49:34', '2023-12-21 05:49:34'),
(574, 19, 'Gum Surgery', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:50:03', '2023-12-21 05:50:03'),
(575, 19, 'Denatures', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:50:56', '2023-12-21 05:50:56'),
(576, 19, 'Smile Make Over', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:51:23', '2023-12-21 05:51:23'),
(577, 19, 'Gum Contouring', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:52:37', '2023-12-21 05:52:37'),
(578, 19, 'Tooth Bonding', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:53:00', '2023-12-21 05:53:00'),
(579, 8, 'Electro Therapy', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:53:43', '2023-12-21 05:53:57'),
(580, 17, 'Laser Eye Surgery', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:55:20', '2023-12-21 05:55:20'),
(581, 17, 'Laser Dentistry', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:55:45', '2023-12-21 05:55:45'),
(582, 17, 'Laser Vein Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:56:17', '2023-12-21 05:56:17'),
(583, 17, 'Laser Therapy For Skin  Condition', NULL, NULL, NULL, NULL, 1, '2023-12-21 05:56:47', '2023-12-21 05:59:26'),
(584, 17, 'Laser Surgery For Tumors', NULL, NULL, NULL, NULL, 1, '2023-12-21 06:01:25', '2023-12-21 06:01:25'),
(585, 17, 'Laser Acne  Treatment', NULL, NULL, NULL, NULL, 1, '2023-12-21 06:02:06', '2023-12-21 06:02:06'),
(586, 17, 'Laser Skin Resurfacing', NULL, NULL, NULL, NULL, 1, '2023-12-21 06:03:24', '2023-12-21 06:03:24');

-- --------------------------------------------------------

--
-- Table structure for table `service_reviews`
--

CREATE TABLE `service_reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` int NOT NULL,
  `service_id` int NOT NULL,
  `freelancer_id` int NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `rating` double(10,2) DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax` double(10,2) DEFAULT NULL,
  `delivery_charge` double(10,2) DEFAULT NULL,
  `currencySymbol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currencySide` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currencyCode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `appDirection` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sms_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sms_creds` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `have_shop` tinyint NOT NULL,
  `findType` tinyint NOT NULL DEFAULT '0',
  `reset_pwd` tinyint NOT NULL DEFAULT '0',
  `user_login` tinyint NOT NULL DEFAULT '0',
  `freelancer_login` tinyint NOT NULL DEFAULT '0',
  `user_verify_with` tinyint NOT NULL DEFAULT '0',
  `search_radius` double(10,2) NOT NULL DEFAULT '10.00',
  `country_modal` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_country_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_city_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_delivery_zip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `app_color` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `app_status` tinyint NOT NULL DEFAULT '1',
  `fcm_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '0',
  `allowDistance` double NOT NULL,
  `searchResultKind` tinyint NOT NULL DEFAULT '0',
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `mobile`, `email`, `address`, `city`, `state`, `zip`, `country`, `tax`, `delivery_charge`, `currencySymbol`, `currencySide`, `currencyCode`, `appDirection`, `logo`, `sms_name`, `sms_creds`, `have_shop`, `findType`, `reset_pwd`, `user_login`, `freelancer_login`, `user_verify_with`, `search_radius`, `country_modal`, `default_country_code`, `default_city_id`, `default_delivery_zip`, `social`, `app_color`, `app_status`, `fcm_token`, `status`, `allowDistance`, `searchResultKind`, `extra_field`, `created_at`, `updated_at`) VALUES
(1, 'Papa Bear Unique Beauty Solution', '919562121333', 'hello@papabear4u.com', 'Njanikkal Building , Brahmapuram (P.O)', 'Ernakulam', 'kerala', '682303', 'india', 0.00, 0.00, '₹', 'left', 'INR', 'ltr', 'setting/PL0EFSANWK6myF1zXXtMZPsl3wvjWrXm2CwgcofV.svg', '3', 'adsg', 1, 0, 0, 0, 0, 0, 10.00, 'vsdf', '91', 'Calicut ', '676554', '[\"https:\\/\\/codecanyon.net\\/user\\/initappz\\/portfolio\",\"https:\\/\\/codecanyon.net\\/user\\/initappz\\/portfolio\",\"https:\\/\\/codecanyon.net\\/user\\/initappz\\/portfolio\",\"https:\\/\\/codecanyon.net\\/user\\/initappz\\/portfolio\",\"https:\\/\\/codecanyon.net\\/user\\/initappz\\/portfolio\",\"https:\\/\\/papabear.techinwallet.com\"]', '#16742d', 1, 'AAAA7fVOrNc:APA91bGIMbeboR672qXziz87WFY_nJ-IFpG1qbdpiCmq0c2gzdOmfjB9t_CCROQAvWrRAUwVOo4R46dQKmZsjN9IB_YkwkVFWFQfiir3AoAAX5mvTU1HI6PMsDl0bHyoX-VdGuN15kbs', 0, 1, 0, NULL, NULL, '2024-01-01 02:39:41');

-- --------------------------------------------------------

--
-- Table structure for table `specialist`
--

CREATE TABLE `specialist` (
  `id` bigint UNSIGNED NOT NULL,
  `salon_uid` int NOT NULL,
  `cate_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timeslots`
--

CREATE TABLE `timeslots` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` int NOT NULL,
  `week_id` int NOT NULL,
  `slots` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `payable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payable_id` bigint UNSIGNED NOT NULL,
  `wallet_id` bigint UNSIGNED NOT NULL,
  `type` enum('deposit','withdraw') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(64,0) NOT NULL,
  `confirmed` tinyint(1) NOT NULL,
  `meta` json DEFAULT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transfers`
--

CREATE TABLE `transfers` (
  `id` bigint UNSIGNED NOT NULL,
  `from_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_id` bigint UNSIGNED NOT NULL,
  `to_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_id` bigint UNSIGNED NOT NULL,
  `status` enum('exchange','transfer','paid','refund','gift') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'transfer',
  `status_last` enum('exchange','transfer','paid','refund','gift') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deposit_id` bigint UNSIGNED NOT NULL,
  `withdraw_id` bigint UNSIGNED NOT NULL,
  `discount` decimal(64,0) NOT NULL DEFAULT '0',
  `fee` decimal(64,0) NOT NULL DEFAULT '0',
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` tinyint DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fcm_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `stripe_key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `extra_field` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `country_code`, `mobile`, `cover`, `gender`, `type`, `fcm_token`, `stripe_key`, `extra_field`, `status`, `created_at`, `updated_at`) VALUES
(1, 'PapaBear', 'Admin', 'admin@gmail.com', '$2y$10$Ry0fAy1JO6Z.pVc2zXXec.eAaqvSBGiMjsTx.j00Ku5SfdIlsA/hW', NULL, NULL, NULL, NULL, 'admin', NULL, NULL, NULL, 1, NULL, NULL),
(4, 'Mohammed', 'Thamnees', 'thamnees611@gmail.com', '$2y$10$TNO7JVWqkgXZzYIgkOrEyOvghF0qZU02GgqpXGMDBiH/qPtedmPt6', '91', '9074727570', NULL, 1, 'salon', NULL, NULL, NULL, 1, '2023-10-12 03:41:44', '2023-10-12 08:26:18'),
(8, 'suhail', 'tp', 'suhail@gmail.com', '$2y$10$Ry0fAy1JO6Z.pVc2zXXec.eAaqvSBGiMjsTx.j00Ku5SfdIlsA/hW', '91', '9897676765', NULL, NULL, 'user', NULL, NULL, NULL, 1, NULL, NULL),
(9, 'saniya', 'p', 'saniya@gmail.com', '$2y$10$TuXXQKV21uvv50MMJrQhUeUrXRrqweujvLCaZmzMCUbwECY42yeT.', '91', '9896145665', 'salon/image/76f3Arju5dgG9WDsZyGxBp5lgHCJP36ZcFNndyFE.jpg', 1, 'freelancer', NULL, NULL, NULL, 1, '2023-11-23 08:56:50', '2023-11-24 15:39:42'),
(15, 'aslam', 'p', 'aslam@gmail.com', '$2y$10$Ry0fAy1JO6Z.pVc2zXXec.eAaqvSBGiMjsTx.j00Ku5SfdIlsA/hW', '91', '9897676545', NULL, 1, 'salon', NULL, NULL, NULL, 1, '2024-01-01 07:53:47', '2024-01-01 07:53:47');

-- --------------------------------------------------------

--
-- Table structure for table `user_services`
--

CREATE TABLE `user_services` (
  `id` bigint UNSIGNED NOT NULL,
  `service_id` int NOT NULL,
  `uid` int NOT NULL,
  `cover` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `off` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descriptions` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `images` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int DEFAULT NULL,
  `extra_field` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_services`
--

INSERT INTO `user_services` (`id`, `service_id`, `uid`, `cover`, `duration`, `price`, `off`, `discount`, `descriptions`, `images`, `status`, `extra_field`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 'regreg', '10', '110', '10', '11', 'fgsfdgbfd rfhrwet thebt fdbhfg therbn.', 'fgbfg', 1, NULL, '2024-01-10 17:51:04', '2024-01-10 17:51:04'),
(2, 2, 4, 'fgg', '20', '250', '20', '50', 'fgrth trheth trhetb trht', 'thgt', 1, NULL, '2024-01-10 17:53:44', '2024-01-10 17:53:44'),
(3, 1, 24, 'fbtb', '10', '110', '10', '11', 'hhnj sthr etbe', 'thwr', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` bigint UNSIGNED NOT NULL,
  `holder_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `holder_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta` json DEFAULT NULL,
  `balance` decimal(64,0) NOT NULL DEFAULT '0',
  `decimal_places` smallint UNSIGNED NOT NULL DEFAULT '2',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_rooms`
--
ALTER TABLE `chat_rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `commission`
--
ALTER TABLE `commission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conversions`
--
ALTER TABLE `conversions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `filters`
--
ALTER TABLE `filters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `individual`
--
ALTER TABLE `individual`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otp`
--
ALTER TABLE `otp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `owner_reviews`
--
ALTER TABLE `owner_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages_reviews`
--
ALTER TABLE `packages_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products_orders`
--
ALTER TABLE `products_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_sub_category`
--
ALTER TABLE `product_sub_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `redeem`
--
ALTER TABLE `redeem`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referral`
--
ALTER TABLE `referral`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referralcodes`
--
ALTER TABLE `referralcodes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `register_request`
--
ALTER TABLE `register_request`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `register_request_email_unique` (`email`);

--
-- Indexes for table `salon`
--
ALTER TABLE `salon`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salon_services`
--
ALTER TABLE `salon_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_reviews`
--
ALTER TABLE `service_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `specialist`
--
ALTER TABLE `specialist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timeslots`
--
ALTER TABLE `timeslots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactions_uuid_unique` (`uuid`),
  ADD KEY `transactions_payable_type_payable_id_index` (`payable_type`,`payable_id`),
  ADD KEY `payable_type_payable_id_ind` (`payable_type`,`payable_id`),
  ADD KEY `payable_type_ind` (`payable_type`,`payable_id`,`type`),
  ADD KEY `payable_confirmed_ind` (`payable_type`,`payable_id`,`confirmed`),
  ADD KEY `payable_type_confirmed_ind` (`payable_type`,`payable_id`,`type`,`confirmed`),
  ADD KEY `transactions_type_index` (`type`),
  ADD KEY `transactions_wallet_id_foreign` (`wallet_id`);

--
-- Indexes for table `transfers`
--
ALTER TABLE `transfers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transfers_uuid_unique` (`uuid`),
  ADD KEY `transfers_from_type_from_id_index` (`from_type`,`from_id`),
  ADD KEY `transfers_to_type_to_id_index` (`to_type`,`to_id`),
  ADD KEY `transfers_deposit_id_foreign` (`deposit_id`),
  ADD KEY `transfers_withdraw_id_foreign` (`withdraw_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_services`
--
ALTER TABLE `user_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wallets_holder_type_holder_id_slug_unique` (`holder_type`,`holder_id`,`slug`),
  ADD UNIQUE KEY `wallets_uuid_unique` (`uuid`),
  ADD KEY `wallets_holder_type_holder_id_index` (`holder_type`,`holder_id`),
  ADD KEY `wallets_slug_index` (`slug`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `chat_rooms`
--
ALTER TABLE `chat_rooms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `commission`
--
ALTER TABLE `commission`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversions`
--
ALTER TABLE `conversions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `filters`
--
ALTER TABLE `filters`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `individual`
--
ALTER TABLE `individual`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `otp`
--
ALTER TABLE `otp`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `owner_reviews`
--
ALTER TABLE `owner_reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packages_reviews`
--
ALTER TABLE `packages_reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products_orders`
--
ALTER TABLE `products_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_sub_category`
--
ALTER TABLE `product_sub_category`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `redeem`
--
ALTER TABLE `redeem`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referral`
--
ALTER TABLE `referral`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `referralcodes`
--
ALTER TABLE `referralcodes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `register_request`
--
ALTER TABLE `register_request`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `salon`
--
ALTER TABLE `salon`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `salon_services`
--
ALTER TABLE `salon_services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=587;

--
-- AUTO_INCREMENT for table `service_reviews`
--
ALTER TABLE `service_reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `specialist`
--
ALTER TABLE `specialist`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timeslots`
--
ALTER TABLE `timeslots`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_services`
--
ALTER TABLE `user_services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transfers`
--
ALTER TABLE `transfers`
  ADD CONSTRAINT `transfers_deposit_id_foreign` FOREIGN KEY (`deposit_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfers_withdraw_id_foreign` FOREIGN KEY (`withdraw_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
