<?php
  if(!defined('IN_KKFRAME')) exit('Access Denied');
  DB::query("ALTER TABLE `member_setting` ADD `force_sign` tinyint(1) NOT NULL DEFAULT '0';");
  DB::query("ALTER TABLE `member_setting` ADD `stoken` text NULL;");
  DB::query("ALTER TABLE `sign_log` ADD `fid` int unsigned NOT NULL DEFAULT '0';");
  saveSetting('version', '1.17.11.27');
  showmessage('成功更新到 1.17.11.27！', './');
?>