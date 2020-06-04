<?php
namespace app\index\controller;

class Index extends Base
{
    /**
     * 后台首页
     * @return mixed
     */
    public function index()
    {
    	// $this->isLogin(); // 判断用户是否登录
    	$this->view->assign('title', '简易教学管理系统');
        return $this->view->fetch('index'); // 渲染后台首页模板
    }
}
