定制化开发请加qq 1424173603
# tableEdit
&emsp;ayui table edit编辑功能
<br/>

## 一、介绍
&emsp;&emsp; 基于aop编程思想对layui table工具条事件tool(lay-filter)进行封装而成的table单元格编辑器。

### 功能

- 下拉框（单或多选）
- 时间选择框
- 单元格下拉框联动（下拉框联动下拉框、时间选择框联动下拉框）
- 编辑数据校验

## 二、使用说明

### 1.使用方法
&emsp; 把tableEdit.js放在你的项目里面，然后使用模块加载的方式使用：

```javascript
layui.config({
    base: 'module/'
}).extend({
        tableEdit:'js/tableEdit'
}).use(['table','tableEdit'], function () {
    var tableEdit= layui.tableEdit;
    
});
```
#### 测试页面
- tableEdit.html 编辑测试页面
- verifyTest.html 数据校验测试页面


### 2.方法说明
方法名 | 描述 |
---          | ----
aopObj       | 获取一个table的aop代理对象方法,一张表对应一个aop对象。
on           | 事件注册
callbackFn   | 事件回调

### 3.参数说明

#### aopObj
参数      | 类型      | 是否必填 | 描述 |
---       | ---       | ---      | -----
cols      | array     | 是       | table.render(options)中options的cols属性值（单元格信息）。

#### on
参数      | 类型    | 是否必填 | 描述 |
---       | ---     | ---      | ----
event     | string  | 是       | 事件名称
callback  | function| 是       | 事件回调方法

#### callbackFn
参数      | 类型    | 是否必填 | 描述 |
---       | ---     | ---      | ----
event     | string  | 是       | 事件名称
params    | object  | 否       | 事件回调方法的参数


&emsp;**tableEdit配置格式**

- 在cols中加上config属性
```json
{"field":"name","event":"name","config":{}}
```
- 输入框          config:{"type":"input"}
- 带(+和-)输入框  config:{"type":"signedInput"}
- 单选下拉框      config:{"type":"select","data":params}
- 多选下拉框      config:{"type":"select","data":params,"enabled":true}
- 下拉框联动      config:{"type":"select","data":params,"cascadeSelectField":"name"}
- 日期选择框      config":{"type":"date","dateType":"date"}
- 日期选择框联动  config:{type:'date',dateType:'date',cascadeSelectField:'name'}

&emsp;**config说明**

属性               | 类型                 | 是否必填 | 描述 |
---                | ---                  | ---      | -----
type               | string               | 是       | 输入框：input 下拉框：select 时间选择框：date
data               | array                | 是       | 下拉框数据
enabled            | boolean              | 否       | 多选：true，单选：false，默认单选。
dateType           | string               | 否       | 时间格式 date:yyyy-MM-dd,datetime:yyyy-MM-dd HH:ss:mm,time:HH:ss:mm
cascadeSelectField | string               | 否       | 联动下拉框配置字段
verify             | object/boolean       | 否       | 字段数据验证

&emsp;**verify说明**

- verify => boolean true开启验证，false关闭验证
```json
{"field":"name","event":"name","config":{"type":"input","verify":true}}
```
- verify => object

属性               | 类型                         | 是否必填       | 描述    |
---                | -----                        | ---            | -----
type               | string                       | 否             | 内置验证类型
regx               | regExp/string/function       | 否             | 自定义正则类型
msg                | string                       | 否             | 自定义提示消息

&emsp;**内置type说明**
```json
{"field":"name","event":"name","config":{"type":"input","verify":{"type":"required"}}}
```

类型                   | 描述                
---                    | -----               
required               | 空值验证            
phone                  | 手机号码验证        
email                  | 邮箱验证             
url                    | url验证             
number                 | 数字验证(整数、小数)     
date                   | 日期时间验证        
identity               | 身份证验证          

&emsp;**自定义正则regExp说明**

类型                   | 描述   |
---                    | -----
function               | 自定义函数验证 需return验证结果  true成功 false失败
string                 | 字符串类型正则   
regExp                 | 正则表达式 

- function
```json
{"field":"name","event":"name","config":{"type":"input","verify":{"regx":function(data){
     //data为验证数据
     // true为验证成功  false为验证失败
      return true；
}}}}
```

string
```json
{"field":"name","event":"name","config":{"type":"input","verify":{"regx":"(^[-+]?\\d+$)|(^[-+]?\\d+\\.\\d+$)","msg":"请输入整数或者小数"}}}
```

regExp
```json
{"field":"name","event":"name","config":{"type":"input","verify":{"regx":/(^[-+]?\d+$)|(^[-+]?\d+\.\d+$)/,msg:"请输入整数或者小数"}}}
```
- 自定义提示
```json
{"field":"name","event":"name","config":{"type":"input","verify":{"type":"required","msg":"必填项不能为空"}}}
```
&emsp;**data格式**

```json
[
    {name:1,value:"测试1"},
    {name:2,value:"测试2"},
    {name:3,value:"测试3"},
    {name:4,value:"测试4"},
    {name:5,value:"测试5"}
]
```

### 6.效果图
![效果图](https://images.gitee.com/uploads/images/2020/0508/123901_092d3f62_1588195.gif "tableEdit.gif")
- 编辑数据验证
![啊啊](https://images.gitee.com/uploads/images/2020/0614/042738_bacccaf7_1588195.gif "tableEdit.gif")
- 提交数据验证
![哎哎哎](https://images.gitee.com/uploads/images/2020/0614/160955_3036ab33_1588195.gif "tableEdit.gif")