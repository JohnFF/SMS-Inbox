DROP TABLE IF EXISTS `civicrm_smsinbox_state`;

CREATE TABLE `civicrm_smsinbox_state` (
     `id` int(10) unsigned NOT NULL AUTO_INCREMENT  COMMENT 'Unique smsinbox_state ID',
     `activity_id` int(10) unsigned NOT NULL COMMENT 'Activity ID of the Incoming SMS Message',
     `read_status` tinyint(4) unsigned NOT NULL DEFAULT 0 COMMENT '0 if unread, 1 if read',
     PRIMARY KEY (`id`),
     INDEX `index_activity_id`(`activity_id`),
     UNIQUE (`activity_id`),
     INDEX `index_read_status`(`read_status`)
) ENGINE=InnoDB;
