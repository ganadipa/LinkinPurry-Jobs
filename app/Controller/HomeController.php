<?php

namespace App\Controller;

use App\Repository\Db\Db;
use App\Repository\Db\DbUser;
use \PDOException;
use App\Util\Enum\UserRoleEnum;
use App\Http\Request;
use App\Http\Response;
use App\Service\HomeService;
use App\Util\Enum\JobTypeEnum;
use App\Util\Enum\JenisLokasiEnum;
use App\Validator\ArrayValidator;
use Exception;

class HomeController {
    public static function home() {
        $viewPath = dirname(__DIR__) . '/View/HomePage.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            echo "View not found";
        }
    }

    public static function showProfile($params) {
        try {
            $user_id = (int) $params['params']['id'];
            echo "User id: $user_id<br>";

            // Use the singleton instance of Db to get the PDO connection
            $dbInstance = Db::getInstance();
            $db = $dbInstance->getConnection();
            $dbUser = new DbUser($db);

            $user = $dbUser->getUserProfileById($user_id);
            if ($user) {
                echo "User ID: {$user->user_id}<br>";
                echo "Email: {$user->email}<br>";
                echo "Role: {$user->role->value}<br>";
            } else {
                echo "User not found<br>";
            }

            echo "<br>Show All Users<br>";
            $users = $dbUser->getAllUsers();
            foreach ($users as $user) {
                echo "User ID: {$user->user_id}<br>";
                echo "Email: {$user->email}<br>";
                echo "Role: {$user->role->value}<br>";
                echo "<br>";
            }

        } catch (PDOException $e) {
            error_log('Show profile error: ' . $e->getMessage());
            echo 'Error: ' . $e->getMessage() . '<br>';
        }
    }    

    public static function addProfile($params) {
        try {
            $user_id = (int) $params['params']['id'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = $_POST['role'] === 'COMPANY' ? UserRoleEnum::COMPANY : UserRoleEnum::JOBSEEKER;

            $dbInstance = Db::getInstance();
            $db = $dbInstance->getConnection();
            $dbUser = new DbUser($db);

            $dbUser->saveProfile($user_id, $email, $password, $role);

            echo json_encode(['success' => true, 'message' => 'User profile added/updated successfully']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public static function removeProfile($params) {
        try {
            $user_id = (int) $params['params']['id'];

            $dbInstance = Db::getInstance();
            $db = $dbInstance->getConnection();
            $dbUser = new DbUser($db);

            $removed = $dbUser->removeProfile($user_id);
            if ($removed) {
                echo json_encode(['success' => true, 'message' => 'User profile removed successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'User not found']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // public static function clientPage() {
    //     $viewPath = dirname(__DIR__) . '/View/ClientView.php';
    //     if (file_exists($viewPath)) {
    //         require_once $viewPath;
    //     } else {
    //         echo "View not found";
    //     }
    // }

    public static function showHomePage(Request $req, Response $res): void {
        try {
            $q = $req->getQueryParam('q') ?? '';
            $jobType = $req->getQueryParam('job-type') ?? ['full-time', 'part-time', 'internship'];
            $locationType = $req->getQueryParam('location-type') ?? [
                'on-site', 'hybrid', 'remote'
            ];
            $sortOrder = $req->getQueryParam('sort-order') ?? 'desc';

            // Validate each query parameter
            $jobType = ArrayValidator::validate($jobType);
            $locationType = ArrayValidator::validate($locationType);

            foreach ($jobType as $type) {
                // If not in array then just remove it
                if (!in_array($type, [JobTypeEnum::FULL_TIME->value, JobTypeEnum::PART_TIME->value, JobTypeEnum::INTERNSHIP->value])) {
                    // remove
                    $jobType = array_filter($jobType, function($job) use ($type) {
                        return $job !== $type;
                    });
                }
            }

            foreach ($locationType as $type) {
                if (!in_array($type, [JenisLokasiEnum::ON_SITE->value, JenisLokasiEnum::HYBRID->value, JenisLokasiEnum::REMOTE->value])) {
                    $locationType = array_filter($locationType, function($location) use ($type) {
                        return $location !== $type;
                    });
                }
            }

            // Make the jobtype and location type as enum
            $jobType = array_map(function($type) {
                return JobTypeEnum::from($type);
            }, $jobType);
    
            $locationType = array_map(function($type) {
                return JenisLokasiEnum::from($type);
            }, $locationType);
    
    
            $user = $req->getUser();
    
            if ($user === null || $user->role === UserRoleEnum::JOBSEEKER) {
                $html = HomeService::getHomeJobSeekerPage(
                    $q, $jobType, $locationType, $sortOrder
                );
            } else {
                $html = HomeService::getHomeCompanyPage(
                    $q, $jobType, $locationType, $sortOrder
                );
            }
            
            $res->setBody($html);
            $res->send();
        } catch (Exception $e) {
            $res->setBody('Error: ' . $e->getMessage());
            $res->send();
        }

    }


}
