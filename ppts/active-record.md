title: Yii2 ActiveRecord 的使用及实现原理
speaker: Zack Sun
url: https://github.com/ksky521/nodePPT
transition: cards
theme: dark
[slide]

# Yii2 ActiveRecord 的使用及实现原理
## 演讲者： Zack Sun

[slide data-transition="vertical3d"]
## What will be discussed
+ ActiveRecord 是什么
+ Yii里的ActiveRecord
+ 有用的功能

[slide data-transition="vertical3d"]
## ActiveRecord 是什么
+ 属于ORM，由Rails最早提出
+ 表映射到记录，记录映射到对象，字段映射到对象属性
+ 能够很大程度的快速实现模型的操作，而且简洁易懂

[slide data-transition="vertical3d"]
## ActiveRecord 的主要思想
+ 每一个数据库表对应创建一个类,类的每一个对象实例对应于数据库中表的一行记录; 通常表的每个字段在类中都有相应的Field
+ ActiveRecord同时负责把自己持久化. 在ActiveRecord中封装了对数据库的访问, 即CRUD

[slide data-transition="vertical3d"]
## Yii2.0中的ActiveRecord
+ class `ActiveRecord` extends `BaseActiveRecord`
+ abstract class `BaseActiveRecord` extends `Model` implements `ActiveRecordInterface`

[slide data-transition="vertical3d"]
## 里面有什么
+ `ActiveRecord`: 重写CRUD的基础操作,对于sql而言，添加transaction
+ `BaseActiveRecord`: 监控Attributes的变化及CRUD行为
+ `Model`: *rules*, *scenarios*, *validators*

[slide data-transition="vertical3d"]
## 有用的功能
+ `validate()`: *rules*, *scenarios*, *validators*
+ `load()`
+ CRUD/*transactions*

[slide data-transition="vertical3d"]
## validate
+ 属于Model
+ 系统自带的validator会把有错误的属性和错误信息加入在$errors数组里面（自己写的validator也应该这么做）
+ 在CUD操作前验证（`EVENT_BEFORE_*`之前，所以如果属性有错误，则不会执行before函数）
+ 触发`EVENT_BEFORE_VALIDATE`和`EVENT_AFTER_VALIDATE`

[slide data-transition="vertical3d"]
## validate的过程
```php
$scenarios = $this->scenarios();
$scenario = $this->getScenario();
if (!isset($scenarios[$scenario])) {
    throw new InvalidParamException("Unknown scenario: $scenario");
}

if ($attributeNames === null) {
    $attributeNames = $this->activeAttributes();
}

foreach ($this->getActiveValidators() as $validator) {
    $validator->validateAttributes($this, $attributeNames);
}
```
+ `Model`和`Validator`类相关

[slide data-transition="vertical3d"]
## 解释
+ 通过`activeAttributes()`取得当前`$scenario`里包含的属性
+ 通过`getActiveValidators()`取得`rules()`里面配置的规则，为每个属性创建相应的`Validator`
+ 通过`validateAttributes()`验证属性

[slide data-transition="vertical3d"]
## *scenarios*
+ 在`scenarios()`函数中设置
+ 注意父类是否已经设置了scenario，可以选择覆盖或者合并
```php
return [
    'login' => ['attribute11', 'attribute12', ...],
    'register' => ['attribute21', 'attribute22', ...],
    ...
]
```

[slide data-transition="vertical3d"]
## *rules*
+ 在`rules()`函数中设置
+ 每一个数组项的第二个参数必须是Validator的实例或者当前类中的函数
+ `on`/`except`规定当前验证规则应用的`scenario`，没有则所有`scenario`中都使用
```php
return [
    // built-in "required" validator that is used in all scenarios
    [['username', 'password'], 'required'],
    // built-in "string" validator customized with "min" and "max" properties that is used expect "login" scenario only
    ['username', 'string', 'min' => 3, 'max' => 12, 'expect' => 'login'],
    // built-in "compare" validator that is used in "register" scenario only
    ['password', 'compare', 'compareAttribute' => 'password2', 'on' => 'register'],
    // an inline validator defined via the "authenticate()" method in the model class
    ['password', 'authenticate', 'on' => 'login'],
    // a validator of class "DateRangeValidator"
    ['dateRange', 'DateRangeValidator'],
];
```

[slide data-transition="vertical3d"]
## *scenario* 与 *rule* 之前的联系
+ 是两种不同的维度来确保当前`scenario`数据的正确性。
+ 在创建每一条`rule`的`Validator`时，通过`on`和`expect`确定是否创建
+ 创建的`Validator`实例会有`rule`中属性的数组，在验证时，取`scenario`与`rule`的交集

[slide data-transition="vertical3d"]
## Yii提供的Validator中比较有用的
+ unique
  ```php
  [['name'], 'unique', 'targetAttribute' => ['grade', 'name']]
  ```
+ safe: 禁止copy行为覆盖，比如表中自增的id
+ match: 正则表达式

[slide data-transition="vertical3d"]
## *errors*
+ addErrors()/getErrors()
```php
[
    'username' => [
        'Username is required.',
        'Username must contain only word characters.',
    ],
    'email' => [
        'Email address is invalid.',
    ]
]
```

[slide data-transition="vertical3d"]
## *load*
+ setAttributes()
```php
if (is_array($values)) {
    $attributes = array_flip($safeOnly ? $this->safeAttributes() : $this->attributes());
    foreach ($values as $name => $value) {
        if (isset($attributes[$name])) {
            $this->$name = $value;
        } elseif ($safeOnly) {
            $this->onUnsafeAttribute($name, $value);
        }
    }
}
```
  - array_flip(): 交换数组中的键和值的位置
+ safeAttributes(): 取当前`scenario`包含的属性作为`safeAttributes`

[slide data-transition="vertical3d"]
## CRUD/*transactions*
+ `OP_ALL`/`OP_DELETE`/`OP_INSERT`/`OP_UPDATE`
+ 设置CUD操作在`scenario`中是否是事务操作
```php
public function transactions() {
    return [
        'login' => self::OP_INSERT,
        'register' => self::OP_INSERT | self::OP_UPDATE | self::OP_DELETE,
        // the above is equivalent to the following:
        // 'api' => self::OP_ALL,
    ];
}
```

[slide data-transition="vertical3d"]
##　为什么要设置 *transactions*
+ 与`before`和`after`函数或事件联用，使这些操作在同一`transaction`中
+ 使用统一的框架，使代码共容易维护

[slide data-transition="vertical3d"]
## 实例

[slide data-transition="vertical3d"]
## 总结
+ `scenario`可以为同一表设置不同的场景，区分不同`scenario`对属性的要求。
+ `rule`依靠于yii自带的`Validator`，同时yii也提供了一些常用有效的验证。
+ 在重写CRUD方法时，调用父类的方法或者手动触发相关event
+ 在CRUD之前或之后对其他类的操作，应该绑在event中，同时在`transactions()`中设置此操作为事务操作
