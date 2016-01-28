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

    function login($username, $password)
    {
        try {
            $or_where = array('LoginName' => $username, 'Mobile' => $username, 'EMail' => $username);
            $this->db->or_where($or_where);
            $query = $this->db->get('users');
            $row = $query->row();
            if(isset($row)) {
                //最终密码算法：
                //password=md5(salt+md5(password))
                $password = md5($row->ID . $password);
                if($row->Password === $password) {
                    if($row->Status !== 0) {
                        if(!empty($row->RolesID)) {
                            //用户角色ID，一对多，多个角色id用|隔开
                            $roles_str = $row->RolesID;
                            //用户角色数组
                            $roles = explode('|', $roles_str);
                            $this->model('Role_model');
                            //用户权限数组
                            $perms = $this->Role_model->get_user_perms($roles);
                            log_message('debug', 'perms:'.json_encode($perms));
                            if(count($perms) <= 0) {
                                throw new Exception('您没有分配权限', 500);
                            }

                            //登录用户相关信息存入session
                            $_SESSION[Const_string::SessionUserIDKey] = $row->ID;
                            $_SESSION[Const_string::SessionUserNameKey] = $row->LoginName;
                            $_SESSION[Const_string::SessionRolesKey] = $roles;
                            $_SESSION[Const_string::SessionPermsKey] = $perms;

                            log_message('debug','用户[' . $username . ']的ID:' . $row->ID);
                            log_message('debug','用户[' . $username . ']的LoginName:' . $row->LoginName);
                            log_message('debug','用户[' . $username . ']的角色roles:' . json_encode($roles));
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

}