<nav class="navbar navbar-expand-md navbar-dark bg-dark">
  <a class="navbar-brand" href="<?php echo (new Route('home'))->get()?>">
    Blood Bank
  </a>
  <?php if(isset($disableNav) && !$disableNav || !isset($disableNav)){ ?>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link <?php echo $bloodSamplesNav ?? ''?>" href="<?php echo (new Route('bloodSamples'))->get()?>">Samples</a>
      </li>
      <?php if(isset($auth->user()['id']) && isset($auth->user()['userType']) && $auth->user()['userType'] == "hospital") {
        ?>
        <li class="nav-item">
          <a class="nav-link <?php echo $addSamplesNav ?? ''?>" href="<?php echo (new Route('addSamples'))->get()?>">Add Blood Samples</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo (new Route('viewRequest'))->get()?>">View Requests</a>
        </li>
        <?php
      }
      ?>
      <?php 
        if(isset($auth->user()['id'])){
          ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="material-icons <?php echo $auth->user()['userType'] == 'receiver' ? 'text-warning' : 'text-primary'; ?>" style="vertical-align:text-top;font-size:20px!important;">
                <?php echo $auth->user()['userType'] == 'receiver' ? 'account_circle' : 'local_hospital'; ?>
              </span>
              <span>
                <?php echo $auth->user()['name'];?>
              </span>
            </a>
            <div class="dropdown-menu bg-dark text-secondary" aria-labelledby="profileDropdown">
              <?php echo $auth->user()['userType'] == 'receiver' ? '<a class="dropdown-item text-white">Blood Group: '.$auth->user()['blood_group'].'</a><div class="dropdown-divider"></div>' : '';?>
              <a class="dropdown-item <?php echo $auth->user()['userType'] == 'receiver' ? 'text-warning' : 'text-primary'?>" href="<?php echo (new Route('logout'))->get()?>">Logout</a>
            </div>
          </li>
          <?php
        }
      ?>
    </ul>
  </div>
  <?php } ?>
</nav>