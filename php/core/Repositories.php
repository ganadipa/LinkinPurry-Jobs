<?php

namespace Core;

use App\Repository\Interface\RAttachmentLowongan;
use App\Repository\Interface\RCompanyDetail;
use App\Repository\Interface\RLamaran;
use App\Repository\Interface\RLowongan;
use App\Repository\Interface\RUser;
use App\Repository\Interface\RFile;


class Repositories {
    public static RAttachmentLowongan $attachmentLowongan;
    public static RCompanyDetail $companyDetail;
    public static RLamaran $lamaran;
    public static RLowongan $lowongan;
    public static RUser $user;
    public static RFile $file;
}