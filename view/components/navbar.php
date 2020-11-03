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
          <a class="nav-link" href="#">View Requests</a>
        </li>
        <?php
      }
      ?>
      <?php 
        if(isset($auth->user()['id'])){
          ?>
          <li class="nav-item">
            <a class="nav-link btn <?php echo $auth->user()['userType'] == 'receiver' ? 'btn-warning' : 'btn-primary'?> text-white" href="<?php echo (new Route('logout'))->get()?>">Logout</a>
          </li>
          <?php
        }
      ?>
    </ul>
  </div>
  <?php } ?>
</nav>