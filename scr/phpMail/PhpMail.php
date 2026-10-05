<?php

require_once('PHPMailer/Exception.php');
require_once('PHPMailer/PHPMailer.php');
require_once('PHPMailer/SMTP.php');




use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email
{
    private PHPMailer $mail;
    private array $config;


    public function __construct(array $config)
    {
        $this->config = array_merge([
            'host'      => 'smtp.gmail.com',
            'username'  => '',
            'password'  => '',
            'port'      => 587,
            'fromEmail' => '',
            'fromName'  => '',
            'debug'     => false,
        ], $config);

        $this->mail = new PHPMailer(true);
        $this->configurar();
    }

    /**
     * Configura o servidor SMTP
     */
    private function configurar(): void
    {
        $this->mail->isSMTP();
        $this->mail->Host       = $this->config['host'];
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = $this->config['username'];
        $this->mail->Password   = $this->config['password'];
        $this->mail->Port       = $this->config['port'];

        // Debug (só em desenvolvimento)
        $this->mail->SMTPDebug = $this->config['debug']
            ? SMTP::DEBUG_SERVER
            : SMTP::DEBUG_OFF;

        // Remetente padrão
        if (!empty($this->config['fromEmail'])) {
            $this->mail->setFrom(
                $this->config['fromEmail'],
                $this->config['fromName']
            );
        }

        // Codificação
        $this->mail->CharSet = 'UTF-8';
    }

    /**
     * Define o destinatário principal
     */
    public function para(string $email, string $nome = ''): self
    {
        $this->mail->addAddress($email, $nome);
        return $this;
    }

    /**
     * Adiciona cópia (CC)
     */
    public function comCopia(string $email, string $nome = ''): self
    {
        $this->mail->addCC($email, $nome);
        return $this;
    }

    /**
     * Adiciona cópia oculta (BCC)
     */
    public function comCopiaOculta(string $email, string $nome = ''): self
    {
        $this->mail->addBCC($email, $nome);
        return $this;
    }

    /**
     * Adiciona e-mail de resposta
     */
    public function responderPara(string $email, string $nome = ''): self
    {
        $this->mail->addReplyTo($email, $nome);
        return $this;
    }

    /**
     * Anexa um arquivo
     */
    public function anexar(string $caminho, string $nome = ''): self
    {
        if (!file_exists($caminho)) {
            throw new Exception("Arquivo não encontrado: $caminho");
        }

        $this->mail->addAttachment($caminho, $nome);
        return $this;
    }

    /**
     * Define o assunto
     */
    public function assunto(string $assunto): self
    {
        $this->mail->Subject = $assunto;
        return $this;
    }

    /**
     * Define o corpo HTML
     */
    public function corpo(string $html): self
    {
        $this->mail->isHTML(true);
        $this->mail->Body = $html;
        return $this;
    }

    /**
     * Define o corpo em texto puro (fallback)
     */
    public function corpoTexto(string $texto): self
    {
        $this->mail->AltBody = $texto;
        return $this;
    }

    /**
     * Envia o e-mail
     * @throws Exception
     */
    public function enviar(): bool
    {
        return $this->mail->send();
    }

    /**
     * Retorna mensagem de erro (se houver)
     */
    public function getErro(): string
    {
        return $this->mail->ErrorInfo;
    }
}
