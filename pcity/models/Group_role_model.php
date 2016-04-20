<?php

class Group_role_model extends MY_Model
{
    public $table = 'group_role';

    public function role_list($where)
    {
        $this->db->select("{$this->table}.user_id,role,nickname,headimgurl");
        $this->db->from($this->table);
        $this->db->join('wx_user', "user_id = wx_user.id");

        if (!empty($where)) {
            $this->db->where($where);
        }
        return $this->db->get()->result_array();
    }
}
