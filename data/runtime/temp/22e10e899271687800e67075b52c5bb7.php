<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:45:"app/view/admin/admin\admin_category\edit.html";i:1536061786;s:48:"F:\myapp\wxgzh\app\view\admin\public\header.html";i:1535607506;}*/ ?>
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
</head>
<body>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li><a href="<?php echo url('AdminCategory/index'); ?>">分类管理</a></li>
        <li class="active"><a href="<?php echo url('AdminCategory/edit'); ?>">编辑分类</a></li>
    </ul>
    <div class="row margin-top-20">

        <div class="col-md-3">
            <form class="js-ajax-form" action="<?php echo url('AdminCategory/editPost'); ?>" method="post">
                <div class="tab-content">
                    <div class="tab-pane active" id="A">


                            <div class="form-group">

                                <label for="input-parent"><span class="form-required">*</span>上级</label>
                                <div>
                                    <select class="form-control" name="pid[]" id="input-parent" onchange="category()">

                                        <?php if(is_array($re) || $re instanceof \think\Collection || $re instanceof \think\Paginator): $i = 0; $__LIST__ = $re;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$re): $mod = ($i % 2 );++$i;?>
                                            <option value="<?php echo $re['id']; ?>"><?php echo $re['title']; ?></option>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>>
                                    </select>

                                </div>
                            </div>

                            <div class="form-group" id="erji" style="display: none">
                                <label for="house"><span class="form-required">*</span>第二级</label>
                                <div>

                                    <select class="form-control" name="pid[]" id="house"  >

                                    </select>
                                </div>
                            </div>

                        <script>
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
                                            $('#erji').css('display','block');
                                        }
                                    });


                                }else{
                                    $('#erji').css('display','none');
                                }
                            }
                        </script>
                        <div class="form-group">
                            <label for="input-name"><span class="form-required">*</span>分类名称</label>
                            <div>
                                <input type="text" class="form-control" id="input-name" name="title" value="<?php echo $vo['title']; ?>">
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="input-description">描述</label>
                            <div>
                                <textarea class="form-control" name="desc" id="input-description"><?php echo $vo['desc']; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input-keyw">关键字</label>
                            <div>
                                <input class="form-control" name="keyw" id="input-keyw" value="<?php echo $vo['keyw']; ?>"></input>
                            </div>
                        </div>
                        <div class="form-group">
                            <label >模板</label>
                            <div>
                                <input  name="tpl"  type="checkbox">1</input>
                                <input name="tpl"  type="checkbox">2</input>
                                <input  name="tpl"  type="checkbox">3</input>
                                <input  name="tpl"  type="checkbox">4</input>

                            </div>
                        </div>
                        <div class="form-group">

                            <label for="thumb">缩略图</label>


                                <input type="hidden" name="thumb" id="thumb" value="<?php echo $vo['thumb']; ?>">
                                <a href="javascript:uploadOneImage('图片上传','#thumbnail');">
                                    <?php if(empty($vo['thumb'])): ?>
                                        <img src="/app/view/admin/public/assets/images/default-thumbnail.png"
                                             id="thumbnail-preview"
                                             width="135" style="cursor: pointer"/>
                                        <?php else: ?>
                                        <img src="<?php echo cmf_get_image_preview_url($vo['thumb']); ?>"
                                             id="thumbnail-preview"
                                             width="135" style="cursor: pointer"/>
                                    <?php endif; ?>
                                </a>

                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary js-ajax-submit"><?php echo lang('ADD'); ?></button>
                            <a class="btn btn-default" href="<?php echo url('AdminCategory/index'); ?>"><?php echo lang('BACK'); ?></a>
                        </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="/static/js/admin.js">

</script>
</body>
</html>