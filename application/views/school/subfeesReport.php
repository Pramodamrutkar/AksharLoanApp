<?php $pageTitle = 'Sub Fees Report';

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
                                <tr style="font-size:14px;">
                                    <th width="10">Sr. No</th>    
                                    <th width="20">Date</th>
                                    <th width="20">Amount</th>
                                    <th width="20">Source</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                $i = 1;
                                    foreach($data as $row){
                                 ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo date("jS F",strtotime($row['paymentDate'])); ?></td>
                                        <td><?php echo $row['loanAmount']; ?></td>
                                        <td><?php 
                                            if($row['loanType'] == 0){
                                                echo "Loan"; 
                                            }else if($row['loanType'] == 1){ 
                                                echo "Remmittance"; 
                                            }else if($row['loanType'] == 2){ 
                                                echo "Direct"; 
                                            } ?>
                                        </td>
                                    </tr>
                                <?php 
                                    $i++;
                                } ?>
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