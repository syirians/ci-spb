<div class="page-content row">
  <!-- Page header -->
  <div class="page-header">
    <div class="page-title">
      <h3> <?php echo $pageTitle ?> <small><?php echo $pageNote ?></small></h3>
    </div>
    <ul class="breadcrumb">
      <li><a href="<?php echo site_url('dashboard') ?>">Dashboard</a></li>
      <li><a href="<?php echo site_url('produk') ?>"><?php echo $pageTitle ?></a></li>
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
						<td width='30%' class='label-view text-right'>IdProduk</td>
						<td><?php echo $row['idProduk'] ;?> </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>NamaProduk</td>
						<td><?php echo $row['namaProduk'] ;?> </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>HargaBeli</td>
						<td><?php echo $row['hargaBeli'] ;?> </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>HargaJual</td>
						<td><?php echo $row['hargaJual'] ;?> </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>KapasitasTank</td>
						<td><?php echo $row['kapasitasTank'] ;?> </td>
						
					</tr>
				
            </tbody>  
          </table>    
        </div>
      </div>
    </div>
  </div>
  
</div>
    