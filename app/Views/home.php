<?php echo view('header',is_array($header) ? $header : []); ?>
  <div class="min-height-300 bg-primary position-absolute w-100"></div>
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="/ " target="_blank">
        <img src="./assets/img/logo-ct-dark.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">Smart Project</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <?php echo view('leftmenu'); ?>
    </div>
    <div class="sidenav-footer mx-3 ">
      <?php if(logged_in()){ ?>
      <div class="card card-plain shadow-none" id="sidenavCard">
        <img class="w-30 mx-auto rounded-circle img-fluid border border-2 border-white" src="/assets/img/team-2.jpg" alt="sidebar_illustration">
      </div>
      <a href="/profile" class="btn btn-dark btn-sm w-100 mb-3">Profile</a>
      <a class="btn btn-primary btn-sm mb-0 w-100" href="/logout" type="button">Logout</a>
      <?php }else{ ?>
        <div class="card card-plain shadow-none" id="sidenavCard">
          <img class="w-30 mx-auto rounded-circle img-fluid border border-2 border-white" src="/assets/img/team-2.jpg" alt="sidebar_illustration">
        </div>
        <a href="/login" class="btn btn-dark btn-sm w-100 mb-3">Login</a>
        <a class="btn btn-primary btn-sm mb-0 w-100" href="/register" type="button">Register</a>
      <?php } ?>
    </div>

  </aside>
  <main class="main-content position-relative border-radius-lg ">
    
    <!-- Navbar -->
    <?php echo view('nav'); ?>
    <!-- End Navbar -->
    <?= $this->renderSection('main') ?>
  </main>
  <script src="/assets/js/jquery.js?v=2.0.2"></script>
  <script src="/assets/js/core/popper.min.js"></script>
  <script src="/assets/js/core/bootstrap.min.js"></script>
  <script src="/assets/js/moment.js?v=2.0.2"></script>
  <script src="/assets/js/axios.js?v=2.0.2"></script>
  <?php 
  if(is_array($js)){
      foreach($js as $javascript){
        ?>
        <script src="<?php echo $javascript;?>"></script>
        <?php
      }
  }
  ?>
  <?php 
  if(is_array($css)){
      foreach($css as $style){
        ?>
        <link href="<?php echo $style;?>" rel="stylesheet"></script>
        <?php
      }
  }
  ?>

 <?= $this->renderSection('javascript') ?>
 <?= $this->renderSection('style') ?>
 <?php echo view('footer',is_array($footer) ? $footer : []); ?>
