<?php

namespace app\controllers;

use app\repositories\UserRepository;

use app\services\AuthService;
use app\services\UserService;
use Flight;

class AuthController
{
    public static function showLogin($app)
    {
        $app->render('auth/layout', [
            'values' => ['email' => ''],
            'errors' => ['email' => '', 'password' => ''],
            'page' => 'login',
            'title' => 'Connexion',
            'success' => false
        ]);
    }

    public static function validateLoginAjax($app) {
        header('Content-Type: application/json');
        $repo = new UserRepository($app->db());

        $input = Flight::request()->data->getData();

        $res = AuthService::validateAuth($input, $repo);
        Flight::json($res);
    }

    public static function postLogin($app) {
        $pdo = $app->db();
        $repo = new UserRepository($pdo);

        $input = Flight::request()->data->getData();

        $res = AuthService::validateAuth($input, $repo);

        if ($res['ok']) {
            $email = $res['values']['email'];

            if (!$repo->emailExists($email)) {
                $svc = new UserService($repo);
                $svc->register($res['values'], $input['password']);
            }

            $_SESSION['user'] = $repo->getAdminByEmail($email);

            Flight::redirect('/accueil');

            return;
        }

        $app->render('auth/layout', [
            'values' => ['email' => $input['email']],
            'errors' => $res['errors'],
            'page' => 'login',
            'success' => false
        ]);
    }

    public static function logout()
    {
        session_destroy();
        Flight::redirect('/login');
    }
}