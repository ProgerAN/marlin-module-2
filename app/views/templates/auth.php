<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=$this->e($title)?></title>
    <meta name="description" content="Login">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <!-- Call App Mode on ios devices -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <!-- Remove Tap Highlight on Windows Phone IE -->
    <meta name="msapplication-tap-highlight" content="no">
    <!-- base css -->
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="/public/assets/css/vendors.bundle.css">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="/public/assets/css/app.bundle.css">
    <link id="mytheme" rel="stylesheet" media="screen, print" href="#">
    <link id="myskin" rel="stylesheet" media="screen, print" href="/public/assets/css/skins/skin-master.css">
    <!-- Place favicon.ico in the root directory -->
    <link rel="apple-touch-icon" sizes="180x180" href="/public/assets/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/public/assets/img/favicon/favicon-32x32.png">
    <link rel="mask-icon" href="/public/assets/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="stylesheet" media="screen, print" href="/public/assets/css/page-login-alt.css">
</head>
<body>

<div class="blankpage-form-field">
    <div class="page-logo m-0 w-100 align-items-center justify-content-center rounded border-bottom-left-radius-0 border-bottom-right-radius-0 px-4">
        <a href="javascript:void(0)" class="page-logo-link press-scale-down d-flex align-items-center">
            <img src="/public/assets/img/logo.png" alt="SmartAdmin WebApp" aria-roledescription="logo">
            <span class="page-logo-text mr-1">Учебный проект</span>
            <i class="fal fa-angle-down d-inline-block ml-1 fs-lg color-primary-300"></i>
        </a>
    </div>

    <?=$this->section('content')?>

</div>

<video poster="/public/assets/img/backgrounds/clouds.png" id="bgvid" playsinline autoplay muted loop>
    <source src="/public/assets/media/video/cc.webm" type="video/webm">
    <source src="/public/assets/media/video/cc.mp4" type="video/mp4">
</video>
<script src="/public/assets/js/vendors.bundle.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script>
    $(document).ready(function () {
        $('form').submit(function (event) {
            if ($(this).attr('id') == 'no_ajax') {
                return
            }
            var json;
            event.preventDefault();

            //Disable our button
            let sbm_button = $('.btn-submit');
            let sbm_txt = sbm_button.text();
            sbm_button.attr("disabled", true);
            sbm_button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="visually-hidden">Loading...</span>   ' + sbm_txt);

            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (result) {
                    //Active our button
                    sbm_button.attr("disabled", false);
                    sbm_button.html(sbm_txt);

                    json = jQuery.parseJSON(result);
                    if (json.method == 'url') {
                        if (json.isSwal == 0) {
                            window.location.href = '/' + json.url;
                        } else if (json.isSwal == 1) {
                            swal({
                                title: json.title,
                                text: json.message,
                                timer: json.timer,
                                buttons: {
                                    cancel: false,
                                    confirm: "Ok, next.",
                                },
                            }).then(function () {
                                window.location.href = '/' + json.url;
                            });
                        } else if (json.isSwal == 2) {
                            document.location.href = json.url;
                        }

                    } else if (json.method == 'message') {
                        //Notiflix.Notify.Info(json.message);
                        swal({
                            title: json.status,
                            text: json.message,
                            timer: 3000,
                            buttons: false,
                        });
                        //alert(json.status + ' - ' + json.message);
                    }
                },
            });
        });
    });
</script>
</body>
</html>
