<?php

namespace App\Service;

use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
class Mailer
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail(string $mailTo, string $type, $params = []): void
    {

        switch ($type) {
          case 'signup':
            $email = $this->createMailSignup($params)
            break;
          case 'newOffer'
            $email = $this->createMailToken($params)
            break;
          case 'canditateValidated'
            $email = $this->createValidationToken($params)
            break;
          default:
            return false;
            break;
        }

        $email
        ->to($mailTo)
        ->from('recrutax.2020@gmail.com');

        $this->mailer->send($email);
    }

    private function createMailSignup($params){

      return (new Email())
             ->subject()
             ->text('Welcome to our app '.$params['username'].' !')
             ->html('<p>Go on our app Recrutax and see the offers !</p>');

    }
    private function createMailToken($params){

      return (new Email())
             ->subject()
             ->text('Access this new offer on Recrutax')
             ->html('<p>Go on our app Recrutax enter the following token <b> '.$params['token'].' </b> !</p>');


    }
    private function createMailValidation($params){
      return (new Email())
             ->subject()
             ->text('Your application has been validated')
             ->html('<p>Hello '.params['username']. ' your candidature for the offer '.$params['offerName'].' is validated, the recruiter should contact you ASAP. !</p>');
    }
}
