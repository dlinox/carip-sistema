<div class="cover"></div>
<div class="login-box">

  <div class="login-logo">
    <a href="#">Sistema Académico</a>
  </div>
  <div class="login-box-body">
    <div class="row">
      <div class="col-sm-6 hidden-xs">
        <img src="<?= base_url() ?>/assets/img/<?=$logo->empr_logo?>" class="" alt="User Image" style="">
      </div>
      <div class="col-sm-6">
        <p class="login-box-msg">Llena los datos para iniciar sesión</p>

        <form class="form-auth-small" action="<?php echo base_url() ?>login/verificar_login" method="POST">
          <?php if (isset($_GET["t"])) : ?>
            <div class="callout callout-danger">
              <p><?= $_GET["t"] ?></p>
            </div>
          <?php endif; ?>
          <div class="form-group has-feedback">
            <input name="user" type="text" class="form-control" placeholder="Usuario" autocomplete="off">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input name="password" type="password" class="form-control" placeholder="Contraseña">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <?php
          if (isset($_COOKIE["captcha_count"])) :
            if ($_COOKIE["captcha_count"] > 3) :
          ?>
              <div class="text-center">
                <?php echo $cap['image']; ?>
              </div>
              <div class="form-group">
                <label for="login-capcha">Capcha</label>
                <input type="text" placeholder="captcha" name="captcha" class="form-control" id="login-captcha">
              </div>
          <?php
            endif;
          endif;
          ?>
          <div class="row">
            <div class="col-xs-12">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Ingresar</button>
            </div>
          </div>
        </form>

      </div>
    </div>

  </div>