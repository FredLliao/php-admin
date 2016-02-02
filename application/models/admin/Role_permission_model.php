<?php
/**
 * Created by PhpStorm.
 * User: liaosy
 * Date: 16/1/22
 * Time: 下午3:54
 */

include_once('Admin_base_model.php');
class Role_permission_model extends Admin_base_model {

    function __construct()
    {
        parent::__construct();
    }

    function get_perms_by_role_id($role_id)
    {
        $result = array();
        if(empty($role_id)) {
            return $result;
        }
        return $this->get_perms_by_role_ids(array($role_id));
    }

    function get_perms_by_role_ids(Array $role_ids)
    {
        $result = array();
        if(empty($role_ids) || ! is_array($role_ids)) {
            return $result;
        }
        try {
            $this->db->where_in('RoleID', $role_ids);
            $query = $this->db->get('role_permissions');
            $result = $query->result();
        } catch(Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
        return $result;
    }

}