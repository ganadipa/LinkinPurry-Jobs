<?php

namespace App\Controller;

use App\Model\CompanyDetail;
use App\Repository\Db\DbCompanyDetail;
use App\Repository\Db\Db;

class CompanyController {
    private static DbCompanyDetail $DbCompanyDetail;

    public function __construct() {
        $db = Db::getInstance()->getConnection();
        self::$DbCompanyDetail = new DbCompanyDetail($db);
    }

    public static function showProfile(array $params) {
        try {
            $user_id = (int) $params['params']['id'];  // Note the change here
            echo "User id: $user_id<br>";
            
            // Ensure DbCompanyDetail is initialized
            if (!isset(self::$DbCompanyDetail)) {
                $db = Db::getInstance()->getConnection();
                self::$DbCompanyDetail = new DbCompanyDetail($db);
            }
            
            $companyDetail = self::$DbCompanyDetail->getCompanyDetailByUserId($user_id);
            echo "HELLO I'M HEREEEE<br>";
    
            if (!$companyDetail) {
                echo "Company detail not found<br>";
                return;
            }
    
            echo "Company detail found<br>";
    
            $viewPath = dirname(__DIR__) . '/View/CompanyView.php';
            if (file_exists($viewPath)) {
                require_once $viewPath;
            } else {
                echo "View not found<br>";
            }
        } catch (Exception $e) {
            error_log('Show profile error: ' . $e->getMessage());
            echo 'Show profile error: ' . $e->getMessage() . '<br>';
        }
    }

    public static function updateProfile(array $params) {
        
    }
}