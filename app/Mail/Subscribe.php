<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Subscribe extends Mailable
{
    use Queueable, SerializesModels;
    public $email;
    public $type;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email,$type)
    {
        $this->email = $email;
        $this->type = $type;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject="";
        $markdown="";
        if($this->type == "unpaid"){
            $subject="Hai, Proyek anda sudah selesai nih!";
            $markdown = "emails.unpaid";
        }
        else if($this->type == "unClosedPayment"){
            $subject="Hai, Anda memiliki pembayaran yang belum selesai!";
            $markdown = "emails.unclosed";
        }else if($this->type == "verify"){
            $subject="Berikut adalah kode Verifikasi anda!";
            $markdown = "emails.verify";
        }
        else if($this->type == "inactive"){
            $subject="Halo, Sudah lama nih tidak aktif!";
            $markdown = "emails.inactive";
        }
        else if($this->type == "inactiveClient"){
            $subject="Halo, Sudah lama nih tidak aktif!";
            $markdown = "emails.inactiveClient";
        }

        return $this->subject($subject)
        ->markdown($markdown);
    }
}
