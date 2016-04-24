<?php

class Task_model extends MY_Model
{
    public $table = 'task';

    public function search_list($q, $limit, $offset)
    {
        $this->db->from($this->table);
        $this->db->like('title', $q);
        $this->db->order_by('create_time', 'desc');
        $this->db->limit($limit);
        $this->db->offset($offset);

        return $this->db->get()->result_array();
    }
}
