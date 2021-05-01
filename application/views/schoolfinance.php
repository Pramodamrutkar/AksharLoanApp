<?php $pageTitle = 'Student';?>
<!DOCTYPE html>
<html>
<head>
<?php include('include.php');?>
<style>
/* Style the tab */
.tab {
  overflow: hidden;
  border: 1px solid #ccc;
  background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab button {
  background-color: inherit;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  transition: 0.3s;
  font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
  background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
  display: none;
  padding: 6px 12px;
  border: 1px solid #ccc;
  border-top: none;
}
.dropdown-content{
  top: 0px !important;
}
</style>
</head>
<body>
<?php include('header.php');?>
<div id="main">
  <div class="row">
    <div class="breadcrumbs-inline pt-2" id="breadcrumbs-wrapper" style="padding:20px 0 0 0;">
      <div class="container">
        <div class="row">
          <div class="col s8 breadcrumbs-left">
            <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span><?php echo $schoolName;?></span></h5>
            <ol class="breadcrumbs mb-0">
              <li class="breadcrumb-item active" id="breadcrumbTitle"><?php echo $pageTitle;?> Finance Settings	</li>
            </ol>
          </div>
          <div class="col s4" align="right"> <a href="<?php echo base_url('service/school');?>" class="mb-6 btn waves-effect waves-light gradient-45deg-green-teal"><i class="material-icons left">list</i> <span class="hide-on-small-onl">Back</span></a> </div>
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

<div class="tab">
  <button class="tablinks active" id="parent" data-name="parent" onclick="openCity(event, 'Parent')">Student/Parent Loan Setting</button>
  <button class="tablinks" data-name="personal" onclick="openCity(event, 'Personal')">Personal Loan Setting</button>
  <button class="tablinks" data-name="staff" onclick="openCity(event, 'Staff')">Staff Loan Setting</button>
</div>

<div id="Parent" class="tabcontent">
        <form name="frm" id="frm" method="post" enctype="multipart/form-data" class="uk-form-stacked">
                    <table id="tableData" class="table" style="width:100%;">
                      <thead>
                        <tr>
                          <th>Money Retaintion %</th>
                          <td><input type="text" id="retaintion" name="retaintion"></td>
                          <th>Max Loan Per Parent</th>
                          <td><input type="text" id="maxLoan" name="maxLoan"></td>
                        </tr>
                        <tr>
                          <th>EMI Options (Monthly)</th>
                          <td><select name="emiLevel[]" id="emiLevel" multiple>
                                  <?php 
                                  for($i=1;$i<12;$i++){
                                    echo "<option value=$i>$i</option>";
                                  }
                                  ?>
                              </select>
                          </td>

                          <th>Collection Charges</th>
                          <td><input type="text" id="collectionCharges" name="collectionCharges"></td>
                        </tr>
                        <tr>
                          
                        </tr>
                        <tr>
                          <th>Rate Of Interest (Monthly)</th>
                          <td><input type="text" id="interestRate" name="interestRate"></td>
                          <th>Interest Type</th>
                          <td><select name="interestType" id="interestType">
                              <option value=""></option>
                              <option value="Flat">Flat Rate</option>
                              <option value="Reducing">Reducing Balace</option>
                            </select>
                          </td>
                        </tr>
                        <tr>
                          <th>Loan Gaps (Days)</th>
                          <td><input type="text" id="loanGaps" name="loanGaps" placeholder="Days"></td>
                          <th>Late Payment Charges (Per Day charges)</th>
                          <td><input type="text" id="perdaycharge" name="perdaycharge"></td>
                        </tr>
                        <tr>
                          <th>Processing Fees</th>
                          <td><input type="text" id="fees" name="fees"></td>
                          <th>Processing Fees GST ( Percent %)</th>
                          <td><input type="text" id="processingFeesgst" name="processingFeesgst"></td>
                        </tr>
                        <tr>
                          <th>Delay allowed for repeat loans eligibility (No of days)</th>
                          <td><input type="text" id="delaynoofDays" name="delaynoofDays" placeholder="Days"></td>
                          <th>Number of outstanding loans per child</th>
                          <td><input type="text" id="outstandingloanperChild" name="outstandingloanperChild"></td>
                        </tr>

                        <tr>
                          <th>GST TYPE</th>
                          <td><select name="gstType" id="gstType" onchange="getGstType()">
                              <option value="">Select Type</option>
                              <option value="MH">Maharashtra</option>
                              <option value="outsideMH">Outside Maharashtra</option>
                            </select></td>
                            <th>Advanced EMI</th>
                            <td>
                                <label>
                                  <input type="radio" name="advancedEmi" id="advancedEmi1" class="with-gap" value="1">
                                  <span>Yes</span></label>
                                <label>
                                  <input type="radio" name="advancedEmi" id="advancedEmi2" class="with-gap" value="0"checked>
                                  <span>No</span></label>
                            </td>
                        </tr>
                        <tr id="gst_row">
                          <th>GST</th>
                          <td><input type="text" id="gst" name="gst"></td>
                          <th>Student Caping Loan Amount</th>
                          <td><input type="text" id="loancapAmount" name="loancapAmount"></td>
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

<div id="Personal" class="tabcontent">
      <form name="frm2" id="frm2" method="post" enctype="multipart/form-data" class="uk-form-stacked">
              <table id="tableData2" class="table" style="width:100%;">
                <thead>
                  <tr>
                    <th>Max Loan Amount</th>
                    <td><input type="text" id="maxLoan2" name="maxLoan"></td>
                    <th>Installment Type</th>
                    <td>
                      <select name="installmentType" id="installmentType2">
                        <option value="30">Monthly</option>
                        <option value="14">biweekly</option>
                      </select>
                    </td>
                  </tr>

                  <tr>
                  
                    <th>Rate Of Interest</th>
                    <td><input type="text" id="interestRate2" name="interestRate"></td>
                    <th>Interest Type</th>
                    <td><select name="interestType" id="interestType2">
                        <option value=""></option>
                        <option value="Flat">Flat Rate</option>
                        <option value="Reducing">Reducing Balace</option>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <th>Number of Installments</th>
                    <td><input type="text" id="noofInstallments2" name="noofInstallments"></td>
                    <th>Advanced EMI</th>
                            <td>
                               <p> <label>
                                  <input type="radio" name="advancedEmi" id="peradvancedEmi1" class="with-gap" value="1">
                                  <span>Yes</span></label>
                               </p>
                               <p>
                                <label>
                                  <input type="radio" name="advancedEmi" id="peradvancedEmi2" class="with-gap" value="0"checked>
                                  <span>No</span></label>
                               </p>
                            </td>
                  </tr>                
                  <tr>
                    <th><p>Personal Loan eligibility </p><p> completion of Fee loan EMIs </p></th>
                    <td><input type="text" id="loanEligibilitypostfeeloan" name="loanEligibilitypostfeeloan"></td>
                    <th><p>Increase in loan amount on </p> <p> successful completion of previous loan (%)</p></th>
                    <td><input type="text" id="previousLoansuccessper" name="previousLoansuccessper"></td>
                  </tr>

                  <tr>
                    <td colspan="2"><input type="hidden" name="schoolID" id="schoolID" value="<?php echo $schoolID;?>"/>
                      <input type="hidden" name="personalfinanceID" id="personalfinanceID" value=""/>
                      <button type="submit" name="btnSubmit" id="btnSubmit" class="btn waves-effect waves-light gradient-45deg-green-teal"> <i class="material-icons left">save</i> Save</button>
                      
                  </tr>
                </thead>
              </table>
      </form>                            
</div>

<div id="Staff" class="tabcontent">
<form name="frm3" id="frm3" method="post" enctype="multipart/form-data" class="uk-form-stacked">
              <table id="tableData3" class="table" style="width:100%;">
                <thead>
                  <tr>
                    <th>Max Loan</th>
                    <td><input type="text" id="maxLoan3" name="maxLoan"></td>
                    <th><p>Initial Loan Amount </p><p>(% of monthly salary)</p></th>
                    <td>
                      <input type="text" id="initialLoanAmount" name="initialLoanAmount">
                    </td>
                  </tr>

                  <tr>
                    <th>Rate Of Interest</th>
                    <td><input type="text" id="interestRate3" name="interestRate"></td>
                    <th>Interest Type</th>
                    <td><select name="interestType" id="interestType3">
                        <option value=""></option>
                        <option value="Flat">Flat Rate</option>
                        <option value="Reducing">Reducing Balace</option>
                      </select>
                    </td>
                  </tr>

                  <tr>
                    <th>EMI Options (Monthly)</th>
                          <td><select name="emiLevel[]" id="emiLevel2" multiple>
                                  <?php 
                                  for($i=1;$i<12;$i++){
                                    echo "<option value=$i>$i</option>";
                                  }
                                  ?>
                              </select>
                    </td>              
                  
                    <th>Advanced EMI</th>
                            <td>
                               <p> <label>
                                  <input type="radio" name="advancedEmi" id="sadvancedEmi1" class="with-gap" value="1">
                                  <span>Yes</span></label>
                               </p>
                               <p>
                                <label>
                                  <input type="radio" name="advancedEmi" id="sadvancedEmi2" class="with-gap" value="0"checked>
                                  <span>No</span></label>
                               </p>
                            </td>
                   
                  </tr>                
                  <tr>
                    <th><p>Delay allowed for repeat loans eligibility </p><p> (in the last 3 EMI payment) : days</p></th>
                    <td><input type="text" id="delayinrepeatloan" name="delayinrepeatloan"></td>
                    <th><p>Repeat loan increase amount :</p> <p> % of previous loan</p></th>
                    <td><input type="text" id="repeatLoanincreaseAmountper" name="repeatLoanincreaseAmountper"></td>
                  </tr>
                  <tr>
                    <th><p>Repeat loan eligibility : X EMIs repaid</p></th>
                    <td><input type="text" id="repeatloanEmiRepaid" name="repeatloanEmiRepaid"></td>
                   
                  </tr>               
                  <tr>
                    <td colspan="2"><input type="hidden" name="schoolID" id="schoolID" value="<?php echo $schoolID;?>"/>
                      <input type="hidden" name="stafffinanceID" id="stafffinanceID" value=""/>
                      <button type="submit" name="btnSubmit" id="btnSubmit" class="btn waves-effect waves-light gradient-45deg-green-teal"> <i class="material-icons left">save</i> Save</button>
                      
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
    </div>
  </section>
</div>
<?php include('footer.php');?>
<script type ="text/javascript">
$(document).ready(function(){
	getRow(<?php echo $schoolID;?>);
  getRow2(<?php echo $schoolID;?>);
  getRow3(<?php echo $schoolID;?>);
  setTimeout(function(){
    openCity('active', 'Parent');
  },500);
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

  if ($("#frm2").length > 0){
		var validator = $("#frm2").validate({
			errorElement: 'div',
			errorPlacement: function(error, element){
				if (element.attr("type") == "radio"){
					error.insertAfter(".icheck-inline");
				}else{
					error.insertAfter(element);
				}
			},
			rules: {
				maxLoan: {
					required: true,
				}
			},
			messages: {
				retaintion: {
					required: "Please enter finance",
				}
			},
			submitHandler: function(form){
				saveRow2("#frm2");
			}
		});
	}
  
  if ($("#frm3").length > 0){
		var validator = $("#frm3").validate({
			errorElement: 'div',
			errorPlacement: function(error, element){
				if (element.attr("type") == "radio"){
					error.insertAfter(".icheck-inline");
				}else{
					error.insertAfter(element);
				}
			},
			rules: {
				maxLoan: {
					required: true,
				}
			},
			messages: {
				retaintion: {
					required: "Please enter finance",
				}
			},
			submitHandler: function(form){
				saveRow3("#frm3");
			}
		});
	}

});
function saveRow(frm){
	$(frm).ajaxSubmit({url:serviceUrl+'postfinance',beforeSubmit: function (formData, jqForm, options){
		var queryString = $.param(formData);
	},clearForm: false,resetForm: false,success: function (responseText, statusText, xhr, $form){
		var result = JSON.parse(responseText);
      location.reload();
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

function saveRow2(frm){
	$(frm).ajaxSubmit({url:serviceUrl+'postpersonalfinance',beforeSubmit: function (formData, jqForm, options){
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

function saveRow3(frm){
	$(frm).ajaxSubmit({url:serviceUrl+'poststafffinance',beforeSubmit: function (formData, jqForm, options){
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
      if(row != undefined){
     	$("#financeID").val(row["financeID"]);
			$("#retaintion").val(row["retaintion"]);
			$("#maxLoan").val(row["maxLoan"]);
      var emileveles= row["emiLevel"];
      $('#emiLevel').val(emileveles.split(','));
			$("#fees").val(row["fees"]);
			$("#interestType").val(row["interestType"]);
			$("#interestRate").val(row["interestRate"]);
      $("#loanGaps").val(row["loanGaps"]);
      $("#collectionCharges").val(row["collectionCharges"]);
      $("#delaynoofDays").val(row["delaynoofDays"]);
      $("#perdaycharge").val(row["perdaycharge"]);
			$("#processingFeesgst").val(row["processingFeesgst"]);
			$("#outstandingloanperChild").val(row["outstandingloanperChild"]);
			if(row["advancedEmi"]==1){
				$("#advancedEmi1").prop("checked", true);
			}else{
				$("#advancedEmi2").prop("checked", true);
			}
      $("#gstType").val(row["gstType"]);
      $("#gst").val(row["gst"]);
      $("#igst").val(row["igst"]);
      $("#loancapAmount").val(row["loancapAmount"]);
      
			$("#frm label").addClass("active");
			$("#retaintion").focus();
			$('select').formSelect();
      getGstType();
    }
		}else{
			swal(result["Message"],{icon: "error",closeOnClickOutside: false});
		}
	});
}

function getRow2(schoolID){
	$.post(serviceUrl+'getpersonalfinance',{"schoolID": schoolID}, function (data){
		result = JSON.parse(data);
		if (result["Status"] == "true"){
      row = result.resultArray;
      if(row != undefined){
        $("#personalfinanceID").val(row["personalfinanceID"]);
        $("#maxLoan2").val(row["maxLoan"]);
        $("#interestType2").val(row["interestType"]);
        $("#interestRate2").val(row["interestRate"]);
        $("#noofInstallments2").val(row["noofInstallments"]);
        $("#installmentType2").val(row["installmentType"]);
        $("#loanEligibilitypostfeeloan").val(row["loanEligibilitypostfeeloan"]);
        $("#previousLoansuccessper").val(row["previousLoansuccessper"]);
        if(row["advancedEmi"]==1){
          $("#peradvancedEmi1").prop("checked", true);
        }else{
          $("#peradvancedEmi2").prop("checked", true);
        }
      }
    }else{
			swal(result["Message"],{icon: "error",closeOnClickOutside: false});
		}
	});
}
function getRow3(schoolID){
	$.post(serviceUrl+'getstafffinance',{"schoolID": schoolID}, function (data){
		result = JSON.parse(data);
		if (result["Status"] == "true"){
      row = result.resultArray;
      if(row != undefined){
          var emilevel2= row["emiLevel"];
          $("#stafffinanceID").val(row["stafffinanceID"]);
          $("#maxLoan3").val(row["maxLoan"]);
          $("#interestType3").val(row["interestType"]);
          $("#interestRate3").val(row["interestRate"]);
          $("#initialLoanAmount").val(row["initialLoanAmount"]);
          $('#emiLevel2').val(emilevel2.split(','));
          $("#delayinrepeatloan").val(row["delayinrepeatloan"]);
          $("#repeatLoanincreaseAmountper").val(row["repeatLoanincreaseAmountper"]);
          $("#repeatloanEmiRepaid").val(row["repeatloanEmiRepaid"]);
          if(row["advancedEmi"]==1){
            $("#sadvancedEmi1").prop("checked", true);
          }else{
            $("#sadvancedEmi2").prop("checked", true);
          }
      }
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

function openCity(evt, tabName) {
  console.log(evt);
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(tabName).style.display = "block";
  if(evt == 'active'){
      $('#parent').addClass('active');
  }else{
    evt.currentTarget.className += " active";
  }
  
}


</script>
</body>
</html>