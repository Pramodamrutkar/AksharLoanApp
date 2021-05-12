<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class ServiceModel extends CI_Model{

    function __construct(){
        parent::__construct();
        date_default_timezone_set("Asia/Kolkata");
		$this->load->helper('string');
    }
	public function CountryList(){
		$Sql   = "SELECT * FROM countrymaster ORDER BY countryName ASC";
		$Query = $this->db->query($Sql);
		return $Query->result_array();
	}
	public function StateList($countryID){
		if($countryID > 0){
			$Sql   = "SELECT * FROM statemaster WHERE countryID=".$countryID." ORDER BY stateName ASC";
			$Query = $this->db->query($Sql);
			return $Query->result_array();
		}else{
			return array();	
		}
	}
	public function CityList($stateID){
		if($stateID > 0){
			$Sql   = "SELECT * FROM citymaster WHERE stateID=".$stateID." ORDER BY cityName ASC";
			$Query = $this->db->query($Sql);
			return $Query->result_array();
		}else{
			return array();	
		}
	}
	public function lenderList(){
			$Sql   = "SELECT * FROM lendermaster WHERE isDeleted=0 ORDER BY lenderID ASC";
			$Query = $this->db->query($Sql);
			return $Query->result_array();	
	}
	
	public function schoolDashboardDetails($schoolID)
	{	
		$Sql   = "SELECT count(*) as noOfstudent FROM studentmaster WHERE schoolID=".$schoolID."";
		$Query = $this->db->query($Sql);
		$studentData = $Query->row_array();
		$data['noOfstudent'] = $studentData['noOfstudent'];
		return $data;
	}

	public function validate($eMail,$password){
		$eMail= $this->security->xss_clean($eMail);
		$password= $this->security->xss_clean($password);		
		$sql= "SELECT * FROM usermaster WHERE eMail=?";
		$query=$this->db->query($sql,array($eMail));
		//print_r($this->db->last_query());    		
		if($query->num_rows() == 1) {
			$res = $query->row_array();			
            $hashPass = $res['password'];
			if(password_verify($password, $hashPass)){
				return $res;				
			}else{
				return false;				
			}
		}else{
			return false;
		}
	}
	public function SchoolLogin($eMail,$password){
		$eMail= $this->security->xss_clean($eMail);
		$password= $this->security->xss_clean($password);		
		$sql= "SELECT * FROM schoolmaster WHERE eMail=?";
		$query=$this->db->query($sql,array($eMail));
		//print_r($this->db->last_query());    		
		if($query->num_rows() == 1) {
			$res = $query->row_array();			
            $hashPass = $res['password'];
			if(password_verify($password, $hashPass)){
				return $res;				
			}else{
				return false;				
			}
		}else{
			return false;
		}
	}
	
	
	// User Type	
	public function ListUserType(){
		$sql   = "SELECT * FROM usertype WHERE isDeleted=0 ORDER BY typeID DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}	

	// User	
	public function ListUser(){
		$sql   = "SELECT usermaster.*,usertype.typeName FROM usermaster INNER JOIN usertype ON usermaster.typeID=usertype.typeID WHERE usermaster.isDeleted=0 ORDER BY usermaster.userID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}	
	public function GetUser($userID){
		$sql   = "SELECT * from usermaster WHERE isDeleted=0 AND userID=$userID";
		$query = $this->db->query($sql);
		return $query->row();
	}	
	public function SaveUser(){
		$postArray = $this->input->post();
		$imageName = uploadFile('user','imageName');	
		$postData = array(
			"fullName"=>$postArray["fullName"],
			"eMail"=>$postArray["eMail"],
			"password"=> password_hash($postArray["password"], PASSWORD_DEFAULT),
			"mobileNo"=>$postArray["mobileNo"],
			"userType"=>0,
			"typeID"=>$postArray["typeID"],
			"areaID"=>$postArray["areaID"],
			"imageName"=>$imageName,
			"isActive"=>$postArray["isActive"],
			"created"=>date('Y-m-d H:i:s')
		);
		$this->db->insert("usermaster",$postData);
		return ($this->db->affected_rows() > 0)? true:false;
	}
	public function UpdateUser(){
		$postArray = $this->input->post();
		$imageName = uploadFile('user','imageName');	
		$postData = array(
			"fullName"=>$postArray["fullName"],
			"eMail"=>$postArray["eMail"],
			"mobileNo"=>$postArray["mobileNo"],
			"userType"=>0,
			"typeID"=>$postArray["typeID"],
			"areaID"=>$postArray["areaID"],
			"imageName"=>$imageName,
			"isActive"=>$postArray["isActive"],
			"updated"=>date('Y-m-d H:i:s')
		);
		if(isset($postArray["password"]) && $postArray["password"]!=""){
			$postData["password"] = password_hash($postArray["password"], PASSWORD_DEFAULT);	
		}				
		$this->db->update("usermaster",$postData,array("userID"=>$postArray["userID"]));
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
	}	
	public function DeleteUser(){
		$postArray = $this->input->post();
		$postData = array(
			"isDeleted"=>1,
			"updated"=>date('Y-m-d H:i:s')
		);
		$this->db->update("usermaster",$postData,array("userID"=>$postArray["userID"]));		
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
	}
	// Area	
	public function ListArea(){
		$sql   = "SELECT * FROM areamaster WHERE isDeleted=0 ORDER BY areaID DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}	
	public function GetArea($areaID){
		$sql   = "SELECT * from areamaster WHERE isDeleted=0 AND areaID=$areaID";
		$query = $this->db->query($sql);
		return $query->row();
	}	
	public function SaveArea(){
		$postArray = $this->input->post();
		$postData = array(
			"areaName"=>$postArray["areaName"],
			"description"=>$postArray["description"],
			"isActive"=>$postArray["isActive"],
			"created"=>date('Y-m-d H:i:s')
		);
		$this->db->insert("areamaster",$postData);
		return ($this->db->affected_rows() > 0)? true:false;
	}
	public function UpdateArea(){
		$postArray = $this->input->post();
		$postData = array(
			"areaName"=>$postArray["areaName"],
			"description"=>$postArray["description"],
			"isActive"=>$postArray["isActive"],
			"updated"=>date('Y-m-d H:i:s')
		);
		$this->db->update("areamaster",$postData,array("areaID"=>$postArray["areaID"]));
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
	}	
	public function DeleteArea(){
		$postArray = $this->input->post();
		$postData = array(
			"isDeleted"=>1,
			"updated"=>date('Y-m-d H:i:s')
		);
		$this->db->update("areamaster",$postData,array("areaID"=>$postArray["areaID"]));		
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
	}
	// School Type	
	public function ListSchoolType(){
		$sql   = "SELECT * FROM schooltype WHERE isDeleted=0 ORDER BY typeID DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}		
	// School Board	
	public function ListSchoolBoard(){
		$sql   = "SELECT * FROM schoolboard WHERE isDeleted=0 ORDER BY boardID DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}		
	// School	
	public function ListSchool(){
		$sql   = "
		SELECT schoolmaster.*,schooltype.typeName,schoolboard.boardName,statemaster.stateName,citymaster.cityName FROM schoolmaster 
			LEFT JOIN schooltype ON schoolmaster.typeID=schooltype.typeID 
			LEFT JOIN schoolboard ON schoolmaster.boardID=schoolboard.boardID  
			LEFT JOIN statemaster ON schoolmaster.stateID=statemaster.stateID  
			LEFT JOIN citymaster ON schoolmaster.cityID=citymaster.cityID  
			
		WHERE schoolmaster.isDeleted=0 ORDER BY schoolmaster.schoolID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}	
	public function GetSchool($schoolID){
		$sql   = "SELECT * from schoolmaster WHERE isDeleted=0 AND schoolID=$schoolID";
		$query = $this->db->query($sql);
		return $query->row();
	}	
	public function SaveSchool(){
		$postArray = $this->input->post();
		$postEmail = $postArray["eMail"];
		$mobileNo = $postArray["mobileNo"];
		$sql   = "SELECT * from schoolmaster WHERE isDeleted=0 AND eMail='$postEmail' OR mobileNo='$mobileNo'";
		$query = $this->db->query($sql);
		$schoolArray = $query->result_array();
		//print_r($schoolArray);
		if(empty($schoolArray)){
			$postData = array(
				"schoolName"=>$postArray["schoolName"],
				"typeID"=>$postArray["typeID"],
				"managedBy"=>$postArray["managedBy"],
				"boardID"=>$postArray["boardID"],
				"address"=>$postArray["address"],
				"stateID"=>$postArray["stateID"],
				//"cityID"=>$postArray["cityID"],
				"mobileNo"=>$postArray["mobileNo"],			
				"eMail"=>$postArray["eMail"],
				"password"=> password_hash($postArray["password"], PASSWORD_DEFAULT),
				"isActive"=>$postArray["isActive"],
				"lenderID"=>$postArray["lenderID"],
				"bankName"=>$postArray["bankName"],
				"accountNo"=>$postArray["accountNo"],
				"ifscCode"=>$postArray["ifscCode"],
				"created"=>date('Y-m-d H:i:s')
			);
			$this->db->insert("schoolmaster",$postData);
			return ($this->db->affected_rows() > 0)? true:false;
		}else{
			foreach($schoolArray as $k => $v){
				if($v['mobileNo'] == $postArray["mobileNo"]){
					return array('mobileno' => true,'schoolname' => false,'email'=>false);		
				}else if($v['schoolName'] == $postArray["schoolName"]){
					return array('schoolname' => true, 'email'=>false, 'mobileno' => false);
				}else if($v['eMail'] == $postArray["eMail"]){
					return array('email' => true,'schoolname' => false, 'mobileno' => false);
				}
			}
		}
	}
	public function UpdateSchool(){
		$postArray = $this->input->post();
		$postData = array(
			"schoolName"=>$postArray["schoolName"],
			"eMail"=>$postArray["eMail"],
			"password"=> password_hash($postArray["password"], PASSWORD_DEFAULT),
			"mobileNo"=>$postArray["mobileNo"],
			"typeID"=>$postArray["typeID"],
			"boardID"=>$postArray["boardID"],
			"stateID"=>$postArray["stateID"],
			//"cityID"=>$postArray["cityID"],
			"address"=>$postArray["address"],
			"isActive"=>$postArray["isActive"],
			"lenderID"=>$postArray["lenderID"],
			"bankName"=>$postArray["bankName"],
			"accountNo"=>$postArray["accountNo"],
			"ifscCode"=>$postArray["ifscCode"],
			"updated"=>date('Y-m-d H:i:s')
		);
		if(isset($postArray["password"]) && $postArray["password"]!=""){
			$postData["password"] = password_hash($postArray["password"], PASSWORD_DEFAULT);	
		}				
		$this->db->update("schoolmaster",$postData,array("schoolID"=>$postArray["schoolID"]));
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
		
	}	
	public function DeleteSchool(){
		$postArray = $this->input->post();
		$postData = array(
			"isDeleted"=>1,
			"updated"=>date('Y-m-d H:i:s')
		);
		$this->db->update("schoolmaster",$postData,array("schoolID"=>$postArray["schoolID"]));		
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
	}
	// Student	
	public function ListStudent($schoolID){
		$sql   = "SELECT * FROM studentmaster as s INNER JOIN parentmaster as p on s.parentID = p.parentID WHERE s.isDeleted=0 AND schoolID=".$schoolID." ORDER BY studentID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}	
	public function GetStudent($studentID){
		$sql   = "SELECT * from studentmaster as s INNER JOIN parentmaster as p on s.parentID = p.parentID WHERE s.isDeleted=0 AND studentID=$studentID";
		$query = $this->db->query($sql);
		return $query->row();
	}	
	public function SaveStudent(){
		$postArray = $this->input->post();
		$postData1 = array(
			"pfirstName"=>$postArray["pfirstName"],
			"plastName"=>$postArray["plastName"],
			"pmiddleName"=>$postArray["pmiddleName"],
			"mobileNo"=>$postArray["mobileNo"],
			"accessToken" => 'P'.md5($postArray["mobileNo"]),
			"created"=>date('Y-m-d H:i:s')
		);
		$this->db->insert("parentmaster",$postData1);
		$parentID = $this->db->insert_id();
		
		$postData = array(
			"sfirstName"=>$postArray["sfirstName"],
			"slastName"=>$postArray["slastName"],
			"parentID" => $parentID,
			"standard"=>$postArray["standard"],
			"section"=>$postArray["section"],
			"relationship"=>$postArray["relationship"],
			"gender"=>$postArray["gender"],
			"schoolID"=>$postArray["schoolID"],
			"isActive"=>$postArray["isActive"],
			"isApproved"=> 1,
			"annualFee"=>$postArray["annualFee"],
			"currentPayableFees"=>$postArray["currentPayableFees"],
			"created"=>date('Y-m-d H:i:s')
		);
		$this->db->insert("studentmaster",$postData);
		$studenID = $this->db->insert_id();
		if($this->db->affected_rows() > 0){			
			return true;
			/* $accessToken = 'P'.md5($postArray["mobileNo"]);
			$this->db->update("parentmaster", array("accessToken"=>$accessToken), array("parentID"=>$parentID));
			$this->db->trans_complete();
			if($this->db->trans_status() === FALSE){
				return false;
			}else{ 
				return true;
			}*/		
		}else{
			return false;
		}
	}
	
	public function UpdateStudent(){
		$postArray = $this->input->post();
		$postData = array(
			"pfirstName"=>$postArray["pfirstName"],
			"plastName"=>$postArray["plastName"],
			"pmiddleName"=>$postArray["pmiddleName"],
			"mobileNo"=>$postArray["mobileNo"],
			"updated"=>date('Y-m-d H:i:s')
		);
		$this->db->update("parentmaster",$postData,array("parentID"=>$postArray["parentID"]));
		$this->db->trans_complete();	
		$postData1 = array(
			"sfirstName"=>$postArray["sfirstName"],
			"slastName"=>$postArray["slastName"],
			"standard"=>$postArray["standard"],
			"section"=>$postArray["section"],
			"relationship"=>$postArray["relationship"],
			"gender"=>$postArray["gender"],
			"schoolID"=>$postArray["schoolID"],
			"isActive"=>$postArray["isActive"],
			"isApproved"=>1,
			"annualFee"=>$postArray["annualFee"],
			"currentPayableFees"=>$postArray["currentPayableFees"],
			"updated"=>date('Y-m-d H:i:s')
		);
		$this->db->update("studentmaster",$postData1,array("studentID"=>$postArray["studentID"]));
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
	}	
	public function DeleteStudent(){
		$postArray = $this->input->post();
		$postData = array(
			"isDeleted"=>1,
			"updated"=>date('Y-m-d H:i:s')
		);
		$this->db->update("studentmaster",$postData,array("studentID"=>$postArray["studentID"]));		
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
	}		
	//Added on 11/05/2021
	public function saveSchoolFees()
	{
		$postArray = $this->input->post();
		$postData = array(
			"studentID" => $postArray['studentID'],
			"schoolID" => $postArray['schoolID'],
			"amount" => $postArray['amount'],
			"paymentDate" => $postArray['paymentDate'],
			"created" => date('Y-m-d H:i:s')
		);
		$this->db->insert("schoolstudentpayment",$postData);
		$studenID = $this->db->insert_id();
		if($this->db->affected_rows() > 0){			
			$studentID = $postArray['studentID'];
			$sql   = "SELECT * FROM studentmaster WHERE studentID=$studentID";
			$query = $this->db->query($sql);
			$studentMasterData = $query->row_array();
			$currentpayableFees = $studentMasterData['currentPayableFees'] - $postArray['amount'];
			$postData = array(
				"currentPayableFees"=>$currentpayableFees,
				"updated"=> date('Y-m-d H:i:s')
			);
			$this->db->update("studentmaster",$postData,array("studentID"=>$studentID));		
			$this->db->trans_complete();
			if($this->db->trans_status() === FALSE){
				return false;
			}else{
				return true;
			}
			
		}else{
			return false;
		}	
	}	

	//schoolPayment
	public function ListSchoolPayment($schoolID)
	{
		$sql   = "SELECT * FROM schoolpayment WHERE isDeleted=0 AND schoolID=".$schoolID."";
		$query = $this->db->query($sql);
		return $query->result();
	}
	public function GetSchoolpayment($paymentID){
		$sql   = "SELECT * from schoolpayment WHERE isDeleted=0 AND paymentID=$paymentID";
		$query = $this->db->query($sql);
		$queryData = $query->row();
		$queryData->paymentDate = date('Y-m-d',strtotime($queryData->paymentDate));
		return $queryData;
	}	
	
	public function SaveSchoolpayment()
	{
		$userID = $this->session->userdata('userData')['userID'];
		$postArray = $this->input->post();
		$postData = array(
			"paymentType"=>$postArray["paymentType"],
			"paymentDate"=>$postArray["paymentDate"],
			"schoolID"=>$postArray["schoolID"],
			"paymentAmount"=>$postArray["paymentAmount"],
			"debitCredit"=>$postArray["debitCredit"],
			"narration"=>$postArray["narration"],
			"userID"=> $userID,
			"isDeleted" => 0,
			"created"=>date('Y-m-d H:i:s')
		);
		$this->db->insert("schoolpayment",$postData);
		$paymentID = $this->db->insert_id();
		return $paymentID;
	}
	public function UpdateSchoolpayment(){
		$postArray = $this->input->post();
		$userID = $this->session->userdata('userData')['userID'];
		
		$postData = array(
			"paymentType"=>$postArray["paymentType"],
			"paymentDate"=>$postArray["paymentDate"],
			"schoolID"=>$postArray["schoolID"],
			"paymentAmount"=>$postArray["paymentAmount"],
			"debitCredit"=>$postArray["debitCredit"],
			"narration"=>$postArray["narration"],
			"userID"=> $userID,
			"isDeleted" => 0,
			"updated"=>date('Y-m-d H:i:s')
		);
		$this->db->update("schoolpayment",$postData,array("paymentID"=>$postArray["paymentID"]));
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
	}
	public function DeleteSchoolPayment(){
		$postArray = $this->input->post();
		$postData = array(
			"isDeleted"=>1,
			"updated"=>date('Y-m-d H:i:s')
		);
		$this->db->update("schoolpayment",$postData,array("paymentID"=>$postArray["paymentID"]));		
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
	}		

	//lender
	public function ListLenderDetails()
	{
		$sql   = "SELECT * FROM lendermaster WHERE isDeleted=0";
		$query = $this->db->query($sql);
		return $query->result();
	}

	public function GetlenderDetail($lenderID){
		$sql   = "SELECT * from lendermaster WHERE isDeleted=0 AND lenderID=$lenderID";
		$query = $this->db->query($sql);
		return $query->row();
	}	
	
	public function saveLender()
	{
		$postArray = $this->input->post();
		$postData = array(
			"lenderName"=>$postArray["lenderName"],
			"lenderEmail"=>$postArray["lenderEmail"],
			"lenderMobile"=>$postArray["lenderMobile"],
			"lenderLimit"=>$postArray["lenderLimit"],
			"isDeleted" => 0,
			"created"=>date('Y-m-d H:i:s')
		);
		$this->db->insert("lendermaster",$postData);
		$paymentID = $this->db->insert_id();
		return $paymentID;
	}
	public function updateLender(){
		$postArray = $this->input->post();
		$postData = array(
			"lenderName"=>$postArray["lenderName"],
			"lenderEmail"=>$postArray["lenderEmail"],
			"lenderMobile"=>$postArray["lenderMobile"],
			"lenderLimit"=>$postArray["lenderLimit"],
			"isDeleted" => 0,
			"updated"=>date('Y-m-d H:i:s')
		);

		$this->db->update("lendermaster",$postData,array("lenderID"=>$postArray["lenderID"]));
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
	}
	public function deleteLender(){
		$postArray = $this->input->post();
		$postData = array(
			"isDeleted"=>1,
			"updated"=>date('Y-m-d H:i:s')
		);
		$this->db->update("lendermaster",$postData,array("lenderID"=>$postArray["lenderID"]));		
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
	}

	// Finance	
	public function GetFinance($schoolID){
		$sql   = "SELECT * from schoolfinance WHERE isDeleted=0 AND schoolID=".$schoolID." LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row();
	}	
	public function GetpersonalFinance($schoolID){
		$sql   = "SELECT * from personalfinance WHERE isDeleted=0 AND schoolID=".$schoolID." LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row();
	}	
	
	public function GetstaffFinance($schoolID)
	{
		$sql   = "SELECT * from stafffinance WHERE isDeleted=0 AND schoolID=".$schoolID." LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row();
	}

	public function SaveFinance(){
		$postArray = $this->input->post();
		$emiLevel = implode(",",$postArray['emiLevel']);
		$postData = array(
			"retaintion"=>$postArray["retaintion"],
			"maxLoan"=>$postArray["maxLoan"],
			"emiLevel"=> $emiLevel,
			"fees"=>$postArray["fees"],
			"interestType"=>$postArray["interestType"],
			"interestRate"=>$postArray["interestRate"],
			"loanGaps"=>$postArray["loanGaps"],
			"collectionCharges" =>$postArray["collectionCharges"],
			"delaynoofDays" =>$postArray["delaynoofDays"],
			"perdaycharge" =>$postArray["perdaycharge"],
			"processingFeesgst" =>$postArray["processingFeesgst"],
			"outstandingloanperChild" =>$postArray["outstandingloanperChild"],
			"advancedEmi" =>$postArray["advancedEmi"],
			"gstType"=>$postArray["gstType"],
			"gst"=>$postArray["gst"],
			"igst"=>$postArray["igst"],	
			"loancapAmount"=>$postArray["loancapAmount"],	
			"schoolID"=>$postArray["schoolID"],
			"isActive"=>1,
			"created"=>date('Y-m-d H:i:s')
		);
		$this->db->insert("schoolfinance",$postData);
		return ($this->db->affected_rows() > 0)? true:false;
	}
	public function UpdateFinance(){
		$postArray = $this->input->post();
		$emiLevel = implode(",",$postArray['emiLevel']);
		$postData = array(
			"retaintion"=>$postArray["retaintion"],
			"maxLoan"=> $postArray["maxLoan"],
			"emiLevel"=> $emiLevel,
			"fees"=> $postArray["fees"],
			"interestType"=>$postArray["interestType"],
			"interestRate"=>$postArray["interestRate"],
			"loanGaps"=>$postArray["loanGaps"],
			"collectionCharges" =>$postArray["collectionCharges"],
			"delaynoofDays" =>$postArray["delaynoofDays"],
			"perdaycharge" =>$postArray["perdaycharge"],
			"processingFeesgst" =>$postArray["processingFeesgst"],
			"outstandingloanperChild" =>$postArray["outstandingloanperChild"],
			"advancedEmi" =>$postArray["advancedEmi"],
			"gstType"=>$postArray["gstType"],
			"loancapAmount"=>$postArray["loancapAmount"],	
			"gst"=>$postArray["gst"],
			"igst"=>$postArray["igst"],									
			"schoolID"=>$postArray["schoolID"],
			"isActive"=>1,
			"updated"=>date('Y-m-d H:i:s')
		);
		
		$this->db->update("schoolfinance",$postData,array("financeID"=>$postArray["financeID"]));
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
	}

	public function SavepersonalFinance()
	{
		$postArray = $this->input->post();
		$postData = array(
			"schoolID" =>$postArray["schoolID"],
			"maxLoan"=>$postArray["maxLoan"],
			"installmentType"=>$postArray["installmentType"],
			"interestRate"=>$postArray["interestRate"],
			"interestType"=>$postArray["interestType"],
			"noofInstallments"=>$postArray["noofInstallments"],
			"advancedEmi" =>$postArray["advancedEmi"],
			"loanEligibilitypostfeeloan" =>$postArray["loanEligibilitypostfeeloan"],
			"previousLoansuccessper" =>$postArray["previousLoansuccessper"],
			"isDeleted"=>0,
			"created"=>date('Y-m-d H:i:s')
		);
		$this->db->insert("personalfinance",$postData);
		return ($this->db->affected_rows() > 0)? true:false;
	}
	public function UpdatepersonalFinance(){
		$postArray = $this->input->post();
		$postData = array(
			"schoolID" =>$postArray["schoolID"],
			"maxLoan"=>$postArray["maxLoan"],
			"installmentType"=>$postArray["installmentType"],
			"interestRate"=>$postArray["interestRate"],
			"interestType"=>$postArray["interestType"],
			"noofInstallments"=>$postArray["noofInstallments"],
			"advancedEmi" =>$postArray["advancedEmi"],
			"loanEligibilitypostfeeloan" =>$postArray["loanEligibilitypostfeeloan"],
			"previousLoansuccessper" =>$postArray["previousLoansuccessper"],
			"isDeleted"=>0,
			"updated"=>date('Y-m-d H:i:s')
		);
		$this->db->update("personalfinance",$postData,array("personalfinanceID"=>$postArray["personalfinanceID"]));
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
	}

	public function SavestaffFinance()
	{
		$postArray = $this->input->post();
		$emiLevel = implode(",",$postArray['emiLevel']);
		$postData = array(
			"schoolID" =>$postArray["schoolID"],
			"maxLoan"=>$postArray["maxLoan"],
			"initialLoanAmount"=>$postArray["initialLoanAmount"],
			"interestRate"=>$postArray["interestRate"],
			"interestType"=>$postArray["interestType"],
			"emiLevel"=>$emiLevel,
			"advancedEmi" =>$postArray["advancedEmi"],
			"delayinrepeatloan" =>$postArray["delayinrepeatloan"],
			"repeatLoanincreaseAmountper" =>$postArray["repeatLoanincreaseAmountper"],
			"repeatloanEmiRepaid" =>$postArray["repeatloanEmiRepaid"],
			"isDeleted"=>0,
			"created"=>date('Y-m-d H:i:s')
		);
		$this->db->insert("stafffinance",$postData);
		return ($this->db->affected_rows() > 0)? true:false;
	}
	public function UpdatestaffFinance()
	{
		$postArray = $this->input->post();
		$emiLevel = implode(",",$postArray['emiLevel']);
		$postData = array(
			"schoolID" =>$postArray["schoolID"],
			"maxLoan"=>$postArray["maxLoan"],
			"initialLoanAmount"=>$postArray["initialLoanAmount"],
			"interestRate"=>$postArray["interestRate"],
			"interestType"=>$postArray["interestType"],
			"emiLevel"=> $emiLevel,
			"advancedEmi" =>$postArray["advancedEmi"],
			"delayinrepeatloan" =>$postArray["delayinrepeatloan"],
			"repeatLoanincreaseAmountper" =>$postArray["repeatLoanincreaseAmountper"],
			"repeatloanEmiRepaid" =>$postArray["repeatloanEmiRepaid"],
			"isDeleted"=>0,
			"created"=>date('Y-m-d H:i:s')
		);
		$this->db->update("stafffinance",$postData,array("stafffinanceID"=>$postArray["stafffinanceID"]));
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
	}

	public function saveApproveSchoolStatus(){
		$postArray = $this->input->post();
		$postData = array(
				"isApprovedbySchool"=> 1,
				"approvedSchoolDate" => date('Y-m-d H:i:s'),
				"updated" => date('Y-m-d H:i:s')
		);
		$this->db->update("approvefiles",$postData,array("fileName"=>$postArray['fileName']));		
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
	}

	public function saveBulkData($sheetData,$uploadedFilename,$isAdmin='',$approvedReasonbyAdmin=''){
		//$schoolID = $this->session->userdata('schoolData')["schoolID"];
		$sql   = 'SELECT * FROM approvefiles WHERE `fileName` = "'.$uploadedFilename.'"'; 
		$query = $this->db->query($sql);	
		$FilesRecord =  $query->row();
		$schoolID = $FilesRecord->schoolID;
		array_shift($sheetData); //Removed first row of the Excel sheet i.e First Element
		$i = 0;
	
		if($isAdmin == 1){
			if(!empty($sheetData)){
				foreach($sheetData as $row){
					//$parentSQL = "SELECT * FROM parentmaster WHERE pfirstName LIKE '%".strtolower($row['F'])."%' AND plastName LIKE '%".strtolower($row['G'])."%' AND mobileNo=".$row['I']."";
					if(!empty($row['J'])){
							$parentSQL = "SELECT * FROM parentmaster WHERE mobileNo=".$row['J']."";
							$queryParent = $this->db->query($parentSQL);
							$parentArray = $queryParent->row_array();
							if(empty($parentArray)){
								$parentID = $this->saveParent(array($row['F'],$row['G'],$row['H'],$row['J'],$row['M']));
								$data['parentID']  = $parentID;
								$data['sfirstName']  = $row['A'];
								$data['slastName']  = $row['B'];
								$data['standard']  = $row['C'];
								$data['section']  = $row['D'];
								$data['relationship']  = $row['E'];
								$data['gender']  = $row['I'];
								$data['schoolID']  = $schoolID;
								$data['isDeleted']  = 0;
								$data['isActive']  = 1;
								$data['isApproved']  = 1;
								$data['annualFee']  = str_replace(",", "", $row['K']);
								$data['currentPayableFees']  = str_replace(",", "", $row['L']);
								$data['created']  = date('Y-m-d H:i:s');
								
								$this->db->insert("studentmaster",$data);
								$studentID = $this->db->insert_id();	
								$this->saveOutstandingAmountLog(array($studentID,$data['created'],$data['currentPayableFees']));	
							}else{
								$checkData = $this->checkpayableFeeschange(array($row['A'],$row['B'],$row['J']));
								$data['parentID']  = $parentArray['parentID'];
								$data['sfirstName']  = $row['A'];
								$data['slastName']  = $row['B'];
								$data['standard']  = $row['C'];
								$data['section']  = $row['D'];
								$data['relationship']  = $row['E'];
								$data['gender']  = $row['I'];
								$data['schoolID']  = $schoolID;
								$data['isDeleted']  = 0;
								$data['isActive']  = 1;
								$data['isApproved']  = 1;
								$data['annualFee']  = str_replace(",", "", $row['K']);
								$data['currentPayableFees']  = str_replace(",", "", $row['L']);
								
								if(!empty($checkData)){
									$data['updated']  = date('Y-m-d H:i:s');
									$this->db->update("studentmaster",$data,array("studentID"=>$checkData["studentID"]));
									$this->db->trans_complete();	
									$this->saveOutstandingAmountLog(array($checkData['studentID'],$data['updated'],$data['currentPayableFees']));
								}else{
									$data['created']  = date('Y-m-d H:i:s');
									$this->db->insert("studentmaster",$data);
									$studentID = $this->db->insert_id();	
									$this->saveOutstandingAmountLog(array($studentID,$data['created'],$data['currentPayableFees']));
								}
							}
	
						$i++;
					}
					
				}
				/* if(!empty($sheetData)){
					foreach($sheetData as $row){
						if(!empty($row['A']) && !empty($row['B']) && !empty($row['C']) && !empty($row['D'])){
							$data[$i]['sfirstName']  = $row['A'];
							$data[$i]['slastName']  = $row['B'];
							$data[$i]['standard']  = $row['C'];
							$data[$i]['section']  = $row['D'];
							$data[$i]['relationship']  = $row['E'];
							$data[$i]['pfirstName']  = $row['F'];
							$data[$i]['plastName']  = $row['G'];
							$data[$i]['gender']  = $row['H'];
							$data[$i]['mobileNo']  = $row['I'];
							$data[$i]['schoolID']  = $schoolID;
							$data[$i]['isDeleted']  = 0;
							$data[$i]['isActive']  = 1;
							$data[$i]['isApproved']  = 1;
							$data[$i]['annualFee']  = $row['J'];
							$data[$i]['currentPayableFees']  = $row['K'];
							$data[$i]['accessToken']  = 'P'.md5($row['I']);
							$i++;
						}
					} //end of foreach
					$this->db->insert_batch("studentmaster",$data);
					$noofRecords = $i;
					$postData = array(
						"approvedReasonbyAdmin"=>$approvedReasonbyAdmin,
						"isApprovedbyAdmin"=> 1,
						"updated" => date('Y-m-d H:i:s')
					);
					$this->db->update("approvefiles",$postData,array("fileName"=>$uploadedFilename));		
					
					$this->db->trans_complete();
					return $noofRecords;
				}*/
				$postData = array(
					"approvedReasonbyAdmin"=>$approvedReasonbyAdmin,
					"isApprovedbyAdmin"=> 1,
					"approvedAdminDate" => date('Y-m-d H:i:s'),
					"updated" => date('Y-m-d H:i:s')
				);
				$this->db->update("approvefiles",$postData,array("fileName"=>$uploadedFilename));		
				$this->db->trans_complete();
				$noofRecords = $i;
				return $noofRecords;
			} // end of sheet data if
		} //end of if admin 1	
	}

	function saveParent($postArray){
		$postData = array(
			"pfirstName"=> $postArray[0],
			"pmiddleName" => $postArray[1],
			"pLastName"=> $postArray[2],
			"mobileNo"=> $postArray[3],		
			"accessToken" => 'P'.md5($postArray[3]),
			"recommendLoan" => $postArray[4],
			"created"=>date('Y-m-d H:i:s')
		);
		$this->db->insert("parentmaster",$postData);
		$parentID = $this->db->insert_id();
		return $parentID;
	}

	function checkpayableFeeschange($row){
		$checkSQL = "SELECT * FROM studentmaster as sm INNER JOIN parentmaster as pm On pm.parentID=sm.parentID WHERE sm.sfirstName LIKE '%".strtolower($row[0])."%' AND sm.slastName LIKE '%".strtolower($row[1])."%' AND pm.mobileNo=".$row[2]."";
		$querycheck = $this->db->query($checkSQL);
		return $querycheck->row_array();
	}

	function saveOutstandingAmountLog($postArray){
		$postData = array(
			"studentID"=> $postArray[0],
			"monthYear"=> $postArray[1],
			"openingbalance"=> $postArray[2],			
			"created"=>date('Y-m-d H:i:s')
		);
		$this->db->insert("outstandingamountlog",$postData);
		$parentID = $this->db->insert_id();
		return $parentID;
	}

	public function uploadFile($dir,$file){

		$oldFile = '';
		
		if(isset($_REQUEST[$file."O"]) && trim($_REQUEST[$file."O"])!=""){

			$oldFile = $_REQUEST[$file."O"];

		}		

		if(isset($_FILES[$file]["name"]) && trim($_FILES[$file]["name"])!=""){
			
			if(!is_dir('uploads/'.$dir)){
				
				mkdir('./uploads/'.$dir, 0777, true);
			}
			$config['upload_path']    = './uploads/'.$dir;
			
			$config['allowed_types']        = 'xls|xlsx|pdf';

			$config['max_size']             = 10000;

			$config['max_width']            = 9024;

			$config['max_height']           = 7068;

			$new_name = time();			

			$config['file_name'] = $new_name;

			$this->upload->initialize($config);

			$this->load->library('upload', $config);

			if(!$this->upload->do_upload($file)){

				return "";

			}else{

				$fileData = $this->upload->data();

				if($oldFile!="" && is_file(($config['upload_path'].'/'.$oldFile))){

					unlink($config['upload_path'].'/'.$oldFile);

				}

				

				return $fileData["file_name"];

			}

		}else{

			return $oldFile;

		}

	}
	public function UploadFileonly($fileData)
	{
		$postArray = $this->input->post();
		$schoolID = $this->session->userdata('schoolData')["schoolID"];
		$imageName = $this->uploadFile('approved','fileNameUpload');	 //1st folder name 2nd filename

		$fileName = "uploads/approved/".$imageName;
		require_once APPPATH . "/third_party/PHPExcel.php";
		$file_type	= PHPExcel_IOFactory::identify($fileName);
		$objReader	= PHPExcel_IOFactory::createReader($file_type);
		$objPHPExcel = $objReader->load($fileName);
		$sheet_data	= $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		//echo "<pre>";
		array_shift($sheet_data);
		$message = array();
		foreach($sheet_data as $row){
			if(empty($row['J']) || strlen($row['J']) != 10 || !is_numeric($row['J']) ){
				$message[$row['A']." ".$row['B']][] = "Mobile No is Invalid or blank";
			}
		}
		if(count($message) > 0){
			return $message;
		}
		
		if(!empty($imageName)){
				$postData = array(
					"filename"=> $imageName,
					"isApprovedbySchool"=>0,
					"month"=>$postArray['month'],
					"year"=>$postArray['year'],
					"created"=>date('Y-m-d H:i:s'),
					"schoolID" => $schoolID,
					"updated"=>date('Y-m-d H:i:s')
				);
				$this->db->insert("approvefiles",$postData);
				return ($this->db->affected_rows() > 0) ? $this->db->insert_id():false;
			}else{
				return "something went wrong. please try again";
		}
		
		
	}

	public function getFileDetails($schoolID,$fromAdmin='')
	{	
		if($fromAdmin == 1){
			$sql   = "SELECT a.*,sm.schoolName, DATE_FORMAT(a.created, '%D %M %Y %h:%i:%s') as created from approvefiles as a INNER JOIN schoolmaster as sm on sm.schoolID=a.schoolID WHERE a.`isApprovedbyAdmin`= 0 AND a.isDeleted=0 ORDER BY created DESC";
		}else{
			$sql   = "SELECT a.*,sm.schoolName, DATE_FORMAT(a.created, '%D %M %Y %h:%i:%s') as created from approvefiles as a INNER JOIN schoolmaster as sm on sm.schoolID=a.schoolID WHERE a.schoolID=".$schoolID." AND a.isDeleted=0 AND a.`isApprovedbySchool`= 0 ORDER BY created DESC";	
		}	
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function getFileDetailspastapproved()
	{
		$sql   = "SELECT a.*,sm.schoolName, DATE_FORMAT(a.created, '%D-%M-%Y %h:%i:%s') as created from approvefiles as a INNER JOIN schoolmaster as sm on sm.schoolID=a.schoolID WHERE a.`isApprovedbyAdmin`= 1 ORDER BY created DESC";
		
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	//staff code
	public function getstaffDetails($schoolID)
	{
		$sql   = "SELECT * from staffmaster WHERE isDeleted=0 AND schoolID=".$schoolID."";
		$query = $this->db->query($sql);
		return $query->result_array();
	}	
	public function SaveStaff(){
		$postArray = $this->input->post();
		$postData = array(
			"schoolID"=>$postArray["schoolID"],
			"staffFirstName"=>$postArray["staffFirstName"],
			"staffLastName"=>$postArray["staffLastName"],
			"address"=>$postArray["address"],
			"stateID"=>$postArray["stateID"],
			"cityID"=>$postArray["cityID"],
			"mobileNo"=>$postArray["mobileNo"],			
			"eMail"=>$postArray["eMail"],
			"monthlySalary"=>$postArray["monthlySalary"],
			"joiningDate"=>$postArray["joiningDate"],
			"password"=> password_hash($postArray["password"], PASSWORD_DEFAULT),
			"isActive"=>$postArray["isActive"],
			"created"=>date('Y-m-d H:i:s')
		);
		$this->db->insert("staffmaster",$postData);
		$staffID = $this->db->insert_id();
		if($this->db->affected_rows() > 0){			
			$accessToken = 'S'.random_string('unique').$staffID;
			$this->db->update("staffmaster",array("accessToken"=>$accessToken),array("staffID"=>$staffID));
			$this->db->trans_complete();
			if($this->db->trans_status() === FALSE){
				return false;
			}else{
				return true;
			}			
		}else{
			return false;
		}
	}
	public function UpdateStaff(){
		$postArray = $this->input->post();
		$postData = array(
			"schoolID"=>$postArray["schoolID"],
			"staffFirstName"=>$postArray["staffFirstName"],
			"staffLastName"=>$postArray["staffLastName"],
			"eMail"=>$postArray["eMail"],
			"password"=> password_hash($postArray["password"], PASSWORD_DEFAULT),
			"mobileNo"=>$postArray["mobileNo"],
			"stateID"=>$postArray["stateID"],
			"cityID"=>$postArray["cityID"],
			"address"=>$postArray["address"],
			"monthlySalary"=>$postArray["monthlySalary"],
			"joiningDate"=>$postArray["joiningDate"],
			"isActive"=>$postArray["isActive"],
			"updated"=>date('Y-m-d H:i:s')
		);
		if(isset($postArray["password"]) && $postArray["password"]!=""){
			$postData["password"] = password_hash($postArray["password"], PASSWORD_DEFAULT);	
		}				
		$this->db->update("staffmaster",$postData,array("staffID"=>$postArray["staffID"]));
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
	}	
	public function DeleteStaff(){
		$postArray = $this->input->post();
		$postData = array(
			"isDeleted"=>1,
			"updated"=>date('Y-m-d H:i:s')
		);
		$this->db->update("staffmaster",$postData,array("staffID"=>$postArray["staffID"]));		
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
	}
	public function GetStaff($staffID){
		$sql   = "SELECT * from staffmaster WHERE isDeleted=0 AND staffID=$staffID";
		$query = $this->db->query($sql);
		return $query->row();
	}
	// Designation	
	public function ListDesignation(){
		$sql   = "SELECT * FROM designationmaster WHERE isDeleted=0 ORDER BY designationID DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}	
	public function GetDesignation($designationID){
		$sql   = "SELECT * from designationmaster WHERE isDeleted=0 AND designationID=$designationID";
		$query = $this->db->query($sql);
		return $query->row();
	}	
	public function SaveDesignation(){
		$postArray = $this->input->post();
		$postData = array(
			"designationName"=>$postArray["designationName"],
			"description"=>$postArray["description"],
			"isActive"=>$postArray["isActive"],
			"created"=>date('Y-m-d H:i:s')
		);
		$this->db->insert("designationmaster",$postData);
		return ($this->db->affected_rows() > 0)? true:false;
	}
	public function UpdateDesignation(){
		$postArray = $this->input->post();
		$postData = array(
			"designationName"=>$postArray["designationName"],
			"description"=>$postArray["description"],
			"isActive"=>$postArray["isActive"],
			"updated"=>date('Y-m-d H:i:s')
		);
		$this->db->update("designationmaster",$postData,array("designationID"=>$postArray["designationID"]));
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
	}	
	public function DeleteDesignation(){
		$postArray = $this->input->post();
		$postData = array(
			"isDeleted"=>1,
			"updated"=>date('Y-m-d H:i:s')
		);
		$this->db->update("designationmaster",$postData,array("designationID"=>$postArray["designationID"]));		
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
	}	
	public function postReason($postArray)
	{	
		$postData = array(
			"isApprovedbySchool"=> 0,
			"updated" => date('Y-m-d H:i:s'),
			"reason" => trim($postArray['reason'])
		);
		$this->db->update("approvefiles", $postData, array("fileName"=> $postArray["fileName"]));		
		$this->db->trans_complete();	
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
	}

	public function updateApprovedStatus()
	{
		$postArray = $this->input->post();
		$postData = array(
			"isApproved"=> $postArray['isApproved'],
			"updated" => date('Y-m-d H:i:s'),
			"approvedadminReason" => trim($postArray['approvedReasonbyAdmin'])
		);
		$this->db->update("loandetails", $postData, array("loanID"=> $postArray["loanID"]));		
		$this->db->trans_complete();	
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
	}

	public function checkfileApprovedStatus($fileName){
		$sql   = 'SELECT * FROM approvefiles WHERE `fileName` = "'.$fileName.'"'; 
		$query = $this->db->query($sql);	
		return $query->row();
	}

	//Loan Data page admin & school
	public function getLoankycData()
	{
		$postArray = $this->input->post();
		$schoolID = $postArray['schoolID'];
		if(!empty($schoolID)){
			//$sql = "SELECT sk.*,ld.*,s.*, concat(s.sfirstName,' ',s.slastName) studentName,  concat(address,',',location,',',city) as Address, s.currentPayableFees,s.studentID as masterStudentID FROM `studentkycdetails` as sk INNER JOIN studentmaster s on s.studentID = sk.studentID INNER JOIN loandetails as ld ON sk.studentID=ld.studentID WHERE s.schoolID=$schoolID";
			$sql = "SELECT ld.*, s.*, concat(s.sfirstName,' ',s.slastName) studentName, concat(p.pfirstName,' ',p.plastName) parentName FROM studentmaster s INNER JOIN parentmaster as p ON p.parentID=s.parentID  INNER JOIN loandetails as ld ON s.studentID=ld.studentID WHERE s.schoolID=$schoolID";
			$query = $this->db->query($sql);	
			return $query->result_array();
		}else{
			return false;
		}
	}
	
	public function getLoankycDatadetails($studentID){
		if(!empty($studentID)){
			$sql = "SELECT sk.*,ld.*, concat(s.sfirstName,' ',s.slastName) studentName,concat(s.pfirstName,' ',s.plastName) parentName,s.mobileNo, concat(address,',',location,',',city) as Address, s.currentPayableFees FROM `studentkycdetails` as sk INNER JOIN studentmaster s on s.studentID = sk.studentID LEFT JOIN loandetails as ld ON sk.studentID=ld.studentID WHERE s.studentID=$studentID";
			$query = $this->db->query($sql);	
			return $query->row_array();
		}else{
			return false;
		}
	}

	public function getLoanadminkycData()
	{
		$sql = "SELECT ld.*, s.*,DATE_FORMAT(ld.created, '%D-%M-%Y %h:%i:%s')as loandate , concat(s.sfirstName,' ',s.slastName) studentName, concat(p.pfirstName,' ',p.plastName) parentName FROM studentmaster s INNER JOIN parentmaster as p ON p.parentID=s.parentID  INNER JOIN loandetails as ld ON s.studentID=ld.studentID";
		$query = $this->db->query($sql);	
		return $query->result_array();
	}
	


	//school Reports
	public function getStudentReportData()
	{
		$schoolID = $this->session->userdata('schoolData')["schoolID"];
		$sql   = "SELECT * FROM studentmaster as s INNER JOIN parentmaster as p ON p.parentID=s.parentID WHERE schoolID = $schoolID"; 
		$query = $this->db->query($sql);
		return $query->result_array();	
	}
	public function getStaffReportData()
	{
		$schoolID = $this->session->userdata('schoolData')["schoolID"];
		$sql   = "SELECT * FROM staffmaster WHERE schoolID = $schoolID"; 
		$query = $this->db->query($sql);
		return $query->result_array();	
	}
	public function getSchoolPaymentData()
	{
		$schoolID = $this->session->userdata('schoolData')["schoolID"];
		$sql   = "SELECT * FROM schoolpayment WHERE schoolID = $schoolID"; 
		$query = $this->db->query($sql);
		return $query->result_array();	
	}

	public function getPaymentsubReportData($paymentID){
		$sql   = "SELECT * FROM schoolpayment WHERE paymentID = $paymentID"; 
		$schoolID = $this->session->userdata('schoolData')["schoolID"];
		$query = $this->db->query($sql);
		$schoolpaymentData = $query->row_array();	
		$paymentDate = $schoolpaymentData["paymentDate"];
		$sql2 = "SELECT * FROM `loandetails` as ld INNER JOIN studentmaster sm ON sm.studentID=ld.studentID where ld.created LIKE '%$paymentDate%' AND sm.schoolID=$schoolID";
		$query2 = $this->db->query($sql2);
		$resultArray = $query2->result_array();	
		return $resultArray;
	}

	public function getFeesReportData(){
		$result = array();
		$schoolID = $this->session->userdata('schoolData')["schoolID"];
		$sqlFees = "SELECT sum(ld.loanAmount) as LoanAmount, sm.*,ld.created as loanDate,ld.*,p.*,sm.studentID FROM `studentmaster` as sm INNER JOIN parentmaster as p on p.parentID=sm.parentID LEFT JOIN loandetails ld ON sm.studentID=ld.studentID WHERE sm.schoolID= $schoolID GROUP BY sm.studentID";
		$queryFees = $this->db->query($sqlFees);
		$result = $queryFees->result_array();	
		foreach($result as $row){
			$studentID = $row['studentID'];
			$sql = "SELECT sum(amount) as amount, studentID FROM `schoolstudentpayment` WHERE studentID='".$studentID."' GROUP BY studentID";
			$queryDirectPayment = $this->db->query($sql);
			$resultData = $queryDirectPayment->row_array();	
			//if(!empty($resultData['amount'])){
			$row['DirectAmount'] = $resultData['amount']; 	
			$resultArray[] = $row;
		}
		return $resultArray;
	}

	public function getpaidFeesSubreport($studentID){
		
		$sqlFees1 = "SELECT paymentDate, '2' as loanType, amount as loanAmount FROM `schoolstudentpayment` WHERE studentID= $studentID";
		$queryFees1 = $this->db->query($sqlFees1);
		$schoolstudentpaymentData = $queryFees1->result_array();	
		
		$sqlFees2 = "SELECT created as paymentDate, loanType, loanAmount FROM `loandetails` WHERE studentID= $studentID";
		$queryFees2 = $this->db->query($sqlFees2);
		$loandetailsData = $queryFees2->result_array();	
		$data = array_merge($loandetailsData,$schoolstudentpaymentData);
		return $data;
	}

	public function getLoanReport($loanType)
	{
		$schoolID = $this->session->userdata('schoolData')["schoolID"];
		if($loanType == 0){
			$select = '';
			$innerjoin = "";
			$where = "ld.loanStatus IN (0,1)";
			$where2 = "";
		}else{
			$select = ',e.emiStatus,e.noofdays,e.perdaycharges';
			$innerjoin = "LEFT JOIN emischedule as e ON e.loanID=ld.loanID";
			$where = "ld.loanStatus IN(2,3)";
			$where2 = "AND e.emiStatus=2";
		}
		$sqlLoan = "SELECT sm.*,ld.created as loanDate,ld.*,p.* $select FROM `studentmaster` as sm INNER JOIN parentmaster as p on p.parentID=sm.parentID LEFT JOIN loandetails ld ON sm.studentID=ld.studentID $innerjoin WHERE ld.loanType=0 AND sm.schoolID=$schoolID $where2 AND $where";
		$queryLoan = $this->db->query($sqlLoan);
		$resultArray = $queryLoan->result_array();		
								
		return $resultArray;
	}
	public function getfeeadjustmentreport()
	{
		$resultArray = array();
		$resultEmis = array();
		$sqlemi = "SELECT * FROM `emischedule` where emiStatus = 1";
		$queryemi = $this->db->query($sqlemi);
		$result = $queryemi->result_array();
		foreach($result as $val){
			$loanID = $val['loanID'];
			$sqlemi2 = "SELECT * FROM `emischedule` emi INNER JOIN studentmaster sm on sm.studentID=emi.studentID WHERE emi.loanID=$loanID and emi.emiStatus=2";
			$queryemi2 = $this->db->query($sqlemi2);
			$resultEmidetails = $queryemi2->row_array();	
			if(!empty($resultEmidetails)){
				$sqlemi3 = "SELECT SUM(emi.emiAmount) as amountNotoverdue FROM `emischedule` emi WHERE emi.loanID=$loanID and emi.emiStatus NOT IN(1,2)";
				$queryemi3 = $this->db->query($sqlemi3);
				$resultnotOverdue = $queryemi3->row_array();
				$resultEmidetails['amountNotoverdue'] = $resultnotOverdue['amountNotoverdue'];	
				$resultArray[] = $resultEmidetails;			
			}
		}

		return $resultArray;
	}

	public function uploadMOUfile($fileName,$schoolID)
	{	
		$mouFileName = $this->uploadFile('mou','moufileUpload');
		if(!empty($mouFileName)){
			$postData = array(
				"moufile"=> $mouFileName,
				"schoolID"=> $schoolID,
				"created"=>date('Y-m-d H:i:s')
			);
			$this->db->insert("mou",$postData);
			return ($this->db->affected_rows() > 0)? true:false;
		}else{
			return "something went wrong. please try again";
		}
	
	} 
	public function getListofMou(){
		$postArray = $this->input->post();
		$schoolID = $postArray['schoolID'];
		$sql   = "SELECT m.*, DATE_FORMAT(m.created, '%D %M %Y') as moudate, sm.* FROM mou as m INNER JOIN schoolmaster as sm on sm.schoolID=m.schoolID WHERE m.schoolID=$schoolID";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function getSchoolView($schoolID){
		$sql   = "SELECT scm.*,stm.stateName,st.typeName,sb.boardName FROM `schoolmaster` as scm INNER JOIN statemaster as stm on stm.stateID=scm.stateID INNER JOIN schooltype st on st.typeID=scm.typeID INNER JOIN schoolboard sb on sb.boardID=scm.boardID where scm.schoolID=$schoolID";
		$query = $this->db->query($sql);
		return $query->row_array();
	}

	public function getLenderDetails($schoolID,$lenderID){
		$sql   = "SELECT ld.*, sm.*, pm.*,scm.*,ld.address as loanAddress, ld.created as loandate,lm.lenderName FROM `loandetails` as ld INNER JOIN studentmaster as sm on sm.studentID=ld.studentID INNER JOIN parentmaster as pm on pm.parentID=sm.parentID INNER JOIN schoolmaster as scm on scm.schoolID=sm.schoolID INNER JOIN lendermaster as lm on scm.lenderID=lm.lenderID WHERE scm.schoolID=$schoolID AND ld.lenderID=$lenderID AND ld.loanType=0 and ld.sentLender=1 AND ld.isApproved IN(1)";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	/* 
	public function loanAllschool(){
		$sql = "SELECT count(ld.loanID),scm.schoolName,scm.schoolID,lm.lenderName FROM `loandetails` as ld INNER JOIN `studentmaster` as sm on sm.studentID=ld.studentID INNER JOIN schoolmaster as scm on scm.schoolID=sm.schoolID INNER JOIN lendermaster as lm on scm.lenderID=lm.lenderID WHERE ld.isApproved IN (0) and ld.loanType=0 and ld.isKyc <> 2 GROUP BY scm.schoolID";
		$query = $this->db->query($sql);
		$sqlOneResult = $query->result_array();
		$sql2 = "SELECT count(ld.loanID) as lenderCount, scm.schoolName,scm.schoolID,lm.lenderName, scm.schoolID FROM `loandetails` as ld INNER JOIN `studentmaster` as sm on sm.studentID=ld.studentID INNER JOIN schoolmaster as scm on scm.schoolID=sm.schoolID INNER JOIN lendermaster as lm on scm.lenderID=lm.lenderID WHERE ld.isApproved = 5 and ld.loanType=0";
		$query2 = $this->db->query($sql2);
		$sqltwoResult = $query2->result_array();
		print_r($sqlOneResult); print_r($sqltwoResult); die;
		$result = array();
		foreach($sqlOneResult as $k => $v){
			foreach($sqltwoResult as $k1 => $v1){
				if($v['schoolID'] == $v1['schoolID']){
					$v['lenderCount'] = $v1['lenderCount'];
				}else{
					$v1['lenderCount'] = 0;
				}
			}
			$result[] = $v;
		}
		if(empty($sqlOneResult)){
			$result = $sqltwoResult;
		}
		return $result;

	}
	*/

	public function loanAllschool(){
		$resultArray = array();
		$sql1 = "SELECT schoolmaster.schoolID,schoolmaster.schoolName,lendermaster.lenderName,loandetails.* FROM loandetails INNER JOIN studentmaster on loandetails.studentID=studentmaster.studentID INNER JOIN schoolmaster on studentmaster.schoolID=schoolmaster.schoolID INNER JOIN lendermaster on loandetails.lenderID=lendermaster.lenderID WHERE loandetails.loanType=0 GROUP BY schoolmaster.schoolID,loandetails.lenderID ORDER BY schoolmaster.schoolName";
		$query1 = $this->db->query($sql1);
		$schoolResult = $query1->result_array();
		foreach($schoolResult as $row){
			//Application count
			$sqlApp ="SELECT count(loandetails.loanID) as applicationCount FROM loandetails INNER JOIN studentmaster on loandetails.studentID=studentmaster.studentID INNER JOIN schoolmaster on studentmaster.schoolID=schoolmaster.schoolID INNER JOIN lendermaster on loandetails.lenderID=lendermaster.lenderID WHERE loandetails.loanType=0 AND loandetails.isApproved=0 AND schoolmaster.schoolID=".$row['schoolID']." AND loandetails.lenderID=".$row['lenderID']." GROUP BY schoolmaster.schoolID,loandetails.lenderID ORDER BY schoolmaster.schoolName";
			$queryApp = $this->db->query($sqlApp);
			$sqlAppResult = $queryApp->row_array();

			$sqllender ="SELECT count(loandetails.loanID) as lenderCount FROM loandetails INNER JOIN studentmaster on loandetails.studentID=studentmaster.studentID INNER JOIN schoolmaster on studentmaster.schoolID=schoolmaster.schoolID INNER JOIN lendermaster on loandetails.lenderID=lendermaster.lenderID WHERE loandetails.loanType=0 AND loandetails.isApproved=1 AND loandetails.sentLender=1 AND schoolmaster.schoolID=".$row['schoolID']." AND loandetails.lenderID=".$row['lenderID']." GROUP BY schoolmaster.schoolID,loandetails.lenderID ORDER BY schoolmaster.schoolName";
			$queryLender = $this->db->query($sqllender);
			$sqlLenderResult = $queryLender->row_array();

			$sqlInprocess ="SELECT count(loandetails.loanID) as inprocessCount FROM loandetails INNER JOIN studentmaster on loandetails.studentID=studentmaster.studentID INNER JOIN schoolmaster on studentmaster.schoolID=schoolmaster.schoolID INNER JOIN lendermaster on loandetails.lenderID=lendermaster.lenderID WHERE loandetails.loanType=0 AND loandetails.isApproved=1 AND schoolmaster.schoolID=".$row['schoolID']." AND loandetails.lenderID=".$row['lenderID']." GROUP BY schoolmaster.schoolID,loandetails.lenderID ORDER BY schoolmaster.schoolName";
			$queryInprocess = $this->db->query($sqlInprocess);
			$sqlInprocessResult = $queryInprocess->row_array();
			$row['lenderName'] = $row['lenderName']; 
			$row['applicationCount'] = round($sqlAppResult['applicationCount']); 
			$row['inprocessCount'] = round($sqlInprocessResult['inprocessCount']); 
			$row['lenderCount'] = round($sqlLenderResult['lenderCount']); 
			$resultArray[] = $row;
		}
		return $resultArray;
	}
	public function loansoneschool(){
		$postArray = $this->input->post();
		$schoolID = $postArray['schoolID'];
		$lenderID = $postArray['lenderID'];
		$sql   = "SELECT ld.isKyc,ld.loanID,ld.loanAmount,ld.loantenure,scm.schoolName,sm.sfirstName,sm.slastName, pm.pfirstName,pm.plastName,ld.isApproved FROM `loandetails` as ld INNER JOIN `studentmaster` as sm on sm.studentID=ld.studentID INNER JOIN parentmaster as pm on pm.parentID=sm.parentID INNER JOIN schoolmaster as scm on scm.schoolID=sm.schoolID WHERE scm.schoolID=$schoolID AND ld.lenderID=$lenderID and ld.isApproved IN (0,1) and ld.loanType=0 and ld.isKyc <> 2";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function loansoneschoolLender(){
		$postArray = $this->input->post();
		$schoolID = $postArray['schoolID'];
		$lenderID = $postArray['lenderID'];
		$sql   = "SELECT ld.isKyc,ld.loanID,ld.loanAmount,ld.loantenure,scm.schoolName,sm.sfirstName,sm.slastName, pm.pfirstName,pm.plastName,ld.isApproved FROM `loandetails` as ld INNER JOIN `studentmaster` as sm on sm.studentID=ld.studentID INNER JOIN parentmaster as pm on pm.parentID=sm.parentID INNER JOIN schoolmaster as scm on scm.schoolID=sm.schoolID WHERE scm.schoolID=$schoolID AND ld.lenderID=$lenderID and ld.isApproved IN (1) and ld.sentLender <> 1 and ld.loanType=0 and ld.isKyc = 1";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function loansoneschoolApproved(){
		$postArray = $this->input->post();
		$schoolID = $postArray['schoolID'];
		$lenderID = $postArray['lenderID'];
		$sql   = "SELECT ld.isKyc,ld.loanID,ld.loanAmount,ld.loantenure,scm.schoolName,sm.sfirstName,sm.slastName, pm.pfirstName,pm.plastName,ld.isApproved,ld.sentLender FROM `loandetails` as ld INNER JOIN `studentmaster` as sm on sm.studentID=ld.studentID INNER JOIN parentmaster as pm on pm.parentID=sm.parentID INNER JOIN schoolmaster as scm on scm.schoolID=sm.schoolID WHERE scm.schoolID=$schoolID AND ld.lenderID=$lenderID and ld.isApproved IN (1) and ld.sentLender=1 and ld.loanType=0 and ld.isKyc = 1";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function loandetailsPopup(){
		$postArray = $this->input->post();
		$loanID = $postArray['loanID'];
		$sql   = "SELECT ld.*,pm.*,sm.*,ld.isApproved as loanApprovedcolumn FROM loandetails as ld INNER JOIN `studentmaster` as sm on sm.studentID=ld.studentID INNER JOIN parentmaster as pm on pm.parentID=sm.parentID WHERE ld.loanID=$loanID";
		$query = $this->db->query($sql);
		return $query->row_array();
	}
	public function updateKycloanstatus(){
		$postArray = $this->input->post();
		if($postArray['isKYC'] == 1){
			$isApproved = 1; //inprocess
			$isKyc = 1; //yes
		}else{
			$isApproved = 0;
			$isKyc = 2; //reject
		}
		$postData = array(
			"isApproved"=> $isApproved,
			"kycDate"=>date('Y-m-d H:i:s'),
			"isKyc" => $isKyc 
		);
		$this->db->update("loandetails", $postData, array("loanID"=> $postArray["loanID"]));		
		$this->db->trans_complete();	
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}	
	}

	public function disbursementReport(){
		$postArray = $this->input->post();
		$schoolID = $postArray['schoolID'];
		$sql = "SELECT ld.isKyc,ld.loanID,ld.loanAmount,ld.loantenure,scm.schoolName, pm.pfirstName,pm.plastName,ld.isApproved,sf.retaintion as margin,ld.isDisbursement FROM `loandetails` as ld INNER JOIN `studentmaster` as sm on sm.studentID=ld.studentID INNER JOIN parentmaster as pm on pm.parentID=sm.parentID INNER JOIN schoolmaster as scm on scm.schoolID=sm.schoolID INNER JOIN schoolfinance as sf on sf.schoolID=scm.schoolID WHERE ld.isApproved IN(2) and ld.isDisbursement=0 and ld.loanType=0 and scm.schoolID =$schoolID";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public  function disbursementSchoolwise()
	{
		$sql = "SELECT SUM(ld.loanAmount) as disbursementAmount, sf.retaintion as margin,scm.schoolID,scm.bankName,scm.accountNo,scm.ifscCode,scm.schoolName, ld.isApproved,ld.isDisbursement FROM `loandetails` as ld INNER JOIN `studentmaster` as sm on sm.studentID=ld.studentID INNER JOIN parentmaster as pm on pm.parentID=sm.parentID INNER JOIN schoolmaster as scm on scm.schoolID=sm.schoolID INNER JOIN schoolfinance as sf on sf.schoolID=sm.schoolID WHERE ld.isApproved = 2 and ld.isDisbursement = 0 and ld.loanType=0 GROUP BY scm.schoolID";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	public function remittanceSchoolwise(){
		$sql = "SELECT ld.loanID, count(ld.loanID),sum(ld.loanAmount) as sumloanAmount,ld.isApproved, scm.schoolName, scm.schoolID, scm.bankName,scm.ifscCode,scm.accountNo,ld.remitLoan FROM `loandetails` as ld INNER JOIN `studentmaster` as sm on sm.studentID=ld.studentID INNER JOIN schoolmaster as scm on scm.schoolID=sm.schoolID WHERE ld.loanType=1 AND ld.remitLoan=1 GROUP BY scm.schoolID";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function remittancereport(){
		$sql   = "SELECT ld.*,pm.*,sm.*,scm.schoolID,scm.schoolName, DATE_FORMAT(ld.created,'%D-%M-%Y') as remittancedate FROM loandetails as ld INNER JOIN `studentmaster` as sm on sm.studentID=ld.studentID INNER JOIN parentmaster as pm on pm.parentID=sm.parentID INNER JOIN schoolmaster as scm on scm.schoolID = sm.schoolID WHERE ld.loanType=1";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function updateApprovedstatusfromReport(){
		$postArray = $this->input->post();
		// sent for lender
		if($postArray['sendforlenderStatus'] == 1){ 
			$sentLender = 1; //send for lender 

			foreach($postArray["loanIDArray"] as $k => $row){
				$postData[] = array(
					"sentLender"=> $sentLender,
					"sentLenderDate" => date('Y-m-d H:i:s'),
					"loanID"=> $row
				);
			}
			$this->db->update_batch("loandetails", $postData, "loanID");		
			$this->db->trans_complete();	
			if($this->db->trans_status() === FALSE){
				return false;
			}else{
				return true;
			}
		}else{
			if($postArray['approveRejectStatus'] == 1){
				$isApproved = 2; //approved e.g refer colum comment
			}else if($postArray['approveRejectStatus'] == 0){
				$isApproved = 3; //reject e.g refer colum comment
			}
			foreach($postArray["loanIDArray"] as $k => $row){
				$postData[] = array(
					"isApproved"=> $isApproved,
					"isApprovedDate" => date('Y-m-d H:i:s'),
					"loanID"=> $row
				);
			}
			$this->db->update_batch("loandetails", $postData, "loanID");		
			$this->db->trans_complete();	
			if($this->db->trans_status() === FALSE){
				return false;
			}else{
				return true;
			}
		}

	}

	public function updatedisbursedstatusfromReport()
	{
		$postArray = $this->input->post();
		if($postArray['approveRejectStatus'] == 1){
			$isApproved = 1; //approved e.g refer colum comment
		}else if($postArray['approveRejectStatus'] == 0){
			$isApproved = 0; //reject e.g refer colum comment
		}
		foreach($postArray["loanIDArray"] as $k => $row){
			$postData[] = array(
				"isDisbursement"=> $isApproved,
				"disbursementDate" => date('Y-m-d H:i:s'),
				"loanID"=> $row
			);
		}
		//$this->db->update("loandetails", $postData, array("loanID" => $postArray["loanIDArray"]));		
		$this->db->update_batch("loandetails", $postData, "loanID");		
		$this->db->trans_complete();	
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}	
	}
	public function updateremittancestatusfromReport()
	{
		$postArray = $this->input->post();
		//postArray['loanIDArray'] is school ID array from remittance page
		$loanIdArray = array();
		foreach($postArray['loanIDArray'] as $key => $value){
			$sql = "SELECT ld.loanID FROM `loandetails` as ld INNER JOIN studentmaster as sm ON sm.studentID=ld.studentID WHERE ld.remitLoan = 1 AND sm.schoolID = $value";
			$query = $this->db->query($sql);
			$loanIdArray = $query->result_array();
		}		
		
		$arrayLoanIDs = array_column($loanIdArray,'loanID');	
		
		if($postArray['approveRejectStatus'] == 1){
			$remitLoan = 2; //complete e.g refer remitLoan column comment
		}
		foreach($arrayLoanIDs as $k => $loanid){									
			$postData[] = array(													
				"remitLoan"=> $remitLoan,											
				"updated" => date('Y-m-d H:i:s'),											
				"loanID"=> $loanid												
			);
		}
		//$this->db->update("loandetails", $postData, array("loanID" => $postArray["loanIDArray"]));		
		$this->db->update_batch("loandetails", $postData, "loanID");		
		$this->db->trans_complete();	
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}	
	}
	public function updateNewremittancestatusfromReport()
	{
		$postArray = $this->input->post();
		if($postArray['approveRejectStatus'] == 1){
			$remitLoan = 1; //yes e.g refer remitLoan column comment
		}else if($postArray['approveRejectStatus'] == 0){
			$remitLoan = 2; //completed e.g refer remitLoan column comment
		}
		foreach($postArray["loanIDArray"] as $k => $row){
			$postData[] = array(
				"remitLoan"=> $remitLoan,
				"updated" => date('Y-m-d H:i:s'),
				"loanID"=> $row
			);
		}
		$this->db->update_batch("loandetails", $postData, "loanID");		
		$this->db->trans_complete();	
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}	
	}

	public function getListofemiReport()
	{
		$currentDate = date('Y-m-d');
		$sql   = "SELECT e.*,pm.* FROM `emischedule` as e INNER JOIN studentmaster as sm on sm.studentID=e.studentID INNER JOIN parentmaster pm on pm.parentID=sm.parentID WHERE e.ispaid = 0 AND e.`emiPaiddate` LIKE '%$currentDate%'";
		$query = $this->db->query($sql);
		return $query->result_array();
	}	

	public function updateemiispaidStatus()
	{
		$postArray = $this->input->post();
		
		foreach($postArray["emiIDArray"] as $k => $row){
			$postData[] = array(
				"ispaid"=> 1,
				"updated" => date('Y-m-d H:i:s'),
				"emiID"=> $row
			);
		}
		$this->db->update_batch("emischedule", $postData, "emiID");		
		$this->db->trans_complete();	
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}	
	}

	public function emilenderlistData(){
		//$currentDate = date('Y-m-d'); paid=1
		$sql = "SELECT SUM(e.emiAmount) as emiAmount, e.emiPaiddate,DATE_FORMAT(e.emiDuedate, '%D %M %Y') as emiDuedate,lm.lenderName, scm.bankName, scm.ifscCode, scm.accountNo FROM `emischedule` as e INNER JOIN loandetails as ld on ld.loanID=e.loanID INNER JOIN lendermaster as lm on lm.lenderID=ld.lenderID INNER JOIN studentmaster as sm ON sm.studentID=ld.studentID INNER JOIN schoolmaster as scm on sm.schoolID=scm.schoolID WHERE e.ispaid=1 GROUP BY e.emiDuedate";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function Deletefile()
	{
		$postArray = $this->input->post();
		$postData = array(
			"isDeleted"=>1,
			"updated"=>date('Y-m-d H:i:s')
		);
		$this->db->update("approvefiles",$postData,array("fileID"=>$postArray["fileID"]));		
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
	}

	public function checkFilemonthly($schoolID)
	{
		$month = date('m');
		$year = date('Y');
		$sql = "SELECT * FROM `approvefiles` WHERE isDeleted = 0 AND month=$month AND year=$year AND schoolID= $schoolID";
		$query = $this->db->query($sql);
		if($query->num_rows() >0) {
			$result = $query->row_array();	
			//echo "<pre>"; print_r($result);
			return $result;			
		}else{
			return false;
		}
	}

	public function generateAgreement()
	{
		$this->load->library('Pdf');
		//$studentID = $this->security->xss_clean($postArray["studentID"]);
		$studentID = 1;
		$sql = "SELECT s.*, ld.*, p.*, sf.collectionCharges FROM studentmaster as s INNER JOIN parentmaster p on p.parentID=s.parentID INNER JOIN loandetails as ld on s.studentID=ld.studentID INNER JOIN schoolfinance as sf on sf.schoolID=s.schoolID WHERE s.`studentID`=?";
		$query = $this->db->query($sql,array($studentID));
		$studentData = $query->row_array();
		$agreementSql = "SELECT * FROM agreement WHERE agreementType=2";
		$agreementQuery = $this->db->query($agreementSql);
		$agreement = $agreementQuery->row_array();
		$loanID = $studentData['loanID'];
		$emiSql = "SELECT emiID,emiDuedate,emiAmount FROM emischedule WHERE ispaid=0 AND loanID=$loanID";
		$emiQuery = $this->db->query($emiSql);
		$emiData = $emiQuery->result_array();
		
		$agreementContents = $agreement['agreementText'];

		$agreementData = str_replace('<input type="text" name="DateNumber">',date("jS"),$agreementContents);
		$agreementData = str_replace('<input type="text" name="MonthName">',date("F"),$agreementData);
		$agreementData = str_replace('<input type="text" name="YearNumber">',date("Y"),$agreementData);
		$agreementData = str_replace('<input type="text" name="ParentName">',$studentData['pfirstName']." ".$studentData["plastName"],$agreementData);
		$agreementData = str_replace('<input type="text" name="PanNumber">',$studentData['panId'],$agreementData);
		$agreementData = str_replace('<input type="text" name="ParentFatherName">', $studentData['pmiddleName']." ".$studentData["plastName"], $agreementData);
		$agreementData = str_replace('<input type="text" name="AddressLocationCityDistrict">', $studentData['address'].", ".$studentData['location'].", ".$studentData['city'].", ".$studentData['state'] ,$agreementData);
		$agreementData = str_replace("[LoanAmount]",$studentData['loanAmount'],$agreementData);
		$agreementData = str_replace("[EmiNumber]",$studentData['loantenure'],$agreementData);
		$agreementData = str_replace("[RateOfInterest]",$studentData['roi'],$agreementData);
		$agreementData = str_replace("[ParentNameBehalf]",$studentData['pfirstName']." ".$studentData["plastName"],$agreementData);
		$agreementData = str_replace("[TenureMonthly]",$studentData['loantenure'],$agreementData);
		$agreementData = str_replace("[EmiAmount]",$studentData['emiAmount'],$agreementData);
		$agreementData = str_replace("[BorrowerName]",$studentData['pfirstName']." ".$studentData["plastName"],$agreementData);
		$agreementData = str_replace('<input type="text" name="LoanAmount">',$studentData['loanAmount'],$agreementData);
		$agreementData = str_replace('<input type="text" name="RateOfInterest">',$studentData['roi'],$agreementData);
		$agreementData = str_replace('<input type="text" name="CollectionCharge">',$studentData['collectionCharges'],$agreementData);
		$agreementData = str_replace('<input type="text" name="Tenure">',$studentData['loantenure'],$agreementData);
		$agreementData = str_replace('<input type="text" name="EMIAmount">',$studentData['emiAmount'],$agreementData);
		$str = '';
		if(!empty($emiData)){
			$i= 1;
			foreach($emiData as $row){
				$emiAmount = $row['emiAmount'];
				$emiDueDate = date("Y-m-d",strtotime($row['emiDuedate']));
				$str .= "<tr><td>".$this->addOrdinalNumberSuffix($i)."</td><td>".$emiAmount."</td><td>".$emiDueDate."</td></tr>";
				$i++;
			}
		}
		$agreementData = str_replace('<tr><td>Loan Amount</td><td><input type="text"></td><td><input type="text"></td></tr>',$str,$agreementData);
		
		$html = $agreementData;
		$this->pdf->loadHtml($html);
		$this->pdf->setPaper('A4', 'portrait'); 
		$this->pdf->render(); 
		$this->pdf->stream();
	}

	function addOrdinalNumberSuffix($num) {
		if (!in_array(($num % 100),array(11,12,13))){
		  switch ($num % 10) {
			case 1:  return $num.'st';
			case 2:  return $num.'nd';
			case 3:  return $num.'rd';
		  }
		}
		return $num.'th';
	}

	

}?>
