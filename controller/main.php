<?php
class main extends spController
{
	function index(){
		$this->display('main/index.html');
	}

	function login(){
		global $apiConfig;
		$_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
		$this->info = array(
			'state' => $_SESSION['state'],
			'client_id' => $apiConfig['renren']['ClientID'],
			'redirect_uri' => $apiConfig['renren']['RedirectURI']
		);
		$this->display('main/login.html');
	}

	function regAccess(){
		$code = $_GET["code"];
		if(!$code){
			$this->error('本站只接受第三方帐号注册和登录', spUrl('main', 'index'));
			return;
		}
		if($_REQUEST['state'] == $_SESSION['state']) { //CSRF protection
			global $apiConfig;
			$grant_type="authorization_code";  //支持的授权类型
			$token_url = "https://graph.renren.com/oauth/token?"
				. "client_id=" . $apiConfig['renren']['ClientID'] . "&redirect_uri=" . urlencode($apiConfig['renren']['RedirectURI'])
				. "&client_secret=" . $apiConfig['renren']['SecretKey'] ."&grant_type=" . $grant_type . "&code=" . $code;
     		$response = @file_get_contents($token_url);
     		$params = null;
     		$params = json_decode($response);
     		$userModel = spClass('m_user');
     		$userInfo = $userModel->findAll('renren_id="'.$params->user->id.'"');
     		if($userInfo[0]['id']){
     			$this->success('登录成功', spUrl('main', 'index'));
     			return;
     		} else {
     			$_SESSION[$code] = $params;
     			$this->renren_name = $params->user->name;
     			$this->renren_code = $code;
     			$this->csrf = $_SESSION['state'];
     			$this->display('main/regaccess.html');
     		}
		} else {
			$this->error('请不要尝试站外提交数据', spUrl('main', 'index'));
			return;
		}
	}

	function regSave(){
		if($_REQUEST['state'] == $_SESSION['state']){
			$code = $_POST['renren_code'];
			if(!$_SESSION[$code]){
				$this->error('错误的renren_code值', spUrl('main', 'index'));
				return;
			} else {
				$renrenInfo = $_SESSION[$code];
			}
			$nickname = $_POST['nickname'];
			$renrenID = $renrenInfo->user->id;
			$userModel = spClass('m_user');
			if($userModel->findAll('nickname="'.$nickname.'"')){
				$this->error('nickname已经存在了', spUrl('main','login'));
				return;
			}
			$userInfo = array(
				'nickname' => $nickname,
				'renren_id' => $renrenID
			);
			$uid = $userModel->create($userInfo);
			if($uid){
				$renrenConnectModel = spClass('m_renrenConnect');
				$expiresTime = time() + $renrenInfo->expires_in; 
				$renrenInfo = array(
					'uid' => $uid,
					'access_token' => $renrenInfo->access_token,
					'refresh_token' => $renrenInfo->refresh_token,
					'expires_time' => $expiresTime
				);
				$renrenConnectModel->create($renrenInfo);
				$this->success('注册成功',spUrl('task', 'index'));
				return;
			}
		} else {
			$this->error('请不要尝试站外提交数据', spUrl('main', 'index'));
			return;
		}

	}
}