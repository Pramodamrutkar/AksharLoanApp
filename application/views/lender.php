<?php $pageTitle = 'Lender Details';?>
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
                        <th>Lender ID</th>
                        <th>Lender Name</th>
                        <th>Email</th>
                        <th>Mobile No</th>
                        <th>Limit</th>
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
            <input type="text" id="lenderName" name="lenderName">
            <label for="lenderName"><span class="req">*</span>Lender Name</label>
          </div>
        </div>
        <div class="col s6">
          <div class="input-field">
            <input type="text" id="lenderEmail" name="lenderEmail">
            <label for="lenderEmail"><span class="req">*</span>Lender Email</label>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col s6">
          <div class="input-field">
            <input type="text" id="lenderMobile" name="lenderMobile">
            <label for="lenderMobile"><span class="req">*</span>Lender Mobile</label>
          </div>
        </div>
        <div class="col s6">
          <div class="input-field">
            <input type="text" id="lenderLimit" name="lenderLimit">
            <label for="lenderLimit"><span class="req">*</span>Lender Limit</label>
          </div>
        </div>
      </div>
      
    </div>
    <div class="modal-footer" style="text-align:center;">
      <input type="hidden" name="lenderID" id="lenderID" value=""/>
      <button type="submit" name="btnSubmit" id="btnSubmit" class="btn waves-effect waves-light gradient-45deg-green-teal"> <i class="material-icons left">save</i> Save</button>
      <button type="button" name="btnCancel" id="btnCancel" class="btnCancel btn waves-effect waves-light gradient-45deg-purple-deep-orange"> <i class="material-icons left">close</i> Cancel</button>
    </div>
  </form>
</div>
<?php include('footer.php');?>

</body>
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
		var lenderID = rows["lenderID"];
		getRow(lenderID);
		$('#ModalBox').modal('open');
	});	
	$('#tableData').on('click', 'a.btnDelete', function (e){
		var table = $('#tableData').DataTable();
		var rows = table.row($(this).closest('tr')).data();
		var lenderID = rows["lenderID"];
		if(lenderID > 0){
			swal({text: "Are you sure, you want to delete the selected record?",icon: 'warning',closeOnClickOutside: false,buttons: {delete: 'Yes',cancel: 'No'}}).then(function (willDelete){
				if(willDelete){
					deleteRow(lenderID);
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
				lenderName: {
					required: true,
				}
			},
			messages: {
				lenderName: {
					required: "Please enter Designation Name",
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
	$.ajax({url: serviceUrl+'listlender',type: "POST",data:{},success: function (respsone){
		var result = JSON.parse(respsone);
        
		if (result["Status"] == "true"){		
			$('#tableData').DataTable({
   			    "searching": false,
				"bPaginate": false,
				"destroy" : true,
				"data": result.resultArray,
				"columns": 
				[
					{"data": "lenderID"},
					{"data": "lenderName"},
					{"data": "lenderEmail"},
                    {"data": "lenderMobile"},
					{"data": "lenderLimit"},
					{"data": "Action"},
				],
				columnDefs: 
				[
					{targets: [0],visible: false},
                    {targets:[1],visible: true},
					{targets:[2],visible: true},
					{targets:[3],visible: true},
					{targets:[4],visible: true},
					 {targets:[5],visible: true, className : "text-center",sorting:false,
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
	$(frm).ajaxSubmit({url:serviceUrl+'postlender',beforeSubmit: function (formData, jqForm, options){
		var queryString = $.param(formData);
	},clearForm: false,resetForm: false,success: function (responseText, statusText, xhr, $form){
		var result = JSON.parse(responseText);
		if (result["Status"] == "true"){	
			swal({text: result["Message"],icon: 'success',closeOnClickOutside: false});
				//if(willDelete){
					listRow();
				//}
			//});
		}else{
			swal(result["Message"],{icon: "error",closeOnClickOutside: false});
		}		
	}});
}
function deleteRow(lenderID){
	$.post(serviceUrl+'deletelender',{"lenderID": lenderID}, function (data){
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
function getRow(lenderID){
	$.post(serviceUrl+'getlender',{"lenderID": lenderID}, function (data){
		result = JSON.parse(data);
		if (result["Status"] == "true"){
			row = result.resultArray;
			$("#lenderID").val(row["lenderID"]);
			$("#lenderName").val(row["lenderName"]);
            $("#lenderEmail").val(row["lenderEmail"]);
            $("#lenderMobile").val(row["lenderMobile"]);
            $("#lenderLimit").val(row["lenderLimit"]);
			$("#frm label").addClass("active");
			$("#lenderName").focus();
			$('select').formSelect();
		}else{
			swal(result["Message"],{icon: "error",closeOnClickOutside: false});
		}
	});
}
function resetForm(){
	$("#frm").validate().resetForm();
	$('#frm')[0].reset();
}
</script>
</html>