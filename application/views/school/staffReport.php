<?php $pageTitle = 'Staff Report';

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
                            <table id="tableData" class="table" style="width:100%;">
                                <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Since</th>
                                    <th>Monthly Salary</th>
                                    <th>Mobile</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    foreach($data as $row){
                                 ?>
                                    <tr>
                                        <td><?php echo $row['staffFirstName']; ?></td>
                                        <td><?php echo $row['staffLastName']; ?></td>
                                        <td><?php echo $row['joiningDate']; ?></td>
                                        <td><?php echo $row['monthlySalary']; ?></td>
                                        <td><?php echo $row['mobileNo']; ?></td>
                                    </tr>
                                <?php  } ?>
                                </tbody>
                            </table>
                        
                    </div>
                   </div>
                </div>
            </div>
        </div>
        
   </section>
</div>


<?php include('footer.php');?>
<script type="text/javascript" src="<?php echo assets_url();?>js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?php echo assets_url();?>js/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?php echo assets_url();?>js/jszip.min.js"></script>


</body>
</html>

<script>
$(document).ready(function(){
    
    $('#tableData').DataTable( {
        dom: 'Bfrtip',
        "bPaginate": false,
        buttons: [
            'excelHtml5'
        ]
    } );
});

</script>