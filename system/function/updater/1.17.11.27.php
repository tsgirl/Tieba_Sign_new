<?php
  if(!defined('IN_KKFRAME')) exit('Access Denied');
  DB::query("ALTER TABLE `member_setting` ADD `checked` text NULL;");
  CACHE::clean('setting');
  saveSetting('version', '1.17.12.5');
  showmessage('成功更新到 1.17.12.5！', './');
?>