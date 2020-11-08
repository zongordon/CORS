ALTER TABLE `classes` ADD `class_discipline_variant` BOOLEAN NOT NULL DEFAULT FALSE AFTER `class_discipline`;
ALTER TABLE `classes` CHANGE `class_team` `class_team` BOOLEAN NOT NULL DEFAULT FALSE;
ALTER TABLE `classes` CHANGE `tatami_id` `tatami_id` BIGINT(20) NULL;



