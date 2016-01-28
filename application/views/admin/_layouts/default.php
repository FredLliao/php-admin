<?php $this->load->view('admin/_template/template_head_start') ; ?>
    <!-- Page CSS Plugins -->
    <# block page_css_plugins #><# /block #>
    <!-- Page CSS Code -->
    <# block page_css_code #><# /block #>

<?php $this->load->view('admin/_template/template_head_end') ; ?>
<?php $this->load->view('admin/_template/base_head') ; ?>



    <!-- Page Header -->
    <# block page_header #><# /block #>
    <!-- END Page Header -->

    <!-- Page Content -->
    <# block page_content #><# /block #>
    <!-- END Page Content -->

<?php $this->load->view('admin/_template/base_footer') ; ?>
<?php $this->load->view('admin/_template/template_footer_start') ; ?>


    <!-- Page JS Plugins -->
    <# block page_js_plugins #><# /block #>
    <!-- Page JS Code -->
    <# block page_js_code #><# /block #>

<?php $this->load->view('admin/_template/template_footer_end') ; ?>