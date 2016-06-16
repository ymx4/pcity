<?php include('_header.php'); ?>

<div class="am-tabs" data-am-tabs="" style=" padding-top: 58px;">
  <div class="search">
    <input type="text" id="aq" minlength="3" placeholder="输入关键字" class="am-form-s" required="">
    <input type="button" class="am-btn-s" value="筛选" onclick="asearch();">
    <div class="clearfloat"></div>
  </div>
  <ul class="am-li">
  </ul>
</div>

<script>
var page = 1;
var sstop = false;
var aq = '';
function nextpage() {
  $.post("/template/getlist/<?php echo $cid; ?>/" + page, {q: aq}, function(data){
      var rr = $.parseJSON(data);
      if (rr.code && rr.data && rr.data.length == 0) {
        if (page == 1) $('.am-li').empty();
      }
      if (rr.code && rr.data && rr.data.length > 0) {
        if (page == 1) $('.am-li').empty();
        $.each(rr.data,function(n, value) {
          $('.am-li').append('<li><a href="' + value.url + '">'
            + '<h2>' + value.title + '</h2>'
            + '<p>' + value.create_time + '</p>'
            + '<div class="clearfloat"></div></a></li>'
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