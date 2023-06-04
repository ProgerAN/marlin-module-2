<?php $this->layout('templates/auth', ['title' => 'Login page']) ?>

<div class="card p-4 border-top-left-radius-0 border-top-right-radius-0">

    <h3>Авторизация</h3>

    <form action="/auth/logger" method="post">
        <div class="form-group">
            <label class="form-label" for="username">Email</label>
            <input name="email" type="email" id="username" class="form-control" placeholder="Введите email" value="" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="password">Пароль</label>
            <input name="password" type="password" id="password" class="form-control" placeholder="Введите пароль" required>
        </div>
        <button type="submit" class="btn btn-default float-right">Войти</button>
    </form>

</div>

<div class="blankpage-footer text-center">
    Нет аккаунта? <a href="/registration"><strong>Зарегистрироваться</strong>
</div>