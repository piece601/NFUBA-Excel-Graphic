<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crud_model extends CI_Model{ 
    private $tableName = '表單名稱';
    private $primaryKey = '表單主鍵';

    function __construct(){
        parent::__construct();
    }

    function select_all_data(){ // 撈出所有資料
        $this->db->order_by($this->primaryKey, "desc"); // 排序(大到小)
        $query = $this->db->get($this->tableName); 
        return $query->result();
    }

    function select_data($id){ //撈出單筆資料
        $query = $this->db->get_where($this->tableName, array($this->primaryKey => $id));
        return $query->row();
    }

    function insert_data($data){ // 傳陣列進來
        $this->db->insert($this->tableName, $data); 
        return $this->db->insert_id(); // 回傳主鍵
    }

    function update_data($data){ // 傳陣列進來
        $this->db->where($this->primaryKey, $data[$this->primaryKey]);
        $this->db->update($this->tableName ,$data);
        return $this->db->affected_rows(); // 還傳影響幾筆資料
    }

    function delete_data($id){ //刪除單筆資料
        $this->db->delete($this->tableName, array($this->primaryKey => $id)); 
        return $this->db->affected_rows();
    }

}