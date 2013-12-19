CREATE TABLE IF NOT EXISTS `image_lists_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image_list_id` int(10) unsigned NOT NULL,
  `image_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;