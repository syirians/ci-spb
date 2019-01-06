<div class="page-content row">
    <!-- Page header -->
<div class="page-header">
  <div class="page-title">
  <h3> <?php echo $pageTitle ?> <small><?php echo $pageNote ?></small></h3>
  </div>
  <ul class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard') ?>"> Dashboard </a></li>
    <li><a href="<?php echo site_url('stock') ?>"><?php echo $pageTitle ?></a></li>
    <li class="active"> Form </li>
  </ul>      
</div>
 
   <div class="page-content-wrapper m-t">     
    <div class="sbox" >
    <div class="sbox-title" >
      <h5><?php echo $pageTitle ?> <small><?php echo $pageNote ?></small></h5>
    </div>
    <div class="sbox-content" >

      
     <form action="<?php echo site_url('stock/save/'.$row['ID']); ?>" class='form-horizontal'  parsley-validate='true' novalidate='true' method="post" enctype="multipart/form-data" > 


<div class="col-md-12">
						<fieldset><legend> Stok</legend>
									
								  <div class="form-group  " >
									<label for="Produk" class=" control-label col-md-4 text-left"> Produk <span class="asterix"> * </span></label>
									<div class="col-md-8">
									  <select name='idProduk' rows='5' id='idProduk' code='{$idProduk}' 
							class='select2 '  required  ></select> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Bbm Masuk" class=" control-label col-md-4 text-left"> Bbm Masuk <span class="asterix"> * </span></label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['bbmMasuk'];?>' name='bbmMasuk'  required /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Tanggal" class=" control-label col-md-4 text-left"> Tanggal <span class="asterix"> * </span></label>
									<div class="col-md-8">
									  
				<input type='text' class='form-control date' placeholder='' value='<?php echo $row['tanggal'];?>' name='tanggal'
				style='width:150px !important;'	  required /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> </fieldset>
			</div>
			
			
    
      <div style="clear:both"></div>  
        
     <div class="toolbar-line text-center">    
      <input type="submit" name="apply" class="btn btn-info btn-sm" value="<?php echo $this->lang->line('core.btn_apply'); ?>" />
      <input type="submit" name="submit" class="btn btn-primary btn-sm" value="<?php echo $this->lang->line('core.btn_submit'); ?>" />
      <a href="<?php echo site_url('stock');?>" class="btn btn-sm btn-warning"><?php echo $this->lang->line('core.btn_cancel'); ?> </a>
     </div>
            
    </form>
    
    </div>
    </div>

  </div>  
</div>  
</div>
       
<script type="text/javascript">
$(document).ready(function() { 

		$("#idProduk").jCombo("<?php echo site_url('stock/comboselect?filter=t_produk:idProduk:namaProduk') ?>",
		{  selected_value : '<?php echo $row["idProduk"] ?>' });
		    
});
</script>     