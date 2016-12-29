<?php

/**
 * 标签业务仓库
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Frontend\Repositories;

use \Marser\App\Frontend\Repositories\BaseRepository;

class Tags extends BaseRepository{

    /**
     * 标签缓存key
     */
    const TAGS_CACHE_KEY = 'tags_list';

    /**
     * 标签缓存时间（秒）。一个月
     */
    const TAGS_CACHE_TTL = '2592000';

    public function __construct(){
        parent::__construct();
    }

    /**
     * 统计数量
     * @return mixed
     */
    public function get_count(){
        $count = $this -> get_model('TagsModel') -> get_count();
        return $count;
    }

    /**
     * 获取标签数据
     * @return array
     */
    public function get_tags_list(){
        /** 从缓存中读取 */
        $cache = $this -> getDI() -> get('cache');
        if($cache -> exists(self::TAGS_CACHE_KEY, self::TAGS_CACHE_TTL)){
            $tagsArray = $cache -> get(self::TAGS_CACHE_KEY, self::TAGS_CACHE_TTL);
            $tagsArray = json_decode($tagsArray, true);
            if(is_array($tagsArray) && count($tagsArray) > 0){
                return $tagsArray;
            }
        }
        /** 从数据库中读取标签数据 */
        $tagsArray = $this -> get_list();
        /** 设置缓存 */
        $cache -> save(self::TAGS_CACHE_KEY, json_encode($tagsArray), self::TAGS_CACHE_TTL);
        return $tagsArray;
    }

    /**
     * 标签列表
     * @param int $status
     * @param array $ext
     * @return array
     * @throws \Exception
     */
    protected function get_list(){
        $tagsList = $this -> get_model('TagsModel') -> get_list();
        return $tagsList;
    }

    /**
     * 根据缩略名获取标签数据
     * @param $slug
     * @return mixed
     */
    public function get_tag_by_slug($slug){
        $tag = $this -> get_model('TagsModel') -> get_tag_by_slug($slug);
        return $tag;
    }


}