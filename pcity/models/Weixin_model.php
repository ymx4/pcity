<?php

class Weixin_model extends MY_Model
{
    public $table = 'wx_user';

    public function member_count($nickname)
    {
        $this->db->from($this->table);
        $this->db->like('nickname', $nickname);

        return $this->db->count_all_results();
    }

    public function member_list($nickname, $limit, $offset)
    {
        $this->db->from($this->table);
        $this->db->like('nickname', $nickname);
        $this->db->limit($limit);
        $this->db->offset($offset);
        return $this->db->get()->result_array();
    }

	public function get_user_by_id($uid){
        $data = $this->db->get_where("wx_user",array('id'=>$uid))->row_array();
       
        if (empty($data)){
        	return FALSE;
        }else{
        	return $data;
        }
	}

	public function get_user_by_openid($openid){

        $data = $this->db->get_where("wx_user",array('openid'=>$openid))->row_array();

        if (empty($data)){
        	return FALSE;
        }else{
        	return $data;
        }
	}

	public function save_media($media_id,$path,$state){
		$data = array();
		$data['media_id'] = $media_id;
		$data['path'] = $path;
		$data['state'] = $state;
		$data['create_time'] = time();
		$this->db->insert('wx_media',$data);
		return $this->db->insert_id();
	}

	public function set_store_data($appid,$type,$token,$expire){
		$data = array();
		$data['data'] = $token;
        $data['expire_time'] = time() + $expire - 60; //expire time - 1min
        $data['app_id'] = $appid;
        $data['type'] = $type;
        $data['create_time'] = time();
        $this->db->replace('wx_data_store',$data);
	}

	public function get_store_data($appid,$type){
		$dt = $this->db->get_where("wx_data_store",array('type'=>$type,'app_id'=>$appid))->row_array();
		if(!empty($dt)){
			if ($dt['expire_time'] > time()){
				return $dt['data'];
			}
			return -2;
		}
		return -1;
	}

	public function update_user($userinfo){
		$db_data = array();
		$openid = $db_data['openid'] = $userinfo['openid'];
		$db_data['nickname'] = isset($userinfo['nickname'])?$userinfo['nickname']:'unknown_nickname';
        $db_data['sex'] = isset($userinfo['sex'])?intval($userinfo['sex']):0;
        $db_data['city'] = isset($userinfo['city'])?$userinfo['city']:'';
        $db_data['country'] = isset($userinfo['country'])?$userinfo['country']:'';
		$db_data['province'] = isset($userinfo['province'])?$userinfo['province']:'';
		$db_data['headimgurl'] = isset($userinfo['headimgurl'])?$userinfo['headimgurl']:'';
        $db_data['language'] = isset($userinfo['language'])?$userinfo['language']:'';
		$db_data['update_time'] = time();

		$this->db->select('*');
		$this->db->from('wx_user');
		$this->db->where('openid',$openid);
		$this->db->limit(1);
      
        $query = $this->db->get();

        $data = $query->row_array();
        if (empty($data)){
        	 $db_data['create_time'] = $db_data['update_time'];
        	 $this->db->insert('wx_user',$db_data);
        	 $db_data['id'] = $this->db->insert_id();
        }else{
        	$this->db->where('openid',$openid);
        	$this->db->update('wx_user',$db_data);
        	$db_data['id'] = $data['id'];
        }
        return $db_data;
	}
}