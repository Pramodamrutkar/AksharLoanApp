<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Kolkata");
        $this->load->helper('string');
        $this->load->library('session');
        $this->load->helper('cookie');
        $this->load->helper('url'); 
		$this->load->library('form_validation');
        $this->load->model('ServiceModel');	
		if($this->session->userdata('userData')){
            redirect('/service');
        }
    }
	public function index()
	{
		$this->load->view('login');
	}
	public function postIndex()
	{
		$this->form_validation->set_rules('eMail', 'E-Mail', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		if ($this->form_validation->run() == FALSE){
			$Status='false';
			$Message = validation_errors();
		}else{
			$postArray = $this->input->post();
			$userData  = $this->ServiceModel->validate($postArray["eMail"],$postArray["password"]);
			if(!$userData){
				$Status='false';
				$Message = "Invalid username or password.";
			}else{
				$this->session->set_userdata('userData',$userData);
				$Status='true';
				$Message = "Login successful. Redirecting.";
			}			
		}echo json_encode(array("Status" => $Status,"Message" => $Message));
	}
}