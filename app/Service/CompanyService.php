<?php

namespace App\Service;

use App\Model\CompanyDetail;
use App\Repository\Db\Db;

class CompanyService {
    private $dbCompanyDetail;

    public function __construct() {
        $this->dbCompanyDetail = Db::getInstance()->getDbCompanyDetail();
    }

    public function getCompanyDetailByUserId(int $userId): ?CompanyDetail {
        return $this->dbCompanyDetail->getCompanyDetailByUserId($userId);
    }

    public function updateCompanyDetail(array $data): CompanyDetail {
        $companyDetail = new CompanyDetail(
            $data['user_id'],
            $data['lokasi'],
            $data['about']
        );
        return $this->dbCompanyDetail->update($companyDetail);
    }
}