<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends CI_Controller {

    function __construct()
    {
        parent::__construct();
		$this->load->model('ServiceModel');
    }			
	public function index()
	{	
		$schoolID = $this->session->userdata('schoolData')["schoolID"];
		if(!empty($schoolID)){
			$data = $this->ServiceModel->schoolDashboardDetails($schoolID);
			$this->load->view('school/index',$data);
		}else{
			$this->session->sess_destroy();
			redirect('school/');
		}
	
	}
	
	/*public function schoolstudent($schoolID)
	{
		$resultArray=$this->ServiceModel->GetSchool($schoolID);
		$data["schoolID"] =  $resultArray->schoolID;
		$data["schoolName"] =  $resultArray->schoolName;
		
		//$data["ProductZonePrice"] = $this->ServiceModel->ProductZonePrice($productID);
		//$data["ProductWarehouse"] = $this->ServiceModel->ListWarehouse();
		$this->load->view('schoolstudent',$data);		
    }*/
	
	public function student()
	{
		$this->load->view('school/student');
	}	
    public function liststudent()
	{
		$postArray = $this->input->post();
		$resultArray=$this->ServiceModel->ListStudent($postArray["schoolID"]);
		echo json_encode(array("Status" => "true","Message" => "Data Found","resultArray"=>$resultArray));
	}	
    public function getstudent()
	{
		$postArray = $this->input->post();
		if($postArray["studentID"] > 0){
			$resultArray=$this->ServiceModel->GetStudent($postArray["studentID"]);
			$Status='true';
			$Message = "Data Found";
		}else{
			$Status='false';
			$Message = "Something went wrong. Please try Again.";
		}echo json_encode(array("Status" => $Status,"Message" => $Message,"resultArray"=>$resultArray));
	}		
	public function poststudent()
	{
		$this->form_validation->set_rules('sfirstName', 'Name', 'required');
		if ($this->form_validation->run() == FALSE){
			$Status='false';
			$Message = validation_errors();
		}else{
			$postArray = $this->input->post();
			if($postArray["studentID"] > 0){
				$result  = $this->ServiceModel->UpdateStudent();
				if(!$result){
					$Status='false';
					$Message = "Something went wrong. Please try Again.";
				}else{
					$Status='true';
					$Message = "Record Update Successfull.";
				}
			}else{
				$result = $this->ServiceModel->SaveStudent();
				if(!$result){
					$Status='false';
					$Message = "Something went wrong. Please try Again.";
				}else{
					$Status='true';
					$Message = "Record Save Successfull.";
				}
			}						
		}echo json_encode(array("Status" => $Status,"Message" => $Message));
	}
	public function deletestudent()
	{
		$postArray = $this->input->post();
		if($postArray["studentID"] > 0){
			$result  = $this->ServiceModel->DeleteStudent();
			if(!$result){
				$Status='false';
				$Message = "Something went wrong. Please try Again.";
			}else{
				$Status='true';
				$Message = "Record Delete Successfull.";
			}
		}else{
			$Status='false';
			$Message = "Something went wrong. Please try Again.";
		}echo json_encode(array("Status" => $Status,"Message" => $Message));
	}	
	//finance
	public function schoolfinance($schoolID)
	{
		$resultArray=$this->ServiceModel->GetSchool($schoolID);
		$data["schoolID"] =  $resultArray->schoolID;
		$data["schoolName"] =  $resultArray->schoolName;
		
		//$data["ProductZonePrice"] = $this->ServiceModel->ProductZonePrice($productID);
		//$data["ProductWarehouse"] = $this->ServiceModel->ListWarehouse();
		$this->load->view('schoolfinance',$data);		
    }
    public function listfinance()
	{
		$resultArray=$this->ServiceModel->ListFinance();
		echo json_encode(array("Status" => "true","Message" => "Data Found","resultArray"=>$resultArray));
	}	
    public function getfinance()
	{
		$postArray = $this->input->post();
		if($postArray["schoolID"] > 0){
			$resultArray=$this->ServiceModel->GetFinance($postArray["schoolID"]);
			$Status='true';
			$Message = "Data Found";
		}else{
			$Status='false';
			$Message = "Something went wrong. Please try Again.";
		}echo json_encode(array("Status" => $Status,"Message" => $Message,"resultArray"=>$resultArray));
	}		
	public function postfinance()
	{
		$this->form_validation->set_rules('retaintion', 'Name', 'required');
		if ($this->form_validation->run() == FALSE){
			$Status='false';
			$Message = validation_errors();
		}else{
			$postArray = $this->input->post();
			if($postArray["financeID"] > 0){
				$result  = $this->ServiceModel->UpdateFinance();
				if(!$result){
					$Status='false';
					$Message = "Something went wrong. Please try Again.";
				}else{
					$Status='true';
					$Message = "Record Update Successfull.";
				}
			}else{
				$result = $this->ServiceModel->SaveFinance();
				if(!$result){
					$Status='false';
					$Message = "Something went wrong. Please try Again.";
				}else{
					$Status='true';
					$Message = "Record Save Successfull.";
				}
			}						
		}echo json_encode(array("Status" => $Status,"Message" => $Message));
	}
	public function deletefinance()
	{
		$postArray = $this->input->post();
		if($postArray["financeID"] > 0){
			$result  = $this->ServiceModel->DeleteFinance();
			if(!$result){
				$Status='false';
				$Message = "Something went wrong. Please try Again.";
			}else{
				$Status='true';
				$Message = "Record Delete Successfull.";
			}
		}else{
			$Status='false';
			$Message = "Something went wrong. Please try Again.";
		}echo json_encode(array("Status" => $Status,"Message" => $Message));
	}	
	public function logout(){
		$this->session->sess_destroy();
		redirect('school/');
    }
	
	public function uploadData(){
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$data = $this->ServiceModel->UploadFileonly($_FILES);
			if(is_array($data) && count($data) > 0){
				$this->load->view('school/uploadData', array('data'=>$data));						
			}else{
				$this->load->view('school/uploadData');
			}
		}else{
			
			$this->load->view('school/uploadData');		
		}			
	}

	public function listfiles()
	{
		$postArray = $this->input->post();
		if($postArray["schoolID"] > 0){
			$resultArray=$this->ServiceModel->getFileDetails($postArray["schoolID"]);
			echo json_encode(array("Status" => "true","Message" => "Data Found","resultArray" => $resultArray));
		}
	}
	public function importStudent()
	{		
			$result=$this->ServiceModel->saveApproveSchoolStatus();
			if($result){
				$status='true';
				$message = "File Approved Successfully.";
			}else{
				$status='false';
				$message = "Something went wrong. Please try Again.";
			}
			echo json_encode(array('status' => $status, 'message' => $message)); 	
			
			/* $fileName = "uploads/approved/".$postArray['fileName'];
			require_once APPPATH . "/third_party/PHPExcel.php";
			$file_type	= PHPExcel_IOFactory::identify($fileName);
			$objReader	= PHPExcel_IOFactory::createReader($file_type);
			$objPHPExcel = $objReader->load($fileName);
			$sheet_data	= $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
			$noofRecords = $this->ServiceModel->saveBulkData($sheet_data, $postArray['fileName']); //in service model
			if($noofRecords > 0){
				$status='true';
				$message = $noofRecords." Records Added Successfully.";
			}else{
				$status='false';
				$message = "Something went wrong. Please try Again.";
			}
			echo json_encode(array('status' => $status, 'message' => $message));  */
	}

	

	public function checkFileuploadmonthly()
	{
		$postArray = $this->input->post();
		if($postArray["schoolID"] > 0){
			$resultArray=$this->ServiceModel->checkFilemonthly($postArray["schoolID"]);
			if(!empty($resultArray)){
				$Status = 'true';
				$message = 'Data Found';
			}else{
				$Status = 'false';
				$message = 'Data Not Found';
			}
			echo json_encode(array("status" => $Status,"message" => $message,"resultArray" => $resultArray));
		}
	}

	//staff
	public function staff()
	{
		$StateArray=$this->ServiceModel->StateList(101);
		$data["State"] = getTableDD($StateArray,"stateID","stateName");	

		$CityArray=$this->ServiceModel->CityList(22);
		$data["City"] = getTableDD($CityArray,"cityID","cityName");	

		$this->load->view('school/staff', $data);
	}

	public function liststaff()
	{
		$postArray = $this->input->post();
		if($postArray["schoolID"] > 0){
			$resultArray=$this->ServiceModel->getstaffDetails($postArray["schoolID"]);
			echo json_encode(array("Status" => "true","Message" => "Data Found","resultArray" => $resultArray));
		}
	}
	public function poststaff()
	{
		$this->form_validation->set_rules('staffFirstName', 'Name', 'required');
		if ($this->form_validation->run() == FALSE){
			$Status='false';
			$Message = validation_errors();
		}else{
			$postArray = $this->input->post();
			if($postArray["staffID"] > 0){
				$result  = $this->ServiceModel->UpdateStaff();
				if(!$result){
					$Status='false';
					$Message = "Something went wrong. Please try Again.";
				}else{
					$Status='true';
					$Message = "Record Update Successfull.";
				}
			}else{
				$result = $this->ServiceModel->SaveStaff();
				if(!$result){
					$Status='false';
					$Message = "Something went wrong. Please try Again.";
				}else{
					$Status='true';
					$Message = "Record Save Successfull.";
				}
			}						
		}echo json_encode(array("Status" => $Status,"Message" => $Message));
	}
	public function deletestaff()
	{
		$postArray = $this->input->post();
		if($postArray["staffID"] > 0){
			$result  = $this->ServiceModel->DeleteStaff();
			if(!$result){
				$Status='false';
				$Message = "Something went wrong. Please try Again.";
			}else{
				$Status='true';
				$Message = "Record Delete Successfull.";
			}
		}else{
			$Status='false';
			$Message = "Something went wrong. Please try Again.";
		}echo json_encode(array("Status" => $Status,"Message" => $Message));
	}
	public function getstaff()
	{
		$postArray = $this->input->post();
		if($postArray["staffID"] > 0){
			$resultArray=$this->ServiceModel->GetStaff($postArray["staffID"]);
			$Status='true';
			$Message = "Data Found";
		}else{
			$Status='false';
			$Message = "Something went wrong. Please try Again.";
		}echo json_encode(array("Status" => $Status,"Message" => $Message,"resultArray"=>$resultArray));
	}		
	//designation
	public function designation()
	{
		$this->load->view('school/designation');
	}
	public function listdesignation()
	{
		$resultArray=$this->ServiceModel->ListDesignation();
		echo json_encode(array("Status" => "true","Message" => "Data Found","resultArray"=>$resultArray));
	}	
    public function getDesignation()
	{
		$postArray = $this->input->post();
		if($postArray["designationID"] > 0){
			$resultArray=$this->ServiceModel->GetDesignation($postArray["designationID"]);
			$Status='true';
			$Message = "Data Found";
		}else{
			$Status='false';
			$Message = "Something went wrong. Please try Again.";
		}echo json_encode(array("Status" => $Status,"Message" => $Message,"resultArray"=>$resultArray));
	}		
	public function postdesignation()
	{
		$this->form_validation->set_rules('designationName', 'Name', 'required');
		if ($this->form_validation->run() == FALSE){
			$Status='false';
			$Message = validation_errors();
		}else{
			$postArray = $this->input->post();
			if($postArray["designationID"] > 0){
				$result  = $this->ServiceModel->Updatedesignation();
				if(!$result){
					$Status='false';
					$Message = "Something went wrong. Please try Again.";
				}else{
					$Status='true';
					$Message = "Record Update Successfull.";
				}
			}else{
				$result = $this->ServiceModel->SaveDesignation();
				if(!$result){
					$Status='false';
					$Message = "Something went wrong. Please try Again.";
				}else{
					$Status='true';
					$Message = "Record Save Successfull.";
				}
			}						
		}echo json_encode(array("Status" => $Status,"Message" => $Message));
	}
	public function deleteDesignation()
	{
		$postArray = $this->input->post();
		if($postArray["designationID"] > 0){
			$result  = $this->ServiceModel->DeleteDesignation();
			if(!$result){
				$Status='false';
				$Message = "Something went wrong. Please try Again.";
			}else{
				$Status='true';
				$Message = "Record Delete Successfull.";
			}
		}else{
			$Status='false';
			$Message = "Something went wrong. Please try Again.";
		}echo json_encode(array("Status" => $Status,"Message" => $Message));
	}
	//Add reason on submission of student data approval. i.e school admin	
	public function submitapproval()
	{
		$postArray = $this->input->post();
		if(!empty($postArray)){
			$result  = $this->ServiceModel->postReason($postArray);
			if(!$result){
				$Status='false';
				$Message = "Something went wrong. Please try Again.";
			}else{
				$Status='true';
				$Message = "Record Added Successfully.";
			}
		}else{
			$Status='false';
			$Message = "Something went wrong. Please try Again.";
		}echo json_encode(array("status" => $Status,"message" => $Message));
	}
	//loan data
	public function loandata(){
		$this->load->view('school/schoolLoandata');	
	}

	public function listloandata()
	{
		$resultArray = $this->ServiceModel->getLoankycData();
		echo json_encode(array("Status" => "true","Message" => "Data Found","resultArray" => $resultArray));
	}
	
	public function loandetails($studentID){
		$resultArray = $this->ServiceModel->getLoankycDatadetails($studentID);
		$this->load->view('school/schoolloandetails',array('data'=>$resultArray));
	}

	//student Report
	public function studentReport(){
		$data = $this->ServiceModel->getStudentReportData();
		$this->load->view('school/studentReport', array('data'=>$data));
	}
	//staff Report
	public function staffReport(){
		$data = $this->ServiceModel->getStaffReportData();
		$this->load->view('school/staffReport', array('data'=>$data));
	}
	//Fees Report
	public function feesReport(){
		$data = $this->ServiceModel->getFeesReportData();
		$this->load->view('school/feesReport',array('data'=>$data));
	}
	public function loanreport()
	{	
		$data = $this->ServiceModel->getLoanReport($loanType=0);
		$this->load->view('school/loanreport',array('data'=>$data));
	}
	public function closedloanreport()
	{	
		$data = $this->ServiceModel->getLoanReport($loanType=2);
		$this->load->view('school/closedloanreport',array('data'=>$data));
	}
	public function staffloanreport()
	{
		$this->load->view('school/staffloanreport');
	}
	//margin report
	public function feeadjustmentreport()
	{
		$data = $this->ServiceModel->getfeeadjustmentreport();
		$this->load->view('school/feeadjustmentreport',array('data'=>$data));
	}
	public function paymentstransferred()
	{
		$paymentData = $this->ServiceModel->getSchoolPaymentData();
		$this->load->view('school/paymentstransferred', array('paymentData'=>$paymentData));
	}
	public function paymentsubreport($paymentID)
	{
		$paymentData = $this->ServiceModel->getPaymentsubReportData($paymentID);
		$this->load->view('school/paymentsubreport', array('paymentData'=>$paymentData));
	}
	
}