<?php $pageTitle = 'Loan Details';
$schoolID = $this->session->userdata('schoolData')["schoolID"];?>
<!DOCTYPE html>
<html>
<head>
<?php include('include.php');?>

<style>
label{
  font-size:16px;
  color :#010;
}
.partext{
  font-size: 15px;
}
.separator{
  position: relative;
  margin-top: 2rem;
}
  
</style>
</head>
<body>
<?php include('header.php');?>
<div id="main">
  <div class="row">
    <div class="pt-1" id="breadcrumbs-wrapper">
      <div class="container">
        <div class="row">
          <div class="col s10 m6 l6 breadcrumbs-left">
            <h6 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span><?php echo $pageTitle;?></span></h6>
          </div>

        </div>
      </div>
    </div>
  </div>
  <section id="listSection" class="users-list-wrapper section">
    <div class="row">
      <div class="col s12">
        <div class="container">
          <div class="card">
            <div class="card-content">
              <?php //echo "<pre>"; print_r($data);
               ?>
               <div class="row">
                  <div class="col s4">
                      <label for="sfirstName">Student Name</label>
                          <p classname="partext"> <?php echo $data['studentName']; ?> </p>
                  </div>
                  <div class="col s4">
                      <label for="plastName">Parent Name</label>
                      <p classname="partext"> <?php echo $data['parentName']; ?> </p>
                  </div>
                  <div class="col s4">
                      <label for="plastName">Mobile Number</label>
                      <p classname="partext"> <?php echo $data['mobileNo']; ?> </p>
                  </div>
                  <div class="col s4">
                    <div class="separator">
                      <label for="plastName">Loan Amount</label>
                      <p classname="partext"> <?php echo $data['loanAmount']; ?> </p>
                    </div>
                  </div>
                  <div class="col s4">
                    <div class="separator">
                      <label for="plastName">Monthly EMI</label>
                      <p classname="partext"> <?php echo $data['emiAmount']; ?> </p>
                    </div>
                  </div>

                  <div class="col s4">
                    <div class="separator">
                      <label for="plastName">Current Payable Fees</label>
                      <p classname="partext"> <?php echo $data['currentPayableFees']; ?> </p>
                    </div>
                  </div>

                  <div class="col s4">
                    <div class="separator">
                      <label for="plastName">Rate of Interest</label>
                      <p classname="partext"> <?php echo $data['roi']; ?> </p>
                    </div>
                  </div>

                  <div class="col s4">
                    <div class="separator">
                      <label for="plastName">Loan Tenure (In Months)</label>
                      <p classname="partext"> <?php echo $data['loantenure']." Months"; ?> </p>
                    </div>
                  </div>
                  <div class="col s4">
                    <div class="separator">
                      <label for="plastName">Monthly Income</label>
                      <p classname="partext"> <?php echo $data['monthlyIncome']; ?> </p>
                    </div>
                  </div>
                  
                  <div class="col s4">
                    <div class="separator">
                      <label for="plastName">Employment Type</label>
                      <p classname="partext"> <?php echo $data['employmentType']; ?> </p>
                    </div>
                  </div>

                  <div class="col s4">
                    <div class="separator">
                      <label for="plastName">Occupation</label>
                      <p classname="partext"> <?php echo $data['occupation']; ?> </p>
                    </div>
                  </div>
                  <div class="col s4">
                    <div class="separator">
                    <label for="plastName">Pan Number</label>
                      <p classname="partext"> <?php echo $data['panId']; ?> </p>
                    </div>
                  </div>

                  <div class="col s4">
                    <div class="separator">
                      <label for="plastName">Pan Card</label>
                      <a href="<?php echo base_url()."/uploads/pancard/".$data['pancard'];?>" download> <p classname="partext"> <img alt="Pan Card" src="<?php echo base_url()."/uploads/pancard/".$data['pancard'];?>" width="100"></p></a>
                    </div>
                  </div>

                  <div class="col s4">
                    <div class="separator">
                      <label for="plastName">Aadhar Front</label>
                      <a href="<?php echo base_url()."/uploads/aadhar/".$data['aadharFront'];?>" download><p classname="partext"> <img alt="Aadhar Front" src="<?php echo base_url()."/uploads/aadhar/".$data['aadharFront'];?>" width="100"></p></a>
                    </div>
                  </div>

                  <div class="col s4">
                    <div class="separator">
                      <label for="plastName">Aadhar Back</label>
                      <a href="<?php echo base_url()."uploads/aadhar/".$data['aadharBack'];?>" download><p classname="partext"> <img alt="Aadhar Back" src="<?php echo base_url()."uploads/aadhar/".$data['aadharBack'];?>" width="100"></p></a>
                    </div>
                  </div>

                  <div class="col s4">
                    <div class="separator">
                      <label for="plastName">Selfi</label>
                      <a href="<?php echo base_url()."uploads/selfi/".$data['selfi'];?>" download><p classname="partext"> <img alt="Selfi" src="<?php echo base_url()."uploads/selfi/".$data['selfi'];?>" width="100"></p></a>
                    </div>
                  </div>


               </div> 
            </div>  
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php include('footer.php');?>

</body>
</html>