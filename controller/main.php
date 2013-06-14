<?php
class main extends spController
{
	function index(){
		global $apiConfig;
		$_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection

		echo '<div><a href="https://graph.renren.com/oauth/authorize?client_id='
			.$apiConfig['renren']['ClientID']
			.'&redirect_uri='.urlencode($apiConfig['renren']['RedirectURI'])
			.'&response_type=code&scope=read_user_feed admin_page publish_feed&state='
			.$_SESSION['state'].'" target="_blank">人人网</a></div>';
	}

	function regAccess(){
		$code = $_GET["code"];
		if(!$code){
			$this->error('本站只接受第三方帐号注册和登录', spUrl('main', 'index'));
		}
		if($_REQUEST['state'] == $_SESSION['state']) {
			global $apiConfig;
			$grant_type="authorization_code";  //支持的授权类型
			$token_url = "https://graph.renren.com/oauth/token?"
				. "client_id=" . $apiConfig['renren']['ClientID'] . "&redirect_uri=" . urlencode($apiConfig['renren']['RedirectURI'])
				. "&client_secret=" . $apiConfig['renren']['SecretKey'] ."&grant_type=" . $grant_type . "&code=" . $code;
     		$response = @file_get_contents($token_url);
     		//$response = '{"scope":"photo_upload read_user_feed publish_feed","token_type":"bearer","expires_in":2592321,"refresh_token":"97468|0.qhuYq5MmhoXw4qTUGjgM3zlZafEVlag8.249896109.1371187869681","user":{"id":249896109,"name":"于业超ETY001","avatar":[{"type":"avatar","url":"http://hdn.xnimg.cn/photos/hdn421/20130407/2130/head_zd68_ec830001831e113e.jpg"},{"type":"tiny","url":"http://hdn.xnimg.cn/photos/hdn221/20130407/2130/tiny_BxhN_f98b00018384111a.jpg"},{"type":"main","url":"http://hdn.xnimg.cn/photos/hdn421/20130407/2130/main_zd68_ec830001831e113e.jpg"},{"type":"large","url":"http://hdn.xnimg.cn/photos/hdn421/20130407/2130/original_zd68_ec830001831e113e.jpg"}]},"access_token":"97468|6.d962626b1233598d0890c9e9708aebfa.2592000.1373824800-249896109"}';
     		$params = null;
     		$params = json_decode($response);
     		$_SESSION[$code] = $params;
     		$this->renren_code = $code;
     		$this->display('main/regaccess.html');
			
		} else {
			$this->error('请不要尝试站外提交数据', spUrl('main', 'index'));
		}
	}

	function regSave(){

	}
}