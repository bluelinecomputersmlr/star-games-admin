<?php
include "connection/config.php";

if (!checks("admin"))
{
    redirect("login.php");
}

$mobile = $_REQUEST['mobile'];

$sc = query("select * from withdraw_details where user='$mobile'");
$s = fetch($sc);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Panel</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="vendors/select2/select2.min.css">
    <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="css/horizontal-layout-light/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="images/favicon.png" />
</head>


<body class="sidebar-dark" style="font-family: 'Oxygen', sans-serif;">
  <div class="container-fluid page-body-wrapper">

    <?php include "include/header.php"; ?>

        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">

                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Payment Profile</h4>

                                <form class="forms-sample" method="post" enctype="multipart/form-data">
                                     
                                    <div class="form-group">
                                        <label for="exampleInputName1">Account number</label>
                                        <input type="text" class="form-control" value="<?php echo $s['account']; ?>" id="exampleInputName1" readonly>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="exampleInputName1">IFSC</label>
                                        <input type="text" class="form-control" value="<?php echo $s['ifsc']; ?>"  id="exampleInputName1" readonly>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="exampleInputName1">Name</label>
                                        <input type="text" class="form-control"  value="<?php echo $s['name']; ?>" id="exampleInputName1" readonly>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="exampleInputName1">VPA(UPI ID)</label>
                                        <input type="text" class="form-control"  value="<?php echo $s['upi']; ?>" id="exampleInputName1" readonly>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="exampleInputName1">Address</label>
                                        <input type="text" class="form-control" value="<?php echo $s['address']; ?>"  id="exampleInputName1" readonly>
                                    </div>
                                    
                                    <div class="form-group">
                                       <label for="exampleInputName1">Cashfree ID</label>
                                       <input type="number" class="form-control" value="<?php echo $s['benId']; ?>"  id="exampleInputName1" readonly>
                                    </div>
                                    
                                 
                                    
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- content-wrapper ends -->
            <!-- partial:../../partials/_footer.html -->
            <footer class="footer">
                <div class="w-100 clearfix">
                    <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i></span>
                </div>
            </footer>
            <!-- partial -->
        </div>
        <!-- main-panel ends -->
</div>
<!-- container-scroller -->
<!-- plugins:js -->
<script src="vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="vendors/typeahead.js/typeahead.bundle.min.js"></script>
<script src="vendors/select2/select2.min.js"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="js/off-canvas.js"></script>
<script src="js/hoverable-collapse.js"></script>
<script src="js/template.js"></script>
<script src="js/settings.js"></script>
<script src="js/todolist.js"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="js/file-upload.js"></script>
<script src="js/typeahead.js"></script>
<script src="js/select2.js"></script>
<!-- End custom js for this page-->

<script src="https://cdn.tiny.cloud/1/6n9e3b6bbutqzcha0os8jsggfbmiqxiy166ekcaclp6aw530/tinymce/5/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: 'textarea',  // change this value according to your HTML
        auto_focus: 'element1'
    });
</script>

</body>

</html>
