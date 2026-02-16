<?php

namespace app\services;

use app\repositories\UserRepository;

class AuthService
{
    public static function normalizeTelephone($tel)
    {
        return preg_replace('/\s+/', '', trim((string)$tel));
    }

    public static function validateAuth(array $input, UserRepository $repo): array {
        $errors = ['email' => '', 'password' => ''];
        $email = trim((string)($input['email'] ?? ''));
        $password = (string)($input['password'] ?? '');

        if ($email === '') {
            $errors['email'] = "L'email est obligatoire.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Format d'email invalide.";
        }

        if (strlen($password) < 8) {
            $errors['password'] = "Le mot de passe doit contenir au moins 8 caractères.";
        }

        if ($errors['email'] !== '' || $errors['password'] !== '') {
            return ['ok' => false, 'errors' => $errors];
        }

        if ($repo->emailExists($email)) {
            if (!$repo->validateCredentials($email, $password)) {
                $errors['email'] = "Mot de passe ou adresse mail incorrect .";
                $errors['password'] = "Veuillez verifier vos identifiants.";

                return ['ok' => false, 'errors' => $errors];
            }
            $message = "Connexion réussie.";
        } else {
            $message = "Inscription réussie.";
        }

        return ['ok' => true, 'errors' => $errors, 'message' => $message, 'values' => ['email' => $email]];
    }
}