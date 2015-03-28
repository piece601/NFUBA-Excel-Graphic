<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class File extends CI_Controller {
	function __construct(){
		parent::__construct();
	}

	private function single_upload(){ //單檔案上傳
		$config["encrypt_name"] = TRUE;
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg|zip|rar|xlsx|xls|csv|pdf|txt|doc|docx|ppt'; //上傳文件格式
		$this->load->library('upload',$config); //讀取上傳的Lib
		if( !$this->upload->do_upload('userfile') ){ // userfile為上傳field name
			return false; //上傳失敗
		}
		$data['path'] = 'uploads/'.$this->upload->data()['file_name']; // 抓取上傳路徑
		return $data; // 回傳$data 陣列
	}

	private function muti_upload(){ //多檔案上傳
		$config["encrypt_name"] = TRUE;
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg|zip|rar|xlsx|xls|csv|pdf|txt|doc|docx|ppt'; //上傳文件格式
		$this->load->library('upload',$config); //讀取上傳的Lib
		foreach ($_FILES as $key => $value) {
			if( !empty($value['name'])){
				if( !$this->upload->do_upload($key)){
					return false; // 上傳失敗
				}
				$data[$key]['path'] = 'uploads/'.$this->upload->data()['file_name']; // 把檔案路徑放進去
			}
		}
		return $data; // 回傳$data 陣列
	}


	public function index(){
		echo '單檔案上傳 ' . base_url('file/single') . "<br>";
		echo '多檔案上傳 ' . base_url('file/muti') . "<br>";
		return true;
	}

	public function single(){ // 單檔案上傳
		if ( empty($_FILES) ){ //判斷無檔案進來
			$this->load->view('file_single');
			return true;
		}

		if ( ! $data = $this->single_upload() ){
			$this->load->view('failure', array('message' => '上傳失敗'));
			return false;
		}
		echo "<pre>";
		print_r($data);
		echo "</pre>";
		return true;
	}

	public function muti(){ //多檔案上傳
		if ( empty($_FILES)){
			$this->load->view('file_muti');
			return true;
		}

		if ( ! $data = $this->muti_upload() ){ //上傳失敗
			$this->load->view('failure', array('message' => '上傳失敗'));
			return false;
		}

		echo "<pre>";
		print_r($data);
		echo "</pre>";
		return true;
	}

}

/* End of file */