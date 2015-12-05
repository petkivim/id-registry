DROP TABLE IF EXISTS `#__isbn_registry_publisher`;

CREATE TABLE `#__isbn_registry_publisher` (
    `id`       INT(11)     NOT NULL AUTO_INCREMENT,
    `official_name` VARCHAR(100) NOT NULL,
    `other_names` VARCHAR(200),
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
    `state` boolean not null default 1,
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

INSERT INTO `#__isbn_registry_publisher` (`official_name`, `other_names`, `address`, `zip`, `city`, `phone`, `email`,`www`, `contact_person`, `question_1`, `question_2`, `question_3`, `question_4`, `question_5`, `question_6`, `question_7`, `question_8`, `confirmation`, `lang_code`, `state`, `created`, `created_by`) VALUES
('Aa-kustantamo', 'Toinen nimi',  'Kettukuja 6', '00100', 'Helsinki', '0400123456', 'teppo.testaaja@pkrete.com','', 'Matti Virtanen', 'Vastaus 1', 'Vastaus 2', 'Vastaus 3', 'Vastaus 4', 'Vastaus 5', 'Vastaus 6', '000,030,050', 'Vastaus 8', '07.11.2015 Matti Virtanen', 'fi-FI', true, UTC_TIMESTAMP(), 'SYSTEM'),
('Aä', '', 'Viulutie 123', '004200', 'Helsinki', '', 'teppo.testaaja@pkrete.com','', 'Matti Virtanen',  'Vastaus 1', 'Vastaus 2', 'Vastaus 3', 'Vastaus 4', 'Vastaus 5', 'Vastaus 6', '', 'Vastaus 8', '07.11.2015 Matti Virtanen', 'fi-FI', false, UTC_TIMESTAMP(), 'SYSTEM'),
('Ää', '', 'Vesitie 100', '05678', 'Nilsiä', '091234567', 'teppo.testaaja@pkrete.com','', 'Matti Virtanen',  'Vastaus 1', 'Vastaus 2', 'Vastaus 3', 'Vastaus 4', 'Vastaus 5', 'Vastaus 6', '490,520', 'Vastaus 8', '07.11.2015 Matti Virtanen', 'en-GB', true, UTC_TIMESTAMP(), 'SYSTEM'),
('Testikustantamo', 'Test, Koe', 'Hiisikuja 5', '04230', 'Kerava', '050 12346789', 'teppo.testaaja@pkrete.com','http://www.test.com', 'Matti Virtanen', 'Vastaus 1', 'Vastaus 2', 'Vastaus 3', 'Vastaus 4', 'Vastaus 5', 'Vastaus 6', '030', 'Vastaus 8', '07.11.2015 Matti Virtanen', 'fi-FI', true, UTC_TIMESTAMP(), 'SYSTEM'),
('Edita', '', 'Kaikukatu 6', '00530', 'Helsinki', '09 123 4556', 'tiina.teekkari@pkrete.com','http://www.edita.fi', 'Matti Virtanen', 'Vastaus 1', 'Vastaus 2', 'Vastaus 3', 'Vastaus 4', 'Vastaus 5', 'Vastaus 6', '000,030,050,950', 'Vastaus 8', '07.11.2015 Matti Virtanen', 'en-GB', false, UTC_TIMESTAMP(), 'SYSTEM');

DROP TABLE IF EXISTS `#__isbn_registry_publication`;

CREATE TABLE `#__isbn_registry_publication` (
    `id`       INT(11)     NOT NULL AUTO_INCREMENT,
    `official_name` VARCHAR(100) NOT NULL,
    `publisher_identifier_str` VARCHAR(20),
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

DROP TABLE IF EXISTS `#__isbn_registry_isbn_range`;

CREATE TABLE `#__isbn_registry_isbn_range` (
    `id`       INT(11)     NOT NULL AUTO_INCREMENT,
    `prefix` INT NOT NULL,
    `category` INT NOT NULL,
    `range_begin` INT NOT NULL,
    `range_end` INT NOT NULL,
    `free` INT NOT NULL,
    `taken` INT NOT NULL DEFAULT 0,
    `next` INT NOT NULL,
    `is_active` boolean not null default 1,
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

DROP TABLE IF EXISTS `#__isbn_registry_publisher_isbn_range`;

CREATE TABLE `#__isbn_registry_publisher_isbn_range` (
    `id`       INT(11)     NOT NULL AUTO_INCREMENT,
    `publisher_identifier` INT NOT NULL,
    `publisher_id` INT NOT NULL,
    `isbn_range_id` INT NOT NULL,
    `range_begin` INT NOT NULL,
    `range_end` INT NOT NULL,
    `free` INT NOT NULL,
    `taken` INT NOT NULL DEFAULT 0,
    `next` INT NOT NULL,
    `is_active` boolean not null default 1,
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
    `number_used` INT NOT NULL,
    `isbn_full` VARCHAR(17) NOT NULL,
    `publication_id` INT NOT NULL,
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` VARCHAR(30),
    `modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` VARCHAR(30),
    PRIMARY KEY (`id`),
    INDEX `idx_isbn_full` (`isbn_full`),
    INDEX `idx_publisher_isbn_range_id` (`publisher_isbn_range_id`),
    INDEX `idx_publication_id` (`publication_id`)
)
ENGINE =MyISAM
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;