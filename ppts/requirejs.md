title:Requirejs 使用以及实现原理
speaker: Alisa Zhang
url: https://github.com/ksky521/nodePPT
transition: zoomin

[slide style="background-image:url('/img/bg1.png')"]

# Requirejs的使用以及实现原理
## 演讲者: Alisa Zhang

[slide style="background-image:url('/img/bg1.png');color: #000;"]

###RequireJS是一款遵循AMD规范的JavaScript模块加载器

+ AMD规范
  + AMD：Asynchronous Modules Definition异步模块定义，提供定义模块及异步加载该模块依赖的机制。
    ```
    define(['./a', './b'], function(a, b) {
        //a模块和b模块已经加载完，直接可用
        a.doing();
        b.doing();
    });
    ```

+ 模块化
  + 代码的模块化，不同于传统的script标签加载js，在使用脚本时以module ID替代URL地址。

+ 使用利弊：
  + 优点：尽早执行依赖可以尽早发现错误；缺点：容易产生浪费
[slide style="background-image:url('/img/bg1.png');color: #000;"]

###RequireJS运行机制
+ 载入模块。
+ 通过模块名解析出模块信息，以及计算出URL。
+ 使用head.appendChild()将每一个依赖加载为一个script标签。
+ 等待所有的依赖加载完毕，计算出模块定义函数正确调用顺序，然后依次调用它们。
+ 等所有脚本都被加载完毕就执行加载完成之后的回调函数。

[slide style="background-image:url('/img/bg1.png');color: #000;"]

###加载代码

+ RequireJS以一个相对于baseUrl的地址来加载所有的代码。页面script标签含有一个特殊的属性data-main，require.js使用它来启动脚本加载过程，而baseUrl一般设置到与该属性相一致的目录。下列示例中展示了baseUrl的设置：

  ```
     <!--This sets the baseUrl to the "scripts" directory,
     and loads a script that will have a module ID of 'main'-->
  <script data-main="scripts/main.js" src="scripts/require.js"></script>
  ```
+ baseUrl亦可通过RequireJS config手动设置。如果没有显式指定config及data-main，则默认的baseUrl为包含RequireJS的那个HTML页面的所属目录：

  ```
  <script src="scripts/require.js"></script>
  ```

[slide style="background-image:url('/img/bg1.png');color: #000;"]

###baseUrl示例

  ```
  <script data-main="js/app.js" src="js/require.js"></script>
  ```

  ```
  requirejs.config({
    baseUrl: 'js/lib',
    //If the module ID starts with "app",load it from the js/app directory.
    //For example 'app/sub' loads 'js/add/sub.js'
    paths: {
        app: '../app'
    }
});
  ```
+ index.html
+ js/
  + app/
    + sub.js
  + lib/

+ paths都是相对于baseUrl的。
+ 配置paths时不要加入.js后缀，因为配置的paths可能是一个路径而不是一个文件。

[slide style="background-image:url('/img/bg1.png');color: #000;"]


###支持的配置项
+ baseUrl
  + 所有模块的查找根路径。当加载纯.js文件(依赖字串以/开头，或者以.js结尾，或者含有协议)，不会使用baseUrl。
  + 如未显式设置baseUrl，则默认值是加载require.js的HTML所处的位置。如果用了data-main属性，则该路径就变成baseUrl。
+ paths
  + path映射那些不直接放置于baseUrl下的模块名。设置path时起始位置是相对于baseUrl的，除非该path设置以"/"开头或含有URL协议（如http:）。
+ shim
  + 为那些没有使用define()来声明依赖关系的模块做依赖和导出配置。
  + shim配置仅设置了代码的依赖关系，想要实际加载shim指定的或涉及的模块，仍然需要一个常规的require/define调用。设置shim本身不会触发代码的加载。
[slide style="background-image:url('/img/bg1.png');color: #000;"]

###shim示例
```
shim:
    //exports: once loaded, use the global 'angular' as the module value.
    angular:
      exports: 'angular'
    angularUIRouter: ['angular']
    angularTranslate: ['angular']
    angularTranslateLoader: [
      'angular'
      'angularTranslate'
    ]
    angularFileUpload: ['angular']
    restangular: ['angular', 'lodash']
    uiBootstrap: ['angular']
    uiBootstrapTpls: ['uiBootstrap']
    autoComplete: ['angular', 'jquery']
    fullcalendar: ['jquery', 'moment']
    angularTooltips: ['angular']
```
+ exports能够帮助我们以AMD模块的方式，使用那些不符合AMD规范的模块，例如angular,underscore等，exports值表明这个模块外部调用时的名称。
[slide style="background-image:url('/img/bg1.png');color: #000;"]

###config
+ 常常需要将配置信息传给一个模块。这些配置往往是application级别的信息，需要一个手段将它们向下传递给模块。在RequireJS中，基于requirejs.config()的config配置项来实现。要获取这些信息的模块可以加载特殊的依赖“module”，并调用module.config()。示例：

```
requirejs.config({
    config: {
        'bar': {
            size: 'large'
        },
        'baz': {
            color: 'blue'
        }
    }
});

define(['module'], function (module) {
    //Will be the value 'blue'
    var color = module.config().color;
});
```

[slide style="background-image:url('/img/bg1.png');color: #000;"]

###map
+ 两类模块需要使用不同版本的"foo"，但它们之间仍需要一定的协同,对于给定的模块前缀，使用一个不同的模块ID来加载该模块。
```
requirejs.config({
    map: {
        'some/newmodule': {
            'foo': 'foo1.2'
        },
        'some/oldmodule': {
            'foo': 'foo1.0'
        }
    }
});
```
如果各模块在磁盘上分布如下：
+ foo1.0.js
+ foo1.2.js
+ some/
  + newmodule.js
  + oldmodule.js
[slide style="background-image:url('/img/bg1.png');color: #000;"]

###map
+ 当"some/newmodule"调用了"require('foo')"，它将获取到foo1.2.js文件；而当"some/oldmodule"调用"require('foo')"时它将获取到foo1.0.js。
该特性仅适用于那些调用了define()并将其注册为匿名模块的真正AMD模块脚本。并且，请在map配置中仅使用绝对模块ID，“../some/thing”之类的相对ID不能工作。
+ 在map中支持"\*"，意思是"对于所有的模块加载，使用本map配置"。如果还有更细化的map配置，会优先于"\*"配置。
    ```
      requirejs.config({
        map: {
            '*': {
                'foo': 'foo1.2'
            },
            'some/oldmodule': {
                'foo': 'foo1.0'
            }
        }
    });
    ```
意思是除了“some/oldmodule”外的所有模块，当要用“foo”时，使用“foo1.2”来替代。对于“some/oldmodule”，则使用“foo1.0”。
[slide style="background-image:url('/img/bg1.png');color: #000;"]

###deps
+ 指定要加载的一个依赖数组。一旦require.js被定义，这些依赖就已加载。它并不阻塞其他的require()调用，指定某些模块作为config块的一部分而异步加载的手段而已。
[slide style="background-image:url('/img/bg1.png');color: #000;"]

###callback
+ 在deps加载完毕后执行的函数。当将require设置为一个config object在加载require.js之前使用时很有用，其作为配置的deps数组加载完毕后为require指定的函数。
[slide style="background-image:url('/img/bg1.png');color: #000;"]

###urlArgs
+  RequireJS获取资源时附加在URL后面的额外的query参数。加上时间戳示例：
    ```
      urlArgs: 'v={timestamp}'
    ```
[slide style="background-image:url('/img/bg1.png');color: #000;"]

###加载JavaScript文件
+ RequireJS默认假定所有的依赖资源都是js脚本，因此无需在module ID上再加".js"后缀，RequireJS在进行module ID到path的解析时会自动补上后缀。
+ 如果想避开"baseUrl + paths"的解析过程，而是直接指定加载某一个目录下的脚本。此时可以这样做：如果一个module ID符合下述规则之一，其ID解析会避开常规的"baseUrl + paths"配置，而是直接将其加载为一个相对于当前HTML文档的脚本：
  + 以 ".js" 结束.
  + 以 "/" 开始.
  + 包含 URL 协议, 如 "http:" or "https:".
+ 一般来说，最好还是使用baseUrl及"paths" config去设置module ID。它会给你带来额外的灵活性，如便于脚本的重命名、重定位等。 同时，为了避免配置凌乱，最好不要使用多级嵌套的目录层次来组织代码，而是要么将所有的脚本都放置到baseUrl中，要么分置为项目库/第三方库的结构,参见hopeland的main.coffee配置。

[slide style="background-image:url('/img/bg1.png');color: #000;"]
###处理入口文件
+ 参见require.js对data-main属性的处理 Line:1967
  + 逆序遍历页面中的script标签，一旦解析出data-main属性就会停止遍历，并且通过data-main属性得到baseUrl。个人认为倒叙遍历的原因是一般情况下会将script放在页面的最下方，这样在requriejs加载依赖项的时候确保前面的js已经加载。
  + 设置了baseUrl
  + 将main.js加入requriejs的加载列表
+ 理想状况下，每个加载的脚本都是通过define()来定义的一个模块；但有些模块并没有使用define()来定义它们的依赖关系，你必须为此使用shim config来指明它们的依赖关系。 如果你没有指明依赖关系，加载可能报错。这是因为基于速度的原因，RequireJS会异步地以无序的形式加载这些库。参见hopeland的main.coffee配置。

[slide style="background-image:url('/img/bg1.png');color: #000;"]

###data-main 入口点
+ require.js 在加载的时候会检察data-main 属性:
    ```
   <!--when require.js loads it will inject another script tag
    (with async attribute) for scripts/main.js-->
  <script data-main="scripts/main.js" src="scripts/require.js"></script>
 ```
你可以在data-main指向的脚本中设置模板加载选项，然后加载第一个应用模块。注意：你在main.js中所设置的脚本是异步加载的。所以如果你在页面中配置了其它JS加载，则不能保证它们所依赖的JS已经加载成功。参见hopeland的main.coffee配置。
例如：

    ```
  <script data-main="scripts/main.js" src="scripts/require.js"></script>
  <script src="scripts/other.js"></script>

  // contents of main.js:
  require.config({
    paths: {
      foo: 'libs/foo-1.1.3'
    }
  });
  // contents of other.js:
  // This code might be called before the require.config() in main.js
  // has executed. When that happens, require.js will attempt to
  // load 'scripts/foo.js' instead of 'scripts/libs/foo-1.1.3.js'
  require( ['foo'], function( foo ) {

  });

    ```
[slide style="background-image:url('/img/bg1.png');color: #000;"]


###定义模块
+ 模块可以显式地列出其依赖关系，并以函数(定义此模块的那个函数)参数的形式将这些依赖进行注入，而无需引用全局变量。
+ RequireJS的模块无需全局地引用其他模块，允许它尽快地加载多个模块，虽然加载的顺序不定，但依赖的顺序最终是正确的。
[slide style="background-image:url('/img/bg1.png');color: #000;"]

###简单的键值对
+ 如果一个模块仅含值对，没有任何依赖，则在define()中定义这些值对就好了：

```
//Inside file my/shirt.js:
define({
    color: "black",
    size: "unisize"
});
```
###函数式定义
+ 如果一个模块没有任何依赖，但需要一个做setup工作的函数，则在define()中定义该函数，并将其传给define()：

```
//Inside file my/shirt.js:
define(function () {
    //Do setup work here
    return {
        color: "black",
        size: "unisize"
    }
});
```
[slide style="background-image:url('/img/bg1.png');color: #000;"]

###存在依赖的函数式定义
+ 如果模块存在依赖：则第一个参数是依赖的名称数组；第二个参数是函数，在模块的所有依赖加载完毕后，该函数会被调用来定义该模块，因此该模块应该返回一个定义了本模块的object。依赖关系会以参数的形式注入到该函数上，参数列表与依赖名称列表一一对应。
    ```
    //my/shirt.js now has some dependencies, a cart and inventory
    //module in the same directory as shirt.js
    define(["./cart", "./inventory"], function(cart, inventory) {
            //return an object to define the "my/shirt" module.
            return {
                color: "blue",
                size: "large",
                addToCart: function() {
                    inventory.decrement(this);
                    cart.add(this);
                }
            }
        }
    );
    ```
  + 模块函数以参数"cart"及"inventory"使用以"./cart"及"./inventory"名称指定的模块。在这两个模块加载完毕之前，模块函数不会被调用。
[slide style="background-image:url('/img/bg1.png');color: #000;"]

###将模块定义为一个函数
+ 对模块的返回值类型并没有强制为一定是个object，任何函数的返回值都是允许的。此处是一个返回了函数的模块定义：

    ```
    //A module definition inside foo/title.js. It uses
    //my/cart and my/inventory modules from before
    define(["my/cart", "my/inventory"],
        function(cart, inventory) {
            //It gets or sets the window title.
            return function(title) {
                return title ? (window.title = title) :
                       inventory.storeName + ' ' + cart.name;
            }
        }
    );
    ```
[slide style="background-image:url('/img/bg1.png');color: #000;"]
###定义一个命名模块
+ 你可能会看到一些define()中包含了一个模块名称作为首个参数：

    ```
    //Explicitly defines the "foo/title" module:
    define("foo/title",
        ["my/cart", "my/inventory"],
        function(cart, inventory) {
            //Define foo/title object in here.
       }
    );
    ```
+ 这些常由优化工具生成。你也可以自己显式指定模块名称，但这使模块更不具备移植性——就是说若你将文件移动到其他目录下，你就得重命名。一般最好避免对模块硬编码，而是交给优化工具去生成。
[slide style="background-image:url('/img/bg1.png');color: #000;"]

###循环依赖
+ 如果你定义了一个循环依赖(a依赖b，b同时依赖a)，则在这种情形下当b的模块函数被调用的时候，它会得到一个undefined的a。b可以在模块已经定义好后用require()方法再获取(记得将require作为依赖注入进来)：
    ```
    //Inside b.js:
    define(["require", "a"],
        function(require, a) {
            //"a" in this case will be null if a also asked for b,
            //a circular dependency.
            return function(title) {
                return require("a").doSomething();
            }
        }
    );
    ```
+ 一般说来你无需使用require()去获取一个模块，而是应当使用注入到模块函数参数中的依赖。循环依赖比较罕见，它也是一个重构代码重新设计的警示灯。但不管怎样，有时候还是要用到循环依赖，这种情形下就使用上述的require()方式来解决。

[slide style="background-image:url('/img/bg1.png');color: #000;"]

###JSONP服务依赖
+ JSONP是在javascript中服务调用的一种方式。它仅需简单地通过一个script标签发起HTTP GET请求，是实现跨域服务调用一种公认手段。

+ 为了在RequireJS中使用JSON服务，须要将callback参数的值指定为"define"。这意味着你可将获取到的JSONP URL的值看成是一个模块定义。
JSONP的callback参数为"callback"，因此"callback=define"告诉API将JSON响应包裹到一个"define()"中，例如：

```
require(["http://example.com/api/data.json?callback=define"],
    function (data) {
        //The data object will be the API response for the
        //JSONP data call.
        console.log(data);
    }
);
```

[slide style="background-image:url('/img/bg1.png');color: #000;"]

###加载插件
+ RequireJS支持加载插件,使用它们能够加载一些对于脚本正常工作很重要的非JS文件。RequireJS的wiki有一个插件的列表。
  + 有时候会有在JavaScript文件中嵌入HTML的需求。我们会在js中使用HTML字串，但这一般很难维护，尤其是HTML代码比较多的情况下。
RequireJS有个text.js插件可以帮助解决这个问题。如果一个依赖使用了text!前缀，它就会被自动加载。
  + 类似的还有json.js，image.js，mdown等分别用来加载json文件，图片文件以及markdown文件。
  + hopeland的config.coffee读取states.json用的就是json.js插件，但是json.js依赖text.js。
[slide style="background-image:url('/img/bg1.png');color: #000;"]

###加载插件
+ RequireJS允许你先仅配置一个含有本地化信息的基本模块，而不需要将所有的本地化信息都预先创建起来。后面可以将这些本地化相关的变化以值对的形式慢慢加入到本地化文件中。
+ 具体使用参考http://www.requirejs.org/docs/api.html#i18n
[slide style="background-image:url('/img/bg1.png');color: #000;"]

###参考文档
+ requirejs官网：
  + http://www.requirejs.org/
+ requirejs中文网：
  + http://requirejs.cn/
+ requirejs插件介绍：
  + https://github.com/millermedeiros/requirejs-plugins/
