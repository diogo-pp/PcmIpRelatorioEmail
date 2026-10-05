<?php
//CSV
//fonte: https://www.php.net/manual/pt_BR/function.fputcsv.php
//criando o conjunto de valores a ser gravado
$lista = array (
    array('Dia e Hora','Fila Origem','Status','Tempo De Chamada','Tempo De Atendimento','Tempo De Espera','Centro De Custo','Atendido','Desligado as'),
    array('Pessoa1', '456', 'Rua XXX'),
    array('Pessoa2', '789', 'Rua yyy')
);
//definindo o nome do arquivo e abrindo o mesmo para escrita (write)
$fp = fopen("contatos.csv", 'w');
//percorrendo o conjunto de valores e gravando no arquivo
foreach ($lista as $linha) {
    fputcsv($fp, $linha,";");
}
//fechando o arquivo
fclose($fp);