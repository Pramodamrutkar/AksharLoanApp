<?php $pageTitle = 'New Application Report';
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
                        <th>Lender Name</th>
                        <th>No of Application</th>
                        <th>Inprocess Application</th>
                        <th>View</th>
                        <th>Download</th>
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
                              <td><?php echo $row['lenderName']; ?></td>
                              <td><?php echo (((isset($row['count(ld.loanID)']) && $row['count(ld.loanID)'] !='')) ? $row['count(ld.loanID)'] : 0);?></td>
                              <td><?php echo (((isset($row['lenderCount']) && $row['lenderCount'] !='')) ? $row['lenderCount'] : 0);?></td>
                              <td><?php if($row['schoolID'] != ''){?><a href="<?php echo base_url()."service/loanoneschool/".$row['schoolID']; ?>">View</a> <?php }else{ echo "View"; } ?></td>
                              <td><a href="<?php echo base_url()."service/lenderReport"?>" target="_blank">Download</a></td>
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