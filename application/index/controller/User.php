<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/4
 * Time: 0:50
 */

namespace app\index\controller;


use think\Db;
use think\Request;
use app\index\model\User as UserModel;
use think\Session;
use think\Cookie;

class User extends Base
{
    /*private $_model = null;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->_model = new UserModel();
    }*/

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub

        $this->view->assign('keywords', '简易教学管理系统');
        $this->view->assign('desc', '简易教学管理系统');
    }

    /**
     * 渲染登录界面
     */
    public function login()
    {
        // 判断用户是否已经登录过了
        $this->alreadyLogin();
        $this->view->assign('title', '用户登录');
        /*$this->view->assign('keywords', '简易教学管理系统');
        $this->view->assign('desc', '简易教学管理系统');*/
        $this->view->assign('copyRight', '简易教学管理系统');
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
        $status = 0; // 验证失败标志
        $msg = '验证失败'; // 失败提示信息

        $data = $request->param();

        /*$name = $request->post('name');
        $password = input('password');*/

        // 验证规则
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
        // $validateRes只会返回两种值：true->表示验证通过，如果返回字符串，则是用户自定义的错误提示信息
        $validateRes = $this->validate($data, $rule, $result);

        // 如果验证通过则执行 进行数据表查询
        // 此处必须全等=== 才可以，因为验证不通过，$validateRes保存错误信息字符串，返回非零
        if ($validateRes === true) {
            // 构造查询条件
            $where = [
                'name'     => $data['name'],
                'password' => md5($data['password'])
            ];

            // 查询用户信息，返回模型对象
            $userInfo = UserModel::get($where);
            if (is_null($userInfo)) {
                $msg = '没有找到该用户，请检查!';
            } else {
                if (!$userInfo->getData('status')) {
                    $msg = '该用户已经被停用，请联系系统管理员处理!';
                } else {
                    $status = 1;
                    $msg = '验证通过,点击[确定]进入!';

                    // 创建2个session，用来检测用户登录状态和防止重复登录
                    Session::set('userId', $userInfo->id);
                    // 去除密码设置
                    $userInfoArr = $userInfo->getData();
                    unset($userInfoArr['password']);

                    Session::set('userInfo', $userInfoArr);

                    if (isset($data['online']) && $data['online'] == 1) { // 记住密码
                        Cookie::set('userId', $userInfo->id);
                    }

                    // 更新用户登录次数：自增1
                    $userInfo->setInc('login_count');
                    $userInfo->update(['last_login_time' => time()], ['id' => $userInfo->id]);
                }
            }
        } else {
            $msg = $validateRes;
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
        UserModel::update([
            'logout_time' => time(),
            'id'          => Session::get('userId')
        ]);
        // 注销session
        Session::delete('userId');
        Session::delete('userInfo');
        $this->redirect(url('user/login'));
        // $this->success('退出登录，正在返回!', url('user/login'));
    }

    /**
     * 渲染管理员列表
     */
    public function adminList()
    {
        $this->view->assign('title', '管理员列表');
        /*$this->view->assign('keywords', '简易教学管理系统');
        $this->view->assign('desc', '简易教学管理系统');*/

        $this->view->count = UserModel::count();

        // 判断当前用户是不是admin用户
        // 先通过session获取到用户登录名
        $userName = Session::get('userInfo.name');
        if ($userName == 'admin') {
            $list = UserModel::all(); // admin用户可以查看所有记录，数据要经过模型获取器处理
        } else {
            // 为了公用列表模板，使用了all()，其实这里用get()符合逻辑，但有时也要变通
            // 非admin只能看到自己信息，数据要经过模型获取器处理
            $list = UserModel::all(['id' => Session::get('userId')]);
        }

        $this->view->assign('list', $list);
        // 渲染管理员列表模板
        return $this->view->fetch('admin_list');
    }

    /**
     * 渲染添加管理员的页面
     */
    public function adminAdd()
    {
        $this->view->assign('title', '添加管理员');
        return $this->view->fetch('admin_add');
    }

    /**
     * 检测用户名是否可用 防止重复
     * @param Request $request
     */
    public function checkUserName(Request $request)
    {
        $userName = trim($request->param('name'));
        $status = 1;
        $msg = '用户名可用';
        if (UserModel::get(['name' => $userName])) {
            // 如果在表中查询该用户名
            $status = 0;
            $msg = '用户名重复,请重新输入!';
        }
        return json(['status' => $status, 'msg' => $msg]);
    }

    /**
     * 检测用户邮箱是否可用
     * @param Request $request
     * @return \think\response\Json
     */
    public function checkUserEmail(Request $request)
    {
        $userEmail = trim($request->param('email'));
        $status = 1;
        $msg = '邮箱可用';
        if (UserModel::get(['email' => $userEmail])) {
            // 查询表中找到了该邮箱，修改返回值
            $status = 0;
            $msg = '邮箱重复,请重新输入!';
        }
        return json(['status' => $status, 'msg' => $msg]);
    }

    /**
     * 处理管理员新增
     * @param Request $request
     * @return \think\response\Json
     */
    public function doAdminAdd(Request $request)
    {
        $data = $request->param();
        $status = 1;
        $msg = '添加管理员信息成功';

        $rules = [
            'name|用户名'   => 'require|min:3|max:10',
            'password|密码' => 'require|min:3|max:10',
            'email|邮箱' => 'require|email'
        ];

        $validateRes = $this->validate($data, $rules);
        if ($validateRes === true) {
            $res = UserModel::create($data);
            if ($res === null) {
                $status = 0;
                $msg = '添加管理员信息失败!';
            }
        }
        return json([
            'status' => $status,
            'msg'    => $msg
        ]);
    }

    /**
     * 软删除管理员操作
     */
    public function adminDelete(Request $request)
    {
        $userId = $request->param('id');
        UserModel::update(['is_delete' => 1], ['id' => $userId]);
        UserModel::destroy($userId);
    }

    /**
     * 软删除恢复操作
     */
    public function unDelete()
    {
        /*$idArr = Db::table('my_user')->whereNotNull('delete_time')->column('id');
        Db::table('my_user')->whereIn('id', $idArr)->update([
            'delete_time' => null,
            'is_delete' => 0,
        ]);*/

        // delete_time为null表示软删除未删除 使用模型查询时会自动加上此字段delete_time is null
        UserModel::update(['delete_time' => null], ['is_delete' => 1]);
    }

    /**
     * 渲染编辑管理员页面
     */
    public function adminEdit(Request $request)
    {
        $userId = $request->param('id');
        $result = UserModel::get($userId);
        $this->view->assign('title', '编辑管理员信息');
        $this->view->assign('userInfo', $result->getData());
        return $this->view->fetch('admin_edit');
    }

    /**
     * 处理更新管理员信息操作
     */
    public function doAdminEdit(Request $request)
    {
        // 获取表单返回的数据
        $reqParam = $request->param();
        /*$password = trim($reqParam['password']);
        if (empty($password)) { // 不修改密码
            unset($reqParam['password']);
        }*/

        // 去掉表单中为空的数据，即没有修改的内容
        foreach ($reqParam as $key => $value) {
            if ('' != trim($value)) {
                $data[$key] = $value;
            }
        }

        $where = ['id' => $data['id']];
        unset($data['id']);
        $updateRes = UserModel::update($data, $where);

        // 如果是admin用户，更新当前session中用户信息userInfo的角色role，供页面调用
        if (Session::get('userInfo.name') == 'admin') {
            if (isset($data['role'])) {
                Session::get('userInfo.role', $data['role']);
            }
        }

        if ($updateRes == true) {
            return json(['status' => 1, 'msg' => '修改管理员信息成功']);
        } else {
            return json(['status' => 0, 'msg' => '修改管理员信息失败,请检查']);
        }
    }

    /**
     * 管理员启用状态变更
     */
    public function setStatus(Request $request)
    {
        $userId = $request->param('id');
        $userInfo = UserModel::get($userId);
        if ($userInfo->getData('status') == 1) {
            UserModel::update(['status' => 0], ['id' => $userId]);
        } else {
            UserModel::update(['status' => 1], ['id' => $userId]);
        }
    }
}