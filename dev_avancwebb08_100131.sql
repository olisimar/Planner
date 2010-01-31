-- phpMyAdmin SQL Dump
-- version 3.2.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 31, 2010 at 03:47 PM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dev_avancwebb08`
--

-- --------------------------------------------------------

--
-- Table structure for table `descriptions`
--

DROP TABLE IF EXISTS `descriptions`;
CREATE TABLE IF NOT EXISTS `descriptions` (
  `user_id` int(10) unsigned NOT NULL,
  `firstname` varchar(30) COLLATE utf8_bin NOT NULL,
  `lastname` varchar(30) COLLATE utf8_bin NOT NULL,
  `email` varchar(80) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `descriptions`
--

INSERT INTO `descriptions` (`user_id`, `firstname`, `lastname`, `email`, `description`) VALUES
(9, 'Rickard', 'Lund', 'natural_blindfold@hotmail.com', 'Thing'),
(11, 'Joakim', 'Molin', 'jocke@swing.se', 'Greenhouse has gas!'),
(12, 'Werner', 'Johansson', 'tux@gmail.com', 'Sweeepeeeer!');

-- --------------------------------------------------------

--
-- Table structure for table `parts_to_tasks`
--

DROP TABLE IF EXISTS `parts_to_tasks`;
CREATE TABLE IF NOT EXISTS `parts_to_tasks` (
  `task_id` int(11) unsigned NOT NULL,
  `project_part_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`task_id`,`project_part_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Which task to what part, one task can be in many parts as we';

--
-- Dumping data for table `parts_to_tasks`
--


-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'if this project has a parent',
  `status` char(1) COLLATE utf8_bin NOT NULL DEFAULT 'A' COMMENT 'A=Active, D=Deactivated, S=Secret',
  `name` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT 'Unnamed Project',
  `description` text COLLATE utf8_bin NOT NULL COMMENT 'Describe the projects goal(s)',
  `forum_id` int(11) unsigned DEFAULT NULL,
  `unit_name` varchar(15) COLLATE utf8_bin NOT NULL DEFAULT 'hours',
  `unit_value` int(3) NOT NULL DEFAULT '1' COMMENT 'Will be converted against hours.',
  `start_at` date NOT NULL,
  `end_at` date DEFAULT NULL,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Whom is responsible for this project',
  `customer` text COLLATE utf8_bin COMMENT 'Whom has contracted this project.',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `parent` (`parent`,`forum_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='This is the top entity for projects, tasks, sprints and proj' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `parent`, `status`, `name`, `description`, `forum_id`, `unit_name`, `unit_value`, `start_at`, `end_at`, `user_id`, `customer`) VALUES
(1, 0, 'A', 'Project: Test 1', 'Just to see that I can select a certain a project and then add/alter and remove from it.', 0, 'hours', 1, '2009-10-23', '2009-11-11', 1, 'Werner Johansson, the grumpy coder.'),
(2, 0, 'A', 'Project: remember', 'Made to remember a lot of stuff.', NULL, 'minutes', 90, '2009-10-22', '2009-11-11', 2, 'Rickard Lund, the blissfull student.');

-- --------------------------------------------------------

--
-- Table structure for table `project_members`
--

DROP TABLE IF EXISTS `project_members`;
CREATE TABLE IF NOT EXISTS `project_members` (
  `user_id` int(11) unsigned NOT NULL COMMENT 'Who works on',
  `project_id` int(11) unsigned NOT NULL COMMENT 'on what project',
  `role` enum('ProjectManager','SprintManager','PartManager','Member','ProductOwner') COLLATE utf8_bin NOT NULL COMMENT 'What role in the project do you have?[enum]',
  PRIMARY KEY (`user_id`,`project_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Members of a project and their roles, drawn from the availab';

--
-- Dumping data for table `project_members`
--

INSERT INTO `project_members` (`user_id`, `project_id`, `role`) VALUES
(1, 1, 'ProjectManager'),
(2, 1, 'SprintManager'),
(1, 2, 'Member');

-- --------------------------------------------------------

--
-- Table structure for table `project_parts`
--

DROP TABLE IF EXISTS `project_parts`;
CREATE TABLE IF NOT EXISTS `project_parts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent` int(11) unsigned NOT NULL,
  `project_id` int(11) unsigned NOT NULL,
  `name` tinytext COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `user_id` int(11) unsigned DEFAULT '0' COMMENT 'Whom is responsible for this project part',
  `customer` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `parent` (`parent`,`project_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='A deliverable part of a project. May have children or parent' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `project_parts`
--


-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip_adress` varchar(25) COLLATE utf8_bin NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=13 ;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`user_id`, `ip_adress`, `created_at`) VALUES
(12, '127.0.0.1', '2009-10-16 13:13:27'),
(11, '127.0.0.1', '2009-10-16 13:09:18'),
(9, '127.0.0.1', '2009-10-16 13:48:18');

-- --------------------------------------------------------

--
-- Table structure for table `sprints`
--

DROP TABLE IF EXISTS `sprints`;
CREATE TABLE IF NOT EXISTS `sprints` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) unsigned NOT NULL COMMENT 'If we have no project this can''t exist.',
  `name` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT 'No Name Defined',
  `description` text COLLATE utf8_bin NOT NULL,
  `user_id` int(11) unsigned DEFAULT '0' COMMENT 'Whom is responsible for this sprint',
  `start_at` date NOT NULL,
  `end_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='This is a weak entity, without a project this can''t exist' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `sprints`
--

INSERT INTO `sprints` (`id`, `project_id`, `name`, `description`, `user_id`, `start_at`, `end_at`) VALUES
(1, 1, 'First Sprint', 'To get the first flies of the wall.', 1, '2009-10-25', '2009-10-31'),
(2, 1, 'Second Sprint', 'The swat all the flies in midair', 1, '2009-11-01', '2009-11-11');

-- --------------------------------------------------------

--
-- Table structure for table `sprint_planning`
--

DROP TABLE IF EXISTS `sprint_planning`;
CREATE TABLE IF NOT EXISTS `sprint_planning` (
  `sprint_id` int(11) NOT NULL COMMENT 'Which sprint',
  `project_id` int(11) NOT NULL COMMENT 'Which project',
  `task_id` int(11) NOT NULL COMMENT 'Which task',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Whom if anyone is responsible for this task.',
  PRIMARY KEY (`sprint_id`,`project_id`,`task_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='For sprint planning purposes, since sprint is weak project_i';

--
-- Dumping data for table `sprint_planning`
--

INSERT INTO `sprint_planning` (`sprint_id`, `project_id`, `task_id`, `user_id`) VALUES
(1, 1, 1, 1),
(1, 1, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent` int(11) unsigned NOT NULL DEFAULT '0',
  `project_id` int(11) NOT NULL COMMENT 'What project is this task apart of.',
  `name` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT 'No Name Defined' COMMENT 'Descriptive names please.',
  `description` text COLLATE utf8_bin NOT NULL,
  `estimated_units` tinyint(4) NOT NULL,
  `start_at` date NOT NULL,
  `start_user_id` int(11) unsigned NOT NULL COMMENT 'Whom started this task.',
  `end_at` date NOT NULL,
  `close_user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Whom closed it, history purposes. 0 for open.',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tasks within a project. May be a part of sprint, project par' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `parent`, `project_id`, `name`, `description`, `estimated_units`, `start_at`, `start_user_id`, `end_at`, `close_user_id`) VALUES
(1, 0, 1, 'Get usecases worked up...', 'Usecase for all things that needs to get done...', 8, '2009-10-25', 1, '2009-10-26', 0),
(2, 0, 1, 'Get first view worked up...', 'Make sure the menu follow the behaviour wanted as well as the info page. Context menu if possible.', 16, '2009-10-26', 1, '2009-10-28', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tasks_to_members`
--

DROP TABLE IF EXISTS `tasks_to_members`;
CREATE TABLE IF NOT EXISTS `tasks_to_members` (
  `user_id` int(11) unsigned NOT NULL COMMENT 'Drawn from table:project_members',
  `task_id` int(11) unsigned NOT NULL COMMENT 'id drawn from table:task',
  PRIMARY KEY (`user_id`,`task_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relating tasks to members of project, no need to have a task';

--
-- Dumping data for table `tasks_to_members`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(40) COLLATE utf8_bin NOT NULL,
  `password` varchar(128) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'test', 'tset'),
(2, 'user', 'resu');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_projects`
--
DROP VIEW IF EXISTS `view_projects`;
CREATE TABLE IF NOT EXISTS `view_projects` (
`id` int(11) unsigned
,`project_name` varchar(50)
,`sprint_id` int(11) unsigned
,`sprint_name` varchar(50)
,`sprint_end` date
,`part_id` int(11) unsigned
,`part_name` tinytext
,`task_id` int(11) unsigned
,`task_name` varchar(50)
,`user_id` int(11) unsigned
,`user_name` varchar(40)
);
-- --------------------------------------------------------

--
-- Structure for view `view_projects`
--
DROP TABLE IF EXISTS `view_projects`;

CREATE ALGORITHM=UNDEFINED DEFINER=`avancwebb08`@`localhost` SQL SECURITY DEFINER VIEW `view_projects` AS select `p`.`id` AS `id`,`p`.`name` AS `project_name`,`s`.`id` AS `sprint_id`,`s`.`name` AS `sprint_name`,`s`.`end_at` AS `sprint_end`,`pp`.`id` AS `part_id`,`pp`.`name` AS `part_name`,`t`.`id` AS `task_id`,`t`.`name` AS `task_name`,`u`.`id` AS `user_id`,`u`.`username` AS `user_name` from (((((((`tasks` `t` left join `projects` `p` on((`t`.`project_id` = `p`.`id`))) left join `sprint_planning` `sp` on((`sp`.`task_id` = `t`.`id`))) left join `sprints` `s` on((`sp`.`sprint_id` = `s`.`id`))) left join `parts_to_tasks` `pt` on((`pt`.`task_id` = `t`.`id`))) left join `project_parts` `pp` on((`pt`.`project_part_id` = `pp`.`id`))) left join `tasks_to_members` `tm` on((`tm`.`task_id` = `t`.`id`))) left join `users` `u` on((`tm`.`user_id` = `u`.`id`)));
