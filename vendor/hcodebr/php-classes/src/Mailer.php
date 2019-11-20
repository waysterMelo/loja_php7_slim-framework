<?php
namespace HCode;
use Rain\Tpl;

 class Mailer{

     const USERNAME = 'waystermelo@gmail.com';
     const PASSWORD = 'deusefiel2019';
     private $mail;

     public function __construct($toAddress, $toName, $subject, $tplName, $data = array()){
         $config = array(
             "tpl_dir" => $_SERVER['DOCUMENT_ROOT']."/views/email/",
             "cache_dir" =>$_SERVER['DOCUMENT_ROOT']."/views-cache/",
             "debug" => false
         );
         Tpl::configure($config);
         $tpl =  new Tpl();
         foreach ($data as $key => $value){
             $tpl->assign($key, $value);
         }
         $html = $tpl->draw($tplName, true);

         $this->mail = new \PHPMailer();

         $this->mail->isSMTP();
         $this->mail->SMTPDebug = 2;
         $this->mail->Debugoutput = 'html';
         $this->mail->Host = 'smtp.gmail.com';
         $this->mail->Port = 587;
         $this->mail->SMTPSecure = 'tls';
         $this->mail->SMTPAuth = true;
         $this->mail->Username =  Mailer::USERNAME;
         $this->mail->Password = Mailer::PASSWORD;
         $this->mail->setFrom('waystermelo@gmail.com', 'curso php 7');
         $this->mail->addAddress($toAddress, $toName);
         $this->mail->Subject = 'Email de teste';
         $this->mail->msgHTML($html);
         $this->mail->AltBody = 'this is a plain text message body';

     }
     public function send(){
      return $this->mail->send();
     }
 }