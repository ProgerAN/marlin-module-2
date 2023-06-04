<?php $this->layout('templates/dashboard', ['title' => 'Dashboard : : users']) ?>

<div class="subheader">
    <h1 class="subheader-title">
        <i class='subheader-icon fal fa-plus-circle'></i> Редактировать
    </h1>

</div>
<form action="/user/action-edit/<?=$user['user_id']?>" method="post">
    <div class="row">
        <div class="col-xl-6">
            <div id="panel-1" class="panel">
                <div class="panel-container">
                    <div class="panel-hdr">
                        <h2>Общая информация</h2>
                    </div>
                    <div class="panel-content">
                            <!-- username -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Имя</label>
                                <input name="name" type="text" id="simpleinput" class="form-control" value="<?=$user['user_name']?>">
                            </div>

                            <!-- title -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Место работы</label>
                                <input name="job" type="text" id="simpleinput" class="form-control" value="<?=$user['user_job']?>">
                            </div>

                            <!-- tel -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Номер телефона</label>
                                <input name="phone" type="tel" id="simpleinput" class="form-control" value="<?=$user['user_phone']?>">
                            </div>

                            <!-- address -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Адрес</label>
                                <input name="address" type="text" id="simpleinput" class="form-control" value="<?=$user['user_address']?>">
                            </div>
                            <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                <button class="btn btn-warning btn-submit">Редактировать</button>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>