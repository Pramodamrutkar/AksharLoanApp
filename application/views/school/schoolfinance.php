<?php $pageTitle = 'Student';?>
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
              <li class="breadcrumb-item active" id="breadcrumbTitle"><?php echo $pageTitle;?> Finance Settings	</li>
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
                  <form name="frm" id="frm" method="post" enctype="multipart/form-data" class="uk-form-stacked">
                    <table id="tableData" class="table" style="width:100%;">
                      <thead>
                        <tr>
                          <th>Money Retaintion %</th>
                          <td><input type="text" id="retaintion" name="retaintion"></td>
                        </tr>
                        <tr>
                          <th>Max Loan Per Parent</th>
                          <td><input type="text" id="maxLoan" name="maxLoan"></td>
                        </tr>
                        <tr>
                          <th>EMI Level Per Parent</th>
                          <td><input type="text" id="emiLevel" name="emiLevel"></td>
                        </tr>
                        <tr>
                          <th>Processing Fees Per Parent</th>
                          <td><input type="text" id="fees" name="fees"></td>
                        </tr>
                        <tr>
                          <th>Interest Type</th>
                          <td><select name="interestType" id="interestType">
                              <option value=""></option>
                              <option value="Flat">Flat</option>
                              <option value="Reducing">Reducing</option>
                            </select></td>
                        </tr>
                        <tr>
                          <th>Interest Charges</th>
                          <td><input type="text" id="interestRate" name="interestRate"></td>
                        </tr>
                        <tr>
                          <th>Loan Gaps</th>
                          <td><input type="text" id="loanGaps" name="loanGaps" placeholder="Days"></td>
                        </tr>
                        <tr>
                          <th>GST TYPE</th>
                          <td><select name="gstType" id="gstType" onchange="getGstType()">
                              <option value="">Select Type</option>
                              <option value="MH">Maharashtra</option>
                              <option value="outsideMH">Outside Maharashtra</option>
                            </select></td>
                        </tr>
                        <tr id="gst_row">
                          <th>GST</th>
                          <td><input type="text" id="gst" name="gst"></td>
                        </tr>
                        <tr id="igst_row">
                          <th>IGST</th>
                          <td><input type="text" id="igst" name="igst"></td>
                        </tr>
                        <tr>
                          <td colspan="2"><input type="hidden" name="schoolID" id="schoolID" value="<?php echo $schoolID;?>"/>
                            <input type="hidden" name="financeID" id="financeID" value=""/>
                            <button type="submit" name="btnSubmit" id="btnSubmit" class="btn waves-effect waves-light gradient-45deg-green-teal"> <i class="material-icons left">save</i> Save</button>
                            <button type="button" name="btnCancel" id="btnCancel" class="btnCancel btn waves-effect waves-light gradient-45deg-purple-deep-orange"> <i class="material-icons left">close</i> Cancel</button></td>
                        </tr>
                      </thead>
                    </table>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php include('footer.php');?>
<script type ="text/javascript">
$(document).ready(function(){
	getRow(<?php echo $schoolID;?>);
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
				retaintion: {
					required: true,
				},
				isActive: {
					required: true
				}
			},
			messages: {
				retaintion: {
					required: "Please enter finance",
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
function saveRow(frm){
	$(frm).ajaxSubmit({url:serviceUrl+'postfinance',beforeSubmit: function (formData, jqForm, options){
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
function getRow(schoolID){
	$.post(serviceUrl+'getfinance',{"schoolID": schoolID}, function (data){
		result = JSON.parse(data);
		if (result["Status"] == "true"){
      
			row = result.resultArray;
			$("#financeID").val(row["financeID"]);
			$("#retaintion").val(row["retaintion"]);
			$("#maxLoan").val(row["maxLoan"]);
			$("#emiLevel").val(row["emiLevel"]);
			$("#fees").val(row["fees"]);
			$("#interestType").val(row["interestType"]);
			$("#interestRate").val(row["interestRate"]);
      $("#loanGaps").val(row["loanGaps"]);
      $("#gstType").val(row["gstType"]);
      $("#gst").val(row["gst"]);
      $("#igst").val(row["igst"]);
			$("#frm label").addClass("active");
			$("#retaintion").focus();
			$('select').formSelect();
      getGstType();
		}else{
			swal(result["Message"],{icon: "error",closeOnClickOutside: false});
		}
	});
}
function resetForm(){
	$("#frm").validate().resetForm();
	$('#frm')[0].reset();
	$('#frm').find("#financeID").val(0);
}
function getGstType(){
  var gstType = $('#gstType').val();
  console.log('GST TYPE'+gstType);
  if(gstType === 'MH'){
    $('#gst_row').show();
    $('#igst_row').hide();
  }else if (gstType === "outsideMH"){
    $('#igst_row').show();
    $('#gst_row').show();
  }else{
    $('#igst_row').hide();
    $('#gst_row').hide();
  }
}
</script>
</body>
</html>