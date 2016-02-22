<?php
/**
 * Created by PhpStorm.
 * User: liaosy
 * Date: 16/2/22
 * Time: 上午11:10
 */

/**
 * @param $options
 */
function pagination($options)
{
    $CI = &get_instance();
    $CI->load->library('pagination');

    $config = array();
    $config['base_url'] = $options['base_url'] ? $options['base_url'] : '';
    $config['total_rows'] = $options['total_rows'] ? $options['total_rows'] : 0;
    $config['per_page'] = $options['per_page'] ? $options['per_page'] : 20;
    //设置带参数的翻页
    $config['suffix'] = '';
    if(isset($options['search'])) {
        $config['suffix'] = '/' . $CI->uri->assoc_to_uri($options['search']);
    }
    //设置first_url 地址
    $config['first_url'] = $config['base_url'] . '/1' . $config['suffix'] ;

    // 当前连接前后显示页码个数。意思就是说你当前页是第5页，那么你可以看到3、4、5、6、7页。
    $config['num_links'] = 4;
    $config['uri_segment'] = 4;
    // $this->uri->segment(4) 获取分页数
    $config['use_page_numbers'] = TRUE;
    $config['reuse_query_string'] = TRUE;

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

    $config['first_link'] = '首页'; // 第一页显示
    $config['last_link'] = '末页'; // 最后一页显示
    $config['next_link'] = '下一页'; // 下一页显示
    $config['prev_link'] = '上一页'; // 上一页显示

    $CI->pagination->initialize($config);
}