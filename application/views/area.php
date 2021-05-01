<?php $pageTitle = 'Area';?>
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
                        <th>Area</th>
                        <th>Description</th>
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
        <div class="col s12">
          <div class="input-field">
            <input type="text" id="areaName" name="areaName">
            <label for="areaName"><span class="req">*</span> Name</label>
          </div>
        </div>
        <div class="col s12">
          <div class="input-field">
            <textarea id="description" name="description" class="materialize-textarea"></textarea>
            <label for="description">Description</label>
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
      <input type="hidden" name="areaID" id="areaID" value=""/>
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
		var areaID = rows["areaID"];
		getRow(areaID);
		$('#ModalBox').modal('open');
	});	
	$('#tableData').on('click', 'a.btnDelete', function (e){
		var table = $('#tableData').DataTable();
		var rows = table.row($(this).closest('tr')).data();
		var areaID = rows["areaID"];
		if(areaID > 0){
			swal({text: "Are you sure, you want to delete the selected record?",icon: 'warning',closeOnClickOutside: false,buttons: {delete: 'Yes',cancel: 'No'}}).then(function (willDelete){
				if(willDelete){
					deleteRow(areaID);
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
				areaName: {
					required: true,
				},
				isActive: {
					required: true
				}
			},
			messages: {
				areaName: {
					required: "Please enter area",
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
	$.ajax({url: serviceUrl+'listarea',type: "POST",data:{},success: function (respsone){
		var result = JSON.parse(respsone);
		if (result["Status"] == "true"){		
			$('#tableData').DataTable({
   			    "searching": true,
				"bPaginate": false,
				"destroy" : true,
				"data": result.resultArray,
				"columns": 
				[
					{"data": "areaID"},
					{"data": "areaName"},
					{"data": "description"},
					{"data": "isActive"},
					{"data": "Action"},
				],
				columnDefs: 
				[
					{targets: [0],visible: false},
					{targets:[1],visible: true},
					{targets:[2],visible: true},
					{targets:[3],visible: true,className : "text-center",
					render: function ( data, type, row, meta ) {
						if(data == 1 ) {
							return 'Active';
						}else {
							return 'Inactive';
						}
					}},
					{targets:[4],visible: true ,className : "text-center",sorting:false,
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
	$(frm).ajaxSubmit({url:serviceUrl+'postarea',beforeSubmit: function (formData, jqForm, options){
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
function deleteRow(areaID){
	$.post(serviceUrl+'deletearea',{"areaID": areaID}, function (data){
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
function getRow(areaID){
	$.post(serviceUrl+'getarea',{"areaID": areaID}, function (data){
		result = JSON.parse(data);
		if (result["Status"] == "true"){
			row = result.resultArray;
			$("#areaID").val(row["areaID"]);
			$("#areaName").val(row["areaName"]);
			$("#description").val(row["description"]);
			if(row["isActive"]==1){
				$("#isActive1").prop("checked", true);
			}else{
				$("#isActive2").prop("checked", true);
			}
			$("#frm label").addClass("active");
			$("#areaName").focus();
			$('select').formSelect();
		}else{
			swal(result["Message"],{icon: "error",closeOnClickOutside: false});
		}
	});
}
function resetForm(){
	$("#frm").validate().resetForm();
	$('#frm')[0].reset();
	$('#frm').find("#areaID").val(0);
}
</script>
</body>
</html>