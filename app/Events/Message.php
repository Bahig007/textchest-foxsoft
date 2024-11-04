<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Message implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $companyName;
    public $phoneNumber;
    public $messageContent; // Assuming you want to broadcast a message content too

    /**
     * Create a new event instance.
     *
     * @param string $companyName
     * @param string $phoneNumber
     * @param string $messageContent
     */
    public function __construct($companyName, $phoneNumber, $messageContent)
    {
        $this->companyName = $companyName;
        $this->phoneNumber = $phoneNumber;
        $this->messageContent = $messageContent; // Store the message content
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("CompanyUpdated"), // Dynamic channel name
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'companyName' => $this->companyName,
            'phoneNumber' => $this->phoneNumber,
            'message' => $this->messageContent, // Send message content as well
        ];
    }
}
