<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Test_excel extends CI_Controller {
    
    private $objPHPExcel;
    public function __construct()
    {
        parent::__construct();
        /*if (!$this->session->userdata('login')) {
            redirect('ws');
        } else {
            $this->limit = $this->config->item('limit');
            $this->filter = $this->config->item('filter');
            $this->order = $this->config->item('order');
            $this->offset = $this->config->item('offset');
            $this->load->model('m_feeder','feeder');
        }*/
        $this->load->library('phpexcel');
        $this->load->library('PHPExcel/IOFactory');
        
        $this->objPHPExcel = new PHPExcel();
    }
    
    public function index(){
        /*$this->load->library('phpexcel');
        $this->load->library('PHPExcel/IOFactory');
        
        $objPHPExcel = new PHPExcel();*/
        $this->objPHPExcel->getProperties()->setTitle("Service Excel for PDPT")
        ->setDescription("Generate Excel From Webservice");
        
        // Assign cell values
        $this->objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B3', 'Nama Lengkap')
        ->setCellValue('B5', 'Alamat')
        ->setCellValue('B7', 'No. Telp')
        ->setCellValue('F7', 'No. HP')
        ->setCellValue('B9', 'Email')
        ->setCellValue('B11', 'Jenis Kelamin')
        ->setCellValue('B13', 'Status')
        ->setCellValue('B15', 'Tempat/Tanggal Lahir')
        ->setCellValue('B17', 'Pendidikan Terakhir')
        ->setCellValue('B21', 'Gaji yang Diminta');
        // Save it as an excel 2003 file
        $this->objWriter = IOFactory::createWriter($this->objPHPExcel, 'Excel5');
        $this->objWriter->save("assets/".date('dmYHis').".xls");
        $this->load->view('test');
    }

    public function read()
    {
        $file= 'C:/25032015074855.xls';
        $this->objPHPExcel = IOFactory::load($file);
        $sheetData = $this->objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
        
        var_dump($sheetData);
        /*
                //Include path
        set_include_path(get_include_path() . PATH_SEPARATOR . '../../../Classes/');
        
        // PHPExcel_IOFactory
        include 'PHPExcel/IOFactory.php';
        
        
        $inputFileName = './sampleData/example1.xls';
        echo 'Loading file ',pathinfo($inputFileName,PATHINFO_BASENAME),' using IOFactory to identify the format<br />';
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
        
        
        echo '<hr />';
        
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
        var_dump($sheetData);
        */
    }
}