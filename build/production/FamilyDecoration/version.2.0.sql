CREATE TABLE  `familydecoration`.`online_user` (
`userName` VARCHAR( 200 ) NOT NULL COMMENT  '用户名',
`onlineTime` DATETIME NOT NULL COMMENT  '上线时间',
`offlineTime` DATETIME NULL COMMENT  '下线时间',
`lastUpdateTime` DATETIME NOT NULL COMMENT  '最后更新时间',
`sessionId` VARCHAR( 100 ) NOT NULL COMMENT  'sessionId',
`ip` VARCHAR( 100 ) NULL COMMENT  '用户的ip',
`userAgent` VARCHAR( 200 ) NULL COMMENT  '用户的浏览器信息',
INDEX (  `userName` ,  `sessionId` )
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci