<?php

namespace App\Enums;

enum TriggerType: string
{
    case Start = 'start';
    case Next = 'next';
    case End = 'end';

    public function label(): string
    {
        return match ($this) {
            TriggerType::Start => 'Start',
            TriggerType::Next => 'Next',
            TriggerType::End => 'End',
        };
    }
}
