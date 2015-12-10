title: sass.md
speaker: Flora Li
url: ../md/sass.md

[slide style="background-image:url('/img/bg1.png')"]

# SASS 的相关函数
## 演讲者: Flora Li

[slide style="background-image:url('/img/bg1.png');color: #000;"]
#简介
* SASS是Syntactically Awesome StyleSheets的缩写
* CSS预处理器语言(用一种专门的编程语言，进行 Web 页面样式设计，然后再编译成正常的 CSS 文件，以供项目使用。CSS 预处理器为 CSS 增加一些编程的特性，无需考虑浏览器的兼容性问题)
* Sass并不是css的替代品，它只是让css变得更加高效、可维护，也不必去修改编译后的css文件
* CSS 预处理器语言，目前比较流行的Sass、LESS、Stylus、Turbine、Swithch CSS、CSS Cacheer、DT CSS

[slide style="background-image:url('/img/bg1.png');color: #000;"]
# Sass的安装及使用

[subslide]
* 安装Ruby,然后安装Sass(gem install sass) {:&.moveIn}
* 将input.scss编译为output.css文件: sass input.scss output.css
* 指定输出风格命令: sass --style expanded input.scss:output.css
* 输出样式的风格可以有四种选择，默认为nested
    * nested：嵌套缩进的css代码 {:&.rollIn}
    * expanded：展开的多行css代码
    * compact：简洁格式的css代码
    * compressed：压缩后的css代码
* 实时监听单个文件命令: sass --watch --style expanded loader.scss:loader.css
* 实时监听多个文件命令: sass --watch (sass文件夹名):(css文件夹名)
[/subslide]

[slide style="background-image:url('/img/bg1.png'); color: #000;"]
# Sass有两种后缀名(Sass、Scss)
* 主要区别在书写格式上
    * sass文件是缩进式的写法，没有括号, 对格式要求比较严谨，末尾不能有分号
    * scss文件的写法和css一致
* Sass与Scss之间的转换
    * 将Sass转换为Scss: sass-convert style.sass style.scss
    * 将Scss转换为Sass: sass-convert style.scss style.sass

[slide style="background-image:url('/img/bg1.png'); color: #000;"]
# 注释
* <div style="float: left;">标准的CSS注释 /* comment */ ，会保留到编译后的文件</div>
* <div style="float: left;">单行注释 // comment，只保留在SASS源文件中，编译后被省略</div>

[slide style="background-image:url('/img/bg1.png'); color: #000;"]
# @import
* css有一个@import规则，它允许在一个css文件中导入其他css文件。然而只有执行到@import时，浏览器才会去下载其他css文件，这导致页面加载起来特别慢
* Sass也有一个@import规则，但Sass的@import规则在生成css文件时就把相关文件导入进来。这意味着所有相关的样式被归纳到了同一个css文件中，而无需发起额外的下载请求。
* 在少数几种情况下会被编译成 CSS 的规则, 如下：
    1. 如果文件的扩展名是 .css => @import "foo.css"
    2. 如果文件名以 http:// 开头 => @import "http://foo.com/bar"
    3. 如果文件名是 url() => @import url(foo)
    4. 如果包含了任何媒体查询（media）=> @import "foo" screen
* 前提如果我们编译监听的是文件夹, 有一个 SCSS文件需要引入， 但是你又不希望它被编译为一个 CSS 文件， 可以在文件名前面加一个下划线，像往常一样引入。_text.scss => @import 'text'


[slide style="background-image:url('/img/bg1.png');color: #000;"]
# @debug  @warn  @error
* 用来调试的，Sass 代码在编译错时，在命令终端会输出你设置的提示 Bug

#sass -i
命令行使用函数

[slide style="background-image:url('/img/bg1.png'); color: #000;"]

# Sass的基本特性-运算
* 加 +、减 -、乘 *、除 /和取模 %
* 同种单位类型不同单位间可以做转换
* 乘法 * 只允许一个有单位
* 除法 / 用常量进行一次计算时,运算要用括号包起来, 否则当成字符串, 其他不需要
* 通过括号来修改他们的运算先后顺序
* 可以用于变量计算,数字运算,颜色运算,字符运算
* 所有算数运算都支持颜色值，并且是分段运算的。也就是说，红、绿和蓝各颜色分段单独进行运算
* 字符运算是否带 '' 以 + 左侧的字符串是否加 '' 为准

[slide style="background-image:url('/img/bg1.png');color: #000;"]
# Sass的控制命令
* @if:@if    @else    if    @else {:&.moveIn}
* if($condition,$if-true,$if-false) Miscellaneous(三元条件)函数.有两个值，当条件成立返回一个值，当条件不成立时返回另一个值：
* @for: @for $i from <start> through / to <'end'> 区别:to不包含<'end'>
* @while: @while 条件 {}
* @each: @each $var in <'list'> / @each $key,$value in <'list'>
    * {可以使用插值#{}}

[slide style="background-image:url('/img/bg1.png');color: #000;"]
# Sass的字符串函数
* unquote($string)：函数只能删除字符串最前和最后的引号(双引号或单引号),如果字符没有带引号，返回的将是字符串本身 {:&.moveIn}
* quote($string): 函数主要用来给字符串添加引号.如果字符串自身带有引号会统一换成双引号"".
而且字符串中间有单引号、空格、特殊符号(除了-和_)时,需要用单引号或双引号将字符串括起,否则编译的时候将会报错.
* To-upper-case($string): 函数将字符串小写字母转换成大写字母
* To-lower-case($string): 函数将字符串转换成小写字母

[slide style="background-image:url('/img/bg1.png');color: #000;"]
# Sass的数字函数
* percentage($value)：将一个不带单位的数转换成百分比值
* round($value)：将数值四舍五入，转换成一个最接近的整数
* ceil($value)：将大于自己的小数转换成下一位整数
* floor($value)：将一个数去除他的小数部分
* abs($value)：返回一个数的绝对值
* min($numbers…)：找出几个数值之间的最小值
* max($numbers…)：找出几个数值之间的最大值
* random(): 获取[0,1)随机数

[slide style="background-image:url('/img/bg1.png');color: #000;"]
# 列表函数
* length($list)：返回一个列表的长度值
* nth($list, $n)：返回一个列表中指定的某个标签值, $n 必须是大于 0 的整数
* join($list1, $list2, [$separator])：将两个列给连接在一起，变成一个列表,只能两个,可以多个join,$separator有comma和space两个值
* append($list1, $val, [$separator])：将某个值放在列表的最后
* zip($lists…)：将几个列表结合成一个多维的列表,每个单一的列表个数值必须是相同的
* index($list, $value)：返回一个值在列表中的位置值,不存在是false

[slide style="background-image:url('/img/bg1.png');color: #000;"]
# 列表函数
* zip(1px 2px 3px,solid dashed dotted,green blue red)
* 执行过程
---
| List | nth(1) | nth(2) | nth(3) |
|:-------|:------:|-------:|--------
|    List1   |      1px     |      2px     |      3px     |
|    List2   |      solid   |      dashed  |     dotted   |
|    List3   |      green   |      blue    |      red     |
* 执行结果: 1px solid green, 2px dashed blue, 3px dotted red


[slide style="background-image:url('/img/bg1.png');color: #000;"]
# Introspection函数(判断型函数)
* type-of($value)：返回一个值的类型,返回值：number(数值型),string(字符串型),bool(布尔型),color(颜色型)
* unit($number)：返回一个值的单位,乘除可以返回多单位组合,加减除了px 与 cm、mm组合, 返回px, 其他报错
* unitless($number)：判断一个值是否带有单位，不带单位返回的值为true，带单位返回的值为 false
* comparable($number1, $number2)：判断两个值是否可以做加、减和合并,可以返回true，不可以返回false

[slide style="background-image:url('/img/bg1.png');color: #000;"]
#Map
* map-get($map,$key): 根据给定的key值，返回map中相关的值
* map-keys($map): 返回map中所有的key
* map-values($map): 返回map中所有的value, 如果有相同的value也将会全部获取出来。
* map-has-key($map,$key): 根据给定的key值判断map是否有对应的value值，如果有返回true，否则返回 false
* map-merge($map1,$map2): 将两个map合并成一个新的map,如果 $map1 和 $map2 中有相同的 $key 名，那么$map2 中的 $key 会取代 $map1 中的
* map-remove($map,$key): 从map中删除一个key，返回一个新map
* keywords($args): 动态创建 map 的函数, 可以通过混合宏或函数的参数变创建 map, 参数也是成对出现, 其中 $args 变成 key(会自动去掉$符号)，而 $args 对应的值就是value。

[slide style="background-image:url('/img/bg1.png');color:#000;"]
# 颜色函数 - RGBA函数
* R红色值(正整数/百分数)、 G绿色值(正整数/百分数)、 B蓝色值(正整数/百分数)、 A透明度(0~1)
* rgba($red,$green,$blue,$alpha): 将一个rgba颜色转译出来，和未转译的值一样
* rgba($color,$alpha): 将一个颜色转换成rgba颜色
* red($color)： 从一个颜色中获取其中红色值
* green($color)： 从一个颜色中获取其中绿色值
* blue($color)： 从一个颜色中获取其中蓝色值
* mix($color-1,$color-2,$weight): $color-1 和 $color-2 指的是你需要合并的颜色(任何表达式|颜色变量),$weight 为合并的比例(0~100%), 默认50%. 如果指定的比例是 25%，这意味着第一个颜色所占比例为 25%，第二个颜色所占比例为75%
* invert($color)： 根据颜色的R、G和B单独进行反相，而透明度不变。

[slide style="background-image:url('/img/bg1.png');color: #000;"]
# 颜色函数 - HSLA函数
1. H色相(正整数)、S饱和度(百分数)、L亮度(百分数)、A透明度(0~1)
2. hue($color)： 从一个颜色中获取色相（hue）值, 红色0/360, 绿色120, 蓝色240；
3. saturation($color)： 从一个颜色中获取饱和度（saturation）值
4. lightness($color)： 从一个颜色中获取亮度（lightness）值
5. hsl($hue,$saturation,$lightness)： 通过色相（hue）、饱和度(saturation)和亮度（lightness）的值创建一个颜色
6. hsla($hue,$saturation,$lightness,$alpha)： 通过色相（hue）、饱和度(saturation)、亮度（lightness）和透明（alpha）的值创建一个颜色

[slide style="background-image:url('/img/bg1.png');color: #000;"]
# 颜色函数 - HSLA函数
1. adjust-hue($color,$degrees)： 改变颜色的色相值，$degrees单位deg,色相(-360,360) 之间，负值逆时针转，正值顺时针转。25deg相当于在355deg色相基础上增加30deg
2. complement($color)： 返回一个补充色，相当于adjust-hue($color,180deg)
3. lighten($color,$amount)： 改变颜色的亮度值，让颜色变亮；$amount百分比
4. darken($color,$amount)： 改变颜色的亮度值，让颜色变暗
5. saturate($color,$amount)： 改变颜色的饱和度值，让颜色更饱和
6. desaturate($color,$amount)： 改变颜色的饱和度值，让颜色减少饱和
7. grayscale($color)： 将一个颜色变成灰色，相当于desaturate($color,100%);


[slide style="background-image:url('/img/bg1.png');color: #000;"]
# 颜色函数 - Opacity 函数
1. alpha($color) /opacity($color)：获取颜色透明度值
2. rgba($color, $alpha)：改变颜色的透明度值
3. opacify($color, $amount) / fade-in($color, $amount)：使颜色更不透明；$amount(0,1)
4. transparentize($color, $amount) / fade-out($color, $amount)：使颜色更加透明

[slide style="background-image:url('/img/bg1.png');color: #000;"]
# @media
* 有点类似 JS 的冒泡功能一样，如果在.sidebar样式中使用 @media 指令，编译后, 它将冒泡到外面,将@media提到.sidebar样式的外边
* @media #{$media} and ($feature: $value) and ($feature: $value) and ...
* $media: all, print, screen, only screen;
* ($feature: $value): (orientation:portrait), (orientation:landscape), (min-device-pixel-ratio: 1.5), (max-width: 720px)

[slide style="background-image:url('/img/bg1.png');color: #000;"]
# @mixin
* 重用一整段Sass代码，能够给他们传递参数
* @mixin function($condition) {}
* 不足之处是会生成冗余的代码块, Sass在调用相同的混合宏时，并不能智能的将相同的样式代码块合并在一起
---
# @include
* 与@mixin配套,引用@mixin的function
* @include function($condition);

[slide style="background-image:url('/img/bg1.png');color: #000;"]
# @extend
* 用来扩展选择器或占位符
* %(占位符选择器),取代以前 CSS 中的基类造成的代码冗余的情形。因为 %placeholder 声明的代码，如果不被 @extend 调用的话，不会产生任何代码。通过 @extend 调用的占位符，编译出来的代码会将相同的代码合并在一起。
---
# @at-root
---
* 跳出根元素, 跳到最外层

[slide style="background-image:url('/img/bg1.png');color: #000;"]
# 混合宏 VS 继承 VS 占位符
* 混合宏: 不会自动合并相同的样式代码，如果在样式文件中调用同一个混合宏，会产生多个对应的样式代码，造成代码的冗余，但是可以传参数
* 继承: 会将使用继承的代码块合并到一起，通过组合选择器的方式向大家展现, 但是他不能传变量参数
* 占位符: 和使用继承基本上是相同，只是不会在代码中生成占位符样式 的选择器。那么占位符和继承的主要区别的，占位符是独立定义，不调用的时候是不会在 CSS 中产生任何代码；继承是首先有一个基类存在，不管调用与不调用，基类的样式都将会出现在编译出来的 CSS 代码中
* 个人建议
    1. 如果代码块中涉及到变量，建议使用混合宏来创建相同的代码块
    2. 如果代码块不需要传任何变量参数，而且有一个基类已在文件中存在，那么建议使用继承。

[slide style="background-image:url('/img/bg1.png');color: #000;"]
# Other
* transform:rotate(360deg) skew(-20deg) scale(1.0) translate(100px,0);
    1. transform:rotate()： 旋转；其中“deg”是“度”的意思，如“10deg”表示“10度”下同
    2. transform:skew()： 倾斜
    3. transform:scale()： 比例；如果要放大2倍，须写成“2.0”，缩小则为负“-”
    4. transform:translate()： 变动，位移；如下表示向右位移120像素，transform: translate(120px,0); 如果向上位移，transform: translate(0,-120px)

[slide style="background-image:url('/img/bg1.png');color: #000;"]
# Other
* box-shadow:inset x-offset y-offset blur-radius spread-radius color;
    1. 对象选择器 {box-shadow:投影方式 X轴偏移量 Y轴偏移量 阴影模糊半径 阴影扩展半径 阴影颜色}
    2. X-offset:如果值为正值，则阴影在对象的右边，反之其值为负值时，阴影在对象的左边
    3. Y-offset:如果为正值，阴影在对象的底部，反之其值为负值时，阴影在对象的顶部
    4. 阴影模糊半径：此参数是可选，但其值只能是为正值，如果其值为0时，表示阴影不具有模糊效果，其值越大阴影的边缘就越模糊
    5. 阴影扩展半径：此参数可选，其值可以是正负值，如果值为正，则整个阴影都延展扩大，反之值为负值是，则缩小
    6. 阴影颜色：此参数可选，如果不设定任何颜色时，浏览器会取默认色，但各浏览器默认色不一样，特别是在webkit内核下的safari和chrome浏览器将无色，也就是透明，建议不要省略此参数
