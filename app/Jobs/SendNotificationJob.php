<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\Notification;
class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tokens;
    protected $title;
    protected $body;
    protected $icon;
    protected $clickAction;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tokens, $title, $body, $icon, $clickAction)
    {
        $this->tokens = $tokens;
        $this->title = $title;
        $this->body = $body;
        $this->icon = $icon;
        $this->clickAction = $clickAction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $firebaseConfigPath = config('services.firebase.service_account_path');
        $factory = (new Factory)->withServiceAccount($firebaseConfigPath);
        $messaging = $factory->createMessaging();

        foreach ($this->tokens as $token) {
            $message = CloudMessage::withTarget('token', $token)
                ->withNotification(['title' => $this->title, 'body' => $this->body])
                ->withData(['icon' => $this->icon, 'click_action' => $this->clickAction]);

            try {
                $messaging->send($message);
            } catch (MessagingException $e) {
                Log::error("MessagingException: " . $e->getMessage());
            } catch (FirebaseException $e) {
                Log::error("FirebaseException: " . $e->getMessage());
            }
        }
    }
}

