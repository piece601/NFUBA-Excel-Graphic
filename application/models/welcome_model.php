<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome_model extends CI_Model{ 
	function __construct(){
		parent::__construct();
	}

	function select_all_data(){ // 撈出所有資料
		$this->db->order_by("主鍵", "desc"); // 排序(大到小)
		$query = $this->db->get('表名'); 
		return $query->result();
	}

	function select_data($主鍵){
		$query = $this->db->get_where('表名', array('欄名' => $主鍵));
		return $query->row();
	}

	function insert_data($data){ // 傳陣列進來
		$this->db->insert('表名', $data); 
		return $this->db->insert_id(); // 回傳主鍵
	}

	function update_data($data){ // 傳陣列進來
		$this->db->where('主鍵', $data['主鍵']);
		$this->db->update('表名' ,$data);
		return $this->db->affected_rows(); // 還傳影響幾筆資料
	}

	function delete_data($主鍵){ //刪除單筆資料
		$this->db->delete('表名', array('主鍵' => $主鍵)); 
		return $this->db->affected_rows();
	}

}