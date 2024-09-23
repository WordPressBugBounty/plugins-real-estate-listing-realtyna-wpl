<?php
/**
 * remove deleted js files from database
 *
 * modernizer -> id = 108
 * qtips-> id = 110
 * spinner-> id = 114
 * realtyna-lightbox -> id = 115
 * realtyna-utility -> id = 117
 * realtyna-tagging -> id = 118
 */
$this->runQuery("
DELETE FROM `#__wpl_extensions` WHERE `type` = 'javascript' and `param1` in (
    'modernizer',
    'qtips',
    'spinner',
    'realtyna-lightbox',
    'realtyna-utility',
    'realtyna-tagging'
);
");