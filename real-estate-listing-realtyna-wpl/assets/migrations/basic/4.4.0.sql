DROP TABLE `#__wpl_addon_idx_payments`, `#__wpl_addon_idx_service_logs`, `#__wpl_addon_idx_tasks`, `#__wpl_addon_idx_trial_logs`, `#__wpl_addon_idx_users`, `#__wpl_addon_idx_users_providers`, `#__wpl_addon_idx_user_wizard_steps`;

ALTER TABLE `#__wpl_sort_options` ADD `default_order` VARCHAR(5) NOT NULL DEFAULT 'DESC' AFTER `name`;
UPDATE `#__wpl_sort_options` SET `default_order`='ASC' WHERE `field_name` IN ('p.first_name','p.location1_name','p.location2_name','ptype_adv','ltype_adv');