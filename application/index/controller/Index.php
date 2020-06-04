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
        return $this->view->fetch('index');
    }
}
