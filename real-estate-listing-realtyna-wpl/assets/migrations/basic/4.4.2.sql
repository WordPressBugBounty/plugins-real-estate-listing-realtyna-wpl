INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(335, 'realtyna_signature_separator', '', 1, 1, 'separator', 'Realtyna Signature', '', '', 335.00),
(336, 'realtyna_affiliate_id', '', 1, 1, 'text', 'Your Affiliate ID', '{"tooltip":"You can create an affiliate account here: https://realtyna.com/affiliate-area/ and earn money."}', '', 335.01);

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(337, 'realtyna_signature', '1', 1, 1, 'select', 'Powered by Realtyna', '{"tooltip":"By Disabling this. The Realtyna logo from the footer of your listings will be removed."}', '{"values":[{"key":"1","value":"Show"},{"key":"0","value":"Hide"}]}', 335.02);

DELETE FROM `#__wpl_settings` WHERE `setting_name` LIKE '%googlemap_per_day_hits_%';

UPDATE `#__wpl_settings` SET `params`='{\"tooltip\":\"If you hide it, you can place a Google Maps widget into your desired sidebar. Then your website users can view the map using the Google Maps widget.\"}' WHERE `id`='257';
UPDATE `#__wpl_settings` SET `params`='{\"tooltip\":\"Hide the marker from maps when the geo point is not available or is set to 0,0.\"}' WHERE `id`='254';