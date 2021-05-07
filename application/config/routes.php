<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
//student or parent API
$route['(i?)parentlogin'] = 'ApiController/parentlogin';
$route['(i?)parentresendotplogin'] = 'ApiController/parentresendotplogin';
$route['(i?)parentverifyotp'] = 'ApiController/parentverifyotp';
$route['(i?)parentstudentlist'] = 'ApiController/parentstudentlist';
$route['(i?)parentstudentdetails'] = 'ApiController/parentstudentdetails';
$route['(i?)parentpersonaldetails'] = 'ApiController/parentpersonaldetails';
$route['(i?)parentuploaddocument'] = 'ApiController/parentuploaddocument';
$route['(i?)parentgetloanagreement'] = 'ApiController/parentgetloanagreement'; 
//$route['(i?)parentloandetails'] = 'ApiController/parentloandetails'; // student id & accesstoken
$route['(i?)statelist'] = 'ApiController/statelist';
$route['(i?)citylist'] = 'ApiController/citylist';
$route['(i?)parentpaidprocessingfees'] = 'ApiController/parentpaidprocessingfees';
$route['(i?)parentinstallmentschedule'] = 'ApiController/parentinstallmentschedule';
$route['(i?)parentupdateemistatus'] = 'ApiController/parentupdateemistatus';
$route['(i?)parentpendingemi'] = 'ApiController/parentpendingemi';
$route['(i?)parentpayfromownaccount'] = 'ApiController/parentpayfromownaccount';

$route['(i?)checkemioverdues'] = 'ApiController/checkemioverdues';

//$route['(i?)getKycDetails'] = 'ApiController/getKycDetails';
//$route['(i?)allocateLoan'] = 'ApiController/allocateLoan';
//staff Loan API
$route['(i?)stafflogin'] = 'ApiController/stafflogin';
$route['(i?)staffverifyotp'] = 'ApiController/staffverifyotp';
$route['(i?)staffresendotp'] = 'ApiController/staffresendotp';
$route['(i?)staffcheckloaneligibility'] = 'ApiController/staffcheckloaneligibility';
$route['(i?)staffpersonaldetails'] = 'ApiController/staffpersonaldetails';
$route['(i?)staffuploaddocument'] = 'ApiController/staffuploaddocument';
$route['(i?)staffuploadebankdetails'] = 'ApiController/staffuploadebankdetails';
$route['(i?)staffpaidprocessingfees'] = 'ApiController/staffpaidprocessingfees';
$route['(i?)staffinstallmentschedule'] = 'ApiController/staffinstallmentschedule';
$route['(i?)staffupdateemistatus'] = 'ApiController/staffupdateemistatus';
$route['(i?)staffpendingemi'] = 'ApiController/staffpendingemi';

//personal Loan Api
$route['(i?)personalloandetails'] = 'ApiController/personalloandetails';
$route['(i?)personalsaveloan'] = 'ApiController/personalsaveloan';
$route['(i?)personalprocessingfees'] = 'ApiController/personalprocessingfees';
$route['(i?)personalinstallmentschedule'] = 'ApiController/personalinstallmentschedule';
$route['(i?)personalupdateemistatus'] = 'ApiController/personalupdateemistatus';
$route['(i?)personalpendingemi'] = 'ApiController/personalpendingemi';





