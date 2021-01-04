<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
    'smtp_host' => 'mail.xxxx.xx', 
    'smtp_port' => 587,
    'smtp_user' => 'account@xxx.com',
    'smtp_pass' => 'password',
    'smtp_crypto' => 'tls', //can be 'ssl' or 'tls' for example
    'mailtype' => 'html', //plaintext 'text' mails or 'html'
    'charset' => 'iso-8859-1',
    'wordwrap' => TRUE
);