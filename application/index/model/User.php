<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/4
 * Time: 2:01
 */

namespace app\index\model;


use think\Model;
use traits\model\SoftDelete;

class User extends Model
{
    // 指定表名
    protected $table = 'my_user';

    // 导入软删除方法集
    use SoftDelete;

    // 设置软删除字段
    // 只设置该字段为null,该字段才会显示出来
    protected $deleteTime = 'delete_time';

    // 保存自动完成列表
    protected $auto = [
        'delete_time' => null,
        'is_delete'   => 1, // 1:允许删除 0:禁止删除
    ];

    // 新增自动完成列表
    protected $insert = [
        'last_login_time' => null, // 新增时上次登录时间应该为null，因为刚创建
        'login_count'     => 0,    // 原因同上，刚创建肯定没有登录过
    ];

    // 更新自动完成列表
    protected $update = [];

    // 是否需要自动写入时间戳，如果设置为字符串，则表示时间字段的类型
    protected $autoWriteTimestamp = true; // 自动写入

    // 创建时间字段
    protected $createTime = 'create_time';

    // 更新时间字段
    protected $updateTime = 'update_time';

    // 时间字段经模型取出后的默认时间格式
    protected $dateFormat = 'Y年m月d日 H:i:s';

    // 数据表中角色字段：role返回值处理
    public function getRoleAttr($value)
    {
        $roleArr = [
            0 => '管理员',
            1 => '超级管理员',
        ];
        return $roleArr[$value];
    }

    // 状态字段：status返回值处理
    public function getStatusAttr($value)
    {
        $statusArr = [
            0 => '已停用',
            1 => '已启用'
        ];
        return $statusArr[$value];
    }

    // 密码修改器
    public function setPasswordAttr($value)
    {
        return md5($value);
    }

    // 登录时间模型获取器
    public function getLastLoginTimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }
}