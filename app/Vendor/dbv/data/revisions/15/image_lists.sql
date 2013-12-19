CREATE TABLE IF NOT EXISTS `image_lists` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(140) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created` DATETIME NOT NULL,
  `updated` DATETIME NOT NULL,
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `image_count` int(10) unsigned NOT NULL DEFAULT '0',
  `follow_count` int(10) unsigned NOT NULL DEFAULT '0',
  `private` tinyint(2) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE UNIQUE INDEX `name,user_id` ON image_lists (name,user_id);
CREATE INDEX `follow_count` ON image_lists (follow_count);
CREATE INDEX `user_id,private` ON image_lists (user_id, private);