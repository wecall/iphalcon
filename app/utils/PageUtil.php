<?php

class PageUtil
{
    public $current;
    public $before;
    public $next;
    public $total_pages;
    public $total_items;

    /**
     * 获取分页信息
     * @param $current_page 当前页号
     * @param $page_limit  一页显示的条数
     * @param $total_items 总条数
     * @return PageUtil
     */
    public static function getPageData($current_page = 1, $page_limit = 10, $total_items)
    {
        $page_data = array();
        $page_data['current'] = $current_page;
        $page_data['total_items'] = $total_items;
        if ($total_items % $page_limit) {
            $total_page = (int)($total_items / $page_limit) + 1;
        } else {
            $total_page = $total_items / $page_limit;
        }
        //超过总页码时处理
        if ($current_page > $total_page) {
            $current_page = $total_page;
        }
        $page_data['total_pages'] = $total_page;//总页数
        if ($current_page > 1) {
            $before_page = $current_page - 1;
        } else {
            if ($current_page == 0) {
                $current_page = 1;
                $page_data['current'] = 1;
            }
            $before_page = 1;
        }
        $page_data['before'] = $before_page;//上一页号
        if ($current_page < $total_page) {
            $nextPage = $current_page + 1;
        } else {
            $nextPage = $current_page;
            if ($total_page > 0) {
                $page_data['current'] = $total_page;
            }
        }
        $page_data['next'] = $nextPage;//下一页号

        return $page_data;
    }
}