<?php $pageTitle = 'Payment Transferred Sub Report';

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
                    <th>Section</th>
                    <th>Date</th>
                    <th>Loan/Remmittance</th>
                    <th width="180">Net transfer after margin</th>
                   </tr>
                </thead>
                <tbody>
                 
                  <?php 
                  foreach($paymentData as $data){ ?>
                  <tr>
                    <td><?php echo $data['sfirstName']." ".$data['slastName']; ?></td>
                    <td><?php echo $data['standard']; ?></td>
                    <td><?php echo $data['section']; ?></td>
                    <td><?php echo date('d-m-Y',strtotime($data['loanDate'])); ?></td>
                    <td><?php echo ($data['loanType'] == 1 ? "Remmittance" : "Loan"); ?></td>
                    <td><?php 
                        if($data['loanType'] == 0){
                            $twentyperAmount = (($data['loanAmount'] * 20) / 100);
                            $Amount = $data['loanAmount'] - $twentyperAmount;
                        }else{
                            $Amount = $data['loanAmount'];
                        } 
                            echo $Amount;
                     ?></td>
                  </tr>
                  <?php  } ?>
                    
                </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" style="text-align:right">Total:</th>
                            <th></th>
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
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 5, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 5 ).footer() ).html(
                pageTotal 
            );
        }
    } );
});

</script>