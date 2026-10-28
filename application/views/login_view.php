
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $title_web; ?></title>

  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <link rel="shortcut icon" href="<?php echo base_url('assets_style/image/cirlce.png'); ?>">

  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet"
    href="<?php echo base_url('assets_style/assets/bower_components/bootstrap/dist/css/bootstrap.min.css'); ?>">

  <!-- Font Awesome -->
  <link rel="stylesheet"
    href="<?php echo base_url('assets_style/assets/bower_components/font-awesome/css/font-awesome.min.css'); ?>">

  <!-- Ionicons -->
  <link rel="stylesheet"
    href="<?php echo base_url('assets_style/assets/bower_components/Ionicons/css/ionicons.min.css'); ?>">

  <!-- AdminLTE -->
  <link rel="stylesheet" href="<?php echo base_url('assets_style/assets/dist/css/AdminLTE.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets_style/assets/dist/css/responsivelogin.css'); ?>">

  <!-- Google Font -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700">

  <style>
    * {
      box-sizing: border-box;
    }

    html,
    body {
      min-height: 100%;
      margin: 0;
    }

    body.login-page {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 25px 15px;
      overflow-x: hidden;

      /* Background perpustakaan */
      background:
        linear-gradient(
          135deg,
          rgba(10, 35, 70, 0.72),
          rgba(13, 76, 120, 0.48)
        ),
        url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=2000&q=85');

      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
      background-attachment: fixed;
    }

    .login-container {
      width: 100%;
      max-width: 430px;
      margin: auto;
    }

    .login-logo {
      margin-bottom: 20px;
      text-align: center;
    }

    .login-logo a {
      color: #fff;
      font-size: 30px;
      font-weight: 700;
      line-height: 1.3;
      text-decoration: none;
      text-shadow: 2px 3px 5px rgba(0, 0, 0, 0.8);
    }

    .login-logo .logo-icon {
      display: block;
      margin-bottom: 10px;
      color: #ffdc45;
      font-size: 48px;
    }

    .login-logo .logo-subtitle {
      display: block;
      margin-top: 8px;
      color: #ffdf57;
      font-size: 14px;
      font-weight: 600;
      letter-spacing: 2px;
    }

    .login-box-body {
      padding: 25px;
      border: 2px solid #226bbf;
      border-radius: 14px;
      background: rgba(255, 255, 255, 0.96);
      box-shadow: 0 12px 35px rgba(0, 0, 0, 0.35);
    }

    .login-box-msg {
      margin-bottom: 20px;
      padding: 0;
      color: #34495e;
      font-size: 15px;
      text-align: center;
    }

    .btn-group {
      margin-bottom: 8px;
    }

    .btn_level {
      padding: 11px 8px;
      font-size: 15px;
      font-weight: 600;
      transition: 0.2s;
    }

    .btn_level.active {
      color: #fff !important;
      background-color: #1672cf !important;
      border-color: #1672cf;
    }

    .btn_level.inactive {
      color: #4b6075;
      background-color: #f4f6f9;
    }

    .form-control {
      height: 43px;
      border-radius: 6px;
      box-shadow: none;
    }

    .form-control:focus {
      border-color: #1672cf;
      box-shadow: 0 0 5px rgba(22, 114, 207, 0.25);
    }

    .form-group {
      margin-bottom: 18px;
    }

    .btn-login {
      min-height: 42px;
      font-size: 15px;
      font-weight: 600;
      background: #1672cf;
      border-color: #1263b5;
      border-radius: 6px;
    }

    .btn-login:hover,
    .btn-login:focus {
      color: #fff;
      background: #105ba5;
    }

    .btn-selfservice {
      margin-top: 18px;
      padding: 12px 8px;
      color: #fff;
      background: #00a65a;
      border: 1px solid #008d4c;
      border-radius: 6px;
      font-size: 14px;
      font-weight: 700;
      white-space: normal;
      transition: 0.2s;
    }

    .btn-selfservice:hover,
    .btn-selfservice:focus {
      color: #fff;
      background: #008d4c;
    }

    .selfservice-info {
      margin-top: 10px;
      color: #596a7b;
      font-size: 12px;
      line-height: 1.6;
      text-align: center;
    }

    .login-footer {
      margin-top: 18px;
      padding: 15px 10px;
      color: #fff;
      background: rgba(10, 47, 91, 0.94);
      border-radius: 8px;
      text-align: center;
      box-shadow: 0 5px 18px rgba(0, 0, 0, 0.2);
    }

    .login-footer .copyright {
      color: #ffdf57;
      font-size: 13px;
    }

    .login-footer .instagram {
      color: #ffdf57;
      text-decoration: none;
      font-weight: 600;
    }

    .login-footer .instagram:hover {
      color: #fff;
      text-decoration: underline;
    }

    .login-footer .school-name {
      display: block;
      margin-top: 7px;
      color: #fff;
      font-size: 13px;
      font-weight: 600;
      letter-spacing: 1px;
    }

    @media screen and (max-width: 480px) {
      body.login-page {
        padding: 20px 12px;
        background-attachment: scroll;
        background-position: center;
      }

      .login-container {
        max-width: 100%;
      }

      .login-logo a {
        font-size: 26px;
      }

      .login-logo .logo-icon {
        font-size: 40px;
      }

      .login-box-body {
        padding: 20px 16px;
      }

      .btn_level {
        font-size: 14px;
      }

      .login-footer {
        padding: 13px 8px;
      }
    }
  </style>
</head>

<body class="hold-transition login-page">

  <div class="login-container">

    <!-- Logo dan Judul -->
    <div class="login-logo">
      <a href="<?php echo base_url(); ?>">
        <span class="logo-icon">
          <i class="fa fa-book"></i>
        </span>

        <b>Sistem Informasi</b>
        <br>
        <b>Perpustakaan</b>

        <span class="logo-subtitle">
          SDN GETASKEREP 01
        </span>
      </a>
    </div>

    <div id="tampilalert"></div>

    <!-- Login Box -->
    <div class="login-box-body">

      <p class="login-box-msg">
        <i class="fa fa-lock"></i>
        Silakan masuk untuk melanjutkan
      </p>

      <form action="<?php echo base_url('login/auth'); ?>" method="POST">

        <!-- Pilihan Level -->
        <div class="form-group">
          <div class="btn-group btn-group-justified"
               role="group"
               aria-label="Pilih Level">

            <div class="btn-group" role="group">
              <button
                type="button"
                value="petugas"
                class="active btn_level btn btn-default">
                <i class="fa fa-user"></i>
                Petugas
              </button>
            </div>

            <div class="btn-group" role="group">
              <button
                type="button"
                value="anggota"
                class="inactive btn_level btn btn-default">
                <i class="fa fa-users"></i>
                Anggota
              </button>
            </div>

          </div>
        </div>

        <!-- Level -->
        <input type="hidden" name="level" id="level" value="petugas">

        <!-- Username -->
        <div class="form-group has-feedback">
          <input
            type="text"
            class="form-control"
            placeholder="Username"
            id="user"
            name="user"
            required
            autocomplete="username">

          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>

        <!-- Password -->
        <div class="form-group has-feedback">
          <input
            type="password"
            class="form-control"
            placeholder="Password"
            id="pass"
            name="pass"
            required
            autocomplete="current-password">

          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>

        <!-- Tombol Login -->
        <div class="row">
          <div class="col-xs-7"></div>

          <div class="col-xs-5">
            <button
              type="submit"
              id="loding"
              class="btn btn-primary btn-block btn-flat btn-login">

              <i class="fa fa-sign-in"></i>
              Sign In

            </button>

            <div id="loadingcuy"></div>
          </div>
        </div>

      </form>

      <!-- Self Service -->
      <div class="text-center">

        <a
          href="<?php echo base_url('selfservice'); ?>"
          class="btn btn-selfservice btn-block">

          <i class="fa fa-desktop"></i>
          AKTIFKAN MODE SELF SERVICE

        </a>

        <div class="selfservice-info">
          <i class="fa fa-info-circle"></i>
          Gunakan mode ini untuk peminjaman dan pengembalian mandiri.
        </div>

      </div>

    </div>
    <!-- /.login-box-body -->

    <!-- Footer -->
    <footer class="login-footer">

      <span class="copyright">
        Copyright &copy; Ego Winasis <?php echo date('Y'); ?>
      </span>

      <span style="color: #ffdf57;"> | </span>

      <a
        href="https://www.instagram.com/egowinasis/"
        target="_blank"
        rel="noopener noreferrer"
        class="instagram">

        <i class="fa fa-instagram"></i>
        @egowinasis

      </a>

      <span class="school-name">
        <i class="fa fa-university"></i>
        SDN GETASKEREP 01
      </span>

    </footer>

  </div>
  <!-- /.login-container -->

  <div id="tampilkan"></div>

  <!-- jQuery -->
  <script src="<?php echo base_url('assets_style/assets/bower_components/jquery/dist/jquery.min.js'); ?>"></script>

  <!-- Bootstrap -->
  <script src="<?php echo base_url('assets_style/assets/bower_components/bootstrap/dist/js/bootstrap.min.js'); ?>"></script>

  <script>
    $(document).ready(function () {

      // Pilihan level Petugas dan Anggota
      $('.btn_level').click(function () {

        $('.btn_level')
          .removeClass('active')
          .addClass('inactive');

        $(this)
          .removeClass('inactive')
          .addClass('active');

        var level = $(this).val();

        $('#level').val(level);

      });

      // Simpan ukuran layar ke cookie
      var screenWidth = window.screen.width;
      var screenHeight = window.screen.height;

      document.cookie = 'width=' + screenWidth + '; path=/; SameSite=Lax';
      document.cookie = 'height=' + screenHeight + '; path=/; SameSite=Lax';

    });
  </script>

</body>
</html>
