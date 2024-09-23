UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2]' WHERE `id`='1';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2]' WHERE `id`='2';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2]' WHERE `id`='3';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2]' WHERE `id`='5';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2]' WHERE `id`='6';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2]' WHERE `id`='11';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2]' WHERE `id`='12';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2]' WHERE `id`='13';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2]' WHERE `id`='14';

UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='903';
UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='900';
UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='901';
UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='902';
UPDATE `#__wpl_dbst` SET `editable`='1', `deletable`='1' WHERE `id`='904';
UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='905';
UPDATE `#__wpl_dbst` SET `editable`='1', `deletable`='1' WHERE `id`='907';
UPDATE `#__wpl_dbst` SET `editable`='1', `deletable`='1' WHERE `id`='908';
UPDATE `#__wpl_dbst` SET `editable`='1', `deletable`='1' WHERE `id`='909';
UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='914';

UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='400';
UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='401';
UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='402';
UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='403';

UPDATE `#__wpl_dbst` SET `deletable`='0' WHERE `id`='4';
UPDATE `#__wpl_dbst` SET `deletable`='0' WHERE `id`='5';

ALTER TABLE `#__wpl_extensions` CHANGE `param2` `param2` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
UPDATE `#__wpl_extensions` SET `param2`='http://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic|Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700|Scada:400italic,700italic,400,700|Archivo+Narrow:400,40|Lato:400,700,900,400italic|BenchNine' WHERE `id`='99';

UPDATE `#__wpl_dbst` SET `index`='3.50' WHERE `id`='171';
UPDATE `#__wpl_dbst` SET `text_search`='1' WHERE `id`='308';
