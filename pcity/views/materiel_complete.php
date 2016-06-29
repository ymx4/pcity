<?php include('_header.php'); ?>

<div class="am-tabs" data-am-tabs="">
  <div class="search">
    <input type="text" id="aq" minlength="3" placeholder="输入关键字" class="am-form-s" required="">
    <input type="button" class="am-btn-s" value="筛选" onclick="asearch();">
    <div class="clearfloat"></div>
  </div>

  <div class="am-tabs-bd" >
    <div class="am-tab-panel am-fade am-active am-in">
      <ul>
        <div id="list-m" class="wei"></div>
      </ul>
    </div>
  </div>
</div>

<script>
var page = 1;
var sstop = false;
var aq = '';
function nextpage() {
  $.post("/materiel/getlist/" + page, {q: aq, total: 1}, function(data){
      var rr = $.parseJSON(data);
      if (rr.code && rr.data && rr.data.length == 0) {
        if (page == 1) $('li.li-m').remove();
      }
      if (rr.code && rr.data && rr.data.length > 0) {
        if (page == 1) $('li.li-m').remove();
        $.each(rr.data,function(n, value) {
          $('#list-m').before('<li class="li-m"><div class="title-video"><a href="' + value.url + '" class="table">'
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