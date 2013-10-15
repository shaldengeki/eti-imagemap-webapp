ALTER TABLE `images` ADD `tags` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `private`,
ADD FULLTEXT (
`tags`
);