<?php
/**
 * Created by PhpStorm.
 * User: liaosy
 * Date: 16/1/22
 * Time: 下午3:54
 */

class Admin_base_model extends MY_Model {

    protected $CI;

    function __construct()
    {
        parent::__construct();
        $this->CI = & get_instance();
        $this->load->database();
    }

    /**
     * 封装model加载方法
     * 该方法与Admin_base Controller中保持一致
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
                $this->CI->load->model($model);
            } else {
                $this->CI->load->model($model, $alias);
            }
        } else {
            if(! isset($alias)) {
                $alias = substr($model, strlen($prefix) + 1);
            }
            $this->CI->load->model($model,$alias);
        }
    }

}