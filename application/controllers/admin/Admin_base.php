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
        $this->load->view('admin/config');
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
        $admin = 'admin';
        if(! String_utils::startWith($layout,$admin)) {
            if(empty($this->sub_class)) {
                $layout = $admin. DIRECTORY_SEPARATOR . $layout;
            } else {
                $layout = $admin. DIRECTORY_SEPARATOR  . $this->sub_class . DIRECTORY_SEPARATOR . $layout;
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
        $admin = 'admin';
        if(! String_utils::startWith($model, $admin)) {
            $model = $admin. DIRECTORY_SEPARATOR . $model;
            if(! isset($alias)) {
                $this->load->model($model);
            } else {
                $this->load->model($model, $alias);
            }
        } else {
            if(! isset($alias)) {
                $alias = substr($model, strlen($admin) + 1);
            }
            $this->load->model($model,$alias);
        }
    }

    /**
     * 检查是否登录
     */
    private function _check_is_login()
    {
        if(! $this->get_login_account_id() || ! $this->get_login_account_role() || ! $this->get_login_account_permission()) {
            //来自http get/post 请求处理
            if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'XMLHttpRequest' == $_SERVER['HTTP_X_REQUESTED_WITH']) {
                log_message('debug', '未登录的http[' . $_SERVER['REQUEST_METHOD'] . ']请求！拒绝服务');
                $this->json_error('需要登录才能操作');
            } else {
                log_message('debug', '未登录的请求！拒绝服务，并跳转到登录页面！');
                redirect('admin/login');
            }
            exit;
        }
    }

    /**
     * 获取登录用户ID
     *
     * @return mixed
     */
    protected function get_login_account_id()
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
    protected function get_login_account_name()
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
    protected function get_login_account_role()
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
    protected function get_login_account_permission()
    {
        if(isset($_SESSION[Const_string::SessionPermsKey])) {
            return $_SESSION[Const_string::SessionPermsKey];
        } else {
            return null;
        }
    }

}