<?php include('_header.php'); ?>

<div class="am-tabs" data-am-tabs="" style=" padding-top: 58px;">
  <div class="search">
    <input type="text" id="aq" minlength="3" placeholder="输入关键字" class="am-form-s" required="">
    <input type="button" class="am-btn-s" value="筛选" onclick="asearch();">
    <div class="clearfloat"></div>
  </div>
  <?php if (!empty($_user['auth']) && (in_array('admin', $_user['auth']) || in_array('task/add', $_user['auth']))) :?>
  <a href="javascript: void(0)" class="jia" class="am-btn am-btn-success a-btn" id="doc-prompt-toggle"><img src="/assets/wx/images/jia.png" alt=""></a>
  <?php endif;?>
  <ul class="am-gao cll">
  </ul>

</div>

<div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1">
  <div class="am-modal-dialog" style="position: relative;width:80%;">
    <div class="am-modal-hd" id="t_title">
    </div>
    <div class="am-modal-bd h22">
      <div id="t_content"></div>
      <a href="javascript: void(0)" class="am-close am-close-spin ll" data-am-modal-close>关闭</a>
    </div>
  </div>
</div>
<div class="am-modal am-modal-prompt kuang-i" tabindex="-1" id="my-prompt">
  <div class="am-modal-dialog">
    <div class="am-modal-hd biaot">添加公告</div>
    <div class="more">
      <table border="0" cellspacing="0" cellpadding="0">
        <tr style="margin-top: 9px;">
          <td>
            <input type="text" style="width: 96%;" id="addtt" minlength="3" placeholder="标题可以不填" class="am-form-field" required="">
          </td>
        </tr>
        <tr>
          <td><div style="height:10px"></div></td>
        </tr>
        <tr>
          <td>
            <textarea id="addtc" minlength="10" placeholder="填写内容" maxlength="100" class="am-active" style="width: 99%;padding:4px;background:#eaeaea;float: left;"></textarea>
          </td>
        </tr>
        <tr>
          <td><div style="height:10px"></div></td>
        </tr>
        <tr>
          <td><div style="height:10px"></div></td>
        </tr>
      </table>
    </div>
    <div class="am-modal-footer">
      <span class="am-modal-btn" data-am-modal-cancel>取消</span>
      <span class="am-modal-btn addtask" data-am-modal-confirm>提交</span>
    </div>
  </div>
</div>

<script>
var page = 1;
var sstop = false;
var aq = '';
function nextpage() {
  $.post("/task/getlist/" + page, {q: aq}, function(data){
      var rr = $.parseJSON(data);
      if (rr.code && rr.data && rr.data.length == 0) {
        if (page == 1) $('.am-gao').empty();
      }
      if (rr.code && rr.data && rr.data.length > 0) {
        if (page == 1) $('.am-gao').empty();
        $.each(rr.data,function(n, value) {
          if (value.title == '') {
            var tmptitle = value.content;
          } else {
            var tmptitle = value.title;
          }
          $('.am-gao').append('<li><a href="#" type="button" onclick="t_detail(\'' + value.title + '\', \'' + value.content + '\');" class="am-btn am-btn-primary" data-am-modal="{target: \'#doc-modal-1\', closeViaDimmer: 0, width: 400, height: 225}">'
            + tmptitle + '<span>' + value.create_time + '</span>'
            + '</a><div class="clearfloat"></div></li>'
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

function t_detail(tt, tc)
{
  $('#t_title').html(tt);
  $('#t_content').html(tc);
}
$('.addtask').click(function(){
  if ($('#addtc').val() == '') {
    alert('请填写任务内容');
    return;
  } else {
    $.post("/task/add/" + page, {title: $('#addtt').val(), content: $('#addtc').val()}, function(data){
        var rr = $.parseJSON(data);
        if (rr.code) {
          if (rr.data.title == '') {
            var tmptitle = rr.data.content;
          } else {
            var tmptitle = rr.data.title;
          }
          $('.am-gao').prepend('<li><a href="#" type="button" onclick="t_detail(\'' + rr.data.title + '\', \'' + rr.data.content + '\');" class="am-btn am-btn-primary" data-am-modal="{target: \'#doc-modal-1\', closeViaDimmer: 0, width: 400, height: 225}">'
            + tmptitle + '<span>' + rr.data.create_time + '</span>'
            + '</a><div class="clearfloat"></div></li>'
          );
          $('#addtt').val('');
          $('#addtc').val('');
        } else {
          alert(rr.msg);
        }
    });
  }
});
</script>

<?php include('_footer.php'); ?>