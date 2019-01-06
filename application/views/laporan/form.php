
<script type="text/javascript">
    function loaddata()
    {
    var VAwal=document.getElementById( "periodeAwal" ).value;
    var VAkhir=document.getElementById( "periodeAkhir" ).value;
    var VProduk=document.getElementById( "idProduk" ).value;
    var VTipe=document.getElementById( "tipe" ).value;
    console.log(VTipe);
    console.log(VProduk);
    console.log(VAkhir);
    console.log(VAwal);
    if(VAwal && VAkhir && VTipe != "null"){
		$.ajax({
			type: 'post',
			url: '<?php echo site_url('laporan/find/'); ?>',
			data: {
			awal:VAwal,
			akhir:VAkhir,
      tipe:VTipe,
      idProduk:VProduk
			},
				success: function (response) {
				// We get the element having id of display_info and put the response inside it
				$( '#display_info' ).html(response);
				}
		});
	}else{
    alert('Lengkapi Data Pencarian');
  }
}
</script>
<div class="page-content row">
    <!-- Page header -->
<div class="page-header">
  <div class="page-title">
  <h3> <?php echo 'Report' ?> <small></small></h3>
  </div>
  <ul class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard') ?>"> Dashboard </a></li>
    <li><a href="<?php echo site_url('laporan') ?>"><?php echo $pageTitle ?></a></li>
    <li class="active"> Report </li>
  </ul>      
</div>
 
   <div class="page-content-wrapper m-t">     
    <div class="sbox" >
    <div class="sbox-title" >
      <h5><?php echo 'Report' ?> <small><?php echo $pageNote ?></small></h5>
    </div>
    <div class="sbox-content" >

      
     <form action="<?php echo site_url('laporan/save/'.$row['ID']); ?>" class='form-horizontal'  parsley-validate='true' novalidate='true' method="post" enctype="multipart/form-data" > 
			<div class="col-md-12">
			<div class="box-body">
					<?php if(isset($error['error_exists'])){ ?>
					<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
						<?php echo $error['error_exists'];?>
					</div>
                    <?php } ?>
          <div class="form-group<?php echo (isset($error['error_bulan'])) ? " has-error " : " ";?>">
						<label for="first-name" class="col-sm-4 control-label">
							<?php echo 'Periode';?>
						</label>
						<div class="col-md-2">
							<input type="date" name="periodeAwal" id="periodeAwal" value="" class="form-control" placeholder="ex : 01">
							<?php if(isset($error['error_bulan'])) { ?>
							<span class="help-block">
								<?php echo $error['error_bulan'] ;?>
							</span>
							<?php } ?>
            </div>
            <div class="col-md-2">
							<input type="date" name="periodeAkhir" id="periodeAkhir" value="" class="form-control" placeholder="ex : 01">
							<?php if(isset($error['error_bulan'])) { ?>
							<span class="help-block">
								<?php echo $error['error_bulan'] ;?>
							</span>
							<?php } ?>
            </div>
          </div>
          <div class="form-group<?php echo (isset($error['error_bulan'])) ? " has-error " : " ";?>">
						<label for="first-name" class="col-sm-4 control-label">
							<?php echo 'Tipe Pencarian';?>
						</label>
						<div class="col-md-3">
              <select name='tipe' id='tipe'>
                <option value="null">-- Tipe Pencarian --</option>
                <option value="harian">Harian</option>
                <option value="bulanan">Bulanan</option>
                <option value="tahunan">Tahunan</option>
              </select>
              <br />
									  <i> <small></small></i>
            </div>
          </div>
          <div class="form-group<?php echo (isset($error['error_bulan'])) ? " has-error " : " ";?>">
						<label for="first-name" class="col-sm-4 control-label">
							<?php echo 'Produk';?>
						</label>
						<div class="col-md-3">
						<select name='idProduk' id='idProduk' rows='5' code='{$idProduk}' 
							class='select2 '  required  ></select> <br />
									  <i> <small></small></i>
            </div>
					</div>
				</div>
			</div>
    </form>
		<div class="col-md-9">
				<button onclick="loaddata()" class="btn btn-info pull-right">
					<?php echo 'Cari';?>
				</button>
		</div>
		<div class="col-md-12" id="display_info" >
		</div>  
    <div style="clear:both"></div>  

    </div>
    </div>

  </div>  
</div>  
</div>
       
<script type="text/javascript">
$(document).ready(function() { 

		$("#idProduk").jCombo("<?php echo site_url('laporan/comboselect?filter=t_produk:idProduk:idProduk') ?>",
		{  selected_value : '<?php echo $row["idProduk"] ?>' });
		    
});
</script>     