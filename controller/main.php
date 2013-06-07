<?php
class main extends spController
{
	function index(){
		echo "Enjoy, Speed of PHP!";
		import('renren/RenrenRestApiService.class.php');
		$rrObj = spClass('RenrenRestApiService');
		$sessionkey='6.c15fbc6fd142dddce6bd98a4d5524286.2592000.1327053600-228487955';//改成测试用户的
		$accesstoken='99273|6.c15fbc6fd142dddce6bd98a4d5524286.2592000.1327053600-228487955';//改成测试用户的

		//$rrObj->setEncode("GB2312");//如果是utf-8的环境可以不用设，如果当前环境不是utf8编码需要在这里设定


		/*@POST暂时有两个参数，第一个是需要调用的方法，具体的方法跟人人网的API一致，注意区分大小写
		 *@第二个参数是一维数组，除了api_key,method,v,format,callid之外的其他参数/

		/*测试1：获取指定用户的信息
		 */
		$params = array('uids'=>'346132863,741966903','fields'=>'uid,name,sex,birthday,mainurl,hometown_location,tinyurl,headurl,mainurl','session_key'=>$sessionkey);//使用session_key调api的情况
		$res = $rrObj->rr_post_curl('users.getInfo', $params);//curl函数发送请求
		//$res = $rrObj->rr_post_fopen('users.getInfo', $params);//如果你的环境无法支持curl函数，可以用基于fopen函数的该函数发送请求
		print_r($res);//输出结果
		//echo '<br>'.$res[0]->name;
		echo '<br><hr><br>';
	}
}