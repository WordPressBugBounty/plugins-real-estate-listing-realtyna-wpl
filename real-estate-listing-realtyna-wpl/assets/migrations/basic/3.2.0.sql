UPDATE `#__wpl_settings` SET `title`='Listing Link Pattern' WHERE `id`='90';
UPDATE `#__wpl_settings` SET `title`='Listing Page Title' WHERE `id`='204';

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(218, 'seo_patterns_separator', '', 1, 4, 'separator', 'SEO Patterns', '', '', 3.50),
(219, 'property_title_pattern', '[property_type] [listing]', 1, 4, 'pattern', 'Listing Title', NULL, NULL, 4.50),
(220, 'meta_description_pattern', '[bedrooms] [str:Bedrooms:bedrooms] [rooms] [str:Rooms:rooms] [str:With:bathrooms] [bathrooms] [str:Bathrooms:bathrooms] [property_type] [listing_type] [field_54] [field_42] [str:On the:field_55] [field_55] [str:Floor:field_55] [str:In] [location]', 1, 4, 'pattern', 'Meta Description', NULL, NULL, 6.00),
(221, 'meta_keywords_pattern', '[location][glue] [bedrooms] [str:Bedrooms:bedrooms][glue] [rooms] [str:Rooms:rooms][glue][bathrooms][str:Bathrooms:bathrooms][glue][property_type][glue][listing_type][glue][field_54][glue][field_42][glue][field_55][glue][listing_id]', 1, 4, 'pattern', 'Meta Keywords', NULL, NULL, 7.00);

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(222, 'seo_adv_patterns_separator', '', 1, 4, 'separator', 'Advanced Patterns', '', '', 22.20),
(223, 'seo_patterns', '', 1, 4, 'seo_patterns', 'Advanced Patterns', '', '', 22.30);

