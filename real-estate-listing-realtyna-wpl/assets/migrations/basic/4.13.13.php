<?php
// this feature should be deleted because it causes some issues and the logic of it is incorrect
$this->runQuery("DELETE FROM `#__wpl_settings` WHERE `setting_name` = 'map_limit_marker'");