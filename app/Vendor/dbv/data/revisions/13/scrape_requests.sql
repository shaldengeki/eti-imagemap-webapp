ALTER TABLE `scrape_requests` ADD `permanent` TINYINT(2) UNSIGNED NOT NULL DEFAULT '0',
ADD `private` TINYINT(2) UNSIGNED NOT NULL DEFAULT '1';