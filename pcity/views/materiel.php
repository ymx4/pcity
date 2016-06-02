<!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>监理</title>
  <!-- Set render engine for 360 browser -->
  <meta name="renderer" content="webkit">

  <!-- No Baidu Siteapp-->
  <meta http-equiv="Cache-Control" content="no-siteapp"/>

  <!-- Add to homescreen for Chrome on Android -->
  <meta name="mobile-web-app-capable" content="yes">

  <!-- Add to homescreen for Safari on iOS -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta name="apple-mobile-web-app-title" content="Amaze UI"/>
  <!-- Tile icon for Win8 (144x144 + tile color) -->
  <meta name="msapplication-TileColor" content="#0e90d2">
  <link rel="stylesheet" href="css/amazeui.min.css">
  <link rel="stylesheet" href="css/app.css">
  <link rel="stylesheet" href="css/style.css">
  <script src="js/jquery-1.8.3.min.js"></script>
  <script>
  $(function(){
  $(".select").each(function(){
    var s=$(this);
    var z=parseInt(s.css("z-index"));
    var dt=$(this).children("dt");
    var dd=$(this).children("dd");
    var _show=function(){dd.slideDown(200);dt.addClass("cur");s.css("z-index",z+1);};   //展开效果
    var _hide=function(){dd.slideUp(200);dt.removeClass("cur");s.css("z-index",z);};    //关闭效果
    dt.click(function(){dd.is(":hidden")?_show():_hide();});
    dd.find("a").click(function(){dt.html($(this).html());_hide();});     //选择效果（如需要传值，可自定义参数，在此处返回对应的“value”值 ）
    $("body").click(function(i){ !$(i.target).parents(".select").first().is(s) ? _hide():"";});
  })
})  
  </script>   

</head>
<body>

<!--在这里编写你的代码-->
<header class="bar bar-nav">
  <h1 class="navbar-title navbar-center"><a href="物料管理.html"><img class="am-home" src="images/home.jpg"></a>物料报验</h1>
</header> 
<div class="wei" style="height:4.5rem"></div>

<div class="more">
  <table border="0" cellspacing="0" cellpadding="0">
    <tr style="margin-top: 9px;">
      <th width="20%">物料名称</th>
      <td width="80%">
        <input type="text" style="width: 94%;" id="doc-vld-name" minlength="3" placeholder=" " class="am-form-field" required/>
      </td>
    </tr>
    <tr>
      <td><div style="height:10px"></div></td>
    </tr>
    <tr>
      <th width="20%">使用部位</th>
      <td>
        <input type="text" style="width: 94%;" id="doc-vld-name" minlength="3" placeholder=" " class="am-form-field" required/>
      </td>
    </tr>
    <tr>
      <td><div style="height:10px"></div></td>
    </tr>
    <tr>
      <th colspan="2">
      物资相片
      </th>
    </tr>
    <tr>
      <td><div style="height:10px"></div></td>
    </tr>
    <tr>
      <td colspan="2">
      <img src="images/jia1.jpg" alt="">
      </td>
    </tr>
    <tr>
      <td><div style="height:10px"></div></td>
    </tr>
    <tr style="margin-top: 9px;">
      <th width="20%">物料数量</th>
      <td>
        <input type="text" style="width: 94%;" id="doc-vld-name" minlength="3" placeholder="填写时加上单位" class="am-form-field" required/>
      </td>
    </tr>
    <tr>
      <td><div style="height:10px"></div></td>
    </tr>
    <tr>
      <th colspan="2">
      XXXXX(物资报验内容)进场，质量证明文件齐全，请予验收（进场时间）
      </th>
    </tr>
    <tr>
      <td><div style="height:10px"></div></td>
    </tr>
    <tr>
      <th>
      监理单位
      </th>
      <td>
        <div class="demo" style="margin-top: 9px;">
          <dl class="select">
            <dt>姓名</dt>
            <dd>
              <ul>
                <li><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
              </ul>
            </dd>
          </dl>
        </div>
      </td>
    </tr>
  </table>
</div>
<figure>
    <a href="物料管理.html"><input type="button" class="am-btn am-btn-primary btn-loading-example" value="提交" style="width:97%"/></a>

</figure>

<!--[if (gte IE 9)|!(IE)]><!-->
<script src="js/jquery.min.js"></script>
<!--<![endif]-->
<!--[if lte IE 8 ]>
<script src="http://libs.baidu.com/jquery/1.11.3/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="assets/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->
<script src="js/amazeui.min.js"></script>
</body>
</html>