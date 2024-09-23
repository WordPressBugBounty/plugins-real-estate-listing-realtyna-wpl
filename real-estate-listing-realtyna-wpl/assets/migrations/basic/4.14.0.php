<?php
$this->runQuery("
INSERT INTO `#__wpl_dbst_types` (`id`, `kind`, `type`, `enabled`, `index`, `queries_add`, `queries_delete`, `options`) VALUES
(26, '[2]', 'upload', 1, 1.00, 'ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]` text NULL; UPDATE `#__wpl_dbst` SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];', 'ALTER TABLE `#__[TABLE_NAME]` DROP `field_[FIELD_ID]`;', NULL);
");