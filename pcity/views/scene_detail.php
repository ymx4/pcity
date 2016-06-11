<?php include('_header_no_nav.php'); ?>

<!--在这里编写你的代码-->
<header class="bar bar-nav">
  <h1 class="navbar-title navbar-center"><a href="/scene"><img class="am-home" src="/assets/wx/images/home.jpg"></a>报验</h1>
</header> 
<div class="wei" style="height:4.5rem"></div>

<?php if (!empty($error)) : ?>
<div class="am-alert am-alert-warning"><?php echo $error; ?></div>
<?php elseif ($status): ?>
<div class="am-alert am-alert-success">操作成功，点击左上角返回！</div>
<?php endif; ?>

<?php if (($_user['id'] == $scene['supervisor_id'] && $scene['status'] == 1) || ($_user['id'] == $scene['builder_id'] && $scene['status'] == 2)) : ?>
<form id="sceneForm" class="am-form" method="post" enctype="multipart/form-data">
<?php endif; ?>
<div class="more">
  <table border="0" cellspacing="0" cellpadding="0">
    <tr style="margin-top: 9px;">
      <th width="20%">报验内容</th>
      <td width="80%">
        <?php echo $scene['title']; ?>
      </td>
    </tr>
    <tr>
      <td><div style="height:10px"></div></td>
    </tr>
    <tr>
      <th width="10%">详细地址</th>
      <td>
        <?php echo $scene['address']; ?>
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
      <img src="<?php echo $scene['constructor_image']; ?>" alt="" style="width:300px;">
      </td>
    </tr>
    <tr>
      <td><div style="height:10px"></div></td>
    </tr>
    <tr>
      <th colspan="2">
        <?php echo $scene['constructor']; ?> 自检合格于 <?php echo date('Y-m-d H:i A', $scene['create_time']); ?>
      </th>
    </tr>
    <tr>
      <td><div style="height:10px"></div></td>
    </tr>
    <tr>
      <th>
      监理单位
      </th>
      <td><?php echo $scene['supervisor']; ?></td>
    </tr>
    <?php if ($_user['id'] == $scene['supervisor_id'] && $scene['status'] == 1) : ?>
      <tr>
        <td><div style="height:10px"></div></td>
      </tr>
      <tr>
        <th colspan="2">
        相关相片
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
            <input type="file" name="up_supervisor_image">
          </div>
        </td>
      </tr>
      <tr>
        <td><div style="height:10px"></div></td>
      </tr>
      <tr style="margin-top: 9px;">
        <th width="20%">验收意见</th>
        <td>
          <select name="supervisor_check" style="width: 94%;">
            <option value="-1">请选择</option>
            <?= $supervisor_check_options?>
          </select>
        </td>
      </tr>
      <tr>
        <td><div style="height:10px"></div></td>
      </tr>
      <tr>
        <th>
        建设单位
        </th>
        <td>
          <select name="builder_id" style="width: 94%;">
            <option value="-1">请选择</option>
            <?= $builder_options?>
          </select>
        </td>
      </tr>
    <?php else : ?>
      <?php if ($scene['supervisor_image']) : ?>
        <tr>
          <td><div style="height:10px"></div></td>
        </tr>
        <tr>
          <th colspan="2">
          相关相片
          </th>
        </tr>
        <tr>
          <td><div style="height:10px"></div></td>
        </tr>
        <tr>
          <td colspan="2">
          <img src="<?php echo $scene['supervisor_image']; ?>" alt="" style="width:300px;">
          </td>
        </tr>
      <?php endif; ?>
      <?php if ($scene['supervisor_check']) : ?>
        <tr>
          <td><div style="height:10px"></div></td>
        </tr>
        <tr>
          <th>验收意见</th>
          <td><?php echo $scene['supervisor_check']; ?></td>
        </tr>
      <?php endif; ?>
    <?php endif; ?>

    <?php if ($_user['id'] == $scene['builder_id'] && $scene['status'] == 2) : ?>
      <tr>
        <td><div style="height:10px"></div></td>
      </tr>
      <tr>
        <th>
        建设单位
        </th>
        <td>
          <?php echo $scene['builder']; ?>
        </td>
      </tr>
      <tr>
        <td><div style="height:10px"></div></td>
      </tr>
      <tr>
        <th colspan="2">
        相关相片
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
            <input type="file" name="up_builder_image">
          </div>
        </td>
      </tr>
      <tr>
        <td><div style="height:10px"></div></td>
      </tr>
      <tr style="margin-top: 9px;">
        <th width="20%">验收意见</th>
        <td>
          <select name="builder_check" style="width: 94%;">
            <option value="-1">请选择</option>
            <?= $builder_check_options?>
          </select>
        </td>
      </tr>
    <?php else : ?>
      <?php if ($scene['builder']) : ?>
        <tr>
          <td><div style="height:10px"></div></td>
        </tr>
        <tr>
          <th>
          建设单位
          </th>
          <td>
            <?php echo $scene['builder']; ?>
          </td>
        </tr>
      <?php endif; ?>
      <?php if ($scene['builder_image']) : ?>
        <tr>
          <td><div style="height:10px"></div></td>
        </tr>
        <tr>
          <th colspan="2">
          相关相片
          </th>
        </tr>
        <tr>
          <td><div style="height:10px"></div></td>
        </tr>
        <tr>
          <td colspan="2">
          <img src="<?php echo $scene['builder_image']; ?>" alt="" style="width:300px;">
          </td>
        </tr>
      <?php endif; ?>
      <?php if ($scene['builder_check']) : ?>
        <tr>
          <td><div style="height:10px"></div></td>
        </tr>
        <tr>
          <th>验收意见</th>
          <td><?php echo $scene['builder_check']; ?></td>
        </tr>
      <?php endif; ?>
    <?php endif; ?>
    <tr>
      <td style="height:60px;"></td>
    </tr>
  </table>
</div>
<?php if (($_user['id'] == $scene['supervisor_id'] && $scene['status'] == 1) || ($_user['id'] == $scene['builder_id'] && $scene['status'] == 2)) : ?>
<figure>
  <input type="submit" class="am-btn am-btn-primary btn-loading-example" value="提交" style="width:97%"/>
</figure>
</form>
<?php endif; ?>

<?php include('_footer.php'); ?>