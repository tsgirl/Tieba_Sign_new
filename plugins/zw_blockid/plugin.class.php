<?php
if (! defined ('IN_KKFRAME')) exit ('Access Denied!');

class plugin_zw_blockid extends Plugin {
  var $name = 'zw_blockid';
  var $description = '本插件可以给网站用户提供循环封禁用户功能';
  var $modules = array (
    array ('id' => 'index',
      'type' => 'page',
      'title' => '循环封禁',
      'file' => 'zw_blockid.inc.php'
      ),
    array('type' => 'cron',
      'cron' => array('id' => 'zw_blockid/daily', 'order' => 101),
      ),
    array('type' => 'cron',
      'cron' => array('id' => 'zw_blockid/blockid', 'order' => 102),
      ),
    array('type' => 'cron',
      'cron' => array('id' => 'zw_blockid/mail', 'order' => 103),
      ),
    );
  var $version = '1.3.0';

  function install() {
    runquery("CREATE TABLE `zw_blockid_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `fid` int(10) unsigned NOT NULL,
  `blockid` varchar(20) NOT NULL,
  `tieba` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`fid`,`blockid`,`tieba`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE `zw_blockid_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `fid` int(8) NOT NULL,
  `tieba` varchar(200) NOT NULL,
  `blockid` varchar(100) NOT NULL,
  `date` int(11) NOT NULL DEFAULT '20131201',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `retry` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
");
  }

  function uninstall() {
    runquery("
DROP TABLE `zw_blockid_list`;
DROP TABLE `zw_blockid_log`;
DELETE FROM `plugin_var` WHERE `pluginid`='zw_blockid';
");
  }

  function checkCompatibility() {
    if (version_compare(VERSION, '1.14.4.24', '<')) showmessage('签到助手版本过低，请升级');
  }

  function page_footer_js() {
    echo '<script src="plugins/zw_blockid/zw_blockid.js?version=1.14.6.2"></script>';
  }

  function on_upgrade($nowversion) {
    if ($nowversion == '0') {
      DB :: query("DELETE FROM  `setting` WHERE  `k` LIKE  'zw_blockid%';");
      return '1.2.0';
    }
    if ($nowversion == '1.2.0') {
      return '1.2.4';
    }
    if ($nowversion == '1.2.4') {
      runquery("UPDATE cron SET id='zw_blockid/cron/zw_blockid' WHERE id='zw_blockid';
      UPDATE cron SET id='zw_blockid/cron/zw_blockid_daily' WHERE id='zw_blockid_daily';
      UPDATE cron SET id='zw_blockid/cron/zw_blockid_mail' WHERE id='zw_blockid_mail';");
      return '1.2.5';
    }
    if ($nowversion == '1.2.5') {
      runquery("UPDATE cron SET id='zw_blockid/cron_blockid' WHERE id='zw_blockid' OR id='zw_blockid/cron/zw_blockid';
      UPDATE cron SET id='zw_blockid/cron_daily' WHERE id='zw_blockid_daily' OR id='zw_blockid/cron/zw_blockid_daily';
      UPDATE cron SET id='zw_blockid/cron_mail' WHERE id='zw_blockid_mail' OR id='zw_blockid/cron/zw_blockid_mail';");
      return '1.2.6';
    }
    if ($nowversion == '1.2.6') {
      runquery("UPDATE cron SET id='zw_blockid/blockid' WHERE id='zw_blockid/cron_blockid';
      UPDATE cron SET id='zw_blockid/daily' WHERE id='zw_blockid/cron_daily';
      UPDATE cron SET id='zw_blockid/mail' WHERE id='zw_blockid/cron_mail';");
      return '1.2.8';
    }
    if ($nowversion == '1.2.8') {
      runquery("CREATE TABLE IF NOT exists `zw_blockid_list_tmp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `fid` int(10) unsigned NOT NULL,
  `blockid` varchar(20) NOT NULL,
  `tieba` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`fid`,`blockid`,`tieba`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
INSERT INTO `zw_blockid_list_tmp`(uid, fid, blockid, tieba) SELECT DISTINCT uid, fid, blockid, tieba FROM `zw_blockid_list`;
DELETE FROM `zw_blockid_list`;
INSERT INTO `zw_blockid_list`(uid, fid, blockid, tieba) SELECT DISTINCT uid, fid, blockid, tieba FROM `zw_blockid_list_tmp`;
DROP TABLE `zw_blockid_list_tmp`;
ALTER TABLE `zw_blockid_list` ADD UNIQUE (`uid` ,`fid` ,`blockid` ,`tieba`);
");
    }
    return $version;
  }
  
  function check($kw, $BDUSS){
    $_imei=md5($BDUSS);
    $_imei=str_replace("a", "3", $_imei);
    $_imei=str_replace("b", "9", $_imei);
    $_imei=str_replace("c", "8", $_imei);
    $_imei=str_replace("d", "0", $_imei);
    $_imei=str_replace("e", "2", $_imei);
    $_imei=str_replace("f", "5", $_imei);
    $__client_id='wappc_'.time().'985_211'; 
    $_imei=substr($_imei,10,15);
    $_cuid=strtoupper(strrev(md5('tsgirl'.$BDUSS.'tsgirl'))).'|'.strrev($_imei);
    $fiddata=Array(
      'BDUSS='.$BDUSS,
      '_client_id='.$__client_id,
      '_client_type=2',
      '_client_version=6.6.6',
      '_phone_imei='.$_imei,
      'cuid='.$_cuid,
      'from=an_leshangdian',
      'kw='.$kw,
      'model=SamsungNote7BoomPhone',
      'pn=1',
      'q_type=2',
      'rn=35',
      'scr_dip=2.8125',
      'scr_h=1552',
      'scr_w=900',
      'stErrorNums=0',
      'stMethod=1',
      'stMode=1',
      'stSize=1391',
      'stTime=170',
      'stTimesNum=0',
      'st_type=tb_forumlist',
      'timestamp='.time().rand(111,999),
      'with_group=1'
    );
    $data=implode('&', $fiddata).'&sign='.strtoupper(md5(implode('', $fiddata).'tiebaclient!!!'));
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://c.tieba.baidu.com/c/f/frs/page');
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, 'bdtb for Android 6.6.6 Chrome FuckBaidu 2.3 Mozilla 6.66 MSIE 10.0 capable');
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $gd = curl_exec($ch);
    curl_close($ch);
    return json_decode($gd, false); 
  }

  function handleAction() {
    global $uid;
    if (!$uid) exit ('Access Denied!');
    $data = array ();
    $data ['msgx'] = 1;
    $data ['msg'] = $_GET ['action'];
    switch ($_GET ['action']) {
      case 'add-id' :
        $tieba = daddslashes($_POST ['tb_name']);
        $blockid = daddslashes($_POST ['user_name']);
        $cookie=get_cookie($uid);
        $matches=explode('=', $cookie);
        $BDUSS = trim($matches[1]);
        $gd=self::check($tieba, $BDUSS);
        if(!$gd){
          $data ['msg'] = 'JSON解析失败';
          $data ['msgx'] = 0;
          break;
        }    
        if ($gd->error_code != 0) {
          $data ['msg'] = '添加失败('.$gd->error_code.':'.$gd->error_msg.')';
          $data ['msgx'] = 0;
          break;
        }
        if ($gd->user->is_manager!=1&&$gd->user->is_manager!=2){
          $data ['msg'] = '你根本不是吧务，不要总想弄个大新闻！';
          $data ['msgx'] = 0;
          break;
        }
        if ($result = DB :: result_first("SELECT * FROM zw_blockid_list WHERE uid={$uid} AND fid={$gd->forum->id} AND blockid='{$blockid}' AND tieba='{$tieba}'")) {
          $data ['msg'] = '添加失败，已有重复记录！';
        } else {
          DB :: insert ('zw_blockid_list', array (
              'uid' => $uid,
              'fid' => $gd->forum->id,
              'blockid' => $blockid,
              'tieba' => $gd->forum->name,
              ));
          $re = $this -> blockid($gd->forum->id, $blockid, 1, $uid, $gd->forum->name);
          $data ['msg'] = ($re['errno'] == 0 ? '封禁成功' : '封禁失败，'.$re['errno'].$re['errmsg'].'，已添加进封禁列表') . "！贴吧FID为：{$gd->forum->id}，被封禁用户为：{$blockid}";
        }
        break;
      case 'add-id-batch' :
        $cookie=get_cookie($uid);
        $matches=explode('=', $cookie);
        $BDUSS = trim($matches[1]);
        $tieba = $_POST ['tb_name'];
        $user_name = explode ("\n", $_POST ['user_name']);
        for($i = 0;$i < count($user_name);$i++) {
          $user_name[$i] = trim($user_name[$i]);
        }
        $user_name = array_filter($user_name);
        if (!is_array($user_name)) {
          $data ['msg'] = '添加失败：格式错误，多个ID请用换行分隔！';
          break;
        }
        $gd=self::check($tieba, $BDUSS);
        if ($gd->error_code != 0) {
          $data ['msg'] = '添加失败('.$gd->error_code.':'.$gd->error_msg.')';
          $data ['msgx'] = 0;
          break;
        }
        if ($gd->user->is_manager!=1&&$gd->user->is_manager!=2){
          $data ['msg'] = '你根本不是吧务！不要总想弄个大新闻！';
          $data ['msgx'] = 0;
          break;
        }
        $count = 0;
        foreach($user_name as $id) {
          if (DB :: insert ('zw_blockid_list', array (
                'uid' => $uid,
                'fid' => $gd->forum->id,
                'blockid' => daddslashes($id),
                'tieba' => $gd->forum->name,
                 ), true)) $count++;
        }
        $data ['msg'] = "成功添加了{$count}个ID！所在贴吧为{$tieba}，该贴吧FID为：{$gd->forum->id}";
        break;
      case 'get-list' :
        $data ['list'] = array ();
        $data ['log'] = array ();
        $query = DB :: query ("SELECT * FROM zw_blockid_list WHERE uid={$uid}");
        while ($result = DB :: fetch ($query)) {
          $data ['list'] [] = $result;
        }
        $data ['today'] = date('Ymd');
        $sendmail_uid = array_filter(explode (',', $this -> getSetting('sendmail_uid')));
        $data['sendmail'] = in_array($uid, $sendmail_uid) ? 1 : 0;
        break;
      case 'get-log':
        $date = intval($_GET['date']);
        $data ['log'] = array ();
        $data ['today'] = date('Ymd');
        $data ['date'] = substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6, 2);
        $data ['log_success_status'] = 0;
        $query = DB :: query ("SELECT * FROM zw_blockid_log WHERE uid={$uid} AND date={$date}");
        while ($result = DB :: fetch ($query)) {
          if ($result['status'] == 1) $data ['log_success_status']++;
          $data ['log'] [] = $result;
        }
        $data['log_count'] = count($data ['log']);
        $data['before_date'] = DB :: result_first("SELECT date FROM zw_blockid_log WHERE uid={$uid} AND date<{$date} ORDER BY date DESC LIMIT 0,1");
        $data['after_date'] = DB :: result_first("SELECT date FROM zw_blockid_log WHERE uid={$uid} AND date>{$date} ORDER BY date LIMIT 0,1");
        break;
      case 'del-blockid' :
        $no = intval($_GET ['no']);
        DB :: query ("DELETE FROM zw_blockid_list WHERE id={$no} AND uid={$uid}");
        $data ['msg'] = "删除成功！";
        break;
      case 'do-blockid':
        $username = urldecode($_GET['blockid']);
        $tieba = urldecode($_GET['tieba']);
        $re = $this -> blockid ($_GET['fid'], $username, 1, $uid, $tieba);
        $id = intval($_GET['bid']);
        if ($re['errno'] == -1) {
          $data ['msg'] = "JSON解析失败！";
        } elseif ($re['errno'] == 0) {
          $data ['msg'] = "封禁成功！封禁账号：{$username}，FID为{$_GET['fid']}";
          DB :: query ("UPDATE zw_blockid_log SET status=1 WHERE id={$id} AND uid={$uid}");
        } else {
          $data ['msg'] = "封禁失败！返回信息：{$re['errno']}:{$re['errmsg']}，封禁账号：{$username}，所在贴吧：{$tieba}，FID为{$_GET['fid']}";
        }
        break;
      case 'del-all' :
        DB :: query ("DELETE FROM zw_blockid_list WHERE uid='{$uid}'");
        $data ['msg'] = "删除成功！";
        break;
      case 'test-blockid' :
        $query = DB :: query ("SELECT * FROM zw_blockid_list WHERE uid='{$uid}'");
        while ($result = DB :: fetch ($query)) {
          $blockid_list [] = $result;
        }
        if (! $blockid_list) {
          $data ['msgx'] = 0;
          $data ['msg'] = "没有封禁信息，请先添加！";
          break;
        }
        $rand = rand (0, count ($blockid_list) - 1);
        $test_blockid = $blockid_list [$rand];
        $re = $this -> blockid ($test_blockid ['fid'], $test_blockid ['blockid'], 1, $uid, $test_blockid['tieba']);
        if (!$re) {
          $data ['msg'] = "JSON解析失败！";
        } elseif ($re['errno'] == 0) {
          $data ['msg'] = "封禁成功！封禁账号：{$test_blockid['blockid']}，所在贴吧：{$test_blockid['tieba']}，FID为{$test_blockid['fid']}";
        } else {
          $data ['msg'] = "封禁失败！返回信息：{$re['errno']}:{$re['errmsg']}，封禁账号：{$test_blockid['blockid']}，所在贴吧：{$test_blockid['tieba']}，FID为{$test_blockid['fid']}";
        }
        break;
      case 'setting':
        if (intval($_POST['zw_blockid-report']) == 1) {
          $sendmail_uid = array_filter(explode (',', $this -> getSetting('sendmail_uid')));
          if (!in_array($uid, $sendmail_uid)) $sendmail_uid[] = $uid;
          $this -> saveSetting('sendmail_uid', implode(',', $sendmail_uid));
          $data ['msg'] = "成功开启邮件报告！";
        } else {
          $sendmail_uid = array_filter(explode (',', $this -> getSetting('sendmail_uid')));
          if (in_array($uid, $sendmail_uid)) {
            for($i = 0;$i < count($sendmail_uid);$i++) {
              if ($sendmail_uid[$i] == $uid) unset($sendmail_uid[$i]);
            }
            $this -> saveSetting('sendmail_uid', implode(',', $sendmail_uid));
          }
          $data ['msg'] = "成功关闭邮件报告！";
        }
        break;
      default :
        $data ['msg'] = "没有指定action！";
        break;
    }
    echo json_encode ($data);
  }

  function blockid($fid, $id, $day, $douid, $fw, $testMode=0) {
  
    $blockid_api = 'http://c.tieba.baidu.com/c/c/bawu/commitprison';
    $cookie=get_cookie($douid);
    $matches=explode('=', $cookie);
    if($testMode){
      $gd=self::check($fw, $BDUSS);//test
    }else{
      $BDUSS = trim($matches[1]);
      $_imei=md5($BDUSS);
      $_imei=str_replace("a", "3", $_imei);
      $_imei=str_replace("b", "9", $_imei);
      $_imei=str_replace("c", "8", $_imei);
      $_imei=str_replace("d", "0", $_imei);
      $_imei=str_replace("e", "2", $_imei);
      $_imei=str_replace("f", "5", $_imei);
      $__client_id='wappc_'.time().'985_211'; 
      $_imei=substr($_imei,10,15);
      $_cuid=strtoupper(strrev(md5('tsgirl'.$BDUSS.'tsgirl'))).'|'.strrev($_imei);
      $fiddata=Array(
        'BDUSS='.$BDUSS,
        '_client_id='.$__client_id,
        '_client_type=2',
        '_client_version=6.6.6',
        '_phone_imei='.$_imei,
        'cuid='.$_cuid,
        'day='.$day, 
        'fid='.$fid, 
        'ntn=banid', 
        'reason=抱歉，你的发贴操作或发表贴子的内容违反了本吧的吧规，已经被封禁，封禁期间不能在本吧继续发言。', 
        'tbs='.get_tbs($douid),
        'un='.$id, 
        'word='.$fw, 
        'z='.time()
      );
      $header = array ("Content-Type: application/x-www-form-urlencoded");
      $data=implode('&', $fiddata).'&sign='.strtoupper(md5(implode('', $fiddata).'tiebaclient!!!'));
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $blockid_api);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
      curl_setopt($ch, CURLOPT_USERAGENT, 'bdtb for Android 6.6.6 Chrome FuckBaidu 2.3 Mozilla 6.66 MSIE 10.0 capable');
      curl_setopt($ch, CURLOPT_TIMEOUT, 30);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      $gd = curl_exec($ch);
      curl_close($ch);
      $gd = json_decode($gd);    
    }
    return array('errno' => $gd->error_code, 'errmsg' => isset($gd->error_msg)?$gd->error_msg:'');  
  }
}

