<?php include('_header.php');?>

<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">
            <?php if (isset($company['id'])) echo '修改'; else echo '新增';?>
            <a class="pull-right admin-back" href="/admin/company">返回</a>
        </h1>

        <div class="text-num">
            <form role="form" action="/admin/company/edit<?php if (isset($company['id'])) echo '/' . $company['id'];?>" method="post" enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label>公司名</label>
                            <input class="form-control" type="text" name="name" value="<?php if (!empty($company['name'])) echo $company['name'];?>">
                        </div>
                        <div class="form-group">
                            <label>公司类型</label>
                            <select class="form-control" name="type">
                                <option value="-1">请选择</option>
                                <?php foreach ($company_type as $k => $v) : ?>
                                    <option value="<?php echo $k; ?>" <?php if ($company['type'] == $k) echo 'selected="selected"'; ?>><?php echo $v; ?></option>
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