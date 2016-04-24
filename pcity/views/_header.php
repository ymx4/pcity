<!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $ptitle; ?></title>

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
  <link rel="stylesheet" href="/assets/wx/css/amazeui.min.css">
  <link rel="stylesheet" href="/assets/wx/css/app.css">
  <link rel="stylesheet" href="/assets/wx/css/style.css">
  <script src="/assets/wx/js/jquery-1.8.3.min.js"></script>
</head>
<body>

<!--在这里编写你的代码-->

<header class="bar bar-nav">
  <h1 class="navbar-title navbar-center"><?php echo $ptitle; ?></h1>
  <!-- 按钮触发器， 需要指定 target -->
  <img class="am-btn am-btn-primary left-t" data-am-offcanvas="{target: '#doc-oc-demo2', effect: 'push'}" src="/assets/wx/images/san.jpg">
  <!-- 侧边栏内容 -->
  <div id="doc-oc-demo2" class="am-offcanvas">
    <div class="am-offcanvas-bar">
      <div class="am-offcanvas-content clues">
        <ul>
          <li class="yuan"><img src="/assets/wx/images/logo.jpg" alt=""></li>
          <li class="left-home">
            <ul class="am-list admin-sidebar-list" id="collapase-nav-1">
              <li class="am-panel hover">
                <a href="#" data-am-collapse="{parent: '#collapase-nav-1', target: '#company-nav'}">
                     公告 <i class="am-icon-angle-right am-fr am-margin-right"></i>
                </a>
                <ul class="am-list am-collapse admin-sidebar-sub" id="company-nav">
                    <?php foreach ($announcement_type as $k => $v) : ?>
                      <li><a href="/announcement/index/<?php echo $k; ?>"><?php echo $v; ?></a></li>
                    <?php endforeach; ?>
                </ul>
              </li>
              <li class="am-panel">
                <a data-am-collapse="{parent: '#collapase-nav-1', target: '#user-nav'}">
                    样板库 <i class="am-icon-angle-right am-fr am-margin-right"></i>
                </a>
                <ul class="am-list am-collapse admin-sidebar-sub" id="user-nav">
                    <?php foreach ($template_categories as $k => $v) : ?>
                      <li><a href="/template/index/<?php echo $k; ?>"><?php echo $v['name']; ?></a></li>
                    <?php endforeach; ?>
                </ul>
              </li>
            </ul>
          </li>
          <li class="left-home"><a href="/task/index">任务安排<i class="am-icon-angle-right am-fr am-margin-right"></i></a></li>
        </ul>
      </div>
    </div>
  </div>
</header>