<?php $pageTitle = 'Staff Loan Report';

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
                    <th>Loan Amount</th>
                    <th>Loan date</th>
                    <th>EMI</th>
                    <th>Overdue Amount</th>
                    <th>Days Overdue</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Vandana Sharma</td>
                    <td>12000</td>
                    <td>05/02/2021</td>
                    <td>2000</td>
                    <td>0</td>
                    <td>3 days</td>
                  </tr>
                  <tr>
                    <td>Vandana Sharma</td>
                    <td>12000</td>
                    <td>05/02/2021</td>
                    <td>2000</td>
                    <td>0</td>
                    <td>3 days</td>
                  </tr>
                  <tr>
                    <td>Vandana Sharma</td>
                    <td>12000</td>
                    <td>05/02/2021</td>
                    <td>2000</td>
                    <td>0</td>
                    <td>3 days</td>
                  </tr>
                  <tr>
                    <td>Vandana Sharma</td>
                    <td>12000</td>
                    <td>05/02/2021</td>
                    <td>2000</td>
                    <td>0</td>
                    <td>3 days</td>
                  </tr>
                  <tr>
                    <td>Vandana Sharma</td>
                    <td>12000</td>
                    <td>05/02/2021</td>
                    <td>2000</td>
                    <td>0</td>
                    <td>3 days</td>
                  </tr>
                  <tr>
                    <td>Vandana Sharma</td>
                    <td>12000</td>
                    <td>05/02/2021</td>
                    <td>2000</td>
                    <td>0</td>
                    <td>3 days</td>
                  </tr>
                  <tr>
                    <td>Vandana Sharma</td>
                    <td>12000</td>
                    <td>05/02/2021</td>
                    <td>2000</td>
                    <td>0</td>
                    <td>3 days</td>
                  </tr>
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