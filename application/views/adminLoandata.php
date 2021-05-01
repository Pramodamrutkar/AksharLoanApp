<?php $pageTitle = 'Loan Data';
$schoolID = $this->session->userdata('schoolData')["schoolID"];?>
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
                        <th>Student Name</th>
                        <th>Parent Name</th>
                        <th>Class</th>
                        <th>Gender</th>  
                        <th width="160px;">Date</th>
                        <th style="width:60px;">Approved</th>
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
  <input type="hidden" name="loanID" id="loanID" value="">
<div id="ModalBox" class="modal modal-fixed-footer" >
        <div class="modal-content">
                <button type="button" name="btnCancel2" id="btnCancel2" class="btnCancel2 btn waves-effect waves-light gradient-45deg-purple-deep-orange" style="float:right;"> <i class="material-icons left">close</i> Close</button>
                <div class="row">
                            <div class="col s12">
                            <p>Approved Selected Record ?</p>
                            <p>
                                <label>
                                <input type="radio" name="isApproved" id="isApproved1" class="with-gap" value="1" checked>
                                <span>Yes</span></label>
                            </p>
                            <p>
                                <label>
                                <input type="radio" name="isApproved" id="isApproved2" class="with-gap" value="0">
                                <span>No</span></label>
                            </p>
                            
                            <label id="reasonblock" style="display: none;"> Enter Reason
                                <textarea id="approvedadminReason" name="approvedadminReason"  class="materialize-textarea"></textarea>
                            </label>
                            <div id="btnSubmit2" onclick="submitApproval()" class="btn waves-effect waves-light gradient-45deg-green-teal"> <i class="material-icons left">save</i>Submit</div>
                    </div>

        </div>
    </div>

</div>

</div>
<?php include('footer.php');?>
<script type ="text/javascript">
$(document).ready(function(){
	$('#ModalBox').modal({dismissible:false});
	listRow();
});

$('#isApproved2').click(function(){
    $('#reasonblock').show();
})

$('#tableData').on('click', 'a.btnEdit', function (e){	
    var table = $('#tableData').DataTable();
    var rows = table.row($(this).closest('tr')).data();
    $('#loanID').val(rows.loanID);
    $('#ModalBox').modal('open');
});	

$('#btnCancel2').click(function(){
    $('#ModalBox').modal('close');		
});	

$('#isApproved1').click(function(){
    $('#reasonblock').hide();
    $('#approvedadminReason').val('');
})

function listRow(){
	$('#ModalBox').modal('close');		
	$.ajax({url: serviceUrl+'listloanadmindata',type: "POST",data:{'schoolID':'<?php echo $schoolID;?>'},success: function (respsone){
		var result = JSON.parse(respsone);
    	if (result["Status"] == "true"){		
			$('#tableData').DataTable({
   			    "searching": true,
				"bPaginate": true,
				"destroy" : true,
				"data": result.resultArray,
				"columns": 
				[
					{"data": "studentName"},
					{"data": "parentName"},
                    {"data": "standard"},
                    {"data": "gender"},
					{"data": "loandate"},
					{"data": "approved"},
				],
				columnDefs: 
				[
					{targets:[0],visible: true},
					{targets:[1],visible: true},
                    {targets:[2],visible: true,className : "text-center",sorting:false,
					render: function ( data, type, row, meta ) {
						return row.standard+row.section;
					}},					
                    {targets:[3],visible: true},					
					{targets:[4],visible: true},
                    {targets:[5],visible: true ,className : "text-center",sorting:false,
					render: function ( data, type, row, meta ) {
						return '<a href="javascript:void(0);" class=\"btnEdit material-icons\">edit</a>';
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

function submitApproval(){
    var yesapproved = 0;
    var reason = $('#approvedadminReason').val().trim();
    var reason1 = reason.trim();
    if($('#isApproved2').prop('checked') == true){
        if(reason.length == 0) {
            alert('Please Enter a Reason'); 
        }else{
            yesapproved = 0;
            approveStatus(yesapproved,reason1);
        }
    }else{
        yesapproved = 1;
        approveStatus(yesapproved,reason1);
    }
    $('#btnCancel2').trigger('click');

}

 function approveStatus(yesapproved,reason1){
    var loanID = $('#loanID').val();
    $.post(serviceUrl+'updateloanapprovedStatus',{"isApproved":yesapproved, "loanID":loanID,"approvedReasonbyAdmin":reason1}, function (data){
        result = JSON.parse(data);
        console.log(result);
        if (result["Status"] == "true"){
            swal(result["Message"],{icon: "success",closeOnClickOutside: true});
        }else{
            swal(result["Message"],{icon: "error",closeOnClickOutside: false});
        }
    });   
 }



</script>
</body>
</html>