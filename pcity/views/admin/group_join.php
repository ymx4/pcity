<?php include('_header.php');?>

<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">
            用户分组
            <a class="pull-right admin-back" href="/admin/template">返回</a>
        </h1>

        <div class="text-num">
            <form role="form" action="/admin/group/join/<?php echo $user_id; ?>" method="post">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        将用户加入分组
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label>用户</label>
                            <input type="text" name="nickname" value="<?php echo $nickname; ?>"<?php if ($user_id) echo 'disabled="disabled"'; ?> />
                        </div>
                        <div class="form-group">
                            <label>分组</label>
                            <input type="text" name="group" value="<?php echo $group; ?>" />
                        </div>
                            <select class="form-control" name="role">
                                <?php foreach ($roles as $k => $v) : ?>
                                    <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">提交</button>
            </form>
        </div>
    </div>
</div>

<?php include('_footer.php');?>