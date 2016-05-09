<?php include('_header.php');?>

<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">
            用户权限
            <a class="pull-right admin-back" href="/admin/member">返回</a>
        </h1>

        <div class="text-num">
            <form role="form" method="post">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        修改用户权限
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label>用户</label>
                            <?php echo $nickname; ?>
                        </div>
                        <div class="form-group">
                            <label>权限</label>
                            <div>
                            <?php foreach ($auth_list as $key => $val) :?>
                                <label class="checkbox-inline">
                                    <input type="checkbox" value="<?php echo $key;?>" name="auth[]" <?php if (in_array($key, $auth)) echo 'checked="checked"';?>><?php echo $val;?>
                                </label>
                            <?php endforeach;?>
                            <div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="post_flag" value="1" />
                <button type="submit" class="btn btn-primary">提交</button>
            </form>
        </div>
    </div>
</div>

<?php include('_footer.php');?>