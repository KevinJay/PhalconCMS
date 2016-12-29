<?php

/**
 * 账户
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Backend\Controllers;

use \Marser\App\Backend\Controllers\BaseController;

class AccountController extends BaseController{

    public function initialize(){
        parent::initialize();
    }

    /**
     * 个人设置页
     */
    public function profileAction(){
        try {
            $username = $this->session->get('user')['username'];
            $user = $this->get_repository('Users')->detail($username);

            $this -> view -> setVars(array(
                'user' => $user,
            ));
            $this -> view -> pick('account/profile');
        }catch(\Exception $e){
            $this -> write_exception_log($e);

            $this -> flashSession -> error($e -> getMessage());

            return $this -> redirect('dashboard/index');
        }
    }

    /**
     * 更新个人设置
     */
    public function saveprofileAction(){
        try{
            if($this -> request -> isAjax() || !$this -> request -> isPost()){
                throw new \Exception('非法请求');
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
            /** 变更个人配置 */
            $data = array(
                'nickname' => $nickname,
                'email' => $email,
            );
            $this -> get_repository('Users') -> update($data, $this -> session -> get('user')['uid']);

            $this -> flashSession -> success('更新成功');
        }catch(\Exception $e){
            $this -> write_exception_log($e);

            $this -> flashSession -> error($e -> getMessage());
        }
        return $this -> redirect('account/profile');
    }

    /**
     * 修改密码
     */
    public function savepwdAction(){
        try{
            if($this -> request -> isAjax() || !$this -> request -> isPost()){
                throw new \Exception('非法请求');
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
            /** 重置密码 */
            $this -> get_repository('Users') -> update_password($oldpwd, $newpwd);

            $this -> flashSession -> success('修改密码成功，下次登录时将启动新密码');
        }catch(\Exception $e){
            $this -> write_exception_log($e);

            $this -> flashSession -> error($e -> getMessage());
        }
        return $this -> redirect('account/profile');
    }

}