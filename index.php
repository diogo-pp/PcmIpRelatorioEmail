<?php
require_once 'vendor/autoload.php';
require_once 'scr/Variavel/Varialel.php';
require_once 'scr/phpMail/PhpMail.php';





$client = new \GuzzleHttp\Client();

$response = $client->request('GET', "$dns$token", [
    'body' => "{
        \"dateTimeStart\": \"$data_anterior 00:00\",
        \"dateTimeEnd\": \"$data_atual 23:59\",
        \"queues\": [
            \"6a85b6d9ead62e45120ffb52\"
        ],
        \"costCenter\": [],
        \"callerNumber\": [],
        \"status\": [],
        \"lessThan\": \"\",
        \"moreThan\": \"\"
    }",
    'headers' => [
        'Content-Type' => 'application/json',
    ],
]);
$resposta=$response->getBody();

//echo $data_anterior .'<br>';
$arrayResposta=json_decode($resposta, true);

//echo $queues['Relacionamento Cliente'].'<br>';



foreach($arrayResposta as $chamada) {
  $arrayinterno = [];

  
  echo "$chamada[dateStartCall], ";
  $arrayinterno[] = $chamada['dateStartCall'];

  echo "$chamada[queueDescription], ";
  $arrayinterno[] = $chamada['queueDescription'];

  

  echo "$chamada[callerNumber], ";
  $arrayinterno[] = $chamada['callerNumber'];
 
  $estatus= $chamada['callStatus']; 

    if($estatus=='answer' ){
      echo "ATENDIDO, ";
      $arrayinterno[] = "ATENDIDO";

    }elseif ($estatus=='abandon' ){
      echo "ABANDONADA, ";
      $arrayinterno[] = "ABANDONADA";

   }elseif($estatus=='timeout'){
      echo "TRANBORDO, ";
      $arrayinterno[] = "TRANBORDO";

   }else {
      echo ";";
      $arrayinterno[] =" " ;

   }



 $TotalCallTime= $chamada['TotalCallTime'] ?? $chamada['TotalTime'];
 $conversationCallTime= $chamada['conversationCallTime'] ?? $chamada['TotalTime'];
 $WaitCallTime= $chamada['WaitCallTime'] ?? $chamada['TotalTime'];

 echo "$TotalCallTime, ";
 $arrayinterno[] = $TotalCallTime;
 echo "$conversationCallTime, ";
 $arrayinterno[] =$conversationCallTime;
 echo "$WaitCallTime ";
 $arrayinterno[] =$WaitCallTime;
 
  //echo $chamada['events'][0] .'<br>';
  //echo count($chamada['events']).'<br>';
  //cho $chamada['events'][0]['type'] .'<br>';
  //echo "$evento  $operadoraAtendeu[operator] <br>";
  // print_r(count($chamada['events'])) ;
  $evento = count($chamada['events']);
  //  print_r($evento);
 if ($evento ) 
    {
      -- $evento;
      $operadoraAtendeu=$chamada['events'][$evento];
      // echo " $evento <br>";
    }

    // var_dump($operadoraAtendeu);

 
  if ($operadoraAtendeu['type'] == 'COMPLETEOPERATOR' || $operadoraAtendeu['type'] == 'COMPLETECALLER' ) {
    echo " $operadoraAtendeu[operator] <br>";
    $arrayinterno[] = $operadoraAtendeu['operator'];
 
  }else{
  echo' <br>';
  }
  
 
 $arrayFp[] = $arrayinterno;

}


$fp = fopen("tmp/$data_atual.csv", 'w');
//percorrendo o conjunto de valores e gravando no arquivo
foreach ($arrayFp as $linha) {
    fputcsv($fp, $linha,";");
}
//fechando o arquivo
fclose($fp);

$email = new Email($config);
$email->para('guilherme@pcmsolucoes.com.br')
      ->assunto('Relatório')
      ->corpo('<p>Segue em anexo</p>')
      ->anexar("tmp/$data_atual.csv")
      ->enviar();



