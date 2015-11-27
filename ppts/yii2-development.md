title: Yii2的使用
speaker: Harry Sun
url: https://github.com/CraryPrimitiveMan/team-sharing
transition: slide

[slide]

# Yii2的使用
<small>演讲者：[@Harry Sun](https://github.com/CraryPrimitiveMan)</small>

[slide]

## Yii的简介
----
* Yii 的作者是美籍华人“薛强”，他原是 Prado 核心开发成员之一。2008 年薛强另起炉灶，
开发了 Yii 框架。
* Yii 是目前比较优秀的 PHP 框架之一，它的支持的特性包括：MVC、DAO/ActiveRecord、
I18N/L10N、caching、AJAX 支持、用户认证和基于角色的访问控制、脚手架、输入验证、部
件、事件、主题化以及 Web 服务等。
* Yii 当前有两个主要版本：1.1 和 2.0。 1.1 版是上代的老版本，现在处于维护状态。2.0 版是一个完全重写的版本，采用了最新的技术和协议，包括依赖包管理器 Composer、PHP 代码规范 PSR、命名空间、Traits（特质）等等。 2.0 版代表新一代框架，是未来几年中我们的主要开发版本。

[slide]

## Yii2 的新特性
----
* 使用[Composer](http://www.phpcomposer.com/)
* PHP 代码规范 PSR
* 命名空间
* Traits

[slide]

## 命名空间
----
* 什么是命名空间？从广义上来说，命名空间是一种封装事物的方法。
* 在PHP中，命名空间用来解决在编写类库或应用程序时创建可重用的代码如类或函数时碰到的两类问题：
 * 用户编写的代码与PHP内部的类/函数/常量或第三方类/函数/常量之间的名字冲突。
 * 为很长的标识符名称(通常是为了缓解第一类问题而定义的)创建一个别名（或简短）的名称，提高源代码的可读性。

[slide]

## Traits
----
* 自 PHP 5.4.0 起，PHP 实现了代码复用的一个方法，称为 traits。
* Traits 是一种为类似 PHP 的单继承语言而准备的代码复用机制。Trait 为了减少单继承语言的限制，使开发人员能够自由地在不同层次结构内独立的类中复用方法集。Traits 和类组合的语义是定义了一种方式来减少复杂性，避免传统多继承和混入类（Mixin）相关的典型问题。
* Trait 和一个类相似，但仅仅旨在用细粒度和一致的方式来组合功能。Trait 不能通过它自身来实例化。它为传统继承增加了水平特性的组合；也就是说，应用类的成员不需要继承。

[slide]

## [Traits](http://www.cnblogs.com/CraryPrimitiveMan/p/4162738.html) Example
----
```php
class Base {
    public function sayHello() {
        echo 'Hello ';
    }
}

trait SayWorld {
    public function sayHello() {
        parent::sayHello();
        echo 'World!';
    }
}

class MyHelloWorld extends Base {
    use SayWorld;
}

$o = new MyHelloWorld();
$o->sayHello();
```
以上例程会输出：Hello World!
优先顺序是来自当前类的成员覆盖了 trait 的方法，而 trait 则覆盖了被继承的方法。

[slide]

## Yii2 的 MVC
----
* Model
* View
* Controller

[slide]

## Yii2 的 执行流程
----
* 加载autoload方法，为类的实例化做准备，实例化yii/web/Application类
* 解析Request请求，找到相应的Controller和Action，并执行
* 渲染页面，先渲染layout，最后渲染相应的view

[slide]

## Yii2 RestController 路由的配置
----
```php
'urlManager' => [
    'enablePrettyUrl' => true,
    'enableStrictParsing' => true,
    'showScriptName' => false,
    'rules' => [
        'POST api/<controller:[\w-]+>s' => '<controller>/create',
        'api/<controller:[\w-]+>s' => '<controller>/index',
        'PUT api/<controller:[\w-]+>/<id:[\w\d,]{24}>' => '<controller>/update',
        'DELETE api/<controller:[\w-]+>/<id:[\w\d]{24}(,[\w\d]{24})*>' => '<controller>/delete',
        'api/<controller:[\w-]+>/<id:[\w\d,]{24}>' => '<controller>/view'
    ],
],
```

[slide]

## Yii2 RestController 的使用以及项目中的重写
----

* 必须绑定一个model
* IndexAction/CreateAction/UpdateAction/DeleteAction

[slide]


## Yii2 封装 Component
----

* mail component
* static page service

[slide]


## Yii2 使用 Behavior
----

* 其效果与 Traits 类似
* ControllerBehavior

[slide]

## Yii2 在我们项目中的使用

[slide]

## 好书推荐

代码大全
