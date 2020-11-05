<?php
    $viewRequestNav = 'active';
    require_once('components/headwrapper.php');
    require_once('../controllers/SampleDetailsController.php');
    require_once('components/datatable.php');

    $sample = new SampleDetailsController();

    $data = $sample->getRequests();
?>
<div class="container mt-5 mb-5 contentHeight">
    <div class="card bg-dark text-light shadow">
        <div class="card-header">
            <span class="material-icons text-danger" style="vertical-align:text-top;">
                invert_colors
            </span>
            <span style="font-size:18px">
                Blood Sample Requests
            </span>
        </div>
        <div class="card-body">
        <?php echo datatable($data,'table-responsive-sm')?>
        </div>
    </div>
</div>
<?php
    require_once('components/footwrapper.php');
?>