<?php error_reporting(0); if (!defined('BASEPATH')) exit('No direct script access allowed');

class Laporan extends SB_Controller 
{

	protected $layout 	= "layouts/main";
	public $module 		= 'laporan';
	public $per_page	= '10';

	function __construct() {
		parent::__construct();
		
		$this->load->model('laporanmodel');
		$this->model = $this->laporanmodel;
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);	
		$this->data = array_merge( $this->data, array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'	=> 'laporan',
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
		redirect( 'laporan/add/'.$ID,301);
		
		$this->data['content'] = $this->load->view('laporan/index',$this->data, true );
		
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
		$this->data['content'] =  $this->load->view('laporan/view', $this->data ,true);	  
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
		$this->data['content'] = $this->load->view('laporan/form',$this->data, true );		
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
				redirect( 'laporan/add/'.$ID,301);
			} else {
				redirect( 'laporan',301);
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
		Redirect('laporan',301); 
	}
	function find() {
		if( isset( $_POST['awal'] ) && isset( $_POST['akhir']) ){
			
			if ($_POST['idProduk']) {
				
				if ($_POST['tipe'] == 'harian') {
					$q_laporan = $this->db->query("
					SELECT 
						transaksi.tanggal AS Tanggal,
						produk.namaProduk AS Produk, 
						SUM((detail.B1Akhir - detail.B1Awal - detail.B1Tera) +
						(detail.B2Akhir - detail.B2Awal - detail.B2Tera) +
						(detail.B3Akhir - detail.B3Awal - detail.B3Tera) + 
						(detail.B4Akhir - detail.B4Awal - detail.B4Tera)) AS Total 
					FROM `t_detail_transaksi` detail 
					INNER JOIN t_transaksi transaksi ON detail.Id_Transaksi = transaksi.ID
					INNER JOIN t_produk produk ON transaksi.idProduk = produk.idProduk
					WHERE detail.idProduk='$_POST[idProduk]' AND transaksi.idProduk='$_POST[idProduk]' AND produk.idProduk='$_POST[idProduk]' and transaksi.tanggal BETWEEN '$_POST[awal]' AND '$_POST[akhir]'
					GROUP BY Tanggal,Produk
					ORDER BY Produk DESC, Tanggal DESC
					")->result_array();
					$row = $this->db->query("
					SELECT 
						transaksi.tanggal AS Tanggal,
						produk.namaProduk AS Produk, 
						SUM((detail.B1Akhir - detail.B1Awal - detail.B1Tera) +
						(detail.B2Akhir - detail.B2Awal - detail.B2Tera) +
						(detail.B3Akhir - detail.B3Awal - detail.B3Tera) + 
						(detail.B4Akhir - detail.B4Awal - detail.B4Tera)) AS Total 
					FROM `t_detail_transaksi` detail 
					INNER JOIN t_transaksi transaksi ON detail.Id_Transaksi = transaksi.ID
					INNER JOIN t_produk produk ON transaksi.idProduk = produk.idProduk
					WHERE detail.idProduk='$_POST[idProduk]' AND transaksi.idProduk='$_POST[idProduk]' AND produk.idProduk='$_POST[idProduk]' and transaksi.tanggal BETWEEN '$_POST[awal]' AND '$_POST[akhir]'
					GROUP BY Tanggal,Produk
					ORDER BY Produk DESC, Tanggal DESC
					")->num_rows();
				}else if ($_POST['tipe'] == 'bulanan') {
					$q_laporan = $this->db->query("
					SELECT 
						transaksi.tanggal AS Tanggal,
						produk.namaProduk AS Produk, 
						SUM((detail.B1Akhir - detail.B1Awal - detail.B1Tera) +
						(detail.B2Akhir - detail.B2Awal - detail.B2Tera) +
						(detail.B3Akhir - detail.B3Awal - detail.B3Tera) + 
						(detail.B4Akhir - detail.B4Awal - detail.B4Tera)) AS Total 
					FROM `t_detail_transaksi` detail 
					INNER JOIN t_transaksi transaksi ON detail.Id_Transaksi = transaksi.ID
					INNER JOIN t_produk produk ON transaksi.idProduk = produk.idProduk
					WHERE detail.idProduk='$_POST[idProduk]' AND transaksi.idProduk='$_POST[idProduk]' AND produk.idProduk='$_POST[idProduk]' and transaksi.tanggal BETWEEN '$_POST[awal]' AND '$_POST[akhir]'
					GROUP BY YEAR(Tanggal), MONTH(Tanggal),Produk
					ORDER BY Produk DESC, Tanggal DESC
					")->result_array();
					$row = $this->db->query("
					SELECT 
						transaksi.tanggal AS Tanggal,
						produk.namaProduk AS Produk, 
						SUM((detail.B1Akhir - detail.B1Awal - detail.B1Tera) +
						(detail.B2Akhir - detail.B2Awal - detail.B2Tera) +
						(detail.B3Akhir - detail.B3Awal - detail.B3Tera) + 
						(detail.B4Akhir - detail.B4Awal - detail.B4Tera)) AS Total 
					FROM `t_detail_transaksi` detail 
					INNER JOIN t_transaksi transaksi ON detail.Id_Transaksi = transaksi.ID
					INNER JOIN t_produk produk ON transaksi.idProduk = produk.idProduk
					WHERE detail.idProduk='$_POST[idProduk]' AND transaksi.idProduk='$_POST[idProduk]' AND produk.idProduk='$_POST[idProduk]' and transaksi.tanggal BETWEEN '$_POST[awal]' AND '$_POST[akhir]'
					GROUP BY Tanggal,Produk
					ORDER BY Produk DESC, Tanggal DESC
					")->num_rows();
				}else if ($_POST['tipe'] == 'tahunan') {
					$q_laporan = $this->db->query("
					SELECT 
						transaksi.tanggal AS Tanggal,
						produk.namaProduk AS Produk, 
						SUM((detail.B1Akhir - detail.B1Awal - detail.B1Tera) +
						(detail.B2Akhir - detail.B2Awal - detail.B2Tera) +
						(detail.B3Akhir - detail.B3Awal - detail.B3Tera) + 
						(detail.B4Akhir - detail.B4Awal - detail.B4Tera)) AS Total 
					FROM `t_detail_transaksi` detail 
					INNER JOIN t_transaksi transaksi ON detail.Id_Transaksi = transaksi.ID
					INNER JOIN t_produk produk ON transaksi.idProduk = produk.idProduk
					WHERE detail.idProduk='$_POST[idProduk]' AND transaksi.idProduk='$_POST[idProduk]' AND produk.idProduk='$_POST[idProduk]' and transaksi.tanggal BETWEEN '$_POST[awal]' AND '$_POST[akhir]'
					GROUP BY YEAR(Tanggal),Produk
					ORDER BY Produk DESC, Tanggal DESC
					")->result_array();
					$row = $this->db->query("
					SELECT 
						transaksi.tanggal AS Tanggal,
						produk.namaProduk AS Produk, 
						SUM((detail.B1Akhir - detail.B1Awal - detail.B1Tera) +
						(detail.B2Akhir - detail.B2Awal - detail.B2Tera) +
						(detail.B3Akhir - detail.B3Awal - detail.B3Tera) + 
						(detail.B4Akhir - detail.B4Awal - detail.B4Tera)) AS Total 
					FROM `t_detail_transaksi` detail 
					INNER JOIN t_transaksi transaksi ON detail.Id_Transaksi = transaksi.ID
					INNER JOIN t_produk produk ON transaksi.idProduk = produk.idProduk
					WHERE detail.idProduk='$_POST[idProduk]' AND transaksi.idProduk='$_POST[idProduk]' AND produk.idProduk='$_POST[idProduk]' and transaksi.tanggal BETWEEN '$_POST[awal]' AND '$_POST[akhir]'
					GROUP BY Tanggal,Produk
					ORDER BY Produk DESC, Tanggal DESC
					")->num_rows();
				}
			}else{
				if ($_POST['tipe'] == 'harian') {
					$q_laporan = $this->db->query("
					SELECT 
						transaksi.tanggal AS Tanggal,
						produk.namaProduk AS Produk, 
						SUM((detail.B1Akhir - detail.B1Awal - detail.B1Tera) +
						(detail.B2Akhir - detail.B2Awal - detail.B2Tera) +
						(detail.B3Akhir - detail.B3Awal - detail.B3Tera) + 
						(detail.B4Akhir - detail.B4Awal - detail.B4Tera)) AS Total 
					FROM `t_detail_transaksi` detail 
					INNER JOIN t_transaksi transaksi ON detail.Id_Transaksi = transaksi.ID
					INNER JOIN t_produk produk ON transaksi.idProduk = produk.idProduk
					WHERE transaksi.tanggal BETWEEN '$_POST[awal]' AND '$_POST[akhir]'
					GROUP BY Tanggal,Produk
					ORDER BY Produk DESC, Tanggal DESC
					")->result_array();

					$row = $this->db->query("
					SELECT 
						transaksi.tanggal AS Tanggal,
						produk.namaProduk AS Produk, 
						SUM((detail.B1Akhir - detail.B1Awal - detail.B1Tera) +
						(detail.B2Akhir - detail.B2Awal - detail.B2Tera) +
						(detail.B3Akhir - detail.B3Awal - detail.B3Tera) + 
						(detail.B4Akhir - detail.B4Awal - detail.B4Tera)) AS Total 
					FROM `t_detail_transaksi` detail 
					INNER JOIN t_transaksi transaksi ON detail.Id_Transaksi = transaksi.ID
					INNER JOIN t_produk produk ON transaksi.idProduk = produk.idProduk
					WHERE transaksi.tanggal BETWEEN '$_POST[awal]' AND '$_POST[akhir]'
					GROUP BY Tanggal,Produk
					ORDER BY Produk DESC, Tanggal DESC
					")->num_rows();
				}else if ($_POST['tipe'] == 'bulanan') {
					$q_laporan = $this->db->query("
					SELECT 
						transaksi.tanggal AS Tanggal,
						produk.namaProduk AS Produk, 
						SUM((detail.B1Akhir - detail.B1Awal - detail.B1Tera) +
						(detail.B2Akhir - detail.B2Awal - detail.B2Tera) +
						(detail.B3Akhir - detail.B3Awal - detail.B3Tera) + 
						(detail.B4Akhir - detail.B4Awal - detail.B4Tera)) AS Total 
					FROM `t_detail_transaksi` detail 
					INNER JOIN t_transaksi transaksi ON detail.Id_Transaksi = transaksi.ID
					INNER JOIN t_produk produk ON transaksi.idProduk = produk.idProduk
					WHERE transaksi.tanggal BETWEEN '$_POST[awal]' AND '$_POST[akhir]'
					GROUP BY YEAR(Tanggal), MONTH(Tanggal),Produk
					ORDER BY Produk DESC, Tanggal DESC
					")->result_array();

					$row = $this->db->query("
					SELECT 
						transaksi.tanggal AS Tanggal,
						produk.namaProduk AS Produk, 
						SUM((detail.B1Akhir - detail.B1Awal - detail.B1Tera) +
						(detail.B2Akhir - detail.B2Awal - detail.B2Tera) +
						(detail.B3Akhir - detail.B3Awal - detail.B3Tera) + 
						(detail.B4Akhir - detail.B4Awal - detail.B4Tera)) AS Total 
					FROM `t_detail_transaksi` detail 
					INNER JOIN t_transaksi transaksi ON detail.Id_Transaksi = transaksi.ID
					INNER JOIN t_produk produk ON transaksi.idProduk = produk.idProduk
					WHERE transaksi.tanggal BETWEEN '$_POST[awal]' AND '$_POST[akhir]'
					GROUP BY Tanggal,Produk
					ORDER BY Produk DESC, Tanggal DESC
					")->num_rows();
				}else if ($_POST['tipe'] == 'tahunan') {
					$q_laporan = $this->db->query("
					SELECT 
						transaksi.tanggal AS Tanggal,
						produk.namaProduk AS Produk, 
						SUM((detail.B1Akhir - detail.B1Awal - detail.B1Tera) +
						(detail.B2Akhir - detail.B2Awal - detail.B2Tera) +
						(detail.B3Akhir - detail.B3Awal - detail.B3Tera) + 
						(detail.B4Akhir - detail.B4Awal - detail.B4Tera)) AS Total 
					FROM `t_detail_transaksi` detail 
					INNER JOIN t_transaksi transaksi ON detail.Id_Transaksi = transaksi.ID
					INNER JOIN t_produk produk ON transaksi.idProduk = produk.idProduk
					WHERE transaksi.tanggal BETWEEN '$_POST[awal]' AND '$_POST[akhir]'
					GROUP BY YEAR(Tanggal),Produk
					ORDER BY Produk DESC, Tanggal DESC
					")->result_array();

					$row = $this->db->query("
					SELECT 
						transaksi.tanggal AS Tanggal,
						produk.namaProduk AS Produk, 
						SUM((detail.B1Akhir - detail.B1Awal - detail.B1Tera) +
						(detail.B2Akhir - detail.B2Awal - detail.B2Tera) +
						(detail.B3Akhir - detail.B3Awal - detail.B3Tera) + 
						(detail.B4Akhir - detail.B4Awal - detail.B4Tera)) AS Total 
					FROM `t_detail_transaksi` detail 
					INNER JOIN t_transaksi transaksi ON detail.Id_Transaksi = transaksi.ID
					INNER JOIN t_produk produk ON transaksi.idProduk = produk.idProduk
					WHERE transaksi.tanggal BETWEEN '$_POST[awal]' AND '$_POST[akhir]'
					GROUP BY Tanggal,Produk
					ORDER BY Produk DESC, Tanggal DESC
					")->num_rows();
				}
			}
			
			if ($row != 0) {
				foreach($q_laporan as $laporan){
					$data['q_laporan'][] =  array(
						'tanggal' => $laporan['Tanggal'],
						'produk' => $laporan['Produk'],
						'total' => $laporan['Total'],
					);            
				}
			} else {
				$data['q_laporan'][] =  array(
					'tanggal' => null,
					'produk' => null,
					'total' => null,
				);
			}
			

			echo '<p>Laporan Hasil Penjualan</p>
			<table id="example3" class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>Tanggal</th>
						<th>Produk</th>
						<th>Total</th>
					</tr>
				</thead>
			<tbody>';
			foreach($data['q_laporan'] as $num => $values) {
				echo  '<tr>';
					echo  '<td>';echo $values["tanggal"];echo'</td>';
					echo  '<td>'; echo $values['produk'];echo'</td>';
					echo  '<td>'; echo $values['total'];echo'</td>';
				echo  '</tr>';
			}
			echo  '</tbody>';
			echo  '</table>';
			// $myJSON = json_encode($data['q_laporan']);
			// echo $tmp;
		}
	}

}
