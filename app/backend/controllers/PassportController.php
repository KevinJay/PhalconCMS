<?php

/**
 * 登录控制器
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Backend\Controllers;
use \Marser\App\Backend\Controllers\BaseController,
    \Marser\App\Backend\Models\UsersModel;

class PassportController extends BaseController{

    /**
     * 登录页
     */
    public function indexAction(){
        $this -> view -> pick();
    }

    /**
     * 登录处理
     * @throws \Exception
     * @author Marser
     */
    public function loginAction(){
        try {
            if(!$this -> request -> isPost()){
                throw new \Exception('请求错误');
            }
            $username = $this -> request -> getPost('username', 'trim');
            $password = $this -> request -> getPost('password', 'trim');

            /** 添加验证规则 */
            $this -> validator -> add_rule('username', 'required', '请输入用户名')
                -> add_rule('username', 'alpha_dash', '用户名由4-20个英文字符、数字、中下划线组成')
                -> add_rule('username', 'min_length', '用户名由4-20个英文字符、数字、中下划线组成', 4)
                -> add_rule('username', 'max_length', '用户名由4-20个英文字符、数字、中下划线组成', 20);
            $this -> validator -> add_rule('password', 'required', '请输入密码')
                -> add_rule('password', 'min_length', '密码由6-20个字符组成', 6)
                -> add_rule('password', 'max_length', '密码由6-20个字符组成', 20);
            /** 截获验证异常 */
            if ($error = $this -> validator -> run(array('username'=>$username, 'password'=>$password))) {
                $error = array_values($error);
                $error = $error[0];
                throw new \Exception($error['message'], $error['code']);
            }
            /** 获取用户信息 */
            $usersModel = new UsersModel();
            $user = $usersModel -> user_detail($username);
            if(!$user){
                throw new \Exception('用户名或密码错误');
            }
            $userinfo = $user -> toArray();
            /** 校验密码 */
            if(!$this -> security -> checkHash($password, $userinfo['password'])){
                throw new \Exception('密码错误，请重新输入');
            }
            /** 设置session */
            unset($userinfo['password']);
            $this -> session -> set('user', $userinfo);

            $this -> ajax_return('success', 1);
        }catch(\Exception $e){
            $this -> write_exception_log($e);

            $code = !empty($e -> getCode()) ? $e -> getCode() : 500;
            $this -> ajax_return($e -> getMessage(), $code);
        }
        $this -> view -> disable();
    }

    /**
     * 注销登录
     * @author Marser
     */
    public function logoutAction(){
        $this -> session -> destroy();
    }
}