<?php
/**
 * Created by PhpStorm.
 * User: liaosy
 * Date: 16/1/22
 * Time: 下午3:54
 */

include_once('Admin_base_model.php');
class User_model extends Admin_base_model {

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取用户角色列表
     *
     * @param $user_id
     * @return array
     */
    function get_roles($user_id)
    {
        $roles = array();
        $roles_keys = array();
        $roles_ids = array();
        $this->db->select('RoleID,RoleKey');
        $this->db->from('user_roles ur');
        $this->db->join('roles r', 'ur.RoleID = r.ID', 'inner');
        $this->db->where('ur.UserID', $user_id);
        $query = $this->db->get();
        $rows = $query->result();
        if(count($rows)) {
            foreach($rows as $row) {
                $roles_ids[] = $row->RoleID;
                $roles_keys[] = $row->RoleKey;
            }
        }
        $roles['ids'] = $roles_ids;
        $roles['keys'] = $roles_keys;
        return $roles;
    }

    /**
     * 获取用户权限列表
     *
     * @param array $role_ids
     * @return array
     */
    function get_perms(Array $role_ids)
    {
        $perms = array();
        $this->db->select('PermKey');
        $this->db->from('role_permissions rp');
        $this->db->join('permissions p', 'rp.PermissionID = p.ID', 'inner');
        $this->db->where_in('rp.RoleID', $role_ids);
        $query = $this->db->get();
        $rows = $query->result();
        if(count($rows)) {
            foreach($rows as $row) {
                $perms[] = $row->PermKey;
            }
        }
        return $perms;
    }

    function get_all_perms()
    {
        $perms = array();
        $this->db->select('PermKey');
        $this->db->from('permissions');
        $query = $this->db->get();
        $rows = $query->result();
        if(count($rows)) {
            foreach($rows as $row) {
                $perms[] = $row->PermKey;
            }
        }
        return $perms;
    }



    function login($username, $password)
    {
        try {
            $or_where = array('LoginName' => $username, 'Mobile' => $username, 'EMail' => $username);
            $this->db->or_where($or_where);
            $query = $this->db->get('users');
            $row = $query->row();
            if(isset($row)) {
                $password = $this->_get_final_password($row->ID, $password);
                if($row->Password === $password) {
                    if($row->Status !== 0) {
                        $roles = $this->get_roles($row->ID);
                        if(count($roles['ids'])) {
                            //用户权限数组
                            if(in_array('super_admin', $roles['keys'])) {
                                //判断是否是超级管理员
                                $perms = $this->get_all_perms();
                            } else {
                                $perms = $this->get_perms($roles['ids']);
                            }
                            if(count($perms) <= 0) {
                                throw new Exception('您没有分配权限', 500);
                            }

                            //登录用户相关信息存入session
                            $_SESSION[Const_string::SessionUserIDKey] = $row->ID;
                            $_SESSION[Const_string::SessionUserNameKey] = $row->LoginName;
                            $_SESSION[Const_string::SessionRolesKey] = $roles['keys'];
                            $_SESSION[Const_string::SessionPermsKey] = $perms;

                            log_message('debug','用户[' . $username . ']的ID:' . $row->ID);
                            log_message('debug','用户[' . $username . ']的LoginName:' . $row->LoginName);
                            log_message('debug','用户[' . $username . ']的角色roles:' . json_encode($roles['keys']));
                            log_message('debug','用户[' . $username . ']的权限perms:' . json_encode($perms));

                            //更新用户最后登录状态信息
                            $set = array('LastLoginTime' => time(),
                                'LastLoginIP' => $this->input->ip_address(),
                                'LastLoginArea' => '',
                                'LoginCount' => (++ $row->LoginCount));
                            $this->db->where('ID', $row->ID);
                            $this->db->update('users', $set);

                            //login success
                            return true;
                        } else {
                            throw new Exception('您没有登录权限', 500);
                        }
                    } else {
                        throw new Exception('账号被禁用', 500);
                    }
                } else {
                    throw new Exception('密码错误', 500);
                }
            } else {
                throw new Exception('账号不存在', 500);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * 退出登录
     */
    function logout()
    {
        log_message('debug','用户[' .   $_SESSION[Const_string::SessionUserNameKey] . '] 退出登录!');
        session_unset();
        session_destroy();
    }

    /**
     * 生成最终密码
     * 密码算法：password=sha1(md5(salt) + md5(password));
     * 最后取前32位:$password = mb_substr($password, 0 , 32);
     *
     * @param $salt
     * @param $password
     * @return string
     */
    private function _get_final_password($salt, $password)
    {
        $password = sha1(md5($salt) . $password);
        $password = mb_substr($password, 0 , 32);
        return $password;
    }

}