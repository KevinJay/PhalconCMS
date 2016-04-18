<?php

namespace cpsapi\app\helpers;

class OutputHelper {

    /**
     * 格式化输出格式
     * @static
     * @access public
     * @param string $msg
     * @param number $code
     * @param array $data
     * @return array
     * @date 2015-06-17 15:25:00
     *
     */
    static public function format_return($msg, $code=500, array $data=array()){
        $result = array(
            'code' => $code,
            'message' => $msg,
            'data' => $data,
        );
        return $result;
    }

    /**
     * ajax输出
     * @static
     * @access public
     * @param number $data 返回结果
     * @param mixed $callback 回调函数
     * @date 2015-06-17 15:47:22
     */
    static public function ajax_return($data, $callback=null){
        header('Content-Type:application/json; charset=utf-8');
        $json = json_encode($data);
        if (empty($callback)) {
            echo $json;
        } else {
            echo $callback . '(' . $json . ')';
        }
    }
}