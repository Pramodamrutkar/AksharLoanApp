<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ApiModel extends CI_Model{
    function __construct()
    {
        parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
    }

    function LoginMatchReturnOtp(){
		$ResultArray = array();
		$this->form_validation->set_rules('mobileNo', 'mobileNo', 'required');
		if($this->form_validation->run() == FALSE){
			$Status='false';
			$Message = validation_errors();
		}else{
			$postArray = $this->input->post();
			$mobileNO = $this->security->xss_clean($postArray["mobileNo"]);
			$sql= "SELECT * FROM `studentmaster` WHERE mobileNo=?";
			$query=$this->db->query($sql,array($mobileNO));
			
			if($query->num_rows() > 0) {
				$UserData = $query->row_array();
				$mobileNO = $UserData['mobileNo'];
				if(!empty($mobileNO)){
					/* $senderID = "AKSHARID";
					$message = "Your OTP for Login is ";
					$this->sendSMS($senderID, $UserData['mobileNo'], $message); */
					$otp = substr($mobileNO,-4);
					$postData['otp'] = $otp;
					$postData['updated'] = date('Y-m-d H:i:s');
					$this->db->update("studentmaster",$postData,array("mobileNo"=>$mobileNO));
					$this->db->trans_complete();
					if($this->db->trans_status() === FALSE){
						$Status='false';
						$Message='Error.';
					}else{
						$ResultArray['otp'] = $otp;
						$Status='true';
						$Message='OTP sent successfully.';
					}
				}else{
					$Status='false';
					$Message='Invalid Mobile Number.';
				}
			}else{
				$Status='false';
				$Message = 'Your Mobile Number is not registered with us';
			}
		}
		if(!empty($ResultArray)){
			return json_encode(array("status" => $Status,"message" => $Message,"data"=>$ResultArray));
		}else{
			return json_encode(array("status" => $Status,"message" => $Message));	
		}
		
	}

	public function sendSMS($senderID, $recipient_no, $message){
		// Request parameters array
		$otp = mt_rand(10000, 99999);
		$message = $message.''.$otp;
 
	   $fields = array(
		  "sender_id" => $senderID,
		  "message" => $message,
		  "language" => "english",
		  "route" => "p",
		  "numbers" => $recipient_no,
		);

	  $curl = curl_init();

	  curl_setopt_array($curl, array(
		CURLOPT_URL => "https://www.fast2sms.com/dev/bulk",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => json_encode($fields),
		CURLOPT_HTTPHEADER => array(
		  "authorization: TGgXsvlFt97NkatPWxN7hUPPDOu4Bp0KkeLahNPFfSQEnsQ7Du5fMSZehAbi",
		  "accept: */*",
		  "cache-control: no-cache",
		  "content-type: application/json"
		),
	  ));
	  $response = curl_exec($curl);
	  $err = curl_error($curl);
	  curl_close($curl);
	  return $response;
 	}

	public function verifyOtpdetails()
	{
		$ResultArray = array();
		$this->form_validation->set_rules('mobileNo', 'mobileNo', 'required');
		$this->form_validation->set_rules('otp', 'otp', 'required');
		if($this->form_validation->run() == FALSE){
			$Status='false';
			$Message = validation_errors();
		}else{
			$postArray = $this->input->post();
			$mobileNO = $this->security->xss_clean($postArray["mobileNo"]);
			$otp = $this->security->xss_clean($postArray["otp"]);
			$sql= "SELECT concat(sfirstName,' ',slastName) as userName,accessToken,otp,mobileNo FROM `studentmaster` WHERE mobileNo=? AND otp=?";
			$query=$this->db->query($sql,array($mobileNO,$otp));
			$ResultArray = array();
			if($query->num_rows() > 0) {
				$UserData = $query->row_array();
				$userDataotp = $UserData['otp'];
				if($userDataotp === $otp){
					$ResultArray = $UserData;
					$Status='true';
					$Message='Login successful. Redirecting.';
				}else{
					$Status='false';
					$Message='Invalid Mobile Number or OTP.';
				}
			}else{
				$Status='false';
				$Message='Invalid Mobile Number or OTP.';
			}
		}
		if(!empty($ResultArray)){
			return json_encode(array("status" => $Status,"message" => $Message,"data"=>$ResultArray));
		}else{
			return json_encode(array("status" => $Status,"message" => $Message));	
		}
	} 

	public function getStudentData(){
		$postArray = $this->input->post();
		$ResultArray = array();
		$UserData  = $this->VerifyStudentAccessToken($postArray["accessToken"]);
		if($UserData == FALSE){
			$Status='false';
			$Message = "Invalid Access Token";
		}else{
			$accessToken = $this->security->xss_clean($postArray["accessToken"]);
			$sql = "SELECT s.*,sc.schoolName FROM studentmaster s LEFT JOIN schoolmaster sc on s.schoolID = sc.schoolID WHERE s.`accessToken`=?";
			$query = $this->db->query($sql,array($accessToken));
			$ResultArray = $query->result_array();
			if($query->num_rows() > 0) {
				$Status ='true';
				$Message = 'Data Found';
			}else{
				$Status ='false';
				$Message = 'No Data Found';
			} 
		}
		if(!empty($ResultArray)){
			return json_encode(array("status" => $Status,"message" => $Message,"data"=>$ResultArray));
		}else{
			return json_encode(array("status" => $Status,"message" => $Message));	
		}
	}

	public function getStudentDatadetails(){
		$postArray = $this->input->post();
		$ResultArray = array();
		$schoolFinance = array();
		$UserData  = $this->VerifyStudentAccessToken($postArray["accessToken"]);
		if($UserData == FALSE){
			$Status='false';
			$Message = "Invalid Access Token";
		}else{
			$this->form_validation->set_rules('studentID', 'studentID', 'required');
			if($this->form_validation->run() == FALSE){
				$Status='false';
				$Message = validation_errors();
			}else{
				$studentID = $this->security->xss_clean($postArray["studentID"]);
				$sql = "SELECT * FROM studentmaster WHERE `studentID`=? and accessToken=?";
				$query = $this->db->query($sql,array($studentID,$postArray["accessToken"]));
				$ResultArray = $query->row_array();
				$loanAmount = $ResultArray['currentPayableFees'];
				$schoolID = $ResultArray['schoolID'];
				//get school details for loan calculation
				$sql1 = "SELECT * FROM schoolfinance WHERE `schoolID`=?";
				$query1 = $this->db->query($sql1,array($schoolID));
				$schoolFinance = $query1->row_array();
				$interestRate = $schoolFinance['interestRate'];
				$emiLevels = $schoolFinance['emiLevel'];
				$maxLoan = $schoolFinance['maxLoan'];
				$emiLevels = explode(",",$emiLevels);
				if($query->num_rows() > 0) {
					if($loanAmount <= $maxLoan){
						for($i = 0; $i < count($emiLevels); $i++){
							$ResultArray['loandetails'][$emiLevels[$i]." Months"] = $this->emiCalculator($loanAmount, $interestRate, $emiLevels[$i]);
						}
						$Status ='true';
						$Message="Data Found";
					}else{
						$Status ='true';
						$Message="You can't get loan more than ".$maxLoan;
					}
				}else{
					$Status ='false';
					$Message = 'No Data Found';
				}
			}
			
		}
		if(!empty($ResultArray)){
			return json_encode(array("status" => $Status,"message" => $Message,"data"=>$ResultArray));
		}else{
			return json_encode(array("status" => $Status,"message" => $Message));	
		}
	}


	//post data into kyc table
	public function saveKycdetails(){
		$ResultArray = array();
		$postArray = $this->input->post();
		$UserData  = $this->VerifyStudentAccessToken($postArray["accessToken"]);
		if($UserData == FALSE){
			$Status='false';
			$Message = "Invalid Access Token";
		}else{
			$this->form_validation->set_rules('studentID', 'student ID', 'required');
			$this->form_validation->set_rules('panId', 'pan ID', 'required');
			if($this->form_validation->run() == FALSE){
				$Status='false';
				$Message = validation_errors();
			}else{
				$postArray = $this->input->post();
				$postData = array(
					"studentID" => $postArray["studentID"],
					"panId"=>$postArray["panId"],
					"address"=>$postArray["address"],
					"location"=>$postArray["location"],
					"city"=>$postArray["city"],
					"state"=>$postArray["state"],
					"pincode"=>$postArray["pincode"],
					"dob"=>$postArray["dob"],
					"employmentType"=>$postArray["employmentType"],
					"occupation"=>$postArray["occupation"],
					"monthlyIncome"=>$postArray["monthlyIncome"],
					"alternateMobile"=>$postArray["alternateMobile"],
					"emailID"=> $postArray["emailID"],
					"created"=>date('Y-m-d H:i:s')
				);
				$this->db->insert("studentkycdetails",$postData);
				$rowID = $this->db->insert_id();
				if($this->db->affected_rows() > 0){
					$postData["rowID"] = $rowID;
					$ResultArray = $postData;
					$Status='true';
					$Message='Data Added Successfully.';
				}else{
					$Status='false';
					$Message='Something went wrong. Please try Again.';
				}
			}
		}
		if(!empty($ResultArray)){
			return json_encode(array("status" => $Status,"message" => $Message,"data"=>$ResultArray));
		}else{
			return json_encode(array("status" => $Status,"message" => $Message));	
		}
	}

	public function uploadKycdetails(){
		$ResultArray = array();
		$postArray = $this->input->post();
		$UserData  = $this->VerifyStudentAccessToken($postArray["accessToken"]);
		if($UserData == FALSE){
			$Status='false';
			$Message = "Invalid Access Token";
		}else{
			$postArray = $this->input->post();
			$this->form_validation->set_rules('kycID', 'id', 'required');
			
			if($this->form_validation->run() == FALSE){
				$Status='false';
				$Message = validation_errors();
			}else{
				$postData = array(
					"pancard"=> $this->uploadFile('pancard',"pancard"),			
					"aadharFront"=> $this->uploadFile('aadhar',"aadharFront"),
					"aadharBack"=> $this->uploadFile('aadhar',"aadharBack"),
					"selfi"=> $this->uploadFile('selfi',"selfi"),
					"updated"=>date('Y-m-d H:i:s')
				);
				$this->db->update("studentkycdetails",$postData,array("id"=>$postArray["kycID"]));
				$this->db->trans_complete();
				if($this->db->trans_status() === FALSE){
					$Status='false';
					$Message='Something went wrong. Please try Again.';
				}else{
					$Status='true';
					$Message='Data Added Successfully.';
				}
			}
		}
		if(!empty($ResultArray)){
			return json_encode(array("status" => $Status,"message" => $Message,"data"=>$ResultArray));
		}else{
			return json_encode(array("status" => $Status,"message" => $Message));	
		}
	}	

	
	public function uploadFile($dir,$file){
		$oldFile = '';
		if(isset($_REQUEST[$file."O"]) && trim($_REQUEST[$file."O"])!=""){
			$oldFile = $_REQUEST[$file."O"];
		}		
		
		if(isset($_FILES[$file]["name"]) && trim($_FILES[$file]["name"])!=""){
			$AbsPath = 'uploads';
			if(!is_dir($AbsPath.'/'.$dir)){
				mkdir($AbsPath.'/'.$dir, 0777, true);
			}
		
			$config['upload_path']          = $AbsPath.'/'.$dir;
			$config['allowed_types']        = 'pdf|jpg|png|jpeg|gif';
			$config['max_size']             = 10000;
			$config['max_width']            = 9024;
			$config['max_height']           = 7068;
			$new_name = time();			
			$config['file_name'] = $file.$new_name;
			$this->upload->initialize($config);
			$this->load->library('upload', $config);
			if(!$this->upload->do_upload($file)){
				return "";
			}else{
				$fileData = $this->upload->data();
				if($oldFile!="" && is_file(($config['upload_path'].'/'.$oldFile))){
					unlink($config['upload_path'].'/'.$oldFile);
				}return $fileData["file_name"];
			}
		}else{
			return $oldFile;
		}
	}

	public function VerifyStudentAccessToken($accessToken){
		$accessToken = $this->security->xss_clean($accessToken);
		$sql = "SELECT * FROM studentmaster WHERE `accessToken`=?";
		$query = $this->db->query($sql,array($accessToken));
		if($query->num_rows() > 0) {
			return true;
		}else{
			return false;
		} 
	}
	public function VerifyStaffAccessToken($accessToken){
		$accessToken = $this->security->xss_clean($accessToken);
		$sql = "SELECT * FROM staffmaster WHERE `accessToken`=?";
		$query = $this->db->query($sql,array($accessToken));
		if($query->num_rows() > 0) {
			return true;
		}else{
			return false;
		} 
	}
	

	public function StateList(){
		
			$Sql   = "SELECT stateID,StateName FROM statemaster WHERE countryID=101 ORDER BY stateName ASC";
			$Query = $this->db->query($Sql);
			$ResultArray = $Query->result_array();
			return json_encode(array("status" => 'true',"message" => 'State List',"data"=>$ResultArray));	
	}
	public function CityList(){
			$postArray = $this->input->post();
			if($postArray['stateId'] > 0){
				$StateId = $postArray['stateId'];
			}else{
				$StateId = 22;
			}
			$Sql = "SELECT cityID,cityName FROM `citymaster` WHERE stateID = ".$StateId." ORDER BY cityName ASC";
			$Query = $this->db->query($Sql);
			$ResultArray = $Query->result_array();
			return json_encode(array("status" => 'true',"message" => 'City List',"data"=>$ResultArray));	
	}

	public function fetchKycDetails(){
		$postArray = $this->input->post();
		$ResultArray = array();
		$this->form_validation->set_rules('studentID', 'studentID', 'required');
		$UserData  = $this->VerifyStudentAccessToken($postArray["accessToken"]);
		if($UserData == FALSE){
			$Status='false';
			$Message = "Invalid Access Token";
		}else{
			if($this->form_validation->run() == FALSE){
				$Status='false';
				$Message = validation_errors();
			}else{
				$studentID = $this->security->xss_clean($postArray["studentID"]);
				$sql = "SELECT * FROM studentkycdetails WHERE `studentID`=?";
				$query = $this->db->query($sql,array($studentID));
				$ResultArray = $query->result_array();
				if($query->num_rows() > 0) {
					$Status ='true';
					$Message = 'Data Found';
				}else{
					$Status ='false';
					$Message = 'No Data Found';
				} 
			}
		}
		if(!empty($ResultArray)){
			return json_encode(array("status" => $Status,"message" => $Message,"data"=>$ResultArray));
		}else{
			return json_encode(array("status" => $Status,"message" => $Message));	
		}
	}

	public function getloanWithEMI()
	{
		$postArray = $this->input->post();
		$schoolID = $this->security->xss_clean($postArray['schoolID']);
		$mobileNO = $this->security->xss_clean($postArray["mobileNo"]);
		$loanAmount = $this->security->xss_clean($postArray["loanAmount"]);
		$schoolFinance = array();
		$ResultArray = array();
		$UserData  = $this->VerifyStudentAccessToken($postArray["accessToken"]);
		if($UserData == FALSE){
			$status='false';
			$message = "Invalid Access Token";
		}else{
			$this->form_validation->set_rules('schoolID', 'schoolID', 'required');
			if($this->form_validation->run() == FALSE){
				$status='false';
				$message = validation_errors();
			}else{
				$sql = "SELECT * FROM schoolfinance WHERE `schoolID`=?";
				$query = $this->db->query($sql,array($schoolID));
				$schoolFinance = $query->row_array();
				$interestRate = $schoolFinance['interestRate'];
				$emiLevels = $schoolFinance['emiLevel'];
				$maxLoan = $schoolFinance['maxLoan'];
				$emiLevels = explode(",",$emiLevels);
				if(!empty($mobileNO)){
					$sql= "SELECT * FROM `studentmaster` WHERE mobileNo=?";
					$query1=$this->db->query($sql,array($mobileNO));
					$studentMasterData = $query1->result_array();
					if($loanAmount <= $maxLoan){
						for($i = 0; $i < count($emiLevels); $i++){
							$ResultArray[$emiLevels[$i]." Months"] = $this->emiCalculator($loanAmount, $interestRate, $emiLevels[$i]);
						}
						$status='true';
						$message="Data Found";
					}else{
						$status='false';
						$message="You can't get loan more than ".$maxLoan;			
					}	
				}
			}
		}
		if(!empty($ResultArray)){
			return json_encode(array("status" => $status,"message" => $message,"data"=>$ResultArray));
		}else{
			return json_encode(array("status" => $status,"message" => $message));	
		}
	}

	function emiCalculator($principalAmnt, $roi, $tenure) 
	{ 
		$emi = '';
		$rateOfInterest = $roi;
		// one month interest 
		$roi = $roi / (12 * 100);
		$emi = ($principalAmnt * $roi * pow(1 + $roi, $tenure)) /  
					(pow(1 + $roi, $tenure) - 1); 
		$totalInterestAmount = $emi * $tenure;
		$interestPayable = $totalInterestAmount - $principalAmnt;
		$monthlyInterest = $interestPayable/$tenure;
		$monthlyInterest = round($monthlyInterest);
		$totalInterestAmount = round($totalInterestAmount);
		$interestPayable = round($interestPayable);
		$emi = round($emi);
		$principalMonthlyAmount = $principalAmnt/$tenure;
		$principalMonthlyAmount = round($principalMonthlyAmount);
		return (array("EMI"=>$emi,"principalMonthlyAmount"=>$principalMonthlyAmount,"monthlyInterest"=>$monthlyInterest,"totalInterest"=>$interestPayable,"totalAmountwithInterest" =>$totalInterestAmount,"rateOfInterest"=>$rateOfInterest)); 
	} 

	public function loanAgreement(){
		$postArray = $this->input->post();
		$ResultArray = array();
		$studentData = array();
		$agreementData = "";
		$this->form_validation->set_rules('studentID', 'studentID', 'required');
		$UserData  = $this->VerifyStudentAccessToken($postArray["accessToken"]);
		if($UserData == FALSE){
			$Status='false';
			$Message = "Invalid Access Token";
		}else{
			if($this->form_validation->run() == FALSE){
				$Status='false';
				$Message = validation_errors();
			}else{
				$studentID = $this->security->xss_clean($postArray["studentID"]);
				$accessToken = $this->security->xss_clean($postArray["accessToken"]);
				$sql = "SELECT s.*,kyc.address, kyc.location,kyc.panId,kyc.city,kyc.state FROM studentmaster as s INNER JOIN studentkycdetails as kyc on s.studentID=kyc.studentID WHERE s.`studentID`=? and s.accessToken=?";
				$query = $this->db->query($sql,array($studentID,$postArray["accessToken"]));
				$studentData = $query->row_array();
				$agreementSql = "SELECT * FROM agreement";
				$agreementQuery = $this->db->query($agreementSql);
				$agreement = $agreementQuery->row_array();
				
				$sqlLoan = "SELECT * FROM loandetails WHERE `studentID`=?";
				$queryLoan = $this->db->query($sqlLoan,array($studentID));
				$loanData = $queryLoan->row_array();
				
				$agreementContents = $agreement['agreementText'];

				$agreementData = str_replace("[DateNumber]",date("jS"),$agreementContents);
				$agreementData = str_replace("[MonthName]",date("F"),$agreementData);
				$agreementData = str_replace("[YearNumber]",date("Y"),$agreementData);
				$agreementData = str_replace("[StudentName]",$studentData['sfirstName']." ".$studentData["slastName"],$agreementData);
				$agreementData = str_replace("[PanNumber]",$studentData['panId'],$agreementData);
				$agreementData = str_replace("[ParentName]",$studentData['pfirstName']." ".$studentData["plastName"],$agreementData);
				$agreementData = str_replace("[AddressLocationCityDistrict]",$studentData['address'].", ".$studentData['location'].", ".$studentData['city'].", ".$studentData['state'],$agreementData);
				$agreementData = str_replace("[LoanAmount]",$loanData['loanAmount'],$agreementData);
				$agreementData = str_replace("[EmiNumber]",$loanData['loantenure'],$agreementData);
				$agreementData = str_replace("[RateOfInterest]",$loanData['roi'],$agreementData);
				$agreementData = str_replace("[ParentNameBehalf]",$studentData['pfirstName']." ".$studentData["plastName"],$agreementData);
				$agreementData = str_replace("[TenureMonthly]",$loanData['loantenure'],$agreementData);
				$agreementData = str_replace("[EmiAmount]",$loanData['emiAmount'],$agreementData);
				$ResultArray = $agreementData;
				$Status='true';
				$Message = "Data Found";
			}
		}
		if(!empty($ResultArray)){
			return json_encode(array("status" => $Status,"message" => $Message,"data"=>$ResultArray));
		}else{
			return json_encode(array("status" => $Status,"message" => $Message));	
		}
	}
	
	public function saveLoanDetails()
	{
		$ResultArray = array();
		$postArray = $this->input->post();
		$UserData  = $this->VerifyStudentAccessToken($postArray["accessToken"]);
		$rowID = '';
		if($UserData == FALSE){
			$Status='false';
			$Message = "Invalid Access Token";
		}else{
			$this->form_validation->set_rules('studentID', 'student ID', 'required');
			$this->form_validation->set_rules('loanAmount', 'loanAmount', 'required');
			if($this->form_validation->run() == FALSE){
				$Status='false';
				$Message = validation_errors();
			}else{
				$postArray = $this->input->post();
				$postData = array(
					"studentID" => $postArray["studentID"],
					"loantenure" => $postArray["loantenure"],	
					"roi" => $postArray["rateOfInterest"],
					"loanAmount"=>$postArray["loanAmount"],
					"emiAmount"=>$postArray["emiAmount"],
					"monthlyInterest"=>$postArray["monthlyInterest"],
					"totalInterest"=>$postArray["totalInterest"],
					"loanStatus"=> 0,
					"isApproved"=> 0,
					"created"=>date('Y-m-d H:i:s')
				);
				$this->db->insert("loandetails",$postData);
				$rowID = $this->db->insert_id();
				if($this->db->affected_rows() > 0){
					$ResultArray["rowID"] = $rowID;
					$Status='true';
					$Message='Data Added Successfully.';
				}else{
					$Status='false';
					$Message='Something went wrong. Please try Again.';
				}
			}
		}
		if(!empty($ResultArray)){
			return json_encode(array("status" => $Status,"message" => $Message,"data"=>$ResultArray));
		}else{
			return json_encode(array("status" => $Status,"message" => $Message));	
		}
	}

	public function saveprocessingFeesStatus()
	{
		$postArray = $this->input->post();
		$ResultArray = array();
		$UserData  = $this->VerifyStudentAccessToken($postArray["accessToken"]);
		if($UserData == FALSE){
			$Status='false';
			$Message = "Invalid Access Token";
		}else{
			$this->form_validation->set_rules('loanID', 'loanID', 'required');
			if($this->form_validation->run() == FALSE){
				$Status='false';
				$Message = validation_errors();
			}else{
				$postArray = $this->input->post();
				$loanID = $postArray["loanID"];
				$postData = array(
					"paymentStatus" => $postArray["paymentStatus"],
					"transactionDetails"=> $postArray["transactionDetails"],
					"created"=>date('Y-m-d H:i:s')
				);
				$this->db->update("loandetails",$postData,array("loanID"=>$loanID));
				$this->db->trans_complete();
				if($this->db->trans_status() === FALSE){
					$Status='false';
					$Message='Error.';
				}else{
					$Status='true';
					$Message='Payment Details Updated.';
				}
			}		
		}
		if(!empty($ResultArray)){
			return json_encode(array("status" => $Status,"message" => $Message,"data"=>$ResultArray));
		}else{
			return json_encode(array("status" => $Status,"message" => $Message));	
		}
	}
	
	public function emiConfirmationDetail()
	{
		$postArray = $this->input->post();
		$ResultArray = array();
		$emiDate = '';
		$UserData  = $this->VerifyStudentAccessToken($postArray["accessToken"]);
		if($UserData == FALSE){
			$Status='false';
			$Message = "Invalid Access Token";
		}else{
			$this->form_validation->set_rules('studentID', 'Student ID', 'required');
			$this->form_validation->set_rules('loanID', 'Loan ID', 'required');
			if($this->form_validation->run() == FALSE){
				$Status='false';
				$Message = validation_errors();
			}else{
				$postArray = $this->input->post();
				
				$loanID = $postArray['loanID'];
				$studentID = $postArray['studentID'];
				
				$sqlld = 'SELECT ld.*,ld.created as loandate, sm.schoolID,sf.* FROM `loandetails` ld inner join studentmaster sm on sm.studentID=ld.studentID LEFT JOIN schoolfinance sf on sf.schoolID=sm.schoolID  WHERE ld.studentID=? and ld.loanID=?';
				$queryLd = $this->db->query($sqlld,array($studentID,$loanID));
				$QueryLoanData = $queryLd->row_array();
				$loanTenure = $QueryLoanData['loantenure'];
				$loanDate = $QueryLoanData['loandate'];
				$emiAmount = $QueryLoanData['emiAmount'];
				$day = date('d',strtotime($loanDate));
				$month = date('m',strtotime($loanDate));
				/* $sqlFinance = "SELECT * FROM schoolfinance WHERE schoolID=?";
				$queryFinance = $this->db->query($sqlFinance,array($QueryLoanData['schoolID']));
				$financeData = $queryFinance->row_array(); */
				
				if($day >= 01 && $day <= 15 ){
					$emiDate = 5;
				}else if($day >= 15 && $day <= 31 ){
					$emiDate = 20;
				}
				$date = date("Y-m-".$emiDate, strtotime($loanDate));
				$end_date = date("Y-m-d", strtotime("+".$loanTenure."months", strtotime($date)));
			
				while (strtotime($date) <= strtotime($end_date)) {
					$date = date ("Y-m-d", strtotime("+1 month", strtotime($date)));
					$ResultArray[] = $date; 
				}
				/* for ($i=0; $i < count($ResultArray) - 1; $i++) { 
					$dateformated = date("jS F",strtotime($ResultArray[$i]));  
					$ResultArray['emiDates'][] = array($dateformated => $emiAmount);
				} */
				$lengthoftheArray = count($ResultArray);
				$postData = array();
				for($j=0;$j < $lengthoftheArray - 1;$j++){
					$postData[] = array(
						"studentID" => $studentID,
						"loanID" => $loanID,
						"noofdays"=> $QueryLoanData['delaynoofDays'],
						"perdaycharges"=> $QueryLoanData['perdaycharge'],
						"emiDuedate" => $ResultArray[$j],
						"emiAmount" => $emiAmount,
						"created"=>date('Y-m-d H:i:s')
					);
				}
				$this->db->insert_batch("emischedule",$postData);
				if($this->db->affected_rows() > 0){
					$Status='true';
					$Message='Data Added Successfully.';
					$response = $this->getscheduledEmi($studentID);
				}else{
					$Status='false';
					$Message='Something went wrong. Please try Again.';
				} 
			}	
		}
		
		if(!empty($response)){
			return json_encode(array("status" => $Status,"message" => $Message,"data"=>$response));
		}else{
			return json_encode(array("status" => $Status,"message" => $Message));	
		}
	}
	
	function getscheduledEmi($studentID){
		$sqlemi = 'SELECT * FROM `emischedule` WHERE studentID=?';
		$queryemi = $this->db->query($sqlemi,array($studentID));
		return $queryemi->result_array();
		//$result = array();
		//foreach ($QueryEmiData as $key => $value) {
		//	$result[$value['emiID']] =$value;	
		//}
		//return $result;
	}

	public function saveemiStatusDetails()
	{
		$postArray = $this->input->post();
		$ResultArray = array();
		$UserData  = $this->VerifyStudentAccessToken($postArray["accessToken"]);
		if($UserData == FALSE){
			$Status='false';
			$Message = "Invalid Access Token";
		}else{
			$this->form_validation->set_rules('emiID', 'Emi ID', 'required');
			$this->form_validation->set_rules('emiStatus', 'Emi Status', 'required');
			if($this->form_validation->run() == FALSE){
				$Status='false';
				$Message = validation_errors();
			}else{
				$postArray = $this->input->post();
				$emiID = $postArray["emiID"];
				$postData = array(
					"paymentDetails" => json_encode($postArray["paymentDetails"]),
					"emiStatus" => $postArray['emiStatus'],
					"updated"=>date('Y-m-d H:i:s')
				);
				$this->db->update("emischedule",$postData,array("emiID"=>$emiID));
				$this->db->trans_complete();
				if($this->db->trans_status() === FALSE){
					$Status='false';
					$Message='Error.';
				}else{
					$Status='true';
					$Message='Payment Details Updated.';
				}
			}		
		}
		if(!empty($ResultArray)){
			return json_encode(array("status" => $Status,"message" => $Message,"data"=>$ResultArray));
		}else{
			return json_encode(array("status" => $Status,"message" => $Message));	
		}
	}

	public function getPendingEMIs()
	{
		$ResultArray = array();
		$postArray = $this->input->post();
		$UserData  = $this->VerifyStudentAccessToken($postArray["accessToken"]);
		if($UserData == FALSE){
			$Status='false';
			$Message = "Invalid Access Token";
		}else{
				$sqlemi1 = "SELECT * FROM `studentmaster` WHERE accessToken=?";
				$queryemi1 = $this->db->query($sqlemi1, array($postArray['accessToken']));
				$result = $queryemi1->result_array();		

				foreach($result as $k => $v){

					$sqlemi = "SELECT e.emiID, e.studentID, e.emiDuedate, e.emiAmount, CONCAT(s.sfirstName,' ',s.slastName) as studentName FROM `studentmaster` as s INNER JOIN emischedule as e on e.studentID= s.studentID WHERE e.emiStatus=0 AND e.studentID=? LIMIT 2";
					$queryemi = $this->db->query($sqlemi, $v['studentID']);
					$emiRes = $queryemi->result_array();		
					$v["emi"] = $emiRes;
					$ResultArray[] = $v;
				}

				if(!empty($ResultArray)){
					$Status='true';
					$Message="Data Found";
				}else{
					$Status='false';
					$Message="No Data Found";
				}
		}
		if(!empty($ResultArray)){
			return json_encode(array("status" => $Status,"message" => $Message,"data"=>$ResultArray));
		}else{
			return json_encode(array("status" => $Status,"message" => $Message));	
		}
	}




	//staff Login API start	
	public function staffLoginMatchReturnOtp()
	{
		$ResultArray = array();
		$this->form_validation->set_rules('mobileNo', 'mobileNo', 'required');
		if($this->form_validation->run() == FALSE){
			$Status='false';
			$Message = validation_errors();
		}else{
			$postArray = $this->input->post();
			$mobileNO = $this->security->xss_clean($postArray["mobileNo"]);
			$sql= "SELECT * FROM `staffmaster` WHERE mobileNo=?";
			$query=$this->db->query($sql,array($mobileNO));
			
			if($query->num_rows() > 0) {
				$UserData = $query->row_array();
				$mobileNO = $UserData['mobileNo'];
				if(!empty($mobileNO)){
					/* $senderID = "AKSHARID";
					$message = "Your OTP for Login is ";
					$this->sendSMS($senderID, $UserData['mobileNo'], $message); */
					$otp = substr($mobileNO,-4);
					$postData['otp'] = $otp;
					$postData['updated'] = date('Y-m-d H:i:s');
					$this->db->update("staffmaster",$postData,array("mobileNo"=>$mobileNO));
					$this->db->trans_complete();
					if($this->db->trans_status() === FALSE){
						$Status='false';
						$Message='Error.';
					}else{
						$ResultArray['otp'] = $otp;
						$Status='true';
						$Message='OTP sent successfully.';
					}
				}else{
					$Status='false';
					$Message='Invalid Mobile Number.';
				}
			}else{
				$Status='false';
				$Message = 'Your Mobile Number is not registered with us';
			}
		}if(!empty($ResultArray)){
			return json_encode(array("status" => $Status,"message" => $Message,"data"=>$ResultArray));
		}else{
			return json_encode(array("status" => $Status,"message" => $Message));	
		}
	}	

	public function staffverifyOtpdetails()
	{
		$ResultArray = array();
		$this->form_validation->set_rules('mobileNo', 'mobileNo', 'required');
		$this->form_validation->set_rules('otp', 'otp', 'required');
		if($this->form_validation->run() == FALSE){
			$Status='false';
			$Message = validation_errors();
		}else{
			$postArray = $this->input->post();
			$mobileNO = $this->security->xss_clean($postArray["mobileNo"]);
			$otp = $this->security->xss_clean($postArray["otp"]);
			$sql= "SELECT concat(staffFirstName,' ',staffLastName) as staffName,accessToken,otp,mobileNo FROM `staffmaster` WHERE mobileNo=? AND otp=?";
			$query=$this->db->query($sql,array($mobileNO,$otp));
			$ResultArray = array();
			if($query->num_rows() > 0) {
				$UserData = $query->row_array();
				$userDataotp = $UserData['otp'];
				if($userDataotp === $otp){
					$ResultArray = $UserData;
					$Status='true';
					$Message='Login successful. Redirecting.';
				}else{
					$Status='false';
					$Message='Invalid Mobile Number or OTP.';
				}
			}else{
				$Status='false';
				$Message='Invalid Mobile Number or OTP.';
			}
		}
		if(!empty($ResultArray)){
			return json_encode(array("status" => $Status,"message" => $Message,"data"=>$ResultArray));
		}else{
			return json_encode(array("status" => $Status,"message" => $Message));	
		}
	}

	public function savestaffKycDetails()
	{
		$ResultArray = array();
		$postArray = $this->input->post();
		$UserData  = $this->VerifyStaffAccessToken($postArray["accessToken"]);
		if($UserData == FALSE){
			$Status='false';
			$Message = "Invalid Access Token";
		}else{
			$this->form_validation->set_rules('staffID', 'staff ID', 'required');
			$this->form_validation->set_rules('panNumber', 'Pan Number', 'required');
			if($this->form_validation->run() == FALSE){
				$Status='false';
				$Message = validation_errors();
			}else{
				$postArray = $this->input->post();
				$postData = array(
					"staffID" => $postArray["staffID"],
					"panNumber"=>$postArray["panNumber"],
					"address"=>$postArray["address"],
					"dob"=>$postArray["dob"],
					"employmentType"=>$postArray["employmentType"],
					"occupation"=>$postArray["occupation"],
					"monthlyhouseholdIncome"=>$postArray["monthlyIncome"],
					"alternatemobileNo"=>$postArray["alternateMobileNo"],
					"emailID"=>$postArray["emailID"],
					"created"=>date('Y-m-d H:i:s')
				);
				$this->db->insert("staffkycdetails",$postData);
				$rowID = $this->db->insert_id();
				if($this->db->affected_rows() > 0){
					$ResultArray["staffkycID"] = $rowID;
					
					$Status='true';
					$Message='Data Added Successfully.';
				}else{
					$Status='false';
					$Message='Something went wrong. Please try Again.';
				}
			}
		}if(!empty($ResultArray)){
			return json_encode(array("status" => $Status,"message" => $Message,"data"=>$ResultArray));
		}else{
			return json_encode(array("status" => $Status,"message" => $Message));	
		}
	}

	public function uploadeStaffKycDetails(){
		$ResultArray = array();
		$postArray = $this->input->post();
		$UserData  = $this->VerifyStaffAccessToken($postArray["accessToken"]);
		if($UserData == FALSE){
			$Status='false';
			$Message = "Invalid Access Token";
		}else{
			$postArray = $this->input->post();
			$this->form_validation->set_rules('staffkycID', 'staff Kyc ID', 'required');
			
			if($this->form_validation->run() == FALSE){
				$Status='false';
				$Message = validation_errors();
			}else{
				$postData = array(
					"panphoto"=> $this->uploadFile('pancard',"panphoto"),			
					"aadharFront"=> $this->uploadFile('aadhar',"aadharFront"),
					"aadharBack"=> $this->uploadFile('aadhar',"aadharBack"),
					"staffSelfi"=> $this->uploadFile('selfi',"staffSelfi"),
					"updated"=>date('Y-m-d H:i:s')
				);
				$this->db->update("staffkycdetails",$postData,array("staffkycID"=>$postArray["staffkycID"]));
				$this->db->trans_complete();
				if($this->db->trans_status() === FALSE){
					$Status='false';
					$Message='Something went wrong. Please try Again.';
				}else{
					$Status='true';
					$Message='Data Added Successfully.';

				}
			}
		} if(!empty($ResultArray)){
			return json_encode(array("status" => $Status,"message" => $Message,"data"=>$ResultArray));
		}else{
			return json_encode(array("status" => $Status,"message" => $Message));	
		}
	}

	public function uploadeBankDetails(){
		$ResultArray = array();
		$postArray = $this->input->post();
		$UserData  = $this->VerifyStaffAccessToken($postArray["accessToken"]);
		if($UserData == FALSE){
			$Status='false';
			$Message = "Invalid Access Token";
		}else{
			$this->form_validation->set_rules('staffkycID', 'staff Kyc ID', 'required');
			
			if($this->form_validation->run() == FALSE){
				$Status='false';
				$Message = validation_errors();
			}else{
				$postData = array(
					"bankName"=> $postArray['bankName'],			
					"accountNo"=> $postArray['accountNo'],			
					"ifscCode"=> $postArray['ifscCode'],			
					"bankCheque"=> $this->uploadFile('bank',"bankCheque"),
					"bankStatement"=> $this->uploadFile('bank',"bankStatement"),
					"updated"=> date('Y-m-d H:i:s')
				);
				$this->db->update("staffkycdetails",$postData,array("staffkycID"=>$postArray["staffkycID"]));
				$this->db->trans_complete();
				if($this->db->trans_status() === FALSE){
					$Status='false';
					$Message='Something went wrong. Please try Again.';
				}else{
					$Status='true';
					$Message='Data Added Successfully.';

				}
			}
		} if(!empty($ResultArray)){
			return json_encode(array("status" => $Status,"message" => $Message,"data"=>$ResultArray));
		}else{
			return json_encode(array("status" => $Status,"message" => $Message));	
		}
	}

}
?>