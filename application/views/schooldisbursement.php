<?php $pageTitle = 'School Disbursement Report';
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
            <table id="tableData" class=" stripe row-border order-column nowrap" style="width:100%;">
                    <thead>
                    <tr>
                        <th>Sr. No</th>
                        <th>School Name</th>
                        <!-- <th>Disbursement Amount</th>
                        <th>Margin %</th> -->
                        <th>Amount to Disbursed</th>
                        <th>Bank Name</th>
                        <th>Account No</th>
                        <th>IFSC code</th>
                        <th>View</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                      $i = 1;
                        foreach($data as $row){
                          ?>
                          <tr>
                              <td><?php echo $i; ?></td>
                              <td><?php echo $row['schoolName']; ?></td>
                            <!--   <td><?php //echo $row['disbursementAmount']; ?></td>
                              <td><?php //echo $row['margin']; ?></td> -->
                              <td><?php 
                              $percentAmount = (($row['disbursementAmount'] * $row['margin'])/100);
                              echo $amountTodisbursed = $row['disbursementAmount'] - $percentAmount;
                              ?></td>
                              <td><?php echo $row['bankName'];?></td>
                              <td><?php echo $row['accountNo'];?></td>
                              <td><?php echo $row['ifscCode'];?></td>
                              <td><a href="<?php echo base_url()."service/disbursementreport/".$row['schoolID']; ?>">View</a></td>
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
          style:    'os',
          selector: 'td:first-child'
      },
      order: [[ 1, 'asc' ]]
  } );
});
</script>