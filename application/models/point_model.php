<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Point_model extends CI_Model{ 
    private $table = 'point';
    private $primaryKey = 'pointId';

    function __construct(){
        parent::__construct();
    }

    function select_all_data(){ // 撈出所有資料
        // $this->db->order_by($this->primaryKey, "desc"); // 排序(大到小)
        $this->db->join('point', 'point.projectId = project.projectId');
        $query = $this->db->get('project'); 
        return $query->result();
    }

    function select_data($projectId){ //撈出單筆資料
        $this->db->join('point', 'point.projectId = project.projectId');
        $query = $this->db->get_where('project', array('project.projectId' => $projectId));
        return $query->result();
    }
    function select_data_point($projectId, $pointId){
        $this->db->join('point', 'point.projectId = project.projectId');
        $query = $this->db->get_where('project', array(
            'project.projectId' => $projectId,
            'point.pointId' => $pointId
        ));
        return $query->result();
    }

    function insert_data($data){ // 傳陣列進來
        $this->db->insert($this->table, $data); 
        return $this->db->insert_id(); // 回傳主鍵
    }

    function insert_project($data){ // 新增一個project
        $this->db->insert('project', $data);
        return $this->db->insert_id(); // 回傳主鍵
    }

    function update_data($data){ // 傳陣列進來
        $this->db->where($this->primaryKey, $data[$this->primaryKey]);
        $this->db->update($this->table ,$data);
        return $this->db->affected_rows(); // 還傳影響幾筆資料
    }

    function delete_data($id){ //刪除單筆資料
        $this->db->delete($this->table, array($this->primaryKey => $id)); 
        return $this->db->affected_rows();
    }

}