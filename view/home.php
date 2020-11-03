<?php
    require_once('components/headwrapper.php');
    if(isset($auth->user()['id'])){
        (new Route('bloodSamples'))->redirect();
    }
    $cards = [
        [
            'col' => 'col-md-4 col-ls-4 col-xs-12 custom-m-5',
            'title' => 'Receiver',
            'icon' => 'account_circle',
            'iconColor' => '#e8e834',
            'buttons' => [
                [
                    'url' => (new Route('auth',['t' => 'receiver', 'f' => 'login']))->get(),
                    'classes' => 'btn',
                    'text' => 'Login'
                ],[
                    'url' => (new Route('auth',['t' => 'receiver', 'f' => 'register']))->get(),
                    'classes' => 'btn',
                    'text' => 'Register'
                ],
            ]
        ],[
            'col' => 'col-md-4 col-ls-4 col-xs-12 custom-m-5',
            'title' => 'Blood Samples',
            'icon' => 'invert_colors',
            'iconColor' => '#da2323',
            'buttons' => [[
                'url' => (new Route('bloodSamples'))->get(),
                'classes' => 'btn',
                'text' => 'Check Availability'
            ]]
        ],[
            'col' => 'col-md-4 col-ls-4 col-xs-12 custom-m-5',
            'title' => 'Hospitals',
            'icon' => 'local_hospital',
            'iconColor' => '#3c9df1',
            'buttons' => [
                [
                    'url' => (new Route('auth',['t' => 'hospital', 'f' => 'login']))->get(),
                    'classes' => 'btn',
                    'text' => 'Login'
                ],[
                    'url' => (new Route('auth',['t' => 'hospital', 'f' => 'register']))->get(),
                    'classes' => 'btn',
                    'text' => 'Register'
                ],
            ]
        ]
    ];
?>
<link rel="stylesheet" href="../resources/css/home.css">

<div class="container mt-5 mb-5">
    <div class="row home-v-center">
    <?php 
        foreach ($cards as $card) {
            ?>
            <div class="<?php echo $card['col']?>">
                <div class="card bg-dark text-white shadow">
                    <div class="card-body">
                        <div class="text-center">
                            <span class="material-icons" style="font-size:100px!important;<?php echo isset($card['iconColor']) ? 'color:'.$card['iconColor']: '' ?>">
                                <?php echo $card['icon']?>
                            </span>
                            <h5 class="card-title"><?php echo $card['title']?><h5>
                            <?php 
                                foreach($card['buttons'] as $button){
                                    echo "<a href='".$button['url']."' class='".$button['classes']."'>".$button['text']."</a>";
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    ?>
    </div>
</div>
<?php
    require_once('components/footwrapper.php');
?>