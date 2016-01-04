DROP TABLE IF EXISTS `#__isbn_registry_publisher`;

CREATE TABLE `#__isbn_registry_publisher` (
    `id`       INT(11)     NOT NULL AUTO_INCREMENT,
    `official_name` VARCHAR(100) NOT NULL,
    `other_names` VARCHAR(200),
    `previous_names` VARCHAR(300),
    `address` VARCHAR(50) NOT NULL,
    `zip` VARCHAR(10) NOT NULL,
    `city` VARCHAR(50) NOT NULL,
    `phone` VARCHAR(30),
    `email` VARCHAR(100) NOT NULL,
    `www` VARCHAR(100),
    `lang_code` VARCHAR(8),
    `contact_person` VARCHAR(100),
    `additional_info` VARCHAR(500),
    `year_quitted` VARCHAR(4) NOT NULL,
    `has_quitted` boolean not null default 0,
    `question_1` VARCHAR(50),
    `question_2` VARCHAR(50),
    `question_3` VARCHAR(50),
    `question_4` VARCHAR(200),
    `question_5` VARCHAR(200),
    `question_6` VARCHAR(50),
    `question_7` VARCHAR(50),
    `question_8` VARCHAR(50),
    `confirmation` VARCHAR(100) NOT NULL,
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` VARCHAR(30),
    `modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` VARCHAR(30),
    PRIMARY KEY (`id`),
    INDEX `idx_official_name` (`official_name`)
)
ENGINE =MyISAM
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

INSERT INTO `#__isbn_registry_publisher` (`official_name`, `other_names`, `address`, `zip`, `city`, `phone`, `email`,`www`, `contact_person`, `question_1`, `question_2`, `question_3`, `question_4`, `question_5`, `question_6`, `question_7`, `question_8`, `confirmation`, `lang_code`, `has_quitted`, `created`, `created_by`) VALUES
('Aa-kustantamo', 'Toinen nimi',  'Kettukuja 6', '00100', 'Helsinki', '0400123456', 'teppo.testaaja@pkrete.com','', 'Matti Virtanen', 'Vastaus 1', 'Vastaus 2', 'Vastaus 3', 'Vastaus 4', 'Vastaus 5', 'Vastaus 6', '000,030,050', 'Vastaus 8', '07.11.2015 Matti Virtanen', 'fi-FI', false, UTC_TIMESTAMP(), 'SYSTEM'),
('Aä', '', 'Viulutie 123', '004200', 'Helsinki', '', 'teppo.testaaja@pkrete.com','', 'Matti Virtanen',  'Vastaus 1', 'Vastaus 2', 'Vastaus 3', 'Vastaus 4', 'Vastaus 5', 'Vastaus 6', '', 'Vastaus 8', '07.11.2015 Matti Virtanen', 'fi-FI', false, UTC_TIMESTAMP(), 'SYSTEM'),
('Ää', '', 'Vesitie 100', '05678', 'Nilsiä', '091234567', 'teppo.testaaja@pkrete.com','', 'Matti Virtanen',  'Vastaus 1', 'Vastaus 2', 'Vastaus 3', 'Vastaus 4', 'Vastaus 5', 'Vastaus 6', '490,520', 'Vastaus 8', '07.11.2015 Matti Virtanen', 'en-GB', false, UTC_TIMESTAMP(), 'SYSTEM'),
('Testikustantamo', 'Test, Koe', 'Hiisikuja 5', '04230', 'Kerava', '050 12346789', 'teppo.testaaja@pkrete.com','http://www.test.com', 'Matti Virtanen', 'Vastaus 1', 'Vastaus 2', 'Vastaus 3', 'Vastaus 4', 'Vastaus 5', 'Vastaus 6', '030', 'Vastaus 8', '07.11.2015 Matti Virtanen', 'fi-FI', false, UTC_TIMESTAMP(), 'SYSTEM'),
('Edita', '', 'Kaikukatu 6', '00530', 'Helsinki', '09 123 4556', 'tiina.teekkari@pkrete.com','http://www.edita.fi', 'Matti Virtanen', 'Vastaus 1', 'Vastaus 2', 'Vastaus 3', 'Vastaus 4', 'Vastaus 5', 'Vastaus 6', '000,030,050,950', 'Vastaus 8', '07.11.2015 Matti Virtanen', 'en-GB', false, UTC_TIMESTAMP(), 'SYSTEM');

DROP TABLE IF EXISTS `#__isbn_registry_publication`;

CREATE TABLE `#__isbn_registry_publication` (
    `id`       INT(11)     NOT NULL AUTO_INCREMENT,
    `official_name` VARCHAR(100) NOT NULL,
    `publisher_identifier_str` VARCHAR(20),
    `publication_identifier_print` VARCHAR(20) default '',
    `publication_identifier_electronical` VARCHAR(20) default '',
    `publication_identifier_type` VARCHAR(4) default '',
    `publisher_id` INT,
    `locality` VARCHAR(50),
    `address` VARCHAR(50) NOT NULL,
    `zip` VARCHAR(10) NOT NULL,
    `city` VARCHAR(50) NOT NULL,
    `contact_person` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(30) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `lang_code` VARCHAR(8),
    `published_before` boolean not null default 0,
    `publications_public` boolean not null default 0,
    `publications_intra` boolean not null default 0,
    `publishing_activity` VARCHAR(10),
    `publishing_activity_amount` VARCHAR(5),
    `publication_type` VARCHAR(15),
    `publication_format` VARCHAR(20),
    `first_name_1` VARCHAR(50) NOT NULL,
    `last_name_1` VARCHAR(50) NOT NULL,
    `role_1` VARCHAR(40) NOT NULL,
    `first_name_2` VARCHAR(50),
    `last_name_2` VARCHAR(50),
    `role_2` VARCHAR(40),
    `first_name_3` VARCHAR(50),
    `last_name_3` VARCHAR(50),
    `role_3` VARCHAR(40),
    `first_name_4` VARCHAR(50),
    `last_name_4` VARCHAR(50),
    `role_4` VARCHAR(40),
    `title` VARCHAR(200) NOT NULL,
    `subtitle` VARCHAR(200),
    `map_scale` VARCHAR(50),
    `language` VARCHAR(3) NOT NULL,
    `year` VARCHAR(4) NOT NULL,
    `month` VARCHAR(2) NOT NULL,
    `series` VARCHAR(200),
    `issn` VARCHAR(9),
    `volume` VARCHAR(20),
    `printing_house` VARCHAR(100),
    `printing_house_city` VARCHAR(50),
    `copies` VARCHAR(10),
    `edition` VARCHAR(2),
    `type` VARCHAR(35),
    `comments` VARCHAR(500),
    `fileformat` VARCHAR(25),
    `no_identifier_granted` boolean not null default 0,
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` VARCHAR(30),
    `modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` VARCHAR(30),
    PRIMARY KEY (`id`),
    INDEX `idx_official_name` (`official_name`),
    INDEX `idx_publisher_id` (`publisher_id`)
)
ENGINE =MyISAM
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

INSERT INTO `#__isbn_registry_publication` (`official_name`, `publisher_id`, `address`, `zip`, `city`, `phone`, `email`, `contact_person`, `publication_type`, `publication_format`, `first_name_1`, `last_name_1`, `role_1`, `title`, `lang_code`, `created`, `created_by`) VALUES
('Aa-kustantamo', 1,  'Kettukuja 6', '00100', 'Helsinki', '0400123456', 'teppo.testaaja@pkrete.com', 'Matti Virtanen', 'BOOK', 'PRINT', 'Matti', 'Meikäläinen', 'AUTHOR', 'Kirja 1 - painettu', 'fi-FI', UTC_TIMESTAMP(), 'SYSTEM'),
('Aa-kustantamo', 1,  'Kettukuja 6', '00100', 'Helsinki', '0400123456', 'teppo.testaaja@pkrete.com', 'Matti Virtanen', 'BOOK', 'PRINT_ELECTRONICAL', 'Teppo', 'Testaaja', 'AUTHOR', 'Kirja 2 - painettu & e', 'fi-FI', UTC_TIMESTAMP(), 'SYSTEM'),
('Aa-kustantamo', 1,  'Kettukuja 6', '00100', 'Helsinki', '0400123456', 'teppo.testaaja@pkrete.com', 'Matti Virtanen', 'SHEET_MUSIC', 'PRINT', 'Tiina', 'Teekkari', 'AUTHOR', 'Nuotti 1 - painettu', 'en-GB', UTC_TIMESTAMP(), 'SYSTEM'),
('Aa-kustantamo', 1,  'Kettukuja 6', '00100', 'Helsinki', '0400123456', 'teppo.testaaja@pkrete.com', 'Matti Virtanen', 'DISSETATION', 'ELECTRONICAL', 'Pertti', 'Professori', 'AUTHOR', 'Disseration 1 - e', 'fi-FI', UTC_TIMESTAMP(), 'SYSTEM'),
('Aa-kustantamo', 1,  'Kettukuja 6', '00100', 'Helsinki', '0400123456', 'teppo.testaaja@pkrete.com', 'Matti Virtanen', 'SHEET_MUSIC', 'PRINT_ELECTRONICAL', 'Mauno', 'Ahonen', 'AUTHOR', 'Nuotti 2 - painettu & e', 'en-GB', UTC_TIMESTAMP(), 'SYSTEM');

DROP TABLE IF EXISTS `#__isbn_registry_isbn_range`;

CREATE TABLE `#__isbn_registry_isbn_range` (
    `id`       INT(11)     NOT NULL AUTO_INCREMENT,
    `prefix` INT,
    `lang_group` INT NOT NULL,
    `category` INT NOT NULL,
    `range_begin` VARCHAR(5) NOT NULL,
    `range_end` VARCHAR(5) NOT NULL,
    `free` INT NOT NULL,
    `taken` INT NOT NULL DEFAULT 0,
    `next` VARCHAR(5) NOT NULL,
    `is_active` boolean not null default 1,
    `is_closed` boolean not null default 0,
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` VARCHAR(30),
    `modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` VARCHAR(30),
    PRIMARY KEY (`id`)
)
ENGINE =MyISAM
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

INSERT INTO `#__isbn_registry_isbn_range` (`prefix`, `lang_group`, `category`, `range_begin`, `range_end`, `free`, `next`, `created`, `created_by`) VALUES
(978, 951, 2, '00', '19', 20, '00', UTC_TIMESTAMP(), 'SYSTEM'),
(0, 952, 3, '000', '099', 100, '000', UTC_TIMESTAMP(), 'SYSTEM'),
(0, 952, 5, '00020', '00021', 2, '00020', UTC_TIMESTAMP(), 'SYSTEM'),
(978, 952, 4, '0100', '0199', 100, '0100', UTC_TIMESTAMP(), 'SYSTEM');

DROP TABLE IF EXISTS `#__isbn_registry_publisher_isbn_range`;

CREATE TABLE `#__isbn_registry_publisher_isbn_range` (
    `id`       INT(11)     NOT NULL AUTO_INCREMENT,
    `publisher_identifier` VARCHAR(15) NOT NULL,
    `publisher_id` INT NOT NULL,
    `isbn_range_id` INT NOT NULL,
    `category` INT NOT NULL,
    `range_begin` VARCHAR(5) NOT NULL,
    `range_end` VARCHAR(5) NOT NULL,
    `free` INT NOT NULL,
    `taken` INT NOT NULL DEFAULT 0,
    `next` VARCHAR(5) NOT NULL,
    `is_active` boolean not null default 1,
    `is_closed` boolean not null default 0,
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` VARCHAR(30),
    `modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` VARCHAR(30),
    PRIMARY KEY (`id`),
    INDEX `idx_publisher_id` (`publisher_id`),
    INDEX `idx_isbn_range_id` (`isbn_range_id`)
)
ENGINE =MyISAM
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

DROP TABLE IF EXISTS `#__isbn_registry_isbn_used`;

CREATE TABLE `#__isbn_registry_isbn_used` (
    `id`       INT(11)     NOT NULL AUTO_INCREMENT,
    `publisher_isbn_range_id` INT NOT NULL,
    `publication_identifier` INT NOT NULL,
    `isbn_full` VARCHAR(17) NOT NULL,
    `publisher_id` INT NOT NULL,
    `publication_id` INT NOT NULL,
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` VARCHAR(30),
    `modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` VARCHAR(30),
    PRIMARY KEY (`id`),
    INDEX `idx_isbn_full` (`isbn_full`),
    INDEX `idx_publisher_id` (`publisher_id`),
    INDEX `idx_publisher_isbn_range_id` (`publisher_isbn_range_id`),
    INDEX `idx_publication_id` (`publication_id`)
)
ENGINE =MyISAM
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

DROP TABLE IF EXISTS `#__isbn_registry_ismn_range`;

CREATE TABLE `#__isbn_registry_ismn_range` (
    `id`       INT(11)     NOT NULL AUTO_INCREMENT,
    `prefix` VARCHAR(5) NOT NULL,
    `category` INT NOT NULL,
    `range_begin` VARCHAR(7) NOT NULL,
    `range_end` VARCHAR(7) NOT NULL,
    `free` INT NOT NULL,
    `taken` INT NOT NULL DEFAULT 0,
    `next` VARCHAR(7) NOT NULL,
    `is_active` boolean not null default 1,
    `is_closed` boolean not null default 0,
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` VARCHAR(30),
    `modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` VARCHAR(30),
    PRIMARY KEY (`id`)
)
ENGINE =MyISAM
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

INSERT INTO `#__isbn_registry_ismn_range` (`prefix`, `category`, `range_begin`, `range_end`, `free`, `next`, `created`, `created_by`) VALUES
('979-0', 3, '000', '019', 20, '000', UTC_TIMESTAMP(), 'SYSTEM'),
('979-0', 7, '0000100', '0000199', 100, '0000100', UTC_TIMESTAMP(), 'SYSTEM');

DROP TABLE IF EXISTS `#__isbn_registry_publisher_ismn_range`;

CREATE TABLE `#__isbn_registry_publisher_ismn_range` (
    `id`       INT(11)     NOT NULL AUTO_INCREMENT,
    `publisher_identifier` VARCHAR(15) NOT NULL,
    `publisher_id` INT NOT NULL,
    `ismn_range_id` INT NOT NULL,
    `category` INT NOT NULL,
    `range_begin` VARCHAR(7) NOT NULL,
    `range_end` VARCHAR(7) NOT NULL,
    `free` INT NOT NULL,
    `taken` INT NOT NULL DEFAULT 0,
    `next` VARCHAR(7) NOT NULL,
    `is_active` boolean not null default 1,
    `is_closed` boolean not null default 0,
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` VARCHAR(30),
    `modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` VARCHAR(30),
    PRIMARY KEY (`id`),
    INDEX `idx_publisher_id` (`publisher_id`),
    INDEX `idx_ismn_range_id` (`ismn_range_id`)
)
ENGINE =MyISAM
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;