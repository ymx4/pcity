<?php

class Materiel_model extends MY_Model
{
    public $table = 'materiel';

    public function search_list($q, $where, $limit, $offset)
    {
        $this->db->from($this->table);
        $this->db->where($where);
        if (!empty($q)) {
        	$this->db->like('title', $q);
        }
        $this->db->order_by('create_time', 'desc');
        $this->db->limit($limit);
        $this->db->offset($offset);

        return $this->db->get()->result_array();
    }
}
