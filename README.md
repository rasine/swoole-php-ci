### centos安装pecl


#### Ubuntu/Debian上是这样(php-pear包含pecl,php5-dev包含phpize,pecl依赖phpize)
apt-get install php-pear php5-dev
pecl install swoole

#### CentOS/Redhat上应该是这样
yum install php-pear php-devel
pecl install swoole

#### 如果是自行编译的PHP(假设安装目录为/opt/php/7.0)
/opt/php/7.0/bin/phpize
/opt/php/7.0/bin/pecl


### 查看

php -m

 	利用php -m 可以看到swoole已经成功


	查看版本：php --version



### php.ini

	如果用yum安装的php，默认在/etc/php.ini（源码编译一般也是这个路径）


添加：

	extension=swoole.so



	yum -y install httpd php
	
	
	systemctl  start httpd



### 测试swoole:

cd /var/www/html/：


c1.php:

	<?php
	$client = new swoole_client(SWOOLE_SOCK_TCP);
	if (!$client->connect('0.0.0.0', 9501, -1))
	{
	    exit("connect failed. Error: {$client->errCode}\n");
	}
	$client->send("hello world\n");
	echo $client->recv();
	$client->close();
	?>

浏览器打开：http://qa.51tywy.com/c1.php
	
	提示：Swoole: hello world


s1.php:

	<?php
	//$serv = new swoole_server("123.57.232.99", 55152);

	$serv = new swoole_server("0.0.0.0", 9501);

	$serv->on('connect', function ($serv, $fd){
	    echo "Client:Connect.\n";
	});
	$serv->on('receive', function ($serv, $fd, $from_id, $data) {
	    $serv->send($fd, 'Swoole: '.$data);
	});
	$serv->on('close', function ($serv, $fd) {
	    echo "Client: Close.\n";
	});
	$serv->start();
	?>

测试：

	[root@k8s-master html]# 
	[root@k8s-master html]# php s1.php
	Client:Connect.
	Client:Connect.
	Client: Close.

telnet测试：

	[root@k8s-master ~]# telnet 127.0.0.1 9501
	Trying 127.0.0.1...
	Connected to 127.0.0.1.
	Escape character is '^]'.
	rrr
	Swoole: rrr
	rrr
	Swoole: rrr
	Connection closed by foreign host.
	[root@k8s-master ~]# 



### 进程的打开或关闭

	ps -aux | grep php

	kill -9 进程id


docker构建：

dockefile:

	FROM registry.cn-hangzhou.aliyuncs.com/zhg_docker_ali_r/php:51tywy
	
	RUN pecl install swoole
	RUN docker-php-ext-enable swoole

注：swoole是直接加载在php核心中的扩展，不需要web服务器组件支撑，它本身就提供搜索独立的http服务

运行：

	docker build -t  registry.cn-hangzhou.aliyuncs.com/zhg_docker_ali_r/php:swoole .

	docker run -it -p 8033:80 registry.cn-hangzhou.aliyuncs.com/zhg_docker_ali_r/php:swoole


提交阿里云:

	docker tag registry.cn-hangzhou.aliyuncs.com/zhg_docker_ali_r/php:swoole registry.cn-hangzhou.aliyuncs.com/zhg_docker_ali_r/php7_swoole:1.0.0

	docker push registry.cn-hangzhou.aliyuncs.com/zhg_docker_ali_r/php7_swoole:1.0.0


查看php版本：

	http://qa.51tywy.com:30012/test.php

启动service：

	qa.51tywy.com:30012/swoole_server.php

任务地址：

	http://qa.51tywy.com:30012/swoole/test?username=%22dd%22

查看swoole：

	http://qa.51tywy.com:30012/swoole/index
	qa.51tywy.com:30012/swoole/test

参考文档：

[http://blog.csdn.net/nuli888/article/details/51849699](http://blog.csdn.net/nuli888/article/details/51849699)

[https://yq.aliyun.com/articles/44247](https://yq.aliyun.com/articles/44247)

[https://hub.docker.com/r/xlight/docker-php7-swoole/~/dockerfile/](https://hub.docker.com/r/xlight/docker-php7-swoole/~/dockerfile/)