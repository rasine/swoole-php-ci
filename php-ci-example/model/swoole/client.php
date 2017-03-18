<?php

/*
 *作者：邹慧刚
  联系方式：952750120@qq.com
  作者网站：www.anooc.com

  $data = array(  
    "url" => "http://192.168.10.19/send_mail",  
    "param" => array(  
        "username" => 'test',  
        "password" => 'test'  
    )  
);  
$client = new Client();  
$client->connect();  
if ($client->send($data)) {  
    echo 'success';  
} else {  
    echo 'fail';  
}  
$client->close();  

 * 
 * */

class Client extends CI_Model{
    private $client;  
	public function __construct(){
		parent::__construct();
		$this->client = new swoole_client(SWOOLE_SOCK_TCP);  
		$this->swoole_server_ip="139.196.48.36";
		$this->swoole_server_port=9501;

	}
 
 
  
    public function connect(){  
        if (!$this->client->connect($this->swoole_server_ip, $this->swoole_server_port, 1)) {  
            throw new Exception(sprintf('Swoole Error: %s', $this->client->errCode));  
        }  
    }  
  
    public function send($data){  
        if ($this->client->isConnected()) {  
            if (!is_string($data)) {  
                $data = json_encode($data);  
            }  
  			
            return $this->client->send($data);  
        } else {  
            throw new Exception('Swoole Server does not connected.');  
        }  
    }  
  
    public function close()  
    {  
        $this->client->close();  
    }  



	//$url请求的网址，paramArr请求的参数
	public function gorun($url,$paramArr){
		$client = new Client();  
		$client->connect();
		$data=array();
		$data["url"]=$url;
		$data["param"]=$paramArr;
	 
		if ($client->send($data)) {  
		    //echo $i.'请求发送成功success'.time()."</br>";  
		} else {  
		    //echo '请求发送失败fail'.time()."</br>";  
		} 
		$client->close();
		
	}


	//class end

}
?>
