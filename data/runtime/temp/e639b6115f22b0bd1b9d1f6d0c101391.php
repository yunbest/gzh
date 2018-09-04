<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:43:"app/view/admin/admin\admin_article\add.html";i:1536042537;s:48:"F:\myapp\wxgzh\app\view\admin\public\header.html";i:1535607506;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- HTML5 shim for IE8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->


    <link href="/app/view/admin/public/assets/themes/<?php echo cmf_get_admin_style(); ?>/bootstrap.min.css" rel="stylesheet">
    <link href="/app/view/admin/public/assets/simpleboot3/css/simplebootadmin.css" rel="stylesheet">
    <link href="/static/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        form .input-order {
            margin-bottom: 0px;
            padding: 0 2px;
            width: 42px;
            font-size: 12px;
        }

        form .input-order:focus {
            outline: none;
        }

        .table-actions {
            margin-top: 5px;
            margin-bottom: 5px;
            padding: 0px;
        }

        .table-list {
            margin-bottom: 0px;
        }

        .form-required {
            color: red;
        }
    </style>
    <script type="text/javascript">
        //全局变量
        var GV = {
            ROOT: "/",
            WEB_ROOT: "/",
            JS_ROOT: "static/js/",
            APP: '<?php echo \think\Request::instance()->module(); ?>'/*当前应用名*/
        };
    </script>
    <script src="/app/view/admin/public/assets/js/jquery-1.10.2.min.js"></script>
    <script src="/static/js/wind.js"></script>
    <script src="/app/view/admin/public/assets/js/bootstrap.min.js"></script>
    <script>
        Wind.css('artDialog');
        Wind.css('layer');
        $(function () {
            $("[data-toggle='tooltip']").tooltip({
                container:'body',
                html:true,
            });
            $("li.dropdown").hover(function () {
                $(this).addClass("open");
            }, function () {
                $(this).removeClass("open");
            });
        });
    </script>
    <?php if(APP_DEBUG): ?>
        <style>
            #think_page_trace_open {
                z-index: 9999;
            }
        </style>
    <?php endif; ?>
<style type="text/css">
    .pic-list li {
        margin-bottom: 5px;
    }
</style>
<script type="text/html" id="photos-item-tpl">
    <li id="saved-image{id}">
        <input id="photo-{id}" type="hidden" name="photo_urls[]" value="{filepath}">
        <input class="form-control" id="photo-{id}-name" type="text" name="photo_names[]" value="{name}"
               style="width: 200px;" title="图片名称">
        <img id="photo-{id}-preview" src="{url}" style="height:36px;width: 36px;"
             onclick="imagePreviewDialog(this.src);">
        <a href="javascript:uploadOneImage('图片上传','#photo-{id}');">替换</a>
        <a href="javascript:(function(){$('#saved-image{id}').remove();})();">移除</a>
    </li>
</script>
<script type="text/html" id="files-item-tpl">
    <li id="saved-file{id}">
        <input id="file-{id}" type="hidden" name="file_urls[]" value="{filepath}">
        <input class="form-control" id="file-{id}-name" type="text" name="file_names[]" value="{name}"
               style="width: 200px;" title="文件名称">
        <a id="file-{id}-preview" href="{preview_url}" target="_blank">下载</a>
        <a href="javascript:uploadOne('文件上传','#file-{id}','file');">替换</a>
        <a href="javascript:(function(){$('#saved-file{id}').remove();})();">移除</a>
    </li>
</script>
</head>
<body>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li><a href="<?php echo url('AdminArticle/index'); ?>">文章管理</a></li>
        <li class="active"><a href="<?php echo url('AdminArticle/add'); ?>">添加文章</a></li>
    </ul>
    <form action="<?php echo url('AdminArticle/addPost'); ?>" method="post" class="form-horizontal js-ajax-form margin-top-20">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th width="100">分类<span class="form-required">*</span></th>


                        <td>
                            <select class="form-control" name="article[categories]" id="input-parent" onchange="category()">

                                <?php if(is_array($re) || $re instanceof \think\Collection || $re instanceof \think\Paginator): $i = 0; $__LIST__ = $re;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$re): $mod = ($i % 2 );++$i;?>
                                    <option value="<?php echo $re['id']; ?>"><?php echo $re['title']; ?></option>
                                <?php endforeach; endif; else: echo "" ;endif; ?>>
                            </select>
                        </td>



                    </tr>

                    <tr style="display:none" id="erji">
                        <th width="100" >次级分类<span class="form-required">*</span></th>

                        <td>

                            <select class="form-control" name="article[categories_erji]" id="house"  >

                            </select>

                        </td>

                    </tr>
                    <tr>
                        <th>标题<span class="form-required">*</span></th>
                        <td>
                            <input class="form-control" type="text" name="article[title]"
                                   id="title" required value="" placeholder="请输入标题"/>
                        </td>
                    </tr>
                    <tr>
                        <th>关键词</th>
                        <td>
                            <input class="form-control" type="text" name="article[keyw]" id="keywords" value=""
                                   placeholder="请输入关键字">
                            <p class="help-block">多关键词之间用英文逗号隔开</p>
                        </td>
                    </tr>

                    <tr>
                        <th>描述</th>
                        <td>
                            <textarea class="form-control" name="article[desc]" style="height: 50px;"
                                      placeholder="请填写描述"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>内容</th>
                        <td>
                            <script type="text/plain" id="content" name="article[cont]"></script>
                        </td>

                    </tr>
                    <tr>
                        <th>多图上传</th>
                        <td>
                            <ul id="photos" class="pic-list list-unstyled form-inline"></ul>
                            <a href="javascript:uploadMultiImage('图片上传','#photos','photos-item-tpl');"
                               class="btn btn-default btn-sm">选择图片</a>
                        </td>
                    </tr>

                    <tr>
                        <th>缩略图</th>
                        <td>

                            <input type="hidden" name="article[thumb]" id="thumbnail" value="">
                            <a href="javascript:uploadOneImage('图片上传','#thumbnail');">
                                <img src="/app/view/admin/public/assets/images/default-thumbnail.png"
                                     id="thumbnail-preview"
                                     width="135" style="cursor: pointer"/>
                            </a>
                        </td>
                    </tr>
                </table>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary js-ajax-submit"><?php echo lang('ADD'); ?></button>
                        <a class="btn btn-default" href="<?php echo url('AdminArticle/index'); ?>"><?php echo lang('BACK'); ?></a>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script type="text/javascript" src="/static/js/admin.js"></script>
<script type="text/javascript">
    //编辑器路径定义
    var editorURL = GV.WEB_ROOT;

</script>
<script type="text/javascript" src="/static/js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/static/js/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript">
    $(function () {

        editorcontent = new baidu.editor.ui.Editor();
        editorcontent.render('content');
        try {
            editorcontent.sync();
        } catch (err) {
        }

        $('.btn-cancel-thumbnail').click(function () {
            $('#thumbnail-preview').attr('src', '/app/view/admin/public/assets/images/default-thumbnail.png');
            $('#thumbnail').val('');
        });

    });

    function category() {
        var id = $('#input-parent').val();
        var url = "<?php echo url('AdminCategory/selectAjax'); ?>"
        if(id > 0){
            $.ajax({
                url:url,
                data:{'id':id},
                dataType:'json',
                type:'post',
                success:function (re) {
                    $('#house').html($.map(re,function (value,key) {
                        return "<option value='"+value.id+"'>"+value.title+"</option>";
                    }));
                    $('#erji').show();
                }
            });


        }else{
            $('#erji').css('display','none');
        }
    }

    function doSelectCategory() {
        var selectedCategoriesId = $('#js-categories-id-input').val();
        openIframeLayer("<?php echo url('AdminCategory/select'); ?>?ids=" + selectedCategoriesId, '请选择分类', {
            area: ['700px', '400px'],
            btn: ['确定', '取消'],
            yes: function (index, layero) {
                //do something

                var iframeWin          = window[layero.find('iframe')[0]['name']];
                var selectedCategories = iframeWin.confirm();
                if (selectedCategories.selectedCategoriesId.length == 0) {
                    layer.msg('请选择分类');
                    return;
                }
                $('#js-categories-id-input').val(selectedCategories.selectedCategoriesId.join(','));
                $('#js-categories-name-input').val(selectedCategories.selectedCategoriesName.join(' '));
                //console.log(layer.getFrameIndex(index));
                layer.close(index); //如果设定了yes回调，需进行手工关闭
            }
        });
    }
</script>
</body>
</html>
