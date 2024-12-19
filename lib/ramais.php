<?php
/**
 * Você deverá transformar em uma classe
 */
header("Content-type: application/json; charset=utf-8");
$ramais = file('ramais');
$filas = file('filas');
$status_ramais = array();
foreach($filas as $linhas){
    if(strstr($linhas,'SIP/')){
        if(strstr($linhas,'(Ring)')){
            $linha = explode(' ', trim($linhas));
            list($tech,$ramal) = explode('/',$linha[0]);
            $status_ramais[$ramal] = array('status' => 'chamando');
        }
        if(strstr($linhas,'(In use)')){            
            $linha = explode(' ', trim($linhas));
            list($tech,$ramal) = explode('/',$linha[0]);
            $status_ramais[$ramal] = array('status' => 'ocupado');    
        }
        if(strstr($linhas,'(Not in use)')){
            $linha = explode(' ', trim($linhas));
            list($tech,$ramal)  = explode('/',$linha[0]);
            $status_ramais[$ramal] = array('status' => 'disponivel');    
        }
    }
}
$info_ramais = array();
foreach($ramais as $linhas){
    $linha = array_filter(explode(' ',$linhas));
    $arr = array_values($linha);
    if(trim($arr[1]) == '(Unspecified)' AND trim($arr[4]) == 'UNKNOWN'){        
        list($name,$username) = explode('/',$arr[0]);        
        $info_ramais[$name] = array(
            'nome' => $name,
            'ramal' => $username,
            'online' => false,
            'status' => $status_ramais[$name]['status']
        );
    }
    if(trim($arr[5]) == "OK"){        
        list($name,$username) = explode('/',$arr[0]);
        $info_ramais[$name] = array(
            'nome' => $name,
            'ramal' => $username,
            'online' => true,
            'status' => $status_ramais[$name]['status']
        );
    }
}
echo json_encode($info_ramais);