<?php
/*
 * 人人网相关功能封装
 *
 * @Created: 17:09:40 2013/6/22
 * @Author:	ETY001 <ety002@gmail.com>
 * @Blog:	http://www.domyself.me
 */

require_once 'RenrenRestApiService.class.php';
class renren{
	const APITYPE = 'renren';

	private $clientId;	//client id
	private $apiKey;	//key
	private $apiSecret;	//secret
	private $apiUrl;	//api url address
	private $apiVersion;	//api version
	private $decodeFormat;	//decode format
	private $redirectUrl;	//redirect url
	private $baseTokenUrl = 'https://graph.renren.com/oauth/token?';	//base token url
	private $oauthUrl = 'https://graph.renren.com/oauth/authorize?';	//oauth url
	private $grantType = 'authorization_code';  //支持的授权类型
	private $responseType = 'code';
	private $scope = 'read_user_feed admin_page publish_feed read_user_status';

	function __construct(){
		global $apiConfig;
		$this->clientId = $apiConfig[self::APITYPE]['ClientID'];
		$this->apiKey = $apiConfig[self::APITYPE]['APIKey'];
		$this->apiSecret = $apiConfig[self::APITYPE]['SecretKey'];
		$this->apiUrl = $apiConfig[self::APITYPE]['APIURL'];
		$this->redirectUrl = $apiConfig[self::APITYPE]['RedirectURI'];
		$this->apiVersion = $apiConfig[self::APITYPE]['apiVersion'];
		$this->decodeFormat = $apiConfig[self::APITYPE]['decodeFormat'];
	}

	/*
	 * 生成连接平台的按钮
	 */
	public function createConnectBtn(){
		$btnHtml = '<a href="'.$this->oauthUrl
					.'client_id='.$this->clientId
					.'&redirect_uri='.$this->redirectUrl
					.'&response_type='.$this->responseType
					.'&scope='.$this->scope
					.'&csrf='.$_SESSION['csrf']
					.'" target="_blank"><img src="/public/images/renren_dark.png" /></a>';
		return $btnHtml;
	}

	/*
	 * 生成获得access_token的地址
	 */
	private function getTokenUrl($code=''){
		if(!$code){
			return false;
		}
		$tokenUrl = $this->baseTokenUrl
					.'client_id='.$this->clientId
					.'&client_secret='.$this->apiSecret
					.'&grant_type='.$this->grantType
					.'&redirect_uri='.urlencode($this->redirectUrl)
					.'&code='.$code;
		return $tokenUrl;
	}

	/*
	 * 获得access_token
	 */
	public function getToken($code=''){
		if(!$code){
			return false;
		}
		$tokenUrl = $this->getTokenUrl($code);
		$response = @file_get_contents($tokenUrl);
		$params = json_decode($response);
		return $params;
	}

	/*
	 * 获得人人网状态
	 */
	public function getStates($userInfo=array(), $page=1, $count=1 ){
		if(!$userInfo['uid']){
			return false;
		} else {
			$uid = $userInfo['uid'];
		}
		$renrenConnectModel = spClass('m_renrenConnect');
		$res = $renrenConnectModel->find('uid="'.$uid.'"');
		$accessToken = $res['access_token'];

		$rrConct = spClass('RenrenRestApiService');
		$apiCommitInfo = array(
			'v' => $this->apiVersion,
			'access_token' => $accessToken,
			'page' => $page,
			'count' => $count
		);
		$result = $rrConct->rr_post_curl('status.gets',$apiCommitInfo);
		return $result;
	}
}