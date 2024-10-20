<?php

namespace App\Service;

use App\Model\CompanyDetail;
use Core\Repositories;

class CompanyService {

    public static function getCompanyDetailByUserId(int $userId): ?CompanyDetail {
        $companyDetailRepo = Repositories::$companyDetail;
        return $companyDetailRepo->getCompanyDetailByUserId($userId);
    }

    public static function updateCompanyDetail(array $data): CompanyDetail {
        $companyDetailRepo = Repositories::$companyDetail;
        $companyDetail = new CompanyDetail(
            $data['user_id'],
            $data['lokasi'],
            $data['about']
        );
        return $companyDetailRepo->update($companyDetail);
    }
}