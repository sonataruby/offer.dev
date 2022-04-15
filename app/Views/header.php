<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="/assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="/assets/img/favicon.png">
  <title><?php echo $title;?></title>
  <link rel="canonical" href="<?php echo base_url();?>" />
  <?php 
  if(is_array($meta)){
      foreach($meta as $metakey => $metahead){
        ?>
        <meta name="<?php echo $metakey;?>" content="<?php echo $metahead;?>">
        <?php
      }
  }
  ?>
  <!--  Social tags      -->
  <meta name="keywords" content="<?php echo $keywords;?>">
  <meta name="description" content="<?php echo $description;?>">
  <!-- Twitter Card data -->
  <meta name="twitter:card" content="product">
  <meta name="twitter:site" content="<?php echo $tiwter;?>">
  <meta name="twitter:title" content="<?php echo $title;?>">
  <meta name="twitter:description" content="<?php echo $description;?>">
  <meta name="twitter:creator" content="<?php echo $tiwter;?>">
  <meta name="twitter:image" content="<?php echo $image;?>">
  <!-- Open Graph data -->
  <meta property="fb:app_id" content="<?php echo $facebook;?>">
  <meta property="og:title" content="<?php echo $title;?>" />
  <meta property="og:type" content="article" />
  <meta property="og:url" content="<?php echo current_url();?>" />
  <meta property="og:image" content="<?php echo $image;?>" />
  <meta property="og:description" content="<?php echo $description;?>" />
  <meta property="og:site_name" content="<?php echo $title;?>" />

  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="/assets/css/dashboard.css?v=2.0.2" rel="stylesheet" />
  

<body class="g-sidenav-show   bg-gray-100">