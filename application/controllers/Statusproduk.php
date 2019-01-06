<?php error_reporting(0); if (!defined('BASEPATH')) exit('No direct script access allowed');

class Statusproduk extends SB_Controller 
{

	protected $layout 	= "layouts/main";
	public $module 		= 'statusproduk';
	public $per_page	= '10';

	function __construct() {
		parent::__construct();
		
		$this->load->model('statusprodukmodel');
		$this->model = $this->statusprodukmodel;
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);	
		$this->data = array_merge( $this->data, array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'	=> 'statusproduk',
		));
		
		if(!$this->session->userdata('logged_in')) redirect('user/login',301);
		
	}
	
	function index() 
	{
		if($this->access['is_view'] ==0)
		{ 
			SiteHelpers::alert('error','Your are not allowed to access the page');
			redirect('dashboard',301);
		}	
		  
		
		// Group users permission
		$this->data['access']		= $this->access;
		// Render into template
		redirect( 'statusproduk/add/'.$ID,301);
		
		$this->data['content'] = $this->load->view('statusproduk/index',$this->data, true );
		
    	$this->load->view('layouts/main', $this->data );
    
	  
	}
	
	function show( $id = null) 
	{
		if($this->access['is_detail'] ==0)
		{ 
			SiteHelpers::alert('error','Your are not allowed to access the page');
			redirect('dashboard',301);
	  	}		

		$row = $this->model->getRow($id);
		if($row)
		{
			$this->data['row'] =  $row;
		} else {
			$this->data['row'] = $this->model->getColumnTable('t_transaksi'); 
		}
		
		$this->data['id'] = $id;
		$this->data['content'] =  $this->load->view('statusproduk/view', $this->data ,true);	  
		$this->load->view('layouts/main',$this->data);
	}
  
	function add( $id = null ) 
	{
		if($id =='')
			if($this->access['is_add'] ==0) redirect('dashboard',301);

		if($id !='')
			if($this->access['is_edit'] ==0) redirect('dashboard',301);	

		$row = $this->model->getRow( $id );
		if($row)
		{
			$this->data['row'] =  $row;
		} else {
			$this->data['row'] = $this->model->getColumnTable('t_transaksi'); 
		}
	
		$this->data['id'] = $id;
		$this->data['content'] = $this->load->view('statusproduk/form',$this->data, true );		
	  	$this->load->view('layouts/main', $this->data );
	
	}
	
	function save() {
		
		$rules = $this->validateForm();

		$this->form_validation->set_rules( $rules );

		if( !empty($rules) && $this->form_validation->run()){
			$data =	array(
					'message'	=> 'Ops , The following errors occurred',
					'errors'	=> validation_errors('<li>', '</li>')
					);			
			$this->displayError($data);
		}

			$data = $this->validatePost();
			$ID = $this->model->insertRow($data , $this->input->get_post( 'ID' , true ));
			// Input logs
			if( $this->input->get( 'ID' , true ) =='')
			{
				$this->inputLogs("New Entry row with ID : $ID  , Has Been Save Successfull");
			} else {
				$this->inputLogs(" ID : $ID  , Has Been Changed Successfull");
			}
			// Redirect after save	
			SiteHelpers::alert('success'," Data has been saved succesfuly !");
			if($this->input->post('apply'))
			{
				redirect( 'statusproduk/add/'.$ID,301);
			} else {
				redirect( 'statusproduk',301);
			}				
	}

	function destroy()
	{
		if($this->access['is_remove'] ==0)
		{ 
			SiteHelpers::alert('error','Your are not allowed to access the page');
			redirect('dashboard',301);
	  	}
			
		$this->model->destroy($this->input->post( 'id' , true ));
		$this->inputLogs("ID : ".implode(",",$this->input->post( 'id' , true ))."  , Has Been Removed Successfull");
			SiteHelpers::alert('success',"ID : ".implode(",",$this->input->post( 'id' , true ))."  , Has Been Removed Successfull");
		Redirect('statusproduk',301); 
	}

	function find() {
		if( isset( $_POST['idProduk'] ) ){
			// $data['t_dexlitetrx'] = array();	    
			// $t_dexlitetrx_number = $this->db->query("SELECT * FROM t_transaksi WHERE ID = (SELECT MAX(ID) FROM t_transaksi WHERE idProduk='$_POST[idProduk]')")->num_rows();				
			// $t_dexlitetrx = $this->db->query("SELECT * FROM t_transaksi WHERE ID = (SELECT MAX(ID) FROM t_transaksi WHERE idProduk='$_POST[idProduk]')")->result_array();
			// $rata_t_dexlitetrx = $this->db->query("SELECT AVG(B1Tera +B2Tera +B3Tera + B4Tera) rata FROM t_detail_transaksi where idProduk='$_POST[idProduk]'")->result_array();

			// foreach($rata_t_dexlitetrx as $rata){
			// 	$data['rata_t_dexlitetrx'][] =  array(
			// 		'rata' => $rata['rata'],
			// 	);            
			// }

			// foreach($t_dexlitetrx as $dexlite){
			// 	$data['t_dexlitetrx'][] =  array(
			// 		'idDexlite' => $dexlite['ID'],
			// 		'stokAwal' => $dexlite['stokAwal'],
			// 		'bbmMasuk'        => $dexlite['bbmMasuk'],
			// 		'sisa'        => $dexlite['sisa'],
			// 		'stokAkhir'        => $dexlite['stokAkhir'],
			// 		'shift'        => $dexlite['shift'],
			// 		'tanggal'        => $dexlite['tanggal'],
			// 	);            
			// }
			// $this->data['t_dexlitetrx'] =  $data['t_dexlitetrx'];
			// $this->data['rata_t_dexlitetrx'] =  $data['rata_t_dexlitetrx'];
			// $data['result'] = array(
			// 	'data' => $data['t_dexlitetrx'],
			// 	'rata' => $data['rata_t_dexlitetrx'],
			// );	    
			// $myJSON = json_encode($data['result']);
			// echo $myJSON;

			$t_dexlitetrx_number = $this->db->query("SELECT * FROM t_transaksi WHERE ID = (SELECT MAX(ID) FROM t_transaksi WHERE idProduk='$_POST[idProduk]')")->num_rows();					
			$t_dexlitetrx = $this->db->query("SELECT COUNT(ID) as jumlah, MAX(stokAwal) as stokAwal, SUM(bbmMasuk) as bbmMasuk, AVG(bbmMasuk) as rataRataPenjualan,SUM(sisa) as sisa, MAX(stokAkhir) as stokAkhir FROM t_transaksi WHERE idProduk='$_POST[idProduk]'")->result_array();

			foreach($t_dexlitetrx as $pertamax){
				$data['t_dexlitetrx'][] =  array(
					'jumlah' => $pertamax['jumlah'],
					'rataRataPenjualan' => $pertamax['rataRataPenjualan'],
					'stokAwal' => $pertamax['stokAwal'],
					'bbmMasuk'        => $pertamax['bbmMasuk'],
					'sisa'        => $pertamax['sisa'],
					'stokAkhir'        => $pertamax['stokAkhir'],
				);            
			}


			$t_dexliteMAX = $this->db->query("SELECT ID as jumlah, stokAwal,bbmMasuk,sisa, stokAkhir FROM t_transaksi WHERE ID = (SELECT MAX(ID) FROM t_transaksi WHERE idProduk='$_POST[idProduk]')")->result_array();

			foreach($t_dexliteMAX as $pertamax){
				$data['t_dexliteMAX'][] =  array(
					'jumlah' => $pertamax['jumlah'],
					'stokAwal' => $pertamax['stokAwal'],
					'bbmMasuk'        => $pertamax['bbmMasuk'],
					'sisa'        => $pertamax['sisa'],
					'stokAkhir'        => $pertamax['stokAkhir'],
					'table' => 't_dexlitetrx'	,
				);            
			}

			$t_dexlite = $this->db->query("SELECT * FROM t_produk where idProduk='$_POST[idProduk]'")->result_array();
				foreach($t_dexlite as $pertamax){
					$data['t_dexlite'][] =  array(
						'idProduk' => $pertamax['idProduk'],
						'namaProduk' => $pertamax['namaProduk'],
						'hargaBeli' => $pertamax['hargaBeli'],
						'hargaJual' => $pertamax['hargaJual'],
						'kapasitasTank' => $pertamax['kapasitasTank'],
					);            
				}

			$data['result'] = array(
			't_dexlitetrx' => $data['t_dexlitetrx'],
			't_dexliteMAX' => $data['t_dexliteMAX'],
			't_dexlite' => $data['t_dexlite'],
			);	    
			$myJSON = json_encode($data['result']);
			echo $myJSON;
		}
	}

}
