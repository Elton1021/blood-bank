<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    require_once('components/headwrapper.php');
    $type = $_GET['t'] ?? '';
    $formType = $_GET['f'] ?? '';
    $formDetails = [
        [
            'title' => 'Hosiptal Name',
            'id' => 'name',
            'col-md' => '6',
            'hint' => 'Special characters and numbers not allowed'
        ],[
            'title' => 'Username',
            'id' => 'username',
            'col-md' => '6',
            'hint' => 'Only alphanumeric characters are allowed'
        ],[
            'title' => 'Password',
            'id' => 'password',
            'col-md' => '6',
            'type' => 'password',
            'hint' => 'Must have at least 8 characters, 1 uppercase letter, 1 lowercase letter, 1 numeric character'
        ],[
            'title' => 'Confirm Password',
            'id' => 'confirm_password',
            'col-md' => '6',
            'type' => 'password',
            'hint' => 'Repeat password'
        ]
    ];

    if($type != 'hospital' && $type != 'receiver' || isset($auth->user()['id'])){
        ?><script>console.log('here')</script><?php
        (new Route('home'))->redirect();
    } else if ( $formType == 'register' && $type == 'receiver') {
        $formDetails[0]['title'] = 'Your Name';
        $formDetails[2]['col-md'] = '4';
        $formDetails[3]['col-md'] = '4';
        $formDetails[4] = [
            'title' => 'Blood Group',
            'id' => 'blood_group',
            'col-md' => '4',
            'type' => 'select',
            'options' => [
                'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'
            ],
            'hint' => 'Select your blood group'
        ];
    } else if($formType == 'login') {
        $formDetails = [
            [
                'title' => 'Username',
                'id' => 'username',
                'col-md' => '6',
                'value' => $_SESSION['username'],
                'hint' => isset($_SESSION['incorrect_username']) && boolval($_SESSION['incorrect_username']) ? 'Invalid username' : null,
                'is-invalid' => isset($_SESSION['incorrect_username']) && boolval($_SESSION['incorrect_username'])
            ],[
                'title' => 'Password',
                'id' => 'password',
                'col-md' => '6',
                'type' => 'password',
                'hint' => isset($_SESSION['incorrect_password']) && boolval($_SESSION['incorrect_password']) ? 'Invalid password' : null,
                'is-invalid' => isset($_SESSION['incorrect_password']) && boolval($_SESSION['incorrect_password'])
            ]
        ];
        session_destroy();
    }

    if($formType == 'register'){
        ?>
            <link rel="stylesheet" href="../resources/css/optional.css">
        <?php
    }
?>

<div class="container mt-5 mb-5">
    <div class="card bg-dark text-white shadow">
        <div class="card-header">
            <span class="material-icons" style="vertical-align:text-top;color:<?php echo ($type == 'receiver' ? '#e8e834' : '#3c9df1')?>">
                <?php if($type == 'receiver') echo'account_circle'; else if($type == 'hospital') echo 'local_hospital';?>
            </span>
            <span style="font-size:18px">
            <?php echo ucfirst($type).' '; if($formType == 'login') echo'Login'; else if($formType == 'register') echo 'Registeration';?>
            </span>
        </div>
        <div class="card-body">
            <form action="<?php echo (new Route($formType))->get();?>" method="post" autocomplete="off">
                <div class="row">
                <?php
                    foreach($formDetails as $formDetail){
                ?>
                    <div class="col-md-<?php echo $formDetail['col-md']?> col-ls-12">
                        <div class="form-group">
                            <label for="<?php echo $formDetail['id']?>"><?php echo $formDetail['title']?>*</label>
                            <?php
                                if(isset($formDetail['type']) && $formDetail['type'] == 'select'){
                                    ?>
                                        <select id="<?php echo $formDetail['id']?>" class="form-control" name="<?php echo $formDetail['id']?>" required>
                                        <?php
                                            foreach($formDetail['options'] as $option){
                                                ?>
                                                <option value="<?php echo $option?>"><?php echo $option?></option>
                                                <?php
                                            }
                                        ?>
                                        </select>
                                    <?php
                                } else {
                                    ?>
                                        <input type="<?php echo $formDetail['type'] ?? 'text' ?>" id="<?php echo $formDetail['id']?>" class="form-control <?php echo isset($formDetail['is-invalid']) && $formDetail['is-invalid'] ? 'is-invalid' : '' ?>" <?php echo isset($formDetail['value']) ? 'value="'.$formDetail['value'].'"': ''?> name="<?php echo $formDetail['id']?>" required>
                                    <?php
                                }
                            ?>
                            <?php echo isset($formDetail['hint']) ? "<small ".(isset($formDetail['is-invalid']) && $formDetail['is-invalid'] ? 'class="invalid-feedback"' : '').">".$formDetail['hint']."</small>" : '<small></small>'?>
                        </div>
                    </div>
                <?php
                    }
                ?>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit"><?php echo ucfirst($formType)?></button>
                        <a href="<?php echo (new Route('auth',['t'=>($type == 'receiver' ? 'hospital' : 'receiver'),'f' => $formType]))->get()?>" style="color:<?php echo ($type == 'hospital' ? '#e8e834' : '#3c9df1');?>" class="btn" type="submit"><?php echo ucfirst($formType).' as '.ucfirst($type == 'receiver' ? 'hospital' : 'receiver') ?></a>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer text-muted">
            <?php
                if($formType == 'register') {
                    ?>
                    Already have an account? Login as <a href="<?php echo (new Route('auth',['t'=> 'receiver','f' => 'login']))->get()?>">Receiver</a> or <a href="<?php echo (new Route('auth',['t'=> 'hospital','f' => 'login']))->get()?>">Hospital</a>
                    <?php
                } else {
                    ?>
                    Don't have an account? Register as <a href="<?php echo (new Route('auth',['t'=> 'receiver','f' => 'register']))->get()?>">Receiver</a> or <a href="<?php echo (new Route('auth',['t'=> 'hospital','f' => 'register']))->get()?>">Hospital</a>
                    <?php
                }
            ?>
        </div>
    </div>
</div>

<script src="../resources/js/validator.js"></script>
<script>
    <?php 
        if($formType == 'register'){
    ?>
        setRules({
            name: {
                regex: /[^A-Za-z\ ]/,
                maxLength: 50,
            },
            username: {
                regex: /[^A-Za-z0-9]/,
                maxLength: 50,
                ajaxMessage: 'Username already exists',
                ajax: {
                    url:"<?php echo (new Route($formType))->get();?>",
                    type:"POST",
                    headers: {'X-Requested-With': 'XMLHttpRequest'},
                    data: {
                        userExists: true
                    },
                    success: (response) => JSON.parse(response).status == 200 && JSON.parse(response).data,
                    dataName: 'username'
                }
            },
            password: {
                regex: /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])/,
                maxLength: 16,
                matchRegex: false,
                minLength: 8
            },
            confirm_password: {
                matchIdValue: 'password',
                maxLength: 50,
                minLength: 8
            },
        })
    <?php
    } else {
    ?>
        setRules({
            username: {
                regex: /[^A-Za-z0-9]/,
                maxLength: 50,
            },
            password: {
                regex: /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])/,
                maxLength: 16,
                matchRegex: false,
                minLength: 8
            }
        })
    <?php
    }
    ?>
</script>

<?php
    require_once('components/footwrapper.php');
?>