<?php include('_header.php');?>

<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">
            <?php if (isset($task['id'])) echo '修改任务'; else echo '新增任务';?>
            <a class="pull-right admin-back" href="/admin/task">返回</a>
        </h1>

        <div class="text-num">
            <form role="form" action="/admin/task/edit<?php if (isset($task['id'])) echo '/' . $task['id'];?>" method="post" enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        任务设置
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label>标题</label>
                            <input class="form-control" type="text" name="title" value="<?php if (!empty($task['title'])) echo $task['title'];?>">
                        </div>
                        <div class="form-group">
                            <label>内容</label>
                            <textarea class="form-control" name="content"><?php if (!empty($task['content'])) echo $task['content'];?></textarea>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">提交</button>
            </form>
        </div>
    </div>
</div>

<?php include('_footer.php');?>