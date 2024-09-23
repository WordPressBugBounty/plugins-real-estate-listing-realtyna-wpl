ALTER TABLE `#__wpl_activities` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_addons` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_cronjobs` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_dbcat` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_dbst` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_dbst_types` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_events` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_extensions` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_filters` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_items` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_item_categories` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_kinds` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_listing_types` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_location1` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_location2` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_location3` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_location4` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_location5` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_location6` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_location7` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_locationtextsearch` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_locationzips` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_logs` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_menus` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_notifications` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_properties` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_property_types` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_room_types` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_settings` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_setting_categories` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_sort_options` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_units` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_unit_types` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_users` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `#__wpl_user_group_types` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

INSERT INTO `#__wpl_sort_options` (`id`, `kind`, `name`, `field_name`, `enabled`, `index`) VALUES
(13, '[0][1]', 'Featured', 'p.sp_featured', 1, 14.00);

UPDATE `#__wpl_extensions` SET `client`='2' WHERE `id`='89';
UPDATE `#__wpl_extensions` SET `client`='2' WHERE `id`='88';
UPDATE `#__wpl_extensions` SET `client`='2' WHERE `id`='109';

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(277, 'google_serverside_api_key', null, 1, 1, 'text', 'Google API key (Server Side)', '{\"tooltip\":\"Please note that for the Server Side API Key, you should not set any referrer restrictions (or IP restrictions).\"}', NULL, 55.05);

UPDATE `#__wpl_settings` SET `title`='Google API key (Client Side)' WHERE `id`='126';

ALTER TABLE `#__wpl_items` ADD INDEX (`index`);
ALTER TABLE `#__wpl_items` ADD INDEX (`parent_id`, `item_type`);

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(294, 'advanced_markers_separator', NULL, 1, 1, 'separator', 'Advanced Markers', NULL, NULL, 240.60),
(293, 'advanced_markers', '{"status":"0","listing_types":{"9":"#29a9df","10":"#cccc24","12":"#d326d6"},"property_types":{"7":"apartment.svg","6":"commercial.svg","13":"income.svg","14":"land.svg","70":"loft.svg","71":"rental.svg","72":"residential.svg","73":"vacant_land.svg","74":"villa.svg"}}', 1, 1, 'advanced_markers', '', NULL, NULL, 240.70);

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(107, 'shortcode', 'Widget shortcode', 0, 'For showing Widgets instance', 1, 'wpl_widget_instance', 'wpl_widget->load_widget_instance', '', '', '', '', 0, 99.99, 2);

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(295, 'geolocation_listings', '0', 1, 1, 'checkbox', 'Geolocation Listings', '{"tooltip":"Show listings based on users current geolocation."}', NULL, 100.10);

ALTER TABLE `#__wpl_properties` ADD `inc_in_listings_numb` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0' AFTER `contact_numb`, ADD INDEX (`inc_in_listings_numb`);
DELETE FROM `#__wpl_items` WHERE `item_type`='contact_stat';
DELETE FROM `#__wpl_items` WHERE `item_type`='inc_in_listings_stat';
DELETE FROM `#__wpl_items` WHERE `item_type`='view_parent_stat';

INSERT INTO `#__wpl_addons` (`id`, `name`, `version`, `addon_name`, `params`, `update`, `update_key`, `support_key`, `updatable`, `message`) VALUES
(45, 'IDX', '1.0.0', 'idx', '', '', '', '', 1, '');

INSERT INTO `#__wpl_menus` (`id`, `client`, `type`, `parent`, `page_title`, `menu_title`, `capability`, `menu_slug`, `function`, `separator`, `enabled`, `index`, `position`, `dashboard`) VALUES
(341, 'backend', 'submenu', 'WPL_main_menu', 'IDX Addon', 'IDX Addon', 'admin', 'wpl_addon_idx', 'b:addon_idx:home', 1, 1, 341, 0, 1);

CREATE TABLE IF NOT EXISTS `#__wpl_addon_idx_users`
(
  `id` int(10) unsigned AUTO_INCREMENT primary key,
  `secret_key` varchar(64) NOT NULL,
  `token` text NOT NULL,
  `email` varchar(64) NOT NULL,
  `created_at` timestamp DEFAULT '0000-00-00 00:00:00' NOT NULL,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
  constraint Dublicate unique (secret_key, email)
);

CREATE TABLE IF NOT EXISTS `#__wpl_addon_idx_users_providers`
(
  `id` int(10) unsigned AUTO_INCREMENT primary key,
  `mls_id` int(2) NOT NULL,
  `user_secret_key` varchar(64) NOT NULL,
  `provider_name` varchar(128) NOT NULL,
  `provider_short_name` varchar(16) NOT NULL,
  `listing_type` varchar(32) NOT NULL,
  `property_type` varchar(32) NOT NULL,
  `created_at` timestamp DEFAULT '0000-00-00 00:00:00' NOT NULL,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TABLE IF NOT EXISTS `#__wpl_addon_idx_payments`
(
  `id` int(10) unsigned AUTO_INCREMENT primary key,
  `secret_key` varchar(64) NOT NULL,
  `provider` varchar(16) NULL,
  `transaction_token` varchar(64) NOT NULL,
  `created_at` timestamp DEFAULT '0000-00-00 00:00:00' NOT NULL,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TABLE IF NOT EXISTS `#__wpl_addon_idx_service_logs`
(
  `id` int AUTO_INCREMENT primary key,
  `ip_addr` varchar(64) NOT NULL,
  `error_code` varchar(128) NOT NULL,
  `date` timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TABLE IF NOT EXISTS `#__wpl_addon_idx_tasks`
(
  `task_id` int(10) unsigned AUTO_INCREMENT primary key,
  `mls_id` int NOT NULL,
  `provider` varchar(16) NOT NULL,
  `secret` varchar(64) NOT NULL,
  `token` text NOT NULL,
  `page` int NULL,
  `status` enum('started', 'completed', 'error') NULL,
  `ts` timestamp DEFAULT '0000-00-00 00:00:00' NOT NULL,
  `ts_updated` timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TABLE IF NOT EXISTS `#__wpl_addon_idx_user_wizard_steps`
(
  `id` int(10) unsigned AUTO_INCREMENT primary key,
  `secret_key` varchar(64) NOT NULL,
  `step_name` varchar(64) NOT NULL,
  `step_value` int(1) NOT NULL,
  `created_at` timestamp DEFAULT '0000-00-00 00:00:00' NOT NULL,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TABLE IF NOT EXISTS `#__wpl_addon_idx_trial_logs`
(
  `id` int AUTO_INCREMENT primary key,
  `secret` varchar(64) NOT NULL,
  `status` char DEFAULT '0' NOT NULL,
  `date` timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL
);

ALTER TABLE `#__wpl_addon_idx_tasks` ADD `first_page` INT(11) NULL DEFAULT '0' AFTER `page`, ADD `completed_page` INT(11) NULL DEFAULT '0' AFTER `first_page`;