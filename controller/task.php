<?php
class task extends spController
{
	function index(){
		global $apiConfig;
		$renrenConnectModel = spClass('m_renrenConnect');
		$arr = $renrenConnectModel->find();
		var_dump($arr['access_token']);

		import('include/renren/RenrenRestApiService.class.php');
		$rrConct = spClass('RenrenRestApiService');
		$apiCommitInfo = array(
			'v' => $apiConfig['renren']['APIVersion'],
			'access_token' => $arr['access_token'],
			'page' => 1,
			'count' => 1
		);
		$result = $rrConct->rr_post_curl('status.gets',$apiCommitInfo);
		var_dump($result);
	}
}