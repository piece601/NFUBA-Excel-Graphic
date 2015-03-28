<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sess extends CI_Controller {
	function __construct(){
		parent::__construct();
	}

	public function add($param){
		$this->session->set_userdata($param , $param);
		print_r($this->session->all_userdata());
		return true;
	}

	public function delete(){
		$this->session->sess_destroy();
		print_r($this->session->all_userdata());
		return true;
	}

	public function index(){
		print_r($this->session->all_userdata());
		return true;
	}
	
}

/* End of file */