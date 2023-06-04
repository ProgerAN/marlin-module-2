<?php $this->layout('templates/dashboard', ['title' => 'Dashboard : : users']) ?>


<div class="subheader">
    <h1 class="subheader-title">
        <i class='subheader-icon fal fa-image'></i> Загрузить аватар
    </h1>
</div>

<form action="/user/action-avatar/<?=$user['user_id']?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="1048576">
    <div class="row">
        <div class="col-xl-6">
            <div id="panel-1" class="panel">
                <div class="panel-container">
                    <div class="panel-hdr">
                        <h2>Текущий аватар</h2>
                    </div>
                    <div class="panel-content">
                        <div class="form-group">
                            <img src="/public/avatars/<?= $user['user_avatar'] ?>" alt="" class="img-responsive" width="200">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="example-fileinput">Выберите аватар</label>
                            <input name="avatar" type="file" id="example-fileinput" class="form-control-file">
                        </div>


                        <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                            <button class="btn btn-warning btn-submit">Загрузить</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>