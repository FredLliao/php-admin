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
        $this->load->database();
        $this->db->from('users');
        $this->db->order_by('created desc');
        $query = $this->db->get();
        $rows = $query->result();

        $this->load->library('pagination');

        $config = array();
        $config['base_url'] = admin_url('user/index');
        $config['total_rows'] = 50;
        $config['per_page'] = 3;

//        $config['page_query_string'] = TRUE;
        $config['full_tag_open'] = '<nav><ul class="pagination pagination-sm">';
        $config['full_tag_close'] = '</ul></nav>';

        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="javascript:void(0)">'; // 当前页开始样式
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['attributes'] = array('class' => 'myclass');
        $config['attributes']['rel'] = FALSE;

        $config['first_link'] = '首页'; // 第一页显示
        $config['last_link'] = '末页'; // 最后一页显示
        $config['next_link'] = '下一页'; // 下一页显示
        $config['prev_link'] = '上一页'; // 上一页显示

        $config['num_links'] = 4;// 当前连接前后显示页码个数。意思就是说你当前页是第5页，那么你可以看到3、4、5、6、7页。
        $config['uri_segment'] = 4;
        /*这个是你在用a)、b)链接样式的时候，用来判断页页数。
        比如localhost/news/page/3  这个uri_segment就要设定为3。localhost/news/title/page/3这个就要设定为4
        */
        $config['use_page_numbers'] = TRUE;
        $config['reuse_query_string'] = TRUE;

        $this->pagination->initialize($config);

        $this->view('index', $rows);
    }

}