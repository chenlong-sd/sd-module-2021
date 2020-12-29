<?php


namespace sdModule\common\helper;


class Password
{
    const PREFIX = 'sd-_CL_love-Forever-';

    /**
     * 加密
     * @param string $password
     * @param string $prefix
     * @return bool|false|string|null
     */
    public function encryption(string $password, string $prefix = self::PREFIX)
    {
        $strlen = strlen($password);
        if ($strlen <= 6) {
            return password_hash($prefix . $password, PASSWORD_DEFAULT);
        } elseif ($strlen > 44) {
            return false;
        }

        $s1 = strrev(substr($password, 0, 3));
        $s2 = strrev(substr($password, -3));

        $password = $s2 . $prefix . $password . $s1;
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * 验证
     * @param string $password  明文密码
     * @param string $hash_string 哈希字符串
     * @param string $prefix
     * @return bool
     */
    public function verify(string $password, string $hash_string, string $prefix = self::PREFIX)
    {
        $strlen = strlen($password);
        if ($strlen <= 6) {
            return password_verify($prefix . $password, $hash_string);
        } elseif ($strlen > 44) {
            return false;
        }

        $s1 = strrev(substr($password, 0, 3));
        $s2 = strrev(substr($password, -3));

        $password = $s2 . $prefix . $password . $s1;
        return password_verify($password, $hash_string);
    }

}


