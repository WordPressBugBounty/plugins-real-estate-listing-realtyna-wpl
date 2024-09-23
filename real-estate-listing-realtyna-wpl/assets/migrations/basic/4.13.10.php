<?php
/**
 * Create default value for select type
 */
$this->runQuery("
UPDATE `#__wpl_dbst_types` set queries_add = 'ALTER TABLE `[TB_PREFIX][TABLE_NAME]` ADD `field_[FIELD_ID]` int(11) NULL DEFAULT [DEFAULT_VALUE]; UPDATE `[TB_PREFIX]wpl_dbst` SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];'
WHERE id = 3;
");