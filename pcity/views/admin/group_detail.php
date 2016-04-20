<?php include('_header.php');?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">分组详情 - <?php echo $group['title']; ?></h1>
        <a class="pull-right admin-back" href="/admin/group/join/0/<?php echo $group['id']; ?>">添加分组用户</a>
    </div>
    <!-- /.col-md-12 -->
</div>
<?php $i = 0; ?>
<?php foreach ($roles as $role => $role_title) : ?>
    <?php if (!empty($group_role[$role])) : ?>
        <?php $i++; if ($i % 2 == 1) echo '<div class="row">'; ?>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php echo $role_title; ?>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive" style="margin-top:30px">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>头像</th>
                                        <th>昵称</th>
                                        <th>删除</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($group_role[$role] as $row) : ?>
                                    <tr id="tr<?php echo $group['id'] . '-' . $row['user_id']; ?>">
                                        <td width="20%"><img width="100%" src="<?php echo $row['headimgurl'];?>"></td>
                                        <td><?php echo $row['nickname'];?></td>
                                        <td>
                                            <a onclick="del('<?php echo $group['id'] . '/' . $row['user_id']; ?>', '<?php echo $group['id'] . '-' . $row['user_id']; ?>')" href="javascript:;">删除</a>
                                        </td>
                                    </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
        <?php if ($i % 2 == 1) echo '</div>'; ?>
    <?php endif; ?>
<?php endforeach; ?>
<script type="text/javascript">
    function del(param, id) {
        if (confirm('确定要删除吗？')) {
            var tr = $("#tr" + id);
            $.get("/admin/group/delrole/" + param,function(data){
                data = JSON.parse(data);
                if (data.code == 1) {
                    tr.remove();
                } else if (data.message) {
                    alert(data.message);
                } else {
                    alert('操作失败');
                }
            });
        }
    };
</script>

<?php include('_footer.php');?>