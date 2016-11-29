<?php

/**
 * @Description: 函数
 * @Author: jason
 * @Date: 2014-07-25
 * @Version:1.0.0.0
 */

namespace Xiucai\AdminBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;

class PagePaginationServices
{
    private $ems;
    private $_navigationItemCount = 10;  //导航栏显示导航总页数
    private $_pageSize = null;            //每页项目数
    private $_align = "right";            //导航栏显示位置
    private $_itemCount = null;           //总项目数
    private $_pageCount = null;           //总页数
    private $_currentPage = null;         //当前页
    private $_front = null;                //前端控制器
    private $_PageParaName = "page";      //页面参数名称

    private $_firstPageString = "|<<";     //导航栏中第一页显示的字符
    private $_nextPageString = ">>";       //导航栏中前一页显示的字符
    private $_previousPageString = "<<";  //导航栏中后一页显示的字符
    private $_lastPageString = ">>|";     //导航栏中最后一页显示的字符
    //页数字间的间隔符 /

    /**
     * 初始化
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager) {

        $this->ems = $entityManager;
    }

    /**
     * 返回当前页
     * @return float|int|null
     */
    public function getCurrentPage(){
        return $this->_currentPage;
    }

    /**
     * 返回导航栏目
     * @return string
     */
    public function getNavigation($itemCount, $pageSize, $page, $router, $where = ""){
        if(!is_numeric($itemCount) || (!is_numeric($pageSize)))
            throw new Exception("Pagination Error:not Number");
        $this->_itemCount = $itemCount;
        $this->_pageSize = $pageSize;
        $this->_front = $router;

        $this->_pageCount = ceil($itemCount/$pageSize); //总页数
        if(empty($page) || (!is_numeric($page))){
            //为空或不是数字，设置当前页为1
            $this->_currentPage = 1;
        }else{
            if($page < 1)
                $page = 1;
            if($page > $this->_pageCount)
                $page = $this->_pageCount;
            $this->_currentPage = $page;
        }

        $navigation = '<div class="page"><p>';

        $pageCote = ceil($this->_currentPage / ($this->_navigationItemCount - 1)) - 1;    //当前页处于第几栏分页
        $pageCoteCount = ceil($this->_pageCount / ($this->_navigationItemCount - 1));    //总分页栏
        $pageStart = $pageCote * ($this->_navigationItemCount -1) + 1;                    //分页栏中起始页
        $pageEnd = $pageStart + $this->_navigationItemCount - 1;                        //分页栏中终止页
        if($this->_pageCount < $pageEnd)
        {
            $pageEnd = $this->_pageCount;
        }
        //$navigation .= "总共：{$this->_itemCount}条　{$this->_pageCount}页\n";
        //首页导航

        $navigation .= ' <a id="first" href="'.$this->createHref(1, $where)."\">首页</a> ";

        if($this->_currentPage != 1)                    //上一页导航
        {
            $navigation .= ' <a id="prev" href="'.$this->createHref($this->_currentPage-1, $where);
            $navigation .= "\">上一页</a> ";
        }

        while ($pageStart <= $pageEnd)                    //构造数字导航区
        {
            if($pageStart == $this->_currentPage)
            {
                $navigation .= " <a class='current' href='javascript:void(0)'>".$pageStart."</a> ";
            }
            else
            {
                $navigation .= ' <a class="page" href="'.$this->createHref($pageStart, $where)."\">$pageStart</a> ";
            }
            $pageStart++;
        }
        if($this->_currentPage != $this->_pageCount)    //下一页导航
        {
            $navigation .= ' <a id="next" href="'.$this->createHref($this->_currentPage+1, $where)."\">下一页</a> ";
        }
        //if($pageCote < $pageCoteCount-1)                //未页导航
        //{
        $navigation .= ' <a id="last" href="'.$this->createHref($this->_pageCount, $where)."\">尾页</a> ";
        //}
        //添加直接导航框
        //$navigation .= '<input type="text" size="3" onkeydown="if(event.keyCode==13){window.location=\' ';
        //$navigation .= $this->createHref().'\'+this.value;return false;}" />';
        //2008年8月27号补充输入非正确页码后出现的错误——begin
        /*$navigation .= ' <select onchange="window.location=\' '.$this->createHref().'\'+this.options

[this.selectedIndex].value;">';
        for ($i=1;$i<=$this->_pageCount;$i++){
            if ($this->getCurrentPage()==$i){
                $selected = "selected";
            }
            else {
                $selected = "";
            }
            $navigation .= '<option value='.$i.' '.$selected.'>'.$i.'</option>';
        }
        $navigation .= '</select>';
        //2008年8月27号补充输入非正确页码后出现的错误——end*/
        $navigation .= "</p></div>";
        return $navigation;
    }

    /**
     * 取得导航栏显示导航总页数
     *
     * @return int 导航栏显示导航总页数
     */
    public function getNavigationItemCount(){
        return $this->_navigationItemCount;
    }

    /**
     * 设置导航栏显示导航总页数
     *
     * @param int $navigationCount:导航栏显示导航总页数
     */
    public function setNavigationItemCoun($navigationCount){
        if(is_numeric($navigationCount)){
            $this->_navigationItemCount = $navigationCount;
        }
    }

    /**
     * 设置首页显示字符
     * @param string $firstPageString 首页显示字符
     */
    public function setFirstPageString($firstPageString){
        $this->_firstPageString = $firstPageString;
    }

    /**
     * 设置上一页导航显示字符
     * @param string $previousPageString:上一页显示字符
     */
    public function setPreviousPageString($previousPageString){
        $this->_previousPageString = $previousPageString;
    }

    /**
     * 设置下一页导航显示字符
     * @param string $nextPageString:下一页显示字符
     */
    public function setNextPageString($nextPageString){
        $this->_nextPageString = $nextPageString;
    }

    /**
     * 设置未页导航显示字符
     * @param $lastPageString
     */
    public function setLastPageString($lastPageString){
        $this->_lastPageString = $lastPageString;
    }

    /**
     * 设置导航字符显示位置
     * @param string $align:导航位置
     */
    public function setAlign($align){
        $align = strtolower($align);
        if($align == "center"){
            $this->_align = "center";
        }elseif($align == "right"){
            $this->_align = "right";
        }else{
            $this->_align = "left";
        }
    }

    /**
     * 设置页面参数名称
     * @param string $pageParamName:页面参数名称
     */
    public function setPageParamName($pageParamName){
        $this->_PageParaName = $pageParamName;
    }

    /**
     * 获取页面参数名称
     * @return string 页面参数名称
     */
    public function getPageParamName(){
        return $this->_PageParaName;
    }

    /**
     * 生成导航链接地址
     * @param null $targetPage
     * @param $where
     * @return null|string
     */
    private function createHref($targetPage = null, $where){
        $params = "";
        $where = (array)$where;
        if(!empty($where)){
            foreach($where as $parent){
                foreach($parent as $key => $child){
                    if($key == 'page')
                        continue;
                    if(!empty($child))
                        $params .= "&$key=$child";
                }
            }
        }

        $targetUrl = $this->_front;
        if(isset($targetPage))                //指定目标页
            $targetUrl .= "?$this->_PageParaName=$targetPage";
        $targetUrl .= $params;

        return $targetUrl;
    }
}
?>