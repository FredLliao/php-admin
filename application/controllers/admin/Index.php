<?php
/**
 * Created by PhpStorm.
 * User: liaosy
 * Date: 16/1/15
 * Time: 上午10:23
 */


include_once('Admin_base.php');

class Index extends Admin_base {

    public function __construct()
    {
        parent::__construct(__CLASS__);
    }

    public function index()
    {
        $data=array('name'=>'liaosy','age'=>29);
        $this->view('index',$data);
    }

}