UPDATE `#__wpl_settings` SET `setting_value`='County, Avenue, Ave, Boulevard, Blvd, Highway, Hwy, Lane, Ln, Square, Sq, Street, St, Road, Rd, Drive, Dr' WHERE `id`='141';
UPDATE `#__wpl_settings` SET `options` = '{"values":[{"key":"Y-m-d:yy-mm-dd","value":"2013-10-19"},{"key":"Y/m/d:yy/mm/dd","value":"2013/10/19"},{"key":"Y.m.d:yy.mm.dd","value":"2013.10.19"},{"key":"m-d-Y:mm-dd-yy","value":"10-19-2013"},{"key":"m/d/Y:mm/dd/yy","value":"10/19/2013"},{"key":"m.d.Y:mm.dd.yy","value":"10.19.2013"},{"key":"d-m-Y:dd-mm-yy","value":"19-10-2013"},{"key":"d/m/Y:dd/mm/yy","value":"19/10/2013"},{"key":"d.m.Y:dd.mm.yy","value":"19.10.2013"}]}' WHERE `id`='27';

UPDATE `#__wpl_settings` SET `params`='' WHERE `id`='90';
INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(204, 'property_page_title_pattern', '[property_type] [listing][glue] [rooms][glue] [bedrooms][glue] [bathrooms][glue] [price][glue] [mls_id]', 1, 4, 'pattern', 'Property Page Title', NULL, NULL, 5.00);
