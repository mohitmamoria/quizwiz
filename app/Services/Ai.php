<?php

namespace App\Services;

use OpenAI;

class Ai
{
    protected $client = null;

    public function __construct()
    {
        $this->client = OpenAI::client(env('OPENAI_API_KEY'));
    }

    public function client()
    {
        return $this->client;
    }
}
