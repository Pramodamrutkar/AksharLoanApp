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
		$this->load->view('index');
	}
	public function user()
	{
		$UserTypeArray=$this->ServiceModel->ListUserType();
		$data["UserType"] = getTableDD($UserTypeArray,"typeID","typeName");
		
		$AreaArray=$this->ServiceModel->ListArea();
		$data["Area"] = getTableDD($AreaArray,"areaID","areaName");	
		
		$this->load->view('user',$data);
	}
    public function listuser()
	{
		$resultArray=$this->ServiceModel->ListUser();
		echo json_encode(array("Status" => "true","Message" => "Data Found","resultArray"=>$resultArray));
	}	
    public function getuser()
	{
		$postArray = $this->input->post();
		if($postArray["userID"] > 0){
			$resultArray=$this->ServiceModel->GetUser($postArray["userID"]);
			$Status='true';
			$Message = "Data Found";
		}else{
			$Status='false';
			$Message = "Something went wrong. Please try Again.";
		}echo json_encode(array("Status" => $Status,"Message" => $Message,"resultArray"=>$resultArray));
	}		
	public function postuser()
	{
		$this->form_validation->set_rules('fullName', 'Name', 'required');
		if ($this->form_validation->run() == FALSE){
			$Status='false';
			$Message = validation_errors();
		}else{
			$postArray = $this->input->post();
			if($postArray["userID"] > 0){
				$result  = $this->ServiceModel->UpdateUser();
				if(!$result){
					$Status='false';
					$Message = "Something went wrong. Please try Again.";
				}else{
					$Status='true';
					$Message = "Record Update Successfull.";
				}
			}else{
				$result = $this->ServiceModel->SaveUser();
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
	public function deleteuser()
	{
		$postArray = $this->input->post();
		if($postArray["userID"] > 0){
			$result  = $this->ServiceModel->DeleteUser();
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
	public function area()
	{
		$this->load->view('area');
	}
    public function listarea()
	{
		$resultArray=$this->ServiceModel->ListArea();
		echo json_encode(array("Status" => "true","Message" => "Data Found","resultArray"=>$resultArray));
	}	
    public function getarea()
	{
		$postArray = $this->input->post();
		if($postArray["areaID"] > 0){
			$resultArray=$this->ServiceModel->GetArea($postArray["areaID"]);
			$Status='true';
			$Message = "Data Found";
		}else{
			$Status='false';
			$Message = "Something went wrong. Please try Again.";
		}echo json_encode(array("Status" => $Status,"Message" => $Message,"resultArray"=>$resultArray));
	}		
	public function postarea()
	{
		$this->form_validation->set_rules('areaName', 'Name', 'required');
		if ($this->form_validation->run() == FALSE){
			$Status='false';
			$Message = validation_errors();
		}else{
			$postArray = $this->input->post();
			if($postArray["areaID"] > 0){
				$result  = $this->ServiceModel->UpdateArea();
				if(!$result){
					$Status='false';
					$Message = "Something went wrong. Please try Again.";
				}else{
					$Status='true';
					$Message = "Record Update Successfull.";
				}
			}else{
				$result = $this->ServiceModel->SaveArea();
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
	public function deletearea()
	{
		$postArray = $this->input->post();
		if($postArray["areaID"] > 0){
			$result  = $this->ServiceModel->DeleteArea();
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
	public function school()
	{
		$SchoolTypeArray=$this->ServiceModel->ListSchoolType();
		$data["SchoolType"] = getTableDD($SchoolTypeArray,"typeID","typeName");	

		$SchoolBoardArray=$this->ServiceModel->ListSchoolBoard();
		$data["SchoolBoard"] = getTableDD($SchoolBoardArray,"boardID","boardName");	
		

		$StateArray=$this->ServiceModel->StateList(101);
		$data["State"] = getTableDD($StateArray,"stateID","stateName");	

		$CityArray=$this->ServiceModel->CityList(22);
		$data["City"] = getTableDD($CityArray,"cityID","cityName");	
		
		$lenderArray=$this->ServiceModel->lenderList();
		$data["lender"] = getTableDD($lenderArray,"lenderID","lenderName");	
		

			
		$this->load->view('school',$data);
	}
    public function listschool()
	{
		$resultArray=$this->ServiceModel->ListSchool();
		echo json_encode(array("Status" => "true","Message" => "Data Found","resultArray"=>$resultArray));
	}	
    public function getschool()
	{
		$postArray = $this->input->post();
		if($postArray["schoolID"] > 0){
			$resultArray=$this->ServiceModel->GetSchool($postArray["schoolID"]);
			$Status='true';
			$Message = "Data Found";
		}else{
			$Status='false';
			$Message = "Something went wrong. Please try Again.";
		}echo json_encode(array("Status" => $Status,"Message" => $Message,"resultArray"=>$resultArray));
	}		
	public function postschool()
	{
		$this->form_validation->set_rules('schoolName', 'Name', 'required');
		if ($this->form_validation->run() == FALSE){
			$Status='false';
			$Message = validation_errors();
		}else{
			$postArray = $this->input->post();
			if($postArray["schoolID"] > 0){
				$result  = $this->ServiceModel->UpdateSchool();
				if(!$result){
					$Status='false';
					$Message = "Something went wrong. Please try Again.";
				}else{
					$Status='true';
					$Message = "Record Update Successfull.";
				}
			}else{
				$result = $this->ServiceModel->SaveSchool();
				if(!$result){
					$Status='false';
					$Message = "Something went wrong. Please try Again.";
				}else{
					if($result['email'] == 1){
						$Status='false';
						$Message = "Email Already Exist.";
					}else if($result['mobileno'] == 1){
						$Status='false';
						$Message = "Mobile No Already Exist.";
					}else if($result['schoolname'] == 1){
						$Status='false';
						$Message = "School Name Already Exist.";
					}else{
						$Status='true';
						$Message = "Record Save Successfull.";
					}
				}
			}						
		}echo json_encode(array("Status" => $Status,"Message" => $Message));
	}
	public function deleteschool()
	{
		$postArray = $this->input->post();
		if($postArray["schoolID"] > 0){
			$result  = $this->ServiceModel->DeleteSchool();
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
	
	public function schoolstudent($schoolID)
	{
		$resultArray=$this->ServiceModel->GetSchool($schoolID);
		$data["schoolID"] =  $resultArray->schoolID;
		$data["schoolName"] =  $resultArray->schoolName;
		
		//$data["ProductZonePrice"] = $this->ServiceModel->ProductZonePrice($productID);
		//$data["ProductWarehouse"] = $this->ServiceModel->ListWarehouse();
		$this->load->view('schoolstudent',$data);		
    }

	public function schoolpayment($schoolID)
	{
		$resultArray=$this->ServiceModel->GetSchool($schoolID);
		$data["schoolID"] =  $resultArray->schoolID;
		$data["schoolName"] =  $resultArray->schoolName;
		$this->load->view('schoolpayment',$data);		
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
		$this->form_validation->set_rules('slastName', 'Name', 'required');
		if($this->form_validation->run() == FALSE){
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
	
	public function listschoolpayment()
	{
		$postArray = $this->input->post();
		$resultArray=$this->ServiceModel->ListSchoolPayment($postArray["schoolID"]);
		echo json_encode(array("Status" => "true","Message" => "Data Found","resultArray"=>$resultArray));
	}
	public function postschoolpayment()
	{
		$this->form_validation->set_rules('paymentType', 'Payment Type', 'required');
		$this->form_validation->set_rules('paymentDate', 'Payment  Date', 'required');
		if($this->form_validation->run() == FALSE){
			$Status='false';
			$Message = validation_errors();
		}else{
			$postArray = $this->input->post();
			if($postArray["paymentID"] > 0){
				$result  = $this->ServiceModel->UpdateSchoolpayment();
				if(!$result){
					$Status='false';
					$Message = "Something went wrong. Please try Again.";
				}else{
					$Status='true';
					$Message = "Record Update Successfull.";
				}
			}else{
				$result = $this->ServiceModel->SaveSchoolpayment();
				if(!$result){
					$Status = 'false';
					$Message = "Something went wrong. Please try Again.";
				}else{
					$Status = 'true';
					$Message = "Record Save Successfull.";
				}
			}						
		}echo json_encode(array("Status" => $Status,"Message" => $Message));
	}

	public function deleteschoolPayment()
	{
		$postArray = $this->input->post();
		if($postArray["paymentID"] > 0){
			$result  = $this->ServiceModel->DeleteSchoolPayment();
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

	public function getschoolpayment()
	{
		$postArray = $this->input->post();
		if($postArray["paymentID"] > 0){
			$resultArray=$this->ServiceModel->GetSchoolpayment($postArray["paymentID"]);
			$Status='true';
			$Message = "Data Found";
		}else{
			$Status='false';
			$Message = "Something went wrong. Please try Again.";
		}echo json_encode(array("Status" => $Status,"Message" => $Message,"resultArray"=>$resultArray));
	}		
	//lender
	public function lender()
	{
		$this->load->view('lender');	
	}			
	public function listlender()
	{
		$resultArray=$this->ServiceModel->ListLenderDetails();
		echo json_encode(array("Status" => "true","Message" => "Data Found","resultArray"=>$resultArray));
	}	

	public function postlender()
	{
		$this->form_validation->set_rules('lenderName', 'Lender Name', 'required');
		if($this->form_validation->run() == FALSE){
			$Status='false';
			$Message = validation_errors();
		}else{
			$postArray = $this->input->post();
			if($postArray["lenderID"] > 0){
				$result  = $this->ServiceModel->updateLender();
				if(!$result){
					$Status='false';
					$Message = "Something went wrong. Please try Again.";
				}else{
					$Status='true';
					$Message = "Record Update Successfull.";
				}
			}else{
				$result = $this->ServiceModel->saveLender();
				if(!$result){
					$Status = 'false';
					$Message = "Something went wrong. Please try Again.";
				}else{
					$Status = 'true';
					$Message = "Record Save Successfull.";
				}
			}						
		}echo json_encode(array("Status" => $Status,"Message" => $Message));
	}	

	public function deletelender()
	{
		$postArray = $this->input->post();
		if($postArray["lenderID"] > 0){
			$result  = $this->ServiceModel->deleteLender();
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

	public function getlender()
	{
		$postArray = $this->input->post();
		if($postArray["lenderID"] > 0){
			$resultArray=$this->ServiceModel->GetlenderDetail($postArray["lenderID"]);
			$Status='true';
			$Message = "Data Found";
		}else{
			$Status='false';
			$Message = "Something went wrong. Please try Again.";
		}echo json_encode(array("Status" => $Status,"Message" => $Message,"resultArray"=>$resultArray));
	}	


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
		}
		if(!empty($resultArray)){
			echo json_encode(array("Status" => $Status,"Message" => $Message,"resultArray"=>$resultArray));
		}else{
			echo json_encode(array("Status" => $Status,"Message" => $Message));
		}
		
	}		
	public function getpersonalfinance()
	{
		$postArray = $this->input->post();
		if($postArray["schoolID"] > 0){
			$resultArray=$this->ServiceModel->GetpersonalFinance($postArray["schoolID"]);
			$Status='true';
			$Message = "Data Found";
		}else{
			$Status='false';
			$Message = "Something went wrong. Please try Again.";
		}
		if(!empty($resultArray)){
			echo json_encode(array("Status" => $Status,"Message" => $Message,"resultArray"=>$resultArray));
		}else{
			echo json_encode(array("Status" => $Status,"Message" => $Message));
		}
	}
	
	public function getstafffinance()
	{
		$postArray = $this->input->post();
		if($postArray["schoolID"] > 0){
			$resultArray=$this->ServiceModel->GetstaffFinance($postArray["schoolID"]);
			$Status='true';
			$Message = "Data Found";
		}else{
			$Status='false';
			$Message = "Something went wrong. Please try Again.";
		}
		if(!empty($resultArray)){
			echo json_encode(array("Status" => $Status,"Message" => $Message,"resultArray"=>$resultArray));
		}else{
			echo json_encode(array("Status" => $Status,"Message" => $Message));
		}
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

	public function postpersonalfinance()
	{
		$this->form_validation->set_rules('maxLoan', 'Max Loan', 'required');
		if ($this->form_validation->run() == FALSE){
			$Status='false';
			$Message = validation_errors();
		}else{
			$postArray = $this->input->post();
			if($postArray["personalfinanceID"] > 0){
				$result  = $this->ServiceModel->UpdatepersonalFinance();
				if(!$result){
					$Status='false';
					$Message = "Something went wrong. Please try Again.";
				}else{
					$Status='true';
					$Message = "Record Update Successfull.";
				}
			}else{
				$result = $this->ServiceModel->SavepersonalFinance();
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

	public function poststafffinance()
	{
		$this->form_validation->set_rules('maxLoan', 'Max Loan', 'required');
		if ($this->form_validation->run() == FALSE){
			$Status='false';
			$Message = validation_errors();
		}else{
			$postArray = $this->input->post();
			if($postArray["stafffinanceID"] > 0){
				$result  = $this->ServiceModel->UpdatestaffFinance();
				if(!$result){
					$Status='false';
					$Message = "Something went wrong. Please try Again.";
				}else{
					$Status='true';
					$Message = "Record Update Successfull.";
				}
			}else{
				$result = $this->ServiceModel->SavestaffFinance();
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

	//approve student data by Admin
	public function approveData(){
			$this->load->view('approveData');		
	}
	public function checkApprovalSchool(){
		$postArray = $this->input->post();
		$fileName = $postArray['fileName'];
		$result  = $this->ServiceModel->checkfileApprovedStatus($fileName);
		echo json_encode(array("Status" => "true","Message" => "Data Found","response" => $result));
	}
	public function listfiles()
	{
		$resultArray=$this->ServiceModel->getFileDetails('',1);
		echo json_encode(array("Status" => "true","Message" => "Data Found","resultArray" => $resultArray));
		
	}
	public function pastapproved()
	{
		$this->load->view('pastapproved');		
	}
	public function pastapproveddata(){
		$resultArray=$this->ServiceModel->getFileDetailspastapproved();
		echo json_encode(array("Status" => "true","Message" => "Data Found","resultArray" => $resultArray));
	}


	public function importStudent()
	{		
			$postArray = $this->input->post();
			$fileName = "uploads/approved/".$postArray['fileName'];
			require_once APPPATH . "/third_party/PHPExcel.php";
			$file_type	= PHPExcel_IOFactory::identify($fileName);
			$objReader	= PHPExcel_IOFactory::createReader($file_type);
			$objPHPExcel = $objReader->load($fileName);
			$sheet_data	= $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
			$noofRecords = $this->ServiceModel->saveBulkData($sheet_data, $postArray['fileName'],1, $postArray['approvedReasonbyAdmin']); //in service model
			if($noofRecords > 0){
				$status='true';
				$message = $noofRecords." Records Added Successfully.";
			}else{
				$status='false';
				$message = "Something went wrong. Please try Again.";
			}
			echo json_encode(array('status' => $status, 'message' => $message)); 
	}

	public function loanaddata(){
		$this->load->view('adminLoandata');
	}

	public function listloanadmindata()
	{
		$resultArray = $this->ServiceModel->getLoanadminkycData();
		echo json_encode(array("Status" => "true","Message" => "Data Found","resultArray" => $resultArray));	
	}

	public function updateloanapprovedStatus(){
		$resultArray = $this->ServiceModel->updateApprovedStatus();
		if($resultArray){
			$Status = "true";
			$Message = "Loan Status Updated";
		}else{
			$Status = "false";
			$Message = "Something went wrong. Please try Again.";
		}
		echo json_encode(array("Status" => $Status,"Message" => $Message));
	}

	//Admin reports
	public function loanallschool()
	{
		$data = $this->ServiceModel->loanAllschool();
		$this->load->view('loanallschool', array('data'=>$data));
	}
	public function loanoneschool($schoolID)
	{
		$this->load->view('loanoneschool',array('data'=>$schoolID));
	}

	public function loanoneschooldata()
	{
		$resultArray = $this->ServiceModel->loansoneschool();
		if($resultArray){
			$Status = "true";
			$Message = "Data Found";
		}else{
			$Status = "false";
			$Message = "No Data Found.";
		}
		echo json_encode(array("Status" => $Status,"Message" => $Message,'resultArray' => $resultArray));
	}

	public function getloandetailsPopup(){
		$resultArray = $this->ServiceModel->loandetailsPopup();
		if($resultArray){
			$Status = "true";
			$Message = "Data Found";
		}else{
			$Status = "false";
			$Message = "Something went wrong. Please try Again.";
		}
		echo json_encode(array("Status" => $Status,"Message" => $Message,'resultArray' => $resultArray));
	}

	public function updateapproveStatus(){
		$data = $this->ServiceModel->updateApprovedstatusfromReport();
		if($data){
			$Status = "true";
			$Message = "Status Updated";
		}else{
			$Status = "false";
			$Message = "Something went wrong. Please try Again.";
		}
		echo json_encode(array("Status" => $Status,"Message" => $Message));
	}

	public function updateKycloan(){
		$resultArray = $this->ServiceModel->updateKycloanstatus();
		if($resultArray){
			$Status = "true";
			$Message = "Status Updated";
		}else{
			$Status = "false";
			$Message = "Something went wrong. Please try Again.";
		}
		echo json_encode(array("Status" => $Status,"Message" => $Message));
	}

	public function disbursementreport($schoolID)
	{   
		$this->load->view('disbursementreport',array('schoolID'=> $schoolID));
	}

	public function disbursementdata()
	{	
		$resultArray = $this->ServiceModel->disbursementReport();
		if($resultArray){
			$Status = "true";
			$Message = "Disbursed Successfully";
		}else{
			$Status = "false";
			$Message = "No Data Found.";
		}
		echo json_encode(array("Status" => $Status, "Message" => $Message, "resultArray" => $resultArray));
	}

	
	public function updatedisbursedStatus(){
		$data = $this->ServiceModel->updatedisbursedstatusfromReport();
		if($data){
			$Status = "true";
			$Message = "Status Updated";
		}else{
			$Status = "false";
			$Message = "Something went wrong. Please try Again.";
		}
		echo json_encode(array("Status" => $Status,"Message" => $Message));
	}

	public function schooldisbursement()
	{

		$data = $this->ServiceModel->disbursementSchoolwise();
		$this->load->view('schooldisbursement',array('data'=>$data));
	}

	public function newremittancereport()
	{
		$this->load->view('newremittancereport');
	}

	public function newremittancedata()
	{
		$resultArray = $this->ServiceModel->remittancereport();
		if($resultArray){
			$Status = "true";
			$Message = "Data found";
		}else{
			$Status = "false";
			$Message = "No Data Found.";
		}
		echo json_encode(array("Status" => $Status, "Message" => $Message, "resultArray" => $resultArray));
	}	
	
	public function updatenewremittanceStatus(){
		$data = $this->ServiceModel->updateNewremittancestatusfromReport();
		if($data){
			$Status = "true";
			$Message = "Status Updated";
		}else{
			$Status = "false";
			$Message = "Something went wrong. Please try Again.";
		}
		echo json_encode(array("Status" => $Status,"Message" => $Message));
	}

	public function remittancereport()
	{
		$this->load->view('remittancereport');
	}

	public function remittancedata()
	{
		$resultArray = $this->ServiceModel->remittanceSchoolwise();
		if($resultArray){
			$Status = "true";
			$Message = "Data Found";
		}else{
			$Status = "false";
			$Message = "No Data Found.";
		}
		echo json_encode(array("Status" => $Status, "Message" => $Message, "resultArray" => $resultArray));
	}
	public function updateremittanceStatus(){
		$data = $this->ServiceModel->updateremittancestatusfromReport();
		if($data){
			$Status = "true";
			$Message = "Status Updated";
		}else{
			$Status = "false";
			$Message = "Something went wrong. Please try Again.";
		}
		echo json_encode(array("Status" => $Status,"Message" => $Message));
	}
	
	//Mou upload
	public function mouupload($schoolID)
	{	
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$data['schoolID'] = $schoolID;
			$data = $this->ServiceModel->uploadMOUfile($_FILES,$schoolID);
			if($data > 0){
				$status='true';
				$message = "File Uploaded Successfully.";
			}else{
				$status='false';
				$message = "Something went wrong. Please try Again.";
			}
			echo "<meta http-equiv='refresh' content='0'>"; //

			//$this->load->view('mouupload');
		}else{
			$data["schoolID"] =  $schoolID;
			$this->load->view('mouupload',$data);
		}
	}

	public function listmoufiles()
	{
		$resultArray = $this->ServiceModel->getListofMou();
		echo json_encode(array("Status" => "true","Message" => "Data Found","resultArray"=>$resultArray));
	}

	public function schoolview($schoolID)
	{	
		$data = array();
		$data["schoolID"] =  $schoolID;
		$data = $this->ServiceModel->getSchoolView($schoolID);
		
		$this->load->view('schoolview',$data);
	}
	
	public function lenderReport()
	{
		$data = $this->ServiceModel->getLenderDetails();
		$this->load->view('lenderReport',array('data'=>$data));
	}

	public function emireport()
	{
		$this->load->view('emiReport');
	}
	public function emiReportData(){
		$resultArray = $this->ServiceModel->getListofemiReport();
		echo json_encode(array("Status" => "true","Message" => "Data Found","resultArray"=>$resultArray));
	}
	public function updateemiPaidStatus(){
		$data = $this->ServiceModel->updateemiispaidStatus();
		if($data){
			$Status = "true";
			$Message = "Status Updated";
		}else{
			$Status = "false";
			$Message = "Something went wrong. Please try Again.";
		}
		echo json_encode(array("Status" => $Status,"Message" => $Message));
	}

	public function emilenderreport()
	{
		$this->load->view('emiLenderReport');
	}
	public function emilenderReportData(){
		$resultArray = $this->ServiceModel->emilenderlistData();
		echo json_encode(array("Status" => "true","Message" => "Data Found","resultArray"=>$resultArray));
	}
	
	public function deletefilefromAdmin()
	{
		$postArray = $this->input->post();
		if($postArray["fileID"] > 0){
			$result  = $this->ServiceModel->Deletefile();
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
		redirect('/');
    }

}