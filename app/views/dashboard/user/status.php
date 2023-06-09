<?php $this->layout('templates/dashboard', ['title' => 'Dashboard : : users']) ?>

<div class="subheader">
    <h1 class="subheader-title">
        <i class='subheader-icon fal fa-sun'></i> Установить статус
    </h1>

</div>
<form action="/user/action-status/<?=$user['user_id']?>" method="post">
    <div class="row">
        <div class="col-xl-6">
            <div id="panel-1" class="panel">
                <div class="panel-container">
                    <div class="panel-hdr">
                        <h2>Установка текущего статуса</h2>
                    </div>
                    <div class="panel-content">
                        <div class="row">
                            <div class="col-md-4">
                                <!-- status -->
                                <div class="form-group">
                                    <label class="form-label" for="example-select">Выберите статус</label>
                                    <select name="status" class="form-control" id="example-select">
                                        <option value="0" <?=($user['user_status'] == 0 ? 'selected' : '')?>>Онлайн</option>
                                        <option value="1" <?=($user['user_status'] == 1 ? 'selected' : '')?>>Отошел</option>
                                        <option value="2" <?=($user['user_status'] == 2 ? 'selected' : '')?>>Не беспокоить</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                <button class="btn btn-warning btn-submit">Set Status</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</form>
