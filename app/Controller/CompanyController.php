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
            $user_id = (int) $params['params']['id'];
            
            if (!isset(self::$DbCompanyDetail)) {
                $db = Db::getInstance()->getConnection();
                self::$DbCompanyDetail = new DbCompanyDetail($db);
            }
            
            $companyDetail = self::$DbCompanyDetail->getCompanyDetailByUserId($user_id);
    
            if (!$companyDetail) {
                echo json_encode(['error' => 'Company detail not found']);
                return;
            }
    
            echo json_encode($companyDetail);
        } catch (Exception $e) {
            error_log('Show profile error: ' . $e->getMessage());
            echo json_encode(['error' => 'Show profile error: ' . $e->getMessage()]);
        }
    }

    public static function updateProfile() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $user_id = $data['user_id'];
            $lokasi = $data['lokasi'];
            $about = $data['about'];

            if (!isset(self::$DbCompanyDetail)) {
                $db = Db::getInstance()->getConnection();
                self::$DbCompanyDetail = new DbCompanyDetail($db);
            }

            $companyDetail = new CompanyDetail($user_id, $lokasi, $about);
            $updatedCompany = self::$DbCompanyDetail->update($companyDetail);

            echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
        } catch (Exception $e) {
            error_log('Update profile error: ' . $e->getMessage());
            echo json_encode(['error' => 'Update profile error: ' . $e->getMessage()]);
        }
    }

    // Add methods for job vacancy management here
    // (e.g., getJobVacancies, addJobVacancy, updateJobVacancy, deleteJobVacancy)
    public static function getJobVacancies(array $params) {
        try {
            $user_id = (int) $params['params']['id'];
            
            if (!isset(self::$DbCompanyDetail)) {
                $db = Db::getInstance()->getConnection();
                self::$DbCompanyDetail = new DbCompanyDetail($db);
            }
            
            $jobVacancies = self::$DbCompanyDetail->getJobVacanciesByUserId($user_id);
    
            if (!$jobVacancies) {
                echo json_encode(['error' => 'Job vacancies not found']);
                return;
            }
    
            echo json_encode($jobVacancies);
        } catch (Exception $e) {
            error_log('Get job vacancies error: ' . $e->getMessage());
            echo json_encode(['error' => 'Get job vacancies error: ' . $e->getMessage()]);
        }
    }

    public static function addJobVacancy() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $user_id = $data['user_id'];
            $jobTitle = $data['job_title'];
            $jobDescription = $data['job_description'];

            if (!isset(self::$DbCompanyDetail)) {
                $db = Db::getInstance()->getConnection();
                self::$DbCompanyDetail = new DbCompanyDetail($db);
            }

            $jobVacancy = new JobVacancy($user_id, $jobTitle, $jobDescription);
            $addedJobVacancy = self::$DbCompanyDetail->addJobVacancy($jobVacancy);

            echo json_encode(['success' => true, 'message' => 'Job vacancy added successfully']);
        } catch (Exception $e) {
            error_log('Add job vacancy error: ' . $e->getMessage());
            echo json_encode(['error' => 'Add job vacancy error: ' . $e->getMessage()]);
        }
    }

    public static function updateJobVacancy() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $jobVacancyId = $data['job_vacancy_id'];
            $jobTitle = $data['job_title'];
            $jobDescription = $data['job_description'];

            if (!isset(self::$DbCompanyDetail)) {
                $db = Db::getInstance()->getConnection();
                self::$DbCompanyDetail = new DbCompanyDetail($db);
            }

            $jobVacancy = new JobVacancy($jobVacancyId, $jobTitle, $jobDescription);
            $updatedJobVacancy = self::$DbCompanyDetail->updateJobVacancy($jobVacancy);

            echo json_encode(['success' => true, 'message' => 'Job vacancy updated successfully']);
        } catch (Exception $e) {
            error_log('Update job vacancy error: ' . $e->getMessage());
            echo json_encode(['error' => 'Update job vacancy error: ' . $e->getMessage()]);
        }
    }

    public static function deleteJobVacancy() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $jobVacancyId = $data['job_vacancy_id'];

            if (!isset(self::$DbCompanyDetail)) {
                $db = Db::getInstance()->getConnection();
                self::$DbCompanyDetail = new DbCompanyDetail($db);
            }

            $deletedJobVacancy = self::$DbCompanyDetail->deleteJobVacancy($jobVacancyId);

            echo json_encode(['success' => true, 'message' => 'Job vacancy deleted successfully']);
        } catch (Exception $e) {
            error_log('Delete job vacancy error: ' . $e->getMessage());
            echo json_encode(['error' => 'Delete job vacancy error: ' . $e->getMessage()]);
        }
    }

    public static function showCompanyPage() {
        $viewPath = dirname(__DIR__) . '/View/CompanyView.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            echo "View not found";
        }
    }
}