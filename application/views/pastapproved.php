<?php $pageTitle = 'Past Approved Files Data';

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
<style>
.donotClass{
    opacity: 0.3;
    pointer-events: none;
}
</style>
<div id="main">
    <div class="row">
        <div class="breadcrumbs-inline pt-2" id="breadcrumbs-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col s8 breadcrumbs-left">
                        <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span><?php echo $pageTitle;?></span></h5>
                    </div>
                    <div class="col s4" align="right"> <a href='<?php echo base_url(); ?>service/approveData' class="btnAdd mb-4 btn waves-effect waves-light gradient-45deg-green-teal"><i class="material-icons left">history</i>Back to Approve Data</a> </div>
                    <!--- <div class="col s2" align=""> <a href='javascript:void(0);' class="btnAdd mb-6 btn waves-effect waves-light gradient-45deg-green-teal" id="uploadFile"><i class="material-icons left">add</i>Upload File</a> </div> -->
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
                        <div class="">
                            <table id="tableData" class="table" style="width:100%;">
                                <thead>
                                <tr>
                                    <th>School Name</th>
                                    <th>FileName</th>
                                    <th style="width:260px;">Date</th>
                                    <th>Approved</th>
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
        <input type="hidden" name="approveSelectedfile" id="approveSelectedfile" value="">
        <input type="hidden" name="checkvalueRadio" id="checkvalueRadio" value="">
   </section>
</div>

<div id="ModalBoxUpload" class="modal modal-fixed-footer" style="height: 40%;">
  <form name="frm" id="frm" method="post" enctype="multipart/form-data" class="uk-form-stacked">
    <div class="modal-content">
                <div class="row">
                            <form action="<?php echo base_url(); ?>school/service/uploadData" name="formUpload" id="formUpload" method="post" enctype="multipart/form-data" class="uk-form-stacked">
                                <label for="fileNameUpload">Upload File <small style="color:#F00;">(Excel file)</small></label>
                                <input type="file" id="fileNameUpload" name="fileNameUpload" required/>
                                <button type="submit" name="submit" id="btnSubmit" class="btn waves-effect waves-light gradient-45deg-green-teal"> <i class="material-icons left">save</i>Upload File</button>
                            </form>
                            <p style="padding:20px 20px; color:red;">
                                <?php if(isset($data['message'])){ print_r($data['message']); } ?>
                            </p>
                        </div>
    </div>
    <div class="modal-footer" style="text-align:center;">
        <button type="button" name="btnCancel" id="btnCancel" class="btnCancel btn waves-effect waves-light gradient-45deg-purple-deep-orange"> <i class="material-icons left">close</i> Cancel</button>
    </div>
  </form>
</div>

    <div id="ModalBoxApproved" class="modal modal-fixed-footer" style="height: 50%;">
        <div class="modal-content">
                <button type="button" name="btnCancel2" id="btnCancel2" class="btnCancel2 btn waves-effect waves-light gradient-45deg-purple-deep-orange" style="float:right;"> <i class="material-icons left">close</i> Close</button>
                <div class="row">
                            <div class="col s12">
                            <p>Approved Selected Record ?</p>
                            <p>
                                <label>
                                <input type="radio" name="isApproved" id="isApproved1" class="with-gap" value="1" >
                                <span>Yes</span></label>
                            </p>
                            <label id="reasonblock" style="display: none;"> Enter Reason
                                <textarea id="approvedadminReason" name="approvedadminReason"> 
                                </textarea>
                            </label>
                            <p>
                                <label>
                                <input type="radio" name="isApproved" id="isApproved2" class="with-gap" value="0"/ checked>
                                <span>No</span></label>
                            </p>
                            
                            <div id="btnSubmit2" onclick="submitApproval()" class="btn waves-effect waves-light gradient-45deg-green-teal"> <i class="material-icons left">save</i>Submit</div>
                    </div>

        </div>
    </div>
    
</div>
<?php include('footer.php');?>
</body>
</html>

<script>
$(document).ready(function(){
    
    listUploadRow();
    $('#ModalBoxUpload').modal({dismissible:false});
    $('#ModalBoxApproved').modal({dismissible:false});
    
    $('#uploadFile').click(function(){
        $('#ModalBoxUpload').modal('open');
    })
    $('.btnCancel').click(function(){
		$('#ModalBoxUpload').modal('close');		
	});	
    $('.btnCancel2').click(function(){
		$('#ModalBoxApproved').modal('close');		
        $('#approveSelectedfile').val('');
        $('#checkvalueRadio').val(0);
	});	
    //resetForm();
    $('#tableData').on('click', 'a.btnEdit', function (e){	
		var table = $('#tableData').DataTable();
		var rows = table.row($(this).closest('tr')).data();
		var fileName = rows["fileName"];
        checkSchoolApproval(fileName)
        $('#reasonblock').hide();
        $('#approveSelectedfile').val(fileName);
        $('#isApproved2').prop('checked', true);
		if(fileName != ''){
                $('#ModalBoxApproved').modal('open');
		}	
	});	
 
     $('#isApproved1').click(function(){
        if($('#checkvalueRadio').val() == 0){
            $('#reasonblock').show();
        }
        $('#approvedadminReason').val('');
    })
    $('#isApproved2').click(function(){
        $('#reasonblock').hide();
        $('#approvedadminReason').val('');
    }) 
    
    
});
function submitApproval(){
    if($('#isApproved1').prop('checked') == true){
        var fileName = $('#approveSelectedfile').val();
        var reason = $('#approvedadminReason').val();
        var reason1 = reason.trim();
        if($('#checkvalueRadio').val() == 0 && reason1.length == 0){
                alert('Please Enter a Reason'); 
        }else{
            approveStudent(fileName,1); 
            listUploadRow();
        }
        $('.btnCancel2').trigger('click');
    }
  
}

function listUploadRow(){	
	$.ajax({url: serviceUrl+'pastapproveddata',type: "POST",data:{},success: function (respsone){
		var result = JSON.parse(respsone);
		if (result["Status"] == "true"){		
			$('#tableData').DataTable({
   			    "searching": true,
				"bPaginate": true,
				"destroy" : true,
				"data": result.resultArray,
				"columns": 
				[   
                    {"data": "schoolName"},					
					{"data": "fileName"},
                    {"data": "created"},
                    {"data": "isApprovedbySchool"},
					{"data": "Action"}
				],
				columnDefs: 
				[
                    
					{targets:[0],visible: true},
                    {targets:[2],visible: true},
					{targets:[1],visible: true ,sorting:false,
					render: function ( data, type, row, meta ) {
                    	return '<a href="'+baseUrl+'uploads/approved/'+data+'" target="_blank">'+data+'</a>'
					}},
					{targets:[3],visible: false,className : "text-center",
					render: function ( data, type, row, meta ) {
						if(data == 1 ) {
							return 'Yes';
						}else {
							return 'No';
						}
					}},
					{targets:[4],visible: false ,className : "text-center",sorting:false,
					render: function ( data, type, row, meta ) {
                        if(row['isApprovedbySchool'] == 1 && row['isApprovedbyAdmin'] == 1 || row['isApprovedbyAdmin'] == 1){
                            return '<a href="javascript:void(0);" class=\"btnEdit donotClass material-icons\">edit</a>'
                        }else{
                            return '<a href="javascript:void(0);" class=\"btnEdit material-icons\">edit</a>'
                        }
						
					}},
				]
			});
		}else{
			swal(result["Message"],{icon: "error",closeOnClickOutside: false});
		}
	},
	error: function (jqXHR,textStatus,errorThrown ) {
		console.log("Error: " + textStatus);
	}});
}

function checkSchoolApproval(fileName){
    $.post(serviceUrl+'checkApprovalSchool',{"fileName": fileName}, function (data){
		var result = JSON.parse(data);
		if(result.response.isApprovedbySchool == "1"){
            $('#checkvalueRadio').val(result.response.isApprovedbySchool);
        }else{
            $('#checkvalueRadio').val(result.response.isApprovedbySchool);
        }
	});
}

function approveStudent(fileName,isAdmin){
    var reason = $('#approvedadminReason').val();
    var reason1 = reason.trim();
    console.log(reason1);
    $.post(serviceUrl+'importStudent',{"fileName": fileName,"isAdmin" : isAdmin,"approvedReasonbyAdmin":reason1}, function (data){
		result = JSON.parse(data);
		if (result["status"] == "true"){
			swal(result["message"],{icon: "success",closeOnClickOutside: true});
		}else{
			swal(result["Message"],{icon: "error",closeOnClickOutside: false});
		}
	});
}
</script>