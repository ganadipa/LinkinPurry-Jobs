<?php

namespace App\Util\Enum;

enum JobTypeEnum: string {
    case FULL_TIME = 'full-time';
    case PART_TIME = 'part-time';
    case INTERNSHIP = 'internship';
}