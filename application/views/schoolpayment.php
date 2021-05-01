<?php $pageTitle = 'School Payment';?>
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
            <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span><?php echo $schoolName;?></span></h5>
            <ol class="breadcrumbs mb-0">
              <li class="breadcrumb-item active" id="breadcrumbTitle"><?php echo $pageTitle;?> List </li>
            </ol>
          </div>
          <div class="col s4" align="right"> <a href="<?php echo base_url('service/school');?>" class="mb-6 btn waves-effect waves-light gradient-45deg-green-teal"><i class="material-icons left">list</i> <span class="hide-on-small-onl">Back</span></a> <a class="btnAdd mb-6 btn waves-effect waves-light gradient-45deg-green-teal"><i class="material-icons left">add</i> <span class="hide-on-small-onl">Add</span></a> </div>
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
                        <th>Sr No</th>
                        <th>Payment Type</th>
                        <th>Payment Date</th>
                        <th>Amount</th>
                        <th>Debit/Credit</th>
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
                    <select name="paymentType" id="paymentType">
                        <option value="cash">Cash</option>
                        <option value="online">Online</option>
                    </select>
                    <label for="paymentType">Payment Type</label>
          </div>
        </div>
		<div class="col s6">
          <div class="input-field">
			<input type="date" name="paymentDate" id="paymentDate"  AUTOCOMPLETE="off"/>
            <label for="paymentDate">Payment Date</label>
          </div>
        </div>
		<div class="col s6">
          <div class="input-field">
            <input type="text" id="paymentAmount" name="paymentAmount">
            <label for="paymentAmount">Amount</label>
          </div>
        </div>
        <div class="col s6">
          <div class="input-field">
                <select name="debitCredit" id="debitCredit">
                        <option value="credit">Credit</option>
                        <option value="debit">Debit</option>
                </select>
            <label for="debitCredit">Debit/Credit</label>
          </div>
        </div>

		<div class="col s8">
          <div class="input-field">
          <label for="narration">Narration</label>
            <textarea id="narration" name="narration" class="materialize-textarea" rows="15" cols="50"></textarea>
            
          </div>
        </div>

      </div>
    </div>
    <div class="modal-footer" style="text-align:center;">
      <input type="hidden" name="schoolID" id="schoolID" value="<?php echo $schoolID;?>"/>
      <input type="hidden" name="paymentID" id="paymentID" value=""/>
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
		var paymentID = rows["paymentID"];
		getRow(paymentID);
		$('#ModalBox').modal('open');
	});	
	$('#tableData').on('click', 'a.btnDelete', function (e){
		var table = $('#tableData').DataTable();
		var rows = table.row($(this).closest('tr')).data();
		var paymentID = rows["paymentID"];
		if(paymentID > 0){
			swal({text: "Are you sure, you want to delete the selected record?",icon: 'warning',closeOnClickOutside: false,buttons: {delete: 'Yes',cancel: 'No'}}).then(function (willDelete){
				if(willDelete){
					deleteRow(paymentID);
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
				paymentType: {
					required: true,
				}
			},
			messages: {
				paymentType: {
					required: "Please select Payment Type",
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
	$.ajax({url: serviceUrl+'listschoolpayment',type: "POST",data:{'schoolID':'<?php echo $schoolID;?>'},success: function (respsone){
		var result = JSON.parse(respsone);
		if (result["Status"] == "true"){		
			$('#tableData').DataTable({
   			    "searching": true,
				"bPaginate": false,
				"destroy" : true,
				"data": result.resultArray,
				"columns": 
				[
					{"data": "paymentID"},
					{"data": "paymentType"},
					{"data": "paymentDate"},					
					{"data": "paymentAmount"},
					{"data": "debitCredit"},
					{"data": "Action"},
				],
				columnDefs: 
				[
					{targets:[0],visible: false},
					{targets:[1],visible: true},
					{targets:[2],visible: true},
					{targets:[3],visible: true},
					{targets:[4],visible: true},
					{targets:[5],visible: true ,className : "text-center",sorting:false,
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
	$(frm).ajaxSubmit({url:serviceUrl+'postschoolpayment',beforeSubmit: function (formData, jqForm, options){
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
function deleteRow(paymentID){
	$.post(serviceUrl+'deleteschoolPayment',{"paymentID": paymentID}, function (data){
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
function getRow(paymentID){
	$.post(serviceUrl+'getschoolpayment',{"paymentID": paymentID}, function (data){
		result = JSON.parse(data);
		if (result["Status"] == "true"){
			row = result.resultArray;
			$("#paymentID").val(row["paymentID"]);
			$("#paymentType").val(row["paymentType"]);
            document.getElementById("paymentDate").setAttribute("value",row["paymentDate"]);
			$("#paymentAmount").val(row["paymentAmount"]);
			$("#debitCredit").val(row["debitCredit"]);
			$("#narration").val(row["narration"]);
			$("#frm label").addClass("active");
			$("#paymentType").focus();
			$('select').formSelect();
            setTimeout(function(){$("#debitCredit,#paymentType").removeClass('active');},1000);
		}else{
			swal(result["Message"],{icon: "error",closeOnClickOutside: false});
		}
	});
}
function resetForm(){
	$("#frm").validate().resetForm();
	$('#frm')[0].reset();
	$('#frm').find("#paymentID").val(0);
}
</script>
</body>
</html>