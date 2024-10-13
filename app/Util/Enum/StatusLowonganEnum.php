<?php

namespace App\Util\Enum;

enum StatusLowonganEnum: string {
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case WAITING = 'waiting';
}