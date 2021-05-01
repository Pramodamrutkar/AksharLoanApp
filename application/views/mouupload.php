<?php $pageTitle = 'Upload MOU';
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
                    
                    <div class="col s4" align="right"> <a href="<?php echo base_url('service/school');?>" class="mb-6 btn waves-effect waves-light gradient-45deg-green-teal"><i class="material-icons left">list</i> <span class="hide-on-small-onl">Back</span></a> <a href='javascript:void(0);' class="btnAdd mb-6 btn waves-effect waves-light gradient-45deg-green-teal" id="uploadFile"><i class="material-icons left">add</i>MOU File</a> </div>
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
                                    <th>Date</th>
                                    <th>MOU</th>
                                    <th>School Name</th>
                                    <th>Mobile No</th>
                                </tr>
                                </thead>
                            </table>
                        </div>

                    </div>
                   </div>
                </div>
            </div>
        </div>
   </section>
</div>

<div id="ModalBoxUpload" class="modal modal-fixed-footer" style="height: 40%;">
  <form name="frm" id="frm" method="post" enctype="multipart/form-data" class="uk-form-stacked">
    <div class="modal-content">
                <div class="row">
                            <form action="<?php echo base_url(); ?>service/mouupload" name="formUpload" id="formUpload" method="post" enctype="multipart/form-data" class="uk-form-stacked">
                                <label for="moufileUpload">Upload File <small style="color:#F00;">(PDF file)</small></label>
                                <input type="file" id="moufileUpload" name="moufileUpload" required/>
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
<input type="hidden" name="schoolID" id="schoolID" value="<?php echo $schoolID;?>">
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
	});	
	
 
    $('#isApproved2').click(function(){
        $('#reasonblock').show();

    })
    $('#isApproved1').click(function(){
        $('#reasonblock').hide();
        $('#approvedReason').val('');
    })
    
    
});

function listUploadRow(){	
    var schoolID = $('#schoolID').val();
	$.ajax({url: serviceUrl+'listmoufiles',type: "POST",data:{'schoolID':schoolID},success: function (respsone){
		var result = JSON.parse(respsone);
		if (result["Status"] == "true"){		
			$('#tableData').DataTable({
   			    "searching": true,
				"bPaginate": false,
				"destroy" : true,
				"data": result.resultArray,
				"columns": 
				[   
                    {"data": "moudate"},
                    {"data": "moufile"},	
                    {"data": "schoolName"},
                    {"data": "mobileNo"}
				],
				columnDefs: 
				[
					{targets:[0],visible: true},
                    {targets:[2],visible: true},
					{targets:[1],visible: true ,sorting:false,
					render: function ( data, type, row, meta ) {
                    	return '<a href="'+baseUrl+'uploads/mou/'+data+'" target="_blank">'+data+'</a>'
					}},
                    {targets:[3],visible: true},
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

</script>