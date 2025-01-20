<?php

abstract class User {

    protected $user_id;
    protected $username;
    protected $role_id;
    protected $email;
    protected $password;

 
    abstract public function loadUserData(): string;

   
    public function getUserRole(): int {
        return $this->role_id;
    }


    public function getUserId(): int {
        return $this->user_id;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }
}