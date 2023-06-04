<?php

namespace App\controllers;

use App\models\Helpres;
use App\models\User;
use Delight\Auth\Auth;
use Delight\FileUpload\FileUpload;
use League\Plates\Engine;
use Medoo\Medoo;
use Tamtamchik\SimpleFlash\Flash;

class DashboardController
{

    private $template;
    private $pdo;

    public function __construct (\PDO $pdo, Engine $engine, Auth $auth) {
        $this->pdo = $pdo;
        $this->template = $engine;

        if (!$auth->isLoggedIn())
            Helpres::redirect('login');
    }

    public function index (Auth $auth, User $user)
    {
        $users = $user->getAll($auth->getUserId());
        $user = $user->getInfo();

        echo $this->template->render('dashboard/home', [
            'isAdmin' => $auth->hasRole(\Delight\Auth\Role::ADMIN),
            'users' => $users,
            'user' => $user
        ]);
    }

    public function user_add (User $user, Auth $auth)
    {
        if (!$auth->hasRole(\Delight\Auth\Role::ADMIN)){
            Flash::error('Отказвно в операции');
            Helpres::redirect('dashboard');
            return;
        }

        echo $this->template->render('dashboard/user/add');
    }

    public function user_action_add (Medoo $db, Auth $auth, FileUpload $upload)
    {
        if (!$auth->hasRole(\Delight\Auth\Role::ADMIN)){
            Helpres::message('Ошибка', 'Отказвно в операции');
            return;
        }

        try {
            $userId = $auth->admin()->createUser($_POST['email'], $_POST['password']);
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            Helpres::message('Ошибка', 'User already exist');
            die('Invalid email address');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            Helpres::message('Ошибка', 'User already exist');
            die('Invalid password');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            Helpres::message('Ошибка', 'User already exist');
            die('User already exists');
        }

        ###

        $upload->withTargetDirectory($_SERVER['DOCUMENT_ROOT'].'/public/avatars');
        $upload->withAllowedExtensions([ 'jpeg', 'jpg', 'png', 'gif' ]);
        $upload->withTargetFilename(uniqid());
        $upload->from('avatar');

        try {
            $uploadedFile = $upload->save();
            $avatar = $uploadedFile->getFilenameWithExtension();
        }
        catch (\Delight\FileUpload\Throwable\InputNotFoundException $e) {
            Helpres::message('Ошибка', 'input not found');
            // input not found
            return;
        }
        catch (\Delight\FileUpload\Throwable\InvalidFilenameException $e) {
            Helpres::message('Ошибка', 'invalid filename');
            // invalid filename
            return;
        }
        catch (\Delight\FileUpload\Throwable\InvalidExtensionException $e) {
            Helpres::message('Ошибка', 'invalid extension');
            // invalid extension
            return;
        }
        catch (\Delight\FileUpload\Throwable\FileTooLargeException $e) {
            Helpres::message('Ошибка', 'file too large');
            // file too large
            return;
        }
        catch (\Delight\FileUpload\Throwable\UploadCancelledException $e) {
            Helpres::message('Ошибка', 'upload cancelled');
            // upload cancelled
            return;
        }

        $db->insert("users_profile", [
            "user_id" => $userId,
            "user_name" => $_POST['name'],
            "user_job" => $_POST['job'],
            "user_phone" => $_POST['phone'],
            "user_address" => $_POST['address'],
            "user_status" => $_POST['status'],
            "user_avatar" => $avatar,
            "user_social" => json_encode([
                'vk' => $_POST['social_vk'],
                'telegram' => $_POST['social_telegram'],
                'instagram' => $_POST['social_insta'],
            ])
        ]);

        Flash::success('Пользователь добавлен');
        Helpres::location('dashboard', 1, 'Успешно', 'Происходит перенаправление...');
    }

    public function user_edit ($id, User $user, Auth $auth)
    {
        if (!$auth->hasRole(\Delight\Auth\Role::ADMIN)) {
            if ($auth->getUserId() != $id){
                Flash::error('Отказвно в операции');
                Helpres::redirect('dashboard');
                return;
            }
        }

        $user = $user->getInfo($id);
        echo $this->template->render('dashboard/user/edit', [
            'user' => $user
        ]);
    }

    public function user_action_edit ($id, Medoo $db, Auth $auth)
    {
        if (!$auth->hasRole(\Delight\Auth\Role::ADMIN)) {
            if ($auth->getUserId() != $id){
                Flash::error('Отказвно в операции');
                Helpres::redirect('dashboard');
                return;
            }
        }

        $data = $db->update("users_profile", [
            "user_name" => $_POST['name'],
            "user_job" => $_POST['job'],
            "user_phone" => $_POST['phone'],
            "user_address" => $_POST['address'],
        ], [
            "user_id[=]" => $id
        ]);

        Flash::success('Данные обновлены');
        Helpres::location('dashboard', 1, 'Успешно', 'Происходит перенаправление...');
    }

    public function user_secure ($id, User $user, Auth $auth)
    {
        if (!$auth->hasRole(\Delight\Auth\Role::ADMIN)) {
            if ($auth->getUserId() != $id){
                Flash::error('Отказвно в операции');
                Helpres::redirect('dashboard');
                return;
            }
        }

        $user = $user->getInfo($id);
        echo $this->template->render('dashboard/user/secure', [
            'user' => $user
        ]);
    }

    public function user_action_secure ($id, Medoo $db, Auth $auth)
    {
        if (!$auth->hasRole(\Delight\Auth\Role::ADMIN)) {
            if ($auth->getUserId() != $id){
                Flash::error('Отказвно в операции');
                Helpres::redirect('dashboard');
                return;
            }
        }

        if ($_POST['pass'] !== $_POST['re-pass']){
            Helpres::message('Ошибка', 'Пароли не совпадают');
            return;
        }

        try {
            $auth->admin()->changePasswordForUserById($id, $_POST['pass']);
        }
        catch (\Delight\Auth\UnknownIdException $e) {
            Helpres::message('Ошибка', 'Unknown ID');
            die('Unknown ID');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            Helpres::message('Ошибка', 'Invalid password');
            die('Invalid password');
        }

        Flash::success('Данные обновлены');
        Helpres::location('dashboard', 1, 'Успешно', 'Происходит перенаправление...');
    }

    public function user_status ($id, User $user, Auth $auth)
    {
        if (!$auth->hasRole(\Delight\Auth\Role::ADMIN)) {
            if ($auth->getUserId() != $id){
                Flash::error('Отказвно в операции');
                Helpres::redirect('dashboard');
                return;
            }
        }

        $user = $user->getInfo($id);
        echo $this->template->render('dashboard/user/status', [
            'user' => $user
        ]);
    }

    public function user_action_status ($id, Medoo $db, Auth $auth)
    {
        if (!$auth->hasRole(\Delight\Auth\Role::ADMIN)) {
            if ($auth->getUserId() != $id){
                Flash::error('Отказвно в операции');
                Helpres::redirect('dashboard');
                return;
            }
        }

        $data = $db->update("users_profile", [
            "user_status" => $_POST['status']
        ], [
            "user_id[=]" => $id
        ]);

        Flash::success('Данные обновлены');
        Helpres::location('dashboard', 1, 'Успешно', 'Происходит перенаправление...');
    }

    public function user_avatar ($id, User $user, Auth $auth)
    {
        if (!$auth->hasRole(\Delight\Auth\Role::ADMIN)) {
            if ($auth->getUserId() != $id){
                Flash::error('Отказвно в операции');
                Helpres::redirect('dashboard');
                return;
            }
        }

        $user = $user->getInfo($id);
        echo $this->template->render('dashboard/user/avatar', [
            'user' => $user
        ]);
    }

    public function user_action_avatar ($id, Medoo $db, Auth $auth, FileUpload $upload)
    {
        if (!$auth->hasRole(\Delight\Auth\Role::ADMIN)) {
            if ($auth->getUserId() != $id){
                Flash::error('Отказвно в операции');
                Helpres::redirect('dashboard');
                return;
            }
        }

        $uuidImg = uniqid();
        $upload->withTargetDirectory($_SERVER['DOCUMENT_ROOT'].'/public/avatars');
        $upload->withAllowedExtensions([ 'jpeg', 'jpg', 'png', 'gif' ]);
        $upload->withTargetFilename($uuidImg);
        $upload->from('avatar');

        try {
            $uploadedFile = $upload->save();

            $data = $db->update("users_profile", [
                "user_avatar" => $uploadedFile->getFilenameWithExtension()
            ], [
                "user_id[=]" => $id
            ]);

            Flash::success('Данные обновлены');
            Helpres::location('dashboard', 1, 'Успешно', 'Происходит перенаправление...');

            // $uploadedFile->getFilenameWithExtension()
            // $uploadedFile->getFilename()
            // $uploadedFile->getExtension()
            // $uploadedFile->getDirectory()
            // $uploadedFile->getPath()
            // $uploadedFile->getCanonicalPath()
        }
        catch (\Delight\FileUpload\Throwable\InputNotFoundException $e) {
            Helpres::message('Ошибка', 'input not found');
            // input not found
        }
        catch (\Delight\FileUpload\Throwable\InvalidFilenameException $e) {
            Helpres::message('Ошибка', 'invalid filename');
            // invalid filename
        }
        catch (\Delight\FileUpload\Throwable\InvalidExtensionException $e) {
            Helpres::message('Ошибка', 'invalid extension');
            // invalid extension
        }
        catch (\Delight\FileUpload\Throwable\FileTooLargeException $e) {
            Helpres::message('Ошибка', 'file too large');
            // file too large
        }
        catch (\Delight\FileUpload\Throwable\UploadCancelledException $e) {
            Helpres::message('Ошибка', 'upload cancelled');
            // upload cancelled
        }
    }

    public function user_delete ($id, Auth $auth, Medoo $db)
    {
        if (!$auth->hasRole(\Delight\Auth\Role::ADMIN)) {
            if ($auth->getUserId() != $id){
                Flash::error('Отказвно в операции');
                Helpres::redirect('dashboard');
                return;
            }
        }

        try {
            $auth->admin()->deleteUserById($id);
            $db->delete("users_profile", [
                "user_id[=]" => $id
            ]);

            if ($auth->getUserId() == $id){
                $auth->logOut();
                Helpres::redirect('login');
                return;
            }

            Flash::success('Пользователь удален');
            Helpres::redirect('dashboard');
            return;
        }
        catch (\Delight\Auth\UnknownIdException $e) {
            Flash::error('Unknown ID');
            Helpres::redirect('dashboard');
            die('Unknown ID');
        }
    }


}