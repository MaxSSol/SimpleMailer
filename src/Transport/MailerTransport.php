<?php


namespace src\Transport;

use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;
use src\Transport\Message;

class MailerTransport implements TransportInterface
{
    private string $services;
    private string $username;
    private string $password;
    private string $encryption;
    private int $port;

    /**
     * MailerTransport constructor.
     * @param string $services for example: smtp.gmail.com
     * @param string $username username of your mail examlple@gmail.com
     * @param string $password password from your mail
     * @param string $encryption ecncryption: type(for example) tsl or ssl
     * @param integer $port for example 465
     */
    public function __construct(string $services, string $username, string $password, string $encryption, int $port)
    {
        $this->services = $services;
        $this->username = $username;
        $this->password = $password;
        $this->encryption = $encryption;
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getServices(): string
    {
        return $this->services;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getEncryption(): string
    {
        return $this->encryption;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    public function send(Message $message): bool
    {
        $transport = (new Swift_SmtpTransport($this->getServices(), $this->getPort()))
        ->setUsername($this->getUsername())
        ->setPassword($this->getPassword())
        ->setEncryption($this->getEncryption())
        ;
        $mailer = new Swift_Mailer($transport);
        $swiftMessage = (new Swift_Message($message->getSubject()))
            ->setFrom($message->getFrom())
            ->setTo($message->getTo())
            ->setBody($message->getBody())
        ;
        return (bool) $mailer->send($swiftMessage);
    }

}