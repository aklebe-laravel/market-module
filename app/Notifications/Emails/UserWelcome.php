<?php

namespace Modules\Market\app\Notifications\Emails;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserWelcome extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * public properties are accessible in view template
     * @var User
     */
    public User $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, string $html)
    {
        $this->user = $user;
        $this->html = $html;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        $toAddress = new Address($this->user->email, $this->user->name);
        $fromAddress = new Address(config('mail.from.address'), config('mail.from.name'));

        return new Envelope(from: $fromAddress, to: $toAddress->address, // object not allowed
            subject: 'Welcome ...', tags: [
                'welcome',
                'greetings',
                'shop'
            ], metadata: [
                'user_id' => $this->user->shared_id,
            ],);
    }

}
