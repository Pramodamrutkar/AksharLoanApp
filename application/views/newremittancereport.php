<?php $pageTitle = 'New Remittance Report';
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
                        <th>Parent Name</th>
                        <th>Student Name</th>
                        <th>School Name</th>
                        <th>Amount</th>
                        <th>Transaction ID</th>
                        <th>Remit</th>
                    </tr>
                    </thead>
                    <tbody>
 
                    </tbody>
                    <tfoot>
                        <tr>
                        <th><button onclick="getupdateclick()">Update</button></th>
                        <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div id="ModalBox" class="modal modal-fixed-footer" style="height: 60%;">
        <div class="modal-content">
              <button type="button" name="btnCancel2" id="btnCancel2" class="btnCancel2 btn waves-effect waves-light gradient-45deg-purple-deep-orange" style="float:right;"> <i class="material-icons left">close</i> Close</button>
                <div class="row">
                  <div class="col s12">
                    <p>Remit Selected Record ?</p>
                    <p>
                        <label>
                        <input type="radio" name="isApproved" id="isApproved1" class="with-gap" value="1" >
                        <span>Yes</span></label>
                    </p>
                  <!--<p>
                        <label>
                        <input type="radio" name="isApproved" id="isApproved2" class="with-gap" value="0"/ checked>
                        <span>No</span></label>
                    </p> -->
                    
                    <div id="btnSubmit2" onclick="updateApproveselStatus()" class="btn waves-effect waves-light gradient-45deg-green-teal"> <i class="material-icons left">save</i>Submit</div>
                </div>

              </div>
        </div>
  </div> <!----end of first modal-->




</div>
<?php include('footer.php');?>
</body>
</html>
<script>
$(document).ready(function(){
  $(".checkAll").click(function(){
		$(':checkbox.customerCheckbox').prop('checked', this.checked);  
  });

  $('#ModalBox').modal({dismissible:false});
  
    $('#tableData').DataTable( {
        dom: 'Bfrtip',
        "bPaginate": false,
        buttons: [
            'excelHtml5'
        ],
        scrollY:        300,
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        fixedColumns:   {
            leftColumns: 2
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
    } );
    listrow();
});


$('.btnCancel2').click(function(){
	  $('#ModalBox').modal('close');		
});	
  
function getupdateclick(){
  $('#ModalBox').modal('open');
}

function listrow(){
  $.ajax({url: serviceUrl+'newremittancedata',type: "POST",data:{},success: function (respsone){
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
        },*/
      	"columns": 
				[
					{"data": "loanID"},
					{"data": "pfirstName"},				
					{"data": "sfirstName"},
          {"data": "schoolName"},
					{"data": "loanAmount"},
          {"data": "transactionDetails"},
					{"data": "remitLoan"},
				],
				columnDefs: 
				[
					{targets: 0,visible: true ,sorting:false,className : "uk-text-center",
					render: function ( data, type, row, meta ) {
            if(row.remitLoan == 0){
              return '<input type="checkbox" name="customerArray" class="customerCheckbox bz'+ row.loanID + '" value="'+ row.loanID + '" style="width:15px;height:15px;text-align:center;">'
            }else{
              return ''
            }
						
					}},          
					{targets: [1],visible: true,sorting:false,className : "uk-text-center",
					render: function ( data, type, row, meta ) {
						return row.pfirstName+" "+row.plastName
					}},
          {targets: [2],visible: true,sorting:false,className : "uk-text-center",
					render: function ( data, type, row, meta ) {
						return row.sfirstName+" "+row.slastName
					}},
          {targets: [3],visible: true},
          {targets: [4],visible: true},
          {targets: [5],visible: true},
          {targets: [6],visible: true,className : "text-center",
					render: function ( data, type, row, meta ) {
						if(row.remitLoan == 0 ) {
							return 'No';
						}else if(row.remitLoan == 1){
							return 'Yes';
						}else if(row.remitLoan == 2){
							return 'Complete';
						}
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

function updateApproveselStatus(){
  var approveRejectStatus = $('input[name="isApproved"]:checked').val();
  var customerArray = [];
  $.each($("input[name='customerArray']:checked"), function(){
          customerArray.push($(this).val());
  });
  $.post(serviceUrl+'updatenewremittanceStatus',{"loanIDArray":customerArray,'approveRejectStatus':approveRejectStatus}, function (data){
		result = JSON.parse(data);
    console.log(result);
		if (result["Status"] == "true"){
      swal(result["Message"],{icon: "success",closeOnClickOutside: false});
      $('.btnCancel2').trigger('click');
      listrow();
		}else{
			swal(result["Message"],{icon: "error",closeOnClickOutside: false});
		}
	});
}


</script>

<script type="text/javascript" src="<?php echo assets_url();?>js/dataTables.buttons.min.js"></script> 
<script type="text/javascript" src="<?php echo assets_url();?>js/buttons.html5.min.js"></script> 
<script type="text/javascript" src="<?php echo assets_url();?>js/jszip.min.js"></script>