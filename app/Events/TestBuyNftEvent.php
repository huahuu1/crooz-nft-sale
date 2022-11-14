<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Log;

class TestBuyNftEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): \Illuminate\Broadcasting\Channel|array
    {
        Log::info("public.buyNft");
        return new Channel('public.buyNft');
    }
    /**
     * Rename Event function
     *
     * @return void
     */
    public function broadcastAs(): string
    {
        return 'BuyNFT';
    }

    /**
     *  data function
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        Log::info("broadcastWith");

        return [
            'message' => 'Test Event BuyNFT',
            'data' => rand(10, 1000),
        ];
    }
}
