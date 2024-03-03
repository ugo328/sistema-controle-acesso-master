<?php

namespace App\Models;
use MF\Model\Model;
use PDO;

class Users extends Model{

    private $id;
    private $nome;
    private $email;
    private $cpf;
    private $senha;
    private $nivel_acesso;
    private $telefone;

    public function __get($att){
        return $this->$att;
    }

    public function __set($att, $value){
        return $this->$att = $value;
    }

    public function validateRegistration(){
        if(strlen($this->nome) < 3 || strlen($this->email) < 3 || strlen($this->cpf) < 14 || strlen($this->senha) < 3 || empty($this->nivel_acesso)){
            return false;
        }
        return true;
    }

    public function validateUpdateRegister(){
        if(strlen($this->email) < 3 || strlen($this->cpf) < 14 || strlen($this->senha) < 3){
            return false;
        }
        return true;
    }

    public function getUserByEmail(){
        $stmt = $this->db->prepare("SELECT * FROM usuarios where email = :email");
        $stmt->bindValue(":email", $this->email);
        $stmt->execute();

        //O usuário ja foi cadastrado
        if($stmt->rowCount() > 0){
            return false;
        }
        return true;
    }

    public function getUserByCPF(){
        $stmt = $this->db->prepare("SELECT * FROM usuarios where cpf = :cpf");
        $stmt->bindValue(":cpf", $this->cpf);
        $stmt->execute();

        //O usuário ja foi cadastrado
        if($stmt->rowCount() > 0){
            return false;
        }
        return true;
    }

    public function registerUser(){
        $stmt = $this->db->prepare("INSERT INTO usuarios(nome, email, cpf, senha, nivel_acesso) values(:nome, :email, :cpf, :senha, :nivel_acesso)");
        $stmt->bindValue(":nome", $this->nome);
        $stmt->bindValue(":email", $this->email);
        $stmt->bindValue(":cpf", $this->cpf);
        $stmt->bindValue(":senha", $this->senha);
        $stmt->bindValue(":nivel_acesso", $this->nivel_acesso);
        $stmt->execute();
    }

    public function updateRegister(){
        $stmt = $this->db->prepare("SELECT * FROM usuarios where email = :email AND cpf = :cpf");
        $stmt->bindValue(":email", $this->email);
        $stmt->bindValue(":cpf", $this->cpf);
        $stmt->execute();

        //Vinculação entre E-mail e CPF confirmada, iniciando alteração de sehna
        if($stmt->rowCount() > 0){
            $stmt = $this->db->prepare("UPDATE usuarios SET senha = :senha where cpf =:cpf AND email =:email");
            $stmt->bindValue(":senha", $this->senha);
            $stmt->bindValue(":cpf", $this->cpf);
            $stmt->bindValue(":email", $this->email);
            $stmt->execute();
            return true;
        }
        else{
            return false;
        }
    }

    public function authenticate(){
        $stmt = $this->db->prepare("SELECT * FROM usuarios where email = :email AND senha = :senha");
        $stmt->bindValue(":email", $this->email);
        $stmt->bindValue(":senha", $this->senha);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!empty($usuario['id_usuario'])){
            $this->id = $usuario['id_usuario'];
            $this->nome = $usuario['nome'];
            $this->email = $usuario['email'];
            $this->cpf = $usuario['cpf'];
            $this->senha = $usuario['senha'];
            $this->nivel_acesso = $usuario['nivel_acesso']; 
            $this->telefone = $usuario['telefone']; 
            $this->data_nascimento = $usuario['data_nascimento']; 
            $this->genero = $usuario['genero']; 
            $this->imagem_usuario = $usuario['imagem_usuario']; 
        }
    }

    public function updateProfile($coluna, $valor, $id_usuario){
        $stmt = $this->db->prepare("UPDATE usuarios SET $coluna = :valor where id_usuario = :id_usuario");
        $stmt->bindValue(":valor", $valor);
        $stmt->bindValue(":id_usuario", $id_usuario);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            $stmt = $this->db->prepare("SELECT * FROM usuarios where id_usuario = :id_usuario");
            $stmt->bindValue(":id_usuario", $id_usuario);
            $stmt->execute();

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            session_start();
            $_SESSION['id'] = $usuario['id_usuario'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['cpf'] = $usuario['cpf'];
            $_SESSION['nivel_acesso'] = $usuario['nivel_acesso'];
            $_SESSION['telefone'] = $usuario['telefone'];
            $_SESSION['data_nascimento'] = $usuario['data_nascimento'];
            $_SESSION['genero'] = $usuario['genero'];
            $_SESSION['imagem_usuario'] = $usuario['imagem_usuario'];
        }
    }
}


?>