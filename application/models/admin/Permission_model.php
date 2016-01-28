<?php
/**
 * Created by PhpStorm.
 * User: liaosy
 * Date: 16/1/22
 * Time: 下午3:54
 */

include_once('Admin_base_model.php');
class Permission_model extends Admin_base_model {

    function __construct()
    {
        parent::__construct();
    }

    function get_one($id)
    {
        try {
            $this->db->where('ID', $id);
            $query = $this->db->get('permissions');
            return $query->row();
        } catch(Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    function get_by_ids(Array $ids)
    {
        $result = array();
        if(empty($ids) || ! is_array($ids)) {
            return $result;
        }
        try {
            $this->db->where_in('ID', $ids);
            $query = $this->db->get('permissions');
            $result = $query->result();
        } catch(Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
        return $result;
    }

}