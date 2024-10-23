<?php

namespace App\Util\Enum;

enum JenisLokasiEnum: string {
    case ON_SITE = 'on-site';
    case REMOTE = 'remote';
    case HYBRID = 'hybrid';
}