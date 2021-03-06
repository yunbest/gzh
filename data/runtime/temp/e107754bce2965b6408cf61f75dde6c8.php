<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:44:"app/view/admin/admin\admin_category\add.html";i:1536058169;s:48:"F:\myapp\wxgzh\app\view\admin\public\header.html";i:1535607506;}*/ ?>
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
        <li class="active"><a href="<?php echo url('AdminCategory/add'); ?>">添加分类</a></li>
    </ul>
    <div class="row margin-top-20">

        <div class="col-md-3">
            <form class="js-ajax-form" action="<?php echo url('AdminCategory/addPost'); ?>" method="post">
                <div class="tab-content">
                    <div class="tab-pane active" id="A">

                        <?php if($vo['title']): ?>
                            <div class="form-group">

                                <label for="input-parent"><span class="form-required">*</span>上级</label>
                                <div>
                                    <select class="form-control" name="pid" id="input-parent" >
                                        <option value="<?php echo $vo['id']; ?>"><?php echo $vo['title']; ?></option>

                                    </select>

                                </div>
                                <input type="hidden" name="pids" value="<?php echo $vo['pids']; ?>">
                            </div>
                            <?php else: ?>
                        <div class="form-group" id="select">

                            <label for="input-parent"><span class="form-required">*</span>第一级</label>
                            <div >
                                <select class="form-control" name="pid[]" id="category_1" onchange="change('category_1')">
                                    <option value="0">第一级分类</option>
                                    <?php if(is_array($re) || $re instanceof \think\Collection || $re instanceof \think\Paginator): $i = 0; $__LIST__ = $re;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$re): $mod = ($i % 2 );++$i;?>
                                        <option value="<?php echo $re['id']; ?>"><?php echo $re['title']; ?></option>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>>
                                </select>

                            </div>
                        </div>


                        <?php endif; ?>
                        <script>
                            function change(val) {
                                var str = val; //select的id
                                var num; //当前级数
                                var id; // 分类id
                                num = str.substr(9,10);
                                //alert(num);
                                var nownum = parseInt(num)+1; // 将字符串转换为数字
                                // alert(nownum)
                                id = $("#"+str+"").val();

                                var r = /^[1-9]+[0-9]*]*$/;　//正整数
                                if (!r.test(id)) {
                                    //清空过时的选项
                                    $("select").each(function(index){
                                        if(index+1 > num) {
                                            $(this).remove();
                                        }
                                    })

                                    return false;
                                }
                                var url ="<?php echo url('AdminCategory/selectAjax'); ?>"
                                $.ajax({
                                    url:url,
                                    data:{'id':id},
                                    dataType:'json',
                                    type:'post',

                                    success: function(result){
                                        if ( result != 0) {
                                            var html = "<label for=input-parent><span class=form-required>*</span>次级</label><select name=pid[] class=form-control   id=category_"+nownum+"  onChange=change('category_"+nownum+"'); >";
                                            html += "<option>请选择分类 </option>";
                                            var datas = eval(result);
                                            $.each(datas, function(i,val){
                                                html += "<option value='"+val.id+"' >"+val.title+"</option>";
                                            });
                                            html += "</select>";

                                            //清空过时的选项
                                            $("select").each(function(index){
                                                if(index+1 > num) {
                                                    $(this).remove();
                                                }
                                            })
                                            // alert(html)
                                            $("#select").append(html);
                                        } else {
                                            //清空过时的选项
                                            $("select").each(function(index){
                                                if(index+1 > num) {
                                                    $(this).remove();
                                                }
                                            })　　　　　　　}

                                    },
                                    error: false
                                });


                            }
                        </script>
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
                                <input type="text" class="form-control" id="input-name" name="title">
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="input-description">描述</label>
                            <div>
                                <textarea class="form-control" name="desc" id="input-description"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input-keyw">关键字</label>
                            <div>
                                <input class="form-control" name="keyw" id="input-keyw"></input>
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
                            <label for="input-description">缩略图</label>
                            <div>
                                <input type="hidden" name="thumb" class="form-control"
                                       id="js-thumbnail-input">
                                <div>
                                    <a href="javascript:uploadOneImage('图片上传','#js-thumbnail-input');">
                                        <img src="/app/view/admin/public/assets/images/default-thumbnail.png"
                                             id="js-thumbnail-input-preview"
                                             width="135" style="cursor: pointer"/>
                                    </a>
                                </div>
                            </div>
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