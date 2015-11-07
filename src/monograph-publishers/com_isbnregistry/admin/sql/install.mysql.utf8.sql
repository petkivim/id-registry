DROP TABLE IF EXISTS `#__isbn_registry_publisher`;

CREATE TABLE `#__isbn_registry_publisher` (
	`id`       INT(11)     NOT NULL AUTO_INCREMENT,
	`official_name` VARCHAR(100) NOT NULL,
	`other_names` VARCHAR(200),
	`address` VARCHAR(50) NOT NULL,
	`zip` VARCHAR(10) NOT NULL,
	`city` VARCHAR(50) NOT NULL,
	`phone` VARCHAR(30),
        `fax` VARCHAR(30),
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
	`created` TIMESTAMP,
        `created_by` VARCHAR(30),
        `modified` TIMESTAMP,
        `modified_by` VARCHAR(30),
	PRIMARY KEY (`id`)
)
	ENGINE =MyISAM
	AUTO_INCREMENT =0
	DEFAULT CHARSET =utf8
	COLLATE utf8_swedish_ci;

INSERT INTO `#__isbn_registry_publisher` (`official_name`, `other_names`, `address`, `zip`, `city`, `phone`, `fax`, `email`,`www`, `contact_person`, `question_1`, `question_2`, `question_3`, `question_4`, `question_5`, `question_6`, `question_7`, `question_8`, `confirmation`, `lang_code`, `created`, `created_by`) VALUES
('Aa-kustantamo', 'Toinen nimi',  'Kettukuja 6', '00100', 'Helsinki', '0400123456', '09123456', 'teppo.testaaja@pkrete.com','', 'Matti Virtanen', 'Vastaus 1', 'Vastaus 2', 'Vastaus 3', 'Vastaus 4', 'Vastaus 5', 'Vastaus 6', 'Vastaus 7', 'Vastaus 8', '07.11.2015 Matti Virtanen', 'fi-FI', NOW(), 'SYSTEM'),
('Aä', '', 'Viulutie 123', '004200', 'Helsinki', '', '0998765432', 'teppo.testaaja@pkrete.com','', 'Matti Virtanen',  'Vastaus 1', 'Vastaus 2', 'Vastaus 3', 'Vastaus 4', 'Vastaus 5', 'Vastaus 6', 'Vastaus 7', 'Vastaus 8', '07.11.2015 Matti Virtanen', 'fi-FI', NOW(), 'SYSTEM'),
('Ää', '', 'Vesitie 100', '05678', 'Nilsiä', '091234567', '', 'teppo.testaaja@pkrete.com','', 'Matti Virtanen',  'Vastaus 1', 'Vastaus 2', 'Vastaus 3', 'Vastaus 4', 'Vastaus 5', 'Vastaus 6', 'Vastaus 7', 'Vastaus 8', '07.11.2015 Matti Virtanen', 'en-GB', NOW(), 'SYSTEM'),
('Testikustantamo', 'Test, Koe', 'Hiisikuja 5', '04230', 'Kerava', '050 12346789', '', 'teppo.testaaja@pkrete.com','http://www.test.com', 'Matti Virtanen', 'Vastaus 1', 'Vastaus 2', 'Vastaus 3', 'Vastaus 4', 'Vastaus 5', 'Vastaus 6', 'Vastaus 7', 'Vastaus 8', '07.11.2015 Matti Virtanen', 'fi-FI', NOW(), 'SYSTEM'),
('Edita', '', 'Kaikukatu 6', '00530', 'Helsinki', '09 123 4556', '', 'tiina.teekkari@pkrete.com','http://www.edita.fi', 'Matti Virtanen', 'Vastaus 1', 'Vastaus 2', 'Vastaus 3', 'Vastaus 4', 'Vastaus 5', 'Vastaus 6', 'Vastaus 7', 'Vastaus 8', '07.11.2015 Matti Virtanen', 'en-GB', NOW(), 'SYSTEM');
