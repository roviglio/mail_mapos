<?php 
# Inclui o arquivo class.phpmailer.php localizado na mesma pasta do arquivo php 
include "PHPMailer-master/PHPMailerAutoload.php"; 

# Inclui o arquivo de configuração tambem na raiz com nome de email.php
require_once('email.php');

# funcao para criação de arquivos log tambem na raiz 
function logMsg( $msg, $level = 'info', $file = 'main.log' )
{
    # variável que vai armazenar o nível do log (INFO, WARNING ou ERROR)
    $levelStr = '';
 
    # verifica o nível do log
    switch ( $level )
    {
        case 'info':
            # nível de informação
            $levelStr = 'INFO';
            break;
 
        case 'warning':
            # nível de aviso
            $levelStr = 'WARNING';
            break;
 
        case 'error':
            # nível de erro
            $levelStr = 'ERROR';
            break;
    }
 
    # data atual para uso no arquivo de log
    $date = date( 'Y-m-d H:i:s' );
 
    # formata a mensagem do log
    # 1o: data atual
    # 2o: nível da mensagem (INFO, WARNING ou ERROR)
    # 3o: a mensagem propriamente dita
    # 4o: uma quebra de linha
	
    $msg = sprintf( "[%s] [%s]: %s%s", $date, $levelStr, $msg, PHP_EOL );
 
    # escreve o log no arquivo
    # é necessário usar FILE_APPEND para que a mensagem seja escrita no final do arquivo, preservando o conteúdo antigo do arquivo
    file_put_contents( $file, $msg, FILE_APPEND );
}

# Inicia a classe PHPMailer 
$mail = new PHPMailer(); 

# Método de envio 
$mail->IsSMTP(); 

# Enviar por SMTP 
# Pega variavel do arquivo email.php
$mail->Host = $config['smtp_host']; 

# Você pode alterar este parametro para o endereço de SMTP do seu provedor 
# Pega a variavel do arquivo email.php
$mail->Port = $config['smtp_port']; 


# Usar autenticação SMTP (obrigatório)
# Pega variavel do arquivo email.php
$mail->SMTPAuth = $config['validate']; 

# Pega as variaveis do arquivo email.php
$mail->Username  = $config['smtp-user'];
$mail->Password = $config['smtp_pass'];

# Configurações de compatibilidade para autenticação em TLS 
$mail->SMTPOptions = array( 'ssl' => array( 'verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true ) ); 

# Você pode habilitar esta opção caso tenha problemas. Assim pode identificar mensagens de erro. 
# $mail->SMTPDebug = 2; 

# Substitua abaixo os dados, de acordo com o banco de dados
# Pega as variaveis do arquivo email.php

$host = $config['nome_host'];
$user = $config['user_banco']; 
$password = $config['pass_banco']; 
$database = $config['nome-banco']; 



# O hostname deve ser sempre localhost 
$hostname = $config['nome_host']; 

# Conecta com o servidor de banco de dados  

$link = mysqli_connect($host, $user, $password, $database);

// Escreve no arquivo de log que esta executando a tarefa
logMsg( "Executando a tarefa SendMail..." );

# Seleciona a tabela do banco de dados
$sql = mysqli_query($link,"select * from email_queue") or die("Erro");
while($dados=mysqli_fetch_assoc($sql))
{
# pega o numero de registros da tabela
$total = 0;
$n = 1;
$total = $sql->num_rows;

# nome do usuario que envia o e-mail pega do arquivo email.php
$mail->From = $mail->Username; 

# Seu nome do app no arquivo email.php que aparece no e-mail
$mail->FromName = $config['nome_app']; 

# Define o(s) destinatário(s) usa a variavel $dados com o vetor  ['to'] coluna to 
$mail->AddAddress($dados['to']); 

# Formato HTML . Use "false" para enviar em formato texto simples ou "true" para HTML.
$mail->IsHTML(true); 

# Charset (opcional) 
$mail->CharSet = 'UTF-8'; 

# Assunto da mensagem 
$mail->Subject = $config['assunto']; 

# Corpo do email igual ao do banco de dados colula mensagen que tem a OS
$mail->Body = $dados['message']; 

# Opcional: Anexos 
# $mail->AddAttachment("/home/usuario/public_html/documento.pdf", "documento.pdf"); 

# filtra o que vai enviar
$filtro = 'sent';
$varfiltro = $dados['status'];

if ($filtro = $varfiltro)
{ 
    # echo "Arquivo já enviado"; 
	logMsg( "Isto é um aviso... este arquivo já foi enviado anteriormente", 'warning' );
} else { 
    # Envia o e-mail 
$enviado = $mail->Send(); 

# salva que foi enviado na tabela do banco de dados
$result_usuario = "UPDATE email_queue SET status='sent'";
$resultado_usuario = mysqli_query($link, $result_usuario);
# Escreve uma mensagem no arquivo de log 
if ($enviado) 
{ 
    # Seu email foi enviado com sucesso!
	logMsg( "Seu email foi enviado com sucesso!" );
} else {
    # Houve um erro enviando o email
logMsg( "Isto é um erro. A operação SendMail falhou", 'error' );	
}

}
# Escreve no arquivo de log que terminou a operação
logMsg( "Tarefa SendMail terminada..." );


 

}


?>