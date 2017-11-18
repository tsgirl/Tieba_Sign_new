<?php
  if(!defined('IN_KKFRAME')) exit('Access Denied');
  DB::query("ALTER TABLE `member_setting` ADD `sign_method` tinyint(1) NOT NULL DEFAULT '3';");
  saveSetting('version', '1.17.11.18');
  showmessage('成功更新到 1.17.11.18！', './');
?>