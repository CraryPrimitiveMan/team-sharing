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
      <h4>内联表单</h4>
      <div class="col-md-8 col-md-push-3">
         <form class="form-inline">
          <div class="form-group">
            <label for="exampleInputName2">Name</label>
            <input type="text" class="form-control" id="exampleInputName2" placeholder="Jane Doe">
          </div>
          <div class="form-group">
            <label for="exampleInputEmail2">Email</label>
            <input type="email" class="form-control" id="exampleInputEmail2" placeholder="jane.doe@example.com">
          </div>
          <button type="submit" class="btn btn-default">Send invitation</button>
        </form>
      </div>
    </div>

    <div class="col-md-12">
      <h4>内联checkbox</h4>
      <div class="col-md-8 col-md-push-3">
        <div class="checkbox-inline">
          <input type="checkbox" id="inlineCheckbox1" value="option1"> 1
        </div>
        <div class="checkbox-inline">
          <input type="checkbox" id="inlineCheckbox2" value="option2"> 2
        </div>
        <div class="checkbox-inline">
          <input type="checkbox" id="inlineCheckbox3" value="option3"> 3
        </div>
      </div>
    </div>

    <div class="col-md-12">
      <h4>水平表单</h4>
      <div class="col-md-8 col-md-push-3">
        <form class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
              <p class="form-control-static">email@example.com</p>
            </div>
          </div>
          <div class="form-group">
            <label for="inputPassword" class="col-sm-2 control-label">Password</label>
            <div class="col-sm-10">
              <input type="password" class="form-control" id="inputPassword" placeholder="Password">
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="col-md-12">
      <h4>按钮</h4>
      <div class="col-md-8 col-md-push-3">
          <a class="btn btn-default" href="#" role="button">Link</a>
          <button class="btn btn-default" type="submit">Button</button>
          <input class="btn btn-default" type="button" value="Input">
          <input class="btn btn-default" type="submit" value="Submit">
          <hr/>
          <button type="button" class="btn btn-default">（默认样式）Default</button>
          <button type="button" class="btn btn-primary">（首选项）Primary</button>
          <button type="button" class="btn btn-success">（成功）Success</button>
          <button type="button" class="btn btn-info">（一般信息）Info</button>
          <button type="button" class="btn btn-warning">（警告）Warning</button>
          <button type="button" class="btn btn-danger">（危险）Danger</button>
          <button type="button" class="btn btn-link">（链接）Link</button>
          <hr/>
          <button type="button" class="btn btn-primary btn-lg">Primary button</button>
          <button type="button" class="btn btn-primary btn-md">Primary button</button>
          <button type="button" class="btn btn-primary btn-sm">Primary button</button>
          <hr/>
          <button type="button" class="btn btn-lg btn-primary active">Primary button</button>
          <button type="button" class="btn btn-primary btn-lg" disabled="disabled">Button</button>
      </div>
    </div>
  </div>
<script src="../javascripts/jquery.min.js"></script>
<script src="../dist/js/bootstrap.min.js"></script>
  </body>
</html>
