-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 07, 2020 at 06:05 PM
-- Server version: 5.7.27
-- PHP Version: 7.2.19-0ubuntu0.18.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `power_school`
--
CREATE DATABASE IF NOT EXISTS `power_school` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `power_school`;

-- --------------------------------------------------------

--
-- Table structure for table `academic_years`
--

CREATE TABLE `academic_years` (
  `academic_year_id` int(11) NOT NULL,
  `academic_year` varchar(45) DEFAULT NULL,
  `users_user_id` varchar(45) DEFAULT NULL,
  `sections_section_code` varchar(10) DEFAULT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `academic_years`
--

INSERT INTO `academic_years` (`academic_year_id`, `academic_year`, `users_user_id`, `sections_section_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '2018/2019', '1', 'en', NULL, NULL, NULL),
(2, '2017/2018', '1', 'en', NULL, NULL, NULL),
(3, '2019/2020', NULL, 'en', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `audit_academic_setting_actions`
--

CREATE TABLE `audit_academic_setting_actions` (
  `id` int(11) NOT NULL,
  `a_year` varchar(45) NOT NULL,
  `users_user_id` int(11) DEFAULT NULL,
  `sequences_sequence_name` varchar(45) DEFAULT NULL,
  `academic_year` varchar(45) DEFAULT NULL,
  `sequence_name` varchar(45) NOT NULL,
  `sections_section_code` varchar(5) DEFAULT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `audit_academic_setting_actions`
--

INSERT INTO `audit_academic_setting_actions` (`id`, `a_year`, `users_user_id`, `sequences_sequence_name`, `academic_year`, `sequence_name`, `sections_section_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '2018/2019', 1, 'First Sequence', '2018/2019', 'First Sequence', 'en', NULL, '2019-09-12 15:04:29.601238', '2019-09-12 15:04:29.601238');

-- --------------------------------------------------------

--
-- Table structure for table `audit_manage_academic_level_actions`
--

CREATE TABLE `audit_manage_academic_level_actions` (
  `id` int(11) NOT NULL,
  `classes_class_name` varchar(45) DEFAULT NULL,
  `users_user_id` int(11) DEFAULT NULL,
  `sequences_sequence_name` varchar(45) DEFAULT NULL,
  `academic_year` varchar(45) DEFAULT NULL,
  `state` int(11) NOT NULL,
  `sections_section_code` varchar(5) DEFAULT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `audit_manage_academic_level_actions`
--

INSERT INTO `audit_manage_academic_level_actions` (`id`, `classes_class_name`, `users_user_id`, `sequences_sequence_name`, `academic_year`, `state`, `sections_section_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'form one', 1, 'First sequence', '2018/2019', 2, 'en', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `audit_manage_sequence_actions`
--

CREATE TABLE `audit_manage_sequence_actions` (
  `id` int(11) NOT NULL,
  `sequence_name` varchar(45) DEFAULT NULL,
  `users_user_id` int(11) DEFAULT NULL,
  `sequences_sequence_name` varchar(45) DEFAULT NULL,
  `academic_year` varchar(45) DEFAULT NULL,
  `state` int(11) NOT NULL,
  `sections_section_code` varchar(5) DEFAULT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `audit_manage_sequence_actions`
--

INSERT INTO `audit_manage_sequence_actions` (`id`, `sequence_name`, `users_user_id`, `sequences_sequence_name`, `academic_year`, `state`, `sections_section_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '1', 1, '1', '2018/2019', 2, 'en', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `audit_manage_term_actions`
--

CREATE TABLE `audit_manage_term_actions` (
  `id` int(11) NOT NULL,
  `terms_term_name` varchar(100) DEFAULT NULL,
  `users_user_id` int(11) DEFAULT NULL,
  `sequences_sequence_name` varchar(45) DEFAULT NULL,
  `academic_year` varchar(45) DEFAULT NULL,
  `state` int(11) NOT NULL,
  `sections_section_code` varchar(5) DEFAULT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `audit_manage_term_actions`
--

INSERT INTO `audit_manage_term_actions` (`id`, `terms_term_name`, `users_user_id`, `sequences_sequence_name`, `academic_year`, `state`, `sections_section_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '1', 1, '1', '2018/2019', 2, 'en', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `audit_student_actions`
--

CREATE TABLE `audit_student_actions` (
  `id` int(11) NOT NULL,
  `students_student_name` varchar(100) DEFAULT NULL,
  `users_user_id` int(11) DEFAULT NULL,
  `sequences_sequence_name` varchar(45) DEFAULT NULL,
  `academic_year` varchar(45) DEFAULT NULL,
  `state` int(11) NOT NULL,
  `sections_section_code` varchar(5) DEFAULT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `audit_student_actions`
--

INSERT INTO `audit_student_actions` (`id`, `students_student_name`, `users_user_id`, `sequences_sequence_name`, `academic_year`, `state`, `sections_section_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '12', 1, '1', '2018/2019', 3, 'en', NULL, NULL, NULL),
(2, 'Ewang Clarkson', 1, 'First Sequence', '2018/2019', 3, 'en', NULL, NULL, NULL),
(3, 'Nde Yanick', 1, 'First Sequence', '2018/2019', 3, 'en', '2019-09-12 11:57:58.696530', '2019-09-12 11:57:58.696530', '2019-09-12 11:57:58.696530');

-- --------------------------------------------------------

--
-- Table structure for table `audit_subject_actions`
--

CREATE TABLE `audit_subject_actions` (
  `id` int(11) NOT NULL,
  `subjects_subject_name` varchar(100) DEFAULT NULL,
  `users_user_id` int(11) DEFAULT NULL,
  `sequences_sequence_name` int(11) DEFAULT NULL,
  `academic_year` varchar(45) DEFAULT NULL,
  `state` int(11) NOT NULL,
  `sections_section_code` varchar(5) DEFAULT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `audit_subject_actions`
--

INSERT INTO `audit_subject_actions` (`id`, `subjects_subject_name`, `users_user_id`, `sequences_sequence_name`, `academic_year`, `state`, `sections_section_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '2', 1, 1, '2018/2019', 2, 'en', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `audit_user_actions`
--

CREATE TABLE `audit_user_actions` (
  `id` int(11) NOT NULL,
  `suspensionee_name` varchar(255) DEFAULT NULL,
  `users_user_id` int(11) DEFAULT NULL,
  `sequences_sequence_name` varchar(45) DEFAULT NULL,
  `academic_year` varchar(45) DEFAULT NULL,
  `state` int(11) NOT NULL,
  `sections_section_code` varchar(5) DEFAULT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `audit_user_actions`
--

INSERT INTO `audit_user_actions` (`id`, `suspensionee_name`, `users_user_id`, `sequences_sequence_name`, `academic_year`, `state`, `sections_section_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'EWANG CLARKSON', 1, 'First Sequence', '2018/2019', 3, 'en', NULL, NULL, NULL),
(2, 'Gegang', 1, 'First Sequence', '2018/2019', 4, 'en', NULL, NULL, NULL),
(3, '3', 1, 'First Sequence', '2018/2019', 1, 'en', NULL, NULL, NULL),
(4, '3', 1, 'First Sequence', '2018/2019', 2, 'en', NULL, NULL, NULL),
(5, 'EWANG CLARKSON', 1, 'First Sequence', '2018/2019', 3, 'en', '2019-09-13 08:10:36.480570', '2019-09-13 08:10:36.480570', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(45) NOT NULL,
  `icon` varchar(45) NOT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `icon`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'student_management', 'student_management_icon', NULL, NULL, NULL),
(2, 'subject_management', 'subject_management_icon', NULL, NULL, NULL),
(3, 'account_management', 'account_management_icon', NULL, NULL, NULL),
(4, 'result_management', 'result_management_icon', NULL, NULL, NULL),
(5, 'auditing_management', 'auditing_management_icon', NULL, NULL, NULL),
(6, 'administration', 'administration_icon', NULL, NULL, NULL),
(7, 'series_management', 'series_management_icon', NULL, NULL, NULL),
(8, 'academic_management', 'academic_management_icon', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `class_id` int(11) NOT NULL,
  `class_name` varchar(45) NOT NULL,
  `class_code` varchar(10) NOT NULL,
  `annual_promotion_average` float NOT NULL DEFAULT '10',
  `next_promotion_class` varchar(15) DEFAULT NULL,
  `programs_program_code` varchar(3) NOT NULL,
  `sections_section_code` varchar(3) DEFAULT 'en',
  `users_user_id` int(11) DEFAULT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`class_id`, `class_name`, `class_code`, `annual_promotion_average`, `next_promotion_class`, `programs_program_code`, `sections_section_code`, `users_user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Form One', 'fm1', 10, 'fm2', 'ol', 'en', 1, NULL, '2019-09-11 10:12:12.000000', NULL),
(2, 'Form Two', 'fm2', 10, 'fm3', 'ol', 'en', 1, NULL, '2019-09-11 10:12:31.000000', NULL),
(3, 'Form Three', 'fm3', 10, 'fm4', 'ol', 'en', 1, NULL, '2019-09-11 10:12:54.000000', NULL),
(4, 'Form Four', 'fm4', 10, 'fm5', 'ol', 'en', 1, NULL, '2019-09-11 10:13:10.000000', NULL),
(5, 'Form Five', 'fm5', 10, 'ls6', 'ol', 'en', 1, NULL, '2019-09-11 10:13:56.000000', NULL),
(6, 'Lower Sixth', 'ls6', 10, 'us6', 'al', 'en', 1, NULL, '2019-09-11 10:25:53.000000', NULL),
(7, 'Upper Sixth', 'us6', 10, 'uniVER', 'al', 'en', 1, NULL, '2019-09-11 10:35:16.000000', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `classes_has_students`
--

CREATE TABLE `classes_has_students` (
  `id` int(11) NOT NULL,
  `classes_class_code` varchar(10) NOT NULL,
  `matricule` varchar(255) NOT NULL,
  `academic_year` varchar(45) NOT NULL,
  `promotion_state` int(11) NOT NULL DEFAULT '0',
  `sections_section_code` varchar(5) NOT NULL,
  `users_user_id` int(11) DEFAULT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `classes_has_students`
--

INSERT INTO `classes_has_students` (`id`, `classes_class_code`, `matricule`, `academic_year`, `promotion_state`, `sections_section_code`, `users_user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'fm1', 'LBa18OL0001', '2018/2019', 0, 'en', NULL, NULL, NULL, NULL),
(2, 'fm1', 'LBa18OL0002', '2018/2019', 0, 'en', NULL, NULL, NULL, NULL),
(3, 'ls6', 'LBA18AL0001', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(4, 'ls6', 'LBA18AL0002', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(5, 'fm2', 'LBa18OL0003', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(6, 'ls6', 'LBA18AL0003', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(7, 'us6', 'LBA18AL0003', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(8, 'fm1', 'LBa18OL0003', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(9, 'ls6', 'LBA18AL0003', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(10, 'fm4', 'LBa18OL0003', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(11, 'fm2', 'LBa18OL0004', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(12, 'us6', 'LBA18AL0003', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(13, 'ls6', 'LBA18AL0003', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(14, 'ls6', 'LBA18AL0003', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(15, 'ls6', 'LBA18AL0003', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(16, 'ls6', 'LBA18AL0003', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(17, 'ls6', 'LBA18AL0003', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(18, 'ls6', 'LBA18AL0003', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(19, 'us6', 'LBA18AL0003', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(20, 'us6', 'LBA18AL0003', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(21, 'fm2', 'LBa18OL0003', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(22, 'fm5', 'LBa18OL0004', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(23, 'ls6', 'LBA18AL0004', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(24, 'ls6', 'LBA18AL0004', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(25, 'ls6', 'LBA18AL0005', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(26, 'fm1', 'LBa18OL0003', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(27, 'ls6', 'LBA18AL0004', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(28, 'ls6', 'LBA18AL0002', '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(29, 'ls6', 'LBA18AL0003', '2019/2020', 1, 'en', NULL, NULL, NULL, NULL),
(30, 'ls6', 'LBA18AL0005', '2019/2020', 1, 'en', NULL, NULL, NULL, NULL),
(31, 'fm1', 'LBa18OL0001', '2019/2020', 0, 'en', NULL, NULL, NULL, NULL),
(32, 'fm1', 'LBa18OL0002', '2019/2020', 0, 'en', NULL, NULL, NULL, NULL),
(33, 'fm2', 'LBa18OL0003', '2019/2020', 0, 'en', NULL, NULL, NULL, NULL),
(34, 'ls6', 'LBA18AL0006', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(35, 'ls6', 'LBA18AL0007', '2018/2019', 0, 'en', 1, NULL, NULL, NULL),
(36, 'ls6', 'LBA18AL0006', '2019/2020', 1, 'en', NULL, NULL, NULL, NULL),
(37, 'ls6', 'LBA18AL0007', '2019/2020', 1, 'en', NULL, NULL, NULL, NULL),
(38, 'ls6', 'LBA18AL0004', '2018/2019', 1, 'en', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `classes_has_subjects`
--

CREATE TABLE `classes_has_subjects` (
  `id` int(11) NOT NULL,
  `subjects_subject_code` varchar(15) NOT NULL,
  `classes_class_code` varchar(15) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `sections_section_code` varchar(15) NOT NULL,
  `users_user_id` int(11) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `classes_has_subjects`
--

INSERT INTO `classes_has_subjects` (`id`, `subjects_subject_code`, `classes_class_code`, `academic_year`, `sections_section_code`, `users_user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, 'Hbio-FM1', 'fm1', '2018/2019', 'en', 1, '2019-08-25 15:04:50.718102', '2019-08-25 15:04:50.718102', '2019-08-25 15:04:50.718102');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(100) NOT NULL,
  `programs_program_code` varchar(5) NOT NULL,
  `sections_section_code` varchar(3) NOT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`, `programs_program_code`, `sections_section_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Mathematics', 'al', 'en', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `exam_settings`
--

CREATE TABLE `exam_settings` (
  `id` int(11) NOT NULL,
  `center_no` varchar(255) NOT NULL,
  `exam_file_path` varchar(255) NOT NULL,
  `academic_year` varchar(45) NOT NULL,
  `programs_program_code` varchar(10) NOT NULL,
  `sections_section_code` varchar(10) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `exam_settings`
--

INSERT INTO `exam_settings` (`id`, `center_no`, `exam_file_path`, `academic_year`, `programs_program_code`, `sections_section_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '788855', 'public_exam/2018_al_1563123019.pdf', '2018/2019', 'al', 'en', '2019-07-12 14:50:52.540530', '2019-07-12 14:50:52.540530', '2019-07-12 14:50:52.540530'),
(2, '565855988', 'public_exam/2018_ol_1564321153.pdf', '2018/2019', 'ol', 'en', '2019-07-12 15:11:49.119626', '2019-07-12 15:11:49.119626', '2019-07-12 15:11:49.119626');

-- --------------------------------------------------------

--
-- Table structure for table `final_marks`
--

CREATE TABLE `final_marks` (
  `id` int(11) NOT NULL,
  `students_student_id` int(11) NOT NULL,
  `sequences_sequence_id` int(11) NOT NULL,
  `subjects_subject_id` int(11) NOT NULL,
  `subject_score` float NOT NULL DEFAULT '0',
  `academic_year` varchar(45) NOT NULL,
  `sections_section_code` varchar(10) NOT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `final_marks`
--

INSERT INTO `final_marks` (`id`, `students_student_id`, `sequences_sequence_id`, `subjects_subject_id`, `subject_score`, `academic_year`, `sections_section_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 10, 1, 2, 17.7778, '2018/2019', 'en', NULL, NULL, NULL),
(2, 11, 1, 2, 12.3333, '2018/2019', 'en', NULL, NULL, NULL),
(3, 14, 1, 2, 15.5556, '2018/2019', 'en', NULL, NULL, NULL),
(4, 4, 1, 1, 11.3333, '2018/2019', 'en', NULL, NULL, NULL),
(5, 6, 1, 1, 12.6667, '2018/2019', 'en', NULL, NULL, NULL),
(6, 12, 1, 1, 13.3333, '2018/2019', 'en', NULL, NULL, NULL),
(7, 13, 1, 1, 6, '2018/2019', 'en', NULL, NULL, NULL),
(8, 4, 1, 5, 12, '2018/2019', 'en', NULL, NULL, NULL),
(9, 6, 1, 5, 14, '2018/2019', 'en', NULL, NULL, NULL),
(10, 13, 1, 5, 4, '2018/2019', 'en', NULL, NULL, NULL),
(11, 4, 1, 6, 5, '2018/2019', 'en', NULL, NULL, NULL),
(12, 6, 1, 6, 8, '2018/2019', 'en', NULL, NULL, NULL),
(13, 12, 1, 6, 17.5, '2018/2019', 'en', NULL, NULL, NULL),
(14, 13, 1, 6, 20, '2018/2019', 'en', NULL, NULL, NULL),
(15, 4, 1, 7, 16, '2018/2019', 'en', NULL, NULL, NULL),
(16, 6, 1, 7, 12, '2018/2019', 'en', NULL, NULL, NULL),
(17, 12, 1, 7, 9.6, '2018/2019', 'en', NULL, NULL, NULL),
(18, 13, 1, 7, 14, '2018/2019', 'en', NULL, NULL, NULL),
(19, 4, 1, 4, 4, '2018/2019', 'en', NULL, NULL, NULL),
(20, 6, 1, 4, 6.66667, '2018/2019', 'en', NULL, NULL, NULL),
(21, 12, 1, 4, 20, '2018/2019', 'en', NULL, NULL, NULL),
(22, 13, 1, 4, 16.6667, '2018/2019', 'en', NULL, NULL, NULL),
(23, 4, 2, 1, 7.2, '2018/2019', 'en', NULL, NULL, NULL),
(24, 6, 2, 1, 9.4, '2018/2019', 'en', NULL, NULL, NULL),
(25, 12, 2, 1, 11, '2018/2019', 'en', NULL, NULL, NULL),
(26, 13, 2, 1, 11, '2018/2019', 'en', NULL, NULL, NULL),
(27, 4, 2, 4, 15, '2018/2019', 'en', NULL, NULL, NULL),
(28, 6, 2, 4, 9, '2018/2019', 'en', NULL, NULL, NULL),
(29, 12, 2, 4, 12.5, '2018/2019', 'en', NULL, NULL, NULL),
(30, 13, 2, 4, 10, '2018/2019', 'en', NULL, NULL, NULL),
(31, 4, 2, 5, 6.5, '2018/2019', 'en', NULL, NULL, NULL),
(32, 6, 2, 5, 5.5, '2018/2019', 'en', NULL, NULL, NULL),
(33, 12, 2, 5, 15, '2018/2019', 'en', NULL, NULL, NULL),
(34, 13, 2, 5, 4.5, '2018/2019', 'en', NULL, NULL, NULL),
(35, 4, 2, 6, 10.5, '2018/2019', 'en', NULL, NULL, NULL),
(36, 6, 2, 6, 15, '2018/2019', 'en', NULL, NULL, NULL),
(37, 12, 2, 6, 20, '2018/2019', 'en', NULL, NULL, NULL),
(38, 13, 2, 6, 10, '2018/2019', 'en', NULL, NULL, NULL),
(39, 4, 2, 7, 11.1111, '2018/2019', 'en', NULL, NULL, NULL),
(40, 6, 2, 7, 16, '2018/2019', 'en', NULL, NULL, NULL),
(41, 12, 2, 7, 15.5556, '2018/2019', 'en', NULL, NULL, NULL),
(42, 13, 2, 7, 15.5556, '2018/2019', 'en', NULL, NULL, NULL),
(43, 4, 3, 1, 0, '2018/2019', 'en', NULL, NULL, NULL),
(44, 6, 3, 1, 0, '2018/2019', 'en', NULL, NULL, NULL),
(45, 13, 3, 1, 0, '2018/2019', 'en', NULL, NULL, NULL),
(46, 4, 3, 4, 0, '2018/2019', 'en', NULL, NULL, NULL),
(47, 6, 3, 4, 0, '2018/2019', 'en', NULL, NULL, NULL),
(48, 13, 3, 4, 0, '2018/2019', 'en', NULL, NULL, NULL),
(49, 4, 3, 5, 0, '2018/2019', 'en', NULL, NULL, NULL),
(50, 6, 3, 5, 0, '2018/2019', 'en', NULL, NULL, NULL),
(51, 12, 3, 5, 15, '2018/2019', 'en', NULL, NULL, NULL),
(52, 13, 3, 5, 0, '2018/2019', 'en', NULL, NULL, NULL),
(53, 4, 3, 6, 0, '2018/2019', 'en', NULL, NULL, NULL),
(54, 6, 3, 6, 0, '2018/2019', 'en', NULL, NULL, NULL),
(55, 12, 3, 6, 10, '2018/2019', 'en', NULL, NULL, NULL),
(56, 13, 3, 6, 0, '2018/2019', 'en', NULL, NULL, NULL),
(57, 4, 3, 7, 0, '2018/2019', 'en', NULL, NULL, NULL),
(58, 6, 3, 7, 0, '2018/2019', 'en', NULL, NULL, NULL),
(59, 12, 3, 7, 13.3333, '2018/2019', 'en', NULL, NULL, NULL),
(60, 13, 3, 7, 0, '2018/2019', 'en', NULL, NULL, NULL),
(61, 4, 4, 1, 0, '2018/2019', 'en', NULL, NULL, NULL),
(62, 6, 4, 1, 0, '2018/2019', 'en', NULL, NULL, NULL),
(63, 12, 4, 1, 17.5, '2018/2019', 'en', NULL, NULL, NULL),
(64, 13, 4, 1, 0, '2018/2019', 'en', NULL, NULL, NULL),
(65, 4, 4, 4, 0, '2018/2019', 'en', NULL, NULL, NULL),
(66, 6, 4, 4, 0, '2018/2019', 'en', NULL, NULL, NULL),
(67, 12, 4, 4, 4, '2018/2019', 'en', NULL, NULL, NULL),
(68, 13, 4, 4, 0, '2018/2019', 'en', NULL, NULL, NULL),
(69, 4, 4, 5, 0, '2018/2019', 'en', NULL, NULL, NULL),
(70, 6, 4, 5, 0, '2018/2019', 'en', NULL, NULL, NULL),
(71, 12, 4, 5, 20, '2018/2019', 'en', NULL, NULL, NULL),
(72, 13, 4, 5, 0, '2018/2019', 'en', NULL, NULL, NULL),
(73, 4, 4, 6, 0, '2018/2019', 'en', NULL, NULL, NULL),
(74, 6, 4, 6, 0, '2018/2019', 'en', NULL, NULL, NULL),
(75, 12, 4, 6, 11.1111, '2018/2019', 'en', NULL, NULL, NULL),
(76, 13, 4, 6, 0, '2018/2019', 'en', NULL, NULL, NULL),
(77, 4, 4, 7, 0, '2018/2019', 'en', NULL, NULL, NULL),
(78, 6, 4, 7, 0, '2018/2019', 'en', NULL, NULL, NULL),
(79, 12, 4, 7, 8.88889, '2018/2019', 'en', NULL, NULL, NULL),
(80, 13, 4, 7, 0, '2018/2019', 'en', NULL, NULL, NULL),
(81, 14, 1, 3, 15, '2018/2019', 'en', NULL, NULL, NULL),
(82, 12, 1, 10, 0, '2018/2019', 'en', NULL, NULL, NULL),
(83, 13, 1, 10, 0, '2018/2019', 'en', NULL, NULL, NULL),
(84, 15, 1, 10, 0, '2018/2019', 'en', NULL, NULL, NULL),
(85, 10, 1, 9, 0, '2018/2019', 'en', NULL, NULL, NULL),
(86, 11, 1, 9, 0, '2018/2019', 'en', NULL, NULL, NULL),
(87, 14, 1, 9, 0, '2018/2019', 'en', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `matricule_settings`
--

CREATE TABLE `matricule_settings` (
  `id` int(11) NOT NULL,
  `matricule_initial` varchar(100) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `programs_program_code` varchar(10) NOT NULL,
  `sections_section_code` varchar(10) NOT NULL,
  `users_user_id` int(11) DEFAULT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `matricule_settings`
--

INSERT INTO `matricule_settings` (`id`, `matricule_initial`, `academic_year`, `programs_program_code`, `sections_section_code`, `users_user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'LBA', '2018/2019', 'al', 'en', 1, '2019-07-10 21:18:19.229258', '2019-07-10 21:18:19.229258', '2019-07-10 21:18:19.229258'),
(2, 'LBa', '2018/2019', 'ol', 'en', 1, '2019-07-10 21:19:23.254120', '2019-07-10 21:19:23.254120', '2019-07-10 21:19:23.254120'),
(3, 'LBCA', '2018/2019', 'pc', 'fr', 1, '2019-07-28 17:16:03.051274', '2019-07-28 17:16:03.051274', '2019-07-28 17:16:03.051274'),
(4, 'LBCa', '2018/2019', 'olc', 'en', 1, '2019-08-25 13:55:53.065956', '2019-08-25 13:55:53.065956', '2019-08-25 13:55:53.065956'),
(5, 'LBCa', '2018/2019', 'alc', 'en', 1, '2019-08-25 13:56:18.404789', '2019-08-25 13:56:18.404789', '2019-08-25 13:56:18.404789'),
(6, 'LBTa', '2018/2019', 'olt', 'en', 1, '2019-08-25 13:56:30.466921', '2019-08-25 13:56:30.466921', '2019-08-25 13:56:30.466921'),
(7, 'LBTa', '2018/2019', 'alt', 'en', 1, '2019-08-25 13:56:46.538864', '2019-08-25 13:56:46.538864', '2019-08-25 13:56:46.538864');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `notification_subject` varchar(100) NOT NULL,
  `notification_body` varchar(255) NOT NULL,
  `state` int(11) NOT NULL,
  `academic_year` varchar(15) DEFAULT '2018/2019',
  `users_user_id` int(11) DEFAULT NULL,
  `notifier_id` int(11) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `notification_subject`, `notification_body`, `state`, `academic_year`, `users_user_id`, `notifier_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Subject Upload', 'Upload all subject of your department', 1, '2018/2019', 1, 1, '2019-06-02 23:00:00.000000', '2019-08-14 19:16:56.000000', '2019-07-06 20:37:18.000000'),
(2, 'Student upload', 'Please upload all your students information', 1, '2018/2019', 1, 1, '2019-06-10 23:00:00.000000', '2019-08-26 16:17:59.000000', '2019-07-06 20:40:52.000000'),
(3, 'Admission', 'Please set the admission date for your department', 1, '2018/2019', 1, 1, '2019-06-25 23:00:00.000000', '2019-07-25 15:56:50.000000', '2019-07-25 15:56:50.000000'),
(4, 'Fee Payment', 'Set Fee Payment dataline', 1, '2018/2019', 1, 2, '2019-06-03 23:00:00.000000', '2019-07-28 17:23:44.000000', NULL),
(5, 'Tution Fee', 'Set tution ', 1, '2018/2019', 1, 2, '2019-06-25 23:00:00.000000', '2019-07-28 17:25:57.000000', NULL),
(6, 'Caution Fee', 'set caution for all students', 1, '2018/2019', 1, 2, '2019-06-26 23:00:00.000000', '2019-08-14 19:12:09.000000', NULL),
(7, 'Chemistry Mark Entry for Form Two', 'Please do well to enter marks for form two chemistry', 1, '2018/2019', 1, 1, '2019-08-12 15:49:36.322609', '2019-08-12 14:57:12.000000', NULL),
(8, 'Chemistry Mark Entry for Form Two', 'Please do well to enter marks for form two chemistry', 1, '2018/2019', 2, 1, '2019-08-12 15:49:36.322609', '2019-08-26 16:18:12.000000', NULL),
(9, 'Chemistry Mark Entry for form three', 'Enter the marks for albgoeirg', 1, '2018/2019', 1, 1, '2019-08-12 15:52:40.772152', '2019-08-12 14:52:49.000000', NULL),
(10, 'Chemistry Mark Entry for form three', 'Enter the marks for albgoeirg', 1, '2018/2019', 2, 1, '2019-08-12 15:52:40.772152', '2019-08-14 19:12:19.000000', NULL),
(11, 'akiskbgiaetg', 'lBIgiag', 1, '2018/2019', 1, 1, '2019-08-12 17:37:57.184893', '2019-08-26 16:17:18.000000', NULL),
(12, 'akiskbgiaetg', 'lBIgiag', 1, '2018/2019', 3, 1, '2019-08-12 17:37:57.184893', '2019-08-26 16:18:14.000000', NULL),
(13, 'aljnoleg', 'akzshgbekt', 1, '2018/2019', 1, 1, '2019-08-12 17:38:20.850848', '2019-08-14 19:12:16.000000', NULL),
(14, 'aljnoleg', 'akzshgbekt', 1, '2018/2019', 3, 1, '2019-08-12 17:38:20.850848', '2019-08-26 16:18:16.000000', NULL),
(15, 'lszg', 'lzsjg', 1, '2018/2019', 3, 1, '2019-08-12 17:38:36.193278', '2019-08-26 16:18:18.000000', NULL),
(16, 'Marks submission', 'Submit marks for all your courses', 1, '2018/2019', 1, 1, '2019-08-29 16:52:49.938632', '2019-08-29 15:52:56.000000', NULL),
(17, 'silgoetw', 'iawygyahte4g', 1, '2018/2019', 1, 1, '2019-08-29 17:06:21.807980', '2019-08-29 16:06:28.000000', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `privileges`
--

CREATE TABLE `privileges` (
  `privilege_id` int(11) NOT NULL,
  `privilege_name` varchar(45) NOT NULL,
  `privilege_url` varchar(45) NOT NULL,
  `privilege_icon` varchar(45) NOT NULL,
  `state` varchar(45) NOT NULL,
  `categories_category_id` int(11) NOT NULL,
  `sections_section_code` varchar(5) NOT NULL DEFAULT 'dl',
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `privileges`
--

INSERT INTO `privileges` (`privilege_id`, `privilege_name`, `privilege_url`, `privilege_icon`, `state`, `categories_category_id`, `sections_section_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'batch_student_upload', 'batch_student_upload', 'batch_student_upload_icon', '1', 1, 'dl', NULL, NULL, NULL),
(2, 'batch_subject_upload', 'batch_subject_upload', 'batch_subject_upload_icon', '1', 2, 'dl', NULL, NULL, NULL),
(3, 'add_user', 'add_user', 'add_user_icon', '1', 3, 'dl', NULL, NULL, NULL),
(4, 'reset_user', 'reset_user', 'reset_user_icon', '1', 3, 'dl', NULL, NULL, NULL),
(5, 'suspend_user', 'suspend_user', 'suspend_user_icon', '1', 3, 'dl', NULL, NULL, NULL),
(6, 'edit_user', 'edit_user', 'edit_user_icon', '1', 3, 'dl', NULL, NULL, NULL),
(7, 'password_reset', 'password_reset', 'password_reset_icon', '1', 3, 'dl', NULL, NULL, NULL),
(8, 'manage_role', 'manage_role', 'manage_role_icon', '1', 6, 'dl', NULL, NULL, NULL),
(9, 'manage_privilege', 'manage_privilege', 'manage_privilege_icon', '1', 6, 'dl', NULL, NULL, NULL),
(10, 'assign_role', 'assign_role', 'assign_role_icon', '1', 6, 'dl', NULL, NULL, NULL),
(11, 'matricule_setting', 'matricule_setting', 'matricule_setting_icon', '1', 8, 'dl', NULL, NULL, NULL),
(12, 'public_exams', 'public_exams', 'public_exams_icon', '1', 4, 'dl', NULL, NULL, NULL),
(13, 'add_student', 'add_student', 'add_student_icon', '1', 1, 'dl', NULL, NULL, NULL),
(14, 'academic_setting', 'academic_setting', 'academic_setting_icon', '1', 8, 'dl', NULL, NULL, NULL),
(15, 'manage_sequence', 'manage_sequence', 'manage_sequence_icon', '1', 8, 'dl', NULL, NULL, NULL),
(16, 'manage_term', 'manage_term', 'manage_term_icon', '1', 8, 'dl', NULL, NULL, NULL),
(17, 'manage_series', 'manage_series', 'manage_series_icon', '1', 7, 'dl', NULL, NULL, NULL),
(18, 'manage_class', 'manage_class', 'manage_class_icon', '1', 8, 'dl', NULL, NULL, NULL),
(19, 'manage_student_series', 'manage_student_series', 'manage_student_series_icon', '1', 7, 'dl', NULL, NULL, NULL),
(20, 'manage_subject_series', 'manage_subject_series', 'manage_subject_series_icon', '1', 7, 'dl', NULL, NULL, NULL),
(21, 'manage_subject_test', 'manage_subject_test', 'manage_subject_test_icon', '1', 2, 'dl', NULL, NULL, NULL),
(22, 'publish_result', 'publish_result', 'publish_result_icon', '1', 4, 'dl', NULL, NULL, NULL),
(23, 'announcement', 'announcement', 'announcement_icon', '1', 8, 'dl', NULL, NULL, NULL),
(24, 'print_report_card', 'print_report_card', 'print_report_card_icon', '1', 4, 'dl', NULL, NULL, NULL),
(25, 'manage_class_promotion', 'manage_class_promotion', 'manage_class_promotion_icon', '1', 4, 'dl', NULL, NULL, NULL),
(26, 'late_marks_submission', 'late_marks_submission', 'late_marks_submission_icon', '0', 4, 'dl', NULL, NULL, NULL),
(27, 'manage_student_portal_access', 'manage_student_portal_access', 'manage_student_portal_access_icon', '1', 1, 'dl', NULL, NULL, NULL),
(28, 'manage_teacher_subject', 'manage_teacher_subject', 'manage_teacher_subject_icon', '1', 2, 'dl', NULL, NULL, NULL),
(29, 'add_subject', 'add_subject', 'add_subject_icon', '1', 2, 'dl', NULL, NULL, NULL),
(30, 'edit_subject', 'edit_subject', 'edit_subject_icon', '1', 2, 'dl', NULL, NULL, NULL),
(31, 'get_class_list', 'get_class_list', 'get_class_list_icon', '1', 1, 'dl', NULL, NULL, NULL),
(32, 'series_data_upload', 'series_data_upload', 'series_data_upload_icon', '1', 7, 'dl', NULL, NULL, NULL),
(33, 'view_subject', 'view_subject', 'view_subject_icon', '1', 2, 'dl', NULL, NULL, NULL),
(34, 'edit_student', 'search_student', 'edit_student_icon', '1', 1, 'dl', NULL, NULL, NULL),
(35, 'sms_notifications', 'sms_notifications', 'sms_notifications_icon', '1', 4, 'dl', NULL, NULL, NULL),
(36, 'id_card', 'id_card', 'id_card_icon', '1', 1, 'dl', NULL, NULL, NULL),
(37, 'user_list', 'user_list', 'user_list_icon', '1', 3, 'dl', NULL, NULL, NULL),
(38, 'audit_user_actions', 'audit_user_actions', 'audit_user_actions_icon', '1', 5, 'dl', NULL, NULL, NULL),
(39, 'audit_student_actions', 'audit_student_actions', 'audit_student_actions_icon', '1', 5, 'dl', NULL, NULL, NULL),
(40, 'audit_subject_actions', 'audit_subject_actions', 'audit_subject_actions_icon', '1', 5, 'dl', NULL, NULL, NULL),
(41, 'audit_sequence_actions', 'audit_sequence_actions', 'audit_sequence_actions_icon', '1', 5, 'dl', NULL, NULL, NULL),
(42, 'audit_term_actions', 'audit_term_actions', 'audit_term_actions_icon', '1', 5, 'dl', NULL, NULL, NULL),
(43, 'audit_class_actions', 'audit_class_actions', 'audit_class_actions_icon', '1', 5, 'dl', NULL, NULL, NULL),
(44, 'audit_setting_actions', 'audit_setting_actions', 'audit_setting_actions_icon', '1', 5, 'dl', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `program_id` int(11) NOT NULL,
  `program_name` varchar(255) NOT NULL,
  `program_code` varchar(5) NOT NULL,
  `sections_section_code` varchar(3) NOT NULL,
  `users_user_id` int(11) NOT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`program_id`, `program_name`, `program_code`, `sections_section_code`, `users_user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'GCE Ordinary Level General', 'ol', 'en', 1, NULL, NULL, NULL),
(2, 'GCE Advance Level General', 'al', 'en', 1, NULL, NULL, NULL),
(3, 'BEPC  ', 'pc', 'fr', 1, NULL, NULL, NULL),
(4, 'Probatoire', 'dc', 'fr', 1, NULL, NULL, NULL),
(5, 'GCE Ordinary Level Technical', 'olt', 'en', 1, NULL, NULL, NULL),
(6, 'GCE Ordinary Level Commercial', 'olc', 'en', 1, NULL, NULL, NULL),
(7, 'GCE Advance Level Technical', 'alt', 'en', 1, NULL, NULL, NULL),
(8, 'GCE Advance Level Commercial', 'alc', 'en', 1, NULL, NULL, NULL),
(9, 'Bacaloriat (BAC)', 'bac', 'fr', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `publish_status`
--

CREATE TABLE `publish_status` (
  `id` int(11) NOT NULL,
  `sequences_sequence_id` int(11) NOT NULL,
  `academic_year` varchar(45) NOT NULL,
  `publish_state` int(11) NOT NULL,
  `sections_section_code` varchar(10) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `publish_status`
--

INSERT INTO `publish_status` (`id`, `sequences_sequence_id`, `academic_year`, `publish_state`, `sections_section_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, '2018/2019', 1, 'en', '2019-08-12 14:21:30.336865', '2019-08-12 14:21:30.336865', '2019-08-12 14:21:30.336865'),
(2, 2, '2018/2019', 1, 'en', '2019-08-13 18:30:37.447646', '2019-08-13 18:30:37.447646', '2019-08-13 18:30:37.447646'),
(3, 3, '2018/2019', 1, 'en', '2019-08-14 23:20:19.789995', '2019-08-14 23:20:19.789995', '2019-08-14 23:20:19.789995'),
(4, 4, '2018/2019', 1, 'en', '2019-08-14 23:40:50.466221', '2019-08-14 23:40:50.466221', '2019-08-14 23:40:50.466221');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(45) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '1',
  `description` varchar(255) DEFAULT NULL,
  `users_user_id` int(11) NOT NULL,
  `sections_section_code` varchar(5) DEFAULT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `state`, `description`, `users_user_id`, `sections_section_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'System Administrator', 1, 'Can do all things in the system', 1, 'en', '2019-06-11 23:00:00.000000', '2019-07-06 18:29:25.000000', NULL),
(2, 'Teacher', 1, 'Teaches students<br>', 1, 'en', '2019-07-06 17:59:39.000000', '2019-07-06 17:59:39.000000', NULL),
(3, 'Vice Principal', 1, 'For vice principal only<br>', 1, 'en', '2019-07-06 18:01:50.000000', '2019-07-06 18:01:50.000000', NULL),
(4, 'Principal', 1, 'This role is for principal only<br>', 1, 'en', '2019-07-06 18:05:53.000000', '2019-07-06 18:30:55.000000', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles_has_privileges`
--

CREATE TABLE `roles_has_privileges` (
  `id` int(11) NOT NULL,
  `privileges_privilege_id` int(11) NOT NULL,
  `roles_role_id` int(11) NOT NULL,
  `users_user_id` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles_has_privileges`
--

INSERT INTO `roles_has_privileges` (`id`, `privileges_privilege_id`, `roles_role_id`, `users_user_id`, `state`, `created_at`, `updated_at`, `deleted_at`) VALUES
(126, 7, 2, 1, 1, NULL, NULL, NULL),
(127, 6, 2, 1, 1, NULL, NULL, NULL),
(128, 5, 2, 1, 1, NULL, NULL, NULL),
(129, 2, 2, 1, 1, NULL, NULL, NULL),
(130, 10, 2, 1, 1, NULL, NULL, NULL),
(131, 9, 2, 1, 1, NULL, NULL, NULL),
(564, 44, 1, 1, 1, NULL, NULL, NULL),
(565, 43, 1, 1, 1, NULL, NULL, NULL),
(566, 42, 1, 1, 1, NULL, NULL, NULL),
(567, 41, 1, 1, 1, NULL, NULL, NULL),
(568, 40, 1, 1, 1, NULL, NULL, NULL),
(569, 39, 1, 1, 1, NULL, NULL, NULL),
(570, 38, 1, 1, 1, NULL, NULL, NULL),
(571, 37, 1, 1, 1, NULL, NULL, NULL),
(572, 36, 1, 1, 1, NULL, NULL, NULL),
(573, 35, 1, 1, 1, NULL, NULL, NULL),
(574, 34, 1, 1, 1, NULL, NULL, NULL),
(575, 29, 1, 1, 1, NULL, NULL, NULL),
(576, 33, 1, 1, 1, NULL, NULL, NULL),
(577, 32, 1, 1, 1, NULL, NULL, NULL),
(578, 31, 1, 1, 1, NULL, NULL, NULL),
(579, 30, 1, 1, 1, NULL, NULL, NULL),
(580, 28, 1, 1, 1, NULL, NULL, NULL),
(581, 27, 1, 1, 1, NULL, NULL, NULL),
(582, 26, 1, 1, 1, NULL, NULL, NULL),
(583, 25, 1, 1, 1, NULL, NULL, NULL),
(584, 24, 1, 1, 1, NULL, NULL, NULL),
(585, 23, 1, 1, 1, NULL, NULL, NULL),
(586, 22, 1, 1, 1, NULL, NULL, NULL),
(587, 21, 1, 1, 1, NULL, NULL, NULL),
(588, 20, 1, 1, 1, NULL, NULL, NULL),
(589, 19, 1, 1, 1, NULL, NULL, NULL),
(590, 18, 1, 1, 1, NULL, NULL, NULL),
(591, 17, 1, 1, 1, NULL, NULL, NULL),
(592, 2, 1, 1, 1, NULL, NULL, NULL),
(593, 12, 1, 1, 1, NULL, NULL, NULL),
(594, 11, 1, 1, 1, NULL, NULL, NULL),
(595, 10, 1, 1, 1, NULL, NULL, NULL),
(596, 1, 1, 1, 1, NULL, NULL, NULL),
(597, 8, 1, 1, 1, NULL, NULL, NULL),
(598, 3, 1, 1, 1, NULL, NULL, NULL),
(599, 6, 1, 1, 1, NULL, NULL, NULL),
(600, 4, 1, 1, 1, NULL, NULL, NULL),
(601, 5, 1, 1, 1, NULL, NULL, NULL),
(602, 7, 1, 1, 1, NULL, NULL, NULL),
(603, 9, 1, 1, 1, NULL, NULL, NULL),
(604, 13, 1, 1, 1, NULL, NULL, NULL),
(605, 14, 1, 1, 1, NULL, NULL, NULL),
(606, 15, 1, 1, 1, NULL, NULL, NULL),
(607, 16, 1, 1, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `section_id` int(11) NOT NULL,
  `section_name` varchar(200) NOT NULL,
  `section_code` varchar(3) NOT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`section_id`, `section_name`, `section_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Anglophone Section', 'en', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sequences`
--

CREATE TABLE `sequences` (
  `sequence_id` int(11) NOT NULL,
  `sequence_name` varchar(100) NOT NULL,
  `sequence_code` varchar(5) NOT NULL,
  `terms_term_id` int(11) NOT NULL,
  `sections_section_code` varchar(5) NOT NULL,
  `users_user_id` int(11) NOT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sequences`
--

INSERT INTO `sequences` (`sequence_id`, `sequence_name`, `sequence_code`, `terms_term_id`, `sections_section_code`, `users_user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'First Sequence', 'fs', 1, 'en', 1, NULL, '2019-07-24 22:33:02.000000', NULL),
(2, 'Second Sequence', 'ss', 1, 'en', 1, NULL, NULL, NULL),
(3, 'Third Sequence', 'ts', 2, 'en', 1, NULL, NULL, NULL),
(4, 'Fourth Sequence', 'fos', 2, 'en', 1, NULL, NULL, NULL),
(5, 'Fifth Sequence', 'fis', 3, 'en', 1, NULL, NULL, NULL),
(6, 'Sixth Sequence', 'sis', 3, 'en', 1, NULL, NULL, NULL),
(7, 'Premiere Sequence', 'prse', 4, 'fr', 1, '2019-07-28 16:03:24.000000', '2019-07-28 16:03:24.000000', NULL),
(8, 'Deuxieme Sequence', 'dus', 4, 'fr', 1, '2019-07-28 16:03:47.000000', '2019-07-28 16:03:47.000000', NULL),
(9, 'Troixieme Sequence', 'trse', 5, 'fr', 1, '2019-07-28 16:04:09.000000', '2019-07-28 16:04:09.000000', NULL),
(10, 'Quatrieme Sequence', 'qtse', 5, 'fr', 1, '2019-07-28 16:04:32.000000', '2019-07-28 16:04:32.000000', NULL),
(11, 'Cinquieme Sequence', 'cise', 6, 'fr', 1, '2019-07-28 16:05:42.000000', '2019-07-28 16:05:42.000000', NULL),
(12, 'Sixieme Sequence', 'sixse', 6, 'fr', 1, '2019-07-28 16:06:06.000000', '2019-07-28 16:06:06.000000', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sequence_averages`
--

CREATE TABLE `sequence_averages` (
  `id` int(11) NOT NULL,
  `sequences_sequence_id` int(11) NOT NULL,
  `students_student_id` int(11) NOT NULL,
  `average` float NOT NULL,
  `academic_year` varchar(45) NOT NULL,
  `state` int(11) DEFAULT NULL,
  `classes_class_code` varchar(15) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sequence_averages`
--

INSERT INTO `sequence_averages` (`id`, `sequences_sequence_id`, `students_student_id`, `average`, `academic_year`, `state`, `classes_class_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 10, 8.89, '2018/2019', 1, 'fm1', '2019-09-06 17:42:31.681383', '2019-09-06 17:42:31.681383', '2019-09-06 17:42:31.681383'),
(2, 1, 11, 6.17, '2018/2019', 1, 'fm1', '2019-09-06 17:42:31.681383', '2019-09-06 17:42:31.681383', '2019-09-06 17:42:31.681383'),
(3, 1, 14, 10.19, '2018/2019', 1, 'fm1', '2019-09-06 17:42:31.681383', '2019-09-06 17:42:31.681383', '2019-09-06 17:42:31.681383'),
(4, 1, 14, 10.19, '2018/2019', 1, 'fm2', '2019-09-06 17:42:31.681383', '2019-09-06 17:42:31.681383', '2019-09-06 17:42:31.681383'),
(5, 1, 14, 10.19, '2018/2019', 1, 'fm4', '2019-09-06 17:42:31.681383', '2019-09-06 17:42:31.681383', '2019-09-06 17:42:31.681383'),
(6, 1, 4, 7, '2018/2019', 1, 'ls6', '2019-09-06 17:42:31.681383', '2019-09-06 17:42:31.681383', '2019-09-06 17:42:31.681383'),
(7, 1, 6, 9.56, '2018/2019', 1, 'ls6', '2019-09-06 17:42:31.681383', '2019-09-06 17:42:31.681383', '2019-09-06 17:42:31.681383'),
(8, 1, 12, 18.75, '2018/2019', 1, 'ls6', '2019-09-06 17:42:31.681383', '2019-09-06 17:42:31.681383', '2019-09-06 17:42:31.681383'),
(9, 1, 13, 13.56, '2018/2019', 1, 'ls6', '2019-09-06 17:42:31.681383', '2019-09-06 17:42:31.681383', '2019-09-06 17:42:31.681383'),
(10, 1, 15, 0, '2018/2019', 1, 'ls6', '2019-09-06 17:42:31.681383', '2019-09-06 17:42:31.681383', '2019-09-06 17:42:31.681383'),
(11, 1, 16, 0, '2018/2019', 1, 'ls6', '2019-09-06 17:42:31.681383', '2019-09-06 17:42:31.681383', '2019-09-06 17:42:31.681383'),
(12, 1, 6, 9.56, '2018/2019', 1, 'us6', '2019-09-06 17:42:31.681383', '2019-09-06 17:42:31.681383', '2019-09-06 17:42:31.681383');

-- --------------------------------------------------------

--
-- Table structure for table `series`
--

CREATE TABLE `series` (
  `series_id` int(11) NOT NULL,
  `series_name` varchar(45) NOT NULL,
  `series_code` varchar(45) NOT NULL,
  `sections_section_code` varchar(5) NOT NULL,
  `users_user_id` int(11) NOT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `series`
--

INSERT INTO `series` (`series_id`, `series_name`, `series_code`, `sections_section_code`, `users_user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 's1', 's1', 'en', 1, NULL, '2019-07-24 23:23:14.000000', NULL),
(2, 's2', 's2', 'en', 1, NULL, NULL, NULL),
(3, 's3', 's3', 'en', 1, NULL, NULL, NULL),
(4, 's4', 's4', 'en', 1, NULL, NULL, NULL),
(5, 's5', 's5', 'en', 1, NULL, NULL, NULL),
(6, 's6', 's6', 'en', 1, NULL, NULL, NULL),
(7, 's7', 's7', 'en', 1, NULL, NULL, NULL),
(8, 's8', 's8', 'en', 1, NULL, NULL, NULL),
(9, 's9', 's9', 'en', 1, NULL, NULL, NULL),
(10, 'a1', 'a1', 'en', 1, NULL, NULL, NULL),
(11, 'a2', 'a2', 'en', 1, NULL, NULL, NULL),
(12, 'a3', 'a3', 'en', 1, NULL, NULL, NULL),
(13, 'a4', 'a4', 'en', 1, NULL, NULL, NULL),
(14, 'a5', 'a5', 'en', 1, NULL, NULL, NULL),
(15, 'a6', 'a6', 'en', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `series_has_students`
--

CREATE TABLE `series_has_students` (
  `id` int(11) NOT NULL,
  `series_series_code` varchar(15) NOT NULL,
  `matricule` varchar(45) NOT NULL,
  `classes_class_code` varchar(15) NOT NULL,
  `sections_section_code` varchar(5) NOT NULL,
  `academic_year` varchar(45) NOT NULL,
  `users_user_id` int(11) NOT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `series_has_students`
--

INSERT INTO `series_has_students` (`id`, `series_series_code`, `matricule`, `classes_class_code`, `sections_section_code`, `academic_year`, `users_user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 's1', 'LBA18AL0001', 'ls6', 'en', '2018/2019', 1, NULL, NULL, NULL),
(2, 's2', 'LBA18AL0002', 'ls6', 'en', '2018/2019', 1, NULL, NULL, NULL),
(3, 's2', 'LBA18AL0003', 'ls6', 'en', '2018/2019', 1, NULL, NULL, NULL),
(16, 's1', 'LBA18AL0004', 'ls6', 'en', '2018/2019', 1, NULL, NULL, NULL),
(18, 's1', 'LBA18AL0005', 'ls6', 'en', '2018/2019', 1, NULL, NULL, NULL),
(19, 's1', 'LBA18AL0006', 'ls6', 'en', '2018/2019', 1, NULL, NULL, NULL),
(20, 's2', 'LBA18AL0007', 'ls6', 'en', '2018/2019', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `series_has_subjects`
--

CREATE TABLE `series_has_subjects` (
  `id` int(11) NOT NULL,
  `series_series_code` varchar(10) NOT NULL,
  `subjects_subject_code` varchar(45) NOT NULL,
  `classes_class_code` varchar(10) NOT NULL,
  `sections_section_code` varchar(5) NOT NULL,
  `academic_year` varchar(45) NOT NULL,
  `users_user_id` int(11) NOT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `series_has_subjects`
--

INSERT INTO `series_has_subjects` (`id`, `series_series_code`, `subjects_subject_code`, `classes_class_code`, `sections_section_code`, `academic_year`, `users_user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 's2', 'CHEMl-306', 'ls6', 'en', '2018/2019', 1, NULL, NULL, NULL),
(3, 's3', 'CHEMl-306', 'ls6', 'en', '2018/2019', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_id` int(11) NOT NULL,
  `sequences_sequence_id` int(11) NOT NULL,
  `sections_section_code` varchar(5) DEFAULT NULL,
  `academic_year` varchar(45) DEFAULT NULL,
  `publish_date` varchar(15) DEFAULT NULL,
  `publish_state` int(11) DEFAULT '0',
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_id`, `sequences_sequence_id`, `sections_section_code`, `academic_year`, `publish_date`, `publish_state`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'en', '2018/2019', '2019-09-11', NULL, NULL, NULL, NULL),
(2, 7, 'fr', '2018/2019', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sms_notifications`
--

CREATE TABLE `sms_notifications` (
  `id` int(11) NOT NULL,
  `sequences_sequence_id` int(11) NOT NULL,
  `students_student_id` int(11) NOT NULL,
  `sms_count` int(11) NOT NULL,
  `academic_year` varchar(15) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `matricule` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `date_of_birth` varchar(45) DEFAULT NULL,
  `place_of_birth` varchar(45) NOT NULL,
  `region_of_origin` varchar(45) NOT NULL,
  `father_address` varchar(45) NOT NULL,
  `mother_address` varchar(45) DEFAULT NULL,
  `tutor_name` varchar(100) DEFAULT NULL,
  `tutor_address` varchar(45) DEFAULT NULL,
  `admission_date` varchar(45) NOT NULL,
  `academic_state` int(11) NOT NULL DEFAULT '1',
  `profile` varchar(255) NOT NULL DEFAULT '/images/avatars/default_profile.png',
  `programs_program_code` varchar(5) NOT NULL,
  `sections_section_code` varchar(3) DEFAULT 'en',
  `users_user_id` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `matricule`, `full_name`, `date_of_birth`, `place_of_birth`, `region_of_origin`, `father_address`, `mother_address`, `tutor_name`, `tutor_address`, `admission_date`, `academic_state`, `profile`, `programs_program_code`, `sections_section_code`, `users_user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 'LBA18AL0002', 'Nde Yanick', '1970-01-01', 'Bamenda', 'nw', '673676302', NULL, '673676302', '673676302', '2019-01-01', 1, '/images/avatars/default_profile.png', 'al', 'en', 1, NULL, NULL, NULL),
(6, 'LBA18AL0003', 'Nde Yanick Che', '2019-09-11', 'Douala', 'nw', '237673676301', NULL, NULL, NULL, '2019-07-16', 1, 'student_profile/674365774_student_photo_1563895778.jpg', 'al', 'en', 1, NULL, '2019-08-26 14:15:19.000000', NULL),
(10, 'LBa18OL0001', 'Ewang Clarkson', '01 September 1905', 'Mbandjock', 'center', '673679301', '673676301', 'Ewang clarkson', '673676301', '01 September 2015', 1, '/images/avatars/default_profile.png', 'ol', 'en', 1, NULL, NULL, NULL),
(11, 'LBa18OL0002', 'Nde Yanick', '01 January 1970', 'Bamenda', 'North WEST', '673676302', '673676302', '673676302', '673676302', '01 September 2019', 1, '/images/avatars/default_profile.png', 'ol', 'en', 1, NULL, NULL, NULL),
(12, 'LBA18AL0004', 'Ewang Clarkson', '1905-09-13', 'Mbandjock', 'fn', '673679301', NULL, 'Ewang clarkson', '673676301', '2019-01-01', 1, 'student_profile/674365774_student_photo_1563895778.jpg', 'al', 'en', 1, NULL, NULL, NULL),
(13, 'LBA18AL0005', 'Nde Yanick Che Obi', '2019-07-16', 'Douala', 'nw', '673676301', NULL, NULL, NULL, '2019-07-10', 1, 'student_profile/673676301_student_photo_1563961925.png', 'al', 'en', 1, NULL, '2019-09-06 12:14:33.000000', NULL),
(14, 'LBa18OL0003', 'Gnoitik Gegang Facko', '2019-07-16', 'Douala', 'fn', '65785548855', NULL, NULL, NULL, '2019-07-09', 1, 'student_profile/65785548855_student_photo_1563962373.jpg', 'ol', 'en', 1, NULL, '2019-08-26 14:15:13.000000', NULL),
(15, 'LBA18AL0006', 'Ewang Clarkson', '01 September 43773', 'Mbandjock', 'center', '673679301', '673676301', 'Ewang clarkson', '673676301', '2015-09-01', 1, '/images/avatars/default_profile.png', 'al', 'en', 1, NULL, NULL, NULL),
(16, 'LBA18AL0007', 'Nde Yanick y', '01 January 88443', 'Bamenda', 'North WEST', '673676302', '673676302', '673676302', '673676302', '2019-09-01', 1, '/images/avatars/default_profile.png', 'al', 'en', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_accounts`
--

CREATE TABLE `student_accounts` (
  `id` int(11) NOT NULL,
  `matricule` varchar(45) NOT NULL,
  `secret_code` varchar(255) NOT NULL,
  `state` int(11) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_ate` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `student_accounts`
--

INSERT INTO `student_accounts` (`id`, `matricule`, `secret_code`, `state`, `created_at`, `updated_at`, `deleted_ate`) VALUES
(1, 'LBA18OL0001', 'b5c0b187fe309af0f4d35982fd961d7e', 1, '2019-07-15 23:00:00.000000', '2019-07-15 23:00:00.000000', '2019-07-22 23:00:00.000000'),
(2, 'LBA18AL0004', 'eyJpdiI6InJyd1lrRmdzeW9sa3RJVFFjQncwdnc9PSIsInZhbHVlIjoiTTUxbkJZN3NOWWY2NzVxTHlvMnVydz09IiwibWFjIjoiMzg1YzBmZmRjNmFjMmFmNDk2ODE0NmMwYzQxZDAzYjZmZTY3NWRiZWU1ZjIxNWQyZjkzZTlmNzY5ODUyZWM0ZSJ9', 1, '2019-07-24 09:37:00.450928', '2019-07-24 09:37:00.450928', '2019-07-24 09:37:00.450928'),
(3, 'LBA18AL0005', 'eyJpdiI6IkY3KytMVTRQSzNUMkx3eDlFZm1rU0E9PSIsInZhbHVlIjoiNEhBTGdZUDNScldwVHdZeHNMc2xBUT09IiwibWFjIjoiMDA0NDNiNjU4MWIzZDZjYWUwNzViMmVmZDA0ZWFiOTY5N2Q4OTFiNWUwYTE3Y2U3YmNhMGI4ODlkNzQzNDhjOSJ9', 1, '2019-07-24 09:52:05.478441', '2019-07-24 09:52:05.478441', '2019-07-24 09:52:05.478441'),
(4, 'LBa18OL0003', 'eyJpdiI6InZSNGpMcGowSTd1UHhESFV3OWZPVGc9PSIsInZhbHVlIjoiNkNvNG9Ca2FBcWNaVk8xdlZ0SHpYQT09IiwibWFjIjoiZTBhZWNlNzc3YmViMjNkYjBkNzhhNDFmNGY4MzliZWZkMDgwODgyNjE4YThhMGVkYTJkNjBkMTBkNmY2ZjIwMiJ9', 1, '2019-07-24 09:59:33.278917', '2019-07-24 09:59:33.278917', '2019-07-24 09:59:33.278917'),
(5, 'LBA18AL0003', 'eyJpdiI6IkgxMVQ5VHErTlg2dys4S21jYURcL2dRPT0iLCJ2YWx1ZSI6Ikl5NGQyU2JcL0d5UDFPS25aeTlRaDJRPT0iLCJtYWMiOiJkZTdkOGE5NTdiMGQ0NjI0MTcwODRjOWU1MzYwNDNiNThkMjZhNjZkOWQ5YTJkODY5ZmNmOTU5MzlkNjA0YTgxIn0=', 1, '2019-08-17 20:12:02.443773', '2019-08-17 20:12:02.443773', '2019-08-17 20:12:02.443773'),
(6, 'LBA18AL0006', 'eyJpdiI6InE0QnhcLytuREl5d28xMGorZjVUN2ZnPT0iLCJ2YWx1ZSI6ImJuNXpHZTlwRER0Z1F5T2pOdGR2OHc9PSIsIm1hYyI6IjczNGIwNGI4Y2YzOWU0YmI4MmExYWFkMjM1MjVkZDU4OTM2YmQ4ZDEzZGYzMjY0NGFjYjcyZTAyYmJhNGQwNzQifQ==', 1, '2019-08-17 20:18:13.147404', '2019-08-17 20:18:13.147404', '2019-08-17 20:18:13.147404'),
(7, 'LBA18AL0007', 'eyJpdiI6IjY0MDZsK1wvZGlTY1o2RW0yQ3cxeXFRPT0iLCJ2YWx1ZSI6ImgwRURtb0RZZjRlWjBSTW5paVpDcnc9PSIsIm1hYyI6ImZjMGFkZjI3Y2RlZjdkN2QzODhlN2ViYjM2YTVlZWM0NTAzZGJkYWEyNzUyYjVlMTRmZjNmYzFiOTk3YTc5M2MifQ==', 1, '2019-08-17 20:18:13.147404', '2019-08-17 20:18:13.147404', '2019-08-17 20:18:13.147404');

-- --------------------------------------------------------

--
-- Table structure for table `student_series_changes`
--

CREATE TABLE `student_series_changes` (
  `id` int(11) NOT NULL,
  `students_student_id` int(11) DEFAULT NULL,
  `series_code` varchar(10) NOT NULL,
  `series_series_code` varchar(10) NOT NULL,
  `users_user_id` int(11) DEFAULT NULL,
  `sequences_sequence_id` int(11) DEFAULT NULL,
  `academic_year` varchar(45) DEFAULT NULL,
  `sections_section_code` varchar(5) DEFAULT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `student_series_changes`
--

INSERT INTO `student_series_changes` (`id`, `students_student_id`, `series_code`, `series_series_code`, `users_user_id`, `sequences_sequence_id`, `academic_year`, `sections_section_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 6, 's2', '1', 1, 1, '2018/2019', 'en', NULL, NULL, NULL),
(2, 6, 's2', '1', 1, 1, '2018/2019', 'en', NULL, NULL, NULL),
(3, 6, 's1', 's1', NULL, 1, '2018/2019', 'en', NULL, NULL, NULL),
(4, 6, 's1', 's2', NULL, 1, '2018/2019', 'en', NULL, NULL, NULL),
(5, 12, 's2', 's1', NULL, 1, '2018/2019', 'en', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `subject_code` varchar(45) NOT NULL,
  `subject_title` varchar(100) NOT NULL,
  `coefficient` int(11) NOT NULL,
  `state` int(11) DEFAULT '1',
  `classes_class_code` varchar(10) NOT NULL,
  `subject_weight` float DEFAULT NULL,
  `sections_section_code` varchar(5) NOT NULL,
  `programs_program_code` varchar(5) NOT NULL,
  `academic_year` varchar(45) NOT NULL,
  `users_user_id` int(11) NOT NULL,
  `departments_department_id` int(11) NOT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `subject_code`, `subject_title`, `coefficient`, `state`, `classes_class_code`, `subject_weight`, `sections_section_code`, `programs_program_code`, `academic_year`, `users_user_id`, `departments_department_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'CHEMF1-206', 'Chemistry', 4, 1, 'fm1', 20, 'en', 'ol', '2018/2019', 1, 1, NULL, NULL, NULL),
(3, 'CHEMF2-207', 'CHEMISTRY', 4, 1, 'fm2', 20, 'en', 'ol', '', 1, 1, NULL, NULL, NULL),
(4, 'PHSls6-500', 'Physics', 4, 1, 'ls6', 20, 'en', 'al', '2018/2019', 1, 1, NULL, NULL, NULL),
(5, 'Bio-ls6 500', 'Biology', 4, 1, 'ls6', 20, 'en', 'al', '2018/2019', 1, 1, NULL, NULL, NULL),
(6, 'PMaths-ls6 500', 'Pure Mathematics', 4, 1, 'ls6', 20, 'en', 'al', '2018/2019', 1, 1, NULL, NULL, NULL),
(9, 'Hbio-FM1', 'Human Biology', 4, 1, 'fm1', 20, 'en', 'ol', '2018/2019', 1, 1, '2019-08-25 14:04:50.000000', '2019-08-25 14:04:50.000000', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subjects_has_scores`
--

CREATE TABLE `subjects_has_scores` (
  `id` int(11) NOT NULL,
  `students_student_id` int(11) NOT NULL,
  `sequences_sequence_id` int(11) NOT NULL,
  `subjects_subject_id` int(11) NOT NULL,
  `subject_score` float NOT NULL DEFAULT '0',
  `academic_year` varchar(45) NOT NULL,
  `submission_state` int(11) NOT NULL DEFAULT '0',
  `sections_section_code` varchar(10) NOT NULL,
  `users_user_id` int(11) DEFAULT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `subjects_has_scores`
--

INSERT INTO `subjects_has_scores` (`id`, `students_student_id`, `sequences_sequence_id`, `subjects_subject_id`, `subject_score`, `academic_year`, `submission_state`, `sections_section_code`, `users_user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 10, 1, 2, 17.7778, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(5, 11, 1, 2, 12.3333, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(6, 14, 1, 2, 15.5556, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(7, 4, 1, 1, 11.3333, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(8, 6, 1, 1, 12.6667, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(9, 12, 1, 1, 13.3333, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(10, 13, 1, 1, 6, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(11, 4, 1, 5, 12, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(12, 6, 1, 5, 14, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(13, 13, 1, 5, 4, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(14, 4, 1, 6, 5, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(15, 6, 1, 6, 8, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(16, 12, 1, 6, 17.5, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(17, 13, 1, 6, 20, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(18, 4, 1, 7, 16, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(19, 6, 1, 7, 12, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(20, 12, 1, 7, 9.6, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(21, 13, 1, 7, 14, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(22, 4, 1, 4, 4, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(23, 6, 1, 4, 6.66667, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(24, 12, 1, 4, 20, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(25, 13, 1, 4, 16.6667, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(26, 4, 2, 1, 7.2, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(27, 6, 2, 1, 9.4, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(28, 12, 2, 1, 11, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(29, 13, 2, 1, 11, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(30, 4, 2, 4, 15, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(31, 6, 2, 4, 9, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(32, 12, 2, 4, 12.5, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(33, 13, 2, 4, 10, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(34, 4, 2, 5, 6.5, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(35, 6, 2, 5, 5.5, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(36, 12, 2, 5, 15, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(37, 13, 2, 5, 4.5, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(38, 4, 2, 6, 10.5, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(39, 6, 2, 6, 15, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(40, 12, 2, 6, 20, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(41, 13, 2, 6, 10, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(42, 4, 2, 7, 11.1111, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(43, 6, 2, 7, 16, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(44, 12, 2, 7, 15.5556, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(45, 13, 2, 7, 15.5556, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(46, 4, 3, 1, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(47, 6, 3, 1, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(48, 13, 3, 1, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(49, 4, 3, 4, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(50, 6, 3, 4, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(51, 13, 3, 4, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(52, 4, 3, 5, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(53, 6, 3, 5, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(54, 12, 3, 5, 15, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(55, 13, 3, 5, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(56, 4, 3, 6, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(57, 6, 3, 6, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(58, 12, 3, 6, 10, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(59, 13, 3, 6, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(60, 4, 3, 7, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(61, 6, 3, 7, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(62, 12, 3, 7, 13.3333, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(63, 13, 3, 7, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(64, 4, 4, 1, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(65, 6, 4, 1, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(66, 12, 4, 1, 17.5, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(67, 13, 4, 1, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(68, 4, 4, 4, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(69, 6, 4, 4, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(70, 12, 4, 4, 4, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(71, 13, 4, 4, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(72, 4, 4, 5, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(73, 6, 4, 5, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(74, 12, 4, 5, 20, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(75, 13, 4, 5, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(76, 4, 4, 6, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(77, 6, 4, 6, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(78, 12, 4, 6, 11.1111, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(79, 13, 4, 6, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(80, 4, 4, 7, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(81, 6, 4, 7, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(82, 12, 4, 7, 8.88889, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(83, 13, 4, 7, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(84, 14, 1, 3, 15, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(85, 12, 1, 10, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(86, 13, 1, 10, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(87, 15, 1, 10, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(88, 10, 1, 9, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(89, 11, 1, 9, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL),
(90, 14, 1, 9, 0, '2018/2019', 1, 'en', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subject_series_changes`
--

CREATE TABLE `subject_series_changes` (
  `id` int(11) NOT NULL,
  `subjects_subject_id` int(11) DEFAULT NULL,
  `series_code` varchar(10) NOT NULL,
  `series_series_code` varchar(10) NOT NULL,
  `users_user_id` int(11) DEFAULT NULL,
  `sequences_sequence_id` int(11) DEFAULT NULL,
  `academic_year` varchar(45) DEFAULT NULL,
  `sections_section_code` varchar(5) DEFAULT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `subject_series_changes`
--

INSERT INTO `subject_series_changes` (`id`, `subjects_subject_id`, `series_code`, `series_series_code`, `users_user_id`, `sequences_sequence_id`, `academic_year`, `sections_section_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(5, 1, ' ', 's2', 1, 1, '2018/2019', 'en', NULL, NULL, NULL),
(6, 1, 's3', 's3', NULL, 1, '2018/2019', 'en', NULL, NULL, NULL),
(7, 1, 's1', 's1', NULL, 1, '2018/2019', 'en', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_teaches_subject`
--

CREATE TABLE `teacher_teaches_subject` (
  `id` int(11) NOT NULL,
  `users_user_id` int(11) NOT NULL,
  `subjects_subject_id` int(11) NOT NULL,
  `sequences_sequence_id` int(11) NOT NULL,
  `academic_year` varchar(45) NOT NULL,
  `assignee_id` int(11) NOT NULL,
  `sections_section_code` varchar(5) NOT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `teacher_teaches_subject`
--

INSERT INTO `teacher_teaches_subject` (`id`, `users_user_id`, `subjects_subject_id`, `sequences_sequence_id`, `academic_year`, `assignee_id`, `sections_section_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 2, 3, 1, '2018/2019', 0, 'en', NULL, NULL, NULL),
(32, 1, 2, 1, '2018/2019', 1, 'en', NULL, NULL, NULL),
(33, 1, 3, 1, '2018/2019', 1, 'en', NULL, NULL, NULL),
(34, 1, 4, 1, '2018/2019', 1, 'en', NULL, NULL, NULL),
(35, 1, 5, 1, '2018/2019', 1, 'en', NULL, NULL, NULL),
(36, 1, 6, 1, '2018/2019', 1, 'en', NULL, NULL, NULL),
(37, 1, 7, 1, '2018/2019', 1, 'en', NULL, NULL, NULL),
(38, 1, 8, 1, '2018/2019', 1, 'en', NULL, NULL, NULL),
(39, 1, 9, 1, '2018/2019', 1, 'en', NULL, NULL, NULL),
(40, 1, 10, 1, '2018/2019', 1, 'en', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `terms`
--

CREATE TABLE `terms` (
  `term_id` int(11) NOT NULL,
  `term_name` varchar(45) NOT NULL,
  `term_code` varchar(10) NOT NULL,
  `sections_section_code` varchar(3) NOT NULL,
  `users_user_id` int(11) NOT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `terms`
--

INSERT INTO `terms` (`term_id`, `term_name`, `term_code`, `sections_section_code`, `users_user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'First Term', 'ft', 'en', 1, NULL, '2019-07-24 22:49:27.000000', NULL),
(2, 'Second Term', 'st', 'en', 1, NULL, NULL, NULL),
(3, 'Third Term', 'tt', 'en', 1, NULL, NULL, NULL),
(4, 'Premiere Trimestre', 'prtr', 'fr', 1, '2019-07-28 16:02:10.000000', '2019-07-28 16:02:10.000000', NULL),
(5, 'Duexieme Trimestre', 'dutr', 'fr', 1, '2019-07-28 16:02:36.000000', '2019-07-28 16:02:36.000000', NULL),
(6, 'Troixieme Trimestre', 'trtr', 'fr', 1, '2019-07-28 16:02:53.000000', '2019-07-28 16:02:53.000000', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `term_averages`
--

CREATE TABLE `term_averages` (
  `id` int(11) NOT NULL,
  `terms_term_id` int(11) NOT NULL,
  `students_student_id` int(11) NOT NULL,
  `average` float NOT NULL,
  `academic_year` varchar(45) NOT NULL,
  `state` int(11) DEFAULT NULL,
  `classes_class_code` varchar(15) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `term_averages`
--

INSERT INTO `term_averages` (`id`, `terms_term_id`, `students_student_id`, `average`, `academic_year`, `state`, `classes_class_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 10, 4.45, '2018/2019', 1, 'fm1', '2019-09-07 11:11:48.211100', '2019-09-07 11:11:48.211100', '2019-09-07 11:11:48.211100'),
(2, 1, 11, 3.08, '2018/2019', 1, 'fm1', '2019-09-07 11:11:48.211100', '2019-09-07 11:11:48.211100', '2019-09-07 11:11:48.211100'),
(3, 1, 14, 5.09, '2018/2019', 1, 'fm1', '2019-09-07 11:11:48.211100', '2019-09-07 11:11:48.211100', '2019-09-07 11:11:48.211100'),
(4, 1, 14, 5.09, '2018/2019', 1, 'fm2', '2019-09-07 11:11:48.211100', '2019-09-07 11:11:48.211100', '2019-09-07 11:11:48.211100'),
(5, 1, 14, 5.09, '2018/2019', 1, 'fm4', '2019-09-07 11:11:48.211100', '2019-09-07 11:11:48.211100', '2019-09-07 11:11:48.211100'),
(6, 1, 4, 8.83, '2018/2019', 1, 'ls6', '2019-09-07 11:11:48.211100', '2019-09-07 11:11:48.211100', '2019-09-07 11:11:48.211100'),
(7, 1, 6, 9.7, '2018/2019', 1, 'ls6', '2019-09-07 11:11:48.211100', '2019-09-07 11:11:48.211100', '2019-09-07 11:11:48.211100'),
(8, 1, 12, 14.17, '2018/2019', 1, 'ls6', '2019-09-07 11:11:48.211100', '2019-09-07 11:11:48.211100', '2019-09-07 11:11:48.211100'),
(9, 1, 13, 10.86, '2018/2019', 1, 'ls6', '2019-09-07 11:11:48.211100', '2019-09-07 11:11:48.211100', '2019-09-07 11:11:48.211100'),
(10, 1, 15, 0, '2018/2019', 1, 'ls6', '2019-09-07 11:11:48.211100', '2019-09-07 11:11:48.211100', '2019-09-07 11:11:48.211100'),
(11, 1, 16, 0, '2018/2019', 1, 'ls6', '2019-09-07 11:11:48.211100', '2019-09-07 11:11:48.211100', '2019-09-07 11:11:48.211100'),
(12, 1, 6, 9.7, '2018/2019', 1, 'us6', '2019-09-07 11:11:48.211100', '2019-09-07 11:11:48.211100', '2019-09-07 11:11:48.211100');

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

CREATE TABLE `tests` (
  `test_id` int(11) NOT NULL,
  `subjects_subject_id` int(11) NOT NULL,
  `test_code` varchar(45) DEFAULT NULL,
  `test_name` varchar(255) NOT NULL,
  `test_weight` float NOT NULL,
  `sequences_sequence_id` int(11) NOT NULL,
  `academic_year` varchar(45) NOT NULL,
  `users_user_id` int(11) NOT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tests`
--

INSERT INTO `tests` (`test_id`, `subjects_subject_id`, `test_code`, `test_name`, `test_weight`, `sequences_sequence_id`, `academic_year`, `users_user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 'ch1', 'Chemistry One Test', 40, 1, '2018/2019', 1, '2019-08-03 14:04:11.000000', '2019-08-03 14:04:11.000000', NULL),
(2, 2, 'che', 'Chemistry test', 50, 1, '2018/2019', 1, NULL, NULL, NULL),
(3, 2, 'kj', 'Chemistry Also', 40, 2, '2018/2019', 1, NULL, NULL, NULL),
(4, 1, 'chem', 'Chemistry Test', 60, 1, '2018/2019', 1, '2019-08-03 21:36:53.000000', '2019-08-03 21:36:53.000000', NULL),
(5, 3, 'chet', 'Chemistry Test Form Two', 40, 1, '2018/2019', 1, '2019-08-06 06:26:01.000000', '2019-08-06 06:26:01.000000', NULL),
(6, 5, 'bio23', 'Bio One', 50, 1, '2018/2019', 1, '2019-08-12 21:17:17.000000', '2019-08-12 21:17:17.000000', NULL),
(7, 6, 'pm2', 'Pure Math', 40, 1, '2018/2019', 1, '2019-08-12 21:18:53.000000', '2019-08-12 21:18:53.000000', NULL),
(8, 7, 'fm20', 'fmath', 50, 1, '2018/2019', 1, '2019-08-12 21:19:49.000000', '2019-08-12 21:19:49.000000', NULL),
(9, 4, 'phy12', 'physics', 30, 1, '2018/2019', 1, '2019-08-12 21:31:26.000000', '2019-08-12 21:31:26.000000', NULL),
(10, 1, 'ete', 'Chemistry One Testr', 40, 2, '2018/2019', 1, '2019-08-13 17:17:03.000000', '2019-08-13 17:17:03.000000', NULL),
(11, 1, 'dss', 'CHesiskffjn', 60, 2, '2018/2019', 1, '2019-08-13 17:18:48.000000', '2019-08-13 17:18:48.000000', NULL),
(12, 4, 'lk', 'physics second se', 40, 2, '2018/2019', 1, '2019-08-13 17:21:06.000000', '2019-08-13 17:21:06.000000', NULL),
(13, 5, 'dh', 'Bio two', 40, 2, '2018/2019', 1, '2019-08-13 17:22:39.000000', '2019-08-13 17:22:39.000000', NULL),
(14, 6, 'pnm', 'Pmath', 40, 2, '2018/2019', 1, '2019-08-13 17:25:48.000000', '2019-08-13 17:25:48.000000', NULL),
(15, 7, 'jk', 'Pure Math', 45, 2, '2018/2019', 1, '2019-08-13 17:28:18.000000', '2019-08-13 17:28:18.000000', NULL),
(16, 1, 'hj', 'Chemistry One Testuy', 100, 3, '2018/2019', 1, '2019-08-14 22:15:08.000000', '2019-08-14 22:15:08.000000', NULL),
(17, 4, 'hj', 'physicsjj', 70, 3, '2018/2019', 1, '2019-08-14 22:15:56.000000', '2019-08-14 22:15:56.000000', NULL),
(18, 5, 'b', 'Bio Onej', 20, 3, '2018/2019', 1, '2019-08-14 22:16:36.000000', '2019-08-14 22:16:36.000000', NULL),
(19, 6, 'jj', 'Pure Mathj', 100, 3, '2018/2019', 1, '2019-08-14 22:17:24.000000', '2019-08-14 22:17:24.000000', NULL),
(20, 7, 'gty', 'fmathhj', 15, 3, '2018/2019', 1, '2019-08-14 22:18:26.000000', '2019-08-14 22:18:26.000000', NULL),
(21, 1, 'hhj', 'Chemistry One Testh', 40, 4, '2018/2019', 1, '2019-08-14 22:21:42.000000', '2019-08-14 22:21:42.000000', NULL),
(22, 4, '4g', 'physics4', 70, 4, '2018/2019', 1, '2019-08-14 22:22:35.000000', '2019-08-14 22:22:35.000000', NULL),
(23, 5, '89', 'Bio One4', 40, 4, '2018/2019', 1, '2019-08-14 22:23:22.000000', '2019-08-14 22:23:22.000000', NULL),
(24, 6, '45555', 'Pure Math', 90, 4, '2018/2019', 1, '2019-08-14 22:24:34.000000', '2019-08-14 22:24:34.000000', NULL),
(25, 7, '898', 'fmath', 90, 4, '2018/2019', 1, '2019-08-14 22:25:31.000000', '2019-08-14 22:25:31.000000', NULL),
(26, 2, 'jkuy', 'ttrju', 60, 4, '2018/2019', 1, '2019-08-23 08:54:55.000000', '2019-08-23 08:54:55.000000', NULL),
(27, 10, 'fmd', 'Further mathematics', 60, 1, '2018/2019', 1, '2019-08-29 15:57:35.000000', '2019-08-29 15:57:35.000000', NULL),
(28, 9, 'SJSF', 'AWIUIOWE', 62, 1, '2018/2019', 1, '2019-08-29 16:11:53.000000', '2019-08-29 16:11:53.000000', NULL),
(29, 8, 'sh', 'awigog', 80, 1, '2018/2019', 1, '2019-08-30 05:54:46.000000', '2019-08-30 05:54:46.000000', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tests_has_scores`
--

CREATE TABLE `tests_has_scores` (
  `id` int(11) NOT NULL,
  `tests_test_id` int(11) NOT NULL,
  `students_student_id` int(11) NOT NULL,
  `sequences_sequence_id` int(11) NOT NULL,
  `subjects_subject_id` int(11) NOT NULL,
  `test_score` float NOT NULL DEFAULT '0',
  `academic_year` varchar(45) NOT NULL,
  `users_user_id` int(11) DEFAULT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tests_has_scores`
--

INSERT INTO `tests_has_scores` (`id`, `tests_test_id`, `students_student_id`, `sequences_sequence_id`, `subjects_subject_id`, `test_score`, `academic_year`, `users_user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 10, 1, 2, 40, '2018/2019', 1, NULL, NULL, NULL),
(2, 1, 11, 1, 2, 20, '2018/2019', 1, NULL, NULL, NULL),
(3, 1, 14, 1, 2, 20, '2018/2019', 1, NULL, NULL, NULL),
(4, 2, 10, 1, 2, 40, '2018/2019', 1, NULL, NULL, NULL),
(5, 2, 14, 1, 2, 50, '2018/2019', 1, NULL, NULL, NULL),
(6, 2, 11, 1, 2, 35.5, '2018/2019', 1, NULL, NULL, NULL),
(7, 4, 12, 1, 1, 40, '2018/2019', 1, NULL, NULL, NULL),
(8, 4, 13, 1, 1, 18, '2018/2019', 1, NULL, NULL, NULL),
(9, 4, 4, 1, 1, 34, '2018/2019', 1, NULL, NULL, NULL),
(10, 4, 6, 1, 1, 38, '2018/2019', 1, NULL, NULL, NULL),
(11, 5, 14, 1, 3, 30, '2018/2019', 1, NULL, NULL, NULL),
(12, 6, 4, 1, 5, 30, '2018/2019', 1, NULL, NULL, NULL),
(13, 6, 6, 1, 5, 35, '2018/2019', 1, NULL, NULL, NULL),
(14, 6, 13, 1, 5, 10, '2018/2019', 1, NULL, NULL, NULL),
(15, 7, 4, 1, 6, 10, '2018/2019', 1, NULL, NULL, NULL),
(16, 7, 6, 1, 6, 16, '2018/2019', 1, NULL, NULL, NULL),
(17, 7, 12, 1, 6, 35, '2018/2019', 1, NULL, NULL, NULL),
(18, 7, 13, 1, 6, 40, '2018/2019', 1, NULL, NULL, NULL),
(19, 8, 4, 1, 7, 40, '2018/2019', 1, NULL, NULL, NULL),
(20, 8, 6, 1, 7, 30, '2018/2019', 1, NULL, NULL, NULL),
(21, 8, 12, 1, 7, 24, '2018/2019', 1, NULL, NULL, NULL),
(22, 8, 13, 1, 7, 35, '2018/2019', 1, NULL, NULL, NULL),
(23, 9, 4, 1, 4, 6, '2018/2019', 1, NULL, NULL, NULL),
(24, 9, 6, 1, 4, 10, '2018/2019', 1, NULL, NULL, NULL),
(25, 9, 12, 1, 4, 30, '2018/2019', 1, NULL, NULL, NULL),
(26, 9, 13, 1, 4, 25, '2018/2019', 1, NULL, NULL, NULL),
(27, 10, 4, 2, 1, 19, '2018/2019', 1, NULL, NULL, NULL),
(28, 10, 6, 2, 1, 17, '2018/2019', 1, NULL, NULL, NULL),
(29, 10, 12, 2, 1, 30, '2018/2019', 1, NULL, NULL, NULL),
(30, 10, 13, 2, 1, 30, '2018/2019', 1, NULL, NULL, NULL),
(31, 11, 4, 2, 1, 17, '2018/2019', 1, NULL, NULL, NULL),
(32, 11, 6, 2, 1, 30, '2018/2019', 1, NULL, NULL, NULL),
(33, 11, 12, 2, 1, 25, '2018/2019', 1, NULL, NULL, NULL),
(34, 11, 13, 2, 1, 25, '2018/2019', 1, NULL, NULL, NULL),
(35, 12, 4, 2, 4, 30, '2018/2019', 1, NULL, NULL, NULL),
(36, 12, 6, 2, 4, 18, '2018/2019', 1, NULL, NULL, NULL),
(37, 12, 12, 2, 4, 25, '2018/2019', 1, NULL, NULL, NULL),
(38, 12, 13, 2, 4, 20, '2018/2019', 1, NULL, NULL, NULL),
(39, 13, 4, 2, 5, 13, '2018/2019', 1, NULL, NULL, NULL),
(40, 13, 12, 2, 5, 30, '2018/2019', 1, NULL, NULL, NULL),
(41, 13, 13, 2, 5, 9, '2018/2019', 1, NULL, NULL, NULL),
(42, 13, 6, 2, 5, 11, '2018/2019', 1, NULL, NULL, NULL),
(43, 14, 4, 2, 6, 21, '2018/2019', 1, NULL, NULL, NULL),
(44, 14, 6, 2, 6, 30, '2018/2019', 1, NULL, NULL, NULL),
(45, 14, 12, 2, 6, 40, '2018/2019', 1, NULL, NULL, NULL),
(46, 14, 13, 2, 6, 20, '2018/2019', 1, NULL, NULL, NULL),
(47, 15, 4, 2, 7, 25, '2018/2019', 1, NULL, NULL, NULL),
(48, 15, 6, 2, 7, 36, '2018/2019', 1, NULL, NULL, NULL),
(49, 15, 12, 2, 7, 35, '2018/2019', 1, NULL, NULL, NULL),
(50, 15, 13, 2, 7, 35, '2018/2019', 1, NULL, NULL, NULL),
(51, 16, 4, 3, 1, 0, '2018/2019', 1, NULL, NULL, NULL),
(52, 16, 6, 3, 1, 0, '2018/2019', 1, NULL, NULL, NULL),
(53, 16, 13, 3, 1, 0, '2018/2019', 1, NULL, NULL, NULL),
(54, 17, 4, 3, 4, 0, '2018/2019', 1, NULL, NULL, NULL),
(55, 17, 6, 3, 4, 0, '2018/2019', 1, NULL, NULL, NULL),
(56, 17, 13, 3, 4, 0, '2018/2019', 1, NULL, NULL, NULL),
(57, 18, 4, 3, 5, 0, '2018/2019', 1, NULL, NULL, NULL),
(58, 18, 6, 3, 5, 0, '2018/2019', 1, NULL, NULL, NULL),
(59, 18, 12, 3, 5, 15, '2018/2019', 1, NULL, NULL, NULL),
(60, 18, 13, 3, 5, 0, '2018/2019', 1, NULL, NULL, NULL),
(61, 19, 4, 3, 6, 0, '2018/2019', 1, NULL, NULL, NULL),
(62, 19, 6, 3, 6, 0, '2018/2019', 1, NULL, NULL, NULL),
(63, 19, 12, 3, 6, 50, '2018/2019', 1, NULL, NULL, NULL),
(64, 19, 13, 3, 6, 0, '2018/2019', 1, NULL, NULL, NULL),
(65, 20, 4, 3, 7, 0, '2018/2019', 1, NULL, NULL, NULL),
(66, 20, 6, 3, 7, 0, '2018/2019', 1, NULL, NULL, NULL),
(67, 20, 12, 3, 7, 10, '2018/2019', 1, NULL, NULL, NULL),
(68, 20, 13, 3, 7, 0, '2018/2019', 1, NULL, NULL, NULL),
(69, 21, 4, 4, 1, 0, '2018/2019', 1, NULL, NULL, NULL),
(70, 21, 6, 4, 1, 0, '2018/2019', 1, NULL, NULL, NULL),
(71, 21, 12, 4, 1, 35, '2018/2019', 1, NULL, NULL, NULL),
(72, 21, 13, 4, 1, 0, '2018/2019', 1, NULL, NULL, NULL),
(73, 22, 4, 4, 4, 0, '2018/2019', 1, NULL, NULL, NULL),
(74, 22, 6, 4, 4, 0, '2018/2019', 1, NULL, NULL, NULL),
(75, 22, 12, 4, 4, 14, '2018/2019', 1, NULL, NULL, NULL),
(76, 22, 13, 4, 4, 0, '2018/2019', 1, NULL, NULL, NULL),
(77, 23, 4, 4, 5, 0, '2018/2019', 1, NULL, NULL, NULL),
(78, 23, 6, 4, 5, 0, '2018/2019', 1, NULL, NULL, NULL),
(79, 23, 12, 4, 5, 40, '2018/2019', 1, NULL, NULL, NULL),
(80, 23, 13, 4, 5, 0, '2018/2019', 1, NULL, NULL, NULL),
(81, 24, 4, 4, 6, 0, '2018/2019', 1, NULL, NULL, NULL),
(82, 24, 6, 4, 6, 0, '2018/2019', 1, NULL, NULL, NULL),
(83, 24, 13, 4, 6, 0, '2018/2019', 1, NULL, NULL, NULL),
(84, 24, 12, 4, 6, 50, '2018/2019', 1, NULL, NULL, NULL),
(85, 25, 4, 4, 7, 0, '2018/2019', 1, NULL, NULL, NULL),
(86, 25, 6, 4, 7, 0, '2018/2019', 1, NULL, NULL, NULL),
(87, 25, 13, 4, 7, 0, '2018/2019', 1, NULL, NULL, NULL),
(88, 25, 12, 4, 7, 40, '2018/2019', 1, NULL, NULL, NULL),
(89, 27, 12, 1, 10, 0, '2018/2019', 1, NULL, NULL, NULL),
(90, 27, 13, 1, 10, 0, '2018/2019', 1, NULL, NULL, NULL),
(91, 27, 15, 1, 10, 0, '2018/2019', 1, NULL, NULL, NULL),
(92, 28, 10, 1, 9, 0, '2018/2019', 1, NULL, NULL, NULL),
(93, 28, 11, 1, 9, 0, '2018/2019', 1, NULL, NULL, NULL),
(94, 28, 14, 1, 9, 0, '2018/2019', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(45) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `office_address` varchar(45) DEFAULT NULL,
  `phone_number` varchar(50) NOT NULL,
  `profile` varchar(255) DEFAULT NULL,
  `position` varchar(45) NOT NULL,
  `type` varchar(15) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '0',
  `academic_state` int(11) NOT NULL DEFAULT '1',
  `password` varchar(255) NOT NULL,
  `lang` varchar(5) NOT NULL,
  `remember_me` varchar(45) DEFAULT NULL,
  `roles_role_id` int(11) DEFAULT '0',
  `users_user_id` int(11) NOT NULL DEFAULT '0',
  `programs_program_code` varchar(5) NOT NULL,
  `sections_section_code` varchar(3) NOT NULL DEFAULT 'en',
  `departments_department_id` int(11) NOT NULL,
  `created_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `full_name`, `email`, `address`, `office_address`, `phone_number`, `profile`, `position`, `type`, `active`, `academic_state`, `password`, `lang`, `remember_me`, `roles_role_id`, `users_user_id`, `programs_program_code`, `sections_section_code`, `departments_department_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'EWANG', 'EWANG CLARKSON', 'ewangclarks@hotmail.com', 'malingo', 'ROOM 101', '673676301', 'images/avatars/clarkson.jpg', 'Vice Principal', 'Full Time', 0, 1, '$2y$10$8/b8tnNXEuCLt97XT/CskOQ4UUCGt1CWTvFyYig0GNoj4ObTPXnJy', 'en', NULL, 1, 0, 'al', 'en', 1, '2019-06-23 23:00:00.000000', '2019-10-07 11:21:42.000000', '2019-06-25 23:00:00.000000'),
(2, 'nde', 'nde yanick che', 'nde@gmail.com', 'douala', 'nort 8', '673676302', '/images/avatars/1.jpg', 'Teacher', 'Full Time', 0, 1, '$2y$10$NT9zp8tluYQ4pup.cGiqJOry4AWjF.mee7eCeRTUQPf/VrvcdWbzO', 'fr', NULL, 0, 1, 'al', 'fr', 1, NULL, '2019-08-05 09:53:59.000000', NULL),
(3, 'Noitick', 'Noitick gegang', 'vanes@gmail.com', 'Bonaberi,Douala', 'room 102', '338588858835', '/images/avatars/default_profile.png', 'Teacher', 'Full Time', 0, 1, '$2y$10$D1VGQ5shq4aNYCVpKcPbgeUIwa6vBhFT4DYR9Wd.nRq1i5p8LyVqO', 'en', NULL, 0, 1, 'al', 'en', 1, '2019-06-29 12:50:40.000000', '2019-09-11 12:45:55.000000', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_years`
--
ALTER TABLE `academic_years`
  ADD PRIMARY KEY (`academic_year_id`),
  ADD UNIQUE KEY `academic_year_id_UNIQUE` (`academic_year_id`);

--
-- Indexes for table `audit_academic_setting_actions`
--
ALTER TABLE `audit_academic_setting_actions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `audit_manage_academic_level_actions`
--
ALTER TABLE `audit_manage_academic_level_actions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `audit_manage_sequence_actions`
--
ALTER TABLE `audit_manage_sequence_actions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `audit_manage_term_actions`
--
ALTER TABLE `audit_manage_term_actions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `audit_student_actions`
--
ALTER TABLE `audit_student_actions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `audit_subject_actions`
--
ALTER TABLE `audit_subject_actions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `audit_user_actions`
--
ALTER TABLE `audit_user_actions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_id_UNIQUE` (`category_id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`),
  ADD UNIQUE KEY `class_id_UNIQUE` (`class_id`),
  ADD UNIQUE KEY `class_code_UNIQUE` (`class_code`);

--
-- Indexes for table `classes_has_students`
--
ALTER TABLE `classes_has_students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `classes_has_subjects`
--
ALTER TABLE `classes_has_subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`),
  ADD UNIQUE KEY `department_id_UNIQUE` (`department_id`);

--
-- Indexes for table `exam_settings`
--
ALTER TABLE `exam_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `final_marks`
--
ALTER TABLE `final_marks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `matricule_settings`
--
ALTER TABLE `matricule_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `privileges`
--
ALTER TABLE `privileges`
  ADD PRIMARY KEY (`privilege_id`),
  ADD UNIQUE KEY `privilege_id_UNIQUE` (`privilege_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`program_id`),
  ADD UNIQUE KEY `program_code_UNIQUE` (`program_code`);

--
-- Indexes for table `publish_status`
--
ALTER TABLE `publish_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_id_UNIQUE` (`role_id`);

--
-- Indexes for table `roles_has_privileges`
--
ALTER TABLE `roles_has_privileges`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`section_id`),
  ADD UNIQUE KEY `section_id_UNIQUE` (`section_id`),
  ADD UNIQUE KEY `section_code_UNIQUE` (`section_code`);

--
-- Indexes for table `sequences`
--
ALTER TABLE `sequences`
  ADD PRIMARY KEY (`sequence_id`),
  ADD UNIQUE KEY `sequence_id_UNIQUE` (`sequence_id`);

--
-- Indexes for table `sequence_averages`
--
ALTER TABLE `sequence_averages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `series`
--
ALTER TABLE `series`
  ADD PRIMARY KEY (`series_id`),
  ADD UNIQUE KEY `series_id_UNIQUE` (`series_id`);

--
-- Indexes for table `series_has_students`
--
ALTER TABLE `series_has_students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `series_has_subjects`
--
ALTER TABLE `series_has_subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD UNIQUE KEY `setting_id_UNIQUE` (`setting_id`);

--
-- Indexes for table `sms_notifications`
--
ALTER TABLE `sms_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `tudent_id_UNIQUE` (`student_id`);

--
-- Indexes for table `student_accounts`
--
ALTER TABLE `student_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_series_changes`
--
ALTER TABLE `student_series_changes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`),
  ADD UNIQUE KEY `subject_id_UNIQUE` (`subject_id`);

--
-- Indexes for table `subjects_has_scores`
--
ALTER TABLE `subjects_has_scores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `subject_series_changes`
--
ALTER TABLE `subject_series_changes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `teacher_teaches_subject`
--
ALTER TABLE `teacher_teaches_subject`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `terms`
--
ALTER TABLE `terms`
  ADD PRIMARY KEY (`term_id`);

--
-- Indexes for table `term_averages`
--
ALTER TABLE `term_averages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`test_id`),
  ADD UNIQUE KEY `test_id_UNIQUE` (`test_id`);

--
-- Indexes for table `tests_has_scores`
--
ALTER TABLE `tests_has_scores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_id_UNIQUE` (`user_id`),
  ADD UNIQUE KEY `phone_number_UNIQUE` (`phone_number`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_years`
--
ALTER TABLE `academic_years`
  MODIFY `academic_year_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `audit_academic_setting_actions`
--
ALTER TABLE `audit_academic_setting_actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `audit_manage_academic_level_actions`
--
ALTER TABLE `audit_manage_academic_level_actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `audit_manage_sequence_actions`
--
ALTER TABLE `audit_manage_sequence_actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `audit_manage_term_actions`
--
ALTER TABLE `audit_manage_term_actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `audit_student_actions`
--
ALTER TABLE `audit_student_actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `audit_subject_actions`
--
ALTER TABLE `audit_subject_actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `audit_user_actions`
--
ALTER TABLE `audit_user_actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `classes_has_students`
--
ALTER TABLE `classes_has_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `classes_has_subjects`
--
ALTER TABLE `classes_has_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `exam_settings`
--
ALTER TABLE `exam_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `final_marks`
--
ALTER TABLE `final_marks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;
--
-- AUTO_INCREMENT for table `matricule_settings`
--
ALTER TABLE `matricule_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `privileges`
--
ALTER TABLE `privileges`
  MODIFY `privilege_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `program_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `publish_status`
--
ALTER TABLE `publish_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `roles_has_privileges`
--
ALTER TABLE `roles_has_privileges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=608;
--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `sequences`
--
ALTER TABLE `sequences`
  MODIFY `sequence_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `sequence_averages`
--
ALTER TABLE `sequence_averages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `series`
--
ALTER TABLE `series`
  MODIFY `series_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `series_has_students`
--
ALTER TABLE `series_has_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `series_has_subjects`
--
ALTER TABLE `series_has_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `sms_notifications`
--
ALTER TABLE `sms_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `student_accounts`
--
ALTER TABLE `student_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `student_series_changes`
--
ALTER TABLE `student_series_changes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `subjects_has_scores`
--
ALTER TABLE `subjects_has_scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;
--
-- AUTO_INCREMENT for table `subject_series_changes`
--
ALTER TABLE `subject_series_changes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `teacher_teaches_subject`
--
ALTER TABLE `teacher_teaches_subject`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT for table `terms`
--
ALTER TABLE `terms`
  MODIFY `term_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `term_averages`
--
ALTER TABLE `term_averages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `tests`
--
ALTER TABLE `tests`
  MODIFY `test_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `tests_has_scores`
--
ALTER TABLE `tests_has_scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
