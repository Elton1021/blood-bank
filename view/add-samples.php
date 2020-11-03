<?php
    $addSamplesNav = 'active';
    require_once('components/headwrapper.php');
    require_once('../controllers/SampleDetailsController.php');

    $sample = new SampleDetailsController();
    $sample->validateHospital();

    $data = $sample->index();
    $bloodGroupSwitches = [
        'a_pos' => 'A+',
        'a_neg' => 'A-',
        'b_pos' => 'B+',
        'b_neg' => 'B-',
        'ab_pos' => 'AB+',
        'ab_neg' => 'AB-',
        'o_pos' => 'O+',
        'o_neg' => 'O-',
    ]
?>

<div aria-live="polite" id="toast-container" aria-atomic="true" class="d-flex justify-content-center align-items-center" style="min-height:100px;position:fixed;top:0;left:0;z-index:9999;width:100%;margin-top:50px;">
</div>

<div class="container mt-5 mb-5 contentHeight">
    <div class="card bg-dark text-light shadow">
        <div class="card-header">
            <span class="material-icons text-primary" style="vertical-align:text-top;">
                local_hospital
            </span>
            <span style="font-size:18px">
                Add Blood Samples
            </span>
        </div>
        <div class="card-body">
            <div class="text-center">These are the blood samples that you provide</div>
            <div class="row mt-3">
            <?php
                $itter = 0;
                foreach($bloodGroupSwitches as $bloodGroupId => $bloodGroup){
            ?>
                    <?php
                        if($itter == 0){
                            ?>
                            <div class="col-md-3 col-sm-12">
                                <div class="row">
                            <?php

                        }else if($itter%2 == 0){
                            ?>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="row">
                            <?php
                        }
                        $itter++;                        
                    ?>
                <div class="col-6">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="<?php echo $bloodGroupId?>" id="<?php echo $bloodGroupId?>" class="custom-control-input" value="<?php echo $bloodGroup?>" <?php echo $data[$bloodGroup] ?? '';?>>
                        <label for="<?php echo $bloodGroupId?>" class="custom-control-label"><?php echo $bloodGroup?></label>
                    </div>
                </div>
            <?php
                    if($itter == sizeof($bloodGroupSwitches)){
                        ?>
                            </div>
                        </div>
                        <?php
                    }
                }
            ?>
            </div>
        </div>
        <div class="card-footer text-muted">
            Toggle Blood groups that you want to provide
        </div>
    </div>
</div>

<script>
    $('input').on('change',(e) => {

        const id = 'unique_id' || Date.now();
        const delay = 1500
        $(`<div class="toast fade show" role="alert" id="${id}" aria-live="assertive" aria-atomic="true" data-delay="${delay}">
            <div class="toast-body">
                ${$(e.target).val() + ($(e.target).is(':checked') ? ' sample added' : ' sample removed')}
            </div>
        </div>`).appendTo('#toast-container');

        setTimeout((id) => {
            $('#toast-container').children('#'+id).removeClass('show').addClass('hide');
        }, delay,id);

        $.ajax({
            url: '<?php echo (new Route('storeSample'))->get();?>',
            type: 'POST',
            data: {
                blood_group: $(e.target).val(),
                status: $(e.target).is(':checked') ? 'A' : 'I'
            },
            success: console.log
        })
    })
</script>

<?php
    require_once('components/footwrapper.php');
?>