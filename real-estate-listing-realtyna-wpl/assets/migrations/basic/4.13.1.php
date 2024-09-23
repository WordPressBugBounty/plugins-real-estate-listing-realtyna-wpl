<?php

$this->runQuery(<<<'SQL'
INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(420, 'watermark_size', NULL, 1, 2, 'text', 'Watermark Size', NULL, NULL, 7.50),
(421, 'watermark_size_unit', NULL, 1, 2, 'text', 'Watermark Size Unit', NULL, NULL, 7.51);
SQL
);

// Add Auto Purge Feature For wpl_logs DB Table
$this->runQuery(<<<'SQL'
INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
    (450, 'log_auto_purge_status', 'disable', 1, 1, 'select', 'Automatic Log Purge', NULL, '{"values":[{"key":"enable","value":"Enable" },{"key":"disable","value":"Disable" }]}', 53.10);
SQL
);

$this->runQuery(<<<'SQL'
INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
    (455, 'log_auto_purge_ttl', '30', 1, 1, 'select', 'Auto-Purge Logs After', NULL, '{"values":[{"key":10,"value":"10 Days" },{"key":30,"value":"1 Month" },{"key":90,"value":"3 Months" },{"key":180,"value":"6 Months" }]}', 53.15);
SQL
);

$this->runQuery(<<<'SQL'
INSERT INTO `#__wpl_cronjobs` (`id`, `cronjob_name`, `period`, `class_location`, `class_name`, `function_name`, `params`, `enabled`, `latest_run`) VALUES
    (50, 'Auto Purge WPL Log Data', 24, 'libraries.logs', 'wpl_logs', 'auto_purge', '', 1, '2023-01-01 00:00:00');
SQL
);

$this->runQuery(<<<'SQL'
INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES(401, 'manual_title_generation', '1', 1, 4, 'checkbox', 'Manual Property Title And Page Title', '{"tooltip":"If you enable it, properties titles & page titles will not be reset in clearing the cache."}', '', 8.00);
SQL
);

/**
 * Make Boolean type nullable
 */
$this->runQuery("
UPDATE `#__wpl_dbst_types` set queries_add = 'ALTER TABLE `[TB_PREFIX][TABLE_NAME]` ADD `field_[FIELD_ID]` TINYINT( 4 ) NULL DEFAULT [DEFAULT_VALUE]; UPDATE `[TB_PREFIX]wpl_dbst` SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];'
WHERE id = 19;
");