<?php

namespace Database;

use mysqli;

class Banco {
    private $mysqli;
    // public function __construct() {
    //     coneccao();
    // }

    public static function coneccao(){
        $servidor = '127.0.0.1';
        $usuario = 'root';
        $senha = '';
        $banco = 'teste_l5';
        // Conecta-se ao banco de dados MySQL
        $mysqli = new mysqli($servidor, $usuario, $senha, $banco);
        // Caso algo tenha dado errado, exibe uma mensagem de erro
        if (mysqli_connect_errno()) trigger_error(mysqli_connect_error());

        return$mysqli;
    }

}
