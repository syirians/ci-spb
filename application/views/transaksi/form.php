<script>

function loaddata()
{
	var idProduk=document.getElementById( "idProduk" ).value;
	console.log(idProduk);
	if(idProduk){
		$.ajax({
			type: 'post',
			url: '<?php echo site_url('transaksi/find/'); ?>',
			data: {
			idProduk:idProduk
			},
				success: function (response) {
					var data=JSON.parse(response);
					console.log(data.length);
					if (data.length>0) {
						document.getElementById("stokAwal").value=data[0].stokAwal;
						document.getElementById("bbmMasuk").value=data[0].bbmMasuk;

					
						document.getElementById("stokAwal").disabled = false;
						document.getElementById("bbmMasuk").disabled = false;
						document.getElementById("tawal_b1").disabled = false;
						document.getElementById("takhir_b1").disabled = false;
						document.getElementById("tera_b1").disabled = false;
						document.getElementById("tawal_b2").disabled = false;
						document.getElementById("takhir_b2").disabled = false;
						document.getElementById("tera_b2").disabled = false;
						document.getElementById("tawal_b3").disabled = false;
						document.getElementById("takhir_b3").disabled = false;
						document.getElementById("tera_b3").disabled = false;
						document.getElementById("tawal_b4").disabled = false;
						document.getElementById("takhir_b4").disabled = false;
						document.getElementById("tera_b4").disabled = false;
						document.getElementById("apply").disabled = false;
						document.getElementById("submit").disabled = false;
					}else{
						alert("Stok Produk Kosong!");
						document.getElementById("stokAwal").disabled = true;
						document.getElementById("bbmMasuk").disabled = true;
						document.getElementById("tawal_b1").disabled = true;
						document.getElementById("takhir_b1").disabled = true;
						document.getElementById("tera_b1").disabled = true;
						document.getElementById("tawal_b2").disabled = true;
						document.getElementById("takhir_b2").disabled = true;
						document.getElementById("tera_b2").disabled = true;
						document.getElementById("tawal_b3").disabled = true;
						document.getElementById("takhir_b3").disabled = true;
						document.getElementById("tera_b3").disabled = true;
						document.getElementById("tawal_b4").disabled = true;
						document.getElementById("takhir_b4").disabled = true;
						document.getElementById("tera_b4").disabled = true;
						document.getElementById("apply").disabled = true;
						document.getElementById("submit").disabled = true;

						document.getElementById("stokAwal").value = '';
						document.getElementById("bbmMasuk").value = '';
						document.getElementById("tawal_b1").value = '';
						document.getElementById("takhir_b1").value = '';
						document.getElementById("tera_b1").value = '';
						document.getElementById("tawal_b2").value = '';
						document.getElementById("takhir_b2").value = '';
						document.getElementById("tera_b2").value = '';
						document.getElementById("tawal_b3").value = '';
						document.getElementById("takhir_b3").value = '';
						document.getElementById("tera_b3").value = '';
						document.getElementById("tawal_b4").value = '';
						document.getElementById("takhir_b4").value = '';
						document.getElementById("tera_b4").value = '';

					}
				// We get the element having id of display_info and put the response inside it
				$( '#display_info' ).html(response);
				}
		});
	}
}

function Teller() {
	var stokAwal = document.getElementById('stokAwal').value;
  var bbmMasuk = document.getElementById('bbmMasuk').value;
	var result1=0;
	var result2=0;
	var result3=0;
	var result4=0;
	var B1Awal = document.getElementById('tawal_b1').value;
	var B1Akhir = document.getElementById('takhir_b1').value;
  var B1Tera = document.getElementById('tera_b1').value;
	var result1 = parseInt(B1Akhir) - parseInt(B1Awal) - parseInt(B1Tera);

	var B2Awal = document.getElementById('tawal_b2').value;
	var B2Akhir = document.getElementById('takhir_b2').value;
  var B2Tera = document.getElementById('tera_b2').value;
	var result2 = parseInt(B2Akhir) - parseInt(B2Awal) - parseInt(B2Tera);

	var B3Awal = document.getElementById('tawal_b3').value;
	var B3Akhir = document.getElementById('takhir_b3').value;
  var B3Tera = document.getElementById('tera_b3').value;
	var result3 = parseInt(B3Akhir) - parseInt(B3Awal) - parseInt(B3Tera);

	var B4Awal = document.getElementById('tawal_b4').value;
	var B4Akhir = document.getElementById('takhir_b4').value;
  var B4Tera = document.getElementById('tera_b4').value;
	var result4 = parseInt(B4Akhir) - parseInt(B4Awal) - parseInt(B4Tera);

	var result = result1+result2+result3+result4;

	console.log(result);
	if (!isNaN(result)) {
		document.getElementById('stokAkhir').value =stokAwal-result;
	}
}

</script>
<div class="page-content row">
    <!-- Page header -->
<div class="page-header">
  <div class="page-title">
  <h3> <?php echo $pageTitle ?> <small><?php echo $pageNote ?></small></h3>
  </div>
  <ul class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard') ?>"> Dashboard </a></li>
    <li><a href="<?php echo site_url('transaksi') ?>"><?php echo $pageTitle ?></a></li>
    <li class="active"> Form </li>
  </ul>      
</div>
 
   <div class="page-content-wrapper m-t">     
    <div class="sbox" >
    <div class="sbox-title" >
      <h5><?php echo $pageTitle ?> <small><?php echo $pageNote ?></small></h5>
    </div>
    <div class="sbox-content" >

      
     <form action="<?php echo site_url('transaksi/save/'.$row['ID']); ?>" class='form-horizontal'  parsley-validate='true' novalidate='true' method="post" enctype="multipart/form-data" > 

<div class="col-md-12">
						<fieldset>
						<div class="form-group  " >
									<label for="Produk" class=" control-label col-md-2 text-left"> Produk <span class="asterix"> * </span></label>
									<div class="col-md-8">
									  <select onchange="loaddata()" name='idProduk' rows='5' id='idProduk' code='{$idProduk}' 
							class='select2 '  required  ></select> 
									  <i> <small></small></i>
									 </div> 
								  </div> 
								  
								  <div class="form-group  " >
									<label for="Tanggal" class=" control-label col-md-2 text-left"> Tanggal <span class="asterix"> * </span></label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['tanggal'];?>' name='tanggal'  required /> 
									  <i> <small></small></i>
									 </div> 
									</div>
									<div class="form-group  " >
							
									<label for="Shift" class=" control-label col-md-2 text-left"> Shift </label>
									<div class="col-md-8">
									  
					<?php $shift = explode(',',$row['shift']);
					$shift_opt = array( '1' => '1' ,  '2' => '2' ,  '3' => '3' , ); ?>
					<select name='shift' rows='5'   class='select2 '  > 
						<?php 
						foreach($shift_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['shift'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
									  <i> <small></small></i>
									 </div> 
								  </div> 	
				</fieldset>
			</div>
			<div class="col-md-3">
			</div>
			
			<div class="col-md-2">
			<div class="form-group  " >
				<div class="col-md-12" >
				<label for="Produk" > Teller Awal Nozzle B1</label>
				</div>
				<div class="col-md-12">
				<input type='number' class='form-control' placeholder=''  name='tawal_b1' id='tawal_b1' onkeyup="Teller()"  value='<?php echo $row['B1Awal'];?>' /> 
									  <i> <small></small></i>
					</div> 
				</div>
				<div class="form-group  " >
				<div class="col-md-12" >
				<label for="Produk" > Teller Awal Nozzle B2</label>
				</div>
				<div class="col-md-12">
				<input type='number' class='form-control' placeholder=''  name='tawal_b2' id='tawal_b2' onkeyup="Teller()"  value='<?php echo $row['B2Awal'];?>' /> 
									  <i> <small></small></i>
					</div> 
				</div>
				<div class="form-group  " >
				<div class="col-md-12" >
				<label for="Produk" > Teller Awal Nozzle B3</label>
				</div>
				<div class="col-md-12">
				<input type='number' class='form-control' placeholder=''  name='tawal_b3' id='tawal_b3' onkeyup="Teller()"   value='<?php echo $row['B3Awal'];?>'/> 
									  <i> <small></small></i>
					</div> 
				</div>
				<div class="form-group  " >
				<div class="col-md-12" >
				<label for="Produk" > Teller Awal Nozzle B4</label>
				</div>
				<div class="col-md-12">
				<input type='number' class='form-control' placeholder=''  name='tawal_b4' id='tawal_b4' onkeyup="Teller()" value='<?php echo $row['B4Awal'];?>' /> 
									  <i> <small></small></i>
					</div> 
				</div> 
			</div>
			<div class="col-md-2">
			<div class="form-group  " >
				<div class="col-md-12" >
				<label for="Produk" > Teller Akhir Nozzle B1</label>
				</div>
				<div class="col-md-12">
				<input type='number' class='form-control' placeholder=''  name='takhir_b1' id='takhir_b1' onkeyup="Teller()" value='<?php echo $row['B1Akhir'];?>'  /> 
									  <i> <small></small></i>
					</div> 
				</div> 
				<div class="form-group  " >
				<div class="col-md-12" >
				<label for="Produk" > Teller Akhir Nozzle B2</label>
				</div>
				<div class="col-md-12">
				<input type='number' class='form-control' placeholder=''  name='takhir_b2' id='takhir_b2' onkeyup="Teller()"  value='<?php echo $row['B2Akhir'];?>' /> 
									  <i> <small></small></i>
					</div> 
				</div>
				<div class="form-group  " >
				<div class="col-md-12" >
				<label for="Produk" > Teller Akhir Nozzle B3</label>
				</div>
				<div class="col-md-12">
				<input type='number' class='form-control' placeholder=''  name='takhir_b3' id='takhir_b3' onkeyup="Teller()"  value='<?php echo $row['B3Akhir'];?>' /> 
									  <i> <small></small></i>
					</div> 
				</div> 
				<div class="form-group  " >
				<div class="col-md-12" >
				<label for="Produk" > Teller Akhir Nozzle B4</label>
				</div>
				<div class="col-md-12">
				<input type='number' class='form-control' placeholder=''  name='takhir_b4' id='takhir_b4' onkeyup="Teller()"  value='<?php echo $row['B4Akhir'];?>'  /> 
									  <i> <small></small></i>
					</div> 
				</div> 
			</div>
			<div class="col-md-2">
			<div class="form-group  " >
				<div class="col-md-12" >
				<label for="Produk" > Tera Nozzle B1</label>
				</div>
				<div class="col-md-12">
				<input type='number' class='form-control' placeholder=''  name='tera_b1' id='tera_b1' onkeyup="Teller()" value='<?php echo $row['B1Tera'];?>'  /> 
									  <i> <small></small></i>
					</div> 
				</div> 
				<div class="form-group  " >
				<div class="col-md-12" >
				<label for="Produk" > Tera Nozzle B2</label>
				</div>
				<div class="col-md-12">
				<input type='number' class='form-control' placeholder=''  name='tera_b2' id='tera_b2' onkeyup="Teller()"   value='<?php echo $row['B2Tera'];?>'/> 
									  <i> <small></small></i>
					</div> 
				</div> 
				<div class="form-group  " >
				<div class="col-md-12" >
				<label for="Produk" > Tera Nozzle B3</label>
				</div>
				<div class="col-md-12">
				<input type='number' class='form-control' placeholder=''  name='tera_b3' id='tera_b3' onkeyup="Teller()"  value='<?php echo $row['B3Tera'];?>' /> 
									  <i> <small></small></i>
					</div> 
				</div> 
				<div class="form-group  " >
				<div class="col-md-12" >
				<label for="Produk" > Tera Nozzle B4</label>
				</div>
				<div class="col-md-12">
				<input type='number' class='form-control' placeholder=''  name='tera_b4' id='tera_b4' onkeyup="Teller()"  value='<?php echo $row['B4Tera'];?>' /> 
									  <i> <small></small></i>
					</div> 
				</div> 
			</div>

		
<div class="col-md-12">
						<fieldset>
									
								 					
								  <div class="form-group  " >
									<label for="Stok Awal" class=" control-label col-md-2 text-left"> Stok Awal <span class="asterix"> * </span></label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['stokAwal'];?>' name='stokAwal' id='stokAwal' required /> 
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Bbm Masuk" class=" control-label col-md-2 text-left"> Bbm Masuk <span class="asterix"> * </span></label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['bbmMasuk'];?>' name='bbmMasuk' id='bbmMasuk'  required /> 
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <!-- <div class="form-group  " >
									<label for="Sisa" class=" control-label col-md-2 text-left"> Sisa</label>
									<div class="col-md-8"> -->
									  <input type='hidden' class='form-control' placeholder='' value='<?php echo $row['sisa'];?>' name='sisa' id='sisa'/> 
									  <!-- <i> <small></small></i>
									 </div> 
								  </div> 					 -->
								  <div class="form-group  " >
									<label for="Stok Akhir" class=" control-label col-md-2 text-left"> Stok Akhir <span class="asterix"> * </span></label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['stokAkhir'];?>' name='stokAkhir'  id='stokAkhir' required /> 
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								   </fieldset>
			</div>
			
			<div class="col-md-2">
						<fieldset>
				</fieldset>
			</div>
			
			<div class="col-md-2">
						<fieldset>
				</fieldset>
			</div>
			
			
    
      <div style="clear:both"></div>  
        
     <div class="toolbar-line text-center">    
      <input type="submit" name="apply" id="apply"  class="btn btn-info btn-sm" value="<?php echo $this->lang->line('core.btn_apply'); ?>" />
      <input type="submit" name="submit" id="submit"  class="btn btn-primary btn-sm" value="<?php echo $this->lang->line('core.btn_submit'); ?>" />
      <a href="<?php echo site_url('transaksi');?>" class="btn btn-sm btn-warning"><?php echo $this->lang->line('core.btn_cancel'); ?> </a>
     </div>
            
    </form>
    
    </div>
    </div>

  </div>  
</div>  
</div>
       
<script type="text/javascript">
$(document).ready(function() { 

		$("#idProduk").jCombo("<?php echo site_url('transaksi/comboselect?filter=t_produk:idProduk:namaProduk') ?>",
		{  selected_value : '<?php echo $row["idProduk"] ?>' });
		    
});
</script>     