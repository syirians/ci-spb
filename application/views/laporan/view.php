<div class="page-content row">
  <!-- Page header -->
  <div class="page-header">
    <div class="page-title">
      <h3> <?php echo $pageTitle ?> <small><?php echo $pageNote ?></small></h3>
    </div>
    <ul class="breadcrumb">
      <li><a href="<?php echo site_url('dashboard') ?>">Dashboard</a></li>
      <li><a href="<?php echo site_url('laporan') ?>"><?php echo $pageTitle ?></a></li>
      <li class="active"> Detail </li>
    </ul>
  </div>  
  
   <div class="page-content-wrapper m-t">   
  
    <div class="sbox" >
      <div class="sbox-title" >
        <h5><?php echo $pageTitle ?> <small><?php echo $pageNote ?></small></h5>
      </div>
      <div class="sbox-content" >

      <div class="table-responsive">
          <table class="table table-striped table-bordered" >
            <tbody>  
          
					<tr>
						<td width='30%' class='label-view text-right'>ID</td>
						<td><?php echo $row['ID'] ;?> </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>IdProduk</td>
						<td><?php echo $row['idProduk'] ;?> </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>StokAwal</td>
						<td><?php echo $row['stokAwal'] ;?> </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>BbmMasuk</td>
						<td><?php echo $row['bbmMasuk'] ;?> </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Sisa</td>
						<td><?php echo $row['sisa'] ;?> </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>StokAkhir</td>
						<td><?php echo $row['stokAkhir'] ;?> </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Shift</td>
						<td><?php echo $row['shift'] ;?> </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>JenisTransaksi</td>
						<td><?php echo $row['jenisTransaksi'] ;?> </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Tanggal</td>
						<td><?php echo $row['tanggal'] ;?> </td>
						
					</tr>
				
            </tbody>  
          </table>    
        </div>
      </div>
    </div>
  </div>
  
</div>
    