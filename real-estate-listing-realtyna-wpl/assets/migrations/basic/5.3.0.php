<?php
$this->runQuery(<<<'SQL'
INSERT INTO `#__wpl_cronjobs` (`id`, `cronjob_name`, `period`, `class_location`, `class_name`, `function_name`, `params`, `enabled`, `latest_run`) VALUES
    (41, 'Auto Purge RF Properties', 4, 'libraries.rf_shell.rf_property', 'wpl_rf_property', 'auto_purge', '', 1, '2023-01-01 00:00:00');
SQL
);