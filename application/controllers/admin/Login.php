<?php
/**
 * Created by PhpStorm.
 * User: liaosy
 * Date: 16/1/15
 * Time: 上午10:23
 */


class Login extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        /**
         * 加载 admin/config 文件，该config里面做了$this->load->vars 输出config对象
         * update by liaosy 2016-01-19
         */
        $this->load->view('admin/config');
    }

    /**
     * 后台登录页面
     */
    public function index()
    {
        $this->view('admin/login');
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        log_message('debug', '退出登录！');
        session_unset();
        session_destroy();
        log_message('debug', 'logout session_destroy: ' . json_encode($_SESSION));
        redirect('admin/login');
    }

    /**
     * ajax 登录接口
     */
    public function ajax_login()
    {
        if ($this->isPost())
        {
            $username=$this->requestData["username"];
            $password=$this->requestData["password"];
            if(isset($username) && ! empty($username) && isset($password) && !empty($password)){
                $this->load->model('admin/user_model', 'user_model');
                try {
                    $result = $this->user_model->login($username,$password);
                    if($result) {
                        log_message('debug','用户[' .$username. ']登录成功！');
                        //login success
                        $this->json_success();
                    } else {
                        log_message('error','用户[' .$username. ']登录失败！');
                        $this->json_error('登录失败！服务器异常');
                    }
                } catch (Exception $e) {
                    log_message('error','用户[' .$username. ']登录失败！' . $e->getMessage());
                    $this->json_error($e->getMessage(), $e->getCode());
                }
            } else {
                log_message('error','用户名或密码不能为空');
                $this->json_error('用户名或密码不能为空!');
            }
        } else {
            log_message('error','非法登录get请求');
            $this->json_error('非法请求!');
        }
    }

}