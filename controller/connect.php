<?php
class connect extends spController
{
	function index(){
		if($_SESSION['uid']!== ''){
			$this->error('还未登录', spUrl('connect', 'index'));
			return;
		}
		$_SESSION['csrf'] = md5(uniqid(rand(), TRUE)); //CSRF protection
		$userModel = spClass('m_user');
     	$userInfo = $userModel->find('uid="'.$uid.'"');
     	$userInfo['csrf'] = $_SESSION['csrf'];
     	$this->user_info = $userInfo;
     	$this->display('connect/index.html');
	}

	function login(){
		global $apiConfig;
		$_SESSION['csrf'] = md5(uniqid(rand(), TRUE)); //CSRF protection
		$this->info = array(
			'state' => $_SESSION['csrf'],
			'client_id' => $apiConfig['renren']['ClientID'],
			'redirect_uri' => $apiConfig['renren']['RedirectURI']
		);
		$this->display('connect/login.html');
	}

	function regAccess(){
		$code = $_GET["code"];
		if(!$code){
			$this->error('本站只接受第三方帐号注册和登录', spUrl('connect', 'index'));
			return;
		}
		$apiType = $_GET['api_type']?$_GET['api_type']:0;
		if(!$apiType){
			$this->error('error api type', spUrl('connect', 'index'));
			return;
		}
		if($_REQUEST['csrf'] == $_SESSION['csrf']) { //CSRF protection
			//get the access token
			import('include/'.$apiType.'/'.$apiType.'.class.php');
			$apiClass = spClass($apiType);
     		$params = $apiClass->getToken($code);
     		//connect the database to check whether the user exists 
     		$userModel = spClass('m_user');
     		$userInfo = $userModel->findAll($apiType.'_id="'.$params->user->id.'"');
     		if($userInfo[0]['id']){
     			$_SESSION['uid'] = $userInfo[0]['id'];
     			$this->success('登录成功', spUrl('connect', 'index'));
     			return;
     		} else {
     			$_SESSION[$code] = $params;
     			$this->renren_name = $params->user->name;
     			$this->renren_code = $code;
     			$this->csrf = $_SESSION['state'];
     			$this->display('connect/regaccess.html');
     		}
		} else {
			$this->error('请不要尝试站外提交数据', spUrl('connect', 'index'));
			return;
		}
	}

	function regSave(){
		if($_REQUEST['state'] == $_SESSION['state']){
			$code = $_POST['renren_code'];
			if(!$_SESSION[$code]){
				$this->error('错误的renren_code值', spUrl('connect', 'index'));
				return;
			} else {
				$renrenInfo = $_SESSION[$code];
			}
			$nickname = $_POST['nickname'];
			$renrenID = $renrenInfo->user->id;
			$userModel = spClass('m_user');
			if($userModel->findAll('nickname="'.$nickname.'"')){
				$this->error('nickname已经存在了', spUrl('connect','login'));
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
			$this->error('请不要尝试站外提交数据', spUrl('connect', 'index'));
			return;
		}

	}
}