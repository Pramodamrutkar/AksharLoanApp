<?php $pageTitle = 'Sales Rep';?>
<!DOCTYPE html>
<html>
<head>
<?php include('include.php');?>
</head>
<body>
<?php include('header.php');?>
<div id="main">
  <div class="row">
    <div class="breadcrumbs-inline pt-2" id="breadcrumbs-wrapper">
      <div class="container">
        <div class="row">
          <div class="col s10 m6 l6 breadcrumbs-left">
            <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span><?php echo $pageTitle;?></span></h5>
            <ol class="breadcrumbs mb-0">
              <li class="breadcrumb-item active" id="breadcrumbTitle">List </li>
            </ol>
          </div>
          <div class="col s2 m6 l6"> <a class="btnAdd mb-6 btn waves-effect waves-light gradient-45deg-green-teal right"><i class="material-icons left">add</i> <span class="hide-on-small-onl">Add</span></a> </div>
        </div>
      </div>
    </div>
  </div>
  <section id="listSection" class="users-list-wrapper section">
    <div class="row">
      <div class="col s12">
        <div class="container">
          <div class="users-list-table">
            <div class="card">
              <div class="card-content">
                <div class="">
                  <table id="tableData" class="table" style="width:100%;">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th style="width:40px;">Image</th>
                        <th>Name</th>
                        <th>E-Mail</th>
                        <th>Mobile</th>
                        <th style="width:60px;">Type</th>
                        <th style="width:60px;">Status</th>
                        <th style="width:60px;">Actions</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<div id="ModalBox" class="modal modal-fixed-footer">
  <form name="frm" id="frm" method="post" enctype="multipart/form-data" class="uk-form-stacked">
    <div class="modal-content">
      <div class="row">
        
        <div class="col s6">
          <div class="input-field">
            <select name="typeID" id="typeID">
              <option value=""></option>
              <?php echo $UserType;?>
            </select>
            <label for="typeID">User Type</label>
          </div>
        </div>
        <div class="col s6">
          <div class="input-field">
            <select name="areaID" id="areaID">
              <option value=""></option>
              <?php echo $Area;?>
            </select>
            <label for="typeID">Area</label>
          </div>
        </div>

        
        <div class="col s12">
          <div class="input-field">
            <input type="text" id="fullName" name="fullName">
            <label for="fullName"><span class="req">*</span> Name</label>
          </div>
        </div>
        <div class="col s6">
          <div class="input-field">
            <input type="text" id="mobileNo" name="mobileNo">
            <label for="mobileNo">Mobile No</label>
          </div>
        </div>
        <div class="col s6">
          <div class="input-field">
            <input type="text" id="eMail" name="eMail">
            <label for="eMail">E-Mail</label>
          </div>
        </div>
        <div class="col s6">
          <div class="input-field">
            <input type="Password" name="password" id="password"/>
            <label for="password"><span class="req">*</span>Password</label>
          </div>
        </div>
        <div class="col s6">
          <div class="input-field">
            <input type="Password" name="cpassword" id="cpassword"/>
            <label for="password"><span class="req">*</span>Confirm Password</label>
          </div>
        </div>
        <div class="col s12">
          <div class="input-field">
            <input type="file" id="imageName" name="imageName"/>
            <input type="hidden" name="imageNameO" id="imageNameO" value=""/>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col s12">
          <p><span class="req">*</span> Active</p>
          <p>
            <label>
              <input type="radio" name="isActive" id="isActive1" class="with-gap" value="1" checked>
              <span>Yes</span></label>
          </p>
          <p>
            <label>
              <input type="radio" name="isActive" id="isActive2" class="with-gap" value="0"/>
              <span>No</span></label>
          </p>
        </div>
      </div>
    </div>
    <div class="modal-footer" style="text-align:center;">
      <input type="hidden" name="userID" id="userID" value=""/>
      <button type="submit" name="btnSubmit" id="btnSubmit" class="btn waves-effect waves-light gradient-45deg-green-teal"> <i class="material-icons left">save</i> Save</button>
      <button type="button" name="btnCancel" id="btnCancel" class="btnCancel btn waves-effect waves-light gradient-45deg-purple-deep-orange"> <i class="material-icons left">close</i> Cancel</button>
    </div>
  </form>
</div>
<?php include('footer.php');?>
<script type ="text/javascript">
$(document).ready(function(){
	$('#ModalBox').modal({dismissible:false});
	
	listRow();
	
	$('.btnAdd').click(function(){
		resetForm();

		$('#password').rules('add',  'required');
		$('#cpassword').rules('add',  'required');			
		$('#eMail').removeAttr("readonly");		
				
		$('#ModalBox').modal('open');
	});
	$('#tableData').on('click', 'a.btnEdit', function (e){	
		resetForm();
		
		$('#password').rules('remove',  'required')
		$('#cpassword').rules('remove',  'required')	
		$('#eMail').attr("readonly","true");		
		
		var table = $('#tableData').DataTable();
		var rows = table.row($(this).closest('tr')).data();
		var userID = rows["userID"];
		getRow(userID);
		$('#ModalBox').modal('open');
	});	
	$('#tableData').on('click', 'a.btnDelete', function (e){
		var table = $('#tableData').DataTable();
		var rows = table.row($(this).closest('tr')).data();
		var userID = rows["userID"];
		if(userID > 0){
			swal({text: "Are you sure, you want to delete the selected record?",icon: 'warning',closeOnClickOutside: false,buttons: {delete: 'Yes',cancel: 'No'}}).then(function (willDelete){
				if(willDelete){
					deleteRow(userID);
				}
			});
		}		
	});	
	$('.btnCancel').click(function(){
		resetForm();
		$('#ModalBox').modal('close');		
	});	
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
				typeID: {
					required: true,
				},
				areaID: {
					required: true,
				},								
				fullName: {
					required: true,
				},
				eMail:{
					required: true,
					email: true,
				},												
				password:{
					required: true,
					minlength: 8
				},
				cpassword:{
					required: true,
					minlength: 8,
					equalTo: "#password"
				},
				mobileNo:{
					required: true,
					digits: true,
					minlength: 10,
					maxlength: 10
				},			
				imageName: {
					extension: "jpg|png|gif",
				},
				isActive: {
					required: true
				}
			},
			messages: {				
				typeID: {
					required: "Please select user type",
				},			
				areaID: {
					required: "Please select user area",
				},			
				fullName: {
					required: "Please enter Name",
				},
				eMail:{
					required: "Please enter your email address",
					email: "This is not a valid email address"
				},												
				password:{
					required: "Please enter your password",
					minlength: "password should be minimum 8 characters"
				},
				cpassword:{
					required: "Please enter your confirm password",
					equalTo: "password and confirm password should be same"
				},
				mobileNo:{
					required: "Please enter mobile number",
					digits: "Please enter only numbers",
					minlength: "The mobile number should be 10 digits",
					maxlength: "The mobile number should be 10 digits"
				},
				isActive: {
					required: "Please select status"
				}
			},
			submitHandler: function(form){
				saveRow("#frm");
			}
		});
	}	
});
function listRow(){
	$('#ModalBox').modal('close');		
	$.ajax({url: serviceUrl+'listuser',type: "POST",data:{},success: function (respsone){
		var result = JSON.parse(respsone);
		if (result["Status"] == "true"){		
			$('#tableData').DataTable({
   			    "searching": true,
				"bPaginate": false,
				"destroy" : true,
				"data": result.resultArray,
				"columns": 
				[
					{"data": "userID"},
					{"data": "imageName"},
					{"data": "fullName"},
					{"data": "eMail"},
					{"data": "mobileNo"},
					{"data": "typeName"},
					{"data": "isActive"},
					{"data": "Action"},
				],
				columnDefs: 
				[
					{targets: [0],visible: false},
					{targets: 1,visible: true ,sorting:false,className : "text-center",
					render: function(data){
						if(data){
							return '<img src="<?php echo upload_url();?>user/'+data+'" class="img-responsive list-img">'
						}else{
							return '<img src="<?php echo assets_url();?>images/default.png" class="img-responsive list-img">'
						}
					}},
					{targets:[2],visible: true},
					{targets:[3],visible: true},
					{targets:[4],visible: true},
					{targets:[5],visible: true},
					{targets:[6],visible: true,className : "text-center",
					render: function ( data, type, row, meta ) {
						if(data == 1 ) {
							return 'Active';
						}else {
							return 'Inactive';
						}
					}},
					{targets:[7],visible: true ,className : "text-center",sorting:false,
					render: function ( data, type, row, meta ) {
						return '<a href="javascript:void(0);" class=\"btnEdit material-icons\">edit</a><a href="javascript:void(0);" class=\"btnDelete material-icons\">delete</a>'
					}},
				]
			});
		}else{
			swal(result["Message"],{icon: "error",closeOnClickOutside: false});
		}
	},
	error: function (jqXHR,textStatus,errorThrown ) {
		console.log(jqXHR);
		console.log(textStatus);
		console.log(errorThrown);
		console.log("Error: " + textStatus);
	}});
}
function saveRow(frm){
	$(frm).ajaxSubmit({url:serviceUrl+'postuser',beforeSubmit: function (formData, jqForm, options){
		var queryString = $.param(formData);
	},clearForm: false,resetForm: false,success: function (responseText, statusText, xhr, $form){
		var result = JSON.parse(responseText);
		if (result["Status"] == "true"){	
			swal({text: result["Message"],icon: 'success',closeOnClickOutside: false,buttons: {delete: 'Ok'}}).then(function (willDelete){
				if(willDelete){
					listRow();
				}
			});
		}else{
			swal(result["Message"],{icon: "error",closeOnClickOutside: false});
		}		
	}});
}
function deleteRow(userID){
	$.post(serviceUrl+'deleteuser',{"userID": userID}, function (data){
		result = JSON.parse(data);
		if (result["Status"] == "true"){
			swal({text: result["Message"],icon: 'success',closeOnClickOutside: false,buttons: {delete: 'Ok'}}).then(function (willDelete){
				if(willDelete){
					listRow();
				}
			});
		}else{
			swal(result["Message"],{icon: "error",closeOnClickOutside: false});
		}
	});
}
function getRow(userID){
	$.post(serviceUrl+'getuser',{"userID": userID}, function (data){
		result = JSON.parse(data);
		if (result["Status"] == "true"){
			row = result.resultArray;
			$("#userID").val(row["userID"]);
			$("#fullName").val(row["fullName"]);
			$("#eMail").val(row["eMail"]);
			$("#mobileNo").val(row["mobileNo"]);
			$("#mobileNo").val(row["mobileNo"]);

			
			
			$("#typeID").val(row["typeID"]);	
			$("#areaID").val(row["areaID"]);	

			$("#imageNameO").val(row["imageName"]);
			if(row["isActive"]==1){
				$("#isActive1").prop("checked", true);
			}else{
				$("#isActive2").prop("checked", true);
			}
			$("#frm label").addClass("active");
			$("#fullName").focus();
			$('select').formSelect();
		}else{
			swal(result["Message"],{icon: "error",closeOnClickOutside: false});
		}
	});
}
function resetForm(){
	$("#frm").validate().resetForm();
	$('#frm')[0].reset();
	$('#frm').find("#userID").val(0);
}
</script>
</body>
</html>