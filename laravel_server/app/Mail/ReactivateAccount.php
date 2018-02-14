<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class ReactivateAccount extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    private $user;

    public function __construct(User $user)
    {
     $this->user = $user;
 }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
     $user = $this->user;
     return $this->view('mail.reactivate-account', compact('user'));
 }
}
