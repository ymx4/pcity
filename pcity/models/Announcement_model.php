<?php

class Announcement_model extends MY_Model
{
    public $table = 'announcement';

    public function search_list($q, $where, $limit, $offset)
    {
        $this->db->from($this->table);
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->like('title', $q);
        $this->db->order_by('create_time', 'desc');
        $this->db->limit($limit);
        $this->db->offset($offset);

        return $this->db->get()->result_array();
    }
}
