# mail_mapos
Testar o ambiente se envia e-mail no XAMMP com Windows10
 mail_mapos.php
Esta rotina foi feita para verificar o ambiente de desenvolvimento
e ou de uso, com windows10 e Xammp e ssl.
A ideia era saber se existe algum problema no ambiente que  impede 
o envio de e-mails do gmail e outros dominios, pois  no  meu  caso 
nenhum dominio funcionou com no MAPOS.
Esta rotina usa  o PHPMailer-master que  esta  no  github 
(https://github.com/PHPMailer/PHPMailer) e  dois
arquivos o send_mail.php que envia e altera o banco de dados  e  o
mail.php onde fica as configurações de email.


## WHAT´s NEW (junho 2021)
Envia  todos  os emails  no banco de dados do MAPOS e muda o status
para  enviado  caso os  e-mails  já tenham sido enviados ele pula o 
registro e vai para o proximo
Um log  é criado na raiz no caso do xammp na pasta htdos com o nome
de main.txt 

## INSTALAÇÃO
Não há, deve ser copiado na raiz, no caso do Xammp é a pasta htdocs

## DEPENDÊNCIAS
O MAPOS deve estar instalado.
O PHPMailer-master deve esta na pasta raiz .
No caso do Xammp é a pasta htdocs

## PARÂMETROS
Não há.

## COMO USAR
Configure o arquivo mail.php
Pode ser executado pelo navegado, apondando para o arquivo.
Ou pode ser executado pelo comando php mail_mapos.php

## NOTAS IMPORTANTES

O arquivo mail_mapos.php esta comentado o maximo que pude.
Os arquivo estão livre de vírus segundo o AVG internet Security
