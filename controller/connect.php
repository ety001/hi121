<?php
class connect extends spController
{
	function index(){
		/*TODO 把权限管理用speedphp自带的spacl重写*/
		if($_SESSION['uid']==''){
			$this->error('还未登录', spUrl('connect', 'login'));
			return;
		} else {
			$uid = $_SESSION['uid'];
		}

		$userModel = spClass('m_user');
     	$userInfo = $userModel->find('id="'.$uid.'"');
     	var_dump($userInfo);
     	if($userInfo['renren_id'] == ''){
     		import('include/renren/renren.class.php');
     		$apiClass = spClass('renren');
     		$renrenHtml = $apiClass->createConnectBtn();
     		$renrenHtml = '<div>'.$renrenHtml.'</div>';
     	} else {
     		$renrenHtml = '<div>已经绑定了人人网帐号</div>';
     	}
     	$btnHtml = $renrenHtml;
     	$this->btn_html = $btnHtml;
     	$this->display('connect/index.html');
	}

	function login(){
		import('include/renren/renren.class.php');
     	$apiClass = spClass('renren');
     	$renrenHtml = $apiClass->createConnectBtn();
     	$renrenHtml = '<div>'.$renrenHtml.'</div>';
     	$btnHtml = $renrenHtml;
     	$this->btn_html = $btnHtml;
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
		if($_REQUEST['state'] == $_SESSION['state']) { //CSRF protection
			//get the access token
			import('include/'.$apiType.'/'.$apiType.'.class.php');
			$apiClass = spClass($apiType);
     		$params = $apiClass->getToken($code);
     		//connect the database to check whether the user exists 
     		$userModel = spClass('m_user');
     		$userInfo = $userModel->findAll($apiType.'_id="'.$params->user->id.'"');
     		if($userInfo[0]['id']){
     			$_SESSION['uid'] = $userInfo[0]['id'];//TODO:是用spAcl重做
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