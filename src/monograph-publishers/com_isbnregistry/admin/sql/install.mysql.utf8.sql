DROP TABLE IF EXISTS `#__isbn_registry_publisher`;

CREATE TABLE `#__isbn_registry_publisher` (
    `id`       INT(11)     NOT NULL AUTO_INCREMENT,
    `official_name` VARCHAR(100) NOT NULL,
    `other_names` VARCHAR(200),
    `previous_names` VARCHAR(300),
    `address` VARCHAR(50) NOT NULL,
    `address_line1` VARCHAR(50),
    `zip` VARCHAR(10) NOT NULL,
    `city` VARCHAR(50) NOT NULL,
    `phone` VARCHAR(30),
    `email` VARCHAR(100) NOT NULL,
    `www` VARCHAR(100),
    `lang_code` VARCHAR(8),
    `contact_person` VARCHAR(100),
    `additional_info` VARCHAR(2000),
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
    `active_identifier_isbn` VARCHAR(20) default '',
    `active_identifier_ismn` VARCHAR(20) default '',
    `promote_sorting` boolean not null default 0,
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` VARCHAR(30),
    `modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` VARCHAR(30),
    PRIMARY KEY (`id`),
    INDEX `idx_official_name` (`official_name`)
)
ENGINE =InnoDB
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

DROP TABLE IF EXISTS `#__isbn_registry_publisher_archive`;

CREATE TABLE `#__isbn_registry_publisher_archive` (
    `id`       INT(11)     NOT NULL AUTO_INCREMENT,
    `publisher_id` INT NOT NULL,
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
    `question_1` VARCHAR(50),
    `question_2` VARCHAR(50),
    `question_3` VARCHAR(50),
    `question_4` VARCHAR(200),
    `question_5` VARCHAR(200),
    `question_6` VARCHAR(50),
    `question_7` VARCHAR(50),
    `question_8` VARCHAR(50),
    `confirmation` VARCHAR(100) NOT NULL,
    `id_old` INT,
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` VARCHAR(30),
    PRIMARY KEY (`id`),
    INDEX `idx_publisher_id` (`publisher_id`)
)
ENGINE =InnoDB
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

DROP TABLE IF EXISTS `#__isbn_registry_publication`;

CREATE TABLE `#__isbn_registry_publication` (
    `id`       INT(11)     NOT NULL AUTO_INCREMENT,
    `official_name` VARCHAR(100) NOT NULL,
    `publisher_identifier_str` VARCHAR(20),
    `publication_identifier_print` VARCHAR(150) default '',
    `publication_identifier_electronical` VARCHAR(150) default '',
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
    `type` VARCHAR(50),
    `type_other` VARCHAR(100),
    `comments` VARCHAR(2000),
    `fileformat` VARCHAR(25),
    `fileformat_other` VARCHAR(100),
    `no_identifier_granted` boolean not null default 0,
    `on_process` boolean not null default 0,
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` VARCHAR(30),
    `modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` VARCHAR(30),
    PRIMARY KEY (`id`),
    INDEX `idx_official_name` (`official_name`),
    INDEX `idx_publisher_id` (`publisher_id`)
)
ENGINE =InnoDB
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

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
    `canceled` INT NOT NULL DEFAULT 0,
    `next` VARCHAR(5) NOT NULL,
    `is_active` boolean not null default 1,
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
    `canceled` INT NOT NULL DEFAULT 0,
    `deleted` INT NOT NULL DEFAULT 0,
    `next` VARCHAR(5) NOT NULL,
    `is_active` boolean not null default 1,
    `is_closed` boolean not null default 0,
    `id_old` INT,
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` VARCHAR(30),
    `modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` VARCHAR(30),
    PRIMARY KEY (`id`),
    UNIQUE (publisher_identifier),
    INDEX `idx_publisher_id` (`publisher_id`),
    INDEX `idx_isbn_range_id` (`isbn_range_id`)
)
ENGINE =InnoDB
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
    `canceled` INT NOT NULL DEFAULT 0,    
    `next` VARCHAR(7) NOT NULL,
    `is_active` boolean not null default 1,
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
    `canceled` INT NOT NULL DEFAULT 0,
    `deleted` INT NOT NULL DEFAULT 0,
    `next` VARCHAR(7) NOT NULL,
    `is_active` boolean not null default 1,
    `is_closed` boolean not null default 0,
    `id_old` INT,
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` VARCHAR(30),
    `modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` VARCHAR(30),
    PRIMARY KEY (`id`),
    UNIQUE (publisher_identifier),
    INDEX `idx_publisher_id` (`publisher_id`),
    INDEX `idx_ismn_range_id` (`ismn_range_id`)
)
ENGINE =InnoDB
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

DROP TABLE IF EXISTS `#__isbn_registry_publisher_isbn_range_canceled`;

CREATE TABLE `#__isbn_registry_publisher_isbn_range_canceled` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `identifier` VARCHAR(13) NOT NULL,
    `category` INT NOT NULL,
    `range_id` INT NOT NULL,
    `id_old` INT,
    `canceled` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `canceled_by` VARCHAR(30),
    PRIMARY KEY (`id`),
    UNIQUE (identifier),
    INDEX `idx_range_id` (`range_id`),
    INDEX `idx_identifier` (`identifier`),
    INDEX `idx_category` (`category`)
)
ENGINE =InnoDB
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

DROP TABLE IF EXISTS `#__isbn_registry_publisher_ismn_range_canceled`;

CREATE TABLE `#__isbn_registry_publisher_ismn_range_canceled` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `identifier` VARCHAR(13) NOT NULL,
    `category` INT NOT NULL,
    `range_id` INT NOT NULL,
    `id_old` INT,
    `canceled` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `canceled_by` VARCHAR(30),
    PRIMARY KEY (`id`),
    UNIQUE (identifier),
    INDEX `idx_range_id` (`range_id`),
    INDEX `idx_identifier` (`identifier`),
    INDEX `idx_category` (`category`)
)
ENGINE =InnoDB
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

DROP TABLE IF EXISTS `#__isbn_registry_message_type`;

CREATE TABLE `#__isbn_registry_message_type` (
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

DROP TABLE IF EXISTS `#__isbn_registry_message_template`;

CREATE TABLE `#__isbn_registry_message_template` (
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

DROP TABLE IF EXISTS `#__isbn_registry_message`;

CREATE TABLE `#__isbn_registry_message` (
    `id`       INT(11)     NOT NULL AUTO_INCREMENT,
    `recipient` VARCHAR(100) NOT NULL,
    `subject` VARCHAR(150) NOT NULL,
    `message` text NOT NULL,
    `lang_code` VARCHAR(8),
    `message_template_id` INT default 0,
    `message_type_id` INT default 0,
    `publisher_id` INT default 0,
    `publication_id` INT default 0,
    `batch_id` INT default 0,
    `has_attachment` boolean not null default 0,
    `attachment_name` VARCHAR(30) default '',
    `group_message_id` INT NOT NULL,
    `sent` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `sent_by` VARCHAR(30),
    PRIMARY KEY (`id`)
)
ENGINE =InnoDB
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

DROP TABLE IF EXISTS `#__isbn_registry_identifier_batch`;

CREATE TABLE `#__isbn_registry_identifier_batch` (
    `id`       INT(11)     NOT NULL AUTO_INCREMENT,
    `identifier_type` VARCHAR(4) NOT NULL,
    `identifier_count` INT NOT NULL,
    `identifier_canceled_count` INT NOT NULL,
    `identifier_canceled_used_count` INT NOT NULL,
    `identifier_deleted_count` INT NOT NULL,
    `publisher_id` INT NOT NULL,
    `publication_id` INT NOT NULL,
    `publisher_identifier_range_id` INT NOT NULL,
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` VARCHAR(30),
    PRIMARY KEY (`id`),
    INDEX `idx_publisher_id` (`publisher_id`),
    INDEX `idx_publisher_identifier_range_id` (`publisher_identifier_range_id`),
    INDEX `idx_publication_id` (`publication_id`)
)
ENGINE =InnoDB
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

DROP TABLE IF EXISTS `#__isbn_registry_identifier`;

CREATE TABLE `#__isbn_registry_identifier` (
    `id`       INT(11)     NOT NULL AUTO_INCREMENT,
    `identifier` VARCHAR(20) NOT NULL,
    `identifier_batch_id` INT NOT NULL,
    `publisher_identifier_range_id` INT NOT NULL,
    `publication_type` VARCHAR(25) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`),
    UNIQUE (identifier),
    INDEX `idx_identifier` (`identifier`),
    INDEX `idx_identifier_batch_id` (`identifier_batch_id`)
)
ENGINE =InnoDB
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

DROP TABLE IF EXISTS `#__isbn_registry_identifier_canceled`;

CREATE TABLE `#__isbn_registry_identifier_canceled` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `identifier` VARCHAR(20) NOT NULL,
    `identifier_type` VARCHAR(4) NOT NULL,
    `category` INT NOT NULL,
    `publisher_id` INT NOT NULL,
    `publisher_identifier_range_id` INT NOT NULL,
    `canceled` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `canceled_by` VARCHAR(30),
    PRIMARY KEY (`id`),
    UNIQUE (identifier),
    INDEX `idx_publisher_id` (`publisher_id`),
    INDEX `idx_publisher_identifier_range_id` (`publisher_identifier_range_id`),
    INDEX `idx_identifier` (`identifier`),
    INDEX `idx_category` (`category`)
)
ENGINE =InnoDB
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

DROP TABLE IF EXISTS `#__isbn_registry_group_message`;

CREATE TABLE `#__isbn_registry_group_message` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `isbn_categories` VARCHAR(20) default '',
    `isbn_publishers_count` INT NOT NULL,
    `ismn_categories` VARCHAR(20) default '',
    `ismn_publishers_count` INT NOT NULL,
    `publishers_count` INT NOT NULL,
    `has_quitted` boolean not null default 0,
    `message_type_id` INT NOT NULL,
    `success_count` INT NOT NULL,
    `fail_count` INT NOT NULL,
    `no_email_count` INT NOT NULL,
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` VARCHAR(30),
    `finished` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`),
    INDEX `idx_message_type_id` (`message_type_id`)
)
ENGINE =InnoDB
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

DROP TABLE IF EXISTS `#__isbn_registry_statistic`;

CREATE TABLE `#__isbn_registry_statistic` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
)
ENGINE =InnoDB
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;