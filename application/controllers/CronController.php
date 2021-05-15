<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CronController extends CI_Controller{
        function __construct()
        {
            parent::__construct();
            $this->load->model('CronModel');
        }

        public function checkemioverdues(){
            echo $this->CronModel->cronToupdateEmioverdues();
        }
}
?>