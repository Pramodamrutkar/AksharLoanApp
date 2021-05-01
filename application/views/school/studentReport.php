<?php $pageTitle = 'Student Report';

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
                                    <th>Sr. NO</th>
                                    <th>Student First Name</th>
                                    <th>Student Last Name</th>
                                    <th>Standard</th>
                                    <th>Section</th>
                                    <th>Parent First Name</th>
                                    <th>Parent Last Name</th>
                                    <th>Relationship</th>
                                    <th>Mobile No</th>
                                    <th>Annual Fee</th>
                                    <th>Current Payable Fees</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                $i= 1;
                                    foreach($data as $row){
                                 ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $row['sfirstName']; ?></td>
                                        <td><?php echo $row['slastName']; ?></td>
                                        <td><?php echo $row['standard']; ?></td>
                                        <td><?php echo $row['section']; ?></td>
                                        <td><?php echo $row['pfirstName']; ?></td>
                                        <td><?php echo $row['plastName']; ?></td>
                                        <td><?php echo $row['relationship']; ?></td>
                                        <td><?php echo $row['mobileNo']; ?></td>
                                        <td><?php echo $row['annualFee']; ?></td>
                                        <td><?php echo $row['currentPayableFees']; ?></td>
                                        
                                    </tr>
                                <?php $i++; } ?>
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