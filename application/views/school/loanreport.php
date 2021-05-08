<?php $pageTitle = 'Active Loan Report';

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
                                    <th>EMI</th>
                                    <!-- <th>Overdue Amount</th>
                                    <th>Days overdue</th> -->
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
                                        <td><?php echo $row['section']."".$row['standard']; ?></td>
                                        <td><?php echo $row['loanAmount']; ?></td>
                                        <td><?php echo ($row['loanDate'] == NULL ? "-" : date('Y-m-d',strtotime($row['loanDate']))); ?></td>
                                        <td><?php echo $row['emiAmount']; ?></td>
                                        <td><?php echo $row['pfirstName']." ".$row['plastName']; ?></td>
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
        "bPaginate": true,
        "pageLength": 25,
        buttons: [
            'excelHtml5'
        ]
    } );
});

</script>