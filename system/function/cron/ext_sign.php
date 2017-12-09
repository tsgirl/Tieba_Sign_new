<?php
if(!defined('IN_KKFRAME')) exit();
$maxe = ini_get('max_execution_time');
$endtime = $maxe ? (TIMESTAMP + ceil(0.9*$maxe)) : TIMESTAMP + 45;
$_uid = getSetting('extsign_uid') ? getSetting('extsign_uid') : 1;
$allFinish=0;
while($endtime>time()){
  $num = 0;
  while($_uid){
    $num++;
    $userNum=0;
    $nextUser=0;
    if($num <= 3){
      if(defined('DEBUG_ENABLED')) echo '<br />'.$_uid;
      $setting = get_setting($_uid);
      $matches=explode('=', base64_decode($setting['cookie']));
      $setting['cookie'] = trim($matches[1]);
      unset($matches);
      //if($setting['zhidao_sign']) zhidao_sign($_uid);
      //if($setting['wenku_sign']) wenku_sign($_uid);
      if($setting['autoagree']){
        if(defined('DEBUG_ENABLED')) echo ' : AutoAgree ';
        $stoken=get_verified_stoken_from_uid($_uid);
        if(!$stoken){
          $_uid = DB::result_first("SELECT uid FROM member WHERE uid>'{$_uid}' ORDER BY uid ASC LIMIT 0,1");
          saveSetting('extsign_uid', $_uid);
          if(!$_uid) $allFinish=1;
          break;
        }
        $res = json_decode(get_frs($_uid, base64_decode($setting['autoagree']), $setting['cookie']), true);
        if(!$res) break;
        if($res['error_code']) break;
        while($userNum<10){
          $userNum++;
          $x=rand(0, sizeof($res['thread_list']));
          list($status, $msg, $result)=op_agree($_uid, $res['forum']['id'], $res['thread_list'][$x]['tid'], null, $res['anti']['tbs'], $setting['cookie'], $stoken);
          if(defined('DEBUG_ENABLED')){
            echo $status.$msg;
            print_r($result);
          }
          unset($res['thread_list'][$x]);
          if($status==2&&!$result['data']['agree']['score']){
            $nextUser=1;
            break;
          }
          if(time()>$endtime){
            cron_set_nextrun(TIMESTAMP + 60);
            exit();
          }
          sleep(2,3);
        }
        $nextUser=1;
      }else{
        $nextUser=1;
      }
    }else{
      $nextUser=1;
    }
    if($nextUser){
      $_uid = DB::result_first("SELECT uid FROM member WHERE uid>'{$_uid}' ORDER BY uid ASC LIMIT 0,1");
      saveSetting('extsign_uid', $_uid);
      if(!$_uid) $allFinish=1;
    }
  }
  
}
cron_set_nextrun($allFinish ? $tomorrow + 900 : TIMESTAMP + 60);
