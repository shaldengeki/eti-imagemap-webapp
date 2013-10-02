CREATE TABLE IF NOT EXISTS `images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `server` tinyint(2) unsigned NOT NULL,
  `hash` char(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'md5',
  `type` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `added_on` datetime NOT NULL,
  `hits` int(10) unsigned NOT NULL,
  `private` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE UNIQUE INDEX `hash` ON images (hash);
CREATE INDEX `added_on,private` ON images (added_on, private);
CREATE INDEX `hits,private` ON images (hits, private)