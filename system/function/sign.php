<?php
if(!defined('IN_KKFRAME')) exit();
function _get_tbs($uid){
	static $tbs = array();
	//if($tbs[$uid]) return $tbs[$uid];
	$cookie = get_cookie($uid);
	$matches=explode('=', $cookie);
	$BDUSS = trim($matches[1]);
  $_imei=md5($BDUSS);
  $_imei=str_replace("a", "3", $_imei);
  $_imei=str_replace("b", "9", $_imei);
  $_imei=str_replace("c", "8", $_imei);
  $_imei=str_replace("d", "0", $_imei);
  $_imei=str_replace("e", "2", $_imei);
  $_imei=str_replace("f", "5", $_imei);
  $__client_id='wappc_'.time().'985_211'; 
  $_imei=substr($_imei,15,15);
  $_cuid=strtoupper(strrev(md5('tsgirl'.$BDUSS.'tsgirl'))).'|'.strrev($_imei);
	$pda=Array(
    'BDUSS' => $BDUSS,
    '_client_id' => $__client_id,
    '_client_type' => '2',
    '_client_version' => '7.4.5',
    '_phone_imei' => $_imei,
    'cuid' => $_cuid,
    'from' => '1012990k',
    'kw' => '♂迷失の世界♀',
    'model' => 'H701',
    'pn' => '1',
    'q_type' => '2',
    'rn' => '35',
    'scr_dip' => '2.8125',
    'scr_h' => '1552',
    'scr_w' => '900',
    'stErrorNums' => '0',
    'stMethod' => '1',
    'stMode' => '1',
    'stSize' => rand(1000,9999),
    'stTime' => rand(100,999),
    'stTimesNum' => '0',
    'st_type' => 'tb_forumlist',
    'timestamp' => time().rand(111,999),
    'with_group' => '1'
  );
  foreach($pda as $k=>$v){
	  $x.=$k.'='.$v;
  }
  $pda['sign'] = strtoupper(md5($x."tiebaclient!!!"));
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://c.tieba.baidu.com/c/f/frs/page');
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_USERAGENT, 'bdtb for Android 6.6.6 Chrome FuckBaidu 2.3 Mozilla 6.66 MSIE 10.0 capable');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $pda);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	$gd = curl_exec($ch);
	curl_close($ch);
  $gd=json_decode($gd);
  return $tbs[$uid]=$gd->anti->tbs;
}

function _verify_cookie($cookie){
	$matches=explode('=', $cookie);
	$BDUSS = trim($matches[1]);
  $_imei=md5($BDUSS);
  $_imei=str_replace("a", "3", $_imei);
  $_imei=str_replace("b", "9", $_imei);
  $_imei=str_replace("c", "8", $_imei);
  $_imei=str_replace("d", "0", $_imei);
  $_imei=str_replace("e", "2", $_imei);
  $_imei=str_replace("f", "5", $_imei);
  $__client_id='wappc_'.time().'985_211'; 
  $_imei=substr($_imei,15,15);
  $_cuid=strtoupper(strrev(md5('tsgirl'.$BDUSS.'tsgirl'))).'|'.strrev($_imei);
	$pda=Array(
    'BDUSS' => $BDUSS,
    '_client_id' => $__client_id,
    '_client_type' => '2',
    '_client_version' => '7.4.5',
    '_phone_imei' => $_imei,
    'cuid' => $_cuid,
    'from' => '1012990k',
    'kw' => '♂迷失の世界♀',
    'model' => 'H701',
    'pn' => '1',
    'q_type' => '2',
    'rn' => '35',
    'scr_dip' => '2.8125',
    'scr_h' => '1552',
    'scr_w' => '900',
    'stErrorNums' => '0',
    'stMethod' => '1',
    'stMode' => '1',
    'stSize' => rand(1000,9999),
    'stTime' => rand(100,999),
    'stTimesNum' => '0',
    'st_type' => 'tb_forumlist',
    'timestamp' => time().rand(111,999),
    'with_group' => '1'
  );
  foreach($pda as $k=>$v){
	  $x.=$k.'='.$v;
  }
  $pda['sign'] = strtoupper(md5($x."tiebaclient!!!"));
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://c.tieba.baidu.com/c/f/frs/page');
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_USERAGENT, 'bdtb for Android 6.6.6 Chrome FuckBaidu 2.3 Mozilla 6.66 MSIE 10.0 capable');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  
	curl_setopt($ch, CURLOPT_POSTFIELDS, $pda);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	$gd = curl_exec($ch);
	curl_close($ch);
  $gd=json_decode($gd);
  if($gd->error_code=='0'){
	  return $gd->user->is_login;
	}else{
	  return $gd->error_code;
	}
}

function _get_baidu_userinfo($uid){
	$cookie = get_cookie($uid);
	if(!$cookie) return array('error_code' => 4);
	$matches=explode('=', $cookie);
	$BDUSS = trim($matches[1]);
  $_imei=md5($BDUSS);
  $_imei=str_replace("a", "3", $_imei);
  $_imei=str_replace("b", "9", $_imei);
  $_imei=str_replace("c", "8", $_imei);
  $_imei=str_replace("d", "0", $_imei);
  $_imei=str_replace("e", "2", $_imei);
  $_imei=str_replace("f", "5", $_imei);
  $__client_id='wappc_'.time().'985_211'; 
  $_imei=substr($_imei,15,15);
  $_cuid=strtoupper(strrev(md5('tsgirl'.$BDUSS.'tsgirl'))).'|'.strrev($_imei);
  $pda=Array(
    'BDUSS' => $BDUSS,
    '_client_id' => $__client_id,
    '_client_type' => '2',
    '_client_version' => '7.4.5',
    '_phone_imei' => $_imei,
    'cuid' => $_cuid,
    'need_post_count' => '1',
    'pn' => '1',
    'rn' => '20',
    'scr_dip' => '2.8125',
    'scr_h' => '1552',
    'scr_w' => '900',
    'stErrorNums' => '0',
    'stMethod' => '1',
    'stMode' => '1',
    'stSize' => rand(1000,9999),
    'stTime' => rand(100,999),
    'stTimesNum' => '0',
    'st_type' => 'null',
    'timestamp' => time().rand(111,999),
    'uid' => get_uid($BDUSS),
  );
	foreach($pda as $k=>$v){
	  $x.=$k.'='.$v;
  }
  $pda['sign'] = strtoupper(md5($x."tiebaclient!!!"));
	$tbs_url = 'https://c.tieba.baidu.com/c/u/user/profile';
	$ch = curl_init($tbs_url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $pda);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_COOKIE, $cookie_tsgirl);
  curl_setopt($ch, CURLOPT_USERAGENT, 'bdtb for Android 6.6.6 Chrome FuckBaidu 2.3 Mozilla 6.66 MSIE 10.0 capable');
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  
	$tbs_json = curl_exec($ch);
	curl_close($ch);
	//$tbs_json = mb_convert_encoding($tbs_json, "utf8", "gbk");
	return json_decode($tbs_json, true);
}

function _get_liked_tieba($cookie){
	$matches=explode('=', $cookie);
	$BDUSS = trim($matches[1]);
	$pn = 1;
	$kw_name = array();
	$retry = 0;
	$userid=get_uid($BDUSS);
  $_imei=md5($BDUSS);
  $_imei=str_replace("a", "3", $_imei);
  $_imei=str_replace("b", "9", $_imei);
  $_imei=str_replace("c", "8", $_imei);
  $_imei=str_replace("d", "0", $_imei);
  $_imei=str_replace("e", "2", $_imei);
  $_imei=str_replace("f", "5", $_imei);
  $__client_id='wappc_'.time().'985_211'; 
  $_imei=substr($_imei,15,15);
  $_cuid=strtoupper(strrev(md5('tsgirl'.$BDUSS.'tsgirl'))).'|'.strrev($_imei);
	do{
    $pda=Array(
    'BDUSS' => $BDUSS,
    '_client_id' => $__client_id,
    '_client_type' => '2',
    '_client_version' => '7.4.5',
    '_phone_imei' => $_imei,
    'cuid' => $_cuid,
    'need_post_count' => '1',
    'page_no' => $pn,
    'page_size' => '50',
    'scr_dip' => '2.8125',
    'scr_h' => '1552',
    'scr_w' => '900',
    'stErrorNums' => '0',
    'stMethod' => '1',
    'stMode' => '1',
    'stSize' => rand(1000,9999),
    'stTime' => rand(100,999),
    'stTimesNum' => '0',
    'st_type' => 'null',
    'timestamp' => time().rand(111,999),
    'uid' => $userid,
  );
	foreach($pda as $k=>$v){
	  $x.=$k.'='.$v;
  }
  $pda['sign'] = strtoupper(md5($x."tiebaclient!!!"));
	$tbs_url = 'https://c.tieba.baidu.com/c/f/forum/like';
	$ch = curl_init($tbs_url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $pda);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_COOKIE, $cookie_tsgirl);
  curl_setopt($ch, CURLOPT_USERAGENT, 'bdtb for Android 6.6.6 Chrome FuckBaidu 2.3 Mozilla 6.66 MSIE 10.0 capable');
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	$tbs_json = curl_exec($ch);
	curl_close($ch);
	$result = json_decode($tbs_json, true);
	if($result['error_code']!='0') return array('.',$result['error_code']);
	if($result['user']['is_login']=='0') return array('.','not_login');
		$count = 0;
	if($result['forum_list']['non-gconforum']){
		$forums = $result['forum_list']['non-gconforum'];
		for ($x=0; $x<sizeof($forums);$x++) {
			$kw_name[] = array(
				'name' => $forums[$x]['name'],
				'uname' => $forums[$x]['name'],
				'fid' => $forums[$x]['id'],
			);
			$count++;
		}
	}
	if($result['forum_list']['gconforum']){
		$forums2 = $result['forum_list']['gconforum'];
		for ($x=0; $x<sizeof($forums2);$x++) {
			$kw_name[] = array(
				'name' => $forums2[$x]['name'],
				'uname' => $forums2[$x]['name'],
				'fid' => $forums2[$x]['id'],
			);
			$count++;
		}
	}
	$pn++;
	}while($result['has_more']==1);
	return $kw_name;
}

function _update_liked_tieba($uid, $ignore_error = false, $allow_deletion = true){
	$date = date('Ymd', TIMESTAMP + 900);
	$cookie = get_cookie($uid);
	if(!$cookie) return array('no' => 4);
	$matches=explode('=', $cookie);
	$BDUSS = trim($matches[1]);
	if(!$cookie){
		if($ignore_error) return;
		showmessage('请先填写 Cookie 信息再更新', './#baidu_bind');
	}
	$liked_tieba = get_liked_tieba($cookie);
	$insert = $deleted = 0;
	if($liked_tieba[0]==' '){
		if($ignore_error) return;
		showmessage('无法获取喜欢的贴吧('.$liked_tieba[1].')', './#baidu_bind');
	}
	if($limit = getSetting('max_tieba')){
		$count = count($liked_tieba);
		if($limit < $count){
			if($ignore_error) return;
			showmessage("<p>您共计关注了 {$count} 个贴吧，</p><p>管理员限制了每位用户最多关注 {$limit} 个贴吧</p>", './#liked_tieba');
		}
	}
	$my_tieba = array();
	$query = DB::query("SELECT name, fid, tid FROM my_tieba WHERE uid='{$uid}'");
	while($r = DB::fetch($query)) {
		$my_tieba[$r['name']] = $r;
	}
	foreach($liked_tieba as $tieba){
		if($my_tieba[$tieba['name']]){
			unset($my_tieba[$tieba['name']]);
			if(!$my_tieba[$tieba['name']]['fid']) DB::update('my_tieba', array(
				'fid' => $tieba['fid'],
				), array(
					'uid' => $uid,
					'name' => $tieba['name'],
				), true);
			continue;
		}else{
			DB::insert('my_tieba', array(
				'uid' => $uid,
				'fid' => $tieba['fid'],
				'name' => $tieba['name'],
				'unicode_name' => $tieba['uname'],
				), false, true, true);
			$insert++;
		}
	}
	DB::query("INSERT IGNORE INTO sign_log (tid, uid, `date`) SELECT tid, uid, '{$date}' FROM my_tieba");
	if($my_tieba && $allow_deletion){
		$tieba_ids = array();
		foreach($my_tieba as $tieba){
			$tieba_ids[] = $tieba['tid'];
		}
		$str = "'".implode("', '", $tieba_ids)."'";
		$deleted = count($my_tieba);
		DB::query("DELETE FROM my_tieba WHERE uid='{$uid}' AND tid IN ({$str})");
		DB::query("DELETE FROM sign_log WHERE uid='{$uid}' AND tid IN ({$str})");
	}
	return array($insert, $deleted);
}

function _client_sign_old($uid, $tieba){
//模拟手机网页签到
	$cookie = get_cookie($uid);
	$matches=explode('=', $cookie);
	$BDUSS = trim($matches[1]);
	if(!$BDUSS) return array(-1, '找不到 BDUSS Cookie', 0);
	$tbs_tsgirl=get_tbs($uid);
	if($tbs_tsgirl) return array(-1, '登录失效', 0);
	$cid=time().'_'.rand(100,999);
	$cookie_tsgirl='BAIDU_WISE_UID=wapp_'.$cid.';  
	           USER_JUMP=2; 
	           CLIENTWIDTH=320; 
	           CLIENTHEIGHT=480; 
	           index_cutoff_cover=1; 
	           loginCk=1; 
	           BDUSS='.$BDUSS.';
	           app_open=1; 
	           SEENKW=%E7%AC%AC%E4%B8%89%E7%B1%BB%E5%A4%A9%E4%BD%BF;
	           mo_originid=2; LASW=1024';
	$ch = curl_init('https://tieba.baidu.com/mo/q/sign?tbs='.$tbs_tsgirl.'&kw='.urlencode($tieba['name']).'&is_like=1&fid='.$tieba['fid']);
	curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_COOKIE, $cookie_tsgirl);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, 'bdtb for Android 6.6.6 Chrome FuckBaidu 2.13 Mozilla 6.66 MSIE 10.0 capable');
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$res= json_decode(curl_exec($ch));
  curl_close($ch);
	if(!$res) return array(1, 'JSON 解析错误', 0);
	if($res->no==0){
		$exp = $res->error;
		return array(2, "签到成功，经验值上升 {$exp}", $exp);
	}else{
		switch($res->no){
			case '1101':		// 已经签过
				return array(2, $res->no.':'.$res->error, 0);
      case '1010':		// 已经签过
				return array(2, $res->no.':'.$res->error, 0);
			default:
				return array(-1, $res->no.':'.$res->error.'(/mo/q/sign?tbs='.$tbs_tsgirl.'&kw='.$tieba['name'].'&is_like=1&fid='.$tieba['fid'].')' , 0);
		}
	}
}

function _client_sign($uid, $tieba){
//真正的客户端签到
	$cookie = get_cookie($uid);
	$matches=explode('=', $cookie);
	$BDUSS = trim($matches[1]);
	if(!$BDUSS) return array(-1, '找不到 BDUSS Cookie', 0);
  $_imei=md5($BDUSS);
  $_imei=str_replace("a", "3", $_imei);
  $_imei=str_replace("b", "9", $_imei);
  $_imei=str_replace("c", "8", $_imei);
  $_imei=str_replace("d", "0", $_imei);
  $_imei=str_replace("e", "2", $_imei);
  $_imei=str_replace("f", "5", $_imei);
  $__client_id='wappc_'.time().'985_211'; 
  $_imei=substr($_imei,15,15);
  $_cuid=strtoupper(strrev(md5('tsgirl'.$BDUSS.'tsgirl'))).'|'.strrev($_imei);
	if(!$BDUSS) return array(-1, '找不到 BDUSS Cookie', 0);
	$ch = curl_init('http://c.tieba.baidu.com/c/c/forum/sign');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', 'User-Agent: BaiduTieba for Android 5.1.3', 'client_user_token: '.random(6, true)));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, 1);
	$array = array(
		'BDUSS' => $BDUSS,
		'_client_id' => $__client_id,
		'_client_type' => '2',
		'_client_version' => '7.4.5',
		'_phone_imei' => $_imei,
		'cuid' => $_cuid,
		'fid' => $tieba['fid'],
		'from' => 'tieba',
		'kw' => $tieba['name'],
		'model' => 'SansungNote7BoomPhone',
		'net_type' => '3',
		'stErrorNums' => '0',
		'stMethod' => '1',
		'stMode' => '1',
		'stSize' => random(5, true),
		'stTime' => random(4, true),
		'stTimesNum' => '0',
		'tbs' => get_tbs($uid),
		'timestamp' => time().rand(1000, 9999),
	);
	$sign_str = '';
	foreach($array as $k=>$v) $sign_str .= $k.'='.$v;
	$sign = strtoupper(md5($sign_str.'tiebaclient!!!'));
	$array['sign'] = $sign;
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($array));
	$sign_json = curl_exec($ch);
	curl_close($ch);
	$res = @json_decode($sign_json, true);
	if(!$res) return array(1, 'JSON 解析错误', 0);
	if($res['user_info']){
		$exp = $res['user_info']['sign_bonus_point'];
		return array(2, "签到成功，经验值上升 {$exp}", $exp);
	}else{
		switch($res['error_code']){
			case '340010':		// 已经签过
			case '160002':
			case '3':
				return array(2, $res['error_msg'], 0);
			case '1':			// 未登录
				return array(-1, "ERROR-{$res[error_code]}: ".$res['error_msg'].' （Cookie 过期或不正确）', 0);
			case '160004':		// 不支持
				return array(-1, "ERROR-{$res[error_code]}: ".$res['error_msg'], 0);
			case '160003':		// 零点 稍后再试
			case '160008':		// 太快了
				return array(1, "ERROR-{$res[error_code]}: ".$res['error_msg'], 0);
			default:
				return array(1, "ERROR-{$res[error_code]}: ".$res['error_msg'], 0);
		}
	}
}

function _zhidao_sign($uid){
	$ch = curl_init('http://zhidao.baidu.com/submit/user');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_COOKIE, get_cookie($uid));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, 'cm=100509&t='.TIMESTAMP);
	$result = curl_exec($ch);
	curl_close($ch);
	return @json_decode($result);
}

function _wenku_sign($uid){
	$ch = curl_init('http://wenku.baidu.com/task/submit/signin');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.43 BIDUBrowser/2.x Safari/537.31'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_COOKIE, get_cookie($uid));
	$result = curl_exec($ch);
	curl_close($ch);
	return @json_decode($result);
}


function _get_uid($BDUSS){
  $_imei=md5($BDUSS);
  $_imei=str_replace("a", "3", $_imei);
  $_imei=str_replace("b", "9", $_imei);
  $_imei=str_replace("c", "8", $_imei);
  $_imei=str_replace("d", "0", $_imei);
  $_imei=str_replace("e", "2", $_imei);
  $_imei=str_replace("f", "5", $_imei);
  $__client_id='wappc_'.time().'985_211'; 
  $_imei=substr($_imei,15,15);
  $_cuid=strtoupper(strrev(md5('tsgirl'.$BDUSS.'tsgirl'))).'|'.strrev($_imei);
	$pda=Array(
    'BDUSS' => $BDUSS,
    '_client_id' => $__client_id,
    '_client_type' => '2',
    '_client_version' => '7.4.5',
    '_phone_imei' => $_imei,
    'cuid' => $_cuid,
    'from' => '1012990k',
    'kw' => '♂迷失の世界♀',
    'model' => 'H701',
    'pn' => '1',
    'q_type' => '2',
    'rn' => '35',
    'scr_dip' => '2.8125',
    'scr_h' => '1552',
    'scr_w' => '900',
    'stErrorNums' => '0',
    'stMethod' => '1',
    'stMode' => '1',
    'stSize' => rand(1000,9999),
    'stTime' => rand(100,999),
    'stTimesNum' => '0',
    'st_type' => 'tb_forumlist',
    'timestamp' => time().rand(111,999),
    'with_group' => '1'
  );
  foreach($pda as $k=>$v){
	  $x.=$k.'='.$v;
  }
  $pda['sign'] = strtoupper(md5($x."tiebaclient!!!"));
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://c.tieba.baidu.com/c/f/frs/page');
	curl_setopt($ch, CURLOPT_HTTPHEADER, $pda);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_USERAGENT, 'bdtb for Android 6.6.6 Chrome FuckBaidu 2.3 Mozilla 6.66 MSIE 10.0 capable');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $pda);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	$gd = curl_exec($ch);
	curl_close($ch);
  $gd=json_decode($gd);
  if ($gd->user->is_login==0) return 0;
	return $gd->user->id;
}

    