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
            $subject="Hey, your project is done!";
            $markdown = "emails.unpaid";
        }
        else if($this->type == "unClosedPayment"){
            $subject="Hey, Your payment is not completed yet!";
            $markdown = "emails.unclosed";
        }else if($this->type == "verify"){
            $subject="Here is your Verification Code!";
            $markdown = "emails.verify";
        }

        return $this->subject($subject)
        ->markdown($markdown);
    }
}
