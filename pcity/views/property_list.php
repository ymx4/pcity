<?php include('_header.php'); ?>

<div class="am-tabs" data-am-tabs="">
  <div class="search">
    <input type="text" id="aq" minlength="3" placeholder="输入关键字" class="am-form-s" required="">
    <input type="button" class="am-btn-s" value="筛选" onclick="asearch();">
    <div class="clearfloat"></div>
  </div>
  
  <ul class="am-tabs-nav am-nav am-nav-tabs" style="border-top:solid 1px #cccccc;">
    <?php if ($_user['role'] == 3) : ?>
    <li class="am-active" onclick="tabSwitch(0)" style="width:50%">
      <a href="#tab1">建设单位</a>
    </li>
    <?php endif; ?>
    <?php if ($_user['role'] == 4) : ?>
    <li class="am-active" onclick="tabSwitch(0)" style="width:50%">
      <a href="#tab1">物业公司</a>
    </li>
    <?php endif; ?>
    <li class="" onclick="tabSwitch(1)" style="width:50%">
      <a href="#tab2">验收完成</a>
    </li>
  </ul>
  <div class="am-tabs-bd" >
    <div class="am-tab-panel am-fade am-active am-in" id="tab1">
      <ul>
        <?php if ($_user['role'] == 3) : ?>
        <li>
          <div class="title-video" style="background:none;">
          <a href="/property/add" class="bth-jia">添加</a>
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

<script>
var page = 1;
var comp = 0;
var sstop = false;
var aq = '';
function nextpage() {
  $.post("/property/getlist/" + page, {comp: comp,q: aq}, function(data){
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
            + '</td></tr></table></a></div></li>'
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