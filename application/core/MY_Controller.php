<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: liaosy
 * Date: 16/1/13
 * Time: 下午8:18
 */

/**
 * 自定义扩展CI_Controller基类
 *
 * Class MY_Controller
 */
class MY_Controller extends CI_Controller {

    //请求数据
    protected $requestData = array();

    protected $success = true;
    protected $code;
    protected $message;
    protected $result;
    protected $total;

    function __construct()
    {
        parent::__construct();
        log_message('info','MY_Controller  Initialized');

        if ($this->input->post()) {
            $this->requestData = $this->input->post();
//            log_message('debug','post:' . json_encode($this->requestData));
        } else if ($this->input->get()) {
            $this->requestData = $this->input->get();
//            log_message('debug','get:' . json_encode($this->requestData));
        }
        $this->requestData = array_merge($this->requestData, (array)json_decode(file_get_contents('php://input'), true));
        log_message('debug','requestData:' . json_encode($this->requestData));
    }

    /**
     * 简单封装view方法
     * 子类调用方式：$this->view($layout[,$data,$return]);
     *
     * @param $layout
     * @param array $data
     * @param bool $return
     */
    protected function view($layout, $data = array(), $return = false)
    {
        $this->load->view($layout, $data, $return);
    }

    /**
     * 判断是否是post提交
     *
     * @return bool
     */
    protected function isPost()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']) === 'POST';
    }

    /**
     * ajax request success 输出json格式数据
     *
     * @param null $result
     * @param null $message
     */
    protected function json_success($result = null, $message = null)
    {
        $this->success = true;
        $this->message = $message;
        $this->result = $result;
        $this->json_response();
    }

    protected function json_error($message = '服务器异常', $code = 500)
    {
        $this->success = false;
        $this->message = $message;
        $this->code = $code;
        $this->json_response();
    }

    /**
     * ajax request 输出json格式数据
     */
    protected function json_response()
    {
        //以json格式输出
        header("Content-type: application/json;charset=utf-8");
        $json = array();
        $json['success'] = $this->success;
        if($this->success) {
            if(isset($this->result)) {
                $json['result'] = $this->result;
            }
            if(isset($this->total)) {
                $json['total'] = $this->total;
            }
            if(isset($this->message)) {
                $json['message'] = $this->message;
            }
        } else {
//            header('HTTP/1.1 500 Internal Server Error.');
            $json['message'] = isset($this->message) ? $this->message : '服务器出错了！';
            $json['code'] = isset($this->code) ? $this->code : 500;
        }
        //避免中文输出乱码
        if (version_compare(PHP_VERSION, '5.4', '>=')) {
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
        } else {
            $json = json_encode($json);
            echo urldecode($json);
        }
    }

}