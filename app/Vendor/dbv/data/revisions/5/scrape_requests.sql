CREATE TABLE IF NOT EXISTS `scrape_requests` (
  `user_id` int(10) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `progress` int(3) unsigned NOT NULL,
  `password` varchar(30) COLLATE utf8_unicode_ci NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE INDEX `password,date` ON scrape_requests (password,date);