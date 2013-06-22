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
	private $grantType='authorization_code';  //支持的授权类型

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
	 * 生成获得access_token的地址
	 */
	public function getTokenUrl($code=''){
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
	public function getToken($tokenUrl=''){
		if(!$tokenUrl){
			return false;
		}
		$response = @file_get_contents($tokenUrl);
		$params = json_decode($response);
		return $params;
	}
}