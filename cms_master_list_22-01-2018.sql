-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 22, 2019 at 03:41 PM
-- Server version: 5.7.17
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `msphitec_hrms`
--

-- --------------------------------------------------------

--
-- Table structure for table `cms_master_list`
--

CREATE TABLE `cms_master_list` (
  `id` int(10) UNSIGNED NOT NULL,
  `descr` varchar(100) NOT NULL,
  `category_id` tinyint(3) DEFAULT NULL,
  `no_of_days` int(11) DEFAULT NULL,
  `field1` varchar(100) DEFAULT NULL,
  `field2` bigint(20) DEFAULT NULL,
  `field3` bigint(20) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_by` int(5) UNSIGNED DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` int(5) UNSIGNED DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cms_master_list`
--

INSERT INTO `cms_master_list` (`id`, `descr`, `category_id`, `no_of_days`, `field1`, `field2`, `field3`, `is_active`, `created_by`, `created_date`, `updated_by`, `updated_date`) VALUES
(1, 'Admin', 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(2, 'Outsourced', 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(4, 'Medical Certificate', 2, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL),
(5, 'Medical Bills', 2, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(7, 'Claims', 2, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(8, 'Active', 3, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(9, 'Resigned', 3, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(13, 'Permanent', 6, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(14, 'Contract', 6, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(15, 'Transport Allowance', 7, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(16, 'Hand Phone Allowance', 7, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(18, 'Other Allowance', 7, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(20, '1 Week', 8, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(21, '2 Weeks', 8, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(22, '3 Weeks', 8, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(23, '1 Month', 8, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(24, '2 Months', 8, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(25, '3 Months', 8, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(26, 'New Contract', 9, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(27, 'Extension Contract', 9, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(28, 'Single', 10, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(29, 'Married', 10, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(30, 'Divorced', 10, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(31, 'Widowed', 10, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(32, 'Male', 11, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(33, 'Female', 11, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(34, 'Permenant', 12, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(35, 'Contract', 12, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(36, 'Short Term', 12, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(37, 'Monthly', 13, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(38, 'Start Date', 13, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(39, 'Full Month', 14, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(40, 'No. of Working Days(Month)', 14, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(41, 'Others', 14, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(42, 'Internal', 15, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(43, 'External', 15, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(44, 'IT Accounts Executive ', 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(45, 'Recruitment ', 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(46, 'IT ', 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(47, 'Annual Leave', 16, 12, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(48, 'Medical Leave', 16, 12, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(49, 'Payroll ', 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(52, 'MIMOS', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(53, 'CIMB Bank Berhad', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(54, 'Mesiniaga Berhad', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(55, 'Emergency Leave', 16, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(57, 'Vice President - Sales', 17, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(58, 'Human Resource Department', 17, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(59, 'Finance Director', 17, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(60, 'CEO', 17, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(61, 'Human Resource - Visa & EP', 17, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(62, 'Theta Edge', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(63, 'Project Leave', 16, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(64, 'Recruiter', 17, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(65, 'Business Development Manager', 17, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(66, 'Sales', 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(67, 'General Skills', 19, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(68, 'Specific Skills', 19, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(69, 'MSP(On Bench)', 12, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(70, 'Laptop', 20, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(71, 'Antivirus', 20, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(72, 'Windows', 20, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(73, 'Linux', 20, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(74, 'MS-Office', 20, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(75, 'Mouse', 20, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(76, 'Limited Key/Serial', 21, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(77, 'Unlimited Key/Serial', 21, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(78, 'Limited Warranty', 21, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(79, 'Unlimited Warranty', 21, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(80, 'WEBE DIGITAL ', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(81, 'Telekom Malaysia', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(82, 'Maxis Telecommunication Berhad', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(83, 'RHB', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(84, 'Digi Telecommunication Bhd', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(85, 'Sime Darby (GSC)', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(86, 'Sime Darby (Property)', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(87, 'Maybank', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(88, 'Outsourcing', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(89, 'Eastspring Investment', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(90, 'YTL Communications', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(91, 'Finance', 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(92, 'Alliance Bank Malaysia Bhd', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(93, 'Deutsche Bank (M) Bhd', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(94, 'Bank of Tokyo-Mitsubishi UFJ (M) Berhad', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(95, 'Bank of Tokyo-MUFG', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(96, 'Hong Leong Bank', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(97, 'FXJS', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(98, 'Payments Network Malaysia Sdn Bhd', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(99, 'Talent Corporation Malaysia Berhad', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(100, 'Euronet/IME SDN BHD', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(101, 'Majesco', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(102, 'Persistent Malaysia Sdn Bhd', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(103, 'Others', 2, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(105, 'prudential bsn takaful', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(106, 'On Target Marketing Solutions Sdn Bhd', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(107, 'Mobipromo Group (Principle Partner)', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(108, 'prubsn takaful', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(109, 'e-soluware', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(110, 'Hong Leong Assurance', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(111, 'Petronas', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(112, 'Banking', 22, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(113, 'Telecommunication', 22, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(114, 'Technology', 22, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(115, 'Traveling expenses', 23, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(116, 'Entertainment', 23, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(117, 'Traveling expenses', 23, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL),
(118, 'Entertainment', 23, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL),
(119, 'Accommodation', 23, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(120, 'Gift', 23, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(121, 'Upkeep Computer', 23, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(122, 'Printing & Stationery', 23, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(123, 'Meeting New Client', 24, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(124, 'Proposal or TOB Submission', 24, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(125, 'Meeting Consultant at Client Place', 24, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(126, 'Others', 24, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(127, 'IT Department - Product & Project', 17, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(128, 'Other', 20, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(129, 'Finance Department', 17, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(130, 'GLC / MNC', 22, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(131, 'Services', 22, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(132, 'Status Update Meeting ', 24, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(133, 'Information Sharing Meeting', 24, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(134, 'Decision Making Meeting', 24, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(135, 'Problem Solving Meeting', 24, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(136, 'Innovation Meeting', 24, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(137, 'Team Building Meeting', 24, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(138, 'Project Meeting', 24, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(139, 'Collaborative Meeting', 24, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(140, 'Emergency Meeting', 24, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(141, 'Motivational Meeting', 24, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(142, 'Administration ', 17, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(143, 'Monster', 27, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(144, 'Colleague', 26, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(145, 'Friend', 26, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(146, 'Relative', 26, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(147, 'Ex-Employer', 26, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(148, 'RightCLIQ Solutions (M) Sdn Bhd', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(149, 'New Appointment', 28, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(150, 'Took Place', 28, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(151, 'Follow Up', 28, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(152, 'Postponed', 28, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(153, 'Cancelled', 28, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(154, 'KCC', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(155, 'Client', 27, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(156, 'HPE', 18, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(157, 'Internal', 27, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(158, 'Architect', 29, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(159, 'Consultant', 29, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(160, 'Company Assets', 30, NULL, '142', 1, 2, 1, NULL, NULL, NULL, NULL),
(161, 'Salary', 30, NULL, '59', 1, 2, 1, NULL, NULL, NULL, NULL),
(162, 'Leave', 30, NULL, '58', 1, 2, 1, NULL, NULL, NULL, NULL),
(163, 'Processing Status', 30, NULL, '58,61', 1, 2, 1, NULL, NULL, NULL, NULL),
(164, 'Document Submission', 30, NULL, '58,61,142', 1, 2, 1, NULL, NULL, NULL, NULL),
(165, 'Allowances', 30, NULL, '59', 1, 2, 1, NULL, NULL, NULL, NULL),
(166, 'Others', 30, NULL, '58,60,129,142', 1, 2, 1, NULL, NULL, NULL, NULL),
(167, 'Open', 31, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(168, 'Pending', 31, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(169, 'Resolved', 31, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(170, 'Closed', 31, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(171, 'Dependent Pass', 32, NULL, '125', 250, NULL, 1, NULL, NULL, NULL, NULL),
(172, 'Annual Medical insurance per Dependent', 32, NULL, '50', 100, NULL, 1, NULL, NULL, NULL, NULL),
(173, 'Annual Medical Outpatient Claim per Dependent ', 32, NULL, '25', 50, NULL, 1, NULL, NULL, NULL, NULL),
(174, 'Draft', 33, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(175, 'In Progress', 33, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(176, 'Approval Stage', 33, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(177, 'Offer Letter Sent', 33, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(178, 'Candidate Replied', 33, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(179, 'Closed', 33, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(180, 'TIMEOTCOM BERHAD', 18, NULL, NULL, NULL, 0, 1, NULL, NULL, NULL, NULL),
(181, 'AmMet Life', 18, NULL, NULL, NULL, 0, 1, NULL, NULL, NULL, NULL),
(182, '7Eleven', 18, NULL, NULL, NULL, 0, 1, NULL, NULL, NULL, NULL),
(183, 'Swift', 18, NULL, NULL, NULL, 0, 1, NULL, NULL, NULL, NULL),
(184, 'Touch N Go', 18, NULL, NULL, NULL, 0, 1, NULL, NULL, NULL, NULL),
(185, 'MSP Hitect (Internal)', 18, NULL, NULL, NULL, 0, 1, NULL, NULL, NULL, NULL),
(186, 'On Boarding', 30, NULL, '58,59,61,142', 24, 2, 1, NULL, NULL, NULL, NULL),
(187, 'STAR PUBLICATIONS', 18, NULL, NULL, NULL, 0, 1, NULL, NULL, NULL, NULL),
(188, 'Sales', 29, NULL, NULL, NULL, 0, 1, NULL, NULL, NULL, NULL),
(189, 'Collect Documents', 24, NULL, NULL, NULL, 0, 1, NULL, NULL, NULL, NULL),
(190, 'Submit Documents ', 24, NULL, NULL, NULL, 0, 1, NULL, NULL, NULL, NULL),
(191, 'Weekly Sales Report', 2, NULL, NULL, NULL, 0, 1, NULL, NULL, NULL, NULL),
(192, 'Weekly Recruitment Report', 2, NULL, NULL, NULL, 0, 1, NULL, NULL, NULL, NULL),
(193, 'Offer Turn Down', 33, NULL, NULL, NULL, 0, 1, NULL, NULL, NULL, NULL),
(194, 'Unpaid Leave', 16, 0, NULL, NULL, 0, 1, NULL, NULL, NULL, NULL),
(195, 'Medical Claims', 23, NULL, NULL, NULL, 0, 1, NULL, NULL, NULL, NULL),
(196, 'Technical', 30, NULL, '60,127', 0, 0, 1, NULL, NULL, NULL, NULL),
(197, 'Draft', 34, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(198, 'Sent for verification/approval', 34, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(199, 'Verified', 34, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(200, 'Approved', 34, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(201, 'Document Sent', 34, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(202, 'Quotation', 35, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(203, 'Purchase Order', 35, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(204, 'Draft', 36, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(205, 'Completed', 36, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(206, 'Master Agreement', 37, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(207, 'PO', 37, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(208, 'Addendum', 37, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(209, 'Annexure', 37, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(210, 'Cancelled', 33, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(211, 'Candidate Rejected', 33, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(212, 'Offer Accepted', 33, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(213, 'Sent for verification/approval', 36, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(214, 'Raise Invoice Request', 39, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(215, 'Payment Request', 39, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(216, 'Advance / Loan Request', 39, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(217, 'Asset Request', 39, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(218, 'Draft', 40, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(219, 'Sent for verification/approval', 40, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(220, 'Completed', 40, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(221, '1 month', 5, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(222, '3 months', 5, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cms_master_list`
--
ALTER TABLE `cms_master_list`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cms_master_list`
--
ALTER TABLE `cms_master_list`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
