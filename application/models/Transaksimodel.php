<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Transaksimodel extends SB_Model 
{

	public $table = 't_transaksi';
	public $primaryKey = 'ID';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		
		// return "   SELECT t_transaksi.* FROM t_transaksi   ";
		return "SELECT t_transaksi.ID as ID, t_transaksi.idProduk as idProduk, t_transaksi.stokAwal as stokAwal, t_transaksi.bbmMasuk as bbmMasuk, t_transaksi.sisa as sisa, t_transaksi.stokAkhir as stokAkhir, t_transaksi.shift as shift, t_transaksi.jenisTransaksi as jenisTransaksi, t_transaksi.tanggal as tanggal, 
		d.ID as ID_detail, d.Id_Transaksi as Id_Transaksi, d.idProduk as idProduk_detail, d.B1Awal, d.B1Akhir, d.B2Awal, d.B2Akhir, d.B3Awal, d.B3Akhir, d.B4Awal, d.B4Akhir, d.B1Tera, d.B2Tera, d.B3Tera, d.B4Tera
		FROM t_transaksi t_transaksi INNER JOIN t_detail_transaksi d ON t_transaksi.ID = d.Id_Transaksi";
	}
	public static function queryWhere(  ){
		
		// return "  WHERE t_transaksi.ID IS NOT NULL   ";
	}
	
	public static function queryGroup(){
		return "   ";
	}
	
}

?>
