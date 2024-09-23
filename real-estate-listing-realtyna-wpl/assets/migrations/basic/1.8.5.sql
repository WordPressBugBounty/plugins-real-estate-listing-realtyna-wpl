ALTER TABLE `#__wpl_user_group_types` ADD `editable` TINYINT(4) UNSIGNED NOT NULL DEFAULT '1', ADD `deletable` TINYINT(4) UNSIGNED NOT NULL DEFAULT '1', ADD `index` FLOAT(5, 2) NOT NULL DEFAULT '99.00';
ALTER TABLE `#__wpl_user_group_types` ADD `params` TEXT NULL, ADD `enabled` TINYINT(4) NOT NULL DEFAULT '1';

UPDATE `#__wpl_user_group_types` SET `editable`='0', `deletable`='0', `index`='1.00' WHERE `id`='1';
UPDATE `#__wpl_user_group_types` SET `editable`='0', `deletable`='0', `index`='2.00' WHERE `id`='2';

UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='906';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2]' WHERE `id`='6';

UPDATE `#__wpl_dbst` SET `specificable`='1' WHERE `id`='912';
UPDATE `#__wpl_dbst` SET `specificable`='1' WHERE `id`='913';
ALTER TABLE `#__wpl_dbst` ADD `user_specific` VARCHAR(200) NULL AFTER `property_type_specific`;

UPDATE `#__wpl_dbst` SET `category`='10' WHERE `id`='912';
UPDATE `#__wpl_dbst` SET `category`='10' WHERE `id`='911';
UPDATE `#__wpl_dbst` SET `category`='10' WHERE `id`='913';

INSERT INTO `#__wpl_user_group_types` (`id`, `name`, `editable`, `deletable`, `index`, `params`, `enabled`) VALUES (3, 'Guests', 0, 0, 0.50, NULL, 1);
UPDATE `#__wpl_users` SET `membership_type`='3' WHERE `id`='-2';
UPDATE `#__wpl_users` SET `membership_type`='3' WHERE `id`='0';

ALTER TABLE `#__wpl_users` DROP `maccess_rank_start`, DROP `maccess_attach`;
ALTER TABLE `#__wpl_users` DROP `maccess_renewal_period`;

ALTER TABLE `#__wpl_properties` CHANGE `field_312` `field_312` VARCHAR(70) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
CHANGE `field_313` `field_313` VARCHAR(70) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;