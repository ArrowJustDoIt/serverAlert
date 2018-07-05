-- ----------------------------
-- Table structure for serverAlert
-- ----------------------------
DROP TABLE IF EXISTS `serverAlert`;
CREATE TABLE `serverAlert` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `openid` varchar(60) NOT NULL COMMENT '微信openid',
  `create_time` int(11) NOT NULL COMMENT '入库时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
