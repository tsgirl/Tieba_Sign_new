<?php
if(!defined('IN_KKFRAME')) exit('Access Denied');
runquery('
CREATE TABLE IF NOT EXISTS `download` (
  `path` varchar(128) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`path`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `update_source` (
  `id` varchar(16) NOT NULL,
  `path` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
');
saveSetting('version', '1.14.1.23');
showmessage('成功更新到 1.14.1.23！', './');
?>