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
                        <th>Section</th>  
                        <th>Gender</th>  
                        <th width="160px;">Date</th>
                        <!-- <th style="width:60px;">Actions</th> -->
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

<?php include('footer.php');?>
<script type ="text/javascript">
$(document).ready(function(){
	$('#ModalBox').modal({dismissible:false});
	
	
	listRow();
	
});
function listRow(){
	$('#ModalBox').modal('close');		
	$.ajax({url: serviceUrl+'listloandata',type: "POST",data:{'schoolID':'<?php echo $schoolID;?>'},success: function (respsone){
		var result = JSON.parse(respsone);
    console.log(result);
		if (result["Status"] == "true"){		
			$('#tableData').DataTable({
   			    "searching": true,
				"bPaginate": false,
				"destroy" : true,
				"data": result.resultArray,
				"columns": 
				[
					{"data": "studentName"},
					{"data": "parentName"},
          {"data": "standard"},
          {"data": "section"},
          {"data": "gender"},
					{"data": "created"},
					{"data": "Action"},
				],
				columnDefs: 
				[
					{targets:[0],visible: true},
					{targets:[1],visible: true},
          {targets:[2],visible: true},					
          {targets:[3],visible: true},					
					{targets:[4],visible: true},
          {targets:[5],visible: true},
					{targets:[6],visible: false ,className : "text-center",sorting:false,
					render: function ( data, type, row, meta ) {
						
						return '<a href="'+serviceUrl+'loandetails/'+row.studentID+'" class=\"btnEdit material-icons\">preview</a>';
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

</script>
</body>
</html>