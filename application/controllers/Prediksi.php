<?php error_reporting(0); if (!defined('BASEPATH')) exit('No direct script access allowed');

class Prediksi extends SB_Controller 
{

	protected $layout 	= "layouts/main";
	public $module 		= 'prediksi';
	public $per_page	= '10';

	function __construct() {
		parent::__construct();
		
		$this->load->model('prediksimodel');
		$this->model = $this->prediksimodel;
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);	
		$this->data = array_merge( $this->data, array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'	=> 'prediksi',
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
		redirect( 'prediksi/add/'.$ID,301);
		
		$this->data['content'] = $this->load->view('transaksi/index',$this->data, true );
		
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
		$this->data['content'] =  $this->load->view('prediksi/view', $this->data ,true);	  
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

		$data['t_dexlitetrx'] = array();	    
		$t_dexlitetrx_number = $this->db->query("SELECT * FROM t_transaksi WHERE ID = (SELECT MAX(ID) FROM t_transaksi WHERE idProduk='Dex')")->num_rows();				
		$t_dexlitetrx = $this->db->query("SELECT * FROM t_transaksi WHERE ID = (SELECT MAX(ID) FROM t_transaksi WHERE idProduk='Dex')")->result_array();
		$rata_t_dexlitetrx = $this->db->query("SELECT AVG(B1Tera +B2Tera +B3Tera + B4Tera) rata FROM t_detail_transaksi where idProduk='Dex'")->result_array();

		foreach($rata_t_dexlitetrx as $rata){
			$data['rata_t_dexlitetrx'][] =  array(
				'rata' => $rata['rata'],
			);            
		}

		foreach($t_dexlitetrx as $dexlite){
			$data['t_dexlitetrx'][] =  array(
				'idDexlite' => $dexlite['ID'],
				'stokAwal' => $dexlite['stokAwal'],
				'bbmMasuk'        => $dexlite['bbmMasuk'],
				'sisa'        => $dexlite['sisa'],
				'stokAkhir'        => $dexlite['stokAkhir'],
				'shift'        => $dexlite['shift'],
				'tanggal'        => $dexlite['tanggal'],
			);            
		}
		$this->data['t_dexlitetrx'] =  $data['t_dexlitetrx'];
		$this->data['rata_t_dexlitetrx'] =  $data['rata_t_dexlitetrx'];
		// var_dump($data['t_dexlitetrx']);
		// die();

	
		$this->data['id'] = $id;
		$this->data['content'] = $this->load->view('prediksi/form',$this->data, true );		
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
				redirect( 'prediksi/add/'.$ID,301);
			} else {
				redirect( 'prediksi',301);
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
		Redirect('prediksi',301); 
	}

	function find() {
		if( isset( $_POST['idProduk'] ) ){
			$data['produk'] = array();	    
			$produk_number = $this->db->query("SELECT * FROM t_transaksi WHERE ID = (SELECT MAX(ID) FROM t_transaksi WHERE idProduk='$_POST[idProduk]')")->num_rows();				
			$produk = $this->db->query("SELECT * FROM t_transaksi WHERE ID = (SELECT MAX(ID) FROM t_transaksi WHERE idProduk='$_POST[idProduk]')")->result_array();
			$rata_produk = $this->db->query("SELECT AVG(B1Tera +B2Tera +B3Tera + B4Tera) rata FROM t_detail_transaksi where idProduk='$_POST[idProduk]'")->result_array();
			$dataProduk = $this->db->query("SELECT * From t_produk where idProduk='$_POST[idProduk]'")->result_array();
			$rataMinggu = $this->db->query("SELECT 

			AVG((B1Akhir-B1Awal-B1Tera)+
			 (B2Akhir-B2Awal-B2Tera)+
			 (B3Akhir-B3Awal-B3Tera)+
			 (B4Akhir-B1Awal-B4Tera))
			
			AS Rata
			
			
			FROM `t_detail_transaksi` 
			LEFT JOIN t_transaksi 
			ON t_detail_transaksi.Id_Transaksi = t_transaksi.ID 
			WHERE t_transaksi.idProduk='$_POST[idProduk]' AND t_detail_transaksi.idProduk='$_POST[idProduk]'
			AND t_transaksi.tanggal BETWEEN 
			
			(SELECT DATE_SUB((SELECT MAX(t_transaksi.tanggal) FROM `t_detail_transaksi` 
			LEFT JOIN t_transaksi 
			ON t_detail_transaksi.Id_Transaksi = t_transaksi.ID 
			WHERE t_transaksi.idProduk='$_POST[idProduk]' AND t_detail_transaksi.idProduk='$_POST[idProduk]'), INTERVAL 7 DAY)) 
			
			AND 
			
			(SELECT MAX(t_transaksi.tanggal) FROM `t_detail_transaksi` 
			LEFT JOIN t_transaksi 
			ON t_detail_transaksi.Id_Transaksi = t_transaksi.ID 
			WHERE t_transaksi.idProduk='$_POST[idProduk]' AND t_detail_transaksi.idProduk='$_POST[idProduk]')
			
			ORDER BY t_transaksi.tanggal DESC")->result_array();

			foreach($rata_produk as $rata){
				$data['rata_produk'][] =  array(
					'rata' => $rata['rata'],
				);            
			}

			foreach($produk as $dexlite){
				$data['produk'][] =  array(
					'idDexlite' => $dexlite['ID'],
					'stokAwal' => $dexlite['stokAwal'],
					'bbmMasuk'        => $dexlite['bbmMasuk'],
					'sisa'        => $dexlite['sisa'],
					'stokAkhir'        => $dexlite['stokAkhir'],
					'shift'        => $dexlite['shift'],
					'tanggal'        => $dexlite['tanggal'],
				);            
			}

			foreach($dataProduk as $dataProduk){
				$data['dataProduk'][] =  array(
					'idProduk' => $dataProduk['idProduk'],
					'namaProduk' => $dataProduk['namaProduk'],
					'hargaBeli'        => $dataProduk['hargaBeli'],
					'hargaJual'        => $dataProduk['hargaJual'],
					'kapasitasTank'        => $dataProduk['kapasitasTank'],
				);            
			}

			foreach($rataMinggu as $rata){
				$data['rataMinggu'][] =  array(
					'rataMinggu' => $rata['Rata'],
				);            
			}
			$this->data['produk'] =  $data['produk'];
			$this->data['rata_produk'] =  $data['rata_produk'];
			$data['result'] = array(
				'data' => $data['produk'],
				'rata' => $data['rata_produk'],
				'kapasitas' => $data['dataProduk'],
				'rataMinggu' => $data['rataMinggu'],
			);	    
			$myJSON = json_encode($data['result']);
			echo $myJSON;
		}
	}
}
