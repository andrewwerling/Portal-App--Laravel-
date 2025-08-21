<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LoginAttemptRecorded
{
    use Dispatchable, SerializesModels;

    public $loginData;

    /**
     * Create a new event instance.
     *
     * @param array $loginData
     */
    public function __construct(array $loginData)
    {
        $this->loginData = $loginData;
    }
}