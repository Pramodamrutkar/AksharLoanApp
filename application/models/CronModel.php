<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CronModel extends CI_Model{
    function __construct()
    {
        parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
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

