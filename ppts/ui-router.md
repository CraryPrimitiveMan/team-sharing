title: UI-ROUTER
speaker: Justin.Yang
url: https://github.com/ksky521/nodePPT
transition: cards

[slide]

# UI-ROUTER
## 演讲者：Justin.Yang

[slide]

### 前言

----

<p style="font-size: 16px; text-align: left;">任何一个MVC(MVVM)框架,无论是前端还是后端都有一套适合自身的路由规则.AngularJs原生的路由规则是: ngRouter,使用起来极其方便.但是随着单页面应用的需求不断扩大,ngRouter已经不能满足很多情况,比如它不能实现多视图,不能实现视图的嵌套等等,缺点逐渐被暴露.ngRouter开发团队(准确的说是angular开发团队)基于状态的方式重新实现了ngRouter,并将其从angular中提取出写成了单独的模块,依赖于angular,使用者可以根据需求安装ui-router.</p>

[slide]

### <p style="text-align: left;">The de-facto solution to flexible routing with nested views</p>

----

<p style="text-align: left;">AngularUI Router is a routing framework for AngularJS(**angular-ui-router基于angular**), which allows you to organize the parts of your interface into a state machine. Unlike the $route service in Angular core, which is organized around URL routes, UI-Router is organized around states, which may optionally have routes, as well as other behavior, attached.(**angular-ui-router是基于state进行**)
<br>
States are bound to named, nested and parallel views, allowing you to powerfully manage your application's interface.(**state可以进行命名**)</p>


[slide]

### The majority of UI-Router's power

----

<br>

* Nest states & views.
* Have multiple ui-views view per template.

<hr>

**<p style="font-size: 18px;">NGROUTER是基于url的改变驱动视图的变化，而UI-ROUTER是基于状态的改变驱动视图变化.</p>**

[slide]

### NGROUTER和UI-ROUTER的使用

----

* 如何配置路由 {:&.moveIn}
* 路由配置项有哪些(StateConfig)

[slide]

NGROUTER的路由

----

```javascript
.config(['$routeProvider', function ($routeProvider) {
  $routeProvider
    .when('/home', {
      templateUrl: 'app/view/home.html',
      // template: 'I am parent <div ng-view>I am child</div>',
      controller: 'homeCtrl'
    })
    .when('/contact', {
      templateUrl: 'app/view/contact.html',
      // controller: 'contactCtrl'
    })
    .otherwise('/home');
}]);
```

[slide]

UI-ROUTER的路由

----

```javascript
.config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {
  $stateProvider
    .state('home', {
      /* Default view */
      url: '/',
      template: '<p class=\'lead\'>Hello UI-Router Home</p>'
    });

    $urlRouterProvider.otherwise('/');
}]);
```

[slide]

### 路由配置项(共有的配置)

----

* template / templateUrl / templateProvider
* controller(controller会进行依赖注入，注入已存在的服务，或者resolve的返回值)
* resolve(预注入服务或者数据,resolve需要和controller一起使用,如果resolve传入的是一个函数,那么这个函数的返回值会被解析成一个promise对象,并在controller实例化之前被注入到controller内,所以可以在controller内随意使用)

[slide]

### Promise对象

----

<pre style="float: left; width: 400px; height: 400px; background: #23241f; color: #fff;">
ajax({
    url: url1,
    success: function(data) {
        ajax({
            url: url2,
            data: data,
            success: function() {
            	//todo
            }
        });
    }
});
</pre>

<pre style="float: right; width: 400px; height: 400px; background: #23241f; color: #fff;">
function A() {
    ajax({
        url: url1,
        success: function(data) {
            B(data);
        }
    });
}

function B(data) {
    ajax({
        url: url2,
        success: function(data) {
            //todo
        }
    });
}
</pre>

[slide]

### UI-ROUTER独有的配置

----

* url：ui-router是基于状态的，所以会为每个状态设置一个url．
* params：可以在url中配置参数，这样在state被navigated或者transitioned的时候，可以用$stateParams拿到传递的参数．
* views：多视图的时候使用．
* onEnter / onExit：angular会在进入视图和离开视图的时候调用这些回调函数，比如进入一个视图的时候弹出一个模态框，提示一些信息．
* abstract(boolean)：如果被设置为true，意味着这个视图永远不能被直接active，只能当它的子状态被active的时候被间接active．
* data：和resolve类似，但是它不会被注入到controller内，用于父状态向子状态传递数据，在子状态获取这些数据的常用方式是＇$state.current.data＇.**(Issue: angular-ui-router源码中明确写到,data属性从父级的state继承而来,并且可以override这些data,反之是不可以的!见源码line 2191)**

[slide]

### UI-ROUTER为什么代替NGROUTER

----

> 因为ngRouter无法实现多视图和嵌套视图，多视图是从宽度上扩展一个视图，而嵌套视图则是从深度上扩展一个视图．

[slide]

### 多视图

----

我们假设这样一个需求：一个页面2块div中分别需要显示不同的内容．尝试用NGROUTER和UI-ROUTER分别使用．　{:.flexbox.vleft}

[slide]

### NG-ROUTER

----

``` html
<div ng-view>块１</div>
<div ng-view>块２</div>
```

``` js
$routeProvider
	.when('/', {
        template: 'Hello world'
    });
```

当我们的路由为＂／＂时候，页面会将ng-view的地方嵌入模板，但是因为ng-view是基于url的，并且在配置中没有名字作为唯一的标示，所以我们无法对这2个ng-view做不同的处理．　{:.flexbox.vleft}

[slide]

### UI-ROUTER

----

``` html
<div ui-view="div-one">块１</div>
<div ui-view="div-two">块２</div>
```

``` js
$stateProvider
    .state('home', {
        url: '/',
        views: {
        	'div-one': { template: 'Hello div one' },
            'div-two': { template: 'Hello div two' }
        }
    });
```

<p style="text-align: left; font-size: 16px;">很明显能发现，UI-ROUTER是基于状态的一种路由规则，我们能通过配置不同的状态让ui-view能做出不同的处理，将页面渲染，这样每个页面的不同ui-view就可以实现基于状态的变化而非是url．</p>

----

**其实无论是单视图还是多视图，UI-ROUTER都会将其解析成views的方式．　{:.flexbox.vleft}**

[slide]

### 单视图

----

```js
$stateProvider
    .state('form', {
      url: '/form',
      templateUrl: 'app/form/form.html',
      controller: 'formController'
    });
会解析成
$stateProvider
    .state('form', {
        views: {
        	'@': {
                url: '/form',
                templateUrl: 'app/form/form.html',
                controller: 'formController'
            }
        }
    })
```

[slide]

### 多视图

----

```js
.state('form.interests', {
        views: {
            /* we can set controller to every child view */
            'interest-entertainment': {
            	templateUrl: 'app/formInterest/form-interests-entertainment.html'
            },
            'interest-eat': {
            	templateUrl: 'app/formInterest/form-interests-eat.html'
            }
        }
    })
会解析成
.state('form.interests', {
        views: {
            /* we can set controller to every child view */
            'interest-entertainment＠form.interest': {
            	templateUrl: 'app/formInterest/form-interests-entertainment.html'
            },
            'interest-eat＠form.interest': {
            	templateUrl: 'app/formInterest/form-interests-eat.html'
            }
        }
    })
```

[slide]

其实views配置的就是类似 viewname + @ + statename　的状态(line 2235)，其中viewname是指ui-view="?"中?的内容，不填的话就是""，statename是父级state的name，子路由放在父级路由的ui-view中，理解一下就是该视图会被放在statename路由下的viewname模板中．　{:.flexbox.vleft}

[slide]

### 嵌套视图

----

我们假设有一个父级div，内部嵌套了一个子div．

```html
<div class="parent">
	I am father.
    <div class="child">
    	I am child.
    </div>
</div>
```

[slide]

### NG-ROUTER

```js
$routeProvider
    .when('/home', {
      template: 'I am parent <div ng-view>I am child</div>'
    });
```

<p style="text-align: left; font-size: 16px;">呵呵，尝试下之后发现出现＂Maximum call stack size exceeded＂，溢出了，就证明这样写不对的．这样的原因就是因为，ng-view是没法设置name去标示直接关系的，这样导致angular没法知道这些ng-view的层级关系，导致循环．如果使用ui-router就会很简单了．</p>

[slide]

### UI-ROUTER

----

```js
$stateProvider
    .state('parent', {
        url: '/',
        template: 'I am parent <div ui-view></div>'
    })
    .state('parent.child', {
        template: 'I am child'
    })

    /* Also can write like this */
    .state('child', {
    	parent: 'parent',
    	template: 'I am child'
    })
    .state('child', {
    	parent: {
        	'name': 'parent'
        },
    	template: 'I am child'
    });
```

<p style="text-align: left; font-size: 16px;">佷简单就可以实现网页的嵌套，我们还可以配置一些传入的参数．利用这样的方式可以实现一个页面内，嵌套多个不同的其他页面块，展示不同的数据.</p>

[slide]

### We need to remember some issues as follows：

----

<ul style="text-align: left; font-size: 1８px;">
	<li>Registering States Order：当然angular注册路由的顺序并没有什么要求，因为如果先注册的是子路由，而子路由的父路由还没有注册，angular会先将该子路由缓存起来的(line 2314).</li>
    <li>Parent MUST Exist(View code)</li>
    <li>Child actived then parent actived：子状态被激活，父状态会被隐式的激活，Child states将会load它的template嵌入到parent的ui-view中.(View code)</li>
</ul>

[slide]

| ngRoute | ui.router |
|---------|:----------|
| $routeProvider | $stateProvider / $urlRouterProvider | 服务的提供者 |
| $route | $state / $urlRouter| 服务 |
| $routeParams | $stateParams | 获取路由中携带的参数 |
| ng-view | ui-view | 视图调用 |

----

* $state服务负责表示路由状态,并且在状态之间transitioning,还提供了能够访问前后路由传递状态的api
* $urlRouter服务负责监视$location,当$location变化的时候,回去遍历rules,直到有一个匹配
* $stateProvider提供了一系列的方法用来配置路由
* $urlRouterProvider常在$stateProvider最后配置出一个重定向的state

[slide]

### 路由事件

----

| 路由过程 | ngRoute | ui.router |
|---------|:--------|:---------|
| 路由变化之前 | $routeChangeStart(broadcast),带有2个参数,一个是路由变化前的url,一个是要导航到的url | $stateChangeStart(broadcast), 参数有evt, toState, fromState, fromParams, toParams |
| 路由变化成功 | $routeChangeSuccess(broadcast),参数和上面一致  | $stateChangeSuccess 从一个状态过渡到下一个状态 |
| 视图加载事件 | $viewContentLoading(视图加载之前执行) | $viewContentLoaded(Dom被加载完成之后执行)

[slide]

### 模板渲染 - 路由解析

----

<ul style="text-align: left; font-size: 18px; margin-top: 20px;">
	<li>创建并存储一个state对象，然后调用$urlRouterProvider进行路由注册(line 2314)，这个里面创建一个rule，将路由信息存在rule内，以后每次查找就从这个rule内去遍历了</li>
	<li style="margin-bottom: 20px;">angular在digest循环开始时候会去监听$stateChangeSuccess事件(line 3920)，当state改变之后会去broadcast事件$stateChangeSuccess(line 3311)</li>
    <li style="margin-bottom: 20px;">每次路由发生变化的时候会去在rule内去搜索，匹配到(line 1877)对应的路由规则</li>
    <li style="margin-bottom: 20px;">通过调用$state.transitionTo()跳转激活对应的state(line 3067可以发现我们可以使用$state.go()进行状态间的跳转，并可以传递数据)，完成数据请求和模板的渲染</li>
</ul>

[slide]

### How to activate a state

----

<ul style="text-align: left; font-size: 20px;">
	<li>Call $state.go()</li>
    <li>Click a link with 'ui-sref' directive</li>
    <li>Navigate the url directly</li>
</ul>

----

<p style="font-size: 16px;">If the path starts with '^' or '.', it is relative, otherwise it is absolute.</p>

[slide]

----

![$state](/images/justin/state.png)

[slide]

### Use ui-sref instead of href and Why?

----

``` html
href的做法：
<a href="/profile"><span>1</span></a>
ui-sref的做法：
<a ui-sref-active="active" ui-sref=".profile"><span>1</span></a>
```

----

```js
ui-router中定义了３个和state跳转相关的directive

angular.module('ui.router.state')
  .directive('uiSref', $StateRefDirective)
  .directive('uiSrefActive', $StateRefActiveDirective)
  .directive('uiSrefActiveEq', $StateRefActiveDirective);

第一个uiSref指令会在dom中绑定click事件，然后根据state.name直接跳转到对应的state上(line 4176)
第二个uiSrefActive会在state被激活的时候，加一个我们自定义的class以改变渲染的样式(line 4272)．
```
----

<p style="font-size: 16px; text-align: left;">在state变化之后都会触发$locationChangeSuccess事件，继而触发回调，传统的href则会触发遍历rules以寻找state，这样会影响性能；而ui-sref会去check，如果是手动触发的方式则会直接return(line 1999).</p>

[slide]

### 关于使用HTML5模式的url

<p style="font-size: 16px; text-align: left;">angular中$location服务能够对url处理,有2种路由模式</p>

* 一种是默认的hash模式,其表现形式是url中能看到 : ui-router/site/#/api/ui.router 的'#',源码line 2113中有默认的设置.
* 一种是html5模式,利用$locationProvider.html5Mode(true),去设置此模式.

$location服务内提供了对浏览器回退api的封装

[slide]

### 总结

----

* 时刻记住: ui-router是基于状态的路由管理,ngRouter是基于url的


[slide]

### 相关链接

----

<p style="font-size: 24px; text-align: left;">GITHUB地址：https://github.com/angular-ui/ui-router</p>
<p style="font-size: 24px; text-align: left;">UI-ROUTER：https://angular-ui.github.io/ui-router/#get-started</p>
<p style="font-size: 24px; text-align: left;">UI-ROUTER-WIKI：https://github.com/angular-ui/ui-router/wiki</p>
<p style="font-size: 24px; text-align: left;">UI-ROUTER-API：http://angular-ui.github.io/ui-router/site/#/api</p>
