<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mail extends CI_Controller {
	function __construct(){
		parent::__construct();
	}

	private function mailing($email = NULL, $content = NULL){
		$this->load->library('email');
		$config = array(
	    'protocol' => 'smtp',
	    'smtp_host' => 'ssl://smtp.gmail.com',
	    'smtp_port' => 465,
	    'smtp_user' => 'piececustom',
	    'smtp_pass' => 'piececustom1234567890',
	    'mailtype'  => 'html', 
	    'charset'   => 'utf-8',
	    'newline' => "\r\n"
		);
		$this->email->initialize($config);
    $this->email->from('piececustom@gmail.com', '發信人');
    $this->email->to($email); // 收信人
    $this->email->subject('主題'); //主題
    $this->email->message($content);  // 內容
    $this->email->send();
    echo $this->email->print_debugger();
    // $this->load->view('email_view');
	}

	public function index(){
		$content = '發信成功' . "<br>" . 'Code By Piece.';
 		$this->mailing('piece601@hotmail.com', $content);
		return false;
	}
}

/* End of file */