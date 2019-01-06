<script>

function loaddata()
{
	var idProduk=document.getElementById( "idProduk" ).value;
	console.log(idProduk);
	if(idProduk){
		$.ajax({
			type: 'post',
			url: '<?php echo site_url('prediksi/find/'); ?>',
			data: {
			idProduk:idProduk
			},
				success: function (response) {
					var data=JSON.parse(response);
					console.log(data.length);
					if (data.data.length>0) {
						if (data.rata[0].rata== 0) {
							document.getElementById("prediksi").value=0;
						} else {
							var stokAkhir=data.data[0].stokAkhir;
							var rata=data.rata[0].rata;
							var rataMinggu=data.rataMinggu[0].rataMinggu;
							var kapasitas=data.kapasitas[0].kapasitasTank;
							var prediksiPembelian=Math.round(parseInt(kapasitas)-parseInt(stokAkhir));
							document.getElementById("prediksi").value=Math.round(parseInt(stokAkhir)/parseInt(rata));
							document.getElementById("rataMinggu").value=parseInt(rataMinggu);
							document.getElementById("terpakai").value=Math.round(parseInt(kapasitas)-parseInt(stokAkhir));
                            if (prediksiPembelian >= 18000) {
							    document.getElementById("prediksiPembelian").value=24000;
                            }else if(prediksiPembelian >= 12000 && prediksiPembelian < 18000 ) {
							    document.getElementById("prediksiPembelian").value=16000;
                            }else if(prediksiPembelian >= 4000 && prediksiPembelian < 12000 ) {
							    document.getElementById("prediksiPembelian").value=8000;
                            }
						}
						document.getElementById("kapasitas").value=parseInt(kapasitas);
						document.getElementById("stokAkhir").value=parseInt(data.data[0].stokAkhir);
						document.getElementById("rata").value=parseInt(data.rata[0].rata);
						document.getElementById("stokAkhir").disabled = false;
						document.getElementById("rata").disabled = false;
						document.getElementById("prediksi").disabled = false;
		
					}else{
						alert("Stok Produk Kosong!");
						document.getElementById("stokAkhir").disabled = false;
						document.getElementById("rata").disabled = false;
						document.getElementById("prediksi").disabled = false;

						document.getElementById("stokAkhir").value = 0;
						document.getElementById("rata").value = 0;
						document.getElementById("prediksi").value = 0;

					}
				// We get the element having id of display_info and put the response inside it
				$( '#display_info' ).html(response);
				}
		});
	}
}
</script>
<div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
        <div class="page-title">
            <h3>
                <?php echo $pageTitle ?> <small>
                    <?php echo $pageNote ?></small></h3>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('dashboard') ?>"> Dashboard </a></li>
            <li><a href="<?php echo site_url('prediksi') ?>">
                    <?php echo $pageTitle ?></a></li>
            <li class="active"> Form </li>
        </ul>
    </div>

    <div class="page-content-wrapper m-t">
        <div class="sbox">
            <div class="sbox-title">
                <h5>
                    <?php echo $pageTitle ?> <small>
                        <?php echo $pageNote ?></small></h5>
            </div>
            <div class="sbox-content">


                <form action="<?php echo site_url('prediksi/save/'.$row['ID']); ?>" class='form-horizontal'
                    parsley-validate='true' novalidate='true' method="post" enctype="multipart/form-data">


                    <div class="col-md-12">
                        <fieldset>
                            <legend> Prediksi</legend>
														<div class="form-group  " >
														<div class="col-md-1">
															</div>	
									<label for="Produk" class=" control-label col-md-2 text-left"> Produk <span class="asterix"> * </span></label>
									<div class="col-md-3">
									  <select onchange="loaddata()" name='idProduk' rows='5' id='idProduk' code='{$idProduk}' 
							class='select2 '  required  ></select> 
									  <i> <small></small></i>
									 </div> 
								  </div> 
                            <div class="form-group  ">
															<div class="col-md-2">
															</div>	
															<div class="col-md-8">
															<table id="example2" class="table  table-hover">
              <thead>
              <tr>
                <th width="30%">Kapasitas Tank </th>
                <th width="2%">:</th>
                <th>
							<input type='text' class='form-control' placeholder='' value='0' name='kapasitas' id='kapasitas' required /> 	
                </th>
                <th>
                </br>
                Liter  	
                </th>
              </tr>
              <tr>
                <th width="30%">BBM Terpakai</th>
                <th width="2%">:</th>
                <th>
							<input type='text' class='form-control' placeholder='' value='0' name='terpakai' id='terpakai' required /> 	
                </th>
                <th>
                </br>
                Liter  	
                </th>
              </tr>
              <tr>
                <th width="30%">Stok Akhir </th>
                <th width="2%">:</th>
                <th>
							<input type='text' class='form-control' placeholder='' value='0' name='stokAkhir' id='stokAkhir' required /> 	
                </th>
                <th>
                </br>
                Liter  	
                </th>
              </tr>
              <tr>
                <th>Rata Rata Penjualan PerShift</th>
                <th width="2%">:</th>
                <th>
							<input type='text' class='form-control' placeholder='' value='0' name='rata' id='rata' required /> 	
								
                </th>
                <th>
                </br>
                Liter/Hari 	
                </th>
              </tr>
              <tr>
                <th>Rata Rata Penjualan Per Hari Dalam 1 Minggu Terakhir</th>
                <th width="2%">:</th>
                <th>
							<input type='text' class='form-control' placeholder='' value='0' name='rataMinggu' id='rataMinggu' required /> 	
								
                </th>
                <th>
                </br>
                Liter/Hari 	
                </th>
              </tr>
              <tr>
                <th>Perkiraan Stok  Habis dalam</th>
                <th width="2%">:</th>
                <th>
                <input type='text' class='form-control' placeholder='' value='0' name='prediksi' id='prediksi' required /> 	
                </th>
                <th>
                </br>
                Hari 	
                </th>
              </tr>
              <tr>
                <th>Prediksi Pembelian BBM</th>
                <th width="2%">:</th>
                <th>
                <input type='text' class='form-control' placeholder='' value='0' name='prediksiPembelian' id='prediksiPembelian' required /> 	
                </th>
                <th>
                </br>
                Liter 	
                </th>
              </tr>
              </thead>
            </table>
															</div>
                            </div>
                        </fieldset>
                    </div>



                    <div style="clear:both"></div>

                   

                </form>

            </div>
        </div>

    </div>
</div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $("#idProduk").jCombo("<?php echo site_url('prediksi/comboselect?filter=t_produk:idProduk:idProduk') ?>",
            { selected_value: '<?php echo $row["idProduk"] ?>' });

    });
</script>