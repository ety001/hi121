<?php
class main extends spController
{
	function index(){
		global $apiConfig;
		session_start();
		$_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection

		echo '<div><a href="https://graph.renren.com/oauth/authorize?client_id='.$apiConfig['renren']['ClientID'].'&redirect_uri='.urlencode($apiConfig['renren']['RedirectURI']).'&response_type=code&scope=read_user_feed&state='.$_SESSION['state'].'" target="_blank">人人网</a></div>';
	}

	function accesstoken(){
		$code = $_GET["code"];
		if($code && $_REQUEST['state'] == $_SESSION['state']) {
			import('renren/RenrenRestApiService.class.php');
			global $apiConfig;
			$grant_type="authorization_code";  //支持的授权类型
			$rrObj = spClass('RenrenRestApiService');
			$token_url = "https://graph.renren.com/oauth/token?"
				. "client_id=" . $apiConfig['renren']['ClientID'] . "&redirect_uri=" . urlencode($apiConfig['renren']['RedirectURI'])
				. "&client_secret=" . $apiConfig['renren']['SecretKey'] ."&grant_type=" . $grant_type . "&code=" . $code;
     		$response = @file_get_contents($token_url);
     		$params = null;
     		$params = json_decode($response);
     		dump($params);
     		echo '<br />';
			echo("Hello " . $params->user->name);
			echo '<img src="'.$params->user->avatar[1]->url.'" />';
		} else {
			echo 'error CSRF';
		}
	}
}