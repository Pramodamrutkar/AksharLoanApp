<?php $pageTitle = 'Student';

$schoolID = $this->session->userdata('schoolData')["schoolID"];
$schoolName = $this->session->userdata('schoolData')["schoolName"];
?>
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
          <div class="col s8 breadcrumbs-left">
            <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span><?php echo $pageTitle;?></span></h5>
            <ol class="breadcrumbs mb-0">
              <li class="breadcrumb-item active" id="breadcrumbTitle"> List </li>
            </ol>
          </div>
          <div class="col s4" align="right"> <a class="btnAdd mb-6 btn waves-effect waves-light gradient-45deg-green-teal"><i class="material-icons left">add</i> <span class="hide-on-small-onl">Add</span></a> </div>
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
                        <th>Student Name</th>
                        <th>Parent Name</th>
                        <th>Phone No</th>
                        <th>Gender</th>
						<th>Approved</th>
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
            <input type="text" id="sfirstName" name="sfirstName">
            <label for="sfirstName"><span class="req">*</span>Student First Name</label>
          </div>
        </div>
		<div class="col s6">
          <div class="input-field">
            <input type="text" id="slastName" name="slastName">
            <label for="slastName"><span class="req">*</span>Student Last Name</label>
          </div>
        </div>
        <div class="col s6">
          <div class="input-field">
            <input type="text" id="pfirstName" name="pfirstName">
            <label for="pfirstName">Parent First Name</label>
          </div>
        </div>
		<div class="col s6">
          <div class="input-field">
            <input type="text" id="plastName" name="plastName">
            <label for="plastName">Parent Last Name</label>
          </div>
        </div>
		<div class="col s4">
          <div class="input-field">
            <input type="text" id="relationship" name="relationship">
            <label for="relationship">Relationship</label>
          </div>
        </div>

        <div class="col s4">
          <div class="input-field">
            <input type="text" id="mobileNo" name="mobileNo">
            <label for="mobileNo">Phone Number</label>
          </div>
        </div>
		<div class="col s4">
          <div class="input-field">
            <input type="text" id="standard" name="standard">
            <label for="standard">Standard</label>
          </div>
        </div>
		<div class="col s4">
          <div class="input-field">
            <input type="text" id="section" name="section">
            <label for="section">Section</label>
          </div>
        </div>
		
        <div class="col s4">
          <div class="input-field">
            <select name="gender" id="gender">
              <option value=""></option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
            <label for="gender">Gender</label>
          </div>
        </div>

		<div class="col s4">
          <div class="input-field">
            <input type="text" id="annualFee" name="annualFee">
            <label for="annualFee">Annual Fee</label>
          </div>
        </div>

		<div class="col s4">
          <div class="input-field">
            <input type="text" id="currentPayableFees" name="currentPayableFees">
            <label for="currentPayableFees">Current Payable Fees</label>
          </div>
        </div>
		
      </div>
      <div class="row">
        <div class="col s4">
          <p><span class="req">*</span> Active</p>
          <p>
            <label>
              <input type="radio" name="isActive" id="isActive1" class="with-gap" value="1" checked>
              <span>Yes</span></label>
            <label>
              <input type="radio" name="isActive" id="isActive2" class="with-gap" value="0"/>
              <span>No</span></label>
          </p>
        </div>
    
    <!--     <div class="col s4">
          <p><span class="req">*</span> Approved</p>
          <p>
            <label>
              <input type="radio" name="isApproved" id="isApproved1" class="with-gap" value="1" >
              <span>Yes</span></label>
            <label>
              <input type="radio" name="isApproved" id="isApproved2" class="with-gap" value="0" checked/>
              <span>No</span></label>
          </p>
        </div> -->
      </div>
    </div>
    <div class="modal-footer" style="text-align:center;">
      <input type="hidden" name="schoolID" id="schoolID" value="<?php echo $schoolID;?>"/>
      <input type="hidden" name="studentID" id="studentID" value=""/>
	  <input type="hidden" name="parentID" id="parentID" value=""/>
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
		$('#ModalBox').modal('open');
	});
	$('#tableData').on('click', 'a.btnEdit', function (e){	
		resetForm();
		var table = $('#tableData').DataTable();
		var rows = table.row($(this).closest('tr')).data();
		var studentID = rows["studentID"];
		getRow(studentID);
		$('#ModalBox').modal('open');
	});	
	$('#tableData').on('click', 'a.btnDelete', function (e){
		var table = $('#tableData').DataTable();
		var rows = table.row($(this).closest('tr')).data();
		var studentID = rows["studentID"];
		if(studentID > 0){
			swal({text: "Are you sure, you want to delete the selected record?",icon: 'warning',closeOnClickOutside: false,buttons: {delete: 'Yes',cancel: 'No'}}).then(function (willDelete){
				if(willDelete){
					deleteRow(studentID);
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
				studentName: {
					required: true,
				},
				isActive: {
					required: true
				}
			},
			messages: {
				studentName: {
					required: "Please enter student",
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
	$.ajax({url: serviceUrl+'liststudent',type: "POST",data:{'schoolID':'<?php echo $schoolID;?>'},success: function (respsone){
		var result = JSON.parse(respsone);
		if (result["Status"] == "true"){		
			$('#tableData').DataTable({
   			    "searching": true,
				"bPaginate": false,
				"destroy" : true,
				"data": result.resultArray,
				"columns": 
				[
					{"data": "studentID"},
					{"data": "studentName"},
					{"data": "parentName"},					
					{"data": "mobileNo"},
					{"data": "gender"},
					{"data": "isApproved"},
					{"data": "isActive"},
					{"data": "Action"},
				],
				columnDefs: 
				[
					{targets: [0],visible: false},
					{targets:[1],visible: true, className : "text-center",
					render: function ( data, type, row, meta ) {
						return row.sfirstName+" "+row.slastName;
					}},
					{targets:[2],visible: true, className :"text-center",
						render:function( data, type, row, meta ){
						return row.pfirstName+" "+row.plastName;	
					}},
					{targets:[3],visible: true},
					{targets:[4],visible: true},
					{targets:[5],visible: true,className : "text-center",
					render: function ( data, type, row, meta ) {
						if(data == 1 ) {
							return 'Yes';
						}else {
							return 'No';
						}
					}},
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
	$(frm).ajaxSubmit({url:serviceUrl+'poststudent',beforeSubmit: function (formData, jqForm, options){
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
function deleteRow(studentID){
	$.post(serviceUrl+'deletestudent',{"studentID": studentID}, function (data){
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
function getRow(studentID){
	$.post(serviceUrl+'getstudent',{"studentID": studentID}, function (data){
		result = JSON.parse(data);
		if (result["Status"] == "true"){
			row = result.resultArray;
			$("#studentID").val(row["studentID"]);
			$("#sfirstName").val(row["sfirstName"]);
			$("#slastName").val(row["slastName"]);
			$("#pfirstName").val(row["pfirstName"]);
			$("#plastName").val(row["plastName"]);
			$("#relationship").val(row["relationship"]);
			$("#gender").val(row["gender"]);
			$("#mobileNo").val(row["mobileNo"]);
			$("#standard").val(row["standard"]);
			$("#section").val(row["section"]);
			$("#annualFee").val(row["annualFee"]);
			$("#currentPayableFees").val(row["currentPayableFees"]);
			$("#parentID").val(row["parentID"]);
			
			if(row["isActive"]==1){
				$("#isActive1").prop("checked", true);
			}else{
				$("#isActive2").prop("checked", true);
			}
			if(row["isApproved"]==1){
				$("#isApproved1").prop("checked", true);
			}else{
				$("#isApproved2").prop("checked", true);
			}
			$("#frm label").addClass("active");
			$("#sfirstName").focus();
			$('select').formSelect();
		}else{
			swal(result["Message"],{icon: "error",closeOnClickOutside: false});
		}
	});
}
function resetForm(){
	$("#frm").validate().resetForm();
	$('#frm')[0].reset();
	$('#frm').find("#studentID").val(0);
}
</script>
</body>
</html>