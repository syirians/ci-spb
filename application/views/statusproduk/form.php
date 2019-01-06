<script>

function loaddata()
{
	var idProduk=document.getElementById( "idProduk" ).value;
	console.log(idProduk);
	if(idProduk){
		$.ajax({
			type: 'post',
			url: '<?php echo site_url('statusproduk/find/'); ?>',
			data: {
			idProduk:idProduk
			},
				success: function (response) {
					document.getElementById("jumlahPesan").disabled = false;
					document.getElementById("prediksi").disabled = false;
					document.getElementById("stokAkhir").disabled = false;
					document.getElementById("kapasitasTank").disabled = false;

					document.getElementById("jumlahPesan").value=0+' Liter';
					document.getElementById("prediksi").value=0+' Hari';
					document.getElementById("stokAkhir").value=0+' Liter';
					document.getElementById("kapasitasTank").value=0+' Liter';
					document.getElementById('status').innerHTML = 'Kosong';
					var data=JSON.parse(response);
					if (data.t_dexlitetrx.length>0) {
						var prediksiHabis=data.t_dexliteMAX[0].stokAkhir/data.t_dexlitetrx[0].bbmMasuk;
						var stokTerakhir=data.t_dexliteMAX[0].stokAkhir;
						var jumlahPesan=data.t_dexlitetrx[0].bbmMasuk;
						var leadTime=1;
						var kapasitasTank=data.t_dexlite[0].kapasitasTank;
						document.getElementById("jumlahPesan").value=jumlahPesan+' Liter';
						document.getElementById("prediksi").value=prediksiHabis+' Hari';
						document.getElementById("stokAkhir").value=stokTerakhir+' Liter';
						document.getElementById("kapasitasTank").value=kapasitasTank+' Liter';
						var status1=kapasitasTank/1;
						var status2=kapasitasTank/2;
						var status3=kapasitasTank/3;
						if (stokTerakhir> status2 && stokTerakhir<status1) {
								document.getElementById('status').innerHTML = 'AMAN';
						} else if(stokTerakhir> status3 && stokTerakhir<status2){
								document.getElementById('status').innerHTML = 'SEDANG';
						} if (stokTerakhir< status3) {
								document.getElementById('status').innerHTML = 'KURANG';
						} else if (stokTerakhir> status1) {
							document.getElementById('status').innerHTML = '';
						} 
						document.getElementById("jumlahPesan").disabled = false;
						document.getElementById("prediksi").disabled = false;
						document.getElementById("stokAkhir").disabled = false;
						document.getElementById("kapasitasTank").disabled = false;
		
					}else{
						alert("Stok Produk Kosong!");
						document.getElementById("jumlahPesan").disabled = false;
						document.getElementById("prediksi").disabled = false;
						document.getElementById("stokAkhir").disabled = false;
						document.getElementById("kapasitasTank").disabled = false;

						document.getElementById("jumlahPesan").value=0+' Liter';
						document.getElementById("prediksi").value=0+' Hari';
						document.getElementById("stokAkhir").value=0+' Liter';
						document.getElementById("kapasitasTank").value=0+' Liter';
						document.getElementById('status').innerHTML = '';

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
            <li><a href="<?php echo site_url('statusproduk') ?>">
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
                <form action="<?php echo site_url('statusproduk/save/'.$row['ID']); ?>" class='form-horizontal'
                    parsley-validate='true' novalidate='true' method="post" enctype="multipart/form-data">
                    <div class="col-md-12">
                        <fieldset>
                            <legend> StatusProduk</legend>
                            <div class="form-group  ">
                                <div class="col-md-1">
                                </div>
                                <label for="Produk" class=" control-label col-md-2 text-left"> Produk <span class="asterix">
                                        * </span></label>
                                <div class="col-md-3">
                                    <select onchange="loaddata()" name='idProduk' rows='5' id='idProduk' code='{$idProduk}'
                                        class='select2 ' required></select>
                                    <i> <small></small></i>
                                </div>
                            </div>
                            <div class="form-group  ">
                                <div class="col-md-2">
                                </div>
                                <div class="col-md-8">
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th colspan="3" style="text-align: center;">Data Detail Stok Produk</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td width="20%">Prediksi Habis</td>
                                                <td width="1%">:</td>
                                                <td>
																								<input type='text' class='form-control' placeholder='' value='0' name='prediksi' id='prediksi' required />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td>:</td>
                                                <td>
                                                  
										<p style="font-size:25px;color:red;" id="status"></p>
										
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Stok Terakhir</td>
                                                <td>:</td>
                                                <td>
																								<input type='text' class='form-control' placeholder='' value='0' name='stokAkhir' id='stokAkhir' required />
																								</td>
																						</tr>
                                            <tr>
                                                <td>Jumlah Pesan</td>
                                                <td>:</td>
                                                <td>
																								<input type='text' class='form-control' placeholder='' value='0' name='jumlahPesan' id='jumlahPesan' required />
        																				</td>
                                            </tr>
                                            <tr>
                                                <td>Lead Time</td>
                                                <td>:</td>
                                                <td>
                                                    <?php echo $leadtime=1; ?> Hari</td>
                                            </tr>
                                            <tr>
                                                <td>Kapasitas Tangki</td>
                                                <td>:</td>
                                                <td>
																								<input type='text' class='form-control' placeholder='' value='0' name='kapasitasTank' id='kapasitasTank' required />
																								</td>
																						</tr>
                                        </tbody>
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

        $("#idProduk").jCombo("<?php echo site_url('statusproduk/comboselect?filter=t_produk:idProduk:idProduk') ?>",
            { selected_value: '<?php echo $row["idProduk"] ?>' });

    });
</script>