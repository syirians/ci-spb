<?php error_reporting(0); if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends SB_Controller {

    function __construct()
    {
        parent::__construct();    
    if(!$this->session->userdata('logged_in')) redirect('user/login',301);      
    }

  public function index()
  {
    $this->data = array();
    
    
    $this->data['content'] = $this->load->view('dashboard',$this->data,true);    
    $this->load->view('layouts/main',$this->data);
  }
  
  
  function find() {
			$data['produk'] = array();	    
			$MasterProduk = $this->db->query("SELECT * FROM t_produk")->result_array();
			$numRowsProduk = $this->db->query("SELECT * FROM t_produk")->num_rows();
      for ($i=0; $i < $numRowsProduk; $i++) { 
        $a=$this->findR($MasterProduk[$i]['idProduk'],$this->findLabel());
        $color=$this->random_color();
        $data['produk'][$i] =  array(
          "label"=> $MasterProduk[$i]['namaProduk'],
          "namaProduk"=> $a,
					"fillColor" => "rgba(220,220,220,0.2)",
					"borderColor" => $color,
					"pointColor" => $color,
					"pointStrokeColor" => "#fff",
					"pointHighlightFill" => "#fff",
					"pointHighlightStroke" => "rgba(220,220,220,1)",
					"data" => $a,
				);    
      }
			$dataMin=array(
        "label"=> "MINIMAL",
        "namaProduk"=> $a,
        "fillColor" => "rgba(220,220,220,0.2)",
        "borderColor" => "#ed1c42",
        "pointColor" => "#fcb5b9",
        "pointStrokeColor" => "#fcb5b9",
        "pointHighlightFill" => "#fcb5b9",
        "pointHighlightStroke" => "rgba(220,220,220,1)",
        "data" => $this->setMin(),
      );
      $dataAwal=array(
        "label"=> "MAX",
        "namaProduk"=> $a,
        "fillColor" => "#ffffff",
        "borderColor" => "#ffffff",
        "pointColor" => "#ffffff",
        "pointStrokeColor" => "#ffffff",
        "pointHighlightFill" => "#ffffff",
        "pointHighlightStroke" => "#ffffff",
        "data" => $this->setMax(),
      );
      array_push($data['produk'],$dataMin);
      array_push($data['produk'],$dataAwal);
			$this->data['produk'] =  $data['produk'];
			$data['result'] = array(
				'data' => $data['produk'],
				'label' => $this->findLabel(),
			);	    
			$myJSON = json_encode($data['result']);
			echo $myJSON;
	}
  
  function findR($id,$arrTanggal) {
    $transaksi = $this->db->query("SELECT ID,stokAkhir, tanggal FROM t_transaksi WHERE tanggal=CURDATE() ORDER BY tanggal DESC, ID DESC")->result_array();
    $hasil=$transaksi[0]['stokAkhir'];
    $length=7;
    if ($hasil == null) {
      $length=8;
    }
    for ($i=0; $i < $length; $i++) { 
      $m=DateTime::createFromFormat("Y-m-d",$arrTanggal[$i])->format("m");
      $y=DateTime::createFromFormat("Y-m-d",$arrTanggal[$i])->format("Y");
      // $transaksi = $this->db->query("SELECT SUM(stokAkhir) AS stokAkhir, tanggal FROM t_transaksi WHERE idProduk='$id' AND MONTH(tanggal)='$m' AND YEAR(tanggal)='$y'")->result_array();
      $transaksi = $this->db->query("SELECT stokAkhir, tanggal FROM t_transaksi WHERE idProduk='$id' AND tanggal='$arrTanggal[$i]' ORDER BY tanggal DESC, ID DESC")->result_array();
      $hasil=$transaksi[0]['stokAkhir'];
      if ($hasil == null) {
        $transaksi = $this->db->query("SELECT stokAkhir, tanggal FROM t_transaksi WHERE idProduk='$id' AND tanggal<='$arrTanggal[$i]' ORDER BY tanggal DESC, ID DESC")->result_array();
        $hasil=$transaksi[0]['stokAkhir'];
      }
      $data['result'][$i] =$hasil;
    }
    return $data['result'];
	}
  
  function findLabel() {
    $transaksi = $this->db->query("SELECT ID,stokAkhir, tanggal FROM t_transaksi WHERE tanggal=CURDATE() ORDER BY tanggal DESC, ID DESC")->result_array();
    $hasil=$transaksi[0]['stokAkhir'];
    if ($hasil == null) {
      $transaksi = $this->db->query("SELECT ID,stokAkhir, tanggal FROM t_transaksi ORDER BY tanggal DESC, ID DESC")->result_array();
      $hasil=$transaksi[0]['tanggal'];
      $j=6;
      $now=date("Y-m-d");
      for ($i=0; $i < 7; $i++) { 
        $tanggal = $this->db->query("SELECT '$hasil'-INTERVAL $j DAY AS tanggal")->result_array();
        $j--;
        $data['result'][$i] =  $tanggal[0]['tanggal'];    
      }
      $data['result'][7] =  $now;    
    }else{
      $j=6;
      for ($i=0; $i < 7; $i++) { 
        $tanggal = $this->db->query("SELECT CURDATE()-INTERVAL $j DAY AS tanggal")->result_array();
        $j--;
        $data['result'][$i] =  $tanggal[0]['tanggal'];    
      }
   }
    return $data['result'];
  }
  
  function setMax() {
    $transaksi = $this->db->query("SELECT ID,stokAkhir, tanggal FROM t_transaksi WHERE tanggal=CURDATE() ORDER BY tanggal DESC, ID DESC")->result_array();
    $hasil=$transaksi[0]['stokAkhir'];
    $length=7;
    if ($hasil == null) {
      $length=8;
    }
    for ($i=0; $i < $length; $i++) {
      $data['result'][$i] = 30000;
    }	  
    return $data['result'];
  }
  
  function setMin() {
    $transaksi = $this->db->query("SELECT ID,stokAkhir, tanggal FROM t_transaksi WHERE tanggal=CURDATE() ORDER BY tanggal DESC, ID DESC")->result_array();
    $hasil=$transaksi[0]['stokAkhir'];
    $length=7;
    if ($hasil == null) {
      $length=8;
    }
    for ($i=0; $i < $length; $i++) {
      $data['result'][$i] = 1000;
    }	  
    return $data['result'];
  }
  function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
  }

  function random_color() {
      $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
      $color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
      return $color;
  }
  
  function compareByTimeStamp($time1, $time2) 
  { 
    die();
      if (strtotime($time1) < strtotime($time2)) 
          return 1; 
      else if (strtotime($time1) > strtotime($time2))  
          return -1; 
      else
          return 0; 
  } 
}
