ALTER TABLE `#__wpl_properties` ADD `last_finalize_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `add_date`;
ALTER TABLE `#__wpl_properties` ADD INDEX (`last_finalize_date`);

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(321, 'listing_404_redirect', '0', 1, 1, 'checkbox', 'Not Found Redirection', '{"tooltip":"Redirect the user to property listing page if property not found!"}', '', 102.00);