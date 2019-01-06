<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Produkmodel extends SB_Model 
{

	public $table = 't_produk';
	public $primaryKey = 'idProduk';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		
		return "   SELECT t_produk.* FROM t_produk   ";
	}
	public static function queryWhere(  ){
		
		return "  WHERE t_produk.idProduk IS NOT NULL   ";
	}
	
	public static function queryGroup(){
		return "   ";
	}
	
}

?>
