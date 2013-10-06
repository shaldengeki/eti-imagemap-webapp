ALTER TABLE `users` ADD `image_count` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `role`;
ALTER TABLE `users` ADD INDEX `last_ip` (`last_ip`);