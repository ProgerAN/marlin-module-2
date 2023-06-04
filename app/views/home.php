<?php $this->layout('templates/auth', ['title' => 'Index page']) ?>

<div class="card p-4 border-top-left-radius-0 border-top-right-radius-0">

    <div class="alert alert-success">
        Welcome to the project
    </div>

    <? if (!$isLogged) : ?>
        <div class="row">
            <a href="/login" class="btn btn-info w-100">Войти</a>
            <a href="/registration" class="btn btn-info w-100 mt-4">Зарегистрироваться</a>
        </div>
    <? else : ?>
        <div class="row">
            <a href="/dashboard" class="btn btn-info w-100">Войти в панель</a>
        </div>
    <? endif; ?>
    


</div>