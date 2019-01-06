<div class="page-content row">
	<div class="page-header">
	  <div class="page-title">
		<h3> Dashboard <small> Summary info site </small></h3>
	  </div>

		  <ul class="breadcrumb">
			<li><a href="<?php echo site_url();?>">Home</a></li>
			<li class="active">Dashboard</li>
		  </ul>
		  
	</div>
	<div class="page-content-wrapper m-t">  
	  
	<?php if($this->session->userdata('gid') ==1) : ?>
<section >

		<div class="row m-l-none m-r-none m-t  white-bg shortcut " >
			<!-- <div class="col-sm-6 col-md-3 b-r  p-sm ">
				<span class="pull-left m-r-sm text-navy"><i class="fa fa-plus-circle"></i></span> 
				<a href="<?php echo site_url('sximo/module/create');?>" class="clear">
					<span class="h3 block m-t-xs"><strong><?php echo $this->lang->line('core.m_modules'); ?> </strong>
					</span> <small class="text-muted text-uc"> Manage Existing Modules or Create new one </small>
				</a>
			</div> -->
			<!-- <div class="col-sm-6 col-md-3 b-r  p-sm">
				<span class="pull-left m-r-sm text-info">	<i class="fa fa-cogs"></i></span>
				<a href="<?php echo site_url('sximo/config');?>" class="clear">
					<span class="h3 block m-t-xs"><strong><?php echo $this->lang->line('core.m_setting'); ?> </strong>
					</span> <small class="text-muted text-uc">  Setting Up your application login option , sitename , email etc. </small> 
				</a>
			</div>
			<div class="col-sm-6 col-md-3 b-r  p-sm">
				<span class="pull-left m-r-sm text-warning">	<i class="fa fa-sitemap"></i></span>
				<a href="<?php echo site_url('sximo/menu');?>" class="clear">
				<span class="h3 block m-t-xs"><strong><?php echo $this->lang->line('core.m_sitemenu'); ?> </strong></span>
				<small class="text-muted text-uc">Manage Menu for your application frontend or backend   </small> </a>
			</div>
			<div class="col-sm-6 col-md-3 b-r  p-sm">
				<span class="pull-left m-r-sm ">	<i class="fa fa-users"></i></span>
				<a href="<?php echo site_url('users');?>" class="clear">
				<span class="h3 block m-t-xs"><strong><?php echo $this->lang->line('core.m_usersgroups'); ?> </strong>
				</span> <small class="text-muted text-uc">Manage groups and users and grant what module and menu are accesible  </small> </a>
			</div> -->
		</div> </section>	

	
	<div class="row m-t">
		<div class="col-md-12">
			<div class="sbox">
				<div class="sbox-title"> <h3> Grafik <small> Stok BBM </small> </h3> </div>
				<div class="sbox-content">
					<div class="row">
						<div class="col-md-11">
							<canvas id="canvas" width="350" height="200" ></canvas>
						</div>
						
					</div>
				
						
				</div>
			</div>
		</div>
		
		
	
	</div>
	<canvas id="mycanvas"></canvas>

	<?php endif;?>
	
	</div>
</div>	
<?php if($this->session->userdata('gid') ==1) : ?>

<!-- <script src="<?php echo base_url();?>sximo/js/plugins/chartjs/jquery.min.js"></script> -->
<!-- <script src="<?php echo base_url();?>sximo/js/plugins/chartjs/Chart.min.js"></script> -->
<!-- <script src="<?php echo base_url();?>sximo/js/plugins/chartjs/linegraph.js"></script> -->
<script src="<?php echo base_url();?>sximo/js/plugins/chartjs/Chart.min.js"></script>

<script>

$(document).ready(function(){
  $.ajax({
    url : "<?php echo site_url('dashboard/find/'); ?>",
	type : "POST",
	data: {
	idProduk:'Dex'
	},
    success : function(response){

		let resPromise = new Promise((resolve, reject) => {
			resolve(response);
		});

		resPromise.then((res) => {
			var data=JSON.parse(res);
			var sortLabel=data.label;
			let namaLabel=[];
			const monthNames = ["January", "February", "March", "April", "May", "June",
								"July", "August", "September", "October", "November", "December"];
			for (let i = 0; i < sortLabel.length; i++) {
				let labeling= new Date(sortLabel[i]).getDate()+' '+monthNames[new Date(sortLabel[i]).getMonth()]+' '+ new Date(sortLabel[i]).getFullYear();
				if (labeling == 'undefined NaN') {
					labeling='';
				}
				namaLabel[i]= labeling;
			}

			var lineChartData = {
				labels : namaLabel,
				datasets : data.data
			}

			return lineChartData;

		}).then((data) => {
			window.onload = function(){
				var ctx = document.getElementById("canvas").getContext("2d");
				var myNewChart = new Chart(ctx , {
					type: "line",
					data: data, 
				});
			}
		});
		


		
    
    },
    error : function(data) {

    }
  });
});
		
	
</script>	
<?php endif;?>
