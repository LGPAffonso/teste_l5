<?php

namespace Model;

require_once('..\Database\Banco.php');

use Database\Banco;




class RamalModel
{
    private $ramal;
    private $username;
    private $online;
    private $status;
    private $grupo;
    private $banco;

    public function __construct()
    {
        $this->banco = Banco::coneccao();
    }

    public function check($ramal)
    {
        $query = 'SELECT * from ramais_info where '
            . 'ramal =' . addslashes($ramal['ramal']);

        $result = $this->banco->query($query)->num_rows;

        return $result;
    }

    public function insert($ramal)
    {
        $query =  "INSERT INTO ramais_info (ramal, username, online, status, grupo) VALUES (" . addslashes($ramal['ramal']) . ",'" . addslashes($ramal['nome']) . "'," . addslashes($ramal['online']) . ",'" . addslashes($ramal['status']) . "','".addslashes($ramal['grupo'])."')";
        $this->banco->query($query);
        $result['rows'] = $this->banco->affected_rows;

        if ($result['rows'] != 1) {
            $result['erro'] = "Erro ao inserir Ramal " . $ramal['ramal'] .' ' .$this->banco->error;
        }

        return $result;
    }
    public function update($ramal)
    {

        $query = "UPDATE ramais_info set username='" . addslashes($ramal['nome']) . "', online=" . addslashes($ramal['online']) . ", status='" . addslashes($ramal['status']) . "', last_update = '".gmdate("Y-m-d H:i:s.uuuuuu", time())."', grupo='".addslashes($ramal['grupo'])."' where ramal=" . addslashes($ramal['ramal']);
        // $query = "UPDATE ramais_info set username='" . addslashes($ramal['nome']) . "', online=" . addslashes($ramal['online']) . ", status='" . addslashes($ramal['status']) . "', grupo='".addslashes($ramal['grupo'])."' where ramal=" . addslashes($ramal['ramal']);

        $this->banco->query($query);
        $result['rows'] = mysqli_affected_rows($this->banco);

        if ($result['rows'] != 1) {
            $result['erro'] = "Erro ao atualizar Ramal " . $ramal['ramal'] .' '.$result['rows'] .' linhas retornadas '.mysqli_error($this->banco);
        }
        return $result;
    }
}
