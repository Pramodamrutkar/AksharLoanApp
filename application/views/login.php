<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="author" content="">
<title>Admin Login</title>
<link href="<?php echo assets_url();?>images/apple-touch-icon-152x152.png" rel="apple-touch-icon">
<link rel="shortcut icon" type="image/x-icon" href="<?php echo assets_url();?>images/favicon-32x32.png">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo assets_url();?>css/vendors.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo assets_url();?>css/materialize.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo assets_url();?>css/style.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo assets_url();?>css/login.css">
<link rel="stylesheet" type="text/css" href="<?php echo assets_url();?>css/custom.css">
<link rel="stylesheet" type="text/css" href="<?php echo assets_url();?>sweetalert2/sweetalert2.min.css">

<style type="text/css">
.input-field div.error {
	left: 3rem;
}
</style>

</head>
<body class="vertical-layout page-header-light vertical-menu-collapsible vertical-menu-nav-dark preload-transitions 1-column login-bg   blank-page blank-page" data-open="click" data-menu="vertical-menu-nav-dark" data-col="1-column">
<div class="row">
  <div class="col s12">
    <div class="container">
      <div id="login-page" class="row">
        <div class="col s12 m6 l4 z-depth-4 card-panel border-radius-6 login-card bg-opacity-8">
          <form name="frm" id="frm" action="#" method="post" enctype="multipart/form-data">
            <div class="row">
              <div class="input-field col s12">
                <h5 class="ml-4">Sign in</h5>
              </div>
            </div>
            <div class="row margin">
              <div class="input-field col s12"> <i class="material-icons prefix pt-2">person_outline</i>
                <input type="text" id="eMail" name="eMail" placeholder="Username"/>
                <label for="username" class="center-align">Username</label>
              </div>
            </div>
            <div class="row margin">
              <div class="input-field col s12"> <i class="material-icons prefix pt-2">lock_outline</i>
                <input type="password" id="password" name="password" placeholder="Password"/>
                <label for="password">Password</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12">
                <button type="submit" name="btnSubmit" id="btnSubmit" class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12"><i class="uk-icon-sign-in"></i> Login </button>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s6 m6 l6">
                <p class="margin medium-small"><!--<a href="user-register.html">Register Now!</a>--></p>
              </div>
              <div class="input-field col s6 m6 l6">
                <p class="margin right-align medium-small"><!--<a href="user-forgot-password.html">Forgot password ?</a>--></p>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="content-overlay"></div>
  </div>
</div>
<script type="text/javascript" src="<?php echo assets_url();?>js/vendors.min.js"></script> 
<script type="text/javascript" src="<?php echo assets_url();?>js/plugins.min.js"></script> 
<script type="text/javascript" src="<?php echo assets_url();?>js/search.min.js"></script> 
<script type="text/javascript" src="<?php echo assets_url();?>validatejs/jquery.validate.min.js"></script> 
<script type="text/javascript" src="<?php echo assets_url();?>validatejs/additional-methods.min.js"></script> 
<script type="text/javascript" src="<?php echo assets_url();?>validatejs/jquery.form.js"></script> 
<script type="text/javascript" src="<?php echo assets_url();?>sweetalert2/sweetalert2.min.js"></script> 
<script type ="text/javascript">
$(document).ready(function(){
	if ($("#frm").length > 0){
		var validator = $("#frm").validate({
			errorElement: 'div',
			errorPlacement: function(error, element){
				if (element.attr("type") == "radio"){
					error.insertAfter(".icheck-inline");
				}else{
					error.insertAfter(element);
				}
			},
			rules: {
				eMail: {
					email: true,
					required: true,
				},
				password: {
					required: true,
				},
			},
			messages: {
				eMail: {
					required: "Please enter E-Mail",
				},
				password: {
					required: "Please enter password",
				},
			},
			submitHandler: function(form){
				processLogin("#frm");
			}
		});
	}
});
function processLogin(frm){
	$(frm).ajaxSubmit({url: '<?php echo base_url('login/postIndex');?>',beforeSubmit: function (formData, jqForm, options){
		var queryString = $.param(formData);
	},clearForm: false,resetForm: false,success: function (responseText, statusText, xhr, $form){
		result = JSON.parse(responseText);
		if (result["Status"] == "true"){
			window.location ="<?php echo base_url('service');?>";
		}else{
			swal({type: 'error',text: result["Message"],buttonsStyling: true,allowOutsideClick: false,showCancelButton: false});
		}
	}});
}
</script>
</body>
</html>

<script type="text/javascript" src="<?php echo assets_url();?>validatejs/jquery.form.js"></script> 
<script type="text/javascript" src="<?php echo assets_url();?>sweetalert/sweetalert.min.js"></script> 