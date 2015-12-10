<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>Bootstrap Template</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../dist/css/bootstrap.css">
    <link rel="stylesheet" href="../styles/index.css">
  </head>
  <body>
  <div class="panel panel-default">
    <div class="col-md-12">
      <h4>图片</h4>
      <div class="col-md-8 col-md-push-3">
         <img src="../images/hello.jpg" class="img-responsive" alt="Responsive image">
        <div class="mt-15px">
          <img src="../images/hello.jpg" width="200px" height="200px" class="img-rounded">
          <img src="../images/hello.jpg" width="200px" height="200px" class="img-circle">
          <img src="../images/hello.jpg" width="200px" height="200px" class="img-thumbnail">
        </div>
      </div>
    </div>

    <div class="col-md-12">
      <h4>响应式的嵌入式网页元素</h4>
      <div class="col-md-8 col-md-push-2">
        <div class="embed-responsive embed-responsive-16by9">
          <embed src="http://player.youku.com/player.php/sid/XMzI2NTc4NTMy/v.swf" type="application/x-shockwave-flash" class="embed-responsive-item"> </embed>
        </div>
        <div class="embed-responsive embed-responsive-4by3 mt-100px">
          <iframe class="embed-responsive-item" src="https://www.baidu.com"></iframe>
        </div>
      </div>
    </div>

  </div>
<script src="../javascripts/jquery.min.js"></script>
<script src="../dist/js/bootstrap.min.js"></script>
  </body>
</html>

