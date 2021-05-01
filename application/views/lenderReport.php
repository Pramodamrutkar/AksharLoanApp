<?php $pageTitle = 'Lender Report';

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
                                    
                                    <th>Sr. No</th>
                                    <th>Borrower Name</th>
                                    <th>PAN Number</th>
                                    <th>Pan Card Image URL</th>
                                    <th>Occupation</th>
                                    <th>Loan Amount</th>
                                    <th>Tenure</th>
                                    <th>Interest Rate</th>
                                    <th>School Name</th>
                                    <th>Address</th>
                                    <th>Address Proof Type</th>
                                    <th>Address Proof Front</th>
                                    <th>Address Proof Back</th>
                                    <th>Photo</th>
                                    <th>Agreement URL</th>
                                    <th>IP Address</th>
                                    <th>Date Time of Submission</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                $i = 1;
                                    foreach($data as $row){
                                 ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $row['pfirstName']." ".$row['plastName']; ?></td>
                                        <td><?php echo $row['panId']; ?></td>
                                        <td><?php if(!empty($row['pancard'])){ echo base_url().'uploads/pancard/'.$row['pancard'];  }else{ echo ""; } ?> </td>
                                        <td><?php echo $row['occupation']; ?></td>
                                        <td><?php echo $row['loanAmount']; ?></td>
                                        <td><?php echo $row['loantenure']; ?></td>
                                        <td><?php echo $row['roi']; ?></td>
                                        <td><?php echo $row['schoolName']; ?></td>
                                        <td><?php echo $row['loanAddress']; ?></td>
                                        <td><?php echo $row['documentType']; ?></td>
                                        <td><?php if(!empty($row['aadharFront'])){  echo base_url().'uploads/aadhar/'.$row['aadharFront'];  }else{ echo ""; } ?> </td>
                                        <td><?php if(!empty($row['aadharBack'])) {   echo base_url().'uploads/aadhar/'.$row['aadharBack'];  }else{ echo ""; } ?> </td>
                                        <td><?php if(!empty($row['selfi'])){  echo base_url().'uploads/selfi/'.$row['selfi']; }else{ echo ""; } ?> </td>
                                        <td><?php echo "-"; ?></td>
                                        <td><?php echo $row['ipAddress']; ?></td>
                                        <td><?php echo $row['loandate']; ?></td>
                                        
                                       
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
        "bPaginate": true,
        "pageLength": 25,
        "scrollX": true,
        buttons: [
            'excelHtml5'
        ]
    } );
});

</script>