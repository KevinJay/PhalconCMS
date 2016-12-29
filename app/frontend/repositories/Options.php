<?php

/**
 * 站点配置业务仓库
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Frontend\Repositories;

use \Marser\App\Frontend\Repositories\BaseRepository;

class Options extends BaseRepository{

    /**
     * 配置缓存key
     */
    const OPTIONS_TREE_CACHE_KEY = 'options_list';

    /**
     * 配置缓存周期（秒）
     */
    const OPTIONS_CACHE_TTL = 86400;

    public function __construct(){
        parent::__construct();
    }

    /**
     * 获取配置数据
     * @return array|mixed
     */
    public function get_options_list(){
        /** 从缓存中读取 */
        $cache = $this -> getDI() -> get('cache');
        if($cache -> exists(self::OPTIONS_TREE_CACHE_KEY, self::OPTIONS_CACHE_TTL)){
            $optionsList = $cache -> get(self::OPTIONS_TREE_CACHE_KEY, self::OPTIONS_CACHE_TTL);
            $optionsList = json_decode($optionsList, true);
            if(is_array($optionsList) && count($optionsList) > 0){
                return $optionsList;
            }
        }
        /** 从数据库中读取分类数据 */
        $optionsList = $this -> get_list();
        /** 设置缓存 */
        $cache -> save(self::OPTIONS_TREE_CACHE_KEY, json_encode($optionsList), self::OPTIONS_CACHE_TTL);
        return $optionsList;
    }

    /**
     * 获取站点基本设置的配置
     * @return array
     */
    public function get_list(){
        $array = array();
        $options = $this -> get_model('OptionsModel') -> get_list();
        if(is_array($options) && count($options) > 0){
            foreach($options as $ok=>$ov){
                $array[$ov['op_key']] = $ov;
            }
        }
        return $array;
    }

    /**
     * 获取某个配置项的值
     * @param $key
     * @return bool|mixed
     */
    public function get_option($key){
        $options = $this -> get_options_list();
        if(is_array($options) && isset($options[$key])){
            return $options[$key]['op_value'];
        }
        return false;
    }
}
