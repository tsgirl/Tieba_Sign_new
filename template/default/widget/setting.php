<?php
if(!defined('IN_KKFRAME')) exit();
?>
<h2>设置</h2>
<form method="post" action="index.php?action=update_setting" id="setting_form" onsubmit="return post_win(this.action, this.id)">
<input type="hidden" name="formhash" value="<?php echo $formhash; ?>">
<p>签到方式：</p>
<p><label><input type="radio" name="sign_method" id="sign_method_1" value="1" readonly />模拟电脑网页签到</label></p>
<p><label><input type="radio" name="sign_method" id="sign_method_2" value="2" readonly />模拟手机网页签到</label></p>
<p><label><input type="radio" name="sign_method" id="sign_method_3" value="3" readonly />模拟手机客户端签到（默认）</label></p>
<p>如果经常出现漏签，可以尝试更换签到方式</p>
<p>附加签到：</p>
<p><label><input type="checkbox" disabled name="zhidao_sign" id="zhidao_sign" value="1" />自动签到百度知道</label></p>
<p><label><input type="checkbox" disabled name="wenku_sign" id="wenku_sign" value="1" />自动签到百度文库</label></p>
<p>报告设置：</p>
<p><label><input type="checkbox" disabled name="error_mail" id="error_mail" value="1" />当天有无法签到的贴吧时给我发送邮件</label></p>
<p><label><input type="checkbox" disabled name="send_mail" id="send_mail" value="1" />每日发送一封签到报告邮件</label></p>
<p>**测试性功能**</p>
<p><label><input type="checkbox" disabled name="force_sign" id="force_sign" value="1" />使用一键签到接口暴力签到被封禁贴吧，适用于强迫症患者(但可能导致后续签到记录经验值异常)</label></p>
<p>stoken：<label><input type="text" name="stoken" id="stoken" placeholder="输入百度账号的stoken值" /></label></p>
<p>stoken是百度近来投入使用的另一个认证cookie，在浏览器中查看bduss时可以看到，虽然暂时还用不上……</p>
<p>**注意：这些测试性功能的效果都不被保证，并且可能在以后的版本中被保留或放弃，请勿过于依赖。**</p>
<p><input type="submit" value="保存设置" /></p>
</form>
<?php HOOK::run('user_setting'); ?>
<br>
<p>签到测试：</p>
<p>随机选取一个贴吧，进行一次签到测试，检查你的设置有没有问题</p>
<p><a href="index.php?action=test_sign&formhash=<?php echo $formhash; ?>" class="btn" onclick="return msg_redirect_action(this.href)">测试签到</a></p>