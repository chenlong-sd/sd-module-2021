<?php
/**
 * Date: 2021/5/7 15:50
 * User: chenlong <vip_chenlong@163.com>
 */

namespace sdModule\common\helper;

/**
 * 字符串表达式计算
 * Class ExpressionCalculation
 * @package app\common\service
 */
class ExpressionCalculation
{
    /**
     * 详细表达式单元
     * @var array
     */
    private $exprItem = [];

    /**
     * @var string 最终表达式
     */
    private $expr = '';

    private $extendCallable = [];

    private $extendPreg = [];

    private $extendExpr = [];

    /**
     * @var int 括号表达式索引
     */
    private $index = 1;
    /**
     * @var int 扩展表达式索引
     */
    private $ExtendIndex = 1;

    /**
     * 创建表达式并计算
     * @param string $express
     * @return $this
     */
    public function create(string $express): ExpressionCalculation
    {
        $express    = $this->parsingExtension(strtr($express, [' ' => '']));
        $this->expr = $this->brackets($express);

        return $this;
    }

    /**
     * 扩展方法 ceil[3 * 5 / 7] 用中 [] 代替 （）
     * @param string $method 正则表达式 例 ceil
     * @param callable $callable 执行函数 例 fn($value) => ceil($value)
     * @return $this
     */
    public function extend(string $method, callable $callable): ExpressionCalculation
    {
        $this->extendPreg[]              = $method;
        $this->extendCallable[$method]   = $callable;
        return $this;
    }


    /**
     * 获取结果
     * @return false|int|mixed|string
     */
    public function getResult()
    {
        return $this->compute($this->expr, $this->exprItem);
    }

    /**
     * @return false|float
     */
    public function getCeil()
    {
        return ceil($this->compute($this->expr, $this->exprItem));
    }

    /**
     * @return false|float
     */
    public function getFloor()
    {
        return floor($this->compute($this->expr, $this->exprItem));
    }

    /**
     * @param int $precision
     * @return float
     */
    public function getRound(int $precision = 0): float
    {
        return round($this->compute($this->expr, $this->exprItem), $precision);
    }

    /**
     * 计算
     * @param string $expr
     * @param array $exprItem
     * @return false|int|mixed
     */
    private function compute(string $expr, array $exprItem)
    {
        if (preg_match('/^(.+)(\+|\-|\/|\*)(.+)/', $expr, $match)) {
            $number1 = $match[1];
            $number2 = $match[3];
            if (isset($exprItem[$match[1]])) {
                // 判断是否有扩展函数
                if (isset($this->extendExpr[$match[1]]) && isset($this->extendCallable[$this->extendExpr[$match[1]]])) {
                    $number1 = call_user_func($this->extendCallable[$this->extendExpr[$match[1]]], $this->compute($match[1], $exprItem));
                }else{
                    $number1 = $this->compute($match[1], $exprItem);
                }
            }
            if (isset($exprItem[$match[3]])) {
                // 判断是否有扩展函数
                if (isset($this->extendExpr[$match[3]]) && isset($this->extendCallable[$this->extendExpr[$match[3]]])) {
                    $number2 = call_user_func($this->extendCallable[$this->extendExpr[$match[3]]], $this->compute($match[3], $exprItem));
                }else{
                    $number2 = $this->compute($match[3], $exprItem);
                }
            }
            // 判断是否有扩展函数
            if (($key = array_search($match[0], $this->exprItem)) && isset($this->extendExpr[$key]) && isset($this->extendCallable[$this->extendExpr[$key]])){
                return call_user_func($this->extendCallable[$this->extendExpr[$key]], $this->specifiedOperation($number1, $number2, $match[2]));
            }
            // 计算值
            return $this->specifiedOperation($number1, $number2, $match[2]);
        }
        return isset($exprItem[$expr]) ? $this->compute($exprItem[$expr], $exprItem) : $expr;
    }

    /**
     * 自定运算符运算
     * @param float $number1
     * @param float $number2
     * @param string $symbol
     * @return float|int
     */
    private function specifiedOperation(float $number1, float $number2, string $symbol)
    {
        switch ($symbol) {
            case '+':
                return $number1 + $number2;
            case '-':
                return $number1 - $number2;
            case '*':
                return $number1 * $number2;
            case '/':
                return $number1 / $number2;
        }
        return 0;
    }

    /**
     * 解析扩展
     * @param string $expression
     * @return string
     */
    private function parsingExtension(string $expression): string
    {
        if (!$this->extendPreg) {
            return $expression;
        }
        $this->ExtendIndex++;
        foreach ($this->extendPreg as $index => $preg){
            $pregTrue = "/{$preg}\[(((?!\[)(?!\]).)+)\]/";
            preg_match_all($pregTrue, $expression, $match);
            if (!current($match)) continue;
            $currentReplace = [];
            foreach ($match[1] as $matchIndex => $matchItem){
                $uniqueKey = "extend{$this->ExtendIndex}{$matchIndex}";
                $this->exprItem[$uniqueKey]   = $this->brackets($this->parsingExtension($matchItem));
                $this->extendExpr[$uniqueKey] = $preg;
                $currentReplace[]             = $uniqueKey;
            }
            $newExpr    = preg_replace($pregTrue, '%s', $expression);
            $expression = $this->parsingExtension(sprintf($newExpr, ...$currentReplace));
        }

        return $expression;
    }

    /**
     * 解析括号
     * @param $expression
     * @param int $index
     * @return string
     */
    private function brackets($expression): string
    {
        preg_match_all('/\([\w|\s]+[\-|\+|\*\/]+[\w|\s]+\)/', $expression, $match);
        if (!current($match)) {
            $key = 1;
            foreach ($this->exprItem as &$value){
                $value = $this->multiplyAndDivide(strtr($value, ['(' => '', ')' => '']), $replaceT, $key++);
            }
            $expression = $this->multiplyAndDivide($expression, $replaceT);
            unset($value);
            $this->exprItem = array_merge($this->exprItem, $replaceT ?? []);
            return $expression;
        }
        $currentReplace = [];
        foreach (current($match) as $key => $expr){
            $rKey = "replace{$this->index}{$key}";
            $this->exprItem[$rKey]   = $expr;
            $currentReplace[]        = $rKey;
        }
        $newExpr = preg_replace('/\([\w|\s|\-|\+|\*\/]+\)/', '%s', $expression);
        $newExpr = sprintf($newExpr, ...$currentReplace);
        $this->index++;
        return $this->brackets($newExpr);
    }

    /**
     * 解析乘除法
     * @param $expression
     * @param array $replace
     * @param int $index
     * @return string
     */
    private function multiplyAndDivide($expression, &$replace = [], $index = 1): string
    {
        $preg = '/(\+|\-)(((replace|extend)[0-9]+|[0-9\.]+)(\*|\/)((replace|extend)[0-9]+|[0-9\.]+))/';
        preg_match_all($preg, $expression, $match);
        if (!current($match) || preg_match_all('/\+|\-|\/|\*/', $expression) < 2) {
            return $this->simplification($expression, $replace, $index);
        }
        $currentReplace = [];
        foreach ($match[2] as $key => $expr){
            $rKey = "replaceT{$index}{$key}";
            $replace[$rKey]   = $expr;
            $currentReplace[] = $rKey;
        }
        $newExpr = preg_replace($preg, '$1%s', $expression);
        return $this->multiplyAndDivide(sprintf($newExpr, ...$currentReplace), $replace);
    }

    /**
     * 表达式化简
     * @param $expression
     * @param array $replace
     * @param int $index
     * @param int $key
     * @return mixed
     */
    private function simplification($expression, &$replace = [], $index = 1, $key = 0)
    {
        if (preg_match_all('/\+|\-|\/|\*/', $expression) < 2){
            return $expression;
        }
        preg_match('/^((replace|extend)(T|I)?[0-9]+|([0-9]|\.)+)(\+|\-|\/|\*)((replace|extend)(T|I)?[0-9]+|([0-9]|\.)+)/', $expression, $match);
        $replace["replaceI{$index}{$key}"] = current($match);
        $newExpr = preg_replace('/^((replace|extend)(T|I)?[0-9]+|([0-9]|\.)+)(\+|\-|\/|\*)((replace|extend)(T|I)?[0-9]+|([0-9]|\.)+)/', "replaceI{$index}{$key}", $expression);
        $key++;
        return $this->simplification($newExpr, $replace, $index, $key);
    }
}


