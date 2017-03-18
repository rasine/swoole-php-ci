<?php

/*
 *作者：邹慧刚
  联系方式：952750120@qq.com
  作者网站：www.anooc.com
  使用swoole之前，需要先按照swoole扩展
  docker pull registry.cn-hangzhou.aliyuncs.com/zhg_docker_ali_r/php7_swoole:1.0.0
 * 
 * */

class Swoole extends MY_Controller{

	public function __construct(){
		parent::__construct();
		    $this->load->model('swoole/client', '', TRUE);
			$this->load->model('commonModel', '', TRUE);

	}

	public function index(){
			
		for($i=0;$i<1000;$i++){
			$url="http://qa.51tywy.com:30012/swoole/test";
			$paramArr=array(  
				"username" => '2adasd',  
				"password" => 'test2332'  
				);
			$this->client->gorun($url,$paramArr);
		}

	}

	//http://www.51joinup.com/swoole/test
	public function test(){

		$test_info = $this->commonModel->getInfo("test", "id=1", "*");
		$username=$this->input->get_post("username");
			$arr= array();
			$arr['ctime'] =time();
			$arr['num'] =$test_info["num"]+1;
			$arr['username'] =$username;
			$rs=$this->commonModel->getUpdate('test',array("id"=>1), $arr);
			echo $this->db->last_query();
			echo "执行成功";
	}




	//class end

}
?>
