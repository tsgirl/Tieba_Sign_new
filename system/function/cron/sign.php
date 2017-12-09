<?php
if(!defined('IN_KKFRAME')) exit();
$date = date('Ymd', TIMESTAMP);
$count = DB::result_first("SELECT COUNT(*) FROM `sign_log` WHERE status IN (0, 1) AND date='{$date}'");
$maxe = ini_get('max_execution_time');
$multi_thread = getSetting('channel') == 'dev' && getSetting('multi_thread');
$endtime = $maxe ? (TIMESTAMP + ceil(0.9*$maxe)) : TIMESTAMP + 45;
if($nowtime - $today < 3600){
  cron_set_nextrun(TIMESTAMP + 3600);//确保在1:00后执行，防止一键签到出错
}elseif($count){
  if($multi_thread){
    $ret = MultiThread::registerThread(5, 10);
    if($ret) MultiThread::newCronThread();
  }
  if(getSetting('next_cron') < TIMESTAMP - 3600) cron_set_nextrun(TIMESTAMP - 1);
  while($endtime > time()){
    if(defined('DEBUG_ENABLED'))  echo '<br />';
    if($count <= 0) break;
    $offset = getSetting('random_sign') ? rand(1, $count) - 1 : 0;
    $res = DB::fetch_first("SELECT tid, status, retry, error_log FROM `sign_log` WHERE status IN (0, 1) AND date='{$date}' ORDER BY uid LIMIT {$offset},1");
    $retry = $res['retry']+34;
    $tid = $res['tid'];
    $error_log = Array();
    if($res['error_log']) $error_log=json_decode(base64_decode($res['error_log']));
    if(!$tid) break;
    if($res['status'] == 2 || $res['status'] == -2) continue;
    $tieba = DB::fetch_first("SELECT * FROM my_tieba WHERE tid='{$tid}'");
    if($tieba['skiped'] || !$tieba){
      DB::query("UPDATE sign_log set status='-2' WHERE tid='{$tieba['tid']}' AND date='{$date}'");
      continue;
    }
    $uid = $tieba['uid'];
    $setting = get_setting($uid);
    $matches=explode('=', base64_decode($setting['cookie']));
    $setting['cookie'] = trim($matches[1]);
    unset($matches);
    if($setting['cookie']) {
      if(defined('DEBUG_ENABLED')) echo '<br />Signing '.$tieba['name'].' for uid '.$uid.' using method '.$setting['sign_method'].'...';
      if(defined('DEBUG_ENABLED')&&$setting['stoken']) echo '(user has stoken '.base64_decode($setting['stoken']).')';
      switch ($setting['sign_method']){
        case 1  : list($status, $result, $exp) = pc_sign($uid, $tieba, $setting['cookie']); break;
        case 2  : list($status, $result, $exp) = wap_sign($uid, $tieba, $setting['cookie']); break;
        default : list($status, $result, $exp) = client_sign($uid, $tieba, $setting['cookie']);
      }
      array_push($error_log, Array('status'=>$status, 'error'=>$result, 'retry'=>$retry, 'sign_method'=>$setting['sign_method']));
      if(defined('DEBUG_ENABLED')) echo '<br />result:'.$status.'->'.$result.'('.$exp.')';
    }else{
      DB::query("UPDATE sign_log SET status='-1' WHERE tid='{$tieba['tid']}' AND date='{$date}' AND status<2");
      $count--;
      continue;
    }
    if($exp == 340001){//tieba banned
      if($setting['force_sign'] == 1){//do onekey sign
        if(defined('DEBUG_ENABLED')) echo '<br />onekey sign for uid'.$uid.'...';
        list($status, $result, $exp) = onekey_sign($uid, $setting['cookie']);
        array_push($error_log, Array('status'=>$status, 'error'=>$result, 'retry'=>$retry, 'sign_method'=>'onekey'));
        if(defined('DEBUG_ENABLED')) echo '<br />result:'.$status.'->'.$result;
        if($status==2){//onekey success
          if($exp){//returns signed tieba
            for($x=0; $x<sizeof($exp); $x++){
              DB::query("UPDATE sign_log SET status='2', exp='{$exp[$x]['loyalty_score']['normal_score']}' WHERE fid='{$exp[$x]['forum_id']}' AND date='{$date}' AND uid='{$uid}'");
              $count--;
            }
            sleep(1);
            continue;
          }else{//onekey sign done before
            DB::query("UPDATE sign_log SET status='2' WHERE tid='{$tieba['tid']}' AND date='{$date}' AND status<2");
            $time = 2;
          }
        }else{//onekey failed
          if($retry >= 100){
            DB::query("UPDATE sign_log SET status='-1' WHERE tid='{$tieba['tid']}' AND date='{$date}' AND status<2");
          }else{
            DB::query("UPDATE sign_log SET status='1', retry={$retry} WHERE tid='{$tieba['tid']}' AND date='{$date}' AND status<2");
          }
        }
        $time = 1;
      }else{//贴吧被封禁（关闭），未开启暴力签到
        if($retry >= 100){
          DB::query("UPDATE sign_log SET status='-1' WHERE tid='{$tieba['tid']}' AND date='{$date}' AND status<2");
        }else{
          DB::query("UPDATE sign_log SET status='{$status}', retry={$retry} WHERE tid='{$tieba['tid']}' AND date='{$date}' AND status<2");
        }
        $time = 1;
      }
    }elseif($status == 2){//real success
      if($exp){
        DB::query("UPDATE sign_log SET status='2', exp='{$exp}' WHERE tid='{$tieba['tid']}' AND date='{$date}'");
        $time = 2;
      }else{
        DB::query("UPDATE sign_log SET status='2' WHERE tid='{$tieba['tid']}' AND date='{$date}' AND status<2");
        $time = 2;
      }
    }else{
      if($retry >= 100){
        DB::query("UPDATE sign_log SET status='-1' WHERE tid='{$tieba['tid']}' AND date='{$date}' AND status<2");
      }else{
        DB::query("UPDATE sign_log SET status='1', retry={$retry} WHERE tid='{$tieba['tid']}' AND date='{$date}' AND status<2");
      }
      $time = 2;
    }
    $error_log = base64_encode(json_encode($error_log));
    DB::query("UPDATE sign_log SET error_log='{$error_log}' WHERE tid='{$tieba['tid']}' AND date='{$date}'");
    if($time){
      sleep($time);
      $count--;
    }
  }
  if($multi_thread){
    $ret = MultiThread::registerThread(5, 10);
    if($ret) MultiThread::newCronThread();
  }
}else{
  cron_set_nextrun($nowtime + 60);
}
if(defined('DEBUG_ENABLED')) echo ('<br />TIME ELAPSED:'.(time()-TIMESTAMP).'s, max execution time allowed:'.$maxe.'s.');