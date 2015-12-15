title: Angularjs directive
speaker: Gary Hou
url: https://github.com/rjgchrm
transition: move
theme: colors


[slide style="background-image:url('https://raw.githubusercontent.com/rjgchrm/HrmProject/develop/src/image/angular.png');color:#000;"]

[slide style="background-image:url('https://raw.githubusercontent.com/rjgchrm/HrmProject/develop/src/image/b9.jpg')"]

# Angularjs directive

<small style="color:#000;">演讲者：[@Gary Hou](https://github.com/rjgchrm)</small>

<p style="color:#000;">
QQ： 1055120977<br/>邮箱： rjgchrm@163.com
</p>

[slide style="background-image:url('https://raw.githubusercontent.com/rjgchrm/HrmProject/develop/src/image/b1.jpg');color:#fff;"]

## 我们的疑问
* 什么是angular 指令？ {:&.rollIn}
* 指令如何定义存储的？
* 指令如何编译运行的？

[slide style="background-image:url('https://raw.githubusercontent.com/rjgchrm/HrmProject/develop/src/image/b3.jpg');color:#fff;" data-transition="vertical3d"]
## 指令
<p style="color: #000;text-align:left;">
At a high level, directives are markers on a DOM element (such as an attribute, element name, comment or CSS class) that tell AngularJS's HTML compiler ($compile) to attach a specified behavior to that DOM element or even transform the DOM element and its children.<br />
指令就是一些附加在HTML元素上的自定义标记（例如：属性，元素，或css类），它告诉AngularJS的HTML编译器 ($compile) 在元素上附加某些指定的行为，甚至操作DOM、改变DOM元素，以及它的各级子节点。
</p>

[slide style="background-image:url('https://raw.githubusercontent.com/rjgchrm/HrmProject/develop/src/image/b3.jpg');color:#fff;" data-transition="vertical3d"]
## 指令的定义与存储
<p style="color: #000;text-align:left;">
指令实例
</p>
```sh
angular.module('selfApp', [])
  .directive('myDirective', function() {
    // 返回一个对象（暂且称之为指令对象）
    return {
      restrict: 'A',//E A M C
      replace: true,
      scope: true,
      template: '<span>hello world</span>',
      compile: function (tElement) {
        console.log('complile: ', tElement);
        return function (scope, elem) {
          console.log('1');
        }

        <!-- return {
          pre: function () {
            console.log('2');
          },
          post: function () {
            console.log('3');
          }
        } -->
      }
    }
  });
```

[slide style="background-image:url('https://raw.githubusercontent.com/rjgchrm/HrmProject/develop/src/image/b3.jpg');color:#000;text-align:left;" data-transition="vertical3d"]
<p style="color: #000;text-align:left;">
调用实例
</p>
```sh
<div ng-controller="Controller">
  Hello <input ng-model='name'> <hr/>
  <span self-app></span> <br/>
  <span self:app></span> <br/>
  <span self_app></span> <br/>
  <span data-self-app></span> <br/>
  <span x-self-app></span> <br/>
</div>
```

[slide style="background-image:url('https://raw.githubusercontent.com/rjgchrm/HrmProject/develop/src/image/b6.jpg');color:#fff;" data-transition="vertical3d"]
## angularjs directive 对像参数说明
<p style="color: #000;text-align:left;font-size:18px;">
<b style="font-size:30px;">name</b> - 当前scope的名称，注册时可以使用默认值（不填）。<br /><br />
<b style="font-size:30px;">priority</b>（优先级）- 当有多个directive定义在同一个DOM元素时，有时需要明确它们的执行顺序。这属性用于在directive的compile function调用之前进行排序。如果优先级相同，则执行顺序是不确定的（经初步试验，优先级高的先执行，同级时按照类似栈的“后绑定先执行”。另外，测试时有点不小心，在定义directive的时候，两次定义了一个相同名称的directive，但执行结果发现，两个compile或者link function都会执行）。<br /><br />
<b style="font-size:30px;">terminal</b>（最后一组）- 如果设置为”true”，则表示当前的priority将会成为最后一组执行的directive。任何directive与当前的优先级相同的话，他们依然会执行，但顺序是不确定的（虽然顺序不确定，但基本上与priority的顺序一致。当前优先级执行完毕后，更低优先级的将不会再执行）。<br /><br /></p>
[slide style="background-image:url('https://raw.githubusercontent.com/rjgchrm/HrmProject/develop/src/image/b6.jpg');color:#fff;" data-transition="vertical3d"]
<p style="color: #000;text-align:left;font-size:18px;">
<b style="font-size:30px;">scope</b> <br />
true - 将为这个directive创建一个新的scope。如果在同一个元素中有多个directive需要新的scope的话，它还是只会创建一个scope。新的作用域规则不适用于根模版（root of the template），因此根模版往往会获得一个新的scope。这个scope是一个新的、独立(isolate)的scope。”isolate” scope与一般的scope的区别在于它不是通过原型继承于父scope的。这对于创建可复用的组件是很有帮助的，可以有效防止读取或者修改父级scope的数据。这个独立的scope会创建一个拥有一组来源于父scope的本地scope属性(local scope properties)的object hash。这些local properties对于为模版创建值的别名很有帮助（useful for aliasing values for templates!）。本地的定义是对其来源的一组本地scope property的hash映射(Locals definition is a hash of local scope property to its source))()：<br/>
</p>
[slide style="background-image:url('https://raw.githubusercontent.com/rjgchrm/HrmProject/develop/src/image/b6.jpg');color:#fff;" data-transition="vertical3d"]
<p style="color: #000;text-align:left;font-size:18px;">
&nbsp;&nbsp;&nbsp;&nbsp;@或@attr - 建立一个local scope property到DOM属性的绑定。因为属性值总是String类型，所以这个值总是返回一个字符串。如果没有通过@attr指定属性名称，那么本地名称将与DOM属性的名称一直。例如<widget my-attr='hello {{name}}'>，widget的scope定义为：{localName:’@myAttr’}。那么，widget scope property的localName会映射出”hello {{name}}"转换后的真实值。name属性值改变后，widget scope的localName属性也会相应地改变（仅仅单向，与下面的”=”不同）。name属性是在父scope读取的（不是组件scope）<br />
&nbsp;&nbsp;&nbsp;&nbsp;=或=expression（这里也许是attr） - 在本地scope属性与parent scope属性之间设置双向的绑定。如果没有指定attr名称，那么本地名称将与属性名称一致。例如<widget my-attr=”parentModel”>，widget定义的scope为：{localModel:’=myAttr’}，那么widget scope property “localName”将会映射父scope的“parentModel”。如果parentModel发生任何改变，localModel也会发生改变，反之亦然。（双向绑定）<br />
&nbsp;&nbsp;&nbsp;&nbsp;&或&attr - 提供一个在父scope上下文中执行一个表达式的途径。如果没有指定attr的名称，那么local name将与属性名称一致。例如<widget my-attr=”count = count + value”>，widget的scope定义为：{localFn:’increment()’}，那么isolate scope property “localFn”会指向一个包裹着increment()表达式的function。一般来说，我们希望通过一个表达式，将数据从isolate scope传到parent scope中。这可以通过传送一个本地变量键值的映射到表达式的wrapper函数中来完成。例如，如果表达式是increment(amount)，那么我们可以通过localFn({amount:22})的方式调用localFn以指定amount的值。<br /><br /></p>
[slide style="background-image:url('https://raw.githubusercontent.com/rjgchrm/HrmProject/develop/src/image/b6.jpg');color:#fff;" data-transition="vertical3d"]
<p style="color: #000;text-align:left;font-size:18px;">
<b style="font-size:30px;">controller</b> - controller 构造函数。controller会在pre-linking步骤之前进行初始化，并允许其他directive通过指定名称的require进行共享（看下面的require属性）。这将允许directive之间相互沟通，增强相互之间的行为。controller默认注入了以下本地对象：<br />
&nbsp;&nbsp;&nbsp;&nbsp;$scope - 与当前元素结合的scope<br />
&nbsp;&nbsp;&nbsp;&nbsp;$element - 当前的元素<br />
&nbsp;&nbsp;&nbsp;&nbsp;$attrs - 当前元素的属性对象<br />
&nbsp;&nbsp;&nbsp;&nbsp;$transclude - 一个预先绑定到当前转置scope的转置linking function :function(cloneLinkingFn)。(A transclude <br />&nbsp;&nbsp;&nbsp;&nbsp;linking function pre-bound to the correct transclusion scope)<br /><br />
<b style="font-size:30px;">require</b> - 请求另外的controller，传入当前directive的linking function中。require需要传入一个directive controller的名称。如果找不到这个名称对应的controller，那么将会抛出一个error。名称可以加入以下前缀：<br/>
&nbsp;&nbsp;&nbsp;&nbsp;? - 不要抛出异常。这使这个依赖变为一个可选项。<br />
&nbsp;&nbsp;&nbsp;&nbsp;^ - 允许查找父元素的controller<br /><br />
<b style="font-size:30px;">restrict</b> - EACM的子集的字符串，它限制directive为指定的声明方式。如果省略的话，directive将仅仅允许通过属性声明：
E - 元素名称&nbsp;&nbsp;A - 属性名&nbsp;&nbsp;C - class名&nbsp;&nbsp;M - 注释 <br /><br /></p>
[slide style="background-image:url('https://raw.githubusercontent.com/rjgchrm/HrmProject/develop/src/image/b6.jpg');color:#fff;" data-transition="vertical3d"]
<p style="color: #000;text-align:left;font-size:18px;">
<b style="font-size:30px;">template</b> - 如果replace 为true，则将模版内容替换当前的HTML元素，并将原来元素的属性、class一并迁移；如果为false，则将模版元素当作当前元素的子元素处理。<br /><br />
<b style="font-size:30px;">templateUrl</b> - 与template基本一致，但模版通过指定的url进行加载。因为模版加载是异步的，所以compilation、linking都会暂停，等待加载完毕后再执行。<br /><br />
<b style="font-size:30px;">replace</b> - 如果设置为true，那么模版将会替换当前元素，而不是作为子元素添加到当前元素中。（注：为true时，模版必须有一个根节点）<br /><br />
</p>
[slide style="background-image:url('https://raw.githubusercontent.com/rjgchrm/HrmProject/develop/src/image/b6.jpg');color:#fff;" data-transition="vertical3d"]
<p style="color: #000;text-align:left;font-size:18px;">
<b style="font-size:30px;">transclude</b> - 编译元素的内容，使它能够被directive所用。需要(在模版中)配合ngTransclude使用(引用)。transclusion的优点是linking function能够得到一个预先与当前scope绑定的transclusion function。一般地，建立一个widget，创建isolate scope，transclusion不是子级的，而是isolate scope的兄弟。这将使得widget拥有私有的状态，transclusion会被绑定到父级（pre-isolate）scope中。<br />
&nbsp;&nbsp;&nbsp;&nbsp;true - 转换这个directive的内容。<br />
&nbsp;&nbsp;&nbsp;&nbsp;‘element’ - 转换整个元素，包括其他优先级较低的directive。（像将整体内容编译后，当作一个整体（外面再包裹p），插入到指定地方）<br /><br />
<b style="font-size:30px;">compile</b> - compile function<br /><br />
<b style="font-size:30px;">link</b> - link function，这个属性仅仅是在compile属性没有定义的情况下使用。
</p>

[slide style="background-image:url('https://raw.githubusercontent.com/rjgchrm/HrmProject/develop/src/image/b6.jpg');color:#fff;" data-transition="vertical3d"]
# 结论：
<p style="color: #000;text-align:left;font-size:18px;">
1. 注册指令时，注册的是工厂函数（支持依赖注入），它负责返回指令对象<br />
2. 一个指令可以注册多个工厂函数，就意味着将对应多个指令对象（即指令对象集合），其实多个指令对象之间是有一些冲突的，比如只能拥有有一个模板，拥有一个孤立作用域等<br />
3. 一个指令对应的指令对象集合是通过注册为服务的方式被外界获取的
</p>
[slide data-transition="vertical3d" style="background-image:url('https://raw.githubusercontent.com/rjgchrm/HrmProject/develop/src/image/b14.jpg');color:#fff;"]
# 谢谢观赏！
