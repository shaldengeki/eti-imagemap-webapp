ALTER TABLE `users` CHANGE `updated` `modified` DATETIME NOT NULL;
ALTER TABLE `users` CHANGE `last_ip` `last_ip` VARCHAR(11) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;