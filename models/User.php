<?php

    class User {
           
        public $id;   
        public $name;   
        public $lastname;   
        public $email;   
        public $password;   
        public $image;   
        public $bio;   
        public $token;  

        public function getFullName($user) {
            return $user->name . " " . $user->lastname;
        }
        
        public function generateToken() {
            return bin2hex(random_bytes(50)); // cria uma token com 50 caracteres e depois modifica novamente
        }

        public function generatePassword($password) {
            return password_hash($password, PASSWORD_DEFAULT);
        }

        public function generateImageName() {
            return bin2hex(random_bytes(60)) . ".jpg";
        }

    }   

    interface UserDAOInterface {

        public function buildUser($data); // receber uma data
        public function create(User $user, $authUser = false); // cria um usuario e faz login
        public function update(User $user, $redirect = true); // atualizar usuario no sistema
        public function verifyToken($protected = false); // proteger rotas somente para usuarios conectados
        public function setTokenToSession($token, $redirect = true); // redirecionar o usuario para alguma pagina
        public function authenticateUser($email, $password); // junta com o metodo de cima e faz autenticação completa
        public function findByEmail($email); // encontrar um usuario por email
        public function findById($id); // encontrar usuario pele ID
        public function findByToken($token); // encontrar um usuario pelo token
        public function destroytoken(); // desloga o usuario
        public function changePassword(User $user); // usar so metodo da troca de senha

    }