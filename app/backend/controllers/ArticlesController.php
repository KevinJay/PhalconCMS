<?php

/**
 * 文章
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Backend\Controllers;

use \Marser\App\Backend\Controllers\BaseController;
use \Marser\App\Helpers\PaginatorHelper;
use \Michelf\Markdown;

class ArticlesController extends BaseController{

    public function initialize(){
        parent::initialize();
    }

    /**
     * 文章列表
     */
    public function indexAction(){
        $page = intval($this -> request -> get('page', 'trim'));
        $cid = intval($this -> request -> get('cid', 'trim'));
        $keyword = $this -> request -> get('keyword', 'trim');
        /**分页获取文章列表*/
        $pagesize = 10;
        $paginator = $this -> get_repository('Articles') -> get_list($page, $pagesize, array(
            'cid' => $cid,
            'keyword' => $keyword
        ));
        /**获取分页页码*/
        $pageNum = PaginatorHelper::get_paginator($paginator->total_items, $page, $pagesize);
        /**获取文章所属的分类ID*/
        $articles = $paginator->items->toArray();
        if(is_array($articles) && count($articles) > 0){
            $aids = array_column($articles, 'aid');
            $categorys = $this -> get_repository('Articles') -> get_categorys_by_aids($aids);
            foreach($categorys as $ck=>$cv){
                foreach($articles as $ak=>&$av){
                    if($cv['aid'] == $av['aid']){
                        $av['categorys'][] = array(
                            'cid' => $cv['cid'],
                            'category_name' => $cv['category_name'],
                        );
                        break;
                    }
                }
            }
        }
        /**获取分类*/
        $categoryList = $this -> get_repository('Categorys') -> get_category_list();

        $this -> view -> setVars(array(
            'paginator' => $paginator,
            'pageNum' => $pageNum,
            'cid' => $cid,
            'keyword' => $keyword,
            'articles' => $articles,
            'categoryList' => $categoryList,
        ));
        $this -> view -> pick('articles/index');
    }

    /**
     * 撰写新文章
     */
    public function writeAction(){
        $aid = intval($this -> request -> get('aid', 'trim'));
        /**获取分类数据*/
        $categoryList = $this -> get_repository('Categorys') -> get_category_list();
        /**获取文章数据*/
        $article = array();
        if($aid > 0){
            $article = $this -> get_repository('Articles') -> detail($aid);
            if(is_array($article) && count($article) > 0){
                /**获取文章关联的分类数据*/
                $categorys = $this -> get_repository('Articles') -> get_categorys_by_aids([$aid]);
                foreach($categorys as $ck=>$cv){
                    $article['categorys'][] = array(
                        'cid' => $cv['cid'],
                        'category_name' => $cv['category_name'],
                    );
                }
                /**获取文章关联的标签数据*/
                $tags = $this -> get_repository('Articles') -> get_tags_by_aids([$aid]);
                foreach($tags as $tk=>$tv){
                    $article['tags'][] = array(
                        'tid' => $tv['tid'],
                        'tag_name' => $tv['tag_name'],
                    );
                }
            }
        }

        $this -> view -> setVars(array(
            'categoryList' => $categoryList,
            'article' => $article,
        ));
        $this -> view -> pick('articles/write');
    }

    /**
     * 发布文章（添加、编辑）
     */
    public function publishAction(){
        try{
            if($this -> request -> isAjax() || !$this -> request -> isPost()){
                throw new \Exception('非法请求');
            }
            $aid = intval($this -> request -> getPost('aid', 'trim'));
            $title = $this -> request -> getPost('title', 'trim');
            $markdown = $this -> request -> getPost('markdown', 'trim');
            $time = $this -> request -> getPost('time', 'trim');
            $categorys = $this -> request -> getPost('category', 'trim');
            $tagName = $this -> request -> getPost('tags', 'trim');
            $content = $this->request->getPost('content', 'trim');
            $status = intval($this->request->getPost('status', 'trim'));
            /** 添加验证规则 */
            $this -> validator -> add_rule('title', 'required', '请填写标题');
            $this -> validator -> add_rule('markdown', 'required', '请填写文章内容');
            $this -> validator -> add_rule('category', 'required', '请选择分类');
            $this -> validator -> add_rule('tagName', 'required', '请填写文章标签');
            !empty($aid) && $this -> validator -> add_rule('aid', 'required', '系统错误，请刷新页面后重试');
            !empty($time) && $this -> validator -> add_rule('time', 'check_time', '请选择发布时间');
            /** 截获验证异常 */
            if ($error = $this -> validator -> run(array(
                'title' => $title,
                'markdown' => $markdown,
                'category' => $categorys,
                'tagName' => $tagName,
                'aid' => $aid,
                'time' => $time,
            ))) {
                $error = array_values($error);
                $error = $error[0];
                throw new \Exception($error['message'], $error['code']);
            }
            /** 发布文章 */
            $this -> get_repository('Articles') -> save(array(
                'title' => $title,
                'markdown' => $markdown,
                'content' => $content,
                'create_time' => $time,
                'cid' => $categorys,
                'tag_name' => $tagName,
                'introduce' => explode('<!--more-->', $content)[0],
                'status' => $status,
            ), $aid);

            $this -> flashSession -> success('发布文章成功');
        }catch(\Exception $e){
            $this -> write_exception_log($e);

            $this -> flashSession -> error($e -> getMessage());
        }
        return $this -> redirect('articles/index');
    }

    /**
     * 删除文章
     */
    public function deleteAction(){
        try{
            $aid = intval($this -> request -> get('aid', 'trim'));
            $affectedRows = $this -> get_repository('Articles') -> delete($aid);
            if(!$affectedRows){
                throw new \Exception('删除文章失败');
            }
            $this -> flashSession -> success('删除文章成功');
        }catch(\Exception $e){
            $this -> write_exception_log($e);

            $this -> flashSession -> error($e -> getMessage());
        }
        return $this -> redirect();
    }

    /**
     * 设为 / 取消置顶
     */
    public function topAction(){
        try{
            $aid = intval($this -> request -> get('aid', 'trim'));
            $type = intval($this -> request -> get('type', 'trim'));

            $affectedRows = $this -> get_repository('Articles') -> update_record(array(
                'is_top' => $type,
            ), $aid);
            $message = $type == 1 ? '设为置顶' : '取消置顶';
            if(!$affectedRows){
                throw new \Exception("{$message}失败");
            }
            $this -> flashSession -> success("{$message}成功");
        }catch(\Exception $e){
            $this -> write_exception_log($e);

            $this -> flashSession -> error($e -> getMessage());
        }
        return $this -> redirect();
    }

    /**
     * 设为 / 取消推荐阅读
     */
    public function recommendAction(){
        try{
            $aid = intval($this -> request -> get('aid', 'trim'));
            $type = intval($this -> request -> get('type', 'trim'));

            $affectedRows = $this -> get_repository('Articles') -> update_record(array(
                'is_recommend' => $type,
            ), $aid);
            $message = $type == 1 ? '设为推荐' : '取消推荐';
            if(!$affectedRows){
                throw new \Exception("{$message}失败");
            }
            $this -> flashSession -> success("{$message}成功");
        }catch(\Exception $e){
            $this -> write_exception_log($e);

            $this -> flashSession -> error($e -> getMessage());
        }
        return $this -> redirect();
    }
}
