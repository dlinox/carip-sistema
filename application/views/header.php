<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <link rel="shortcut icon" href="<?= base_url() ?>assets/images/icono_app.ico" type="image/x-icon">

  <title>Sistema Académico</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Theme style -->

  <link rel="stylesheet" href="<?= base_url() ?>assets/css/select2.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/AdminLTE.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/_all-skins.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/sweetalert/sweetalert.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/daterangepicker.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-colorpicker.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css">
  <?php
  $css = $this->cssjs->generate_css();
  echo isset($css) ? $css : "";
  ?>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<?php if ($this->router->class == 'login') :  ?>

  <body class="hold-transition login-page">
  <?php else :  ?>

    <body class="hold-transition skin-blue sidebar-mini">
      <?php require_once(APPPATH . 'views/templates.html'); ?>
      <div class="wrapper">
        <header class="main-header">
          <!-- Logo -->
          <a href="<?= base_url() ?>" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>R</b>S</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">Sistema Académico</span>
          </a>
          <!-- Header Navbar: style can be found in header.less -->
          <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </a>


            <div class="navbar-custom-menu">
              <ul class="nav navbar-nav">

                <li id="notificaciones" class="dropdown notifications-menu hidden">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="far fa-bell"></i>
                    <span id="total-noty" class="label label-warning">10</span>
                  </a>
                  <ul class="dropdown-menu">
                    <li class="header">Notificaciones</li>
                    <li>
                      <!-- inner menu: contains the actual data -->
                      <ul class="menu">
                        <li>
                          <a id="sin-completar" href="<?= base_url() ?>Ventas/lista">
                            <i class="fa fa-warning text-yellow"></i> <span>3</span> Ventas por completar
                          </a>
                        </li>
                        <li>
                          <a id="sin-grupo" href="<?= base_url() ?>grupos/grupos_alumnos">
                            <i class="fa fa-users text-aqua"></i> <span>20</span>  Alumnos sin grupo
                          </a>
                        </li>
                        <li>
                          <a id="gastos-dia" href="<?= base_url()?>Contabilidad/flujo_caja">
                          <i class="fas fa-cash-register text-red"></i>  S/. <span>500</span> Gastos del día
                          </a>
                        </li>

                      </ul>
                    </li>
                  </ul>
                </li>

                <li class="dropdown user user-menu">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <?php if ($this->session->userdata("userphoto") == '') : ?>
                      <img src="<?= base_url() ?>/assets/img/avatar.jpg" class="user-image" alt="<?= $this->session->userdata("username") ?>">
                    <?php
                    else : ?>
                      <img src="<?= base_url() ?>uploads/FotoUsuario/<?= $this->session->userdata("userphoto") ?>" class="user-image" alt="<?= $this->session->userdata("username") ?>">
                    <?php endif; ?>
                    <span class="hidden-xs"><?= $this->session->userdata("username") ?></span>
                  </a>
                  <ul class="dropdown-menu">


                    <!-- User image -->
                    <li class="user-header">

                      <?php if ($this->session->userdata("userphoto") == '') : ?>
                        <img src="<?= base_url() ?>/assets/img/avatar.jpg" class="img-circle" alt="<?= $this->session->userdata("username") ?>">

                      <?php
                      else : ?>
                        <img src="<?= base_url() ?>uploads/FotoUsuario/<?= $this->session->userdata("userphoto") ?>" class="img-circle" alt="<?= $this->session->userdata("username") ?>">

                      <?php endif; ?>
                      <p><?= $this->session->userdata("username") ?></p>

                    </li>
                    <!-- User Body -->
                    <?php if ($this->session->userdata('authorizedadmin')) : ?>
                      <!-- <li class="user-body" style="padding: 0px;">
                        <div class="row">
                          <div class="col-xs-12 text-center">
                            <a style="color: #fff !important;" href="<?= base_url() ?>usuario/habilitaPc" class="btn-permision btn btn-success btn-flat btn-block">Habilitar terminal</a>
                          </div>
                        </div>
                      </li> -->
                    <?php endif ?>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                      <div class="pull-left">
                        <a href="<?= base_url() ?>usuario/crear/<?= $this->session->userdata('authorized') ?>" class="btn btn-default btn-flat btn-edit-perfil">Perfil</a>
                      </div>
                      <div class="pull-right">
                        <a href="<?= base_url() ?>login/salir" class="btn btn-default btn-flat">Salir</a>
                      </div>
                    </li>
                  </ul>
                </li>
              </ul>
            </div>
          </nav>
        </header>

        <!-- =============================================== -->

        <!-- Left side column. contains the sidebar -->
        <aside class="main-sidebar">
          <!-- sidebar: style can be found in sidebar.less -->
          <section class="sidebar">
            <div class="user-panel">
              <a href="<?= base_url() ?>" class="image" style="align-items: center;">
                <img id="logo-header" src="<?= base_url() ?>/assets/img/logo.png" class="" alt="User Image" style="">
              </a>
            </div>
            <ul class="sidebar-menu" data-widget="tree">
              <li class="header">MENÚ PRINCIPAL</li>
              <?php
              $menu = $this->menu->getMenu();

              foreach ($menu as $i => $men) :
                $cClass = $this->router->fetch_class();
                $cMethod = $this->router->fetch_method();
                $classli = "";
                $dir = explode("/", $men["url"]);
                $nAdd = COUNT($men["add"]);
                $hrefli = ($nAdd > 0) ? "#" : $men["url"];
                $arrow = ($nAdd > 0) ? '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>' : '';
                if ($nAdd > 0) $classli = "treeview";
                if ($dir[0] == $cClass) $classli = $classli . " active";
              ?>
                <li class="<?= $classli ?>">
                  <a href="<?= base_url() . $hrefli ?>">
                    <i class="fa fa-<?= $men['icon'] ?>"></i> <span><?= $men["name"] ?></span><?= $arrow ?>
                  </a>
                  <?php if ($nAdd > 0) : echo '<ul class="treeview-menu">';
                    foreach ($men["add"] as $j => $sub) :
                      $sdir = explode("/", $sub["url"]);
                      $sclass = ($sdir[1] == $cMethod and $sdir[0] == $cClass) ? "active" : "";
                  ?>
                <li class="<?= $sclass ?>"><a href="<?= base_url() . $sub['url'] ?>"><i class="fa fa-<?= $sub['icon'] ?>"></i> <?= $sub["name"] ?></a></li>
            <?php endforeach;
                    echo "</ul>";
                  endif;  ?>

            </li>
          <?php endforeach ?>
            </ul>
          </section>
          <!-- /.sidebar -->
        </aside>
      <?php endif;  ?>