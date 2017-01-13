ALTER TABLE  `msg_log` CHANGE  `id`  `id` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  '主键id';
ALTER TABLE  `business` CHANGE  `id`  `id` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'id主键';
ALTER TABLE  `business_detail` CHANGE  `id`  `id` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'id主键';
ALTER TABLE  `censorship` CHANGE  `id`  `id` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'id主键';
ALTER TABLE  `chart` CHANGE  `id`  `id` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'id主键';
ALTER TABLE  `feedback` CHANGE  `id`  `id` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'id主键';
ALTER TABLE  `mainmaterial` CHANGE  `id`  `id` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'id主键';
ALTER TABLE  `message` CHANGE  `id`  `id` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'id主键';
ALTER TABLE  `msg_recieve_log` CHANGE  `id`  `id` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'id主键';
ALTER TABLE  `plan` CHANGE  `id`  `id` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'id主键';
ALTER TABLE  `potential_business` CHANGE  `id`  `id` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'id主键';
ALTER TABLE  `progress` CHANGE  `id`  `id` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'id主键';
ALTER TABLE  `progress_chart` CHANGE  `id`  `id` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'id主键';
ALTER TABLE  `region` CHANGE  `id`  `id` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'id主键';
ALTER TABLE  `system` CHANGE  `id`  `id` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'id主键';
ALTER TABLE  `taskcensor` CHANGE  `id`  `id` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'id主键';
ALTER TABLE  `task_list` CHANGE  `id`  `id` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'id主键';
ALTER TABLE  `task_self_assessment` CHANGE  `id`  `id` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'id主键';

update `system` set `paramValue`='version-8.11' where `id`='4';