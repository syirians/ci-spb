<?php error_reporting(0); if (!defined('BASEPATH')) exit('No direct script access allowed');

class Transaksi extends SB_Controller 
{

	protected $layout 	= "layouts/main";
	public $module 		= 'transaksi';
	public $per_page	= '10';

	function __construct() {
		parent::__construct();
		
		$this->load->model('transaksimodel');
		$this->model = $this->transaksimodel;
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);	
		$this->data = array_merge( $this->data, array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'	=> 'transaksi',
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
		  
		// Filter sort and order for query 
		$sort = (!is_null($this->input->get('sort', true)) ? $this->input->get('sort', true) : 'ID'); 
		$order = (!is_null($this->input->get('order', true)) ? $this->input->get('order', true) : 'asc');
		// End Filter sort and order for query 
		// Filter Search for query		
		$filter = (!is_null($this->input->get('search', true)) ? $this->buildSearch() : '');
		// End Filter Search for query 
		
		$page = max(1, (int) $this->input->get('page', 1));
		$params = array(
			'page'		=> $page ,
			'limit'		=> ($this->input->get('rows', true) !='' ? filter_var($this->input->get('rows', true),FILTER_VALIDATE_INT) : $this->per_page ) ,
			'sort'		=> $sort ,
			'order'		=> 'DESC',
			'params'	=> $filter,
			'global'	=> (isset($this->access['is_global']) ? $this->access['is_global'] : 0 )
		);
		// Get Query 
		$results = $this->model->getRows( $params );		

		// Build pagination setting
		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;	
		#$pagination = Paginator::make($results['rows'], $results['total'],$params['limit']);		
		$this->data['rowData']		= $results['rows'];
		// Build Pagination
		
		$pagination = $this->paginator( array(
			'total_rows' => $results['total'] ,
			'per_page'	 => $params['limit']
		));
		$this->data['pagination']	= $pagination;
		// Row grid Number 
		$this->data['i']			= ($page * $params['limit'])- $params['limit']; 
		// Grid Configuration 
		$this->data['tableGrid'] 	= $this->info['config']['grid'];
		$this->data['tableForm'] 	= $this->info['config']['forms'];
		$this->data['colspan'] 		= SiteHelpers::viewColSpan($this->info['config']['grid']);		
		// Group users permission
		$this->data['access']		= $this->access;
		// Render into template
		
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
		$this->data['content'] =  $this->load->view('transaksi/view', $this->data ,true);	  
		$this->load->view('layouts/main',$this->data);
	}
  
	function add( $id = null ) 
	{
		if($id =='')
			if($this->access['is_add'] ==0) redirect('dashboard',301);

		if($id !='')
			if($this->access['is_edit'] ==0) redirect('dashboard',301);	
			
			
		// $row = $this->model->getRow( $id );
		if ($id!='') {
			$rows = $this->db->query("SELECT 
								t_transaksi.ID as ID, t_transaksi.idProduk as idProduk, t_transaksi.stokAwal as stokAwal, 
								t_transaksi.bbmMasuk as bbmMasuk, t_transaksi.sisa as sisa, t_transaksi.stokAkhir as stokAkhir, 
								t_transaksi.shift as shift, t_transaksi.jenisTransaksi as jenisTransaksi, t_transaksi.tanggal as tanggal, 
								d.ID as ID_detail, d.Id_Transaksi as Id_Transaksi, d.idProduk as idProduk_detail, d.B1Awal, 
								d.B1Akhir, d.B2Awal, d.B2Akhir, d.B3Awal, d.B3Akhir, d.B4Awal, d.B4Akhir, d.B1Tera, d.B2Tera, d.B3Tera, d.B4Tera 
								FROM t_transaksi t_transaksi INNER JOIN t_detail_transaksi d ON t_transaksi.ID = d.Id_Transaksi 
								WHERE t_transaksi.ID=$id")->row();
		}
		
		// var_dump($row);
		// echo '</br>';
		// var_dump($array = json_decode(json_encode($rows), True));
		$row = json_decode(json_encode($rows), True);
		if($row)
		{
			$this->data['row'] =  $row;
		} else {
			$this->data['row'] = $this->model->getColumnTable('t_transaksi'); 
		}
	
		$this->data['id'] = $id;
		$this->data['content'] = $this->load->view('transaksi/form',$this->data, true );		
	  	$this->load->view('layouts/main', $this->data );
	
	}
	
	function save($id = null) {
		
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
			
			$result = $this->db->query("SELECT t_transaksi.id, t_transaksi.idProduk idProduk, t_transaksi.stokAwal stokAwal, t_transaksi.bbmMasuk bbmMasuk, t_transaksi.sisa sisa, t_transaksi.stokAkhir stokAkhir, t_transaksi.shift shift, t_transaksi.tanggal tanggal, t_produk.namaProduk namaProduk FROm t_transaksi INNER JOIN t_produk ON t_transaksi.idProduk=t_produk.idProduk WHERE t_transaksi.ID=(SELECT MAX(ID) FROM t_transaksi WHERE idProduk='$data[idProduk]') ORDER BY ID DESC")->result_array();
			$row = $this->db->query("SELECT t_transaksi.id, t_transaksi.idProduk idProduk, t_transaksi.stokAwal stokAwal, t_transaksi.bbmMasuk bbmMasuk, t_transaksi.sisa sisa, t_transaksi.stokAkhir stokAkhir, t_transaksi.shift shift, t_transaksi.tanggal tanggal, t_produk.namaProduk namaProduk FROm t_transaksi INNER JOIN t_produk ON t_transaksi.idProduk=t_produk.idProduk WHERE t_transaksi.ID=(SELECT MAX(ID) FROM t_transaksi WHERE idProduk='$data[idProduk]') ORDER BY ID DESC")->row();
			
			
			foreach($result as $result){
				$stokAwal=$result['stokAwal'];
				$bbmMasuk=$data['bbmMasuk'];
				$sisa=$data['stokAkhir'];
				$stokAkhir=$data['stokAkhir'];
				$shift=$result['shift'];
				$tanggal=$result['tanggal'];
			}

			if($id != NULL){
				$dataInput=array(
					'stokAwal'=>$stokAwal,
					'bbmMasuk'=>$data['bbmMasuk'],
					'sisa'=>$data['stokAkhir'],
					'stokAkhir'=>$data['stokAkhir'],
					'tanggal'=> $data['tanggal'],
					'idProduk'=> $data['idProduk']
				);
				$this->db->where('ID', $id);
				$this->db->update('t_transaksi', $dataInput);
				$Detail=  array(
					'ID' => NULL,
					'Id_Transaksi' => $id,
					'idProduk' => $_POST['idProduk'],
					'B1Awal'        => $_POST['tawal_b1'],
					'B1Akhir'        => $_POST['takhir_b1'],
					'B1Tera'        => $_POST['tera_b1'],
					'B2Awal'        => $_POST['tawal_b2'],
					'B2Akhir'        => $_POST['takhir_b2'],
					'B2Tera'        => $_POST['tera_b2'],
					'B3Awal'        => $_POST['tawal_b3'],
					'B3Akhir'        => $_POST['takhir_b3'],
					'B3Tera'        => $_POST['tera_b3'],
					'B4Awal'        => $_POST['tawal_b4'],
					'B4Akhir'        => $_POST['takhir_b4'],
					'B4Tera'        => $_POST['tera_b4'],
				);
				$this->db->where('Id_Transaksi', $id);
				$this->db->update('t_detail_transaksi', $Detail);
			}else{
				$dataInput=array(
					'stokAwal'=>$stokAwal,
					'bbmMasuk'=>$bbmMasuk,
					'sisa'=>$sisa,
					'stokAkhir'=>$stokAkhir,
					'tanggal'=> $data['tanggal'],
					'idProduk'=> $data['idProduk']
				);
				$ID = $this->model->insertRow($dataInput , $this->input->get_post( 'ID' , true ));
				$Detail=  array(
					'ID' => NULL,
					'Id_Transaksi' => $ID,
					'idProduk' => $_POST['idProduk'],
					'B1Awal'        => $_POST['tawal_b1'],
					'B1Akhir'        => $_POST['takhir_b1'],
					'B1Tera'        => $_POST['tera_b1'],
					'B2Awal'        => $_POST['tawal_b2'],
					'B2Akhir'        => $_POST['takhir_b2'],
					'B2Tera'        => $_POST['tera_b2'],
					'B3Awal'        => $_POST['tawal_b3'],
					'B3Akhir'        => $_POST['takhir_b3'],
					'B3Tera'        => $_POST['tera_b3'],
					'B4Awal'        => $_POST['tawal_b4'],
					'B4Akhir'        => $_POST['takhir_b4'],
					'B4Tera'        => $_POST['tera_b4'],
				);
				$this->db->insert('t_detail_transaksi', $Detail);
			}
			
			
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
				redirect( 'transaksi/add/'.$ID,301);
			} else {
				redirect( 'transaksi',301);
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
		Redirect('transaksi',301); 
	}

	function find() {
		if( isset( $_POST['idProduk'] ) ){
			$query = $this->db->query("SELECT * FROM t_transaksi WHERE ID = (SELECT MAX(ID) FROM t_transaksi  WHERE idProduk ='$_POST[idProduk] ')");
			$result = $query->result();
			$query->free_result();
			$myJSON = json_encode($result);
			echo $myJSON;
		}
	}
}
