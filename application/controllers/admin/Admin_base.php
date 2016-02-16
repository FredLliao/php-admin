<?php
/**
 * Created by PhpStorm.
 * User: liaosy
 * Date: 16/1/15
 * Time: 上午10:23
 */

/**
 * 后台需要登录验证才能访问的controller
 *
 * Class Admin_base
 */

class Admin_base extends MY_Controller {

    //子类名称
    protected $sub_class;

    public function __construct($_class = null)
    {
        parent::__construct();

        $this->load->library('session');
        //检查是否登录
        $this->_check_is_login();

        if(! empty($_class)) {
            $this->sub_class = strtolower($_class);
        }

        /**
         * 用户登录信息等可用 $this->load->vars 输出
         */
        $this->load->vars(array('user'=>array('name'=>'liaosy','age'=>29)));

        /**
         * 加载 admin/config 文件，该config里面做了$this->load->vars 输出config对象
         * update by liaosy 2016-01-19
         */
        $this->load->view(Const_string::Admin . '/config');
    }

    /**
     * 封装view模版解析方法
     * 子类调用方式：$this->view($layout[,$data,$return]);
     *
     * @param $layout
     * @param array $data
     * @param bool $return
     */
    protected function view($layout, $data = array(), $return = false)
    {
        $prefix = Const_string::Admin;
        if(! String_utils::startWith($layout,$prefix)) {
            if(empty($this->sub_class)) {
                $layout = $prefix. DIRECTORY_SEPARATOR . $layout;
            } else {
                $layout = $prefix. DIRECTORY_SEPARATOR  . $this->sub_class . DIRECTORY_SEPARATOR . $layout;
            }
        }
        $data = array('data' => $data);
        $this->load->library('hulk_template');
        $this->hulk_template->parse($layout, $data, $return);
    }

    /**
     * 封装model加载方法
     * 该方法与Admin_base_model中保持一致
     *
     * @param string $model 示例:[admin/]Role_model
     * @param string $alias
     */
    protected function model($model, $alias = null)
    {
        $prefix = Const_string::Admin;
        if(! String_utils::startWith($model, $prefix)) {
            $model = $prefix. DIRECTORY_SEPARATOR . $model;
            if(! isset($alias)) {
                $this->load->model($model);
            } else {
                $this->load->model($model, $alias);
            }
        } else {
            if(! isset($alias)) {
                $alias = substr($model, strlen($prefix) + 1);
            }
            $this->load->model($model,$alias);
        }
    }

    /**
     * 检查是否登录
     */
    private function _check_is_login()
    {
        log_message('info', '检查是否登录！');
        if(! $this->get_login_user_id() || ! $this->get_login_user_role() || ! $this->get_login_user_permission()) {
            //来自http get/post 请求处理
            if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'XMLHttpRequest' == $_SERVER['HTTP_X_REQUESTED_WITH']) {
                log_message('debug', '未登录的http[' . $_SERVER['REQUEST_METHOD'] . ']请求！拒绝服务');
                $this->json_error('需要登录才能操作');
            } else {
                log_message('debug', '未登录的请求！拒绝服务，并跳转到登录页面！');
                admin_redirect('login');
            }
            exit;
        }
    }

    /**
     * 获取登录用户ID
     *
     * @return mixed
     */
    protected function get_login_user_id()
    {
        if(isset($_SESSION[Const_string::SessionUserIDKey])) {
            return $_SESSION[Const_string::SessionUserIDKey];
        } else {
            return null;
        }
    }

    /**
     * 获取登录用户名
     *
     * @return mixed
     */
    protected function get_login_user_name()
    {
        if(isset($_SESSION[Const_string::SessionUserNameKey])) {
            return $_SESSION[Const_string::SessionUserNameKey];
        } else {
            return null;
        }
    }

    /**
     * 获取登录用户角色数组
     *
     * @return mixed
     */
    protected function get_login_user_role()
    {
        if(isset($_SESSION[Const_string::SessionRolesKey])) {
            return $_SESSION[Const_string::SessionRolesKey];
        } else {
            return null;
        }
    }

    /**
     * 获取登录用户权限数组
     *
     * @return mixed
     */
    protected function get_login_user_permission()
    {
        if(isset($_SESSION[Const_string::SessionPermsKey])) {
            return $_SESSION[Const_string::SessionPermsKey];
        } else {
            return null;
        }
    }

}