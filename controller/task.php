<?php
class task extends spController
{
	function index(){
		$renrenConnectModel = spClass('m_renrenConnect');
		$arr = $renrenConnectModel->find();
		var_dump($arr['access_token']);

		import('include/renren/RenrenRestApiService.class.php');
		$rrConct = spClass('RenrenRestApiService');
		$rrConct->rr_post_curl();
		var_dump($rrConct);
	}
}