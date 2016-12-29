<?php

namespace  Marser\App\Frontend\Models;

use \Marser\App\Backend\Models\BaseModel;

class OptionsModel extends BaseModel{

    const TABLE_NAME = 'options';

    public function initialize(){
        parent::initialize();
        $this->set_table_source(self::TABLE_NAME);
    }

    /**
     * 获取配置项数据
     * @param array $ext
     * @return mixed
     * @throws \Exception
     */
    public function get_list(array $ext=array()){
        $result = $this -> find();
        if(!$result){
            throw new \Exception('获取配置数据失败');
        }
        $options = $result -> toArray();
        return $options;
    }


}