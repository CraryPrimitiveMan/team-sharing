title: Yii2 UrlManager 的使用及实现原理
speaker: Jacob Xue
transition: zoomin
theme: moon

[slide]

# Yii2 UrlManager 的使用及实现原理

<small>演讲者： Jacob Xue</small>

[slide]

## 主要内容
----
* UrlManager 是什么
* 为什么要用 UrlManager
* UrlManager 的实现原理
* UrlManager 怎么用

[slide]

## Application Structure
----
![application-structure](/images/jacob/application-structure.png)

[slide]

## UrlManager 是什么
----

[slide]

## UrlManager 是什么
----
* UrlManager 是 Yii 提供的一个应用组件
* UrlManager 继承于 Component
* UrlManager 组件由 `yii\web\UrlManager` 类定义

[slide]

## 为什么要用 UrlManager
----

[slide]

## 两个需求
----
* 开发者想要一种简洁的、集中的、统一的使用URL的方法
 + Web 开发中不可避免的要使用到 URL
 + 如果代码中有大量的诸如 `http://www.basic.com/post/view/100` 的代码，一是过于冗长，二是易出错且难排查，三是日后修改起来容易有遗漏
* 开发者想要一种高效的分派请求的方法
 + 我们知道，Yii 中所有的用户请求都是发送给入口脚本 index.php 来处理的
 + 需要有一种方法判断请求应当采用哪个 controller 的哪个 action 进行处理

[slide]

## Route(路由)和UrlManager(URL管理组件)
----
* Route，是指URL中用于标识处理用户请求的 module， controller ，action的部分，一般情况下由 r 查询参数来指定
 + 如 http://www.basic.com/index.php?r=post/view&id=100 ，表示这个请求将由PostController 的 actionView来处理
* UrlManager，包含解析请求以及根据路由规则生成 URL 两个功能
 + 解析用户的请求，并指派相应的 module， controller， action来进行处理
 + 根据预定义的路由规则，生成需要的 URL 返回给用户使用
 + 同时提供了一种美化(伪静态化) URL 的功能，使 URL 可以用一个比较整洁、美观的形式表现出来，如 http://www.basic.com/post/view/100 

[slide]

## UrlManager 的实现原理
----

[slide]

## UrlManager 概览
----
```php
class UrlManager extends Component
{
    // 是否启用URL美化功能，默认false不启用，实际使用中，特别是产品环境，一般都会启用
    public $enablePrettyUrl = false;
    // 是否启用严格解析，如启用，要求当前请求应至少匹配1个路由规则，否则认为是无效路由
    // 这个选项仅在 enablePrettyUrl 启用后才有效
    public $enableStrictParsing = false;
    // 保存所有路由规则的配置数组，不在这里保存路由规则的实例
    public $rules = [];
    // 指定续接在URL后面的后缀(如 .html)，仅在 enablePrettyUrl 启用时有效
    public $suffix;
    // 指定是否在URL中保留入口脚本 index.php
    public $showScriptName = true;
    // 指定不启用 enablePrettyUrl 情况下，URL中用于表示路由的查询参数，默认为 r
    public $routeParam = 'r';
    // 指定应用的缓存组件ID，默认为 cache，编译过的路由规则将通过这个缓存组件进行缓存
    // 如果不想使用缓存，设为false
    public $cache = 'cache';
    // 路由规则的默认配置，注意上面的 rules[] 中的同名规则，优先于这个默认配置的规则
    public $ruleConfig = ['class' => 'yii\web\UrlRule'];

    private $_baseUrl;
    private $_scriptUrl;
    private $_hostInfo;
    private $_ruleCache;
}
```

[slide]

----
```php
class UrlManager extends Component
{
    // urlManager 初始化
    public function init() {}
    // 增加新的规则
    public function addRules($rules, $append = true) {}
    // 创建路由规则
    protected function buildRules($rules) {}
    // 用于解析请求
    public function parseRequest($request) {}
    // 用于创建URL，相对URL（relative URL）
    public function createUrl($params) {}
    // 用于创建URL，绝对URL（absolute URL）
    public function createAbsoluteUrl($params, $scheme = null) {}

    public function getBaseUrl() {}

    public function setBaseUrl($value) {}

    public function getScriptUrl() {}

    public function setScriptUrl($value) {}

    public function getHostInfo() {}

    public function setHostInfo($value) {}
}
```

[slide]

## rules 的配置
----
* Yii2 的 rules 配置比较简单，一般来说，我们用到以下这几条就足够了:
```php
'rules' => [
    // 为路由指定一个别名简化网址
    'reg' => 'user/register',
    // 加id参数，这里用到了正则，\d+ 在表示至少一位的纯数字
    'article/<id:\d+>' => 'article/view',
    // 标准的controller/action显示
    '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
    // 加id参数
    '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
    // controller 和 action 进行严格限制
    '<controller:(post|comment)>/<id:\d+>/<action:(create|update|delete)>' => '<controller>/<action>',
    // 包含了 HTTP 方法限定，用于RESTful风格的 Web Server
    'DELETE <controller:\w+>/<id:\d+>' => '<controller>/delete',
    // 配置 Web Server ,接收 *.basic.com 域名的请求
    'http://<user:\w+>.basic.com/<lang:\w+>/profile' => 'user/profile',
]
```
* 想要熟练应用 rules 的配置并完全理解路由规则，需要多练练手，或是阅读一下 yii/web/UrlRule 的源码
* 如果有特殊需求，也可以根据源码新建一个自己规则类来进行处理

[slide]

----
```php
// urlManager 初始化
public function init()
{
    parent::init();
    if (!$this->enablePrettyUrl || empty($this->rules)) {
        return;
    }
    if (is_string($this->cache)) {
        $this->cache = Yii::$app->get($this->cache, false);
    }
    if ($this->cache instanceof Cache) {
        $cacheKey = __CLASS__;
        $hash = md5(json_encode($this->rules));
        if (($data = $this->cache->get($cacheKey)) !== false && isset($data[1]) && $data[1] === $hash) {
            $this->rules = $data[0];
        } else {
            $this->rules = $this->buildRules($this->rules);
            $this->cache->set($cacheKey, [$this->rules, $hash]);
        }
    } else {
        $this->rules = $this->buildRules($this->rules);
    }
}
```

[slide]

## UrlManager 怎么用
----

[slide]

## UrlManager 配置
----
* 在config下的web.php配置文件的 component 域中添加 urlManager 模块
```php
[
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                // ...
            ],
        ],
    ],
]
```

[slide]

## 总结
----
* 了解Yii的静态结构以及请求处理流程
* 了解 Route 和 UrlManager，会使用 UrlManager 处理Url
* 掌握对 UrlManager 中 rules 的配置

[slide]

## 参考
----
* http://www.digpage.com/urlmanager.html
* http://www.yiichina.com/doc/guide/2.0/runtime-routing