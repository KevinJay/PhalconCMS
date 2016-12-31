PhalconCMS
=================
### 介绍
基于Phalcon的博客CMS

### 推荐环境
* PHP 5.6+
* mysql 5.6+
* phalcon 3.0+

### 安装步骤
* 在数据库中导入phalconCMS.sql文件
* 添加nginx配置，demo:
```bash
	server {
	    listen 80;
	    server_name test.com;
	    root /path/PhalconCMS/public;
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
	
	    access_log  /path/logs/PhalconCMS/access.log  access;
	    error_log  /path/logs/PhalconCMS/error.log;
	}
```
* 修改app/cache目录权限：chmod -R 0777 app/cache
* 在public/index.php中修改"$runtime"值（dev:开发   test:测试    pro:线上）。程序会根据此变量，匹配不同运行环境所需的配置（app/config/api/, app/config/system/）文件
* 后台(http://www.xxx.com/admin/index/index) 登录账号密码：admin  123456
* 在后台的“站点管理-基本设置”中修改“站点地址”、“CDN地址”等

#### 作者
[www.marser.cn][2] (http://www.marser.cn)

#### QQ群
* 广州PHP高端交流群：158587573  <a target="_blank" href="https://shang.qq.com/wpa/qunwpa?idkey=76053c37f853158ffbf505de6556c9dcaaf1b4703ffb538237d441a0d884f03a"><img border="0" src="https://pub.idqqimg.com/wpa/images/group.png" alt="广州PHP高端交流" title="广州PHP高端交流"></a>
* Phalcon玩家群：150237524  <a target="_blank" href="https://shang.qq.com/wpa/qunwpa?idkey=aba021d46bc828231de2464e84a69619696887fe9515512e0ceb8d1bda053826"><img border="0" src="https://pub.idqqimg.com/wpa/images/group.png" alt="Phalcon玩家" title="Phalcon玩家"></a>


[1]:	http://www.iphalcon.cn
[2]:	http://www.marser.cn
