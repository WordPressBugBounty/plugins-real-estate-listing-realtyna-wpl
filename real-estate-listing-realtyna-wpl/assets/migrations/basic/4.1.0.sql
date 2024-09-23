UPDATE `#__wpl_dbst` SET `options`='{"params":{"1":{"enabled":"1"},"2":{"enabled":"1"},"3":{"enabled":"1"},"4":{"enabled":"1"},"5":{"enabled":"1"},"6":{"enabled":"1"},"7":{"enabled":"1"},"zips":{"enabled":"1"}}}' WHERE `type`='locations' AND `kind` IN (0,1,2,4);

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(297, 'show_plisting_visits', '0', 1, 1, 'checkbox', 'Show Listing Visits', '{"tooltip":"If enabled, total property visits will show on the search results pages."}', NULL, 101.00);

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(298, 'gre_separator', NULL, 1, 1, 'separator', 'Google Recaptcha', '', '', 250.05);

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(299, 'gre_enable', '0', 1, 1, 'select', 'Google Recaptcha', NULL, '{"values":[{"key":"0","value":"Disable"},{"key":"1","value":"Enable"}]}', 250.10);

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(300, 'gre_site_key', NULL, 1, 1, 'text', 'Site Key', NULL, '', 250.15);

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(301, 'gre_secret_key', NULL, 1, 1, 'text', 'Secret Key', NULL, '', 250.20);

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(302, 'gre_user_contact_activity', '0', 1, 1, 'checkbox', 'User Contact', NULL, '', 250.25);

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(303, 'gre_listing_contact_activity', '0', 1, 1, 'checkbox', 'Listing Contact', NULL, '', 250.30);

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(304, 'gre_widget_contact_activity', '0', 1, 1, 'checkbox', 'Favorites Widget', NULL, '', 250.35);

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(310, 'gre_report_listing', '0', 1, 1, 'checkbox', 'Report Listing', NULL, '', 250.40);

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(311, 'gre_send_to_friend', '0', 1, 1, 'checkbox', 'Send to Friend', NULL, '', 250.50);

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(312, 'gre_request_visit', '0', 1, 1, 'checkbox', 'Request a Visit', NULL, '', 250.45);