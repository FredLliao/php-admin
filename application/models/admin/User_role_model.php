<?php
/**
 * Created by PhpStorm.
 * User: liaosy
 * Date: 16/1/22
 * Time: 下午3:54
 */

include_once('Admin_base_model.php');
class User_role_model extends Admin_base_model {

    function __construct()
    {
        parent::__construct();
    }

    function get_one($id)
    {
        try {
            $this->db->where('ID', $id);
            $query = $this->db->get('roles');
            return $query->row();
        } catch(Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    function get_roles_by_user_id($user_id)
    {
        $result = array();
        if(empty($user_id)) {
            return $result;
        }
        return $this->get_roles_by_user_ids(array($user_id));
    }

    function get_roles_by_user_ids(Array $user_ids)
    {
        $result = array();
        if(empty($user_ids) || ! is_array($user_ids)) {
            return $result;
        }
        try {
            $this->db->where_in('UserID', $user_ids);
            $query = $this->db->get('user_roles');
            $result = $query->result();
        } catch(Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
        return $result;
    }

    /**
     * 根据用户角色获取权限（数组）
     *
     * @param array $roles
     * @return array
     * @throws Exception
     */
    function get_user_perms(Array $roles)
    {
        $result = array();
        if(empty($roles) || ! is_array($roles)) {
            return $result;
        }
        try {
            $this->db->where_in('ID', $roles);
            $query = $this->db->get('roles');
            $rows = $query->result();
            if(count($rows)) {
                $perms_id = array();
                foreach($rows as $row) {
                    //得到权限ID串，解析成ID数组
                    //角色对应的权限，可一对多，多个id用|隔开
                    $perms_str = $row->PermissionsID;
                    if(! empty($perms_str)) {
                        $perms_id = array_merge($perms_id, explode('|', $perms_str));
                    }
                }
                log_message('debug','perms_id:'.json_encode($perms_id));
                if(count($perms_id)) {
                    //调用Permission_model，得到权限key数组
                    $this->model('Permission_model');
                    $perms = $this->Permission_model->get_by_ids($perms_id);
                    if(count($perms)) {
                        foreach($perms as $perm) {
                            $result[] = $perm->PermKey;
                        }
                    }
                }

            }
        } catch(Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
        return $result;
    }

}