<?php
/**
 * @brief Classe Mailer
 *
 * @file mailer.class.php
 * @author Lucas ESPIET "espiet.l@valbion.com"
 * @version 1.0
 * @date 2024-12-20
 */

namespace ComusParty\Models;

use PHPMailer\PHPMailer\Exception as MailException;

/**
 * @brief Classe Mailer
 * @details Permet d'envoyer des mails avec la librairie PHPMailer
 */
class Mailer
{
    /**
     * @brief Tableau contenant les adresses mail des destinataires
     * @var array $to Tableau contenant les adresses mail des destinataires
     */
    private array $to;
    /**
     * @brief Sujet du mail
     * @var string $subject Sujet du mail
     */
    private string $subject;
    /**
     * @brief Contenu du mail
     * @var string $message Contenu du mail
     */
    private string $message;


    /**
     * @brief Constructeur de la classe Mailer
     * @param array $to Tableau des destinataires
     * @param string $subject Sujet du mail
     * @param string $message Message à envoyer
     */
    public function __construct(array $to, string $subject, string $message)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;
    }

    /**
     * @brief Getter de l'attribut to
     * @return array Tableau des destinataires
     */
    public function getTo(): array
    {
        return $this->to;
    }

    /**
     * @brief Setter de l'attribut to
     * @param array $to Tableau des destinataires
     */
    public function setTo(array $to): void
    {
        $this->to = $to;
    }

    /**
     * @brief Getter de l'attribut subject
     * @return string Sujet du mail
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @brief Setter de l'attribut subject
     * @param string $subject Sujet du mail
     */
    public function setSubject(string $subject): void
    {
        $this->subject = htmlentities($subject);
    }

    /**
     * @brief Getter de l'attribut message
     * @return string Contenu du mail
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @brief Setter de l'attribut message
     * @param string $message Contenu du mail
     */
    public function setMessage(string $message): void
    {
        $this->message = htmlentities($message);
    }

    /**
     * @brief Fonction permettant d'envoyer un mail
     * @return bool Retourne true si le mail a été envoyé, false sinon
     */
    public function send(): bool
    {
        if (empty($this->to) || empty($this->subject) || empty($this->message)) {
            return false;
        }

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = MAIL_HOST;
            $mail->SMTPAuth = true;
            $mail->Port = MAIL_PORT;
            $mail->Username = MAIL_USER;
            $mail->Password = MAIL_PASS;
            $mail->SMTPSecure = MAIL_SECURITY;
            $mail->setFrom(MAIL_FROM);
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->Subject = $this->subject . MAIL_BASE;
            $mail->AltBody = htmlentities($this->message);
            $mail->Body = $this->generateHTMLMessage();

            foreach ($this->to as $email) {
                $mail->addAddress($email);
            }

            $mail->send();
            return true;
        } catch (MailException $e) {
        }

        return false;
    }

    /**
     * @brief Fonction permettant de générer un message HTML avec la template de mail
     * @return string Retourne le message HTML
     */
    public function generateHTMLMessage(): string
    {
        $content = file_get_contents(__DIR__ . '/../templates/mail.twig');
        $content = str_replace('{{ subject }}', $this->getSubject(), $content);
        $content = str_replace('{{ message }}', $this->getMessage(), $content);
        return $content;
    }
}