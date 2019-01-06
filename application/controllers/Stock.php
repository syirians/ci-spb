<?php error_reporting(0); if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stock extends SB_Controller 
{

	protected $layout 	= "layouts/main";
	public $module 		= 'stock';
	public $per_page	= '10';

	function __construct() {
		parent::__construct();
		
		$this->load->model('stockmodel');
		$this->model = $this->stockmodel;
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);	
		$this->data = array_merge( $this->data, array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'	=> 'stock',
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
		
		$this->data['content'] = $this->load->view('stock/index',$this->data, true );
		
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
		$this->data['content'] =  $this->load->view('stock/view', $this->data ,true);	  
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
		$this->data['content'] = $this->load->view('stock/form',$this->data, true );		
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
			// 
			if ($id != NULL) {
				if ($row == NULL) {
					// foreach($result as $result){
						$stokAwal=$data['bbmMasuk'];
						$bbmMasuk=$data['bbmMasuk'];
						$sisa=$data['bbmMasuk'];
						$stokAkhir=$data['bbmMasuk'];
						// $shift=$result['shift'];
						$tanggal=$data['tanggal'];
					// }
				} else {
					foreach($result as $result){
						$stokAwal=$result['stokAwal'] + $data['bbmMasuk'];
						$bbmMasuk=$data['bbmMasuk'];
						$sisa=$result['sisa'] + $data['bbmMasuk'];
						$stokAkhir=$result['stokAkhir'] + $data['bbmMasuk'];
						$shift=$result['shift'];
						$tanggal=$result['tanggal'];
					}
				}
				$dataInput=array(
					'stokAwal'=>$stokAwal,
					'bbmMasuk'=>$bbmMasuk,
					'sisa'=>$sisa,
					'stokAkhir'=>$stokAkhir,
					'tanggal'=> $data['tanggal'],
					'idProduk'=> $data['idProduk']
				);
				
				$ID = $this->model->insertRow($dataInput , $id);
			} else {
				
				if ($row == NULL) {
				// 	var_dump($result);
				// die();
					// foreach($result as $result){
						$stokAwal=$data['bbmMasuk'];
						$bbmMasuk=$data['bbmMasuk'];
						$sisa=$data['bbmMasuk'];
						$stokAkhir=$data['bbmMasuk'];
						// $shift=$result['shift'];
						$tanggal=$data['tanggal'];
					// }
				} else {
					foreach($result as $result){
						$stokAwal=$result['stokAwal'] + $data['bbmMasuk'];
						$bbmMasuk=$data['bbmMasuk'];
						$sisa=$result['sisa'] + $data['bbmMasuk'];
						$stokAkhir=$result['stokAkhir'] + $data['bbmMasuk'];
						$shift=$result['shift'];
						$tanggal=$result['tanggal'];
					}
				}
				
				$dataInput=array(
					'stokAwal'=>$stokAwal,
					'bbmMasuk'=>$bbmMasuk,
					'sisa'=>$sisa,
					'stokAkhir'=>$stokAkhir,
					'tanggal'=> $data['tanggal'],
					'idProduk'=> $data['idProduk']
				);
				$ID = $this->model->insertRow($dataInput , $this->input->get_post( 'ID' , true ));
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
				redirect( 'stock/add/'.$ID,301);
			} else {
				redirect( 'stock',301);
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
		Redirect('stock',301); 
	}


}
