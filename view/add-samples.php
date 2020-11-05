<?php
    $addSamplesNav = 'active';
    require_once('components/headwrapper.php');
    require_once('../controllers/SampleDetailsController.php');

    $sample = new SampleDetailsController();

    $data = $sample->getByHospital();
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
        $.ajax({
            url: '<?php echo (new Route('storeSample'))->get();?>',
            type: 'POST',
            data: {
                blood_group: $(e.target).val(),
                status: $(e.target).is(':checked') ? 'A' : 'I'
            },
            success: (res) => {
                try{
                    res = JSON.parse(res);
                    // will give a toast everytime db is updated
                    if(res.status == 200 && res.data){
                        const id = Math.floor(Math.random() * 10) +'_'+ Date.now();
                        const delay = 1500
                        $(`<div class="toast fade show mx-auto" role="alert" id="${id}" aria-live="assertive" aria-atomic="true" data-delay="${delay}" >
                            <div class="toast-body">
                                ${$(e.target).val() + ($(e.target).is(':checked') ? ' sample added' : ' sample removed')}
                            </div>
                        </div>`).appendTo('#toast-container');

                        setTimeout((id) => {
                            $('#toast-container').children('#'+id).removeClass('show').addClass('hide');
                        }, delay,id);
                    }
                } catch {}
            }
        })
    })
</script>

<?php
    require_once('components/footwrapper.php');
?>