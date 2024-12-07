<?php

namespace Modules\Market\app\Notifications\Emails;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserAssignedToTrader extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * public properties are accessible in view template
     *
     * @var User
     */
    public User $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     *
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        $toAddress = new Address($this->user->email, $this->user->name);
        $fromAddress = new Address(config('mail.from.address'), config('mail.from.name'));

        return new Envelope(from: $fromAddress, to: $toAddress->address, // object not allowed
            subject: __('Welcome as trader.'), tags: [
                'welcome',
                'trader',
                'händler',
            ], metadata: [
                'user_id' => $this->user->shared_id,
            ],);
    }

    /**
     * Get the message content definition.
     *
     * @return Content
     */
    public function content(): Content
    {
        return new Content(view: 'notifications.emails.user-assigned-to-trader',
        //            text: 'notifications.emails.offers.created-text',
        //            markdown: 'notifications.emails.offers.created-md'
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
        return [];
    }
}
