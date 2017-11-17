<?php
if(!defined('IN_KKFRAME')) exit('Access Denied');
runquery("
CREATE TABLE IF NOT EXISTS `plugin_var` (
  `pluginid` varchar(64) NOT NULL,
  `key` varchar(32) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  PRIMARY KEY (`pluginid`,`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
");
saveSetting('version', '1.14.2.6');
showmessage('成功更新到 1.14.2.6！', './');
?>