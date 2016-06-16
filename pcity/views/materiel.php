<?php include('_header_no_nav.php'); ?>

<!--在这里编写你的代码-->
<header class="bar bar-nav">
  <h1 class="navbar-title navbar-center"><a href="/materiel"><img class="am-home" src="/assets/wx/images/home.jpg"></a>物料报验</h1>
</header> 
<div class="wei" style="height:4.5rem"></div>
<?php if (!empty($error)) : ?>
<div class="am-alert am-alert-warning"><?php echo $error; ?></div>
<?php endif; ?>
<form id="materielForm" class="am-form" action="/materiel/add" method="post" enctype="multipart/form-data">
  <div class="more">
    <table border="0" cellspacing="0" cellpadding="0">
      <tr style="margin-top: 9px;">
        <th width="20%">物料名称</th>
        <td width="80%">
          <input type="text" style="width: 94%;" name="title" value="<?php echo $materiel['title']; ?>" minlength="3" placeholder=" " class="am-form-field" required/>
        </td>
      </tr>
      <tr>
        <td><div style="height:10px"></div></td>
      </tr>
      <tr>
        <th width="20%">使用部位</th>
        <td>
          <input type="text" style="width: 94%;" name="position" value="<?php echo $materiel['position']; ?>" minlength="3" placeholder=" " class="am-form-field" required/>
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
      <tr style="margin-top: 9px;">
        <th width="20%">物料数量</th>
        <td>
          <input type="text" style="width: 94%;" name="quantity" value="<?php echo $materiel['quantity']; ?>" minlength="1" placeholder="填写时加上单位" class="am-form-field" required/>
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