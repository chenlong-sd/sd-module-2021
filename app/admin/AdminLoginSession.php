<?php
/**
 * datetime: 2022/1/19 22:35
 * user    : chenlong<vip_chenlong@163.com>
 **/

namespace app\admin;

use think\helper\Str;

/**
 * 后台管理登录的session管理
 * Class LoginInfo
 * 设置开放表后，可自定义方法用于IDE提示：
 * 获取属性的方法名字  get + session字段名， 例：session 存了 用户的 vip字段 ， getVip()
 * 判断账号的方法名字  is  + 表名（不要前缀）， 例：开放user登录 ， isUser()
 *
 * @method static null|int    getId($default = null)           获取登录ID值
 * @method static null|array  getRoute($default = null)        获取当前用户的路由ID集合
 * @method static null|string getTable($default = null)        获取当前登录的表名（不含前缀
 * @method static null|string getRoleId($default = null)       获取当前登录的角色ID
 * @method static null|string getName($default = null)         获取当前登录的用户名
 * @method static null|string getAccount($default = null)      获取当前登录的账号
 * @method static null|int    isUser()  判断是否是用户账号登录，开启有效
 * @package app\admin
 * @author chenlong<vip_chenlong@163.com>
 * @date 2022/1/19
 */
class AdminLoginSession
{
    /**
     * 保存session的key
     */
    private const SESSION_KEY = 'Administrators__Sd__';

    /**
     * 存储用户登录session数据的变量
     * @var array|null
     */
    private static $data = null;

    /**
     * 设置登录的session信息
     * @param array $data
     * @author chenlong<vip_chenlong@163.com>
     * @date 2022/1/19
     */
    protected function setData(array $data = [])
    {
        self::$data === null and self::$data = $data ?: session(self::SESSION_KEY);
    }

    /**
     * 登录判断
     * @return bool
     * @author chenlong<vip_chenlong@163.com>
     * @date 2022/1/19
     */
    protected function loginCheck():bool
    {
        return session('?' . self::SESSION_KEY);
    }

    /**
     * 保存 session
     * @param array $data
     * @return mixed
     * @author chenlong<vip_chenlong@163.com>
     * @date 2022/1/17
     */
    protected function save(array $data)
    {
        return session(self::SESSION_KEY, $data);
    }

    /**
     * 是否是管理员类型账号登录
     * @return bool
     * @author chenlong<vip_chenlong@163.com>
     * @date 2022/1/17
     */
    public static function isAdmin(): bool
    {
        return self::getData('is_admin') === true;
    }

    /**
     * 获取所有的登录信息
     * @return array
     * @author chenlong<vip_chenlong@163.com>
     * @date 2022/1/18
     */
    public static function getAll(): array
    {
        return self::$data;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed|null
     * @author chenlong<vip_chenlong@163.com>
     * @date 2022/1/17
     */
    public static function __callStatic($name, $arguments)
    {
        if (substr($name, 0, 3) === 'get') {
            return self::getData(ltrim($name, 'get'), ($arguments[0] ?? null));
        } elseif (substr($name, 0, 2) === 'is') {
            return self::AccountTypeCheck(ltrim($name, 'is'));
        }

        return null;
    }

    /**
     * @param string $name
     * @param null $default
     * @return mixed|null
     * @author chenlong<vip_chenlong@163.com>
     * @date 2022/1/17
     */
    private static function getData(string $name, $default = null)
    {
        $name = Str::snake($name);
        return self::$data[$name] ?? $default;
    }

    /**
     * @param string $method
     * @return bool
     * @author chenlong<vip_chenlong@163.com>
     * @date 2022/1/17
     */
    private static function AccountTypeCheck(string $method): bool
    {
        return self::getData('table') === Str::snake($method);
    }
}
