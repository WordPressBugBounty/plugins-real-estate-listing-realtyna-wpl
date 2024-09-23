INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(123, 'javascript', 'jQuery Time Picker', 0, '', 1, 'jquery-time-picker', 'js/libraries/wpl.jquery.timepicker.min.js', '', '', '', '', 0, 202.00, 2);

ALTER TABLE `#__wpl_users`
CHANGE `main_email` `main_email` varchar(255) COLLATE 'utf8_general_ci' NULL,
CHANGE `membership_type` `membership_type` varchar(10) COLLATE 'utf8_general_ci' NULL,
CHANGE `maccess_property_types` `maccess_property_types` varchar(255) COLLATE 'utf8_general_ci' NULL,
CHANGE `maccess_upgradable_to` `maccess_upgradable_to` text COLLATE 'utf8_general_ci' NULL,
CHANGE `textsearch` `textsearch` text COLLATE 'utf8_general_ci' NULL,
CHANGE `location_text` `location_text` varchar(255) COLLATE 'utf8_general_ci' NULL,
CHANGE `rendered` `rendered` text COLLATE 'utf8_general_ci' NULL;

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(124, 'action', 'User Login', 0, 'Calls after user login', 1, 'wp_login', 'wpl_users->user_loggedin', '10', '2', '', '', 0, 99.99, 2);