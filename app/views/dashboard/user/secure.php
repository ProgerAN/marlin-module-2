<?php $this->layout('templates/dashboard', ['title' => 'Dashboard : : users']) ?>

<div class="subheader">
    <h1 class="subheader-title">
        <i class='subheader-icon fal fa-lock'></i> Безопасность
    </h1>

</div>
<form action="/user/action-secure/<?=$user['user_id']?>" method="post">
    <div class="row">
        <div class="col-xl-6">
            <div id="panel-1" class="panel">
                <div class="panel-container">
                    <div class="panel-hdr">
                        <h2>Обновление пароля</h2>
                    </div>
                    <div class="panel-content">


                        <!-- password -->
                        <div class="form-group">
                            <label class="form-label" for="simpleinput">Пароль</label>
                            <input name="pass" type="password" id="simpleinput" class="form-control">
                        </div>

                        <!-- password confirmation-->
                        <div class="form-group">
                            <label class="form-label" for="simpleinput">Подтверждение пароля</label>
                            <input name="re-pass" type="password" id="simpleinput" class="form-control">
                        </div>


                        <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                            <button class="btn btn-warning btn-submit">Изменить</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</form>
