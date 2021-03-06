DROP TABLE IF EXISTS `#__issn_registry_form`;

CREATE TABLE `#__issn_registry_form` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `publisher` VARCHAR(100) NOT NULL,
    `contact_person` VARCHAR(100),
    `email` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(30),
    `address` VARCHAR(50) NOT NULL,
    `zip` VARCHAR(10) NOT NULL,
    `city` VARCHAR(50) NOT NULL,
    `publication_count` INT DEFAULT 0,
    `publication_count_issn` INT DEFAULT 0,
    `publisher_id` INT DEFAULT 0,
    `publisher_created` boolean not null default 0,
    `lang_code` VARCHAR(8),
    `status` VARCHAR(12) DEFAULT 'NOT_HANDLED',
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` VARCHAR(30),
    `modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` VARCHAR(30),
    PRIMARY KEY (`id`),
    INDEX `idx_publisher` (`publisher`),
    INDEX `idx_publisher_id` (`publisher_id`)
)
ENGINE =InnoDB
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

DROP TABLE IF EXISTS `#__issn_registry_form_archive`;

CREATE TABLE `#__issn_registry_form_archive` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `form_id` INT NOT NULL,
    `publisher` VARCHAR(100) NOT NULL,
    `contact_person` VARCHAR(600),
    `email` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(30),
    `address` VARCHAR(50) NOT NULL,
    `zip` VARCHAR(10) NOT NULL,
    `city` VARCHAR(50) NOT NULL,
    `publication_count` INT,  
    `lang_code` VARCHAR(8),
    `id_old` INT,
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` VARCHAR(30),
    PRIMARY KEY (`id`),
    INDEX `idx_form_id` (`form_id`)
)
ENGINE =InnoDB
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

DROP TABLE IF EXISTS `#__issn_registry_publication`;

CREATE TABLE `#__issn_registry_publication` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(200) NOT NULL,
    `subtitle` VARCHAR(200),
    `issn` VARCHAR(9) DEFAULT '',
    `place_of_publication` VARCHAR(100) NOT NULL,
    `printer` VARCHAR(100),
    `issued_from_year` VARCHAR(4),
    `issued_from_number` VARCHAR(100),
    `frequency` CHAR(1),
    `frequency_other` VARCHAR(50),
    `language` VARCHAR(50),
    `publication_type` VARCHAR(25),
    `publication_type_other` VARCHAR(50),
    `medium` VARCHAR(7),
    `medium_other` VARCHAR(50),
    `url` VARCHAR(100),
    `previous` VARCHAR(850),
    `main_series` VARCHAR(850),
    `subseries` VARCHAR(850),
    `another_medium` VARCHAR(850),
    `additional_info` VARCHAR(2000),
    `status` VARCHAR(24) DEFAULT 'NO_PREPUBLICATION_RECORD',
    `form_id` INT DEFAULT 0,
    `publisher_id` INT DEFAULT 0,
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` VARCHAR(30),
    `modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` VARCHAR(30),
    PRIMARY KEY (`id`),
    INDEX `idx_title` (`title`),
    INDEX `idx_issn` (`issn`),
    INDEX `idx_form_id` (`form_id`),
    INDEX `idx_publisher_id` (`publisher_id`)
)
ENGINE =InnoDB
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

DROP TABLE IF EXISTS `#__issn_registry_publication_archive`;

CREATE TABLE `#__issn_registry_publication_archive` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `publication_id` INT NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `subtitle` VARCHAR(200),
    `place_of_publication` VARCHAR(100) NOT NULL,
    `printer` VARCHAR(100),
    `issued_from_year` VARCHAR(4),
    `issued_from_number` VARCHAR(100),
    `frequency` CHAR(1),
    `frequency_other` VARCHAR(50),
    `language` VARCHAR(50),
    `publication_type` VARCHAR(25),
    `publication_type_other` VARCHAR(50),
    `medium` VARCHAR(7),
    `medium_other` VARCHAR(50),
    `url` VARCHAR(100),
    `previous` VARCHAR(850),
    `main_series` VARCHAR(850),
    `subseries` VARCHAR(850),
    `another_medium` VARCHAR(850),
    `additional_info` VARCHAR(1000),
    `form_id` INT DEFAULT 0,
    `id_old` INT,
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` VARCHAR(30),
    PRIMARY KEY (`id`),
    INDEX `idx_form_id` (`form_id`),
    INDEX `idx_publication_id` (`publication_id`)
)
ENGINE =InnoDB
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

DROP TABLE IF EXISTS `#__issn_registry_publisher`;

CREATE TABLE `#__issn_registry_publisher` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `official_name` VARCHAR(100) NOT NULL,
    `contact_person` VARCHAR(1200),
    `email_common` VARCHAR(100),
    `phone` VARCHAR(30),
    `address` VARCHAR(50) NOT NULL,
    `zip` VARCHAR(10) NOT NULL,
    `city` VARCHAR(50) NOT NULL,
    `lang_code` VARCHAR(8),
    `additional_info` VARCHAR(2000),
    `form_id` INT DEFAULT 0,
    `id_old` INT,
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` VARCHAR(30),
    `modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` VARCHAR(30),
    PRIMARY KEY (`id`),
    INDEX `idx_official_name`(`official_name`),
    INDEX `idx_form_id`(`form_id`)
)
ENGINE =InnoDB
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

DROP TABLE IF EXISTS `#__issn_registry_issn_range`;

CREATE TABLE `#__issn_registry_issn_range` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `block` VARCHAR(4) NOT NULL,
    `range_begin` VARCHAR(4) NOT NULL,
    `range_end` VARCHAR(4) NOT NULL,
    `free` INT NOT NULL,
    `taken` INT NOT NULL DEFAULT 0,
    `next` VARCHAR(4) NOT NULL,
    `is_active` boolean not null default 0,
    `is_closed` boolean not null default 0,
    `id_old` INT,
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` VARCHAR(30),
    `modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` VARCHAR(30),
    PRIMARY KEY (`id`)
)
ENGINE =InnoDB
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

DROP TABLE IF EXISTS `#__issn_registry_issn_used`;

CREATE TABLE `#__issn_registry_issn_used` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `issn` VARCHAR(9) NOT NULL,
    `publication_id` INT NOT NULL,
    `issn_range_id` INT NOT NULL,
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` VARCHAR(30),
    PRIMARY KEY (`id`),
    UNIQUE (issn),
    INDEX `idx_issn_range_id` (`issn_range_id`),
    INDEX `idx_publication_id` (`publication_id`)
)
ENGINE =InnoDB
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

DROP TABLE IF EXISTS `#__issn_registry_issn_canceled`;

CREATE TABLE `#__issn_registry_issn_canceled` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `issn` VARCHAR(9) NOT NULL,
    `issn_range_id` INT NOT NULL,
    `canceled` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `canceled_by` VARCHAR(30),
    PRIMARY KEY (`id`),
    UNIQUE (issn),
    INDEX `idx_issn_range_id` (`issn_range_id`),
    INDEX `idx_issn` (`issn`)
)
ENGINE =InnoDB
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

DROP TABLE IF EXISTS `#__issn_registry_message_type`;

CREATE TABLE `#__issn_registry_message_type` (
    `id`       INT(11)     NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL,
    `description` VARCHAR(200) NOT NULL,
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` VARCHAR(30),
    `modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` VARCHAR(30),
    PRIMARY KEY (`id`)
)
ENGINE =InnoDB
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

DROP TABLE IF EXISTS `#__issn_registry_message_template`;

CREATE TABLE `#__issn_registry_message_template` (
    `id`       INT(11)     NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL,
    `subject` VARCHAR(150) NOT NULL,
    `lang_code` VARCHAR(8),
    `message_type_id` INT default 0,
    `message` text NOT NULL,
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` VARCHAR(30),
    `modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` VARCHAR(30),
    PRIMARY KEY (`id`)
)
ENGINE =InnoDB
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

DROP TABLE IF EXISTS `#__issn_registry_message`;

CREATE TABLE `#__issn_registry_message` (
    `id`       INT(11)     NOT NULL AUTO_INCREMENT,
    `recipient` VARCHAR(100) NOT NULL,
    `subject` VARCHAR(150) NOT NULL,
    `message` text NOT NULL,
    `lang_code` VARCHAR(8),
    `message_template_id` INT default 0,
    `message_type_id` INT default 0,
    `publisher_id` INT default 0,
    `form_id` INT default 0,
    `group_message_id` INT default 0,
    `sent` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `sent_by` VARCHAR(30),
    PRIMARY KEY (`id`)
)
ENGINE =InnoDB
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

DROP TABLE IF EXISTS `#__issn_registry_statistic`;

CREATE TABLE `#__issn_registry_statistic` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
)
ENGINE =InnoDB
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;