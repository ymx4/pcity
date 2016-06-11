<?php include('_header_no_nav.php'); ?>

<!--在这里编写你的代码-->
<header class="bar bar-nav">
  <h1 class="navbar-title navbar-center"><a href="/property"><img class="am-home" src="/assets/wx/images/home.jpg"></a>问题报验</h1>
</header> 
<div class="wei" style="height:4.5rem"></div>
<?php if (!empty($error)) : ?>
<div class="am-alert am-alert-warning"><?php echo $error; ?></div>
<?php endif; ?>
<form id="propertyForm" class="am-form" action="/property/add" method="post" enctype="multipart/form-data">
  <div class="more">
    <table border="0" cellspacing="0" cellpadding="0">
      <tr style="margin-top: 9px;">
        <th width="20%">标题</th>
        <td width="80%">
          <input type="text" style="width: 94%;" name="title" value="<?php echo $property['title']; ?>" minlength="3" placeholder=" " class="am-form-field" required/>
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
          <div class="am-form-group am-form-file">
            <button type="button" class="am-btn am-btn-primary am-btn-sm">
              <i class="am-icon-cloud-upload"></i> 选择要上传的相片
            </button>
            <input type="file" name="up_problem_image">
          </div>
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
        <textarea name="description" minlength="10" placeholder="填写内容" maxlength="100" class="am-active" style="width: 97%;padding:4px;background:#eaeaea;float: left;"><?php echo $property['description']; ?></textarea>
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
          <div class="am-form-group am-form-file">
            <button type="button" class="am-btn am-btn-primary am-btn-sm">
              <i class="am-icon-cloud-upload"></i> 选择要上传的相片
            </button>
            <input type="file" name="up_fix_image">
          </div>
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
          <select name="property_id" style="width: 94%;">
            <option value="-1">请选择</option>
            <?= $property_options?>
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