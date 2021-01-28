<?php
/**
 * Date: 2020/11/4 16:56
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\common;

/**
 * 静态调用组件
 * Class StaticCallGetInstance
 * @package sdModule\common
 */
abstract class StaticCallGetInstance extends Singleton
{
    /**
     * 组件实例组
     * @var array
     */
    private array $instances = [];

    /**
     * 返回命名空间
     * @return string|array
     */
    abstract protected function getNamespace();

    protected function init()
    {
        // TODO: Implement init() method.
    }

    /**
     * @param $method
     * @param $vars
     * @return object
     * @throws \ReflectionException
     */
    public static function __callStatic($method, $vars)
    {
        $instance   = self::getInstance();
        $classname  = $instance->getClassname($method);
        $class      = $instance->getIncludeNamespaceClassname($classname, $method);
        return $instance->getModuleInstance($class, $vars);
    }


    /**
     * 获取包含命名空间的类名
     * @param string $classname 类名
     * @param string $method
     * @return string
     */
    private function getIncludeNamespaceClassname(string $classname, string $method):string
    {
        if (is_string($namespace = $this->getNamespace())) {
            return rtrim($namespace, '\\') . '\\' . $classname;
        }else{
            return $namespace[$method] ?? $classname;
        }
    }

    /**
     * 获取组件实例
     * @param string $class
     * @param array $vars
     * @return object
     * @throws \ReflectionException
     */
    private function getModuleInstance(string $class, array $vars)
    {
        if (!empty($this->instances[$class])) {
            return $this->instances[$class];
        }

        $reflex_class = new \ReflectionClass($class);
        if (($construct = $reflex_class->getConstructor()) && $construct->getParameters()){
            return $reflex_class->newInstanceArgs($vars);
        }

        return $this->instances[$class] = $reflex_class->newInstance();
    }

    /**
     * 获取参数名字
     * @param string $method 方法名字
     * @return string
     * @throws \ReflectionException
     */
    private function getClassname(string $method)
    {
        $s = new \ReflectionClass($this);
        $line_doc = explode("\r\n", $s->getDocComment());
        foreach ($line_doc as $doc) {
            if (preg_match('/\* (@method\s)(static(\s)+)?(\w+(\s)+)[A-Za-z]\w*\(.*\)$/', $doc)){
                preg_match_all('/[A-Za-z]\w*\(/', $doc, $match);
                if ($match && substr(current($match[0]), 0, -1) === $method){
                    preg_match('/(\s)+(static(\s)+)+([A-Za-z]+[0-9]*(\s)+)/', $doc, $class);
                    return trim(strtr(current($class), [' static ' => '']));
                }
            }
        }
        return '';
    }
}
