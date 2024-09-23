<?php
/**
 * remove deprecated cronjob
 */
$this->runQuery("DELETE FROM `#__wpl_cronjobs` WHERE `id` = 1");