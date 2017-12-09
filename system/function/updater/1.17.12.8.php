<?php
  if(!defined('IN_KKFRAME')) exit('Access Denied');
  DB::query("ALTER TABLE `member_setting` ADD `autoagree` text NULL;");
  CACHE::clean('setting');
  saveSetting('version', '1.17.12.9');
  showmessage('成功更新到 1.17.12.9！', './');
?>