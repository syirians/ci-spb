<div class="page-content row">
    <!-- Page header -->
<div class="page-header">
  <div class="page-title">
  <h3> <?php echo $pageTitle ?> <small><?php echo $pageNote ?></small></h3>
  </div>
  <ul class="breadcrumb">
    <li><a href="<?php echo site_url('dashboard') ?>"> Dashboard </a></li>
    <li><a href="<?php echo site_url('pengguna') ?>"><?php echo $pageTitle ?></a></li>
    <li class="active"> Form </li>
  </ul>      
</div>
 
   <div class="page-content-wrapper m-t">     
    <div class="sbox" >
    <div class="sbox-title" >
      <h5><?php echo $pageTitle ?> <small><?php echo $pageNote ?></small></h5>
    </div>
    <div class="sbox-content" >

      
     <form action="<?php echo site_url('pengguna/save/'.$row['id']); ?>" class='form-horizontal'  parsley-validate='true' novalidate='true' method="post" enctype="multipart/form-data" > 


<div class="col-md-12">
						<fieldset><legend> pengguna</legend>
									
								  <div class="form-group hidethis " style="display:none;">
									<label for="Id" class=" control-label col-md-4 text-left"> Id </label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['id'];?>' name='id'   /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Level Hak Akses" class=" control-label col-md-4 text-left"> Level Hak Akses <span class="asterix"> * </span></label>
									<div class="col-md-8">
									  <select name='group_id' rows='5' id='group_id' code='{$group_id}' 
							class='select2 '  required  ></select> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Username" class=" control-label col-md-4 text-left"> Username <span class="asterix"> * </span></label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['username'];?>' name='username'  required /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Password" class=" control-label col-md-4 text-left"> Password </label>
									<div class="col-md-8">
									  <input type='password' class='form-control' placeholder='' value='<?php echo $row['password'];?>' name='password'   /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Email" class=" control-label col-md-4 text-left"> Email <span class="asterix"> * </span></label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['email'];?>' name='email'  required parsley-type='email'  /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Nama Awal Pengguna" class=" control-label col-md-4 text-left"> Nama Awal Pengguna <span class="asterix"> * </span></label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['first_name'];?>' name='first_name'  required /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Nama Akhir Pengguna" class=" control-label col-md-4 text-left"> Nama Akhir Pengguna </label>
									<div class="col-md-8">
									  <input type='text' class='form-control' placeholder='' value='<?php echo $row['last_name'];?>' name='last_name'   /> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Foto Profile" class=" control-label col-md-4 text-left"> Foto Profile </label>
									<div class="col-md-8">
									  <input  type='file' name='avatar' id='avatar' <?php if($row['avatar'] =='') echo 'class="required"' ;?> style='width:150px !important;'  />
					<?php echo SiteHelpers::showUploadedFile($row['avatar'],'/uploads/users/') ;?>
				 <br />
									  <i> <small></small></i>
									 </div> 
								  </div> 					
								  <div class="form-group  " >
									<label for="Active" class=" control-label col-md-4 text-left"> Active <span class="asterix"> * </span></label>
									<div class="col-md-8">
									  
					<label class='radio radio-inline'>
					<input type='radio' name='active' value ='0' requred <?php if($row['active'] == '0') echo 'checked="checked"';?> > Inactive </label>
					<label class='radio radio-inline'>
					<input type='radio' name='active' value ='1' requred <?php if($row['active'] == '1') echo 'checked="checked"';?> > Aktive </label> <br />
									  <i> <small></small></i>
									 </div> 
								  </div> </fieldset>
			</div>
			
			
    
      <div style="clear:both"></div>  
        
     <div class="toolbar-line text-center">    
      <input type="submit" name="apply" class="btn btn-info btn-sm" value="<?php echo $this->lang->line('core.btn_apply'); ?>" />
      <input type="submit" name="submit" class="btn btn-primary btn-sm" value="<?php echo $this->lang->line('core.btn_submit'); ?>" />
      <a href="<?php echo site_url('pengguna');?>" class="btn btn-sm btn-warning"><?php echo $this->lang->line('core.btn_cancel'); ?> </a>
     </div>
            
    </form>
    
    </div>
    </div>

  </div>  
</div>  
</div>
       
<script type="text/javascript">
$(document).ready(function() { 

		$("#group_id").jCombo("<?php echo site_url('pengguna/comboselect?filter=tb_groups:group_id:name') ?>",
		{  selected_value : '<?php echo $row["group_id"] ?>' });
		    
});
</script>     