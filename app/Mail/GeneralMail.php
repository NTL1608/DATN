<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\{Config, Mail};

class GeneralMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    protected $viewCus;
    protected $toCus;
    protected $subjectCus;
    protected $ccCus;
    protected $partFile;
    protected $fromEmail;
    protected $fromName;
    protected $sendFile;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sendFile)
    {
        $this->sendFile = $sendFile;
    }

    /**
     * set view and data view
     *
     * @param string $view
     * @param type $dataView
     * @return $this
     */
    public function setView($view, $dataView = null)
    {
        $this->viewCus = $view;
        $this->data = $dataView;
        return $this;
    }

    /**
     * set from email
     *
     * @param string $email
     * @param string $name
     * @return $this
     */
    public function setFrom($email, $name)
    {
        $this->fromEmail = $email;
        $this->fromName = $name;
        return $this;
    }

    /**
     * set from email
     *
     * @return $this
     */
    public function setFromDefault()
    {
        $this->fromEmail = Config::get('mail.username');
        $this->fromName = Config::get('mail.from.name');
        return $this;
    }

    /**
     * set to email
     *
     * @param string $toMail
     * @return $this
     */
    public function setTo($toMail)
    {
        $this->toCus = $toMail;
        return $this;
    }

    /**
     * set subject email
     *
     * @param type $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subjectCus = $subject;
        return $this;
    }

    /**
     * set cc email
     *
     * @param type $subject
     * @return $this
     */
    public function setCc($cc)
    {
        $this->ccCus = $cc;
        return $this;
    }

    /**
     * set cc email
     *
     * @param type $subject
     * @return $this
     */
    public function setAttachFile($partFile)
    {
        $this->partFile = $partFile;
        return $this;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->sendFile) {
            return $this->from($this->fromEmail, $this->fromName)
                ->view($this->viewCus)
                ->subject($this->subjectCus)
                ->attach($this->partFile, [
                    'as' => 'qrcode.svg',
                    'mime' => 'image/svg+xml',
                ]);
        }

        return $this->from($this->fromEmail, $this->fromName)
            ->view($this->viewCus)
            ->subject($this->subjectCus);
    }

    /**
     * sent mail
     *
     * @return type
     */
    public function sentMail()
    {
        return Mail::to($this->toCus)->send($this);
            
    }
}
