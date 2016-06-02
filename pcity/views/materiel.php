<?php include('_header_no_nav.php'); ?>

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

<!--在这里编写你的代码-->
<header class="bar bar-nav">
  <h1 class="navbar-title navbar-center"><a href="物料管理.html"><img class="am-home" src="/assets/wx/images/home.jpg"></a>物料报验</h1>
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
      <img src="/assets/wx/images/jia1.jpg" alt="">
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
<?php include('_footer.php'); ?>