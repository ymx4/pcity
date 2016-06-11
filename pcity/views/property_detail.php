<?php include('_header_no_nav.php'); ?>

<!--在这里编写你的代码-->
<header class="bar bar-nav">
  <h1 class="navbar-title navbar-center"><a href="/property"><img class="am-home" src="/assets/wx/images/home.jpg"></a>报验</h1>
</header> 
<div class="wei" style="height:4.5rem"></div>

<?php if (!empty($error)) : ?>
<div class="am-alert am-alert-warning"><?php echo $error; ?></div>
<?php elseif ($status): ?>
<div class="am-alert am-alert-success">操作成功，点击左上角返回！</div>
<?php endif; ?>

<?php if ($_user['id'] == $property['property_id'] && $property['status'] == 1) : ?>
<form id="propertyForm" class="am-form" method="post" enctype="multipart/form-data">
<?php endif; ?>
<div class="more">
  <table border="0" cellspacing="0" cellpadding="0">
    <tr>
      <th width="20%">标题</th>
      <td width="80%">
        <?php echo $property['title']; ?>
      </td>
    </tr>
    <tr>
      <td><div style="height:10px"></div></td>
    </tr>
    <tr style="margin-top: 9px;">
      <th colspan="2">初验问题汇总</th>
    </tr>
    <tr>
      <td><div style="height:10px"></div></td>
    </tr>
    <tr>
      <td colspan="2">
      <img src="<?php echo $property['problem_image']; ?>" alt="" style="width:300px;">
      </td>
    </tr>
    <tr>
      <td><div style="height:10px"></div></td>
    </tr>
    <tr style="margin-top: 9px;">
      <th colspan="2">问题描述</th>
    </tr>
    <tr>
      <td><div style="height:10px"></div></td>
    </tr>
    <tr>
      <td colspan="2">
      <?php echo $property['description']; ?>
      </td>
    </tr>
    <tr>
      <td><div style="height:10px"></div></td>
    </tr>
    <tr>
      <th>
      整改情况
      </th>
      <td>已完成物业所提问题整改工作</td>
    </tr>
    
    <tr>
      <td><div style="height:10px"></div></td>
    </tr>
    <tr>
      <td colspan="2">
      <img src="<?php echo $property['fix_image']; ?>" alt="" style="width:300px;">
      </td>
    </tr>
    <tr>
      <td><div style="height:10px"></div></td>
    </tr>
    <tr>
      <th>
      物业公司
      </th>
      <td>
        <?php echo $property['property_user']; ?>
      </td>
    </tr>
    <?php if ($_user['id'] == $property['property_id'] && $property['status'] == 1) : ?>
      <tr>
        <td><div style="height:10px"></div></td>
      </tr>
      <tr>
        <th>
        物业意见
        </th>
        <td>
          <select name="status" style="width: 94%;">
            <option value="-1">请选择</option>
            <?= $property_check_options?>
          </select>
        </td>
      </tr>
      <tr>
        <td><div style="height:10px"></div></td>
      </tr>
      <tr>
        <th>
        剩余条数
        </th>
        <td>
          <input type="text" style="width: 88%;" name="unqualified_num" value="<?php echo $property['unqualified_num']; ?>" minlength="3" placeholder="剩余条数" class="am-form-field" required="">
        </td>
      </tr>
    <?php elseif ($property['status'] == 3) : ?>
      <tr>
        <td><div style="height:10px"></div></td>
      </tr>
      <tr>
        <th>
        物业意见
        </th>
        <td>
          未完成整改
        </td>
      </tr>
      <tr>
        <th>
        剩余条数
        </th>
        <td>
          <?php echo $property['unqualified_num']; ?>
        </td>
      </tr>
    <?php elseif ($property['status'] == 2) : ?>
      <tr>
        <td><div style="height:10px"></div></td>
      </tr>
      <tr>
        <th>
        物业意见
        </th>
        <td>
          已完成整改
        </td>
      </tr>
    <?php endif; ?>
    <tr>
      <td><div style="height:60px"></div></td>
    </tr>
  </table>
</div>
<?php if ($_user['id'] == $property['property_id'] && $property['status'] == 1) : ?>
<figure>
  <input type="submit" class="am-btn am-btn-primary btn-loading-example" value="提交" style="width:97%"/>
</figure>
</form>
<?php endif; ?>

<?php include('_footer.php'); ?>