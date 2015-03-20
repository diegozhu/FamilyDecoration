CREATE TABLE  `business` (

`id` VARCHAR( 18 ) NOT NULL COMMENT  'id主键',
`regionId` VARCHAR( 18 ) NOT NULL COMMENT  '小区id',
`address` VARCHAR( 45 ) NOT NULL COMMENT  '地址',
`isDeleted` VARCHAR( 5 ) NOT NULL DEFAULT  'false' COMMENT  '是否已经删除',
`isFrozen` VARCHAR( 5 ) NOT NULL DEFAULT  'false' COMMENT  '是否是死单',
`isTransfered` VARCHAR( 5 ) NOT NULL DEFAULT  'false' COMMENT  '是否已经转为工程',
`createTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT  '创建时间',
`updateTime` DATETIME NULL COMMENT  '最近修改时间',
`customer` VARCHAR( 45 ) NOT NULL COMMENT  '客户姓名',
`salesman` VARCHAR( 200 ) NOT NULL COMMENT  '业务员',
`source` VARCHAR( 400 ) NULL COMMENT  '业务来源',
PRIMARY KEY (  `id` )
) ENGINE = INNODB COMMENT =  '业务记录表';


CREATE TABLE  `business_detail` (
`id` VARCHAR( 18 ) NOT NULL COMMENT  'id主键',
`businessId` VARCHAR( 18 ) NOT NULL COMMENT  '所属业务id',
`createTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT  '创建时间',
`isDeleted` VARCHAR( 5 ) NOT NULL DEFAULT  'false' COMMENT  '是否已经删除',
`content` TEXT NOT NULL COMMENT  '业务详情内容',
PRIMARY KEY (  `id` ) ,
INDEX (  `businessId` )
) ENGINE = INNODB;


CREATE TABLE  `region` (
`id` VARCHAR( 18 ) NOT NULL COMMENT  'id主键',
`name` VARCHAR( 260 ) NOT NULL COMMENT  '小区名称',
`isDeleted` VARCHAR( 5 ) NOT NULL DEFAULT  'false' COMMENT  '是否已经删除',
`createTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT  '创建时间',
`updateTime` DATETIME NULL COMMENT  '最后更新时间',
PRIMARY KEY (  `id` )
) ENGINE = INNODB  COMMENT =  '业务小区';