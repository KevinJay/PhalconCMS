<?php

/**
 * 账户
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Backend\Controllers;

use \Marser\App\Backend\Controllers\BaseController,
    \Marser\App\Backend\Models\UsersModel;

class AccountController extends BaseController{

    /**
     * 个人设置页
     */
    public function profileAction(){
        $this -> view -> pick('settings/profile');
    }

    /**
     * 更新个人设置
     */
    public function setprofileAction(){
        try{
            if(!$this -> request -> isPost()){
                throw new \Exception('请求错误');
            }
            $nickname = $this -> request -> getPost('nickname', 'trim');
            $email = $this -> request -> getPost('email', 'trim');
            empty($nickname) && $nickname = $this -> session -> get('user')['nickname'];
            /** 添加验证规则 */
            $this -> validator -> add_rule('nickname', 'chinese_alpha_numeric_dash', '昵称由2-20个中英文字符、数字、中下划线组成')
                -> add_rule('nickname', 'min_length', '呢称由2-20个中英文字符、数字、中下划线组成', 2)
                -> add_rule('nickname', 'max_length', '昵称由2-20个中英文字符、数字、中下划线组成', 20);
            $this -> validator -> add_rule('email', 'required', '请填写电子邮箱地址')
                -> add_rule('email', 'email', '请填写正确的邮箱地址');
            /** 截获验证异常 */
            if ($error = $this -> validator -> run(array(
                'nickname'=>$nickname,
                'email'=>$email
            ))) {
                $error = array_values($error);
                $error = $error[0];
                throw new \Exception($error['message'], $error['code']);
            }
            /** 更新个人设置数据 */
            $data = array(
                'nickname' => $nickname,
                'email' => $email,
            );
            $usersModel = new UsersModel();
            $affectedRows = $usersModel -> update_user($data, $this -> session -> get('user')['username']);
            if(!$affectedRows){
                throw new \Exception('修改个人设置失败');
            }
            $this -> ajax_return('更新成功');
        }catch(\Exception $e){
            $this -> write_exception_log($e);

            $code = !empty($e -> getCode()) ? $e -> getCode() : 500;
            $this -> ajax_return($e -> getMessage(), $code);
        }
        $this -> view -> disable();
    }

    /**
     * 修改密码
     */
    public function setpwdAction(){
        try{
            if(!$this -> request -> isPost()){
                throw new \Exception('请求错误');
            }
            $oldpwd = $this -> request -> getPost('oldpwd', 'trim');
            $newpwd = $this -> request -> getPost('newpwd', 'trim');
            $confirmpwd = $this -> request -> getPost('confirmpwd', 'trim');
            /** 添加校验规则 */
            $this -> validator -> add_rule('oldpwd', 'required', '请填写原始密码')
                -> add_rule('oldpwd', 'not_equals', '新密码不能与旧密码相同', $newpwd)
                -> add_rule('oldpwd', 'min_length', '密码由6-20个字符组成', 6)
                -> add_rule('oldpwd', 'max_length', '密码由6-20个字符组成', 20);
            $this -> validator -> add_rule('newpwd', 'required', '请填写新密码')
                -> add_rule('newpwd', 'min_length', '密码由6-20个字符组成', 6)
                -> add_rule('newpwd', 'max_length', '密码由6-20个字符组成', 20)
                -> add_rule('newpwd', 'equals', '两次密码输入不一致', $confirmpwd);
            /** 截获验证异常 */
            if ($error = $this -> validator -> run(array(
                'oldpwd'=>$oldpwd,
                'newpwd'=>$newpwd
            ))) {
                $error = array_values($error);
                $error = $error[0];
                throw new \Exception($error['message'], $error['code']);
            }
            /** 校验旧密码是否正确 */
            $usersModel = new UsersModel();
            $user = $usersModel -> user_detail($this -> session -> get('user')['username']);
            if(!$user){
                throw new \Exception('密码错误');
            }
            $userinfo = $user -> toArray();
            if(!$this -> security -> checkHash($oldpwd, $userinfo['password'])){
                throw new \Exception('密码错误，请重新输入');
            }
            /** 密码更新 */
            $password = $this -> security -> hash($newpwd);
            $affectedRows = $usersModel -> update_user(array(
                'password' => $password,
            ), $this -> session -> get('user')['username']);
            if(!$affectedRows){
                throw new \Exception('修改密码失败，请重试');
            }
            $this -> ajax_return('修改密码成功');
        }catch(\Exception $e){
            $this -> write_exception_log($e);

            $code = !empty($e -> getCode()) ? $e -> getCode() : 500;
            $this -> ajax_return($e -> getMessage(), $code);
        }
        $this -> view -> disable();
    }

}

