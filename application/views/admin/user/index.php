<# extends admin/_layouts/default #>

<!--视图模版-->

<!-- Page Header -->
<# block page_header #>
    <div class="content bg-gray-lighter">
        <form class="form-horizontal push-10-t" action="base_forms_elements_modern.php" method="post" onsubmit="return false;">
            <div class="form-group">
                <div class="col-xs-6 col-sm-4 col-md-3">
                    <div class="form-material form-material-primary">
                        <input class="form-control" type="text" id="login_name" name="login_name" placeholder="请输入用户账号">
                        <label for="login_name">用户账号</label>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-3">
                    <div class="form-material form-material-primary">
                        <input class="form-control" type="text" id="mobile" name="mobile" placeholder="请输入用户手机号码">
                        <label for="mobile">手机号码</label>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-3">
                    <div class="form-material form-material-primary">
                        <input class="form-control" type="text" id="start_time" name="start_time" placeholder="请选择开始时间">
                        <label for="start_time">开始时间</label>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-3">
                    <div class="form-material form-material-primary">
                        <input class="form-control" type="text" id="end_time" name="end_time" placeholder="请选择结束时间">
                        <label for="end_time">结束时间</label>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-3">
                    <div class="form-material form-material-primary">
                        <input class="form-control" type="text" id="email" name="email" placeholder="请输入用户邮箱">
                        <label for="email">邮箱</label>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-3">
                    <div class="form-material form-material-primary">
                        <select class="form-control" id="status" name="status">
                            <option>--所有--</option>
                            <option value="0">禁用</option>
                            <option value="1">正常</option>
                        </select>
                        <label for="status">账号状态</label>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-3">
                    <button class="btn btn-sm btn-primary" type="submit">查询</button>
                </div>
            </div>
        </form>
    </div>
<# /block #>
<!-- END Page Header -->

<!-- Page Content -->
<# block page_content #>
    <div class="content">
        <div class="block">
            <div class="block-header">
                <ul class="block-options">
                    <li>
                        <button type="button" data-toggle="tooltip" title="新建"><i class="si si-plus"></i></button>
                    </li>
                    <li>
                        <button type="button" data-toggle="block-option" data-action="content_toggle"></button>
                    </li>
                </ul>
                <h3 class="block-title">用户列表 <small>(<?php echo count($data); ?>)</small></h3>
            </div>
            <div class="block-content">

                <div class="table-responsive">
                    <table class="table table-bordered table-condensed table-hover">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>账号</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>状态</th>
                            <th>最后登录时间</th>
                            <th>最后登录时IP</th>
                            <th>登录总次数</th>
                            <th>创建时间</th>
                            <th class="text-center">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($data as $k=>$v) : ?>
                            <tr>
                                <td class="text-center"><?php echo $k + 1; ?></td>
                                <td class="font-w600"><?php echo $v->LoginName; ?></td>
                                <td><?php echo $v->Mobile; ?></td>
                                <td><?php echo $v->Email; ?></td>
                                <td><?php echo $v->Status; ?></td>
                                <td><?php echo String_utils::getLongTime($v->LastLoginTime); ?></td>
                                <td><?php echo $v->LastLoginIP; ?></td>
                                <td><?php echo $v->LoginCount; ?></td>
                                <td><?php echo String_utils::getLongTime($v->Created); ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="Edit Client"><i class="fa fa-pencil"></i></button>
                                        <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="Remove Client"><i class="fa fa-times"></i></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                    <?php echo $this->pagination->create_links(); ?>
                </div>
            </div>
        </div>
    </div>
<# /block #>
<!-- END Page Content —>

<!-- Page CSS Plugins -->
<# block page_css_plugins #>
<# /block #>
<!-- Page CSS Code -->
<# block page_css_code #>
<# /block #>

<!-- Page JS Plugins -->
<# block page_js_plugins #><# /block #>
<!-- Page JS Code -->
<# block page_js_code #>
    <script>
        $(function(){
        });
    </script>
<# /block #>



