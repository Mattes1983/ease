-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 23. März 2012 um 08:27
-- Server Version: 5.5.8
-- PHP-Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `mattes`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ease_content`
--

DROP TABLE IF EXISTS `ease_content`;
CREATE TABLE IF NOT EXISTS `ease_content` (
  `con_id` int(11) NOT NULL AUTO_INCREMENT,
  `con_lin_id` int(11) NOT NULL,
  `con_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `con_value` longtext CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`con_id`),
  KEY `con_lin_id` (`con_lin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `ease_content`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ease_document`
--

DROP TABLE IF EXISTS `ease_document`;
CREATE TABLE IF NOT EXISTS `ease_document` (
  `doc_id` int(11) NOT NULL AUTO_INCREMENT,
  `doc_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `doc_suffix` varchar(255) CHARACTER SET latin1 NOT NULL,
  `doc_title` varchar(70) CHARACTER SET latin1 NOT NULL,
  `doc_meta_description` varchar(156) CHARACTER SET latin1 NOT NULL,
  `doc_meta_keywords` text CHARACTER SET latin1 NOT NULL,
  `doc_create_use_id` int(11) NOT NULL,
  `doc_create_date` datetime NOT NULL,
  `doc_changed` int(1) NOT NULL,
  `doc_changed_use_id` int(11) NOT NULL,
  `doc_changed_date` datetime NOT NULL,
  `doc_auto_name` int(1) NOT NULL DEFAULT '1',
  `doc_auto_title` int(1) NOT NULL DEFAULT '1',
  `doc_first_text` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`doc_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `ease_document`
--

INSERT INTO `ease_document` (`doc_id`, `doc_name`, `doc_suffix`, `doc_title`, `doc_meta_description`, `doc_meta_keywords`, `doc_create_use_id`, `doc_create_date`, `doc_changed`, `doc_changed_use_id`, `doc_changed_date`, `doc_auto_name`, `doc_auto_title`, `doc_first_text`) VALUES(1, 'index', '.php', 'Home', '', '', 1, '0000-00-00 00:00:00', 1, 1, '0000-00-00 00:00:00', 0, 1, 'Home\n\n	Das ist die Startseite :)');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ease_document_files`
--

DROP TABLE IF EXISTS `ease_document_files`;
CREATE TABLE IF NOT EXISTS `ease_document_files` (
  `dof_id` int(11) NOT NULL AUTO_INCREMENT,
  `dof_doc_id` int(11) NOT NULL,
  `dof_path` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`dof_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `ease_document_files`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ease_extension`
--

DROP TABLE IF EXISTS `ease_extension`;
CREATE TABLE IF NOT EXISTS `ease_extension` (
  `ext_id` int(11) NOT NULL AUTO_INCREMENT,
  `ext_active` int(1) NOT NULL,
  `ext_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `ext_include` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`ext_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Daten für Tabelle `ease_extension`
--

INSERT INTO `ease_extension` (`ext_id`, `ext_active`, `ext_name`, `ext_include`) VALUES(1, 1, 'EASESettings', '/ease-settings');
INSERT INTO `ease_extension` (`ext_id`, `ext_active`, `ext_name`, `ext_include`) VALUES(2, 0, 'EASEUpdate', '/ease-update');
INSERT INTO `ease_extension` (`ext_id`, `ext_active`, `ext_name`, `ext_include`) VALUES(3, 1, 'EASEDocument', '/ease-document');
INSERT INTO `ease_extension` (`ext_id`, `ext_active`, `ext_name`, `ext_include`) VALUES(4, 1, 'EASEUser', '/ease-user');
INSERT INTO `ease_extension` (`ext_id`, `ext_active`, `ext_name`, `ext_include`) VALUES(5, 1, 'EASENavigation', '/ease-navigation');
INSERT INTO `ease_extension` (`ext_id`, `ext_active`, `ext_name`, `ext_include`) VALUES(6, 1, 'EASESearch', '/ease-search');
INSERT INTO `ease_extension` (`ext_id`, `ext_active`, `ext_name`, `ext_include`) VALUES(7, 1, 'EASEForm', '/ease-form');
INSERT INTO `ease_extension` (`ext_id`, `ext_active`, `ext_name`, `ext_include`) VALUES(8, 1, 'EASENews', '/ease-news');
INSERT INTO `ease_extension` (`ext_id`, `ext_active`, `ext_name`, `ext_include`) VALUES(9, 1, 'EASEImage', '/ease-image');
INSERT INTO `ease_extension` (`ext_id`, `ext_active`, `ext_name`, `ext_include`) VALUES(10, 1, 'EASELink', '/ease-link');
INSERT INTO `ease_extension` (`ext_id`, `ext_active`, `ext_name`, `ext_include`) VALUES(11, 1, 'CKEditor', '/ckeditor');
INSERT INTO `ease_extension` (`ext_id`, `ext_active`, `ext_name`, `ext_include`) VALUES(12, 1, 'JCarousel', '/jcarousel');
INSERT INTO `ease_extension` (`ext_id`, `ext_active`, `ext_name`, `ext_include`) VALUES(13, 1, 'Web', '/web');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ease_ext_easeform_data`
--

DROP TABLE IF EXISTS `ease_ext_easeform_data`;
CREATE TABLE IF NOT EXISTS `ease_ext_easeform_data` (
  `eed_id` int(11) NOT NULL AUTO_INCREMENT,
  `eed_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `eed_date` datetime NOT NULL,
  `eed_ip` varchar(15) CHARACTER SET latin1 NOT NULL,
  `eed_document` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`eed_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `ease_ext_easeform_data`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ease_ext_easeform_fields`
--

DROP TABLE IF EXISTS `ease_ext_easeform_fields`;
CREATE TABLE IF NOT EXISTS `ease_ext_easeform_fields` (
  `eef_id` int(11) NOT NULL AUTO_INCREMENT,
  `eef_eed_id` int(11) NOT NULL,
  `eef_name` text CHARACTER SET latin1 NOT NULL,
  `eef_value` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`eef_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `ease_ext_easeform_fields`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ease_ext_easeimage_groups`
--

DROP TABLE IF EXISTS `ease_ext_easeimage_groups`;
CREATE TABLE IF NOT EXISTS `ease_ext_easeimage_groups` (
  `eig_id` int(11) NOT NULL AUTO_INCREMENT,
  `eig_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`eig_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `ease_ext_easeimage_groups`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ease_ext_easeimage_images`
--

DROP TABLE IF EXISTS `ease_ext_easeimage_images`;
CREATE TABLE IF NOT EXISTS `ease_ext_easeimage_images` (
  `eii_id` int(11) NOT NULL AUTO_INCREMENT,
  `eii_eig_id` int(11) NOT NULL,
  `eii_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `eii_suffix` varchar(10) CHARACTER SET latin1 NOT NULL,
  `eii_description` text CHARACTER SET latin1 NOT NULL,
  `eii_keywords` text CHARACTER SET latin1 NOT NULL,
  `eii_width` int(11) NOT NULL,
  `eii_height` int(11) NOT NULL,
  PRIMARY KEY (`eii_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `ease_ext_easeimage_images`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ease_ext_easenavigation_links`
--

DROP TABLE IF EXISTS `ease_ext_easenavigation_links`;
CREATE TABLE IF NOT EXISTS `ease_ext_easenavigation_links` (
  `enl_id` int(11) NOT NULL AUTO_INCREMENT,
  `enl_parent` int(11) NOT NULL,
  `enl_order` int(11) NOT NULL,
  `enl_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `enl_type` int(1) NOT NULL,
  `enl_url` text CHARACTER SET latin1 NOT NULL,
  `enl_doc_id` int(11) NOT NULL,
  `enl_target` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`enl_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `ease_ext_easenavigation_links`
--

INSERT INTO `ease_ext_easenavigation_links` (`enl_id`, `enl_parent`, `enl_order`, `enl_name`, `enl_type`, `enl_url`, `enl_doc_id`, `enl_target`) VALUES(1, 0, 0, 'Home', 0, '', 1, '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ease_ext_easenews_news`
--

DROP TABLE IF EXISTS `ease_ext_easenews_news`;
CREATE TABLE IF NOT EXISTS `ease_ext_easenews_news` (
  `een_id` int(11) NOT NULL AUTO_INCREMENT,
  `een_lin_id` int(11) NOT NULL,
  `een_startdate` int(11) NOT NULL,
  `een_enddate` int(11) NOT NULL,
  PRIMARY KEY (`een_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `ease_ext_easenews_news`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ease_ext_easenews_related`
--

DROP TABLE IF EXISTS `ease_ext_easenews_related`;
CREATE TABLE IF NOT EXISTS `ease_ext_easenews_related` (
  `eer_id` int(11) NOT NULL AUTO_INCREMENT,
  `eer_een_id` int(11) NOT NULL,
  `eer_lin_parent` int(11) NOT NULL,
  `eer_lin_id` int(11) NOT NULL,
  PRIMARY KEY (`eer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `ease_ext_easenews_related`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ease_file`
--

DROP TABLE IF EXISTS `ease_file`;
CREATE TABLE IF NOT EXISTS `ease_file` (
  `fil_id` int(11) NOT NULL AUTO_INCREMENT,
  `fil_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `fil_suffix` varchar(5) CHARACTER SET latin1 NOT NULL,
  `fil_title` varchar(255) CHARACTER SET latin1 NOT NULL,
  `fil_create_use_id` int(11) NOT NULL,
  `fil_create_date` datetime NOT NULL,
  PRIMARY KEY (`fil_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `ease_file`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ease_generated_content`
--

DROP TABLE IF EXISTS `ease_generated_content`;
CREATE TABLE IF NOT EXISTS `ease_generated_content` (
  `gec_id` int(11) NOT NULL AUTO_INCREMENT,
  `gec_doc_id` int(11) NOT NULL,
  `gec_typ` varchar(255) CHARACTER SET latin1 NOT NULL,
  `gec_content` longtext CHARACTER SET latin1 NOT NULL,
  `gec_content_plaintext` longtext CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`gec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `ease_generated_content`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ease_generated_document`
--

DROP TABLE IF EXISTS `ease_generated_document`;
CREATE TABLE IF NOT EXISTS `ease_generated_document` (
  `ged_doc_id` int(11) NOT NULL AUTO_INCREMENT,
  `ged_path` varchar(255) CHARACTER SET latin1 NOT NULL,
  `ged_doc_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `ged_doc_suffix` varchar(255) CHARACTER SET latin1 NOT NULL,
  `ged_doc_title` varchar(70) CHARACTER SET latin1 NOT NULL,
  `ged_doc_meta_description` varchar(156) CHARACTER SET latin1 NOT NULL,
  `ged_doc_keywords` text CHARACTER SET latin1 NOT NULL,
  `ged_doc_first_text` text CHARACTER SET latin1 NOT NULL,
  `ged_date` datetime NOT NULL,
  `ged_use_id` int(11) NOT NULL,
  PRIMARY KEY (`ged_doc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `ease_generated_document`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ease_generated_path`
--

DROP TABLE IF EXISTS `ease_generated_path`;
CREATE TABLE IF NOT EXISTS `ease_generated_path` (
  `geu_id` int(11) NOT NULL AUTO_INCREMENT,
  `geu_doc_id` int(11) NOT NULL,
  `geu_path` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`geu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `ease_generated_path`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ease_level`
--

DROP TABLE IF EXISTS `ease_level`;
CREATE TABLE IF NOT EXISTS `ease_level` (
  `lvl_id` int(11) NOT NULL AUTO_INCREMENT,
  `lvl_order` int(11) NOT NULL,
  `lvl_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`lvl_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `ease_level`
--

INSERT INTO `ease_level` (`lvl_id`, `lvl_order`, `lvl_name`) VALUES(1, 0, 'Layout');
INSERT INTO `ease_level` (`lvl_id`, `lvl_order`, `lvl_name`) VALUES(2, 1, 'Elements');
INSERT INTO `ease_level` (`lvl_id`, `lvl_order`, `lvl_name`) VALUES(3, 2, 'Document');
INSERT INTO `ease_level` (`lvl_id`, `lvl_order`, `lvl_name`) VALUES(4, 3, 'CMS-Settings');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ease_link`
--

DROP TABLE IF EXISTS `ease_link`;
CREATE TABLE IF NOT EXISTS `ease_link` (
  `lin_id` int(11) NOT NULL AUTO_INCREMENT,
  `lin_parent` int(11) NOT NULL,
  `lin_relation` int(11) NOT NULL,
  `lin_order` int(11) NOT NULL,
  `lin_doc_id` int(11) NOT NULL,
  `lin_lco_id` int(11) NOT NULL,
  `lin_lvl_id` int(11) NOT NULL,
  `lin_ext_id` int(11) NOT NULL,
  `lin_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `lin_startdate` varchar(255) CHARACTER SET latin1 NOT NULL,
  `lin_enddate` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`lin_id`),
  KEY `lin_doc_id` (`lin_doc_id`),
  KEY `lin_parent` (`lin_parent`),
  KEY `lin_name` (`lin_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `ease_link`
--

INSERT INTO `ease_link` (`lin_id`, `lin_parent`, `lin_relation`, `lin_order`, `lin_doc_id`, `lin_lco_id`, `lin_lvl_id`, `lin_ext_id`, `lin_name`, `lin_startdate`, `lin_enddate`) VALUES(1, 0, 0, 0, 1, 1, 1, 13, '', '', '');
INSERT INTO `ease_link` (`lin_id`, `lin_parent`, `lin_relation`, `lin_order`, `lin_doc_id`, `lin_lco_id`, `lin_lvl_id`, `lin_ext_id`, `lin_name`, `lin_startdate`, `lin_enddate`) VALUES(2, 1, 0, 0, 1, 2, 1, 13, 'header', '', '');
INSERT INTO `ease_link` (`lin_id`, `lin_parent`, `lin_relation`, `lin_order`, `lin_doc_id`, `lin_lco_id`, `lin_lvl_id`, `lin_ext_id`, `lin_name`, `lin_startdate`, `lin_enddate`) VALUES(3, 1, 0, 0, 1, 3, 1, 13, 'footer', '', '');
INSERT INTO `ease_link` (`lin_id`, `lin_parent`, `lin_relation`, `lin_order`, `lin_doc_id`, `lin_lco_id`, `lin_lvl_id`, `lin_ext_id`, `lin_name`, `lin_startdate`, `lin_enddate`) VALUES(4, 2, 0, 0, 1, 4, 1, 13, 'navigation', '', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ease_link_content`
--

DROP TABLE IF EXISTS `ease_link_content`;
CREATE TABLE IF NOT EXISTS `ease_link_content` (
  `lco_id` int(11) NOT NULL AUTO_INCREMENT,
  `lco_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `lco_value` longtext CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`lco_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `ease_link_content`
--

INSERT INTO `ease_link_content` (`lco_id`, `lco_name`, `lco_value`) VALUES(1, 'layout', 'startpage');
INSERT INTO `ease_link_content` (`lco_id`, `lco_name`, `lco_value`) VALUES(2, 'layout', 'header');
INSERT INTO `ease_link_content` (`lco_id`, `lco_name`, `lco_value`) VALUES(3, 'layout', 'footer');
INSERT INTO `ease_link_content` (`lco_id`, `lco_name`, `lco_value`) VALUES(4, 'layout', 'menu');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ease_link_parentattributes`
--

DROP TABLE IF EXISTS `ease_link_parentattributes`;
CREATE TABLE IF NOT EXISTS `ease_link_parentattributes` (
  `lpa_id` int(11) NOT NULL AUTO_INCREMENT,
  `lpa_lin_id` int(11) NOT NULL COMMENT 'Link-ID',
  `lpa_name` varchar(255) CHARACTER SET latin1 NOT NULL COMMENT 'Attribut-Name',
  `lpa_value` varchar(255) CHARACTER SET latin1 NOT NULL COMMENT 'Attribut-Value',
  PRIMARY KEY (`lpa_id`),
  KEY `lpa_lin_id` (`lpa_lin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Daten für Tabelle `ease_link_parentattributes`
--

INSERT INTO `ease_link_parentattributes` (`lpa_id`, `lpa_lin_id`, `lpa_name`, `lpa_value`) VALUES(13, 3, 'drop', 'false');
INSERT INTO `ease_link_parentattributes` (`lpa_id`, `lpa_lin_id`, `lpa_name`, `lpa_value`) VALUES(14, 3, 'extension', 'Web');
INSERT INTO `ease_link_parentattributes` (`lpa_id`, `lpa_lin_id`, `lpa_name`, `lpa_value`) VALUES(15, 3, 'extension_name', 'layout');
INSERT INTO `ease_link_parentattributes` (`lpa_id`, `lpa_lin_id`, `lpa_name`, `lpa_value`) VALUES(16, 3, 'extension_value', 'footer');
INSERT INTO `ease_link_parentattributes` (`lpa_id`, `lpa_lin_id`, `lpa_name`, `lpa_value`) VALUES(17, 2, 'drop', 'false');
INSERT INTO `ease_link_parentattributes` (`lpa_id`, `lpa_lin_id`, `lpa_name`, `lpa_value`) VALUES(18, 2, 'extension', 'Web');
INSERT INTO `ease_link_parentattributes` (`lpa_id`, `lpa_lin_id`, `lpa_name`, `lpa_value`) VALUES(19, 2, 'extension_name', 'layout');
INSERT INTO `ease_link_parentattributes` (`lpa_id`, `lpa_lin_id`, `lpa_name`, `lpa_value`) VALUES(20, 2, 'extension_value', 'header');
INSERT INTO `ease_link_parentattributes` (`lpa_id`, `lpa_lin_id`, `lpa_name`, `lpa_value`) VALUES(21, 4, 'drop', 'false');
INSERT INTO `ease_link_parentattributes` (`lpa_id`, `lpa_lin_id`, `lpa_name`, `lpa_value`) VALUES(22, 4, 'extension', 'Web');
INSERT INTO `ease_link_parentattributes` (`lpa_id`, `lpa_lin_id`, `lpa_name`, `lpa_value`) VALUES(23, 4, 'extension_name', 'layout');
INSERT INTO `ease_link_parentattributes` (`lpa_id`, `lpa_lin_id`, `lpa_name`, `lpa_value`) VALUES(24, 4, 'extension_value', 'menu');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ease_setting`
--

DROP TABLE IF EXISTS `ease_setting`;
CREATE TABLE IF NOT EXISTS `ease_setting` (
  `set_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `set_value` text CHARACTER SET latin1 NOT NULL,
  UNIQUE KEY `set_name` (`set_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `ease_setting`
--

INSERT INTO `ease_setting` (`set_name`, `set_value`) VALUES('Document-Auto-Title', '1');
INSERT INTO `ease_setting` (`set_name`, `set_value`) VALUES('Document-Start', '1');
INSERT INTO `ease_setting` (`set_name`, `set_value`) VALUES('Generate-Mode', '1');
INSERT INTO `ease_setting` (`set_name`, `set_value`) VALUES('HTMLCopyright', 'Web-Umsetzung:\r\nmove:elevator \r\nFull Service Werbeagentur\r\nWeb: http://www.move-elevator.de');
INSERT INTO `ease_setting` (`set_name`, `set_value`) VALUES('HTMLVersion', 'html5');
INSERT INTO `ease_setting` (`set_name`, `set_value`) VALUES('JQuery-CSS', '');
INSERT INTO `ease_setting` (`set_name`, `set_value`) VALUES('JQuery-JS', '/jquery/jquery-1.7.1.min.js');
INSERT INTO `ease_setting` (`set_name`, `set_value`) VALUES('JQuery-UI-CSS', '/jquery-ui/jquery-ui-1.8.13.custom.css');
INSERT INTO `ease_setting` (`set_name`, `set_value`) VALUES('JQuery-UI-JS', '/jquery-ui/jquery-ui-1.8.13.custom.min.js');
INSERT INTO `ease_setting` (`set_name`, `set_value`) VALUES('Language', 'de');
INSERT INTO `ease_setting` (`set_name`, `set_value`) VALUES('Theme', '1');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ease_theme`
--

DROP TABLE IF EXISTS `ease_theme`;
CREATE TABLE IF NOT EXISTS `ease_theme` (
  `the_id` int(11) NOT NULL AUTO_INCREMENT,
  `the_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `the_folder` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`the_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `ease_theme`
--

INSERT INTO `ease_theme` (`the_id`, `the_name`, `the_folder`) VALUES(1, 'Default', 'default');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ease_user`
--

DROP TABLE IF EXISTS `ease_user`;
CREATE TABLE IF NOT EXISTS `ease_user` (
  `use_id` int(11) NOT NULL AUTO_INCREMENT,
  `use_login` varchar(255) CHARACTER SET latin1 NOT NULL,
  `use_pw` varchar(255) CHARACTER SET latin1 NOT NULL,
  `use_admin` int(1) NOT NULL,
  `use_language` varchar(255) CHARACTER SET latin1 NOT NULL,
  `use_theme` int(11) NOT NULL,
  PRIMARY KEY (`use_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `ease_user`
--

INSERT INTO `ease_user` (`use_id`, `use_login`, `use_pw`, `use_admin`, `use_language`, `use_theme`) VALUES(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, 'de', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ease_user_data`
--

DROP TABLE IF EXISTS `ease_user_data`;
CREATE TABLE IF NOT EXISTS `ease_user_data` (
  `usd_id` int(11) NOT NULL AUTO_INCREMENT,
  `usd_use_id` int(11) NOT NULL,
  `usd_firstname` varchar(255) CHARACTER SET latin1 NOT NULL,
  `usd_lastname` varchar(255) CHARACTER SET latin1 NOT NULL,
  `usd_email` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`usd_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `ease_user_data`
--

INSERT INTO `ease_user_data` (`usd_id`, `usd_use_id`, `usd_firstname`, `usd_lastname`, `usd_email`) VALUES(1, 1, '', '', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ease_user_login`
--

DROP TABLE IF EXISTS `ease_user_login`;
CREATE TABLE IF NOT EXISTS `ease_user_login` (
  `ulo_id` int(11) NOT NULL AUTO_INCREMENT,
  `ulo_use_id` int(11) NOT NULL,
  `ulo_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ulo_datetime` datetime NOT NULL,
  `ulo_session_id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `ulo_ip` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`ulo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `ease_user_login`
--

