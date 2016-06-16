<?php include('_header.php');?>

<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">
            <?php if (isset($announcement['id'])) echo '修改公告'; else echo '新增公告';?>
            <a class="pull-right admin-back" href="/admin/announcement">返回</a>
        </h1>

        <div class="text-num">
            <form role="form" action="/admin/announcement/edit<?php if (isset($announcement['id'])) echo '/' . $announcement['id'];?>" method="post" enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        公告设置
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label>标题</label>
                            <input class="form-control" type="text" name="title" value="<?php if (!empty($announcement['title'])) echo $announcement['title'];?>">
                        </div>
                        <div class="form-group">
                            <label>公告类型</label>
                            <select class="form-control" id="anntype" name="type">
                                <option value="-1">所有</option>
                                <?php foreach ($announcement_type_list as $tid => $tname) : ?>
                                    <option value="<?php echo $tid; ?>"<?php if (isset($announcement['type']) && $tid == $announcement['type']) echo ' selected="selected"'; ?>><?php echo $tname; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>上传 (doc|docx|pdf|jpg|jpeg|png)</label>
                            <?php if (!empty($announcement_file)) echo sprintf('<a href="%s">%s</a>', $announcement_file, $announcement['file_name']); ?>
                            <input type="file" name="up_file">
                        </div>
                        <!-- <div class="form-group">
                            <label>内容</label>
                            <textarea class="form-control" name="content"><?php if (!empty($announcement['content'])) echo $announcement['content'];?></textarea>
                        </div> -->
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">提交</button>
            </form>
        </div>
    </div>
</div>

<?php include('_footer.php');?>