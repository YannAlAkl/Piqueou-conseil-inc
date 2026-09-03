<?php
  /**
  * Requires the "PHP Email Form" library
  * The library should be uploaded to: assets/vendor/php-email-form/php-email-form.php
  * For more info and help: https://bootstrapmade.com/php-email-form/
  */
 
  // Adresse qui recoit les demandes de contact
  $receiving_email_address = 'contact@piqueou-conseil.com';
 
  if( file_exists($php_email_form = '../assets/vendor/php-email-form/php-email-form.php' )) {
    include( $php_email_form );
  } else {
    die( 'Unable to load the "PHP Email Form" Library!');
  }
 
  $contact = new PHP_Email_Form;
  $contact->ajax = true;
 
  $contact->to = $receiving_email_address;
  $contact->from_name = $_POST['name'];
  $contact->from_email = $_POST['email'];
  $contact->subject = $_POST['subject'];
 
  // Serveur SMTP local Mailpit (conteneur Docker, port 1025)
  $contact->smtp = array(
    'host' => '127.0.0.1',
    'port' => '1025',
    'username' => 'test',
    'password' => 'test',
    'encryption' => '',
    'mailer' => 'site-vitrine@piqueou-conseil.com'
  );
 
  $contact->add_message( $_POST['name'], 'From');
  $contact->add_message( $_POST['email'], 'Email');
  isset($_POST['phone']) && $contact->add_message($_POST['phone'], 'Phone');
  $contact->add_message( $_POST['message'], 'Message', 10);
 
  echo $contact->send();
?>