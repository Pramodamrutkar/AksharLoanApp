<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApiController extends CI_Controller{
        function __construct()
        {
            parent::__construct();
            $this->load->model('ApiModel');
        }
        
        public function parentlogin(){
            echo $this->ApiModel->LoginMatchReturnOtp();
        }
        public function parentresendotplogin()
        {
            echo $this->ApiModel->LoginMatchReturnOtp();    //Resend otp code. used same function to generate otp.
        }
        public function parentverifyotp(){
            echo $this->ApiModel->verifyOtpdetails();
        }
        public function parentstudentlist(){
            echo $this->ApiModel->getStudentData();
        }
        public function parentstudentdetails(){
            echo $this->ApiModel->getStudentDatadetails();
        }
        public function parentpersonaldetails(){
            echo $this->ApiModel->saveKycdetails();
        }

        public function parentuploaddocument(){
            echo $this->ApiModel->uploadKycdetails();
        }

        public function parentgetloanagreement()
        {
            echo $this->ApiModel->loanAgreement();
        }
        
        public function parentloandetails()
        {
            echo $this->ApiModel->saveLoanDetails();
        }

       /*  public function getKycDetails(){
            echo $this->ApiModel->fetchKycDetails();
        } */

        public function statelist(){
            echo $this->ApiModel->StateList();
        }
        
        public function citylist(){
            echo $this->ApiModel->CityList();
        }

      /*   public function allocateLoan(){
            echo $this->ApiModel->getloanWithEMI();
        } */

        public function parentpaidprocessingfees()
        {
            echo $this->ApiModel->saveprocessingFeesStatus();
        }

        public function parentinstallmentschedule()
        {
            echo $this->ApiModel->emiConfirmationDetail();
        }
        
        public function parentupdateemistatus()
        {
            echo $this->ApiModel->saveemiStatusDetails();
        }

        public function parentpendingemi()
        {
            echo $this->ApiModel->getPendingEMIs();
        }

        public function parentpayfromownaccount()
        {
            echo $this->ApiModel->addPayfromownAccount();
        }
        

        //staff login
        public function stafflogin(){
            echo $this->ApiModel->staffLoginMatchReturnOtp();
        }

        public function staffverifyotp(){
            echo $this->ApiModel->staffverifyOtpdetails();
        }
         
        public function staffcheckloaneligibility()
        {
            echo $this->ApiModel->staffGetcheckLoanElibility();
        }

        public function staffresendotp()
        {
            echo $this->ApiModel->staffLoginMatchReturnOtp();    //Resend otp code. used same function to generate otp.
        }
        
        public function staffpersonaldetails()
        {
            echo $this->ApiModel->savestaffKycDetails();
        }

        public function staffuploaddocument()
        {
            echo $this->ApiModel->uploadeStaffKycDetails();
        }

        public function staffuploadebankdetails(){
            echo $this->ApiModel->uploadeBankDetails();
        }

        public function staffpaidprocessingfees()
        {
            echo $this->ApiModel->savestaffprocessingFeesStatus();
        }
        public function staffinstallmentschedule()
        {
            echo $this->ApiModel->staffemiConfirmationDetail();
        }
        
        public function staffupdateemistatus()
        {
            echo $this->ApiModel->updatestaffemiStatusDetails();
        }
        public function staffpendingemi(){
            echo $this->ApiModel->staffgetPendingEMIs();
        }


        //personal loan start
        public function personalloandetails()
        {
            echo $this->ApiModel->getPersonalloandetails();
        }

        public function personalsaveloan()
        {
            echo $this->ApiModel->savepersonalLoan();
        }
        
        public function personalprocessingfees()
        {
            echo $this->ApiModel->savepersonalprocessingFeesStatus();
        }

        public function personalinstallmentschedule()
        {
            echo $this->ApiModel->personalEmiconfirmation();
        }
        
        public function personalupdateemistatus()
        {
            echo $this->ApiModel->updatepersonalemiStatusDetails();
        }

        public function personalpendingemi(){
            echo $this->ApiModel->personalgetPendingEMIs();
        }

}
?>