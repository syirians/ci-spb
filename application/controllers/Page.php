<?php error_reporting(0); if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends SB_controller {

	protected $_key 	= 'id';
	protected $_class	= 'page';
	protected $layout = "layouts/main";
	
	function __construct()
	{
		parent::__construct();	
		$this->layout = 'layouts/'.CNF_THEME.'/index';
				
	}
	
	
	public function index( $page = null)
	{
		if(CNF_FRONT =='false' && $this->uri->segment(1) =='' ) :
			redirect('dashboard',301);
		endif; 
    
		if($page != null) :
			$row = $this->db->query("SELECT * FROM tb_pages WHERE alias ='$page' and status='enable' ")->row();

			if(count($row) >=1)
			{

				$this->data['pageTitle'] = $row->title;
				$this->data['pageNote'] = $row->note;		
				$this->data['breadcrumb'] = 'active';					
				
				if($row->access !='')
				{
					$access = json_decode($row->access,true)	;	
				} else {
					$access = array();
				}	

				// If guest not allowed 
				if($row->allow_guest !=1)
				{	
					$group_id = $this->session->userdata('gid');				
					$isValid =  (isset($access[$group_id]) && $access[$group_id] == 1 ? 1 : 0 );	
					if($isValid ==0)
					{
						redirect('',301);				
					}
				}				
				if($row->template =='backend')
				{
					 $this->layout = "layouts/main";
				}
				
				$filename = "application/views/pages/".$row->filename.".php";
				if(file_exists($filename))
				{
					$page = $row->filename;
				} else {
					redirect('',301);						
				}
				
			} else {
				redirect('',301);			
			}
			
			
		else :
			$this->data['pageTitle'] = 'Home';
			$this->data['pageNote'] = 'Welcome To Our Site';
			$this->data['breadcrumb'] = 'inactive';			
			$page = 'home';
		endif;	
			
		
		$this->data['content'] = $this->load->view('pages/'.$page,$this->data, true );		
    	$this->load->view($this->layout, $this->data );
		
	}

	function  submitcontact()
	{
	
		$rules = array(
			array('field'   => 'name','label'   => ' Please Fill Name','rules'   => 'required'),
			array('field'   => 'email','label'   => 'email ','rules'   => 'required|email'),
			array('field'   => 'message','label'   => 'message','rules'   => 'required'),
		);	


		$this->form_validation->set_rules( $rules );
		if( $this->form_validation->run() )
		{
			
			$data = array(
				'name'=>$this->input->post('name',true),
				'email'=>$this->input->post('email',true),
				'subject'=> 'New Form Submission',
				'notes'=>$this->input->post('message',true)
			); 
			$message = $this->load->view('emails/contact', $data,true); 
			
			
			$to 		= 	CNF_EMAIL;
			$subject 	= 'New Form Submission';
			$headers  	= 'MIME-Version: 1.0' . "\r\n";
			$headers 	.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers 	.= 'From: '.$this->input->post('name',true).' <'.$this->input->post('sender',true).'>' . "\r\n";
				//mail($to, $subject, $message, $headers);			
			$message = "Thank You , Your message has been sent !";
			$this->session->set_flashdata('message',SiteHelpers::alert('success',$message));
			redirect('contact-us',301);
			
				
		} else {
			$message = "The following errors occurred";
			$this->session->set_flashdata(array(
					'message'=>SiteHelpers::alert('error',$message),
					'errors'	=> validation_errors('<li>', '</li>')
			));
			redirect('contact-us',301);	
		}		
	}

	public function lang($lang)
	{

		$this->session->set_userdata('lang',$lang);	
		 redirect($_SERVER['HTTP_REFERER']);  	
	}	


	public function skin($skin = 'sximo-light-blue')
	{

		$this->session->set_userdata('themes',$skin);	
		 redirect($_SERVER['HTTP_REFERER']);  	
	}	


}

/* End of file welcome.php */
/* Location: ./application/controllers/page.php */ 