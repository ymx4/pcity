<?php

class Template_model extends MY_Model
{
    public $table = 'template';

    public function search_list($category, $q, $limit, $offset)
    {
        $this->db->from($this->table);
        $this->db->where(array('category' => $category));
        $this->db->like('title', $q);
        $this->db->order_by('create_time', 'desc');
        $this->db->limit($limit);
        $this->db->offset($offset);

        return $this->db->get()->result_array();
    }
}
