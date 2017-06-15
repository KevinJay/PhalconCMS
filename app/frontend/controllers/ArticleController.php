<?php

namespace  Marser\App\Frontend\Controllers;

use \Marser\App\Frontend\Controllers\BaseController,
    \Marser\App\Helpers\PaginatorHelper;

class ArticleController extends  BaseController{

    /**
     * 首页 / 搜索页 / 分类页 / 标签页
     */
    public function listAction(){
        try {
            /** 设置置顶文章 */
            $this->set_top_articles();
            /** 设置文章列表 */
            $this->set_articles_list();
            /**设置推荐文章*/
            $this->set_recommend_articles();
            /**设置分类*/
            $this->set_categorys();
            /**设置标签*/
            $this->set_tags();
            /**设置站点统计*/
            $this->set_statistic();

            $this->view->pick('article/list');
        }catch(\Exception $e){
            $this -> write_exception_log($e);

            return $this -> response -> redirect('/404');
        }
    }

    /**
     * 文章页
     */
    public function detailAction(){
        try{
            /** 设置文章数据 */
            $this -> set_article();
            /** 设置前一篇文章 */
            $this -> set_prev_article();
            /** 设置后一篇文章 */
            $this -> set_next_article();
            /**设置推荐文章*/
            $this -> set_recommend_articles();
            /**设置分类*/
            $this -> set_categorys();
            /**设置标签*/
            $this -> set_tags();
            /**设置站点统计*/
            $this -> set_statistic();

            $this -> view -> pick('article/detail');
        }catch(\Exception $e){
            $this -> write_exception_log($e);

            return $this -> redirect('/404');
        }
    }

    /**
     * 设置置顶文章
     */
    protected function set_top_articles(){
        $topArticles = $this -> get_repository('Articles') -> get_top_articles();
        $topArticles = $topArticles -> toArray();

        $this -> view -> setVar('topArticles', $topArticles);
    }

    /**
     * 设置文章数据
     */
    protected function set_articles_list(){
        $page = intval($this -> request -> get('page', 'trim'));
        $keyword = $this -> request -> get('keyword', 'remove_xss');
        $ext['keyword'] = $keyword;
        $cid = 0;
        if($this -> dispatcher -> hasParam('category')){
            $category = $this -> dispatcher -> getParam('category', 'trim');
            if(intval($category) <= 0){
                $category = $this -> get_repository('Categorys') -> get_category_by_slug($category);
                $cid = $category -> cid;
            }else{
                $cid = intval($category);
            }
            $ext['cid'] = $cid;
        }
        $tid = 0;
        if($this -> dispatcher -> hasParam('tag')){
            $tag = $this -> dispatcher -> getParam('tag', 'trim');
            if(intval($tag) <= 0){
                $tag = $this -> get_repository('Tags') -> get_tag_by_slug($tag);
                $tid = $tag -> tid;
            }else{
                $tid = intval($tag);
            }
            $ext['tid'] = $tid;
        }
        /** 分页获取文章列表 */
        $pagesize = $this -> get_repository('Options') -> get_option('page_article_number');
        $paginator = $this -> get_repository('Articles') -> get_list($page, $pagesize, $ext);
        /** 获取分页页码 */
        $pageNum = PaginatorHelper::get_paginator($paginator->total_items, $page, $pagesize);
        $articles = $paginator -> items -> toArray();
        if(is_array($articles) && count($articles) > 0){
            $aids = array_column($articles, 'aid');
            /** 根据aids获取分类 */
            $categorys = $this -> get_repository('Articles') -> get_categorys_by_aids($aids);
            foreach($categorys as $ck=>$cv){
                foreach($articles as $ak=>&$av){
                    if($cv->aid == $av['aid']){
                        $av['categorys'][] = array(
                            'cid' => $cv->cid,
                            'category_name' => $cv->category_name,
                        );
                        break;
                    }
                }
            }
            /** 根据aids获取标签 */
            $tags = $this -> get_repository('Articles') -> get_tags_by_aids($aids);
            foreach($tags as $tk=>$tv){
                foreach($articles as $ak=>&$av){
                    if($tv->aid == $av['aid']){
                        $av['tags'][] = array(
                            'tid' => $tv->tid,
                            'tag_name' => $tv->tag_name,
                        );
                        break;
                    }
                }
            }
        }

        $this -> view -> setVars(array(
            'articles' => $articles,
            'paginator' => $paginator,
            'pageNum' => $pageNum,
            'keyword' => $keyword,
            'cid' => $cid,
            'tid' => $tid,
        ));
    }

    /**
     * 设置推荐文章数据
     */
    protected function set_recommend_articles(){
        $recommendPagesize = $this -> get_repository('Options') -> get_option('recommend_article_number');
        $recommendArticles = $this -> get_repository('Articles') -> get_recommend_articles($recommendPagesize);
        $recommendArticles = $recommendArticles -> toArray();

        $this -> view -> setVar('recommendArticles', $recommendArticles);
    }

    /**
     * 设置分类数据
     */
    protected function set_categorys(){
        $categorysList = $this -> get_repository('Categorys') -> get_category_list();

        $this -> view -> setVar('categorysList', $categorysList);
    }

    /**
     * 设置标签数据
     */
    protected function set_tags(){
        $tagsList = $this -> get_repository('Tags') -> get_tags_list();
        shuffle($tagsList);
        $tagidArray = array_column($tagsList, 'tid');
        $tagsList = array_combine($tagidArray, $tagsList);

        $this -> view -> setVar('tagsList', $tagsList);
    }

    /**
     * 设置站点统计数据
     */
    protected function set_statistic(){
        $totalArticle = $this -> get_repository('Articles') -> get_count();
        $totalCategory = $this -> get_repository('Categorys') -> get_count();
        $totalTag = $this -> get_repository('Tags') -> get_count();

        $this -> view -> setVars(array(
            'totalArticle' => $totalArticle,
            'totalCategory' => $totalCategory,
            'totalTag' => $totalTag,
        ));
    }

    /**
     * 设置文章数据
     * @throws \Exception
     */
    public function set_article(){
        /** 获取文章数据 */
        $aid = intval($this->dispatcher->getParam('aid', 'trim'));
        $article = $this -> get_repository('Articles') -> detail($aid);
        if(!is_array($article) || count($article) == 0){
            throw new \Exception('文章不存在', 404);
        }
        /** 根据aid获取分类 */
        $categorys = $this -> get_repository('Articles') -> get_categorys_by_aids([$aid]);
        foreach($categorys as $ck=>$cv){
            $article['categorys'][] = array(
                'cid' => $cv->cid,
                'category_name' => $cv->category_name,
            );
        }
        /** 根据aid获取标签 */
        $tags = $this -> get_repository('Articles') -> get_tags_by_aids([$aid]);
        foreach($tags as $tk=>$tv){
            $article['tags'][] = array(
                'tid' => $tv->tid,
                'tag_name' => $tv->tag_name,
            );
        }
        /** 更新浏览次数 */
        $this -> get_repository('Articles') -> update_views($aid);
        /** 设置猜你喜欢文章数据 */
        $cids = array_column($article['categorys'], 'cid');
        $tids = array_column($article['tags'], 'tid');
        $this -> set_guess_you_like($cids, $tids, $aid);
        /** 生成keywords */
        $categoryNames = array_column($article['categorys'], 'category_name');
        $tagsName = array_column($article['tags'], 'tag_name');
        $siteKeywords = array_merge($tagsName, $categoryNames);
        $siteKeywords = array_unique($siteKeywords);

        $this -> view -> setVars(array(
            'siteTitle' => $article['title'],
            'siteKeywords' => implode(',', $siteKeywords),
            'article' => $article,
        ));
    }

    /**
     * 设置猜你喜欢的文章数据
     * @param array $cids
     * @param array $tids
     * @param $aid
     */
    protected function set_guess_you_like(array $cids, array $tids, $aid){
        $pagesize = $this -> get_repository('Options') -> get_option('relate_article_number');
        $articles = $this -> get_repository('Articles') -> guess_you_like($cids, $tids, $aid, $pagesize);

        $this -> view ->setVar('guessYouLike', $articles);
    }

    /**
     * 设置前一篇文章
     */
    protected function set_prev_article(){
        $aid = intval($this->dispatcher->getParam('aid', 'trim'));
        $prevArticle = $this -> get_repository('Articles') -> get_prev_article($aid);

        $this -> view -> setVar('prevArticle', $prevArticle);
    }

    /**
     * 设置后一篇文章
     */
    protected function set_next_article(){
        $aid = intval($this->dispatcher->getParam('aid', 'trim'));
        $nextArticle = $this -> get_repository('Articles') -> get_next_article($aid);

        $this -> view -> setVar('nextArticle', $nextArticle);
    }
}