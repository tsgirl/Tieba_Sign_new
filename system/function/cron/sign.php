<?php
if(!defined('IN_KKFRAME')) exit();
$date = date('Ymd', TIMESTAMP);
$count = DB::result_first("SELECT COUNT(*) FROM `sign_log` WHERE status IN (0, 1) AND date='{$date}'");
@set_time_limit(60);
$multi_thread = getSetting('channel') == 'dev' && getSetting('multi_thread');
$endtime = $multi_thread ? TIMESTAMP + 10 : TIMESTAMP + 45;
if($nowtime - $today < 1800){
  cron_set_nextrun($today + 1800);
}elseif($count){
  if($multi_thread){
    $ret = MultiThread::registerThread(5, 10);
    if($ret) MultiThread::newCronThread();
  }
  if(getSetting('next_cron') < TIMESTAMP - 3600) cron_set_nextrun(TIMESTAMP - 1);
  while($endtime > time()){
    if($count <= 0) break;
    $offset = getSetting('random_sign') ? rand(1, $count) - 1 : 0;
    $res = DB::fetch_first("SELECT tid, status FROM `sign_log` WHERE status IN (0, 1) AND date='{$date}' ORDER BY uid LIMIT {$offset},1");
    $tid = $res['tid'];
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
    if($setting['stoken']){  
      $setting['stoken']=base64_decode($setting['stoken']);
      $stokenhash=md5($setting['cookie'].$setting['stoken']);
      if($setting['checked']!=$stokenhash){
        if($vaildity=check_stoken($setting['cookie'], $setting['stoken'])){
          DB::query("UPDATE `member_setting` SET `checked`='{$stokenhash}' WHERE `uid`='{$uid}';");
        }else{
          $setting['stoken']=null;
        }
      }
    }
    unset($matches);
    if($setting['cookie']) {
      if(defined('DEBUG_ENABLED')) echo '<br />Signing '.$tieba['name'].' for uid '.$uid.' using method '.$setting['sign_method'].'...';
      if(defined('DEBUG_ENABLED')&&$setting['stoken'])echo '(user has stoken '.$setting['stoken'].')';
      switch ($setting['sign_method']){
        case 1  : list($status, $result, $exp) = pc_sign($uid, $tieba, $setting['cookie'], $setting['stoken']); break;
        case 2  : list($status, $result, $exp) = wap_sign($uid, $tieba, $setting['cookie'], $setting['stoken']); break;
        default : list($status, $result, $exp) = client_sign($uid, $tieba, $setting['cookie'], $setting['stoken']);
      }
      if(defined('DEBUG_ENABLED')) echo '<br />result:'.$status.'->'.$result.'('.$exp.')';
    }else{
      DB::query("UPDATE sign_log SET status='-1' WHERE tid='{$tieba['tid']}' AND date='{$date}' AND status<2");
      $count--;
      continue;
    }
    if($exp == 340001){//tieba banned
      if($setting['force_sign'] == 1){
        if(defined('DEBUG_ENABLED')) echo '<br />onekey sign for uid'.$uid.'...';
        list($status, $result, $exp) = onekey_sign($uid, $setting['cookie'], $setting['stoken']);
        if(defined('DEBUG_ENABLED')) echo '<br />result:'.$status.'->'.$result;
        if($status==2&&$result){
          for($x=0; $x<sizeof($exp); $x++){
            DB::query("UPDATE sign_log SET status='2', exp='{$exp[$x]['loyalty_score']['normal_score']}' WHERE fid='{$exp[$x]['forum_id']}' AND date='{$date}' AND uid='{$uid}'");
            $count--;
          }
          sleep(1);
          continue;
        }elseif(!$result){//onekey sign done before
          DB::query("UPDATE sign_log SET status='2' WHERE tid='{$tieba['tid']}' AND date='{$date}' AND status<2");
          $time = 2;
        }else{
          $retry = DB::result_first("SELECT retry FROM sign_log WHERE tid='{$tieba['tid']}' AND date='{$date}' AND status<2");
          if($retry >= 100){
            DB::query("UPDATE sign_log SET status='-1' WHERE tid='{$tieba['tid']}' AND date='{$date}' AND status<2");
          }else{
            DB::query("UPDATE sign_log SET status='1', retry=retry+51 WHERE tid='{$tieba['tid']}' AND date='{$date}' AND status<2");
          }
        }
        $time = 1;
      }else{//贴吧被封禁（关闭），未开启暴力签到
        DB::query("UPDATE sign_log SET status='2' WHERE tid='{$tieba['tid']}' AND date='{$date}' AND status<2");
        $time = 0;
      }
    }elseif($status == 2){
      if($exp){
        DB::query("UPDATE sign_log SET status='2', exp='{$exp}' WHERE tid='{$tieba['tid']}' AND date='{$date}'");
        $time = 2;
      }else{
        DB::query("UPDATE sign_log SET status='2' WHERE tid='{$tieba['tid']}' AND date='{$date}' AND status<2");
        $time = 2;
      }
    }else{
      $retry = DB::result_first("SELECT retry FROM sign_log WHERE tid='{$tieba['tid']}' AND date='{$date}' AND status<2");
      if($retry >= 100){
        DB::query("UPDATE sign_log SET status='-1' WHERE tid='{$tieba['tid']}' AND date='{$date}' AND status<2");
      }else{
        DB::query("UPDATE sign_log SET status='1', retry=retry+101 WHERE tid='{$tieba['tid']}' AND date='{$date}' AND status<2");
      }
      $time = 1;
    }
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
  cron_set_nextrun($nowtime + 90);
}
