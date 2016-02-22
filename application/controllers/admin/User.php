<?php
/**
 * Created by PhpStorm.
 * User: liaosy
 * Date: 16/1/15
 * Time: 上午10:23
 */


include_once('Admin_base.php');

class User extends Admin_base {

    public function __construct()
    {
        parent::__construct(__CLASS__);
    }

    public function index()
    {
        $visible=-1;
        if(isset($_GET["visible"])){
            $visible=$_GET["visible"];
            if($visible!=-1){
                $select_where["Visible"]=intval($visible);
            }
        }
        $search["visible"]=$visible;

        $title="test";
        if(isset($_GET["title"])&&!empty($_GET["title"])){
            $title=trim($_GET["title"]);
            //模糊匹配
            $select_where['Title']= $title;
        }
        $search["title"]=$title;

        $this->load->database();
        $this->db->from('users');
        $this->db->order_by('created desc');
        $query = $this->db->get();
        $rows = $query->result();



        $options['base_url'] = admin_url('user/index');
        $options['total_rows'] = 50;
        $options['per_page'] = 5;
        $options['search'] = $search;
        pagination($options);

        $this->view('index', $rows);

//        log_message('debug','get:'.json_encode($_GET));
//        log_message('debug','segment:'.$this->uri->segment(4,1));
//        log_message('debug','uri_to_assoc:'.json_encode($this->uri->uri_to_assoc(5)));
    }

}