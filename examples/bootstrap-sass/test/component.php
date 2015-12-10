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
    <div class="col-md-10">
      <h4>字体图标</h4>
      <div class="col-md-8 col-md-push-3">
         <div class="form-group">
           <span class="glyphicon glyphicon-plus larger"></span>
           <span class="glyphicon glyphicon-search"></span>
           <span class="glyphicon glyphicon-qrcode"></span>
         </div>
         <!-- Example1 -->
         <form class="form-inline">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Search">
          </div>
          <button type="button" class="btn btn-default">
            <span class="glyphicon glyphicon-search"></span>
          </button>
         </form>
         <!-- Example2 -->
         <div class="alert alert-danger mt-15px" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign"></span>
            <span>Error: Enter a valid email address</span>
         </div>
      </div>
    </div>

    <div class="col-md-10">
      <h4>面包屑</h4>
      <div class="col-md-8 col-md-push-3">
        <ol class="breadcrumb">
          <li><a href="#">Home</a></li>
          <li><a href="#">Library</a></li>
          <li class="active">Data</li>
        </ol>
      </div>
    </div>

    <div class="col-md-10">
      <h4>分页</h4>
      <div class="col-md-8 col-md-push-3">
        <nav>
          <ul class="pagination">
            <li>
              <a href="#">&laquo;</a>
            </li>
            <li class="active"><a href="#">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#">4</a></li>
            <li class="disabled"><a href="#">5</a></li>
            <li>
              <a href="#">&raquo;</a>
            </li>
          </ul>
        </nav>

        <nav>
          <ul class="pager">
            <li><a href="#">Previous</a></li>
            <li><a href="#">Next</a></li>
          </ul>
        </nav>

        <nav>
          <ul class="pager">
            <li class="previous"><a href="#">&larr;Older</a></li>
            <li class="next"><a href="#">Newer &rarr;</a></li>
          </ul>
        </nav>
      </div>
    </div>

    <div class="col-md-10">
      <h4>徽章</h4>
      <div class="col-md-3 col-md-push-3">
        <ul class="list-group">
          <li class="list-group-item">
            <span class="badge">14</span>
            Hello Badge
          </li>
          <li class="list-group-item">
            <span class="badge">12</span>
            Simple Badge
          </li>
        </ul>
      </div>
    </div>

    <div class="col-md-10">
      <h4>缩略图</h4>
      <div class="col-md-8 col-md-push-3">
        <div class="row">
          <div class="col-xs-6 col-md-3">
            <a href="#" class="thumbnail">
              <img src="http://vincenthou.qiniudn.com/2d6aaebdad46423e7bce1d86.jpg" alt="">
            </a>
          </div>
          <div class="col-xs-6 col-md-3">
            <a href="#" class="thumbnail">
              <img src="http://vincenthou.qiniudn.com/2d6aaebdad46423e7bce1d86.jpg" alt="...">
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-10">
      <h4>面板</h4>
      <div class="col-md-8 col-md-push-3">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Panel title</h3>
          </div>
          <div class="panel-body">
            Panel content
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-10">
      <h4>警告框</h4>
      <div class="col-md-8 col-md-push-3">
        <div class="alert alert-success" role="alert">
          Well done! You successfully read this important alert message.
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <div class="alert alert-info" role="alert">Heads up! This alert needs your attention, but it's not super important.</div>
        <div class="alert alert-warning" role="alert">Warning! Better check yourself, you're not looking too good.</div>
        <div class="alert alert-danger" role="alert">Oh snap! Change a few things up and try submitting again.</div>
      </div>
    </div>
  </div>
<script src="../javascripts/jquery.min.js"></script>
<script src="../dist/js/bootstrap.min.js"></script>
  </body>
</html>
