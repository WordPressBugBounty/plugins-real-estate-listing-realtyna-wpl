UPDATE `#__wpl_user_group_types` SET `editable`='1' WHERE `id`='1';
UPDATE `#__wpl_user_group_types` SET `editable`='1' WHERE `id`='2';
UPDATE `#__wpl_user_group_types` SET `editable`='1' WHERE `id`='3';

ALTER TABLE `#__wpl_user_group_types` ADD `description` TEXT NULL AFTER `default_membership_id`;
ALTER TABLE `#__wpl_users` ADD `maccess_short_description` TEXT NULL AFTER `maccess_upgradable_to`, ADD `maccess_long_description` TEXT NULL AFTER `maccess_short_description`;

ALTER TABLE `#__wpl_properties` ADD `expired` TINYINT(4) NOT NULL DEFAULT '0' AFTER `confirmed`;
ALTER TABLE `#__wpl_users` ADD `expired` TINYINT(4) NOT NULL DEFAULT '0' AFTER `maccess_long_description`, ADD `expiry_date` DATETIME NULL AFTER `expired`;

UPDATE `#__wpl_dbst` SET `specificable`='0' WHERE `id`='310';
UPDATE `#__wpl_dbst` SET `specificable`='0' WHERE `id`='311';