<?php
//重新编码bduss，减少数据库占用
if(!defined('IN_KKFRAME')) exit('Access Denied');
$maxTime=ini_get('MAX_EXECUTION_TIME');
$i=DB::fetch_first('SELECT * FROM `cache` WHERE k=\'bduss_recode\' LIMIT 1');
if(!$i){
  DB::query('INSERT INTO `cache`(k, v) VALUES (\'bduss_recode\', \'0\')');
  $i['v']=0;
}
$success=0;
while(time()-TIMESTAMP < 0.9*$maxTime||!$maxTime){
  $bduss=DB::fetch_first("SELECT * FROM `member_setting` ORDER BY uid LIMIT {$i['v']}, 1");
  if(!$bduss){
    saveSetting('version', '1.17.11.11');
    $success=1;
    break;
  }
  $bduss['cookie']=base64_encode(strrev(str_rot13(pack('H*', $bduss['cookie']))));
  DB::query("UPDATE `member_setting` SET cookie='{$bduss['cookie']}' WHERE uid='{$bduss['uid']}'");
  $i['v']++;
  DB::query("UPDATE `cache` SET v='{$i['v']}' WHERE k='bduss_recode'");
}
showmessage($success?'成功更新到 1.17.11.11！':'正在更新数据……', './');
?>