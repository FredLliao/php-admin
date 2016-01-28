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
        if(! empty($_class)) {
            $this->sub_class = strtolower($_class);
        }
        parent::__construct();

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

}