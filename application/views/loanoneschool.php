<?php $pageTitle = 'New Loans one School';
?>
<!DOCTYPE html>
<html>
<head>
<?php include('include.php');?>

<style>
.hidden{
  display: none;
}
.addfadeClass{
  pointer-events: none;
    opacity: 0.3;
}
[type=checkbox]:checked, [type=checkbox]:not(:checked) {
     position: relative !important; 
     pointer-events: all  !important; 
     opacity: 1 !important; 
     cursor:pointer;
}

table.dataTable.display tbody tr.odd>.sorting_1, table.dataTable.order-column.stripe tbody tr>.sorting_1{
  text-align:center;
}
label.photosLabel{
  font-size:15px;
}
.separatorImages{
  margin:10px 0px;
}
</style>
</head>
<body>
<?php include('header.php');?>

<div id="main">
  <div class="row">
    <div class="breadcrumbs-inline pt-2" id="breadcrumbs-wrapper">
      <div class="container">
        <div class="row">
          <div class="col s6 breadcrumbs-left">
            <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span><?php echo $pageTitle;?></span></h5>
          </div>
        </div>
      </div>
    </div>
  </div>
  <section>
    <div class="row">
      <div class="col s12">
        <div class="container">
          <div class="card">
            <div class="card-content">
            <table id="tableData" class=" stripe row-border order-column nowrap" style="width:100%;">
                    <thead>
                    <tr>
                        <th style="text-align: center;"><input type="checkbox" class="checkAll" style="width:15px;height:15px;"></th>
                        <th>Borrower Name</th>
                        <th>Student Name</th>
                        <th>Loan Amount</th>
                        <th>Tenure</th>
                        <th>School Name</th>
                        <th>Status</th>
                        <th>View</th>
                    </tr>
                    </thead>
                    <tbody>
                   
                    </tbody>
                    <tfoot>
                        <tr>
                        <th>
                          <button type="submit" name="btnSubmit2" id="btnSubmit2" onclick="getupdateclick();">Update</button></th>
                        <th></th>
                        </tr>
                    </tfoot>
                </table>
                <div id="tablerow"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

<div id="ModalBox" class="modal modal-fixed-footer" style="height: 60%;">
        <div class="modal-content">
              <button type="button" name="btnCancel2" id="btnCancel2" class="btnCancel2 btn waves-effect waves-light gradient-45deg-purple-deep-orange" style="float:right;"> <i class="material-icons left">close</i> Close</button>

                <div class="row" id="approvalblock">
                  <div class="col s12">
                    <p>Send for Lender / Approve Selected Record ?</p>
                    <p>
                        <label>
                        <input type="radio" name="isApproved" id="isApproved1" class="with-gap" value="1">
                        <span>Approve</span></label>
                    </p>
                    <p>
                        <label>
                        <input type="radio" name="isApproved" id="isApproved2" class="with-gap" value="0">
                        <span>Reject</span></label>
                    </p>
                      <p>
                          <label>
                          <input type="radio" name="isApproved" id="isApproved3" class="with-gap" value="2">
                          <span>Send for lender</span></label>
                      </p>
                </div>
              </div>
              <div id="btnSubmit2" onclick="updateApproveselStatus()" class="btn waves-effect waves-light gradient-45deg-green-teal"> <i class="material-icons left">save</i>Submit</div>
        </div>
    </div> <!----end of first modal-->

<div id="ModalBox2" class="modal modal-fixed-footer">
  <form name="frm" id="frm" method="post" enctype="multipart/form-data" class="uk-form-stacked">
    <div class="modal-content">
        <div class="row">
                <table style="width:100%">
                  <tr>
                    <th>Personal Details</th>
                    <th></th> 
                  </tr>
                 
                  <tr>
                    <td style="width:200px;"><label for="studentName">Student Name</label></td>
                    <td id="studentName"></td>
                  </tr>
                  <tr>
                    <td style="width:200px;"><label for="parentName">Parent Name</label></td>
                    <td id="parentName"></td>
                  </tr>
                  <tr>
                    <td style="width:200px;"><label for="plastName">Address</label></td>
                    <td id="address"></td>
                  </tr>
                  <tr>
                    <td style="width:200px;"><label for="plastName">Loan Amount</label></td>
                    <td id="loanAmount"></td>
                  </tr>
                  <tr>
                    <td style="width:200px;"><label for="plastName">Monthly EMI</label></td>
                    <td id="monthlyEmi"></td>
                  </tr>

                  <tr>
                    <td style="width:200px;"><label for="plastName">Current Payable Fees</label></td>
                    <td id="currentPayableFees"></td>
                  </tr>
                  <tr>
                    <td style="width:200px;"><label for="plastName">Rate of Interest</label></td>
                    <td id="roi"></td>
                  </tr>

                  <tr>
                    <td style="width:200px;"><label for="plastName">Loan Tenure (In Months)</label></td>
                    <td id="loantenure"></td>
                  </tr>

                  <tr>
                    <td style="width:200px;"><label for="plastName">Monthly Income</label></td>
                    <td id="monthlyIncome"></td>
                  </tr>

                  <tr>
                    <td style="width:200px;"><label for="plastName">Employment Type</label></td>
                    <td id="employmentType"></td>
                  </tr>
                  <tr>
                    <td style="width:200px;"><label for="plastName">Occupation</label></td>
                    <td id="occupation"></td>
                  </tr>
                  <tr>
                    <td style="width:200px;"><label for="plastName">Pan Number</label></td>
                    <td id="pannumber"></td>
                  </tr>
                  <tr>
                    <td style="width:200px;"><label for="plastName" class="photosLabel">Pan Card Image</label></td>
                    <td id=""><img id="pancard" src="" style="width:100%;margin:5px 0px;">
                    <span id="pancardspan"></span></td>
                  </tr>

                  <tr>
                    <td style="width:200px;"><label for="plastName" class="photosLabel">ID Image</label></td>
                    <td id=""> <img id="aadharFront" src="" style="width:100%;margin:5px 0px;">
                    <span id="aadharFrontspan"></span></td>
                  </tr>

                  <tr>
                    <td style="width:200px;"><label for="plastName" class="photosLabel">Aadhar Back Image</label></td>
                    <td id=""><img id="aadharBack" src="" style="width:100%;margin:5px 0px;">
                    <span id="aadharBackspan"></span></td>
                  </tr>

                  <tr>
                    <td style="width:200px;"><label for="plastName" class="photosLabel">Selfi</label></td>
                    <td id=""><img id="selfi" src="" style="width:100%;margin:5px 0px;">
                    <span id="selfispan"></span></td>
                  </tr>

                </table>

        </div>                     
      <div class="row">
         <div class="col s4" id="rowkyc">
          <p><span class="req">*</span> Approve KYC</p>
          <p>
            <label>
              <input type="radio" name="isKYC" id="isActive1" class="with-gap" value="1" checked>
              <span>Approve</span></label>
            <label>
              <input type="radio" name="isKYC" id="isActive2" class="with-gap" value="0"/>
              <span>Reject</span></label>
          </p>
        </div>
      </div>

      <div class="row">                    
        <input type="hidden" name="loanID" id="loanID" value=""/>
        <button type="submit" name="btnSubmit" id="btnSubmit" class="btn waves-effect waves-light gradient-45deg-green-teal"> <i class="material-icons left">save</i> Save</button>
        <button type="button" name="btnCancel" id="btnCancel" class="btnCancel btn waves-effect waves-light gradient-45deg-purple-deep-orange"> <i class="material-icons left">close</i> Cancel</button>
      </div> 


    </div>
      
  </form>
</div>

</div>
<?php include('footer.php');?>
<script>
$(document).ready(function(){
  

  $(".checkAll").click(function(){
		$(':checkbox.customerCheckbox').prop('checked', this.checked);  
  });

  var table =  $('#tableData').DataTable( {
    dom: 'Bfrtip',
      "bPaginate": false,
      buttons: [
          'excelHtml5'
      ],
      scrollX:        true,
      fixedColumns:   {
          leftColumns: 0
      },
      columnDefs: [ {
          orderable: false,
          className: 'select-checkbox',
          targets:   0
      } ],
      select: {
          style:    'multi',
          selector: 'td:first-child'
      },
      order: [[ 1, 'asc' ]]
  });

  listrow();

  $('#ModalBox').modal({dismissible:false});
  $('#ModalBox2').modal({dismissible:false});
  
  
  $('#tableData').on('click', 'a.btnEdit', function (e){	
      var table = $('#tableData').DataTable();
      var rows = table.row($(this).closest('tr')).data();
      var loanID = rows['loanID'];
      $('#loanID').val(loanID);
      getRow(loanID);
      $('#ModalBox2').modal('open');
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
				isApproved: {
					required: true
				}
			},
			messages: {
				isApproved: {
					required: "Please select status"
				}
			},
			submitHandler: function(form){
				saveRow("#frm");
			}
		});
	}

  $('.btnCancel2').click(function(){
		$('#ModalBox').modal('close');		
	});	
  $('.btnCancel').click(function(){
		$('#ModalBox2').modal('close');		
	});	
 
});

  
function getupdateclick(){
  $('#ModalBox').modal('open');

}

function listrow(){
  $.ajax({url: serviceUrl+'loanoneschooldata',type: "POST",data:{'schoolID':'<?php echo $data; ?>'},success: function (respsone){
		var result = JSON.parse(respsone);
		if (result["Status"] == "true"){		
			$('#tableData').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ],
   			"searching": true,
				"bPaginate": false,
				"destroy" : true,
				"data": result.resultArray,
        /* "fnRowCallback" : function(nRow, aData, iDisplayIndex){
                $("td:first", nRow).html(iDisplayIndex +1);
               return nRow;
            }, */
      	"columns": 
				[
					{"data": "loanID"},
          //{"data": "loanID"},
					{"data": "pfirstName"},
					{"data": "sfirstName"},					
					{"data": "loanAmount"},
					{"data": "loantenure"},
					{"data": "schoolName"},
					{"data": "isApproved"},
					{"data": "isKyc"},
				],
				columnDefs: 
				[
					{targets: 0,visible: true ,sorting:false,className : "uk-text-center",
					render: function ( data, type, row, meta ) {
            if(row.isApproved == 4 || row.isApproved == 5){
              return '<input type="checkbox" name="customerArray" class="customerCheckbox bz'+ row.loanID + '" value="'+ row.loanID + '" style="width:15px;height:15px;text-align:center;">'
            }else{
              return ''
            } 
						
					}},          
          //{targets: [0],visible: true},
					{targets: [1],visible: true,sorting:false,className : "uk-text-center",
					render: function ( data, type, row, meta ) {
						return row.pfirstName+" "+row.plastName
					}},
          {targets: [2],visible: true,sorting:false,className : "uk-text-center",
					render: function ( data, type, row, meta ) {
						return row.sfirstName+" "+row.slastName
					}},
          //{targets: [2],visible: true},
          {targets: [3],visible: true},
          {targets: [4],visible: true},
          {targets: [5],visible: true},
          {targets: [6],visible: true,className : "text-center",
					render: function ( data, type, row, meta ) {
						if(data == 0 ) {
							return 'Application';
						}else if(data == 1){
							return 'Approved';
						}else if(data == 2){
							return 'Reject';
						}else if(data == 4){
							return 'Inprocess';
						}else if(data == 5){
							return 'Sent for Lender';
						}
					}},
					{targets: [7],visible: true ,className : "text-center",sorting:false,
					render: function ( data, type, row, meta ) {
						return '<a href="javascript:void(0);" class=\"btnEdit material-icons\">visibility</a>'
					}},
				]
			});
      
		}else{
			//swal(result["Message"],{icon: "error",closeOnClickOutside: false});
		}
	},
	error: function (jqXHR,textStatus,errorThrown ) {
		console.log(jqXHR);
		console.log(textStatus);
		console.log(errorThrown);
		console.log("Error: " + textStatus);
	}});
}

function getRow(loanID){
	$.post(serviceUrl+'getloandetailsPopup',{"loanID": loanID}, function (data){
		result = JSON.parse(data);
		if (result["Status"] == "true"){
			row = result.resultArray;
      console.log(row);
      $("#studentName").text(row["sfirstName"]+" "+row['slastName']);
      $("#parentName").text(row["pfirstName"]+" "+row['plastName']);
      $('#mobileNo').text(row['mobileNo']);
			$('#loanAmount').text(row['loanAmount']);
      $('#monthlyEmi').text(row['emiAmount']);
      $('#address').text(row['address']+", "+row['location']+", "+row['city']);
      $('#currentPayableFees').text(row['currentPayableFees']);
      $('#roi').text(row['roi']);
      $('#loantenure').text(row['loantenure']);
      $('#employmentType').text(row['employmentType']);
      $('#occupation').text(row['occupation']);
      $('#pannumber').text(row['panId']);
      $('#monthlyIncome').text(row['monthlyIncome']);
      if(row['isKyc'] == 1){
        $('#rowkyc').addClass('addfadeClass');
        $('#btnSubmit').addClass('addfadeClass');
      }
      if(row["pancard"] !='' && row["pancard"] != null){
        var pancardImg = baseUrl+"uploads/pancard/"+row["pancard"];
        $('#pancard').attr('src', pancardImg);
        $('#pancardspan').text('');
      }else{
        $('#pancard').attr('src', '');
        $('#pancardspan').text('No Image uploaded');
      }
      
      if(row["aadharFront"] !='' && row["aadharFront"] != null){
        var aadharFront = baseUrl+"uploads/aadhar/"+row["aadharFront"];
        $('#aadharFront').attr('src', aadharFront);
        $('#aadharFrontspan').text('');
      }else{
        $('#aadharFront').attr('src', '');
        $('#aadharFrontspan').text('No Image uploaded');
      } 
      
      if(row["aadharBack"] !='' && row["aadharBack"] != null){
        var aadharBack = baseUrl+"uploads/aadhar/"+row["aadharBack"];
        $('#aadharBack').attr('src', aadharBack);
        $('#aadharBackspan').text('');
      }else{
        $('#aadharBack').attr('src', '');
        $('#aadharBackspan').text('No Image uploaded');
      }
      
      if(row["selfi"] !='' && row["selfi"] != null){
        var selfi = baseUrl+"uploads/selfi/"+row["selfi"];
        $('#selfi').attr('src', selfi);
        $('#selfispan').text('');
      }else{
        $('#selfi').attr('src', '');
        $('#selfispan').text('No Image uploaded');
      }
      
      
		}else{
		//	swal(result["Message"],{icon: "error",closeOnClickOutside: false});
		}
	});
}

function saveRow(frm){
	$(frm).ajaxSubmit({url:serviceUrl+'updateKycloan',beforeSubmit: function (formData, jqForm, options){
		var queryString = $.param(formData);
	},clearForm: false,resetForm: false,success: function (responseText, statusText, xhr, $form){
		var result = JSON.parse(responseText);
		if (result["Status"] == "true"){	
			swal({text: result["Message"],icon: 'success',closeOnClickOutside: false,buttons: {delete: 'Ok'}}).then(function (willDelete){
				if(willDelete){
            $('.btnCancel').trigger('click');
            location.reload();
				}
			});
		}else{
			swal(result["Message"],{icon: "error",closeOnClickOutside: false});
		}		
	}});
}

function updateApproveselStatus(){
  var approveRejectStatus = $('input[name="isApproved"]:checked').val();
  var customerArray = [];
  $.each($("input[name='customerArray']:checked"), function(){
          customerArray.push($(this).val());
  });
  $.post(serviceUrl+'updateapproveStatus',{"loanIDArray":customerArray,'approveRejectStatus':approveRejectStatus}, function (data){
		result = JSON.parse(data);
    console.log(result);
		if (result["Status"] == "true"){
      swal(result["Message"],{icon: "success",closeOnClickOutside: false});
      //listrow();
      $('.btnCancel2').trigger('click');
      location.reload();
		}else{
			swal(result["Message"],{icon: "error",closeOnClickOutside: false});
		}
	});
}

</script>

<script type="text/javascript" src="<?php echo assets_url();?>js/dataTables.buttons.min.js"></script> 
<script type="text/javascript" src="<?php echo assets_url();?>js/buttons.html5.min.js"></script> 
<script type="text/javascript" src="<?php echo assets_url();?>js/jszip.min.js"></script>

</body>
</html>
