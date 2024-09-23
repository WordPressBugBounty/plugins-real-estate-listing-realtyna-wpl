INSERT INTO `#__wpl_activities` (`id`, `activity`, `position`, `enabled`, `index`, `params`, `show_title`, `title`, `association_type`, `associations`) VALUES
(24, 'user_contact', 'profile_show_position1', 0, 99.00, '{"top_comment":""}', 1, 'Contact', 1, '');

INSERT INTO `#__wpl_notifications` (`id`, `description`, `template`, `subject`, `additional_memberships`, `additional_users`, `additional_emails`, `options`, `params`, `enabled`) VALUES
(3, 'Contact to agent from profile page', 'contact_profile', 'New Profile Contact', '', '', '', NULL, '', 1);

INSERT INTO `#__wpl_events` (`id`, `type`, `trigger`, `class_location`, `class_name`, `function_name`, `params`, `enabled`) VALUES
(5, 'notification', 'contact_profile', 'libraries.event_handlers.notifications', 'wpl_events_notifications', 'contact_profile', '', 1);

UPDATE `#__wpl_dbst` SET `searchmod`='1' WHERE `id`='313';

ALTER TABLE `#__wpl_properties` CHANGE `last_modified_time_stamp` `last_modified_time_stamp` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `#__wpl_users` ADD `last_modified_time_stamp` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `rendered`;