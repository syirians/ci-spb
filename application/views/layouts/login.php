<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title> SIM SPB</title>
<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="<?php echo base_url();?>sximo/js/plugins/bootstrap/css/bootstrap.css" type="text/css"  />	
<link rel="stylesheet" href="<?php echo base_url();?>sximo/css/sximo-dark-blue.css" type="text/css"  />
<link rel="stylesheet" href="<?php echo base_url();?>sximo/css/icon.css" type="text/css"  />

<script src="<?php echo base_url();?>sximo/js/plugins/jquery.min.js"></script>
<script src="<?php echo base_url();?>sximo/js/plugins/parsley.js"></script>
<script src="<?php echo base_url();?>sximo/js/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
 
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->	

		
	
  	</head>
<body class="gray-bg">
    <div class="middle-box  ">
        <div>

           <?php echo $content ;?>
        </div>
    </div>


<script type="text/javascript">
 
 
 <?php if($this->session->flashdata('success')){ ?>
     toastr.success("<?php echo $this->session->flashdata('success'); ?>");
 <?php }else if($this->session->flashdata('error')){  ?>
     toastr.error("<?php echo $this->session->flashdata('error'); ?>");
 <?php }else if($this->session->flashdata('warning')){  ?>
     toastr.warning("<?php echo $this->session->flashdata('warning'); ?>");
 <?php }else if($this->session->flashdata('info')){  ?>
     toastr.info("<?php echo $this->session->flashdata('info'); ?>");
 <?php } ?>
  
  
 </script>
</body> 
</html>
