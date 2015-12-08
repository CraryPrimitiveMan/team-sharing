title: Bootstrap 的sass实现
speaker: Grace Mao
url: https://github.com/ksky521/nodePPT
transition: cards
files: /js/demo.js,/css/demo.css
theme:moon
[slide]

# Bootstrap 的sass实现
## 演讲者：Grace Mao

[slide data-transition="horizontal3d"]
## 简 介
+ Bootstrap 是最受欢迎的 HTML、CSS 和 JS 框架，它的源码是基于最流行的CSS 预处理脚本-Less和Sass开发的。
+ Bootstrap 使用到的某些 HTML 元素和 CSS 属性需要将页面设置为 HTML5 文档类型。(<!DOCTYPE html>)
+ Bootstrap 是移动设备优先的,支持响应式的布局。
  + &lt;meta name="viewport" content="width=device-width, initial-scale=1"&gt;

[slide data-transition="horizontal3d"]
##栅格系统
  + 通过一系列的行与列的组合来创建页面布局,一行最多12列.
  + 100%宽度布局 .container-fluid  ,响应式的固定宽度布局 .container 
  + 行 .row，行必须放置在容器内，以便获得适当的对齐和内边距
  + 响应式的column
    + .col-lg-* 大屏幕 大桌面显示器 (≥1200px)
    + .col-md-* 中等屏幕 桌面显示器 (≥992px)
    + .col-sm-* 小屏幕 平板 (≥768px)
    + .col-xs-* 超小屏幕 手机 (<768px)
    + 列偏移 .col-md-offset-*：实际上是设置了margin
    + 列排序 .col-md-push-* .col-md-pull-*:本质上是通过left或right设置了位置
    + 嵌套列 在column中再嵌套一个row
  + 如果一行中包含了的列大于12，多余的列所在的元素将被作为一个整体另起一行排列。

[slide data-transition="horizontal3d"]
##排 版
  + 标题: &lt;h1&gt; 到 &lt;h6&gt;　和　.h1 到 .h6 类 
  + 副标题:&lt;small&gt;  .small   
  + 无样式列表: .list-unstyled  
  + 内联列表: .list-inline   
  + 水平列表: .dl-horizontal
  + .text-left .text-right .text-center .text-justify .text-nowrap 
  + .text-lowercase .text-uppercase .text-capitalize

##代 码
  + 内联代码： &lt;code&gt;
  + 代码块： &lt;pre&gt;  
  + 固定高度垂直方向滚动：.pre-scrollable
  + 注意代码中尖括号做转义处理

[slide data-transition="horizontal3d"]
##表 格
 + 基本表格: .table 
 + 条纹状表格: .table-striped
 + 带边框表格: .table-bordered
 + 鼠标悬停: .table-hover
 + 表格紧缩: .table-condensed
 + 响应式表格: .table-responsive,会在小屏幕设备上（小于768px）水平滚动,当屏幕大于 768px 宽度时，水平滚动条消失.

[slide data-transition="horizontal3d"]
##表 单
 + 表单控件：.form-control .form-group  
 + 内联表单：.form-inline  
  + 内联单选框.radio-inline  
  + 内联多选框.checkbox-inline  
 + 静态控件：.form-control-static,当在表单中需将一行纯文本和label元素放置于同一行，为&lt;p&gt; 元素添加 .form-control-static 类。
 + 水平表单：.form-horizontal 

[slide data-transition="horizontal3d"]
##按 钮
 + 表示按钮的三种形式： &lt;a&gt; &lt;input&gt; &lt;button&gt;
 + 按钮类：.btn　.btn-default .btn-primary .btn-success .btn-info .btn-warning .btn-danger .btn-link
 + 按钮状态类：
  + 激活：.active
  + 禁用：.disabled
 + 按钮尺寸：.btn-lg .btn-sm .btn-xs 
 + 块级按钮：.btn-block

[slide data-transition="horizontal3d"]
##图 片
 + 响应式图片：.img-responsive  
 + 圆角图片：.img-rounded  
 + 圆形图片：.img-circle  
 + 缩略图：.img-thumbnail  
 + mixin: img-responsive($display: block)

[slide data-transition="horizontal3d"]
## 响应式的嵌入式网页元素
 + 根据被嵌入内容的外部容器的宽度，自动创建一个固定的比例，从而让浏览器自动确定视频的尺寸，能够在各种设备上缩放。
 + 应用在 &lt;iframe&gt;、&lt;embed&gt;、&lt;video&gt; 和 &lt;object&gt; 元素上。
 + 响应式样式：.embed-responsive
 + 高宽比：.embed-responsive-16by9  .embed-responsive-4by3

[slide data-transition="horizontal3d"]
##实用的样式和mixin
+ .clearfix .pull-left .pull-right .center-block .hide .show  .hidden .invisible
+ mixin: 
 + 透明度:opacity(@opacity)   
 + 设置尺寸: size(@width @height)   square(@size)    
 + 截断文本(用省略号代替): text-overflow() 
 + 隐藏文本(不是去除此元素): text-hide() hide-text()
 + 重置文本样式: reset-text()
 + 定义某一颜色label: label-variant($color)
 + 定义某一颜色文本: text-emphasis-variant($parent, $color)
 + 定义某一颜色背景: bg-variant($parent, $color)

[slide data-transition="horizontal3d"]
+ 圆角： 
  + border-top-radius(@radius) 
  + border-bottom-radius(@radius) 
  + border-right-radius(@radius) 
  + border-left-radius(@radius) 
+ 占位符文本颜色：placeholder(@color: @input-color-placeholder)


[slide data-transition="horizontal3d"]
##Bootstrap 组件
+ bootstrap还提供了许多可复用的组件
  + 字体图标
  + 徽章 .badge
  + 缩略图 .thumbnail
  + 面包屑: .breadcrumb
  + 面板: .panel .panel-default
  + 分页： .pagination
  + 警告框： .alert .alert-success .alert-danger role="alert"
  + 关闭按钮：&lt;button type="button" class="close" &gt;&times;&lt;/button&gt;

[slide data-transition="horizontal3d"]
##JS插件
+ JavaScript 插件可以单个引入（使用 Bootstrap 提供的单个 *.js 文件），或者一次性全部引入（使用 bootstrap.js 或压缩版的 bootstrap.min.js）
+ 所有插件都依赖 jQuery，jQuery必须在所有插件之前引入页面。
+ 常用的bootstrap插件:
  + Modal用法： 
    + 通过data属性：在控制器按钮上添加属性 data-toggle="modal" data-target="#myModal"
    + 通过js调用： $('#myModal').modal(options); options: show hide toggle

[slide data-transition="horizontal3d"]
  + Tooltip(工具提示)和PopOver(弹出框)比较：
    + 弹出框依赖于工具提示,所以使用弹出框，必须先引入工具提示的js。
    + 都必须手动初始化
      + $('[data-toggle="tooltip"]').tooltip();
      + $('[data-toggle="popover"]').popover();
    + 用法:
      + 通过data属性：data-toggle="tooltip" data-toggle="popover" data-placement=""
      + 通过js调用：$('#example').tooltip(options)；$('#example').popover(options)； options: show hide 

[slide data-transition="horizontal3d"]
##相关链接
  + Bootstrap英文官网：http://getbootstrap.com/
  + Bootstrap中文官网：http://www.bootcss.com/
  + Github地址：https://github.com/twbs/bootstrap-sass
  + 优站精选：http://http://expo.bootcss.com/



