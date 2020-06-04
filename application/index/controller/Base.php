<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/4
 * Time: 0:30
 */

namespace app\index\controller;


use think\Controller;
use think\Session;

class Base extends Controller
{
	/** @var boolean 用户是否登录标志 */
	private $isLogin = false;

	public function _initialize()
	{
		parent::_initialize(); // 继承父类中的初始化操作
		if (Session::has('userId')) {
			$this->isLogin = true;
		}
//		var_dump(Session::get('userId'));

	    /*$action = $this->request->action();
	    $filterArr = [
	    	'login'
	    ];
	    if (!in_array($action, $filterArr)) {
	    	// 判断用户是否登录
	    	$this->isLogin();
	    }*/
	}

	/**
     * 判断用户是否登录，放在系统后台入口前面：index/index
     * @return boolean
     */
	public function isLogin()
	{
		if (!$this->isLogin) {
			return $this->redirect(url('user/login'));
			# return $this->error('用户未登录，无权访问!', url('user/login'));
		}
	}

	/**
	 * 防止用户重复登录，放在登录操作前面：user/login
	 * @return boolean
	 */
	public function alreadyLogin()
	{
		if ($this->isLogin) {
			return $this->redirect(url('index/index'));
			# return $this->error('用户已经登录，请勿重复登录!', url('index/index'));
		}
	}
}