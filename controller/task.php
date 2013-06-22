<?php
class task extends spController
{
	function index(){
		$apiType = 'renren';
		import('include/'.$apiType.'/'.$apiType.'.class.php');
		$$apiType = spClass($apiType);
		$result = $$apiType->getStates(array('uid'=>3));
		var_dump($result);
	}
}