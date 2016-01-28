<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: liaosy
 * Date: 16/1/13
 * Time: 下午8:18
 */

/**
 * 自定义扩展CI_Model基类
 *
 * Class MY_Model
 */
class MY_Model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        log_message('info','MY_Security_Controller  Initialized');
    }

}