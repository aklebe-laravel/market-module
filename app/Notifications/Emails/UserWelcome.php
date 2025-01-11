<?php

namespace Modules\Market\app\Notifications\Emails;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Modules\WebsiteBase\app\Services\SendNotificationService;

class UserWelcome extends Mailable
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
    public function __construct(User $user, string $html)
    {
        $this->user = $user;
        $this->html = $html;
    }

    /**
     * Get the message envelope.
     *
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        $toAddress = new Address($this->user->email, $this->user->name);
        $sendNotificationService = app(SendNotificationService::class);
        $fromAddress = $sendNotificationService->getSenderEmailAddress();

        return new Envelope(from: $fromAddress, to: $toAddress->address, // object not allowed
            subject: 'Welcome ...', tags: [
                'welcome',
                'greetings',
                'shop',
            ], metadata: [
                'user_id' => $this->user->shared_id,
            ],);
    }

}
