ALTER TABLE `classes` ADD `class_team` BOOLEAN NOT NULL DEFAULT FALSE AFTER `tatami_id`;
ALTER TABLE `competition` CHANGE `comp_end_date` `comp_end_date` DATE NULL DEFAULT NULL;



