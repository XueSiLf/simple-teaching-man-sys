<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/4
 * Time: 0:50
 */

namespace app\index\controller;


use think\Request;
use app\index\model\User as UserModel;

class User extends Base
{
    /*private $_model = null;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->_model = new UserModel();
    }*/

    /**
     * 渲染登录界面
     */
    public function login()
    {
        return $this->view->fetch('login');
    }

    /**
     * 验证登录 采用验证器
     * $data   要验证的数据
     * $rule   验证数据的规则
     * $result 验证失败以后的提示信息
     * $this->validate($data, $rule, $result)
     */
    public function checkLogin(Request $request)
    {
        // 初始化返回参数
        $status = 0;
        $msg = '';

        $data = $request->param();

        /*$name = $request->post('name');
        $password = input('password');*/

        $rule = [
            'name|用户名'     => 'require',         // 用户名必填
            'password|密码'   => 'require',         // 密码必填
            'verify|验证码'   => 'require|captcha', // 验证码必填
        ];

        // 自定义验证失败的提示信息
        $result = [
            'name.require'     => '用户名不能为空，请检查!',
            'password.require' => '密码不能为空，请检查!',
            'verify.require'   => '验证码不能为空，请检查!',
        ];

        // 进行验证
        // $msg = $this->validate($data, $rule);
        $validateRes = $this->validate($data, $rule, $result);

        // 如果验证通过则执行
        if ($validateRes == true) {
            // 构造查询条件
            $where = [
                'name'     => $data['name'],
                'password' => md5($data['password'])
            ];

            // 查询用户信息
            $userInfo = UserModel::get($where);
            if (is_null($userInfo)) {
                $msg = '没有找到该用户!';
            } else {
                $status = 1;
                $msg = '验证通过,点击[确定]进入!';
            }
        }

        return json([
            'status' => $status,
            'msg'    => $msg,
            'data'   => $data
        ]);
    }

    /**
     * 退出登录
     */
    public function logout()
    {

    }
}