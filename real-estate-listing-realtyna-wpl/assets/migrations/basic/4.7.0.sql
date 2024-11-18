DELETE FROM `#__wpl_notifications` WHERE `id`='9';

DELETE FROM `#__wpl_events` WHERE `id`='44';

ALTER TABLE `#__wpl_properties` CHANGE `googlemap_lt` `googlemap_lt` DECIMAL(10,8) NOT NULL DEFAULT '0';

ALTER TABLE `#__wpl_properties` CHANGE `googlemap_ln` `googlemap_ln` DECIMAL(11,8) NOT NULL DEFAULT '0';

CREATE TABLE IF NOT EXISTS `#__wpl_properties2` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `att_numb` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `sent_numb` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `contact_numb` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `inc_in_listings_numb` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `visit_time` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `visit_date` datetime DEFAULT NULL,
  `vids_numb` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
);

INSERT `#__wpl_properties2` (`id`, `att_numb`, `sent_numb`, `contact_numb`, `inc_in_listings_numb`, `visit_time`, `visit_date`, `vids_numb`) SELECT `id`, `att_numb`, `sent_numb`, `contact_numb`, `inc_in_listings_numb`, `visit_time`, `visit_date`, `vids_numb` FROM `#__wpl_properties`;

ALTER TABLE `#__wpl_properties` DROP `att_numb`, DROP `sent_numb`, DROP `contact_numb`, DROP `inc_in_listings_numb`, DROP `visit_time`, DROP `visit_date`, DROP `vids_numb`;

ALTER TABLE `#__wpl_properties` DROP `view`, DROP `parkings`, DROP `street`, DROP `property_title`;
