<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Laporanmodel extends SB_Model 
{

	public $table = 't_transaksi';
	public $primaryKey = 'ID';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		
		return "   SELECT t_transaksi.* FROM t_transaksi   ";
	}
	public static function queryWhere(  ){
		
		return "  WHERE t_transaksi.ID IS NOT NULL   ";
	}
	
	public static function queryGroup(){
		return "   ";
	}
	
}

?>
