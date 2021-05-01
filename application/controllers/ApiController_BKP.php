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
        public function resendotplogin()
        {
            echo $this->ApiModel->LoginMatchReturnOtp();    //Resend otp code. used same function to generate otp.
        }
        public function verifyotp(){
            echo $this->ApiModel->verifyOtpdetails();
        }
        public function studentlist(){
            echo $this->ApiModel->getStudentData();
        }
        public function studentdetails(){
            echo $this->ApiModel->getStudentDatadetails();
        }

        public function personaldetails(){
            echo $this->ApiModel->saveKycdetails();
        }

        public function uploaddocument(){
            echo $this->ApiModel->uploadKycdetails();
        }

        public function getloanagreement()
        {
            echo $this->ApiModel->loanAgreement();
        }
        
        public function loandetails()
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

        public function allocateLoan(){
            echo $this->ApiModel->getloanWithEMI();
        }

        public function paidprocessingfees()
        {
            echo $this->ApiModel->saveprocessingFeesStatus();
        }

        public function installmentschedule()
        {
            echo $this->ApiModel->emiConfirmationDetail();
        }

        public function updateemistatus()
        {
            echo $this->ApiModel->saveemiStatusDetails();
        }

        public function pendingemi()
        {
            echo $this->ApiModel->getPendingEMIs();
        }

        
        //staff login
        public function stafflogin(){
            echo $this->ApiModel->staffLoginMatchReturnOtp();
        }

        public function staffverifyotp(){
            echo $this->ApiModel->staffverifyOtpdetails();
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

}
?>