<?php $pageTitle = 'Upload Student Data';

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
.addpadding{
    padding:30px 0px;
}
</style>
<?php 
    function formMonth(){
        $month = strtotime(date('Y').'-'.date('m').'-'.date('j').' - 12 months');
        $end = strtotime(date('Y').'-'.date('m').'-'.date('j').' + 0 months');
        $val=1;
        while($month < $end){
            $selected = (date('F', $month) == date('F')) ? ' selected' : 'disabled';
            echo '<option'.$selected.' value='.$val.'>'.date('F', $month).'</option>'."\n";
            $month = strtotime("+1 month", $month);
                $val++;
        }
    }

    function formYear(){
        $currently_selected = date('Y'); 
        $earliest_year = 2021; 
        $latest_year = 2030; 
        foreach ( range( $earliest_year , $latest_year ) as $i ) {
            $selected = (date('Y') == $i) ? ' selected' :'disabled';
          //echo '<option value="'.$i.'"'.($i === $selected ? ' selected="selected"' : '').'>'.$i.'</option>';
          echo '<option'.$selected.' value="'.$i.'">'.$i.'</option>';
        }
    }

?>
<div id="main">
    <div class="row">
        <div class="breadcrumbs-inline pt-2" id="breadcrumbs-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col s6 breadcrumbs-left">
                        <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span><?php echo $pageTitle;?></span></h5>
                    </div>
                    <div class="col s4" align="right"> <a href='<?php echo base_url(); ?>uploads/SampleFile.xlsx' class="btnAdd mb-6 btn waves-effect waves-light gradient-45deg-green-teal" target="_blank"><i class="material-icons left">download</i>Download Sample File</a> </div>
                    <div class="col s2" align=""> <a href='javascript:void(0);' class="btnAdd mb-6 btn waves-effect waves-light gradient-45deg-green-teal" id="uploadFile"><i class="material-icons left">add</i>Upload File</a> </div>
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
                        <div id="errorDiv"><?php if(isset($data) && !empty($data) && is_array($data)){ 
                            $i = 1;
                          foreach ($data as $key => $value) {
                              echo "<p style='color:red'>$i. ".$key." ".$value[0]."</p>";
                              $i++;
                          }
                        }?></div>
                        <div class="">
                            <table id="tableData" class="table" style="width:100%;">
                                <thead>
                                <tr>
                                    <th>Date Time</th>
                                    <th>FileName</th>
                                    <th>SchoolName</th>
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
   </section>
</div>

<div id="ModalBoxUpload" class="modal modal-fixed-footer" >
  <form name="frm" id="frm" method="post" enctype="multipart/form-data" class="uk-form-stacked">
    <div class="modal-content">
                <div class="row addpadding">
                    <div class="col s6">
                        <div class="input-field">
                            <select name="month" id="month">
                                <option value="">Select Month</option>
                                <?php 
                                    formMonth();
                                ?>
                            </select>
                            <label for="month">Month</label>
                        </div>
                    </div>
                    <div class="col s6">
                        <div class="input-field">
                            <select name="year" id="year">
                                <option value="">Select Year</option>
                                <?php 
                                    formYear();
                                ?>
                            </select>
                            <label for="month">Month</label>
                        </div>    
                    </div>

                </div>
                <div class="row addpadding" style="padding:0px 10px;">
                            <form action="<?php echo base_url(); ?>school/service/uploadData" name="formUpload" id="formUpload" method="post" enctype="multipart/form-data" class="uk-form-stacked">
                                <label for="fileNameUpload">Upload File <small style="color:#F00;">(Excel file)</small></label>
                                <input type="file" id="fileNameUpload" name="fileNameUpload" required/>
                                <button type="submit" name="submit" id="btnSubmit" class="btn waves-effect waves-light gradient-45deg-green-teal"> <i class="material-icons left">save</i>Upload File</button>
                                <b><span id="erroMsg" style="color:red;font-size: 15px;"></span></b>
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
                                <input type="radio" name="isApproved" id="isApproved1" class="with-gap" value="1" checked>
                                <span>Yes</span></label>
                            </p>
                            <p>
                                <label>
                                <input type="radio" name="isApproved" id="isApproved2" class="with-gap" value="0"/>
                                <span>No</span></label>
                            </p>
                            <label id="reasonblock" style="display: none;"> Enter Reason
                            <textarea id="approvedReason" name="approvedReason"> 
                            </textarea>
                            </label>
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
        $.post(serviceUrl+'checkFileuploadmonthly',{'schoolID':'<?php echo $schoolID;?>'}, function (data){
		result = JSON.parse(data);
		if (result["status"] == "true"){
            $('#btnSubmit').hide();
            $('#erroMsg').text('You already have uploaded file for this month');
		}else{
            $('#erroMsg').text('');
		}
	});
    })
    $('.btnCancel').click(function(){
		$('#ModalBoxUpload').modal('close');		
	});	
    $('.btnCancel2').click(function(){
		$('#ModalBoxApproved').modal('close');		
        $('#approveSelectedfile').val('');
	});	
    //resetForm();
    $('#tableData').on('click', 'a.btnEdit', function (e){	
		var table = $('#tableData').DataTable();
		var rows = table.row($(this).closest('tr')).data();
		var fileName = rows["fileName"];
        $('#approveSelectedfile').val(fileName);
		if(fileName != ''){
            $('#ModalBoxApproved').modal('open');
			/* swal({text: "Are you sure, you want to Approve the selected record?",icon: 'warning',closeOnClickOutside: false,buttons: {delete: 'Yes',cancel: 'No'}}).then(function (willApprove){
				if(willApprove){
				    approveStudent(fileName);
                    listUploadRow();
				}
			}); */
		}	
	});	
 
    $('#isApproved2').click(function(){
        $('#reasonblock').show();

    })
    $('#isApproved1').click(function(){
        $('#reasonblock').hide();
        $('#approvedReason').val('');
    })
    
    
});

function submitApproval(){
    if($('#isApproved1').prop('checked') == true){
        var fileName = $('#approveSelectedfile').val();
        approveStudent(fileName); 
        listUploadRow();
        $('.btnCancel2').trigger('click');
    }else{
        var fileName = $('#approveSelectedfile').val();
        var reason = $('#approvedReason').val();
        var reason1 = reason.trim();
        if(reason1.length > 0){
                $.post(serviceUrl+'submitapproval',{"fileName": fileName,'schoolID':'<?php echo $schoolID;?>', "reason":reason1 }, function (data){
                result = JSON.parse(data);
                if (result["status"] == "true"){
                    swal(result["message"],{icon: "success",closeOnClickOutside: true});
                    listUploadRow();
                    $('.btnCancel2').trigger('click');

                }else{
                    swal(result["Message"],{icon: "error",closeOnClickOutside: false});
                }
            }); 
        }else{
            alert('Please Enter a Reason');
        }
    }
}

function listUploadRow(){	
	$.ajax({url: serviceUrl+'listfiles',type: "POST",data:{'schoolID':'<?php echo $schoolID;?>'},success: function (respsone){
		var result = JSON.parse(respsone);
		if (result["Status"] == "true"){		
			$('#tableData').DataTable({
   			    "searching": true,
				"bPaginate": false,
				"destroy" : true,
				"data": result.resultArray,
				"columns": 
				[   
                    {"data": "created"},
                    {"data": "fileName"},	
                    {"data": "schoolName"},					
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
					{targets:[4],visible: true ,className : "text-center",sorting:false,
					render: function ( data, type, row, meta ) {
                        if(row['isApprovedbySchool'] == 1){
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

function approveStudent(fileName){
    $.post(serviceUrl+'importStudent',{"fileName": fileName}, function (data){
		result = JSON.parse(data);
		if (result["status"] == "true"){
			swal(result["message"],{icon: "success",closeOnClickOutside: true});
		}else{
			swal(result["Message"],{icon: "error",closeOnClickOutside: false});
		}
	});
}

</script>