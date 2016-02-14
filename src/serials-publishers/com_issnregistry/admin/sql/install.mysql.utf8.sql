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
    `publication_count` INT,
    `publisher_id` INT,
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

DROP TABLE IF EXISTS `#__issn_registry_publication`;

CREATE TABLE `#__issn_registry_publication` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(200) NOT NULL,
    `place_of_publication` VARCHAR(100) NOT NULL,
    `printer` VARCHAR(100),
    `issued_from_year` VARCHAR(4),
    `issued_from_number` VARCHAR(20),
    `frequency` VARCHAR(30),
    `language` VARCHAR(50),
    `publication_type` VARCHAR(25),
    `publication_type_other` VARCHAR(50),
    `medium` VARCHAR(7),
    `medium_other` VARCHAR(50),
    `url` VARCHAR(100),
    `previous_title` VARCHAR(100),
    `previous_issn` VARCHAR(9),
    `previous_title_last_issue` VARCHAR(20),
    `main_series_title` VARCHAR(100),
    `main_series_issn` VARCHAR(9),
    `subseries_title` VARCHAR(100),
    `subseries_issn` VARCHAR(9),
    `another_medium_title` VARCHAR(100),
    `another_medium_issn` VARCHAR(9),
    `additional_info` VARCHAR(500),
    `form_id` INT,
    `publisher_id` INT,
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` VARCHAR(30),
    `modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` VARCHAR(30),
    PRIMARY KEY (`id`),
    INDEX `idx_title` (`title`),
    INDEX `idx_form_id` (`form_id`),
    INDEX `idx_publisher_id` (`publisher_id`)
)
ENGINE =InnoDB
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;

DROP TABLE IF EXISTS `#__issn_registry_publisher`;

CREATE TABLE `#__issn_registry_publisher` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `publisher` VARCHAR(100) NOT NULL,
    `contact_person` VARCHAR(100),
    `email` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(30),
    `address` VARCHAR(50) NOT NULL,
    `zip` VARCHAR(10) NOT NULL,
    `city` VARCHAR(50) NOT NULL,
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` VARCHAR(30),
    `modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` VARCHAR(30),
    PRIMARY KEY (`id`),
    INDEX `idx_publisher` (`publisher`)
)
ENGINE =InnoDB
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8
COLLATE utf8_swedish_ci;