<?php

/**
 * 个人设置控制器
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Backend\Controllers;

use \Marser\App\Backend\Controllers\BaseController,
    \Marser\App\Backend\Models\UsersModel;

class SettingsController extends BaseController{

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
            $usersModel -> update_user($data, $this -> session -> get('user')['username']);
            $this -> ajax_return('更新成功');
        }catch(\Exception $e){
            $this -> write_exception_log($e);

            $code = !empty($e -> getCode()) ? $e -> getCode() : 500;
            $this -> ajax_return($e -> getMessage(), $code);
        }
        $this -> view -> disable();
    }


}

