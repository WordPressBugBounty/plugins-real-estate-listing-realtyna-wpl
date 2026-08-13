<?php

$this->runQuery("ALTER TABLE `#__wpl_users` MODIFY `expiry_date` DATETIME NULL DEFAULT NULL");
$this->runQuery("UPDATE `#__wpl_users` SET `expiry_date` = NULL WHERE `expiry_date` IS NOT NULL AND `expiry_date`<'1000-01-01 00:00:00'");

/** DB Structure runs these templates when an admin adds a date field, MySQL 8 rejects a zero date default **/
$this->runQuery("UPDATE `#__wpl_dbst_types` SET `queries_add` = REPLACE(`queries_add`, CONCAT('date DEFAULT ', CHAR(39), '0000-00-00', CHAR(39)), 'date DEFAULT NULL') WHERE `type` = 'date'");
$this->runQuery("UPDATE `#__wpl_dbst_types` SET `queries_add` = REPLACE(`queries_add`, CONCAT('datetime DEFAULT ', CHAR(39), '0000-00-00 00:00:00', CHAR(39)), 'datetime DEFAULT NULL') WHERE `type` = 'datetime'");
