<?php include('_header.php');?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">用户管理</h1>
    </div>
    <!-- /.col-md-12 -->
</div>
<div class="row">
    <div class="col-md-4">
        <form>
            <div class="form-group">
                <label>用户昵称</label>
                <input type="text" name="nickname" />
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-default" value="搜索" />
            </div>
        </form>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                用户列表
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive" style="margin-top:30px">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>头像</th>
                                <th>昵称</th>
                                <th>绑定时间</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($member_list as $key => $row) :?>
                            <tr>
                                <td><img width="100px" src="<?php echo $row['headimgurl'];?>"></td>
                                <td><?php echo $row['nickname']; ?></td>
                                <td><?php echo $row['create_time']; ?></td>
                                <td>
                                    <a href="/admin/group/join/<?php echo $row['id'];?>">加入分组</a>
                                </td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>

                    <?php if (!empty($member_list)) echo $this->pagination->create_links();?>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
</div>

<?php include('_footer.php');?>