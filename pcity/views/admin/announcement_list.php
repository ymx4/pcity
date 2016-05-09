<?php include('_header.php');?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">公告管理</h1>
    </div>
    <!-- /.col-md-12 -->
</div>
<div class="row">
    <div class="col-md-4">
        <form>
            <div class="form-group">
                <label>公告类型</label>
                <select class="form-control" id="anntype" name="type">
                    <option value="-1">所有</option>
                    <?php foreach ($announcement_type_list as $tid => $tname) : ?>
                        <option value="<?php echo $tid; ?>"<?php if ($tid == $ptype) echo ' selected="selected"'; ?>><?php echo $tname; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-default" value="搜索" />
            </div>
        </form>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                公告列表
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive" style="margin-top:30px">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th><a href="/admin/announcement/edit"><i class="fa fa-plus-square"> 添加</a></th>
                                <th>标题</th>
                                <th>修改</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($announcement_list as $key => $row) :?>
                            <tr id="tr<?php echo $row['id'];?>">
                                <td><?php echo $key + 1;?></td>
                                <td><?php echo $row['title'];?></td>
                                <td><a href="/admin/announcement/edit/<?php echo $row['id'];?>">修改</a>
                                    <a onclick="del(<?php echo $row['id'];?>)" href="javascript:;">删除</a>
                                </td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>

                    <?php if (!empty($announcement_list)) echo $this->pagination->create_links();?>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
</div>
<script type="text/javascript">
    function del(id) {
        if (confirm('确定要删除吗？')) {
            var tr = $("#tr" + id);
            $.get("/admin/announcement/delete/" + id,function(data){
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