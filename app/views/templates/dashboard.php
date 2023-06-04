<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=$this->e($title)?></title>
    <meta name="description" content="Chartist.html">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="/public/assets/css/vendors.bundle.css">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="/public/assets/css/app.bundle.css">
    <link id="myskin" rel="stylesheet" media="screen, print" href="/public/assets/css/skins/skin-master.css">
    <link rel="stylesheet" media="screen, print" href="/public/assets/css/fa-solid.css">
    <link rel="stylesheet" media="screen, print" href="/public/assets/css/fa-brands.css">
    <link rel="stylesheet" media="screen, print" href="/public/assets/css/fa-regular.css">
</head>
<body class="mod-bg-1 mod-nav-link">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-primary-gradient">
    <a class="navbar-brand d-flex align-items-center fw-500" href="/"><img alt="logo" class="d-inline-block align-top mr-2" src="/public/assets/img/logo.png"> Учебный проект</a> <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navbarColor02">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="/dashboard">Главная <span class="sr-only">(current)</span></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="/logout">Выйти</a>
            </li>
        </ul>
    </div>
</nav>

<main id="js-page-content" role="main" class="page-content mt-3">
    <?=$this->section('content')?>
</main>

<!-- BEGIN Page Footer -->
<footer class="page-footer" role="contentinfo">
    <div class="d-flex align-items-center flex-1 text-muted">
        <span class="hidden-md-down fw-700">2020 © Учебный проект</span>
    </div>
    <div>
        <ul class="list-table m-0">
            <li><a href="intel_introduction.html" class="text-secondary fw-700">Home</a></li>
            <li class="pl-3"><a href="info_app_licensing.html" class="text-secondary fw-700">About</a></li>
        </ul>
    </div>
</footer>

</body>

<script src="/public/assets/js/vendors.bundle.js"></script>
<script src="/public/assets/js/app.bundle.js"></script>
<script>

    $(document).ready(function()
    {

        $('input[type=radio][name=contactview]').change(function()
        {
            if (this.value == 'grid')
            {
                $('#js-contacts .card').removeClassPrefix('mb-').addClass('mb-g');
                $('#js-contacts .col-xl-12').removeClassPrefix('col-xl-').addClass('col-xl-4');
                $('#js-contacts .js-expand-btn').addClass('d-none');
                $('#js-contacts .card-body + .card-body').addClass('show');

            }
            else if (this.value == 'table')
            {
                $('#js-contacts .card').removeClassPrefix('mb-').addClass('mb-1');
                $('#js-contacts .col-xl-4').removeClassPrefix('col-xl-').addClass('col-xl-12');
                $('#js-contacts .js-expand-btn').removeClass('d-none');
                $('#js-contacts .card-body + .card-body').removeClass('show');
            }

        });

        //initialize filter
        initApp.listFilter($('#js-contacts'), $('#js-filter-contacts'));
    });

</script>

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

</html>