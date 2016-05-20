PhalconCMS
=================
### 介绍
基于Phalcon的博客CMS


#### 环境匹配设置
* 在不同环境（开发、测试、线上）上运行此项目时，请修改index.php中的``` "$runtime" ```值：
``` dev:开发   test:测试    pro:线上 ```程序会根据此变量，自动匹配环境所需的配置（api,system）文件

#### nginx配置
```bash
	server {
	    listen 80;
	    server_name test.com;
	    root /path/Marser/public;
	    index index.php index.html index.htm;
	
	    location / {
	        if ($request_uri ~ (.+?\.php)(|/.+)$ ) {
	            break;
	        }
	
	        if (!-e $request_filename) {
	            rewrite ^/(.*)$ /index.php?_url=/$1;
	        }
	    }
	
	    location ~ \.php {
	        fastcgi_pass  unix:/tmp/php-cgi.sock;
	        fastcgi_index index.php;
	        include fastcgi_params;
	        set $real_script_name $fastcgi_script_name;
	        if ($fastcgi_script_name ~ "^(.+?\.php)(/.+)$") {
	            set $real_script_name $1;
	            set $path_info $2;
	        }
	        fastcgi_param SCRIPT_FILENAME $document_root$real_script_name;
	        fastcgi_param SCRIPT_NAME $real_script_name;
	        fastcgi_param PATH_INFO $path_info;
	    }
	
	    access_log  /path/logs/Marser/access.log  access;
	    error_log  /path/logs/Marser/error.log;
	}
```

#### 社区
[Phalcon中文社区][1] (http://www.iphalcon.cn)

#### 作者
[www.marser.cn][2] (http://www.marser.cn)

[1]:	http://www.iphalcon.cn
[2]:	http://www.marser.cn
