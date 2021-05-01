<?php $pageTitle = $schoolName;?>
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
  font-size: 16px;
}
.separator{
  position: relative;
  margin-top: 2rem;
}
.rowmargin{
    margin:30px 0px;
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
               
                <div class="row rowmargin">
                  <div class="col s4">
                      <label for="sfirstName">School Name</label>
                          <p class="partext"> <?php echo $schoolName; ?> </p>
                  </div>
                  <div class="col s4">
                      <label for="plastName">Email</label>
                      <p class="partext"> <?php echo $eMail; ?> </p>
                  </div>
                  <div class="col s4">
                      <label for="plastName">Mobile Number</label>
                      <p class="partext"> <?php echo $mobileNo; ?> </p>
                  </div>
                </div>

                <div class="row rowmargin">
                  <div class="col s4">
                      <label for="sfirstName">School Type</label>
                          <p class="partext"> <?php echo $typeName; ?> </p>
                  </div>
                  <div class="col s4">
                      <label for="plastName">School Board</label>
                      <p class="partext"> <?php echo $boardName; ?> </p>
                  </div>
                  <div class="col s4">
                      <label for="plastName">Managed By</label>
                      <p class="partext"> <?php echo $managedBy; ?> </p>
                  </div>
                </div>

                <div class="row rowmargin">
                  <div class="col s4">
                      <label for="sfirstName">Address</label>
                          <p class="partext"> <?php echo $address; ?> </p>
                  </div>
                  <div class="col s4">
                      <label for="plastName">City</label>
                      <p class="partext"> <?php echo $cityID; ?> </p>
                  </div>
                  <div class="col s4">
                      <label for="plastName">State</label>
                      <p class="partext"> <?php echo $stateName; ?> </p>
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