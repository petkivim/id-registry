DROP TABLE IF EXISTS `#__isbn_registry_publisher`;

CREATE TABLE `#__isbn_registry_publisher` (
	`id`       INT(11)     NOT NULL AUTO_INCREMENT,
	`data_source` VARCHAR(10) NOT NULL,
	`payment_method` VARCHAR(50) NOT NULL,
	`category` VARCHAR(3) NOT NULL,
	`first_name` VARCHAR(50) NOT NULL,
	`last_name` VARCHAR(50) NOT NULL,
	`organization` VARCHAR(100),
	`address` VARCHAR(50) NOT NULL,
	`zip` VARCHAR(10) NOT NULL,
	`city` VARCHAR(50) NOT NULL,
	`country` VARCHAR(50) NOT NULL,
	`phone` VARCHAR(30),
	`email` VARCHAR(100) NOT NULL,
	`amount` VARCHAR(20) NOT NULL,
	`permission` boolean not null default 0,
	`donation_timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`stamp` VARCHAR(50),
	`co_payment_id` VARCHAR(50),
	`confirmation_status` INTEGER not null default 0,
	`confirmation` boolean not null default 0,
	`confirmation_timestamp` TIMESTAMP,
	`confirmation_stamp` VARCHAR(50),
	`confirmation_action` VARCHAR(10),
	`published` tinyint(4) NOT NULL,
	PRIMARY KEY (`id`)
)
	ENGINE =MyISAM
	AUTO_INCREMENT =0
	DEFAULT CHARSET =utf8
	COLLATE utf8_swedish_ci;

INSERT INTO `#__isbn_registry_publisher` (`data_source`, `category`, `first_name`, `last_name`, `address`, `zip`, `city`, `country`, `amount`, `phone`, `email`,`payment_method`, `confirmation`, `permission`, `published`, `organization`) VALUES
('ADMIN', 'PRI', 'Heikki', 'Aakkonen', 'Kettukuja 6', '00100', 'Helsinki', 'Suomi', '25,00', '0400123456', 'teppo.testaaja@pkrete.com','PAYPAL', true, true, true, ''),
('ADMIN', 'PRI', 'Tapio', 'Aäkkonen', 'Viulutie 123', '004200', 'Helsinki', 'Suomi', '55,00', '', 'teppo.testaaja@pkrete.com','PAYPAL', true, true, true, ''),
('ADMIN', 'PRI', 'Tanja', 'Ääkkönen', 'Vesitie 100', '05678', 'Nilsiä', 'Suomi', '125,00', '091234567', 'teppo.testaaja@pkrete.com','PAYPAL', true, true, true, ''),
('ADMIN', 'PRI', 'Teppo', 'Testaaja', 'Hiisikuja 5', '04230', 'Kerava', 'Suomi', '25,00', '', 'teppo.testaaja@pkrete.com','PAYPAL', true, true, true, ''),
('ADMIN', 'PRI', 'Tiina', 'Teekkari', 'Kaikukatu 6', '00530', 'Helsinki', 'Suomi', '50,00', '', 'tiina.teekkari@pkrete.com','CHECKOUT', true, true, true, '');
