<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {
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

	private function job(){
		date_default_timezone_set("Asia/Taipei");
		ignore_user_abort(true); // 使用者如果關掉瀏覽器，但是php還是會繼續執行
		set_time_limit(0); // 讓連結逾時 0

		$compareX = $this->input->post('compareX'); // 比較欄位X
		$compareY = $this->input->post('compareY'); // 比較欄位Y
		$writerX = $this->input->post('writerX'); // 寫入第一個檔案的某個欄位
		$writerY = $this->input->post('writerY'); // 從哪邊要求寫入的某個欄位

		ini_set('memory_limit', '1024M');
		$this->load->library('PHPExcel');
		$this->load->library('PHPExcel/IOFactory');
		$reader = IOFactory::load($data['userfile']['path']);
		$reader1 = IOFactory::load($data['userfile1']['path']);
		$sheet = $reader->getActiveSheet();
		$sheet1 = $reader1->getActiveSheet();
		for ($i=2; $i < $sheet->getHighestRow() ; $i++) { // 第一個excel
			for ($j=2; $j < $sheet1->getHighestRow(); $j++) { // 第二個excel
				if ($sheet->getCell($compareX.$i)->getValue() == $sheet1->getCell($compareY.$j)->getValue() ) {
					$sheet->setCellValue($writerX.$i, $sheet1->getCell($writerY.$j)->getValue());
					// echo 'A'.$i.'  '.'A'.$j ."<br>";
					break;
				}
			}
		}
		$Writer = IOFactory::createWriter($reader, 'Excel5');
		$savePath = 'uploads/'.md5(time()).'.xls';
		$Writer->save($savePath);
		$content = '<a href="'. $savePath .'">點我下載</a>';
		$this->mailing('piece601@hotmail.com', $content);
	}

	private function upload(){ //單檔案上傳
		$config["encrypt_name"] = TRUE;
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg|zip|rar|xlsx|xls|csv|pdf|txt|doc|docx|ppt'; //上傳文件格式
		$this->load->library('upload',$config); //讀取上傳的Lib
		foreach ($_FILES as $key => $value) {
			if( !empty($value['name'])){
				if( !$this->upload->do_upload($key)){
					echo $this->upload->display_errors();
					return false; // 上傳失敗
				}
				$data[$key]['path'] = 'uploads/'.$this->upload->data()['file_name']; // 把檔案路徑放進去
			}
		}
		return $data; // 回傳$data 陣列
	}


	private function _graphic($filePath, $indexRow, $valueRowArray){ // filePath檔案路徑, indexRow要跑的Row, valueRowArray要記錄的Row的Array
		ini_set('memory_limit', '1024M');
		$this->load->model('point_model');
		date_default_timezone_set("Asia/Taipei");
		$projectId = $this->point_model->insert_project(array('date' => date("Y-m-d H:i:s"))); // 新增一個project
		$this->load->library('PHPExcel');
		$this->load->library('PHPExcel/IOFactory');
		$reader = IOFactory::load( $filePath ); // 讀取excel檔案
		$sheet = $reader->getActiveSheet(); // 第一個sheet
		$counter = 0; // 計算是否符合5個
		while ( empty( $sheet->getCell($indexRow.$counter++)->getValue() ) ) {} // 抓到第一筆有值的資料
		$counter--;
		$temp = $sheet->getCell($indexRow.$counter)->getValue(); // 存當前的索引值
		$start = $counter; //把初始值放進去
		$counter = 0;
		for ($i=$start; $i <= $sheet->getHighestRow() ; $i++) {
			if ( empty( $sheet->getCell($indexRow.$i)->getValue() ) ) { // 如果空的就 continue
				continue;
			}	 
			if ($temp == $sheet->getCell($indexRow.$i)->getValue()) { // 集滿五個
				$counter++;
				continue;
			}
			$temp = $sheet->getCell($indexRow.$i)->getValue(); // 放入決策點下一個值
			if ($counter < 5) { // 如果總計小於五個點，就不紀錄
				$counter = 1;
				continue;
			}

			foreach ($valueRowArray as $key => $valueRow) {
				// 把值存進去
				$data = array(
					'pointId' => $this->_back_to_value($sheet, $indexRow, $i, 1), // 抓取題目點 
					'first' => $this->_back_to_value($sheet, $valueRow, $i, 5),
					'second' => $this->_back_to_value($sheet, $valueRow, $i, 4),
					'third' => $this->_back_to_value($sheet, $valueRow, $i, 3),
					'forth' => $this->_back_to_value($sheet, $valueRow, $i, 2),
					'fifth' => $this->_back_to_value($sheet, $valueRow, $i, 1),
					'fieldName' => $valueRow,
					'projectId' => $projectId
					);
				$this->point_model->insert_data($data);
			}

			
			$counter = 1; // 因為值已經到決策點下一個，所以要設定為1
		}
		return $projectId;
	}

	private function _back_to_value(&$sheet, $row, $start ,$round){ // $sheet 是excel的object, $row是哪個row, $start 是從哪個點開始往回找, $round是想往前找第幾個
		for ($i=$round; $i > 0 ; $i--) {
			$start--; // 先往回跳一個 
			while ( empty( $sheet->getCell( $row.$start )->getValue() )) {
				$start--;
			}
		}
		return (int)$sheet->getCell($row.$start)->getValue();
	}


	// private function _graphic2($filePath, $indexRow, $valueRow){ // filePath檔案路徑, indexRow要跑的Row, valueRow要記錄的Row
	// 	$this->load->model('point_model');
	// 	date_default_timezone_set("Asia/Taipei");
	// 	$projectId = $this->point_model->insert_project(array('date' => date("Y-m-d H:i:s"))); // 新增一個project
	// 	$this->load->library('PHPExcel');
	// 	$this->load->library('PHPExcel/IOFactory');
	// 	$reader = IOFactory::load( $filePath ); // 讀取excel檔案
	// 	$sheet = $reader->getActiveSheet(); // 第一個sheet
	// 	$counter = 0; // 計算是否符合5個
	// 	$temp = $sheet->getCell($indexRow.'2')->getValue(); // 存當前的索引

	// 	for ($i=2; $i <= $sheet->getHighestRow() ; $i++) { 
	// 		if ($temp == $sheet->getCell($indexRow.$i)->getValue()) { // 集滿五個
	// 			$counter++;
	// 			continue;
	// 		}
	// 		$temp = $sheet->getCell($indexRow.$i)->getValue(); // 放入決策點下一個值
	// 		if ($counter < 5) { // 如果總計小於五個點，就不紀錄
	// 			$counter = 1;
	// 			continue;
	// 		}

	// 		// 把值存進去
	// 		$data = array(
	// 			'pointId' => $sheet->getCell($indexRow.($i-1)), // 抓取題目點 
	// 			'first' => $sheet->getCell($valueRow.($i-5)),
	// 			'second' => $sheet->getCell($valueRow.($i-4)),
	// 			'third' => $sheet->getCell($valueRow.($i-3)),
	// 			'forth' => $sheet->getCell($valueRow.($i-2)),
	// 			'fifth' => $sheet->getCell($valueRow.($i-1)),
	// 			'projectId' => $projectId
	// 			);
	// 		$this->point_model->insert_data($data);
	// 		$counter = 1; // 因為值已經到決策點下一個，所以要設定為1
	// 	}
	// 	return $projectId;
	// }

	// public function test(){
	// 	$this->load->view('welcome_show');
	// }

	public function show($projectId = NULL){
		$this->load->model('point_model');
		if ($projectId == NULL) {
			$query = $this->point_model->select_all_data();
			$this->load->view('welcome_show_all', array(
				'query' => $query
			));	
			return true;
		}
		$query = $this->point_model->select_data($projectId);
		$this->load->view('welcome_show', array(
			'query' => $query
		));
		return true;
	}

	public function index(){
		if ( empty($_FILES) ){ //判斷無檔案進來
			$this->load->view('file_single');
			return true;
		}

		if ( ! $data = $this->upload() ){
			$this->load->view('failure', array('message' => '上傳失敗'));
			return false;
		}

		// if ( pcntl_fork() ){ //多執行緒
		// 	$this->job();
		// }

		$compareX = $this->input->post('compareX'); // 比較欄位X
		$compareY = $this->input->post('compareY'); // 比較欄位Y
		$writerX = $this->input->post('writerX'); // 寫入第一個檔案的某個欄位
		$writerY = $this->input->post('writerY'); // 從哪邊要求寫入的某個欄位
		ignore_user_abort(true); // 使用者如果關掉瀏覽器，但是php還是會繼續執行
		set_time_limit(0); // 讓連結逾時 0

		ini_set('memory_limit', '1024M'); // 記憶體限制設定1G

		//繪圖完傳回該計畫的projectId
		// $projectId = $this->_graphic($data['userfile1']['path'], $compareY, $this->input->post('graphic'));

		$this->load->library('PHPExcel');
		$this->load->library('PHPExcel/IOFactory');
		$reader = IOFactory::load($data['userfile']['path']);
		$reader1 = IOFactory::load($data['userfile1']['path']);
		$sheet = $reader->getActiveSheet();
		$sheet1 = $reader1->getActiveSheet();
		for ($i=2; $i <= $sheet->getHighestRow() ; $i++) { // 第一個excel
			for ($j=2; $j <= $sheet1->getHighestRow(); $j++) { // 第二個excel
				if ($sheet->getCell($compareX.$i)->getValue() == $sheet1->getCell($compareY.$j)->getValue() ) {
					$sheet->setCellValue($writerX.$i, $sheet1->getCell($writerY.$j)->getValue());
					break;
				}
			}
		}
		$Writer = IOFactory::createWriter($reader, 'Excel5'); // 把檔案寫進去
		$savePath = 'uploads/'.md5(time()).'.xls';
		$Writer->save($savePath);
		// $content = '<a href="'. $savePath .'">點我下載</a>';
		// $this->mailing('piece601@hotmail.com', $content);
		echo '<a href="'. $savePath .'">點我下載</a>';
		return true;
	}

	public function clear(){
    foreach(glob("uploads/*") as $file) {
      if (is_dir($file)) { 
          recursiveRemoveDirectory($file);
      } else {
          unlink($file);
      }
    }
    redirect();
    return true;
	}

	public function graphic(){
		if ( empty($_FILES) ) {
			$this->load->view('graphic');
			return true;
		}
		if ( ! $data = $this->upload() ) {
			$this->load->view('failure', array('message' => '上傳失敗'));
			return true;
		}
		// $projectId = $this->_graphic($data['userfile']['path'], 'A', array('D'));

		$projectId = $this->_graphic($data['userfile']['path'],
																 $this->input->post('search'),
																 $this->input->post('graphicField')); //
		redirect('welcome/test/'.$projectId);
		// echo $projectId;
		return true;
	}

	public function test($projectId = Null, $point = Null){
		$this->load->model('point_model');
		$query = $this->point_model->select_data($projectId);
		$this->load->view('testg', array(
			'query' => $query
		));
	}

}

/* End of file */