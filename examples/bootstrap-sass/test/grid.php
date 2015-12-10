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
      <h4>Grid</h4>
      <div class="container-fluid">
        <div class="row">
          <div class="col-xs-12 col-sm-6 col-md-8 bg-1">.col-xs-12 .col-sm-6 .col-md-8</div>
          <div class="col-xs-6 col-sm-6 col-md-4 bg-2">.col-xs-6 .col-sm-6 .col-md-4</div>
        </div>
        <div class="row">
          <div class="col-xs-6 col-sm-4 bg-3">.col-xs-6 .col-sm-4</div>
          <div class="col-xs-6 col-sm-4 bg-4">.col-xs-6 .col-sm-4</div>
          <div class="col-xs-6 col-sm-4 bg-5">.col-xs-6 .col-sm-4</div>
        </div>
      </div>
    </div>

    <div class="col-md-12">
      <h4>列偏移</h4>
      <div class="container">
        <div class="row">
          <div class="col-md-4 bg-1">.col-md-4</div>
          <div class="col-md-4 col-md-offset-4 bg-2">.col-md-4 .col-md-offset-4</div>
        </div>
        <div class="row">
          <div class="col-md-3 col-md-offset-3 bg-3">.col-md-3 .col-md-offset-3</div>
          <div class="col-md-3 col-md-offset-3 bg-4">.col-md-3 .col-md-offset-3</div>
        </div>
        <div class="row">
          <div class="col-md-6 col-md-offset-3 bg-5">.col-md-6 .col-md-offset-3</div>
        </div>
      </div>
    </div>

    <div class="col-md-12">
      <h4>列排序</h4>
      <div class="container">
        <div class="row">
          <div class="col-md-9 col-md-push-3 bg-1">.col-md-9 .col-md-push-3</div>
          <div class="col-md-3 col-md-pull-9 bg-2">.col-md-3 .col-md-pull-9</div>
        </div>
      </div>
    </div>

    <div class="col-md-12">
      <h4>嵌套列</h4>
      <div class="row">
        <div class="col-md-12 bg-1">
          Level 1: .col-sm-12
          <div class="row bg-2">
            <div class="col-md-3 bg-3 opacity">
              Level 2: .col-xs-8 .col-sm-6
            </div>
            <div class="col-md-9 bg-4 opacity">
              Level 2: .col-xs-4 .col-sm-6
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
<script src="../javascripts/jquery.min.js"></script>
<script src="../dist/js/bootstrap.min.js"></script>
  </body>
</html>
