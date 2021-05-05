<?php $pageTitle = 'EMI Report';
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
                        <th>EMI</th>
                        <th>Penal Interest</th>
                        <th>Due Date</th>
                        <th>Payment Date</th>            
                        <th>Transaction ID</th>
                    </tr>
                    </thead>
                    <tbody>
 
                    </tbody>
                    <tfoot>
                        <tr>
                        <td><button onclick="updateApproveselStatus()" class="btn waves-effect waves-light gradient-45deg-green-teal">Submit</button></td>
                        <td></td><td></td><td></td><td></td><td></td><td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</div>
<?php include('footer.php');?>
</body>
</html>
<script>
$(document).ready(function(){
  $(".checkAll").click(function(){
		$(':checkbox.customerCheckbox').prop('checked', this.checked);  
  });

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


 

function listrow(){
  $.ajax({url: serviceUrl+'emiReportData',type: "POST",data:{},success: function (respsone){
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
					{"data": "emiID"},
					{"data": "pfirstName"},
					{"data": "emiAmount"},
                    {"data": "perdaycharges"},
                    {"data": "emiDuedate"},
                    {"data": "emiPaiddate"},
					{"data": "paymentDetails"},
				],
				columnDefs: 
				[
					{targets: 0,visible: true ,sorting:false,className : "uk-text-center",
					render: function ( data, type, row, meta ) {
                    if(row.ispaid == 0){
                     return '<input type="checkbox" name="customerArray" class="customerCheckbox bz'+ row.emiID + '" value="'+ row.emiID + '" style="width:15px;height:15px;text-align:center;">'
                    }else{
                        return ''
                    }
						
					}},          
					{targets: [1],visible: true,sorting:false,className : "uk-text-center",
					render: function ( data, type, row, meta ) {
						return row.pfirstName+" "+row.plastName
					}},
                    {targets: [2],visible: true},
                    {targets: [3],visible: true,sorting:false,className : "uk-text-center",
				    	render: function ( data, type, row, meta ) {
						return row.noofdays * row.perdaycharges
					}},
                    {targets: [4],visible: true},
                    {targets: [5],visible: true},
                    {targets: [6],visible: true},
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
  var customerArray = [];
  $.each($("input[name='customerArray']:checked"), function(){
          customerArray.push($(this).val());
  });
  $.post(serviceUrl+'updateemiPaidStatus',{"emiIDArray":customerArray}, function (data){
		result = JSON.parse(data);
    console.log(result);
		if (result["Status"] == "true"){
            swal(result["Message"],{icon: "success",closeOnClickOutside: false});
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