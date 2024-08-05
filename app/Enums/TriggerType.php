<?php

namespace App\Enums;

enum TriggerType: string
{
    case Start = 'start';
    case Next = 'next';
    case End = 'end';
}
