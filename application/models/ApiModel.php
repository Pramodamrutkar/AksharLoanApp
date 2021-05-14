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
			$sql= "SELECT * FROM `parentmaster` WHERE mobileNo=?";
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
					$this->db->update("parentmaster",$postData,array("mobileNo"=>$mobileNO));
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
			$sql= "SELECT concat(s.sfirstName,' ',s.slastName) as studentName,concat(p.pfirstName,' ',p.plastName) as parentName, p.accessToken,p.otp, p.mobileNo FROM `studentmaster` as s INNER JOIN `parentmaster` as p ON p.parentID = s.parentID WHERE mobileNo=? AND otp=?";
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
			$sql = "SELECT s.*,sc.schoolName, p.* FROM studentmaster as s INNER JOIN parentmaster p on p.parentID = s.parentID LEFT JOIN schoolmaster sc on s.schoolID = sc.schoolID WHERE sc.isActive=1 AND p.`accessToken`=?";
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
        $dayDifference = 0;
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
				$isKyc = 0;
				$sqlretunKYC = "SELECT IFNULL(ld.isKyc,0) as isKyc FROM studentmaster as s INNER JOIN parentmaster p on p.parentID=s.parentID INNER JOIN loandetails as ld on ld.studentID = s.studentID WHERE p.accessToken=? AND ld.isKyc=1";
				$sqlretunKYCQuery = $this->db->query($sqlretunKYC,array($postArray["accessToken"]));
				//$sqlretunKYCArray = $sqlretunKYCQuery->result_array();
				if($sqlretunKYCQuery->num_rows() > 0) {
					$isKyc = 1;
				}else{
					$isKyc = 0;
				} 
			
				$sql = "SELECT s.*,p.* FROM studentmaster as s INNER JOIN parentmaster p on p.parentID=s.parentID LEFT JOIN loandetails as ld on ld.studentID = s.studentID WHERE s.`studentID`=? and p.accessToken=?";
				$query = $this->db->query($sql,array($studentID,$postArray["accessToken"]));
				$ResultArray = $query->row_array();
				$loanAmount = $ResultArray['currentPayableFees'];
				$schoolID = $ResultArray['schoolID'];
                
				//get school details for loan calculation
				$sql1 = "SELECT sf.* FROM schoolfinance as sf WHERE sf.`schoolID`=?";
				$query1 = $this->db->query($sql1,array($schoolID));
				$schoolFinance = $query1->row_array();
				$interestRate = $schoolFinance['interestRate'];
				$emiLevels = $schoolFinance['emiLevel'];
				$maxLoan = $schoolFinance['maxLoan'];
                $loanGaps = $schoolFinance['loanGaps'];
				$emiLevels = explode(",",$emiLevels);
			if($query->num_rows() > 0) {
                $ResultArray['perdaycharge'] = $schoolFinance['perdaycharge'];
                $ResultArray['interestRate'] = $schoolFinance['interestRate']; 
                $ResultArray['interestType'] = $schoolFinance['interestType'];
				$ResultArray['advancedEmi'] = $schoolFinance['advancedEmi'];
				$ResultArray['isKyc'] = $isKyc;
				//calculate processing fees
				$ResultArray['processingFees'] = $schoolFinance['fees'];
				$factor = round($schoolFinance['processingFeesgst'] / 100);
				$ResultArray['finalprocessingFees']= round(($factor * $schoolFinance['fees']) + $schoolFinance['fees']); 	
				$ResultArray['processingFeesgstPercent'] = round($schoolFinance['processingFeesgst']);
				$ResultArray['gstAmount'] = round($factor * $schoolFinance['fees']);

				
				$sqlcheckLoanAvailability = "SELECT ld.created as loandate, ld.*,sm.*,p.* FROM studentmaster sm INNER JOIN parentmaster as p on p.parentID=sm.parentID  INNER JOIN loandetails ld on sm.studentID=ld.studentID WHERE p.accessToken=? ORDER BY ld.loanID DESC LIMIT 1";
				$loanAvailabiltyquery = $this->db->query($sqlcheckLoanAvailability,array($postArray["accessToken"]));
				$loanAvailableData = $loanAvailabiltyquery->row_array();
				if($loanAvailabiltyquery->num_rows() > 0){
					$loanInitiateDate = strtotime($loanAvailableData['loandate']);
					$now = time();
					$datediff = $now - $loanInitiateDate;
					$dayDifference = round($datediff / (60 * 60 * 24));
				} 
				
			if($loanAmount <= $schoolFinance['loancapAmount']){
				if($dayDifference >= $loanGaps || $dayDifference == 0){
					if($loanAmount <= $maxLoan){
						if($schoolFinance['interestType'] == "Flat"){
							for($i = 0; $i < count($emiLevels); $i++){
									$ResultArray['loandetails'][] = $this->flatemiCalculator($loanAmount, $interestRate, $emiLevels[$i], $schoolFinance['collectionCharges']);
							}
						}else{		
							for($i = 0; $i < count($emiLevels); $i++){
								$ResultArray['loandetails'][] = $this->emiCalculator($loanAmount, $interestRate, $emiLevels[$i], $schoolFinance['collectionCharges']);
							}
						}
						$Status ='true';
						$Message="Data Found";
						$ResultArray['isLoan'] = 1;
					}else{
						$ResultArray['isLoan'] = 0;
						$Status ='false';
						//$Message="You can't get loan more than ".$maxLoan;
						$Message="you are currently Eligible for a fee loan of ".$maxLoan." Please pay the balance amount of ".($maxLoan - $loanAmount)." to the school before you can take a fee loan.";
					}
				}else{
					$ResultArray['isLoan'] = 0;
					$Status ='false';
					$calculateDays = $loanGaps - $dayDifference;
					$daystr = $calculateDays > 1 ? " days" :" day";
					$Message="You will get loan after ".$calculateDays.$daystr;
				}
			}else{
				$ResultArray['isLoan'] = 0;
				$Status = "true";
				$Message = "Loan Amount is Greater than Cap Amount";
				//$ResultArray = ''; //set empty not to return any data of the student.				
			}	
				}else{
						$Status ='false';
						$Message = 'No Data Found';
				}
		  }
			
	    }
		//echo "<pre>"; print_r($ResultArray); die;
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
		$getSchoolID = $this->getSchoolIDonAccessToken($postArray["accessToken"]);
		$loanAvailable = $this->checkLoanavailability($getSchoolID['schoolID'],$postArray["accessToken"]);


		if(!is_array($loanAvailable) && $loanAvailable){ 
			if($UserData == FALSE){
				$Status='false';
				$Message = "Invalid Access Token";
			}else{
				$this->form_validation->set_rules('studentID', 'student ID', 'required');
				//$this->form_validation->set_rules('panId', 'pan ID', 'required');
				//$this->form_validation->set_rules('isKyc', 'KYC Status', 'required');
				if($this->form_validation->run() == FALSE){
					$Status='false';
					$Message = validation_errors();
				}else{
					$postArray = $this->input->post();
					$studentID = $postArray['studentID'];
					
					
					/*$checkExistEntrysql = "SELECT loanID FROM loandetails where studentID=$studentID AND transactionDetails=''";
					$checkExistEntryQuery = $this->db->query($checkExistEntrysql);
					$checkExistEntryResult = $checkExistEntryQuery->result_array();	
					if(!empty($checkExistEntryResult)){
						foreach($checkExistEntryResult as $key => $loanIDvalue){
							$loanID = $loanIDvalue['loanID'];
							//$deleteSql = "DELETE FROM `emischedule` WHERE `loanID`=$loanID";
							$this->db->delete('emischedule', array('loanID' => $loanID)); 
							$this->db->delete('loandetails', array('loanID' => $loanID)); 
						}
					}*/
					
					$checkExistEntrysql = "					DELETE loandetails, emischedule
FROM    loandetails
INNER JOIN emischedule    
ON loandetails.loanID=emischedule.loanID
WHERE loandetails.studentID=".$studentID." AND loandetails.paymentStatus IS null AND loandetails.transactionDetails is null";
$checkExistEntryQuery = $this->db->query($checkExistEntrysql);					
					
					if(isset($postArray["isKyc"]) && $postArray["isKyc"] == 1){
						$sql = "SELECT * FROM loandetails where isApproved=1 AND studentID=$studentID";
						$kycQuery = $this->db->query($sql);
						$row = $kycQuery->row_array();
						$postData = array(	
							"studentID" => $postArray["studentID"],
							"lenderID" => $getSchoolID['lenderID'],
							"panId"=>$row["panId"],
							"loanType"=> 0,
							"address"=>$row["address"],
							"location"=>$row["location"],
							"city"=>$row["city"],
							"state"=>$row["state"],
							"pincode"=>$row["pincode"],
							"dob"=>$row["dob"],
							"employmentType"=>$row["employmentType"],
							"occupation"=>$row["occupation"],
							"monthlyIncome"=>$postArray["monthlyIncome"],
							"alternateMobile"=>$row["alternateMobile"],
							"emailID"=> $row["emailID"],
							"roi" => $postArray["rateOfInterest"],
							"loantenure" => $postArray["loantenure"],	
							"loanAmount"=>$postArray["loanAmount"],
							"emiAmount"=>$postArray["emiAmount"],
							"isKyc" => $postArray["isKyc"],
							"monthlyInterest"=>$postArray["monthlyInterest"],
							//"totalInterest"=>$postArray["totalInterest"],
							"processingFees"=>$postArray["processingFees"],
							"gstpercent"=>$postArray["gstpercent"],
							"gstAmount"=>$postArray["gstAmount"],
							"finalprocessingfees"=>$postArray["finalprocessingfees"],
							"pancard"=> $row["pancard"],			
							"aadharFront"=> $row["aadharFront"],
							"aadharBack"=> $row["aadharBack"],
							"selfi"=> $row["selfi"],
							"loanStatus"=> 0,
							"isApproved"=> 0,
							"created"=>date('Y-m-d H:i:s'),
							"ipAddress"=>$_SERVER['REMOTE_ADDR']
						);

					}else{

					$postData = array(
						"studentID" => $postArray["studentID"],
						"lenderID" => $getSchoolID['lenderID'],
						"panId"=>$postArray["panId"],
						"loanType"=>0,
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
						"roi" => $postArray["rateOfInterest"],
						"loantenure" => $postArray["loantenure"],	
						"loanAmount"=>$postArray["loanAmount"],
						"emiAmount"=>$postArray["emiAmount"],
						"monthlyInterest"=>$postArray["monthlyInterest"],
						//"isKyc"=> isset($postArray["isKyc"]) && $postArray["isKyc"] == 0 ? 1 : $postArray['isKyc'],
						"isKyc"=> $postArray['isKyc'],
						//"totalInterest"=>$postArray["totalInterest"],
						"processingFees"=>$postArray["processingFees"],
						"gstpercent"=>$postArray["gstpercent"],
						"gstAmount"=>$postArray["gstAmount"],
						"finalprocessingfees"=>$postArray["finalprocessingfees"],
						"loanStatus"=> 0,
						"isApproved"=> 0,
						"created"=>date('Y-m-d H:i:s'),
						"ipAddress"=>$_SERVER['REMOTE_ADDR']
					);
				}

					$this->db->insert("loandetails",$postData);
					$datarowID = $this->db->insert_id();
					$datarowIDArray = array();
					if($this->db->affected_rows() > 0){
						$datarowIDArray["loanID"] = $datarowID;
						$Status='true';
						$Message='Data Added Successfully.';
						$loanID = $datarowIDArray["loanID"];
						$studentID = $postArray['studentID'];
						$sqlld = 'SELECT ld.*,ld.created as loandate, sm.schoolID,sf.* FROM `loandetails` ld inner join studentmaster sm on sm.studentID=ld.studentID LEFT JOIN schoolfinance sf on sf.schoolID=sm.schoolID  WHERE ld.studentID=? and ld.loanID=?';
						$queryLd = $this->db->query($sqlld,array($studentID,$loanID));
						$QueryLoanData = $queryLd->row_array();
						$loanTenure = $QueryLoanData['loantenure'];
						$loanDate = $QueryLoanData['loandate'];
						$emiAmount = $QueryLoanData['emiAmount'];
						$day = date('d',strtotime($loanDate));
						$month = date('m',strtotime($loanDate));
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

						$lengthoftheArray = count($ResultArray);
						$postData = array();
						for($j=0;$j < $lengthoftheArray - 1;$j++){
							$postData[] = array(
								"studentID" => $studentID,
								"loanID" => $loanID,
								"noofdays"=> 0,
								"perdaycharges"=> $QueryLoanData['perdaycharge'],
								"emiDuedate" => $ResultArray[$j],
								"emiAmount" => $emiAmount,
								"created"=>date('Y-m-d H:i:s')
							);
						}
						$this->db->insert_batch("emischedule",$postData);

					}else{
						$Status='false';
						$Message='Something went wrong. Please try Again.';
					}
				}
			}
		}else{
			$Status ='false';
			$calculateDays = $loanAvailable['loanGaps'] - $loanAvailable['dayDifference'];
			$daystr = $calculateDays > 1 ? " days" :" day";
			$Message="You will get loan after ".$calculateDays.$daystr;
		}	
		if(!empty($datarowIDArray)){
			return json_encode(array("status" => $Status,"message" => $Message,"data"=>$datarowIDArray));
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
			$this->form_validation->set_rules('loanID', 'id', 'required');
			
			if($this->form_validation->run() == FALSE){
				$Status='false';
				$Message = validation_errors();
			}else{
				$pancard = ((isset($_FILES['pancard']) && $_FILES['pancard']['name'] !='') ? $this->uploadFile('pancard',"pancard") : '');
				$aadharFront = ((isset($_FILES['aadharFront']) && $_FILES['aadharFront']['name'] !='') ? $this->uploadFile('aadhar',"aadharFront") : '');
				$aadharBack = ((isset($_FILES['aadharBack']) && $_FILES['aadharBack']['name'] !='') ? $this->uploadFile('aadhar',"aadharBack") : '');
				$selfi = ((isset($_FILES['selfi']) && $_FILES['selfi']['name'] !='') ? $this->uploadFile('selfi',"selfi") : '');	
				$postData = array(		
					"pancard"=> $pancard,			
					"aadharFront"=> $aadharFront,
					"aadharBack"=> $aadharBack,
					"selfi"=> $selfi,
					"documentType" => (isset($postArray["documentType"]) ? $postArray["documentType"] : ''),
					"updated"=>date('Y-m-d H:i:s')
				);
				$this->db->update("loandetails",$postData,array("loanID"=>$postArray["loanID"]));
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

	public function getSchoolIDonAccessToken($accessToken){
		$accessToken = $this->security->xss_clean($accessToken);
		$sql = "SELECT sm.schoolID,scm.lenderID FROM parentmaster as pm INNER JOIN studentmaster sm on sm.parentID=pm.parentID INNER JOIN schoolmaster as scm on scm.schoolID=sm.schoolID WHERE  pm.`accessToken`=? GROUP BY sm.schoolID";
		$query = $this->db->query($sql,array($accessToken));
		$row = $query->row_array();
		return $row;
	}

	public function checkLoanavailability($schoolID,$accessToken){
		$sql1 = "SELECT sf.* FROM schoolfinance as sf WHERE sf.`schoolID`=?";
		$query1 = $this->db->query($sql1,array($schoolID));
		$schoolFinance = $query1->row_array();
		$loanGaps = $schoolFinance['loanGaps'];
		$dayDifference = 0;
		$sqlcheckLoanAvailability = "SELECT ld.created as loandate, ld.*,sm.*,p.* FROM studentmaster sm INNER JOIN parentmaster as p on p.parentID=sm.parentID  INNER JOIN loandetails ld on sm.studentID=ld.studentID WHERE p.accessToken=? ORDER BY ld.loanID DESC LIMIT 1";
		$loanAvailabiltyquery = $this->db->query($sqlcheckLoanAvailability,array($accessToken));
		$loanAvailableData = $loanAvailabiltyquery->row_array();
		if($loanAvailabiltyquery->num_rows() > 0){
			$loanInitiateDate = strtotime($loanAvailableData['loandate']);
			$now = time();
			$datediff = $now - $loanInitiateDate;
			$dayDifference = round($datediff / (60 * 60 * 24));
		} 

		if($dayDifference >= $loanGaps || $dayDifference == 0){
			return true;
		}else{
			return array("loanGaps"=> $loanGaps, "dayDifference" => $dayDifference);
		}
		
	}


	public function VerifyStudentAccessToken($accessToken){
		$accessToken = $this->security->xss_clean($accessToken);
		$sql = "SELECT * FROM parentmaster WHERE `accessToken`=?";
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
	
	//this function is not in used.
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
				$sql = "SELECT * FROM loandetails WHERE `studentID`=?";
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
    //this function is not in used.
	/* public function getloanWithEMI()
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
	} */
	function flatemiCalculator($principalAmnt, $roi, $tenure,$collectionCharges='') 
	{ 
		$emi = '';
		$rateOfInterest = $roi;
		//one month interest 
					
		$interest = round(($principalAmnt *  $roi) / 100); 
		$withoutinterestEmi = round($principalAmnt/$tenure);
		
		//$monthlyInterest = round($interest/$tenure);
		$monthlyInterest = round($interest);
		$emi = ($withoutinterestEmi + $monthlyInterest + $collectionCharges);

		$totalInterestAmount = $interest;
		$interestPayable = $totalInterestAmount + $principalAmnt;

		//$monthlyInterest = $interest/$tenure;
		//$monthlyInterest = round($monthlyInterest);
		//$totalInterestAmount = $totalInterestAmount;
		//$interestPayable = $interestPayable;
		$emi = round($emi);
		//$principalMonthlyAmount = $principalAmnt/$tenure;
		$principalMonthlyAmount = $withoutinterestEmi;


		return (array("Duration" => $tenure,"EMI"=>$emi,"principalMonthlyAmount"=> $principalMonthlyAmount,"monthlyInterest"=>$monthlyInterest,"totalInterest"=>$totalInterestAmount,"totalAmountwithInterest" =>$interestPayable,"rateOfInterest"=>$rateOfInterest,"collectionCharges"=>$collectionCharges)); 
	}

	function emiCalculator($principalAmnt, $roi, $tenure , $collectionCharges="") 
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
		$emi = round($emi + $collectionCharges);
		$principalMonthlyAmount = $principalAmnt/$tenure;
		$principalMonthlyAmount = round($principalMonthlyAmount);
		return (array("Duration" => $tenure,"EMI"=>$emi,"principalMonthlyAmount"=>$principalMonthlyAmount,"monthlyInterest"=>$monthlyInterest,"totalInterest"=>$interestPayable,"totalAmountwithInterest" =>$totalInterestAmount,"rateOfInterest"=>$rateOfInterest,"collectionCharges"=>$collectionCharges)); 
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
				$sql = "SELECT s.*,ld.*,p.* FROM studentmaster as s INNER JOIN parentmaster p on p.parentID=s.parentID INNER JOIN loandetails as ld on s.studentID=ld.studentID WHERE s.`studentID`=? and p.accessToken=?";
				$query = $this->db->query($sql,array($studentID,$postArray["accessToken"]));
				$studentData = $query->row_array();
				$agreementSql = "SELECT * FROM agreement";
				$agreementQuery = $this->db->query($agreementSql);
				$agreement = $agreementQuery->row_array();
				/* $sqlLoan = "SELECT * FROM loandetails WHERE `studentID`=?";
				$queryLoan = $this->db->query($sqlLoan,array($studentID));
				$loanData = $queryLoan->row_array(); */
				
				$agreementContents = $agreement['agreementText'];

				$agreementData = str_replace("[DateNumber]",date("jS"),$agreementContents);
				$agreementData = str_replace("[MonthName]",date("F"),$agreementData);
				$agreementData = str_replace("[YearNumber]",date("Y"),$agreementData);
				$agreementData = str_replace("[StudentName]",$studentData['sfirstName']." ".$studentData["slastName"],$agreementData);
				$agreementData = str_replace("[PanNumber]",$studentData['panId'],$agreementData);
				$agreementData = str_replace("[ParentName]",$studentData['pfirstName']." ".$studentData["plastName"],$agreementData);
				$agreementData = str_replace("[AddressLocationCityDistrict]",$studentData['address'].", ".$studentData['location'].", ".$studentData['city'].", ".$studentData['state'],$agreementData);
				$agreementData = str_replace("[LoanAmount]",$studentData['loanAmount'],$agreementData);
				$agreementData = str_replace("[EmiNumber]",$studentData['loantenure'],$agreementData);
				$agreementData = str_replace("[RateOfInterest]",$studentData['roi'],$agreementData);
				$agreementData = str_replace("[ParentNameBehalf]",$studentData['pfirstName']." ".$studentData["plastName"],$agreementData);
				$agreementData = str_replace("[TenureMonthly]",$studentData['loantenure'],$agreementData);
				$agreementData = str_replace("[EmiAmount]",$studentData['emiAmount'],$agreementData);
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
					"updated"=>date('Y-m-d H:i:s')
				);
				$this->db->update("loandetails",$postData,array("loanID"=>$loanID));
				$this->db->trans_complete();
				if($this->db->trans_status() === FALSE){
					$Status='false';
					$Message='Error.';
				}else{
					$Status='true';
					$Message='Payment Details Updated.';
					//code to update current payable fees once the payment is done.
                    $loanID = $postArray["loanID"];
                    $sqlloan = 'SELECT * FROM `loandetails` WHERE loanID=?';
		            $queryloan = $this->db->query($sqlloan,array($loanID));
		            $loanData = $queryloan->row_array();
                    $postData = array(
                        "currentPayableFees" => 0,
                        "updated"=>date('Y-m-d H:i:s')
                    );
                    $this->db->update("studentmaster",$postData,array("studentID"=>$loanData['studentID']));
                    $this->db->trans_complete();
				}
			}		
		}
		return json_encode(array("status" => $Status,"message" => $Message));	
		
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
				
			/* 	$sqlld = 'SELECT ld.*,ld.created as loandate, sm.schoolID,sf.* FROM `loandetails` ld inner join studentmaster sm on sm.studentID=ld.studentID LEFT JOIN schoolfinance sf on sf.schoolID=sm.schoolID  WHERE ld.studentID=? and ld.loanID=?';
				$queryLd = $this->db->query($sqlld,array($studentID,$loanID));
				$QueryLoanData = $queryLd->row_array();
				$loanTenure = $QueryLoanData['loantenure'];
				$loanDate = $QueryLoanData['loandate'];
				$emiAmount = $QueryLoanData['emiAmount'];
				$day = date('d',strtotime($loanDate));
				$month = date('m',strtotime($loanDate));
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
				/*$lengthoftheArray = count($ResultArray);
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
				$this->db->insert_batch("emischedule",$postData); */
				if($studentID){
					$response = $this->getscheduledEmi($studentID,$loanID);
                    if(!empty($response)){
                        $Status='true';
					    $Message='Data Found.';
                    }else{
                        $Status='true';
					    $Message='No Data Found.';
                    }
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
	
	function getscheduledEmi($studentID,$loanID){
		$sqlemi = 'SELECT * FROM `emischedule` WHERE studentID=? AND loanID=?';
		$queryemi = $this->db->query($sqlemi,array($studentID,$loanID));
		return $queryemi->result_array();
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
					"emiPaiddate" => date('Y-m-d H:i:s'),
					"updated" => date('Y-m-d H:i:s')
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
		        $emiArray = array();
				$sqlemi1 = "SELECT * FROM `studentmaster` as s INNER JOIN `parentmaster` p ON p.parentID = s.parentID WHERE accessToken=?";
				$queryemi1 = $this->db->query($sqlemi1, array($postArray['accessToken']));
				$result = $queryemi1->result_array();		

				foreach($result as $k => $v){					
					$sqlemi = "SELECT e.emiID, e.studentID, e.emiDuedate, e.emiAmount, CONCAT(s.sfirstName,' ',s.slastName) as studentName FROM `studentmaster` as s INNER JOIN emischedule as e on e.studentID= s.studentID WHERE e.emiStatus=0 AND e.studentID=".$v['studentID']." ORDER BY  e.emiDuedate ASC LIMIT 2";
					$queryemi = $this->db->query($sqlemi);
					if($queryemi->num_rows() > 0 ) {
						$emiRes = $queryemi->result_array();
						foreach($emiRes as $emi){
							$emiArray[] = $emi;
						}			
						$price = array();
						$em = array();
						
						$price = array_column($emiArray, 'emiDuedate');
						$em = array_multisort($price, SORT_ASC, $emiArray);
						
					}
				}
				$ResultArray = $emiArray;
				if(!empty($ResultArray)){
					$Status='true';
					$Message="Data Found";
				}else{
					$Status='false';
					$Message="No Pending EMI";
				}
		}
		if(!empty($ResultArray)){
			return json_encode(array("status" => $Status,"message" => $Message,"data"=>$ResultArray));
		}else{
			return json_encode(array("status" => $Status,"message" => $Message));	
		}
	}
	
    public function addPayfromownAccount()
    {

		$postArray = $this->input->post();
		$UserData  = $this->VerifyStudentAccessToken($postArray["accessToken"]);
		if($UserData == FALSE){
			$Status='false';
			$Message = "Invalid Access Token";
		}else{
			$this->form_validation->set_rules('studentID', 'student ID', 'required');
			if($this->form_validation->run() == FALSE){
				$Status='false';
				$Message = validation_errors();
			}else{
				$postArray = $this->input->post();
				$postData = array(
					"studentID" => $postArray["studentID"],
					"loanType"=>1,
					"loanAmount"=>$postArray["loanAmount"],
					"created"=>date('Y-m-d H:i:s'),
					"paymentStatus" => $postArray["paymentStatus"],
					"transactionDetails"=> $postArray["transactionDetails"],
					"ipAddress"=>$_SERVER['REMOTE_ADDR'],
					"updated"=>date('Y-m-d H:i:s')
				);
				$this->db->insert("loandetails",$postData);
				$rowID = $this->db->insert_id();
				if($this->db->affected_rows() > 0){
					$row["loanID"] = $rowID;
					$Status='true';
					$Message='Data Added Successfully.';

					$sqlstudent = "SELECT * FROM `studentmaster` WHERE studentID=?";
					$querystudent = $this->db->query($sqlstudent, array($postArray['studentID']));
					$result = $querystudent->row_array();	
					$updatePayableFees = $result['currentPayableFees'] - $postArray["loanAmount"];
					$postData = array(
						"currentPayablefees"=> $updatePayableFees,
						"updated"=> date('Y-m-d H:i:s')
					);
					$this->db->update("studentmaster",$postData,array("studentID"=>$postArray["studentID"]));
					$this->db->trans_complete();
				}else{
					$Status='false';
					$Message='Something went wrong. Please try Again.';
				}	
			}
		}		
		if(!empty($row)){
			return json_encode(array("status" => $Status,"message" => $Message,"data"=>$row));
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
			$sql= "SELECT concat(staffFirstName,' ',staffLastName) as staffName,accessToken,otp,mobileNo,staffID FROM `staffmaster` WHERE mobileNo=? AND otp=?";
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

	public function staffGetcheckLoanElibility()
	{
		$ResultArray = array();
		$postArray = $this->input->post();
		$UserData  = $this->VerifyStaffAccessToken($postArray["accessToken"]);
		if($UserData == FALSE){
			$Status='false';
			$Message = "Invalid Access Token";
		}else{
			$this->form_validation->set_rules('staffID', 'staff ID', 'required');
			if($this->form_validation->run() == FALSE){
				$Status='false';
				$Message = validation_errors();
			}else{
				$staffID = $this->security->xss_clean($postArray["staffID"]);
				$sql= "SELECT sm.*,sf.emiLevel,sf.interestRate,sf.maxLoan FROM `staffmaster` sm INNER JOIN stafffinance sf on sf.schoolID = sm.schoolID WHERE staffID=?";
				$query=$this->db->query($sql,array($staffID));
				$ResultArray = $query->row_array();
				$loanAmount = $ResultArray['maxLoan'];
				$interestRate = $ResultArray['interestRate'];
				$emiLevels = $ResultArray['emiLevel'];
				$ResultArray['monthlyCharges'] = 300;
				$ResultArray['loanArrangementFees'] = 600;
				$ResultArray['latepaymentcharges'] = 10;
				$emiLevels = explode(",",$emiLevels);
				for($i = 0; $i < count($emiLevels); $i++){
					$ResultArray['loandetails'][$emiLevels[$i]." Months"] = $this->emiCalculator($loanAmount, $interestRate, $emiLevels[$i]);
				}
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
					"roi"=>$postArray["rateOfInterest"],
					"loantenure"=>$postArray["loantenure"],
					"loanAmount"=>$postArray["loanAmount"],
					"emiAmount"=>$postArray["emiAmount"],
					"monthlyInterest"=>$postArray["monthlyInterest"],
					"totalInterest"=>$postArray["totalInterest"],
					"created"=>date('Y-m-d H:i:s')
				);
				$this->db->insert("staffloan",$postData);
				$rowID = $this->db->insert_id();
				if($this->db->affected_rows() > 0){
					$row["staffloanID"] = $rowID;
					$Status='true';
					$Message='Data Added Successfully.';
					
					$staffloanID = $rowID;
                    $staffID = $postArray['staffID'];
                    
                    $sqlld = 'SELECT sl.*,sl.created as loandate, sm.schoolID,sf.* FROM `staffloan` sl inner join staffmaster sm on sm.staffID=sl.staffID LEFT JOIN stafffinance sf on sf.schoolID=sm.schoolID  WHERE sl.staffID=? and sl.staffloanID=?';
                    $queryLd = $this->db->query($sqlld,array($staffID,$staffloanID));
                    $QueryLoanData = $queryLd->row_array();
                    $loanTenure = $QueryLoanData['loantenure'];
                    $loanDate = $QueryLoanData['loandate'];
                    $emiAmount = $QueryLoanData['emiAmount'];
                    $day = date('d',strtotime($loanDate));
                    $month = date('m',strtotime($loanDate));
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

                    $lengthoftheArray = count($ResultArray);
                    $postData = array();
                    for($j=0;$j < $lengthoftheArray - 1;$j++){
                        $postData[] = array(
                            "staffID" => $staffID,
                            "staffloanID" => $staffloanID,
                            "noofdays"=> 0,
                            "perdaycharges"=> 0,
                            "emiDuedate" => $ResultArray[$j],
                            "emiAmount" => $emiAmount,
                            "created"=>date('Y-m-d H:i:s')
                        );
                    }
                    $this->db->insert_batch("staffemischedule",$postData);
					

				}else{
					$Status='false';
					$Message='Something went wrong. Please try Again.';
				}
			}
		}if(!empty($row)){
			return json_encode(array("status" => $Status,"message" => $Message,"data"=>$row));
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
			$this->form_validation->set_rules('staffloanID', 'staff Loan ID', 'required');
			
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
				$this->db->update("staffloan",$postData,array("staffloanID"=>$postArray["staffloanID"]));
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
			$this->form_validation->set_rules('staffloanID', 'staff Loan ID', 'required');
			
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
				$this->db->update("staffloan",$postData,array("staffloanID"=>$postArray["staffloanID"]));
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

	public function savestaffprocessingFeesStatus()
	{
		$postArray = $this->input->post();
		$ResultArray = array();
		$UserData  = $this->VerifyStaffAccessToken($postArray["accessToken"]);
		if($UserData == FALSE){
			$Status='false';
			$Message = "Invalid Access Token";
		}else{
			$this->form_validation->set_rules('staffloanID', 'staff loanID', 'required');
			if($this->form_validation->run() == FALSE){
				$Status='false';
				$Message = validation_errors();
			}else{
				$postArray = $this->input->post();
				$staffloanID = $postArray["staffloanID"];
				$postData = array(
					"paymentStatus" => $postArray["paymentStatus"],
					"transactionDetails"=> $postArray["transactionDetails"],
					"updated"=>date('Y-m-d H:i:s')
				);
				$this->db->update("staffloan",$postData,array("staffloanID"=>$staffloanID));
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
		return json_encode(array("status" => $Status,"message" => $Message));	
		
	}
	
	function getStaffscheduledEmi($staffID,$loanID){
		$sqlemi = 'SELECT * FROM `staffemischedule` WHERE staffID=? AND staffloanID=?';
		$queryemi = $this->db->query($sqlemi,array($staffID,$loanID));
		return $queryemi->result_array();
	}
	
	public function staffemiConfirmationDetail(){
		$postArray = $this->input->post();
		$ResultArray = array();
		$emiDate = '';
		$UserData  = $this->VerifyStaffAccessToken($postArray["accessToken"]);
		if($UserData == FALSE){
			$Status='false';
			$Message = "Invalid Access Token";
		}else{
			$this->form_validation->set_rules('staffID', 'staffID ID', 'required');
			$this->form_validation->set_rules('staffloanID', 'staff Loan ID', 'required');
			if($this->form_validation->run() == FALSE){
				$Status='false';
				$Message = validation_errors();
			}else{
				$postArray = $this->input->post();
				$staffloanID = $postArray['staffloanID'];
				$staffID = $postArray['staffID'];
				
			
				if($staffID){
					$response = $this->getStaffscheduledEmi($staffID,$staffloanID);
                    if(!empty($response)){
                        $Status='true';
					    $Message='Data Found.';
                    }else{
                        $Status='true';
					    $Message='No Data Found.';
                    }
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

	public function updatestaffemiStatusDetails(){
		$postArray = $this->input->post();
		$ResultArray = array();
		$UserData  = $this->VerifyStaffAccessToken($postArray["accessToken"]);
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
				$this->db->update("staffemischedule",$postData,array("emiID"=>$emiID));
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
		return json_encode(array("status" => $Status,"message" => $Message));	
	}

	public function staffgetPendingEMIs(){
		$ResultArray = array();
		$postArray = $this->input->post();
		$UserData  = $this->VerifyStaffAccessToken($postArray["accessToken"]);
		if($UserData == FALSE){
			$Status='false';
			$Message = "Invalid Access Token";
		}else{
				$sqlemi1 = "SELECT * FROM `staffmaster` WHERE accessToken=?";
				$queryemi1 = $this->db->query($sqlemi1, array($postArray['accessToken']));
				$result = $queryemi1->result_array();		

				foreach($result as $k => $v){

					$sqlemi = "SELECT e.emiID, e.staffID, e.emiDuedate, e.emiAmount, CONCAT(s.staffFirstName,' ',s.staffLastName) as staffName FROM `staffmaster` as s INNER JOIN staffemischedule as e on e.staffID= s.staffID WHERE e.emiStatus=0 AND e.staffID=? LIMIT 2";
					$queryemi = $this->db->query($sqlemi, $v['staffID']);
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

	//personal Loan API functions start
	public function getPersonalloandetails()
	{
		$postArray = $this->input->post();
		$ResultArray = array();
		$schoolFinance = array();
        $dayDifference = 0;
		$UserData  = $this->VerifyStudentAccessToken($postArray["accessToken"]);
		if($UserData == FALSE){
			$Status='false';
			$Message = "Invalid Access Token";
		}else{
				$sql = "SELECT * FROM studentmaster as sm INNER JOIN parentmaster as p on p.parentID=sm.parentID INNER JOIN personalfinance as pf ON pf.schoolID=sm.schoolID WHERE p.accessToken=?";
				$query = $this->db->query($sql,array($postArray["accessToken"]));
				$ResultArray = $query->row_array();
				$loanAmount = $ResultArray['maxLoan'];
				$interestRate = $ResultArray['interestRate'];
				$emiLevels = $ResultArray['noofInstallments'];	
				
				if($query->num_rows() > 0) {
					$ResultArray['interestRate'] = $ResultArray['interestRate']; 
					$ResultArray['interestType'] = $ResultArray['interestType']; 
	
					for($i = 0; $i < $emiLevels; $i++){
						
						$ResultArray['loandetails'][$emiLevels." Months"] = $this->emiCalculator($loanAmount, $interestRate, $emiLevels);
					}
					$Status ='true';
					$Message="Data Found";
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

	public function savepersonalLoan(){
		$ResultArray = array();
		$postArray = $this->input->post();
		$UserData  = $this->VerifyStudentAccessToken($postArray["accessToken"]);
		if($UserData == FALSE){
			$Status='false';
			$Message = "Invalid Access Token";
		}else{
			$this->form_validation->set_rules('parentID', 'Parent ID', 'required');
			$this->form_validation->set_rules('bankName', 'Bank Name', 'required');
			if($this->form_validation->run() == FALSE){
				$Status='false';
				$Message = validation_errors();
			}else{
				$postArray = $this->input->post();
				$postData = array(
					"parentID" => $postArray["parentID"],
					"bankName"=>$postArray["bankName"],
					"accountNo"=>$postArray["accountNo"],
					"ifscCode"=>$postArray["ifscCode"],
					"bankcheque"=> $this->uploadFile('bank',"bankcheque"),
					"bankstatement"=> $this->uploadFile('bank',"bankstatement"),
					"roi"=>$postArray["rateOfInterest"],
					"loantenure"=>$postArray["loantenure"],
					"loanAmount"=>$postArray["loanAmount"],
					"emiAmount"=>$postArray["emiAmount"],
					"monthlyInterest"=>$postArray["monthlyInterest"],
					"totalInterest"=>$postArray["totalInterest"],
					"created"=>date('Y-m-d H:i:s')
				);
				$this->db->insert("personalloan",$postData);
				$rowID = $this->db->insert_id();
				if($this->db->affected_rows() > 0){
					$row["personalloanID"] = $rowID;
					$Status='true';
					$Message='Data Added Successfully.';
					
					$personalloanID = $rowID;
                    $parentID = $postArray['parentID'];
                    
                    $sqlld = 'SELECT pl.*,pl.created as loandate, sm.schoolID, pf.* FROM `personalloan` pl inner join studentmaster sm on sm.parentID=pl.parentID LEFT JOIN personalfinance pf on pf.schoolID=sm.schoolID  WHERE pl.parentID=? and pl.personalloanID=?';
                    $queryLd = $this->db->query($sqlld,array($parentID,$personalloanID));
                    $QueryLoanData = $queryLd->row_array();
                    $loanTenure = $QueryLoanData['loantenure'];
                    $loanDate = $QueryLoanData['loandate'];
                    $emiAmount = $QueryLoanData['emiAmount'];
                    $day = date('d',strtotime($loanDate));
                    $month = date('m',strtotime($loanDate));
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

                    $lengthoftheArray = count($ResultArray);
                    $postData = array();
                    for($j=0;$j < $lengthoftheArray - 1;$j++){
                        $postData[] = array(
                            "parentID" => $parentID,
                            "personalloanID" => $personalloanID,
                            "noofdays"=> 0,
                            "perdaycharges"=> 0,
                            "emiDuedate" => $ResultArray[$j],
                            "emiAmount" => $emiAmount,
                            "created"=>date('Y-m-d H:i:s')
                        );
                    }
                    $this->db->insert_batch("personalemischedule",$postData);
					
					
				}else{
					$Status='false';
					$Message='Something went wrong. Please try Again.';
				}
			}
		}if(!empty($row)){
			return json_encode(array("status" => $Status,"message" => $Message,"data"=>$row));
		}else{
			return json_encode(array("status" => $Status,"message" => $Message));	
		}
		
	}

	public function savepersonalprocessingFeesStatus()
	{
		$postArray = $this->input->post();
		$ResultArray = array();
		$UserData  = $this->VerifyStudentAccessToken($postArray["accessToken"]);
		if($UserData == FALSE){
			$Status='false';
			$Message = "Invalid Access Token";
		}else{
			$this->form_validation->set_rules('personalloanID', 'Personal loanID', 'required');
			if($this->form_validation->run() == FALSE){
				$Status='false';
				$Message = validation_errors();
			}else{
				$postArray = $this->input->post();
				$personalloanID = $postArray["personalloanID"];
				$postData = array(
					"paymentStatus" => $postArray["paymentStatus"],
					"transactionDetails"=> $postArray["transactionDetails"],
					"updated"=>date('Y-m-d H:i:s')
				);
				$this->db->update("personalloan",$postData,array("personalloanID"=>$personalloanID));
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
		return json_encode(array("status" => $Status,"message" => $Message));	
		
	}

	function getpersonalscheduledEmi($parentID,$personalloanID){
		$sqlemi = 'SELECT * FROM `personalemischedule` WHERE parentID=? AND personalloanID=?';
		$queryemi = $this->db->query($sqlemi,array($parentID,$personalloanID));
		return $queryemi->result_array();
	}

	public function personalEmiconfirmation()
	{
		$postArray = $this->input->post();
		$ResultArray = array();
		$emiDate = '';
		$UserData  = $this->VerifyStudentAccessToken($postArray["accessToken"]);
		if($UserData == FALSE){
			$Status='false';
			$Message = "Invalid Access Token";
		}else{
			$this->form_validation->set_rules('parentID', 'parent ID', 'required');
			$this->form_validation->set_rules('personalloanID', 'Personal Loan ID', 'required');
			if($this->form_validation->run() == FALSE){
				$Status='false';
				$Message = validation_errors();
			}else{
				$postArray = $this->input->post();
				$personalloanID = $postArray['personalloanID'];
				$parentID = $postArray['parentID'];
				
			
				if($parentID){
					$response = $this->getpersonalscheduledEmi($parentID,$personalloanID);
                    if(!empty($response)){
                        $Status='true';
					    $Message='Data Found.';
                    }else{
                        $Status='true';
					    $Message='No Data Found.';
                    }
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

	public function updatepersonalemiStatusDetails(){
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
				$this->db->update("personalemischedule",$postData,array("emiID"=>$emiID));
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
		return json_encode(array("status" => $Status,"message" => $Message));	
	}

	public function personalgetPendingEMIs()
	{
		$ResultArray = array();
		$postArray = $this->input->post();
		$UserData  = $this->VerifyStudentAccessToken($postArray["accessToken"]);
		if($UserData == FALSE){
			$Status='false';
			$Message = "Invalid Access Token";
		}else{
				$sqlemi1 = "SELECT * FROM `parentmaster` WHERE accessToken=?";
				$queryemi1 = $this->db->query($sqlemi1, array($postArray['accessToken']));
				$result = $queryemi1->result_array();		

				foreach($result as $k => $v){
					$sqlemi = "SELECT e.emiID, e.parentID, e.emiDuedate, e.emiAmount, CONCAT(s.pfirstName,' ',s.plastName) as parentName FROM `parentmaster` as s INNER JOIN personalemischedule as e on e.parentID= s.parentID WHERE e.emiStatus=0 AND e.parentID=? LIMIT 2";
					$queryemi = $this->db->query($sqlemi, $v['parentID']);
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
	
	public function cronToupdateEmioverdues()
	{
		$currentDate = date('Y-m-d');
		$sql = "SELECT e.*,ld.loanStatus FROM emischedule as e INNER JOIN loandetails as ld on ld.loanID=e.loanID WHERE ld.loanStatus=0 and e.ispaid <> 1 and e.emiStatus NOT IN (1,2)";		
		$query = $this->db->query($sql);
		$result = $query->result_array();
		//echo "<pre>"; print_r($result);die;
		$noOfDays = 0;
		foreach($result as $row){
			if(strtotime($currentDate) > strtotime($row['emiDuedate']) && $row['noofdays'] < 30){
				$noOfDays = $row['noofdays'] + 1;
				$postData = array(
					"noofdays" => $noOfDays,
					"updated"=>date('Y-m-d H:i:s')
				);
				$this->db->update("emischedule",$postData,array("emiID"=>$row['emiID']));
				$this->db->trans_complete();
				if($this->db->trans_status() === FALSE){
					$Status='false';
					$Message='Error.';
				}else{
					$Status='true';
					$Message= $row['noofdays']." No of day updated for EmiID=".$row['emiID']." and loanID=".$row['loanID']."";
				}
			}else if($row['noofdays'] >= 30){
				//code to declare emi overdue
				$postData = array(
					"emiStatus" => 2, 
					"updated"=> date('Y-m-d H:i:s')
				);
				$this->db->update("emischedule",$postData,array("emiID"=>$row['emiID']));
				$this->db->trans_complete();
				//loandetails forcefully closed i.e 3 in loandetails
				$postData2 = array(
					"loanStatus" => 3, 
					"updated"=> date('Y-m-d H:i:s')
				);
				$this->db->update("loandetails",$postData2,array("loanID"=>$row['loanID']));
				$this->db->trans_complete();
				if($this->db->trans_status() === FALSE){
					$Status='false';
					$Message='Error.';
				}else{
					$Status='true';
					$Message= "Loan closed forcefully for loanID=".$row['loanID']."";
				}
				
			}else{
				$Status='true';
				$Message= "No EMI Found";
			}
		}
		return json_encode(array("status" => $Status,"message" => $Message));	
	}
}
?>