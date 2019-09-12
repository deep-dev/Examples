CREATE TABLE `table1` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`key` int(11) DEFAULT NULL,
`name` varchar(255) NOT NULL,
`size` varchar(48) NOT NULL,
`type` varchar(128) DEFAULT NULL,
`modified` varchar(24) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB;
