<?php

namespace Controller;

use \Model\RamalModel;

require_once('..\Model\RamalModel.php');
if (isset($_GET)) {
    $funcao = $_GET['funcao'];
    // $dados = $_GET['dados'];
} else if ($_POST) {
    $funcao = $_POST['funcao'];
    // $dados = $_POST['dados'];
}
$ramal = new RamalController;
$result = $ramal->$funcao();
echo json_encode($result);
die;

class RamalController
{
    private $ramalModel;

    private $funcao;
    private $dados;


    public function __construct()
    {
        $this->ramalModel =  new RamalModel();
    }

    // public function getAll()
    // {
    //     $resp = $this->ramalModel->getAll();
    //     return $resp;
    // }

    public function dashboard()
    {

        $filas = file('../lib/filas');
        // $ramais = file('../lib/ramais');
        $ramais = array_slice(file('../lib/ramais'), 1, -1);
        $status_ramais = array();
        foreach ($filas as $linhas) {
            if(strstr($linhas, 'gcallcenter-')){
                list($grupo, $nomegrupo) = explode('-',strstr($linhas, 'gcallcenter-'));
                list($nome) = explode(' ', $nomegrupo);
            }
            
            if (strstr($linhas, 'SIP/')) {
                if (strstr($linhas, '(Ring)')) {
                    $linha = explode(' ', trim($linhas));
                    list($tech, $ramal) = explode('/', $linha[0]);
                    $status_ramais[$ramal] = array('status' => 'chamando', 'nome' => '' . end($linha) . '', 'grupo' => ''.$nome.'');
                }
                if (strstr($linhas, '(In use)')) {
                    $linha = explode(' ', trim($linhas));
                    list($tech, $ramal) = explode('/', $linha[0]);
                    $status_ramais[$ramal] = array('status' => 'ocupado', 'nome' => '' . end($linha) . '', 'grupo' => ''.$nome.'');
                }
                if (strstr($linhas, '(Not in use)') && !strstr($linhas, '(paused)')) {
                    $linha = explode(' ', trim($linhas));
                    list($tech, $ramal)  = explode('/', $linha[0]);
                    $status_ramais[$ramal] = array('status' => 'disponivel', 'nome' => '' . end($linha) . '', 'grupo' => ''.$nome.'');
                }
                if (strstr($linhas, '(Unavailable)')) {
                    $linha = explode(' ', trim($linhas));
                    list($tech, $ramal)  = explode('/', $linha[0]);
                    $status_ramais[$ramal] = array('status' => 'indisponivel', 'nome' => '' . end($linha) . '', 'grupo' => ''.$nome.'');
                }
                if (strstr($linhas, '(paused)')) {
                    $linha = explode(' ', trim($linhas));
                    list($tech, $ramal)  = explode('/', $linha[0]);
                    $status_ramais[$ramal] = array('status' => 'pausa', 'nome' => '' . end($linha) . '', 'grupo' => ''.$nome.'');
                }
            }
        }
        $info_ramais = array();
        foreach ($ramais as $linhas) {
            $linha = array_filter(explode(' ', $linhas));
            $arr = array_values($linha);

            if (trim($arr[1]) == '(Unspecified)' and trim($arr[4]) == 'UNKNOWN') {
                list($name, $username) = explode('/', $arr[0]);

                $info_ramais[$username] = array(
                    'nome' => $status_ramais[$username]['nome'],
                    'ramal' => $username,
                    'online' => 0,
                    'status' => $status_ramais[$username]['status'],
                    'grupo' => $status_ramais[$username]['grupo']
                );
            }
            if (isset($arr[5]) && trim($arr[5]) == "OK") {
                list($name, $username) = explode('/', $arr[0]);

                $info_ramais[$username] = array(
                    'nome' => $status_ramais[$username]['nome'],
                    'ramal' => $username,
                    'online' => 1,
                    'status' => $status_ramais[$username]['status'],
                    'grupo' => $status_ramais[$username]['grupo']

                );
            }
        }
        $erros = $this->save($info_ramais);
        if(array_key_exists('erro',$erros)){
            $flgErro = true;
        }else{
            $flgErro = false;
        }
        $result['data']= $info_ramais;
        $result['erro']= $flgErro;
        $result['erros']= $erros;
        return $result;
    }

    public function save($info_ramais)
    {
        $resp= array();
        foreach ($info_ramais as $linha) {

            $update = $this->ramalModel->check($linha);

            if ($update) {
                $result = $this->ramalModel->update($linha);
                if (isset($result['erro'])) {
                    $resp['erro'][]=$result['erro'];
                }
            } else {
                $result = $this->ramalModel->insert($linha);
                if (isset($result['erro'])) {
                    $resp['erro'][]=$result['erro'];
                }
            }
        }
        return $resp;
    }
}
