<?php
    $bloodSamplesNav = 'active';
    require_once('components/headwrapper.php');
    require_once('../controllers/SampleDetailsController.php');
    require_once('components/datatable.php');

    $sample = new SampleDetailsController();

    $data = $sample->index();
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
        <?php echo datatable($data,$auth->user()['userType'] == 'receiver' ? 'table-responsive-sm' : null)?>
        </div>
    </div>
</div>

<script>
    function requestBlood(e){
        $(e.target).prop('disabled',true);
        $(e.target).text('Processing...');
        $.ajax({
            url: '<?php echo (new Route('requestBlood'))->get()?>',
            type:'POST',
            data: {
                sampleId: $(e.target).attr('sample-id')
            },
            success: (res) => {
                try{
                    res = JSON.parse(res);
                    if(res.status == 200 && res.data){
                        $(e.target).text('Requested');
                        $(e.target).removeClass('btn-primary');
                        $(e.target).addClass('btn-secondary');
                    } else {
                        $(e.target).text('Request');
                        $(e.target).prop('disabled',false);
                    }
                } catch {
                    $(e.target).text('Request');
                    $(e.target).prop('disabled',false);
                }
            },
        })
    }
</script>

<?php
    require_once('components/footwrapper.php');
?>