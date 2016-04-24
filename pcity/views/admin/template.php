<?php include('_header.php');?>

<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">
            <?php if (isset($template['id'])) echo '修改样板'; else echo '新增样板';?>
            <a class="pull-right admin-back" href="/admin/template">返回</a>
        </h1>

        <div class="text-num">
            <form role="form" id="fileupload" action="/admin/template/edit/<?php echo (isset($template['id'])) ? $cid . '/' . $template['id'] : $cid;?>" method="post" enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        样板设置
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label>标题</label>
                            <input class="form-control" type="text" name="title" value="<?php if (!empty($template['title'])) echo $template['title'];?>">
                        </div>
                        <div class="form-group">
                            <label>样板图片</label>
                            <span class="btn btn-success fileinput-button">
                                <i class="glyphicon glyphicon-plus"></i>
                                <span>添加</span>
                                <input type="file" name="up_image" multiple>
                            </span>
                        </div>
                        <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
                        <?php foreach ($fields as $key => $value) : ?>
                            <div class="form-group">
                                <label><?php echo $value['label']; ?></label>
                                <textarea class="form-control" name="<?php echo $key; ?>"><?php if (!empty($template[$key])) echo $template[$key];?></textarea>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">提交</button>
            </form>
        </div>
    </div>
</div>

<script id="template-files" type="text/x-tmpl">
    <tr>
        <td>
            {% if (o.url) { %}
                <img width="150px" src="{%=o.url%}">
            {% } %}
        </td>
        <td>
            <input type="button" class="btn btn-danger" onclick="delimg(this, '{%=o.name%}')" value="删除" />
        </td>
    </tr>
</script>
<script>
var template_id = <?php echo $id; ?>;
$(function () {
    'use strict';

    <?php if (!empty($template['image'])) : ?>
        <?php foreach ($template['image'] as $file) : ?>
            $(tmpl("template-files", <?php echo json_encode($file); ?>)).appendTo($('#fileupload .files'));
        <?php endforeach; ?>
    <?php endif; ?>

    $('#fileupload').fileupload({
        url: '/admin/template/do_upload/<?php echo $id;?>',
        autoUpload: true,
        done: function (e, data) {
            var rr = $.parseJSON(data.result);
            if (rr.code == 0) {
                alert(rr.msg);
            } else {
                template_id = rr.data.id;
                $(tmpl("template-files", rr.data)).appendTo($('#fileupload .files'));
            }
        }
    });

});

function delimg(delbtn, imgname)
{
    $.post("/admin/template/delimg", { id: template_id, imgname: imgname }, function(data){
        var rr = $.parseJSON(data);
        if (rr.code == 0) {
            alert(rr.msg);
        } else {
            $(delbtn).closest('tr').remove();
        }
    });
}

</script>

<?php include('_footer.php');?>