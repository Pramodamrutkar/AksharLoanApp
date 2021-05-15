<?php $pageTitle = 'Fee Adjustment Report';//margin report.

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
                                    <th>Name</th>
                                    <th>Class</th>
                                    <th>Loan Amount</th>
                                    <th>Loan Date</th>
                                    <th>Overdue EMIs</th>
                                    <th>Balance Principle</th>
                                    <th>Penal Interest</th>
                                    <th width="150px;">Amount Adjusted</th>
                                    <th>Parent Name</th>
                                    <th>Mobile</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    foreach($data as $row){
                                 ?>
                                    <tr>
                                        <td><?php echo $row['sfirstName']." ".$row['slastName']; ?></td>
                                        <td><?php echo $row['standard']." ".$row['section']; ?></td>
                                        <td><?php echo $row['loanAmount']; ?></td>
                                        <td><?php echo date('d-m-Y',strtotime($row['loanDate'])); ?></td>
                                        <td><?php echo $row['emiAmount']; ?></td>
                                        <td><?php echo $row['amountNotoverdue']; ?></td>
                                        <td><?php echo round($row['noofdays'] * $row['perdaycharges']); ?></td>
                                        <td><?php $penalty = $row['noofdays'] * $row['perdaycharges'];
                                        echo round($penalty+ $row['emiAmount'] + $row['amountNotoverdue']); ?></td>
                                        <td><?php echo $row['parentName']; ?></td>
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
        "bPaginate":true,
        "pageLength": 25,
        buttons: [
            'excelHtml5'
        ]
    } );
});

</script>