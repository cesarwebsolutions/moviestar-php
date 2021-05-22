<?php

    require_once("globals.php");
    require_once("db.php");
    require_once("models/User.php");
    require_once("models/Message.php");
    require_once("dao/UserDAO.php");

    $message = new Message($BASE_URL);

    $userDao = new UserDAO($conn, $BASE_URL);

    // resgata o tipo de formulário
    $type = filter_input(INPUT_POST, "type");

    // verificação do tipo de formulário

    if($type === "register") {

        $name = filter_input(INPUT_POST, "name");
        $lastname = filter_input(INPUT_POST, "lastname");
        $email = filter_input(INPUT_POST, "email");
        $password = filter_input(INPUT_POST, "password");
        $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

        // verificação de dados minimos
        if($name && $lastname && $email && $password) {

            // verificar se as senhas são iguais
            if($password === $confirmpassword) {

                // verificar se o email já está cadastrado no sistema
                if($userDao->findByEmail($email) === false) {

                    $user = new User();

                    // Criação de token e senha
                    $userToken = $user->generateToken();
                    $finalPassword = $user->generatePassword($password);

                    $user->name = $name;
                    $user->lastname = $lastname;
                    $user->email = $email;
                    $user->password = $finalPassword;
                    $user->token = $userToken;

                    $auth = true;

                    $userDao->create($user, $auth);

                } else {

                    // envair uma msg de erro, email já cadastrado
                    $message->setMessage("E-mail já cadastrado, tente outro e-mail", "error", "back");

                }

            } else {

                // enviar uma msg de erro, as senhas não batem
                $message->setMessage("As senhas não são iguais.", "error", "back");

            }

        } else {

            // enviar uma msg de erro, de dados faltantes
            $message->setMessage("Por favor, preencha todos os campos.", "error", "back");

        }

    } elseif($type === "login") {

        $email = filter_input(INPUT_POST, "email");
        $password = filter_input(INPUT_POST, "password");

        //  Tenta autenticar usuario
        if($userDao->authenticateUser($email, $password)) {

            $message->setMessage("Seja bem-vindo!", "success", "editprofile.php");

        //  Redireciona o usuario, caso não consiguir autenticar 
        } else {
            
            $message->setMessage("Usuário e/ou senha inválidos", "error", "back");

        }

    } else {
        $message->setMessage("Informações inválidas!", "error", "index.php");
    }
