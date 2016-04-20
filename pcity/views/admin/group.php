<?php include('_header.php');?>

<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">
            <?php if (isset($group['id'])) echo '修改分组'; else echo '新增分组';?>
            <a class="pull-right admin-back" href="/admin/group">返回</a>
        </h1>

        <div class="text-num">
            <form role="form" action="/admin/group/edit<?php if (isset($group['id'])) echo '/' . $group['id'];?>" method="post">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        分组设置
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label>标题</label>
                            <input class="form-control" type="text" name="title" value="<?php if (!empty($group['title'])) echo $group['title'];?>">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">提交</button>
            </form>
        </div>
    </div>
</div>

<?php include('_footer.php');?>