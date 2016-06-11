<?php include('_header_no_nav.php'); ?>

<!--在这里编写你的代码-->
<header class="bar bar-nav">
  <h1 class="navbar-title navbar-center"><a href="/scene"><img class="am-home" src="/assets/wx/images/home.jpg"></a>报验</h1>
</header> 
<div class="wei" style="height:4.5rem"></div>
<?php if (!empty($error)) : ?>
<div class="am-alert am-alert-warning"><?php echo $error; ?></div>
<?php endif; ?>
<form id="sceneForm" class="am-form" action="/scene/add" method="post" enctype="multipart/form-data">
  <div class="more">
    <table border="0" cellspacing="0" cellpadding="0">
      <tr style="margin-top: 9px;">
        <th width="20%">报验内容</th>
        <td width="80%">
          <input type="text" style="width: 94%;" name="title" value="<?php echo $scene['title']; ?>" minlength="3" placeholder=" " class="am-form-field" required/>
        </td>
      </tr>
      <tr>
        <td><div style="height:10px"></div></td>
      </tr>
      <tr>
        <th width="20%">详细地址</th>
        <td>
          <input type="text" style="width: 94%;" name="address" value="<?php echo $scene['address']; ?>" minlength="3" placeholder=" " class="am-form-field" required/>
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
          <div class="am-form-group am-form-file">
            <button type="button" class="am-btn am-btn-primary am-btn-sm">
              <i class="am-icon-cloud-upload"></i> 选择要上传的相片
            </button>
            <input type="file" name="up_constructor_image">
          </div>
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
          <select name="supervisor_id" style="width: 94%;">
            <option value="-1">请选择</option>
            <?= $supervisor_options?>
          </select>
        </td>
      </tr>
    </table>
  </div>
  <figure>
    <input type="submit" class="am-btn am-btn-primary btn-loading-example" value="提交" style="width:97%"/>
  </figure>
</form>
<?php include('_footer.php'); ?>