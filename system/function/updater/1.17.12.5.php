<?php
  if(!defined('IN_KKFRAME')) exit('Access Denied');
  DB::query("ALTER TABLE `sign_log` ADD `error_log` text NULL;");
  CACHE::clear();
  saveSetting('version', '1.17.12.8');
  showmessage('成功更新到 1.17.12.8！', './');
?>