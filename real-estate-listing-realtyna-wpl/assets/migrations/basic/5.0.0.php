<?php
if(wpl_settings::is_setting_exists('rf_separator') === false) {
	$this->runQuery(<<<'SQL'
	INSERT INTO `#__wpl_settings` (id, `setting_name`, `showable`, `category`, `type`, `title`, `index`) VALUES
	 (364, 'rf_separator', 1, 1, 'separator', 'Data Source', 0.03);
SQL
	);
}

if(wpl_settings::is_setting_exists('property_source') === false) {
	$this->runQuery(<<<'SQL'
	INSERT INTO `#__wpl_settings` (id, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
	 (365, 'property_source', 'wpl', 1, 1, 'select', 'Property Source', NULL, '{"values":[{"key":"wpl","value":"WPL"},{"key":"rf","value":"MLS On The Fly™"}]} ', 0.04);
SQL
	);
}
if(wpl_settings::is_setting_exists('rf_default_user') === false) {
	$this->runQuery(<<<'SQL'
	INSERT INTO `#__wpl_settings` (id, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `options`, `index`) VALUES
	 (366, 'rf_default_user', null, 1, 1, 'select', 'Default User (For MLS On The Fly™)', '{"show_empty":1, "query":"SELECT u.`id` AS `key`,  CONCAT(u.`display_name`,\' (\',u.`user_email`,\')\') AS `value` FROM `#__users` as u INNER JOIN `#__wpl_users` AS wpl ON u.ID = wpl.id WHERE 1"}', 0.04);
SQL
	);
}

$this->runQuery("ALTER TABLE `#__wpl_dbst` ADD COLUMN `is_fly` tinyint(1) NULL DEFAULT 0;");