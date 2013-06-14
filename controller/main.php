<?php
class main extends spController
{
	function index(){
		global $apiConfig;
		$_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
		$this->info = array(
			'state' => $_SESSION['state'],
			'client_id' => $apiConfig['renren']['ClientID'],
			'redirect_uri' => $apiConfig['renren']['RedirectURI']
		);
		$this->display('main/index.html');
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
     		$params = null;
     		$params = json_decode($response);
     		$_SESSION[$code] = $params;
     		$this->renren_name = $params->user->name;
     		$this->renren_code = $code;
     		$this->display('main/regaccess.html');
			
		} else {
			$this->error('请不要尝试站外提交数据', spUrl('main', 'index'));
		}
	}

	function regSave(){

	}
}