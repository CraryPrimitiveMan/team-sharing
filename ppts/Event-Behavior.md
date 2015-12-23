title: Event和Beahvior的使用和实现原理
speaker: Sarah.Zhang
url: https://github.com/CraryPrimitiveMan/team-sharing/tree/master/ppts
transition: move

[slide style="background-image:url('http://img1.3lian.com/2015/w17/45/d/64.jpg');"]
# <h1 style="color: transparent;">Events和Behaviors的使用和实现原理</h1>
## <h2 style="color: transparent;">演讲者： Sarah.Zhang</h2>

[slide style="background-image:url('http://img1.3lian.com/2015/w17/45/d/66.jpg'); color:black;"]
## <h2 style="color: transparent;">Events的使用及其原理</h2>
---
* 简介
* Events的绑定与解绑
* Events的触发及其顺序
* Events的级别

[slide style="background-image:url('http://img1.3lian.com/2015/w17/45/d/66.jpg'); color:black;"]
## <h2 style="color: transparent;">Events简介</h2>
----
* 事件实现了将自定义代码注入到特定的执行点。
* 大致执行过程：定义事件 -> 绑定事件 -> 触发事件。
* 绑定事件后，一旦事件被触发，自定义代码便会自动执行。

* <p style="color:brown;">[相关方法：on绑定，trigger触发，off解绑]</p>
[slide style="font-size: 20px; background-image:url('http://img1.3lian.com/2015/w17/45/d/66.jpg'); color:black;"]
###<h3 style="color: transparent;">Events的绑定</h3>
----
* 在任意控制器和模型中可以直接使用下面方式来绑定事件：
```php
   $this->on($name, $handler, $data = null, $append = true)
```
* Component中维护了一个handler数组，用来保存绑定的handler， 在 on 方法中将$handler写入一个$_event[$name][$handler, $data]数组中。
```php
private _events = [];
// 绑定过程就是将handler写入_event[]
public function on($name, $handler, $data = null, $append = true)
{
    $this->ensureBehaviors();
    if ($append || empty($this->_events[$name])) {
        $this->_events[$name][] = [$handler, $data];
    } else {
        array_unshift($this->_events[$name], [$handler, $data]);
    }
}
```

[slide style="font-size: 20px; background-image:url('http://img1.3lian.com/2015/w17/45/d/66.jpg'); color:black;"]
### <h3 style="color: transparent;">Events的绑定</h3>
* $handler即为事件发生时要被调用的函数。可以使用以下回调函数之一:
 * 全局函数
 * 一个包含modal名称和方法名的数组
 * 一个包含对象和方法名的数组
 * 一个匿名函数
```php
$person = new Person();
$processer = new Processer();
// 处理器是全局函数
$person->on(Person::ACTION_SEND, 'function_name');
// 处理器是对象方法 or 静态类方法
$person->on(Person::ACTION_SEND, [Processer::className(), 'function_name']);
// 处理器是匿名函数
$person->on('anonymous', function($event) {
        echo 'This is anonymous function.<br>';
});
```

[slide style="font-size: 20px; background-image:url('http://img1.3lian.com/2015/w17/45/d/66.jpg'); color:black;"]
### <h3 style="color: transparent;">Events的触发</h3>
----
* 大多数事件通过正常工作流来触发。触发一个事件，使用 trigger() 方法，在被附属了handler的组件上调用。
```php
$this->trigger($name, Event $event = null);
//trigger的参数可以在第二个参数设置，如果没有设置，系统会默认创建一个Event对象
```

[slide style="font-size: 20px; background-image:url('http://img1.3lian.com/2015/w17/45/d/66.jpg'); color:black;"]
###<h3 style="color: transparent;">Events的处理顺序</h3>
----
* 一个事件绑定多个处理程序，当事件被触发时，会默认按照它们被绑定的顺序执行。
* 如果某个handler需要停止后续handler的调用，可以设置Event::$handler属性的$event参数为true。
```php
$person->on('order2', function($event) {
    echo 'This is order 2.';
    /*设置参数$handled, 停止执行后续处理程序*/
    $event->handled = true;
});
```
* 默认情况下，一个新的handler被绑定在已经存在的事件队列后面。
* 可以调用Conponent::on(), 给第四个参数$append传递false， 以便这个新事件可以最先被调用。
```php
$this->on('order3', [Processer::className(), 'push'], '', false);
```
[slide style="font-size: 20px; background-image:url('http://img1.3lian.com/2015/w17/45/d/66.jpg'); color:black;"]
##<h2 style="color: transparent;">Events解绑</h2>
----
```php
Component::off($name, $handler)
```
* 注意：<span style="color:brown;">一般情况不能解绑一个匿名函数。</span>除非当他们被绑定时，你将它存放在某个变量。假定将匿名函数存放在了一个变量里。如下：
```php
$anonymous = function($event) {
    echo 'anonymous<br><br>';
};
$person->on('order3', $anonymous);
$person->off('order3', $anonymous);
```
* 要解除所有的handlers，使用调用Component::off()，不传递第二个参数即可。
```php
$person->off('order3');
```

[slide style="font-size: 20px; background-image:url('http://img1.3lian.com/2015/w17/45/d/66.jpg'); color:black;"]
###<h3 style="color: transparent;">Class-Level-Events</h3>
----
```php
// Component中的$_event[] 数组
$_event[$eventName][] = [$handler, $data];

// Event中的$_event[] 数组
$_event[$eventName][$calssName][] = [$handler, $data];
```
* 通过Event::on()静态方法，绑定类级别handler。
```php
Event::on(Person::className(), Person::ACTION_SEND, [$processer, 'push']);
```
* 一旦类的实例或它的某个子类触发事件，该handler将被调用。
* 注意，$event->handled = true 只能终止同一级别handler的处理，没办法跨级别进行干预。

[slide style="font-size: 20px; background-image:url('http://img1.3lian.com/2015/w17/45/d/66.jpg'); color:black;"]
###<h3 style="color: transparent;">Class-Level-Events</h3>
----
* 类级别事件，总是在实例事件后触发。
* 类级别事件的触发，应使用 Event::trigger(), 不会触发实例级别的事件。
* 注意: $event->sender 在实例级别事件中，指向触发事件的实例，而在类级别事件中， 指向的是触发事件的类。
* 因为类级处理器能对该类或子类的所有触发器做出回应，因此要谨慎使用，尤其是基层类中。
```php
// 解绑单个
Event::off(Person::className(), Person::ACTION_SEND, $handler);
// 解绑所有
Event::off(Person::className(), Person::ACTION_SEND);
```

[slide style="font-size: 20px; background-image:url('http://img1.3lian.com/2015/w17/45/d/66.jpg'); color: black;"]
###<h3 style="color: transparent;">全局事件</h3>
----
* 全局事件，本质上只是一个实例事件。利用Application实例在整个应用的生命周期中全局可访问的特性，来实现全局事件, 也可以将他绑定在任意全局可访问的的Component上。
* 创建全局事件，一个事件调用者调用单例组件的trigger()方法而非调用自身的trigger()方法。事件处理器被绑定在单例组件上。如：
```php
Yii::$app->on('app', function ($event) {
    echo get_class($event->sender);
});
Yii::$app->trigger('app');
```
* 全局事件一个最大优势在于：在任意需要的时候，都可以触发全局事件，也可以在任意必要的时候绑定，或移除一个事件。

[slide style="background-image:url('http://img1.3lian.com/2015/w17/45/d/66.jpg'); color:black;"]
# <h1 style="color: transparent;">Beahvior</h1>
---
* 简介
* Behaviors要素
* Behaviors的事件处理
* Behaviors的绑定和解除

[slide style="background-image:url('http://img1.3lian.com/2015/w17/45/d/66.jpg'); color:black;"]
## <h2 style="color: transparent;">简介</h2>
---
* Behaviors, 也叫mixins (A class that contains a combination of methods from other classes.)
* 对类添加特性的方法
 * 直接修改这个类的代码，添加一些属性和方法
 * 派生，通过子类来扩展
 * 通过Behaviors为类绑定，使类具有行为本身所定义的属性和方法

[slide style="background-image:url('http://img1.3lian.com/2015/w17/45/d/66.jpg'); color:black;"]
##<h2 style="color: transparent;">Behavior Class</h2>
----
* $owner: 成员变量，用于指向行为的依附对象
* attach(): 用于将行为与Component绑定起来
* events(): 用于表示行为所有要响应的事件
* deatch(): 用于将行为从Component上解除

[slide style="background-image:url('http://img1.3lian.com/2015/w17/45/d/66.jpg'); color:black;"]
###<h3 style="color: transparent;">Behaviors的绑定</h3>
* 关系到行为绑定过程函数：
 * behaviors(): 返回一个数组用于描述行为
 * ensureBehaviors(): 确保 behaviors() 中所注入的行为已经进行了绑定
 * attachBehaviorInternal(): 对行为进行绑定
 * attach(): 设置$owner, 通过on()将$handler绑定到所依附的类上

[slide style="font-size: 20px; background-image:url('http://img1.3lian.com/2015/w17/45/d/66.jpg'); color:black;"]
###<h3 style="color: transparent;">Behaviors的绑定</h3>
----
* 静态绑定：覆写Component::beahvior()方法，返回行为配置列表。
```php
    public function behaviors()
    {
        return [
            // 匿名行为，只有行为类名
            MyBehavior::className(),
            // 命名行为，只有行为类名
            'myBehavior2' => MyBehavior::className(),
            // 匿名行为，配置数组
            [
                'class' => MyBehavior::className(),
                'prop' => 'value',
            ],
            // 命名行为，配置数组
            'myBehavior4' => [
                'class' => MyBehavior::className(),
                'prop' => 'value',
            ]
        ];
    }
```

[slide style="font-size: 20px; background-image:url('http://img1.3lian.com/2015/w17/45/d/66.jpg'); color:black;"]
###<h3 style="color: transparent;">Behaviors的绑定</h3>
----
* 动态绑定：在对应组件里调用Component::attachBehavior()方法。
```php
// Step 1: 定义一个将绑定行为的类(MyClass extends Component)
// Step 2: 定义一个行为类(MyBehavior extends Behavior)
class SiteController extends Controller
{
    // Step 3: 创建以上两个类的实例
    $person = new Person();
    $myBehavior = new MyBehavior();

    // Step 4: 将行为绑定到类上
    $person->attachBehavior('myBehavior', $myBehavior);

    // Step 5: 访问行为中公共属性和方法，就和访问类自身的属性和方法一样
    echo $person->property1;
    echo $person->method1();
}
```
<p style="color:brown; text-align: left;">[注意：private和protected的属性和方法是无法访问的。]</p>

[slide style="background-image:url('http://img1.3lian.com/2015/w17/45/d/66.jpg'); color:black;"]
###<h3 style="color: transparent;">Behaviors的绑定</h3>
----
* <p>动态绑定：</p>
```php
// 可附加行为对象、行为类，以及配置数组等
$person->attachBehavior('myBehavior', [
    'class' => MyBehavior::className(),
    'prop1' => 'value1',        // 可为其属性赋值,注意必须是已定义属性
    'prop2' => 'value2',
]);
// 可同时附加多个行为
$person->attachBehaviors([
    'myBeh' => $myBehavior,         // 命名行为
    'myBeh1' => new Mybehavior,
    MyBehavior::className()         // 匿名行为
]);
```

[slide style="font-size: 20px; background-image:url('http://img1.3lian.com/2015/w17/45/d/66.jpg'); color:black;"]
###<h3 style="color: transparent;">事件处理</h3>
----
* 行为响应对应组件的事件触发，覆写Behavior->events()方法
* 该方法返回事件列表和响应的处理器,指定事件处理器格式：
 * 指向行为类的方法名的字符串
 * 对象或类名和方法名的数组
 * 匿名方法
* ```php
class MyBehavior extends Behavior
{
    public function events()
    {
        return [
            'func1' => 'sing',
            'func2' => [$processer, 'push'],
            'func3' => function($event) {
                echo 'This is func3';
            }
        ];
    }
}
```

[slide style="background-image:url('http://img1.3lian.com/2015/w17/45/d/66.jpg'); color:black;"]
###<h3 style="color: transparent;">Behaviors的解除</h3>
* 移除Behaviors
 * 使用Component::detachBeahvior('xxx')移除相关联的命名行为
 * 使用Component::detachBehaviors()移除所有行为
* ```php
public function detachBehavior($name)
{
    $this->ensureBehaviors();
    if (isset($this->_behaviors[$name])) {
        $behavior = $this->_behaviors[$name];
        unset($this->_behaviors[$name]);
        $behavior->detach();
        return $behavior;
    } else {
        return null;
    }
}
```

[slide style="font-size: 20px; background-image:url('http://img1.3lian.com/2015/w17/45/d/66.jpg'); color:black;"]
###<h3 style="color: transparent;">Behaviors 与 Traits</h3>
* 区别
 * 从本质上讲，Behaviors 属于类，可以通过继承来实现代码的复用；而 Traits 只是php的一种实现代码复用的语法，不支持继承。
 * Behaviors 可以动态地绑定、解除，而不必要对类进行修改；而 Traits 必须在类在使用 use 语句，即需要对类进行修改。
 * Behaviors 是可配置的而 Traits 不能。
 * Behaviors 以响应事件来自定义组件的代码执行。
 * 当出现命名冲突时，Behaviors 会自行排除冲突； 而 Traits 需要认为进行干预排除一些命名冲突。
* Traits 优势
 * Traits 比行为在效率上要高一点，因为 Behaviors 是对象，需要时间和空间进行分配。
 * Traits 是PHP的语法，因此，IDE的支持要好一些。


[slide style="background-image:url('http://img1.3lian.com/2015/w17/45/d/66.jpg'); color:black;"]
##<h2 style="color: transparent;">总结</h2>
* 使用 Events 和 Behaviors，必须先把它绑定到Component类或其子类上。
* Events： 绑定->解绑，注意匿名函数的解绑(只能对已命名的匿名函数进行解绑)。
* Events: 绑定时, on()方法参数$append控制同名事件绑定的顺序，绑定函数中$event->handled控制同名的后续事件是否执行。
* Behaviors: 绑定->解绑, 可以通过自检访问行为的<span style="color: brown">公共成员变量</span>或<span style="color: brown">getter和setter方法定义的属性</span>
* Behaviors: <span style="color: brown">绑定时，后绑定的行为会取代已经绑定的同名行为。多个行为中属性或方法出现命名冲突时，自动使用先绑定行为中的属性或方法。</span>

[slide style="background-image:url('http://img1.3lian.com/2015/w17/45/d/66.jpg'); color:black;"]
##<h2 style="color: transparent;">相关链接</h2>

* Events:
 * http://www.yiiframework.com/doc-2.0/guide-concept-events.html
 * http://www.digpage.com/event.html
* Behaviors:
 * http://www.yiiframework.com/doc-2.0/guide-concept-behaviors.html
 * http://www.digpage.com/behavior.html
