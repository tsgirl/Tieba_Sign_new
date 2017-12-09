<?php
if(!defined('IN_KKFRAME')) exit();
function _get_tbs($uid, $BDUSS=null){
  static $tbs = array();
  if(isset($tbs[$uid])) return $tbs[$uid];
  if(!$BDUSS){
    $cookie = get_cookie($uid);
    $matches=explode('=', $cookie);
    $BDUSS = trim($matches[1]);
  }
  list($clientid, $phoneimei, $cuid, $zid)=device_id($BDUSS);
  $pda=Array(
    'BDUSS' => $BDUSS,
    '_client_id' => $clientid,
    '_client_type' => '2',
    '_client_version' => CLIENT_VERSION,
    '_phone_imei' => $phoneimei,
    'cuid' => $cuid,
    'from' => '1012990k',
    'kw' => '♂迷失の世界♀',
    'model' => 'SansungNote7BoomPhone',
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
  $res = json_decode(client_submit('http://c.tieba.baidu.com/c/f/frs/page', $pda), true);
  if(isset($res['anti']['tbs'])) return $tbs[$uid]=$res['anti']['tbs'];
  return null;
}

function _verify_cookie($cookie){
  $matches=explode('=', $cookie);
  $BDUSS = trim($matches[1]);
  list($clientid, $phoneimei, $cuid, $zid)=device_id($BDUSS);
  $pda=Array(
    'BDUSS' => $BDUSS,
    '_client_id' => $clientid,
    '_client_type' => '2',
    '_client_version' => CLIENT_VERSION,
    '_phone_imei' => $phoneimei,
    'cuid' => $cuid,
    'from' => '1012990k',
    'kw' => '♂迷失の世界♀',
    'model' => 'SansungNote7BoomPhone',
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
  $res = json_decode(client_submit('http://c.tieba.baidu.com/c/f/frs/page', $pda), true);
  if($res['error_code']==0){
    return $gd['user']['is_login'];
  }else{
    return $res['error_code'];
  }
}

function _get_baidu_userinfo($uid, $BDUSS=null){
  if(!$BDUSS){
    $cookie = get_cookie($uid);
    if(!$cookie) return array('error_code' => 4);
    $matches=explode('=', $cookie);
    $BDUSS = trim($matches[1]);
  }
  list($clientid, $phoneimei, $cuid, $zid)=device_id($BDUSS);
  $pda=Array(
    'BDUSS' => $BDUSS,
    '_client_id' => $clientid,
    '_client_type' => '2',
    '_client_version' => CLIENT_VERSION,
    '_phone_imei' => $phoneimei,
    'cuid' => $cuid,
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
  //$tbs_json = mb_convert_encoding($tbs_json, "utf8", "gbk");
  return json_decode(client_submit('http://c.tieba.baidu.com/c/u/user/profile', $pda), true);
}

function _get_liked_tieba($cookie){
  $matches=explode('=', $cookie);
  $BDUSS = trim($matches[1]);
  $pn = 1;
  $kw_name = array();
  if(!$userid=get_uid($BDUSS)) return array(null, '取UID失败！');
  list($clientid, $phoneimei, $cuid, $zid)=device_id($BDUSS);
  $count = 0;
  do{
    $pda=Array(
    'BDUSS' => $BDUSS,
    '_client_id' => $clientid,
    '_client_type' => '2',
    '_client_version' => CLIENT_VERSION,
    '_phone_imei' => $phoneimei,
    'cuid' => $cuid,
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
    $result = json_decode(client_submit('http://c.tieba.baidu.com/c/f/forum/like', $pda), true);
    if(!$result) return array(null, 'JSON解析失败');
    if($result['error_code']!=0) return array(null, $result['error_code'].$result['error_msg']);
    if($result['forum_list']['non-gconforum']){
      $forums = $result['forum_list']['non-gconforum'];
      for ($x=0; $x<sizeof($forums); $x++) {
        $kw_name[] = array(
          'name' => $forums[$x]['name'],
          'uname' => $forums[$x]['name'],
          'fid' => $forums[$x]['id'],
        );
        $count++;
      }
    }
    if($result['forum_list']['gconforum']){
      $forums = $result['forum_list']['gconforum'];
      for ($x=0; $x<sizeof($forums); $x++) {
        $kw_name[] = array(
          'name' => $forums[$x]['name'],
          'uname' => $forums[$x]['name'],
          'fid' => $forums[$x]['id'],
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
  if(!$cookie){
    if($ignore_error) return;
    showmessage('请先填写 Cookie 信息再更新', './#baidu_bind');
  }
  $liked_tieba = get_liked_tieba($cookie);
  $insert = $deleted = 0;
  if(!$liked_tieba[0]){
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
  $query = DB::query("SELECT * FROM my_tieba WHERE uid='{$uid}'");
  while($r = DB::fetch($query)) {
    $my_tieba[strval($r['fid'])] = $r;
    //某些极端条件（度娘bug）下吧名可能重复，但fid唯一
  }
  if(defined('DEBUG_ENABLED')) print_r($my_tieba);
  foreach($liked_tieba as $tieba){
    if(isset($my_tieba[strval($tieba['fid'])])){
      unset($my_tieba[strval($tieba['fid'])]);
      /*if(!$my_tieba[$tieba['fid']]['name']) DB::update('my_tieba', array(
        'fid' => $tieba['fid'],
        ), array(
          'uid' => $uid,
          'name' => $tieba['name'],
        ), true);
      //更新已有的贴吧，貌似没用？
      */
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
  DB::query("INSERT IGNORE INTO sign_log (tid, uid, `date`, fid) SELECT tid, uid, '{$date}', fid FROM my_tieba");
  if($my_tieba && $allow_deletion){
    $tieba_ids = array();
    foreach($my_tieba as $tieba){
      DB::query("DELETE FROM my_tieba WHERE uid='{$uid}' AND fid='{$tieba['fid']}'");
      DB::query("DELETE FROM sign_log WHERE tid='{$tieba['tid']}'");
    }
    $deleted = count($my_tieba);
  }
  return array($insert, $deleted);
}

function _client_sign_old($uid, $tieba, $BDUSS=null, $stoken=null){
//模拟手机网页签到
  if(!$BDUSS){
    $cookie = get_cookie($uid);
    $matches=explode('=', $cookie);
    $BDUSS = trim($matches[1]);
    if(!$BDUSS) return array(-1, '找不到 BDUSS Cookie', 0);
  }
  $tbs_tsgirl=get_tbs($uid);
  $cid=time().'_'.rand(100,999);
  $cookie_tsgirl='BAIDU_WISE_UID=wapp_'.time().'985_211;  
             USER_JUMP=2; 
             CLIENTWIDTH=320; 
             CLIENTHEIGHT=480; 
             index_cutoff_cover=1; 
             loginCk=1; 
             BDUSS='.$BDUSS.';
             app_open=1; 
             SEENKW=%E7%AC%AC%E4%B8%89%E7%B1%BB%E5%A4%A9%E4%BD%BF;
             mo_originid=2; LASW=1024';
  if($stoken) $cookie_tsgirl.=';STOKEN='.$stoken;
  $ch = curl_init('https://tieba.baidu.com/mo/q/sign?tbs='.$tbs_tsgirl.'&kw='.urlencode($tieba['name']).'&is_like=1&fid='.$tieba['fid']);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
    'Accept: application/json',
    'Accept-Language: zh-CN,zh; q=0.9'
  ));
  curl_setopt($ch, CURLOPT_COOKIE, $cookie_tsgirl);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36');
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  $res= json_decode(curl_exec($ch));
  curl_close($ch);
  if(!$res) return array(1, 'JSON 解析错误', 0);
  switch($res->no){
    case 0:
      $exp = $res->error;
      return array(2, "签到成功，经验值上升 {$exp}", $exp);
    case 1102: //sign too fast
      return array(1, $res->no.':'.$res->error, 0);
    case 1101:    // 已经签过
    case 11000: //user banned
      return array(2, $res->no.':'.$res->error, 0);
    case 1010:    // 贴吧被封
      return array(2, $res->no.':'.$res->error, 340001);
    default:
      return array(-1, $res->no.':'.$res->error, 0);
    
  }
}

function _client_sign($uid, $tieba, $BDUSS=null, $stoken=null){
//真正的客户端签到
  if(!$BDUSS){
    $cookie = get_cookie($uid);
    $matches=explode('=', $cookie);
    $BDUSS = trim($matches[1]);
    if(!$BDUSS) return array(-1, '找不到 BDUSS Cookie', 0);
  }
  list($clientid, $phoneimei, $cuid, $zid)=device_id($BDUSS);
  $pda = array(
    'BDUSS' => $BDUSS,
    '_client_id' => $clientid,
    '_client_type' => '2',
    '_client_version' => CLIENT_VERSION,
    '_phone_imei' => $phoneimei,
    'cuid' => $cuid,
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
    'timestamp' => time().rand(100, 999),
  );
  $res=json_decode(client_submit('http://c.tieba.baidu.com/c/c/forum/sign', $pda), true);
  if(!$res) return array(1, 'JSON 解析错误', 0);
  switch($res['error_code']){
    case 0:
      $exp = $res['user_info']['sign_bonus_point'];
      return array(2, "签到成功，经验值上升 {$exp}", $exp);
    case 340010:    // 已经签过
    case 160002:
    case 3:
    case 3250004:    // user banned by bawu!!!
    case 3250002:    // user banned by system!!!
      return array(2, $res['error_msg'], 0);
    case 1:      // 未登录
      return array(-1, "ERROR-{$res['error_code']}: {$res['error_msg']} （Cookie 过期或不正确）", 0);
    case 160004:    // 不支持
      return array(-1, "ERROR-{$res['error_code']}: {$res['error_msg']}", 0);
    case 160003:    // 零点 稍后再试
    case 160008:    // 太快了
      return array(1, "ERROR-{$res['error_code']}: {$res['error_msg']}", 0);
    case 340006:    // this tieba is banned!!!
      return array(2, "ERROR-{$res['error_code']}: {$res['error_msg']}", 340001);
    default:
      if($res['error_code']>=3250000) return array(2, $res['error_msg'], 0);
      return array(1, "ERROR-{$res['error_code']}: {$res['error_msg']}", 0);
    
  }
}

function _client_sign_new($uid, $tieba, $BDUSS=null, $stoken=null){
//带stoken客户端签到
  if(!$BDUSS){
    $cookie = get_cookie($uid);
    $matches=explode('=', $cookie);
    $BDUSS = trim($matches[1]);
    if(!$BDUSS) return array(-1, '找不到 BDUSS Cookie', 0);
  }
  if(!$stoken) $stoken=get_verified_stoken_from_uid($uid);
  list($clientid, $phoneimei, $cuid, $zid)=device_id($BDUSS, 2);
  $pda = array(
    'BDUSS' => $BDUSS,
    '_client_id' => $clientid,
    '_client_type' => '2',
    '_client_version' => CLIENT_VERSION_NEW,
    '_phone_imei' => $phoneimei,
    'cuid' => $cuid,
    'fid' => $tieba['fid'],
    'from' => 'an_leshangdian',
    'kw' => $tieba['name'],
    'model' => 'SansungNote7BoomPhone',
    'stErrorNums' => '1',
    'stMethod' => '1',
    'stMode' => '1',
    'stSize' => random(5, true),
    'stTime' => random(4, true),
    'stTimesNum' => '1',
    'stoken' => $stoken,
    'tbs' => get_tbs($uid),
    'timestamp' => time().rand(100, 999),
    'z_id' => $zid
  );
  $res=json_decode(client_submit('http://c.tieba.baidu.com/c/c/forum/sign', $pda), true);
  if(!$res) return array(1, 'JSON 解析错误', 0);
  switch($res['error_code']){
    case 0:
      $exp = $res['user_info']['sign_bonus_point'];
      return array(2, "签到成功，经验值上升 {$exp}", $exp);
    case 340010:    // 已经签过
    case 160002:
    case 3:
    case 3250004:    // user banned by bawu!!!
    case 3250002:    // user banned by system!!!
      return array(2, $res['error_msg'], 0);
    case 1:      // 未登录
      return array(-1, "ERROR-{$res['error_code']}: {$res['error_msg']} （Cookie 过期或不正确）", 0);
    case 160004:    // 不支持
      return array(-1, "ERROR-{$res['error_code']}: {$res['error_msg']}", 0);
    case 160003:    // 零点 稍后再试
    case 160008:    // 太快了
      return array(1, "ERROR-{$res['error_code']}: {$res['error_msg']}", 0);
    case 340006:    // this tieba is banned!!!
      return array(2, "ERROR-{$res['error_code']}: {$res['error_msg']}", 340001);
    default:
      if($res['error_code']>=3250000) return array(2, $res['error_msg'], 0);
      return array(1, "ERROR-{$res['error_code']}: {$res['error_msg']}", 0);
    
  }
}

function _pc_sign($uid, $tieba, $BDUSS=null, $stoken=null){
//电脑签到
  if(!$BDUSS){
    $cookie = get_cookie($uid);
    $matches=explode('=', $cookie);
    $BDUSS = trim($matches[1]);
    if(!$BDUSS) return array(-1, '找不到 BDUSS Cookie', 0);
  }
  $tbs_tsgirl=get_tbs($uid);
  $cookie_tsgirl='BAIDU_WISE_UID=wapp_'.time().'985_211;  
             USER_JUMP=2; 
             CLIENTWIDTH=320; 
             CLIENTHEIGHT=480; 
             index_cutoff_cover=1; 
             loginCk=1; 
             BDUSS='.$BDUSS.';
             app_open=1; 
             SEENKW=%E7%AC%AC%E4%B8%89%E7%B1%BB%E5%A4%A9%E4%BD%BF;
             mo_originid=2; LASW=1024';
  if(!$stoken) $stoken=get_verified_stoken_from_uid($uid);
  if($stoken) $cookie_tsgirl.='; STOKEN='.$stoken;
  $postfields='ie=utf-8&tbs='.$tbs_tsgirl.'&kw='.urlencode($tieba['name']);
  $ch = curl_init('https://tieba.baidu.com/sign/add');
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
    'Accept: application/json',
    'Accept-Language: zh-CN,zh;q=0.9'
  ));
  curl_setopt($ch, CURLOPT_COOKIE, $cookie_tsgirl);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
  curl_setopt($ch, CURLOPT_REFERER, 'https://tieba.baidu.com/f?kw='.urlencode($tieba['name']));
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36');
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  $res= json_decode(curl_exec($ch));
  curl_close($ch);
  if(!$res) return array(1, 'JSON 解析错误', 0);
  switch($res->no){
    case 0:
      return array(2, '签到成功', 0); //pc签到不返回经验增量
    case 2280002: //user banned
    case 1101:    // 已经签过
      return array(2, $res->no.':'.$res->error, 0);
    case 1010:    // 贴吧被封
      return array(2, $res->no.':'.$res->error, 340001);
    case 2150040:
      return array(1, $res->no.':'.$res->error, 340001);
    default:
      return array(1, $res->no.':'.$res->error, 0);
    
  }
}

function _onekey_sign($uid, $BDUSS=null, $stoken=null){
//一键签到
  if(!$BDUSS){
    $cookie = get_cookie($uid);
    $matches=explode('=', $cookie);
    $BDUSS = trim($matches[1]);
    if(!$BDUSS) return array(-1, '找不到 BDUSS Cookie', 0);
  }
  $cookie_tsgirl='BAIDU_WISE_UID=wapp_'.time().'985_211;  
             USER_JUMP=2; 
             CLIENTWIDTH=320; 
             CLIENTHEIGHT=480; 
             index_cutoff_cover=1; 
             loginCk=1; 
             BDUSS='.$BDUSS.';
             app_open=1; 
             SEENKW=%E7%AC%AC%E4%B8%89%E7%B1%BB%E5%A4%A9%E4%BD%BF;
             mo_originid=2; LASW=1024';
  if(!$stoken) $stoken=get_verified_stoken_from_uid($uid);
  if($stoken) $cookie_tsgirl.='; STOKEN='.$stoken;
  $postfields='ie=utf-8&tbs='.get_tbs($uid);
  $ch = curl_init('http://tieba.baidu.com/tbmall/onekeySignin1');
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
    'Accept: application/json',
    'Accept-Language: zh-CN,zh;q=0.9'
  ));
  curl_setopt($ch, CURLOPT_COOKIE, $cookie_tsgirl);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
  curl_setopt($ch, CURLOPT_REFERER, 'https://tieba.baidu.com/');
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36');
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  $res= json_decode(curl_exec($ch), true);
  curl_close($ch);
  if(!$res) return array(1, 'JSON 解析错误', null);
  switch($res['no']){
    case 0:
      return array(2, '一键签到成功', $res['data']['forum_list']);//返回已签到贴吧详细信息
    case 2280006://已经使用一键签到
    case 2500113://没有可以签到的贴吧
      return array(2, $res['no'].':'.$res['error'], null);
    default:
      return array(-1, $res['no'].':'.$res['error'], null);
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
  list($clientid, $phoneimei, $cuid, $zid)=device_id($BDUSS);
  $pda=Array(
    'BDUSS' => $BDUSS,
    '_client_id' => $clientid,
    '_client_type' => '2',
    '_client_version' => CLIENT_VERSION,
    '_phone_imei' => $phoneimei,
    'cuid' => $cuid,
    'from' => '1012990k',
    'kw' => '♂迷失の世界♀',
    'model' => 'SansungNote7BoomPhone',
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
  $res=json_decode(client_submit('http://c.tieba.baidu.com/c/f/frs/page', $pda), true);
  if ($res['error_code']!=0||$res['user']['is_login']==0) return 0;
  return $res['user']['id'];
}

function _check_stoken($BDUSS, $stoken){
  $cookie_tsgirl='BAIDU_WISE_UID=wapp_'.time().'985_211;  
             USER_JUMP=2; 
             CLIENTWIDTH=320; 
             CLIENTHEIGHT=480; 
             index_cutoff_cover=1; 
             loginCk=1; 
             BDUSS='.$BDUSS.';
             app_open=1; 
             SEENKW=%E7%AC%AC%E4%B8%89%E7%B1%BB%E5%A4%A9%E4%BD%BF;
             mo_originid=2; LASW=1024; STOKEN='.$stoken;
  $ch = curl_init('http://tieba.baidu.com/f/user/json_userinfo?_='.time().rand(111,999));
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Accept: application/json',
    'Accept-Language: zh-CN,zh;q=0.9'
  ));
  curl_setopt($ch, CURLOPT_COOKIE, $cookie_tsgirl);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_REFERER, 'https://tieba.baidu.com/');
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36');
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
  $re=curl_exec($ch);
  curl_close($ch);
  if(!json_decode($re)) return false;
  return true;
}
    
function _device_id($BDUSS, $imeiOffset=0){
  if($imeiOffset>16) $imeiOffset=1;
  for($x=0; $x<=$imeiOffset; $x++) $BDUSS.='tsgirl';
  $_phone_imei=md5($BDUSS);
  $_phone_imei=str_replace('a', '1', $_phone_imei);
  $_phone_imei=str_replace('b', '2', $_phone_imei);
  $_phone_imei=str_replace('c', '3', $_phone_imei);
  $_phone_imei=str_replace('d', '4', $_phone_imei);
  $_phone_imei=str_replace('e', '5', $_phone_imei);
  $_phone_imei=str_replace('f', '6', $_phone_imei);
  $_client_id='wappc_'.(time()-$imeiOffset).rand(111,999).'_'.rand(111,999); 
  $_phone_imei=substr($_phone_imei, $imeiOffset, 15);
  $cuid=strtoupper(strrev(md5('tsgirl'.$BDUSS.$_phone_imei.'tsgirl'))).'|'.strrev($_phone_imei);
  $z_id=strtoupper(md5($_phone_imei.substr(md5($BDUSS), $imeiOffset, 16).$_phone_imei)).'|'.strrev($_phone_imei);
  return array($_client_id, $_phone_imei, $cuid, $z_id);
}

function _get_frs($uid, $tieba, $BDUSS=null, $pn=1){
//获取贴吧贴子列表
  if(!$BDUSS){
    $cookie = get_cookie($uid);
    $matches=explode('=', $cookie);
    $BDUSS = trim($matches[1]);
    if(!$BDUSS) return array(-1, '找不到 BDUSS Cookie', 0);
  }
  if(!is_numeric($pn)) $pn=1;
  list($clientid, $phoneimei, $cuid, $zid)=device_id($BDUSS);
  $pda=Array(
    'BDUSS' => $BDUSS,
    '_client_id' => $clientid,
    '_client_type' => '2',
    '_client_version' => CLIENT_VERSION,
    '_phone_imei' => $phoneimei,
    'cuid' => $cuid,
    'from' => '1012990k',
    'kw' => $tieba,
    'model' => 'SansungNote7BoomPhone',
    'pn' => $pn,
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
  return client_submit('http://c.tieba.baidu.com/c/f/frs/page', $pda);
}

function _op_agree($uid, $fid, $tid, $pid=null, $tbs=null, $BDUSS=null, $stoken=null){
//带stoken客户端点赞
  if(!$BDUSS){
    $cookie = get_cookie($uid);
    $matches=explode('=', $cookie);
    $BDUSS = trim($matches[1]);
    if(!$BDUSS) return array(-1, '找不到 BDUSS Cookie', 0);
  }
  if(!$tbs) $tbs=get_tbs($uid);
  if(!$stoken) $stoken=get_verified_stoken_from_uid($uid);
  list($clientid, $phoneimei, $cuid, $zid)=device_id($BDUSS, 2);
  if($pid){
    $pda = array(
      'BDUSS' => $BDUSS,
      '_client_id' => $clientid,
      '_client_type' => '2',
      '_client_version' => CLIENT_VERSION_NEW,
      '_phone_imei' => $phoneimei,
      'agree_type' => '2',
      'cuid' => $cuid,
      'forum_id' => $fid,
      'from' => 'an_leshangdian',
      'model' => 'SansungNote7BoomPhone',
      'obj_type' => '3',
      'op_type' => '0',
      'pid' => $pid,
      'stErrorNums' => '1',
      'stMethod' => '1',
      'stMode' => '1',
      'stSize' => random(4, true),
      'stTime' => random(3, true),
      'stTimesNum' => '1',
      'stoken' => $stoken,
      'tbs' => $tbs,
      'thread_id' => $tid,
      'timestamp' => time().rand(100, 999)
    );
  }else{
    $pda = array(
      'BDUSS' => $BDUSS,
      '_client_id' => $clientid,
      '_client_type' => '2',
      '_client_version' => CLIENT_VERSION_NEW,
      '_phone_imei' => $phoneimei,
      'agree_type' => '2',
      'cuid' => $cuid,
      'forum_id' => $fid,
      'from' => 'an_leshangdian',
      'model' => 'SansungNote7BoomPhone',
      'obj_type' => '3',
      'op_type' => '0',
      'stErrorNums' => '1',
      'stMethod' => '1',
      'stMode' => '1',
      'stSize' => random(4, true),
      'stTime' => random(3, true),
      'stTimesNum' => '1',
      'stoken' => $stoken,
      'tbs' => $tbs,
      'thread_id' => $tid,
      'timestamp' => time().rand(100, 999)
    );
  }
  $res=json_decode(client_submit('http://c.tieba.baidu.com/c/c/agree/opAgree', $pda), true);
  if(!$res) return array(0, 'JSON 解析失败！', null);
  return array( $res['error_code'] ? 1 : 2 , $res['error_code'] ? 'failed' :'success' , $res);
}

