<?php
    $bloodSamplesNav = 'active';
    require_once('components/headwrapper.php');
    require_once('components/table.php');
?>
<div class="container mt-5 mb-5 contentHeight">
    <div class="card bg-dark text-light shadow">
        <div class="card-header">
            <span class="material-icons text-danger" style="vertical-align:text-top;">
                invert_colors
            </span>
            <span style="font-size:18px">
                Blood Samples
            </span>
        </div>
        <div class="card-body">
            <!-- ?php echo datatable($columns,$db_data);? -->
        </div>
    </div>
</div>
<?php
    require_once('components/footwrapper.php');
?>