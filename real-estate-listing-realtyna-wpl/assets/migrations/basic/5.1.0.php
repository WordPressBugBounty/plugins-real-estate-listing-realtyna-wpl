<?php
$this->runQuery("INSERT INTO `#__wpl_cronjobs` (`id`, `cronjob_name`, `period`, `class_location`, `class_name`, `function_name`, `params`, `enabled`, `latest_run`) VALUES
(40, 'Update Openhouse tag', 1, 'libraries.property', 'wpl_property', 'update_openhouse_tag', '', 1, '2025-09-10 13:19:29');");