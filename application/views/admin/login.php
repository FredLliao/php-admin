<?php $this->load->view('admin/_template/template_head_start') ; ?>
<?php $this->load->view('admin/_template/template_head_end') ; ?>

    <!-- Login Content -->
    <div class="content overflow-hidden">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                <!-- Login Block -->
                <div class="block block-themed animated fadeIn">
                    <div class="block-header bg-primary">
                        <h3 class="block-title">登录</h3>
                    </div>
                    <div class="block-content block-content-full block-content-narrow">
                        <!-- Login Title -->
                        <div class="text-center">
                            <i class="fa fa-2x fa-circle-o-notch text-primary"></i>
                            <p class="text-muted push-15-t">A perfect match for your project</p>
                        </div>
                        <!-- END Login Title -->

                        <!-- Login Form -->
                        <!-- jQuery Validation (.js-validation-login class is initialized in js/pages/base_pages_login.js) -->
                        <!-- For more examples you can check out https://github.com/jzaefferer/jquery-validation -->
                        <form id="form-login" class="js-validation-login form-horizontal push-30-t push-50"  method="post">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <div class="form-material form-material-primary floating">
                                        <input class="form-control" type="text" id="login-username" name="username">
                                        <label for="login-username">用户名</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <div class="form-material form-material-primary floating">
                                        <input class="form-control" type="password" id="login-password" name="password">
                                        <label for="login-password">密码</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label class="css-input switch switch-sm switch-primary">
                                        <input type="checkbox" id="login-remember-me" name="login-remember-me"><span></span> 记住我?
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <button class="btn btn-block btn-primary" type="button" id="btn-login"><i class="si si-login pull-right"></i> 登录</button>
                                </div>
                            </div>
                        </form>
                        <!-- END Login Form -->
                    </div>
                </div>
                <!-- END Login Block -->
            </div>
        </div>
    </div>
    <!-- END Login Content -->

    <!-- Login Footer -->
    <div class="push-10-t text-center animated fadeInUp">
        <small class="text-muted font-w600"><span class="js-year-copy"></span> &copy; <?php echo $one->name . ' ' . $one->version; ?></small>
    </div>
    <!-- END Login Footer -->

<?php $this->load->view('admin/_template/template_footer_start') ; ?>


    <!-- Page JS Plugins -->
    <script src="<?php echo $one->assets_folder; ?>/js/plugins/md5.min.js"></script>
<!--    <script src="--><?php //echo $one->assets_folder; ?><!--/js/plugins/jquery-validation/jquery.validate.min.js"></script>-->

    <!-- Page JS Code -->
<!--    <script src="--><?php //echo $one->assets_folder; ?><!--/js/pages/base_pages_login.js"></script>-->
    <script>
        $(function(){
            $("#btn-login").click(function(){
                var username=$("#login-username").val();
                var password=$("#login-password").val();

                if(commonUtil.isEmptyString(username)){
                    showError("请输入用户名",'top');
                    $("#login-username").focus();
                    return false;
                }
                if(commonUtil.isEmptyString(password)){
                    showError("请输入密码");
                    $("#login-password").focus();
                    return false;
                }
                password = md5(password);

                showLoading("正在登录...",false);
                var url = myDomain.baseUrl + 'admin/login/ajax_login';
                $.post(url,{username:username, password:password},function(res){
                    hideLoading();
                    if(res.success){
                        location.href = myDomain.baseUrl + 'admin';
                    }else{
                        alert_error(res.message);
                    }
                },"json").error(function (result) {
                    console.log('error:'+result);
                    hideLoading();
                    showError("登录失败：服务器异常");

                });

            });

//            $('#form-login').ajaxForm({
//                url:myDomain.baseUrl + 'admin/login/ajax',
//                dataType: "json",
//                beforeSubmit:function(){
//                    return $('#form-login').valid();
//                },
//                success:function(res){
//                    if(res.success){
//                        console.log('success');
//                    }else{
//                        alert_error(res.message);
//                    }
//                },
//                error: function(xhr) {
//                    //正上方提示
//                    var msg = '错误代码:'+xhr.status+'，'+xhr.statusText;
//                },
//                timeout:   5000
//            });
        });

    </script>

<?php $this->load->view('admin/_template/template_footer_end') ; ?>