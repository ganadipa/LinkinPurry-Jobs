<?php

namespace App\Repository\Interface;
use App\Model\CompanyDetail;

interface RCompanyDetail {
    public function insert(CompanyDetail $companyDetail): CompanyDetail;
    public function delete(int $companyId): CompanyDetail;
    public function update(CompanyDetail $companyDetail): CompanyDetail;
    public function getCompanyDetailByUserId(int $userId): CompanyDetail;
}