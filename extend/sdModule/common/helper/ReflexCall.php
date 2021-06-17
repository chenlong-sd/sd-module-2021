<?php


namespace sdModule\common\helper;

/**
 * 反射类调用处理
 * Class ReflexCall
 * @package app\common\custom
 */
class ReflexCall
{
    /**
     * 利用反射类调用指定的操作函数
     * @param object|string $class  所属类的实例或类名
     * @param string        $method 函数名
     * @param array         $args   参数
     * @return mixed
     * @throws \ReflectionException
     */
    public function invoke($class, string $method, array $args = [])
    {
        $class = is_object($class) ? $class : $this->getInstance($class);

        if (method_exists($class, $method)) {
            $reflex = new \ReflectionMethod($class, $method);
            return $reflex->invokeArgs($class, $this->paramHandle($reflex, $args));
        }else if(method_exists($class, '__call')){
            $reflex = new \ReflectionMethod($class, '__call');
            return $reflex->invokeArgs($class, ['method' => $method, 'vars' => $args]);
        }else{
            throw new \ReflectionException($class .  "::" . $method .  ' 方法不存在');
        }
    }

    /**
     * 利用反射类获取指定类实例
     * @param string $className 类名
     * @param array  $args  构造参数
     * @return object
     * @throws \ReflectionException
     */
    public function getInstance(string $className = '', array $args = [])
    {
        $ReflectionClass = new \ReflectionClass($className);
        if (!$ReflectionClass->getConstructor()) {
            return $ReflectionClass->newInstance();
        }

        return $ReflectionClass->newInstanceArgs($this->paramHandle($ReflectionClass->getConstructor(), $args));
    }

    /**
     * 函数的参数处理并注入
     * @param \ReflectionMethod $method
     * @param array $args
     * @return array
     * @throws \ReflectionException
     */
    private function paramHandle(\ReflectionMethod $method, array $args = [])
    {
        $param = [];
        foreach ($method->getParameters() as $parameter) {
            if (isset($args[$parameter->name])) {
                $param[$parameter->name] = $args[$parameter->name];
            }else if ($parameter->getClass()) {
                $class = $parameter->getClass()->name;
                $param[$parameter->getName()] = $this->getInstance($class, $args);
            }else if ($parameter->isDefaultValueAvailable()) {
                continue;
            }else if($parameter->isArray()){
                $param[$parameter->getName()] = [];
            }else if ($parameter->isCallable()) {
                $param[$parameter->getName()] = function () {return null;};
            }else {
                $param[$parameter->getName()] = $parameter->allowsNull() ? null : '';
            }
        }
        return $param;
    }
}

