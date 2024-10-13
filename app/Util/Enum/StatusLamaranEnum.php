<?php

namespace App\Util\Enum;

enum StatusLamaranEnum: string {
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case WAITING = 'waiting';
}