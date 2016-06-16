<?php include('_header.php'); ?>

<div class="am-tabs" data-am-tabs="">
  <div class="search">
    <input type="text" id="aq" minlength="3" placeholder="输入关键字" class="am-form-s" required="">
    <input type="button" class="am-btn-s" value="筛选" onclick="asearch();">
    <div class="clearfloat"></div>
  </div>
  
  <ul class="am-tabs-nav am-nav am-nav-tabs" style="border-top:solid 1px #cccccc;">
    <?php if ($_user['role'] == 1) : ?>
    <li class="am-active" onclick="tabSwitch(0)" style="width:50%">
      <a href="#tab1">施工单位</a>
    </li>
    <?php endif; ?>
    <?php if ($_user['role'] == 2) : ?>
    <li class="am-active" onclick="tabSwitch(0)" style="width:50%">
      <a href="#tab1">监理单位</a>
    </li>
    <?php endif; ?>
    <?php if ($_user['role'] == 3) : ?>
    <li class="am-active" onclick="tabSwitch(0)" style="width:50%">
      <a href="#tab1">建设单位</a>
    </li>
    <?php endif; ?>
    <li class="" onclick="tabSwitch(1)" style="width:50%">
      <a href="#tab2">验收完成</a>
    </li>
  </ul>
  <div class="am-tabs-bd" >
    <div class="am-tab-panel am-fade am-active am-in" id="tab1">
      <ul>
        <?php if ($_user['role'] == 1) : ?>
        <li>
          <div class="title-video" style="background:none;">
          <a href="/scene/add" class="bth-jia">添加</a>
          </div>
        </li>
        <?php endif; ?>
        <div id="list-m" class="wei"></div>
      </ul>
    </div>
    <div class="am-tab-panel am-fade" id="tab2">
      <ul>
        <div id="list-m-comp" class="wei"></div>
      </ul>
    </div>
  </div>
</div>

<!-- 弹窗部分 -->
<div class="am-modal am-modal-prompt kuang-i" tabindex="-1" id="my-prompt">
  <div class="am-modal-dialog">
    <div class="am-modal-hd biaot">Amaze UI</div>
    <div class="more">
  <table border="0" cellspacing="0" cellpadding="0">
    <tr style="margin-top: 9px;">
      <th width="20%">物资名称</th>
      <td>
        <div class="demo" style=" left: 8px;">
          <dl class="select" style="margin:0;">
            <dt>下拉</dt>
            <dd style="left:8px;">
              <ul>
                <li><a href="#">下拉1</a></li>
                <li><a href="#">下拉2</a></li>
                <li><a href="#">下拉3</a></li>
                <li><a href="#">下拉4</a></li>
                <li><a href="#">下拉5</a></li>
                <li><a href="#">下拉6</a></li>
                <li><a href="#">下拉1</a></li>
                <li><a href="#">下拉2</a></li>
                <li><a href="#">下拉3</a></li>
                <li><a href="#">下拉4</a></li>
              </ul>
            </dd>
          </dl>
        </div>
      </td>
    </tr>
    <tr>
      <td><div style="height:10px"></div></td>
    </tr>
    <<!-- tr>
      <th width="20%">进场日期</th>
      <td>
        <input size="16" type="text" value="添加日历" readonly class="form-datetime am-form-field" style="    width: 88.4%;margin-left: 2%;">
      </td>

    </tr> -->
    
    <tr>
      <td><div style="height:10px"></div></td>
    </tr>
    <tr style="margin-top: 9px;">
      <th width="20%">人员姓名</th>
      <td>
        <div class="demo" style=" left: 8px;">
          <dl class="select" style="margin:0;">
            <dt>下拉</dt>
            <dd style="left:8px;">
              <ul>
                <li><a href="#">下拉1</a></li>
                <li><a href="#">下拉2</a></li>
                <li><a href="#">下拉3</a></li>
                <li><a href="#">下拉4</a></li>
                <li><a href="#">下拉5</a></li>
                <li><a href="#">下拉6</a></li>
                <li><a href="#">下拉1</a></li>
                <li><a href="#">下拉2</a></li>
                <li><a href="#">下拉3</a></li>
                <li><a href="#">下拉4</a></li>
              </ul>
            </dd>
          </dl>
        </div>
      </td>
    </tr>
    <tr>
      <td><div style="height:10px"></div></td>
    </tr>
  </table>
</div>
    <div class="am-modal-footer">
      <span class="am-modal-btn" data-am-modal-cancel>取消</span>
      <span class="am-modal-btn" data-am-modal-confirm>提交</span>
    </div>
  </div>
</div>

<script>
var page = 1;
var comp = 0;
var sstop = false;
var aq = '';
function nextpage() {
  $.post("/scene/getlist/" + page, {comp: comp,q: aq}, function(data){
      var rr = $.parseJSON(data);
      if (rr.code && rr.data && rr.data.length == 0) {
        if (page == 1) $('li.li-m').remove();
      }
      if (rr.code && rr.data && rr.data.length > 0) {
        if (comp == 0) {
          var mid = "#list-m";
        } else {
          var mid = "#list-m-comp";
        }
        if (page == 1) $('li.li-m').remove();
        $.each(rr.data,function(n, value) {
          $(mid).before('<li class="li-m"><div class="title-video"><a href="' + value.url + '" class="table">'
            + '<table width="100%">'
            + '<tr><td style=" font-size: 1.2em;padding: 0.2em 0em 0.2em;border-bottom: solid 1px #ccc;border-bottom: solid 1px #ccc;line-height:2em">'
            + value.title
            + '</td></tr><tr><td style=" line-height:2em;font-size: 1.2em;padding: 0.2em 0em 0em;">'
            + value.create_time
            + '</td></tr><tr><td style=" line-height:2em;font-size: 1.2em;padding: 0.2em 0em 0em;border-top: solid 1px #ccc;">'
            + '<div style="color:#18b6bf;">'
            + value.desc
            + '</div></td></tr></table></a></div></li>'
          );
        });
        if (rr.stop) {
          sstop = true;
        }
        page++;
      }
  });
}

function tabSwitch(tcomp)
{
  if (comp == tcomp) {
    return;
  }
  page = 1;
  comp = tcomp;
  sstop = false;
  $('#aq').val('');
  aq = '';
  nextpage();
}

function asearch()
{
  page = 1;
  sstop = false;
  aq = $('#aq').val();
  nextpage();
}

function aload()
{
  if (page == 1) {
    nextpage();
  }
}

$(window).scroll(function(){
  if (sstop) return;
  totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop());
  if ($(document).height() <= totalheight) {
    nextpage();
  }
});

setTimeout(function () { 
  aload();
}, 300);

</script>

<?php include('_footer.php'); ?>