<?php

namespace App\Service;

use App\Model\File;
use Core\Repositories;
use App\Util\Enum\StatusLamaranEnum;
use DateTime;
use Exception;
use App\Model\Lamaran;
use App\Model\User;
use App\View\View;

class LamaranService {
// returns lamaran_id
    public static function applyJob(int $lowongan_id, int $user_id,mixed $cv, mixed $video): int {

        try {

            $localFileRepo = Repositories::$file;
            $cvFile = new File(
                $cv['name'],
                pathinfo($cv['name'], PATHINFO_EXTENSION),
                $cv['type'],
                $cv['size'],
                $cv['tmp_name']
            );

            if ($video !== null) $videoFile = new File(
                $video['name'],
                pathinfo($video['name'], PATHINFO_EXTENSION),
                $video['type'],
                $video['size'],
                $video['tmp_name']
            );

            if ($cv['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Error uploading CV.');
            }

            $cvRet = $localFileRepo->save($cvFile);

            $videoPath = null;
            if (isset($video) && $video['error'] === UPLOAD_ERR_OK) {
                $videoRet = $localFileRepo->save($videoFile);
                $videoPath = $videoRet->absolutePath;
            }

            

            $lamaran = new Lamaran(
                $user_id,
                $lowongan_id,
                $cvRet->absolutePath,
                $videoPath,
                StatusLamaranEnum::WAITING,
                '',
                new DateTime()
            );
    
            // Save the application to the database
            $lamaranRepo = Repositories::$lamaran;
    
            $lamaranRet = $lamaranRepo->save($lamaran);
    
            return $lamaranRet->lamaran_id;
        } catch (Exception $e) {
            // If there is an error, delete the uploaded files
            if (isset($cvRet)) {
                unlink($cvRet->absolutePath);
            }

            if (isset($videoRet)) {
                unlink($videoRet->absolutePath);
            }

            throw new Exception("Error applying job: " . $e->getMessage());
        }


    }

    public static function updateStatus(int $userId, int $lowonganId, string $status, string $statusReason): void {
        $lamaranRepo = Repositories::$lamaran;
        $lamaran = $lamaranRepo->getLamaranByUserIdAndJobId($userId, $lowonganId);
        $lamaran->status = StatusLamaranEnum::from($status);
        $lamaran->status_reason = $statusReason;
        $lamaranRepo->update($lamaran);
    }
    
    public static function acceptApplication(int $jobId, int $applicantId, string $reason): void {
        $lamaranRepo = Repositories::$lamaran;
        $lamaran = $lamaranRepo->getLamaranByUserIdAndJobId($applicantId, $jobId);
        $lamaran->status = StatusLamaranEnum::ACCEPTED;
        $lamaran->status_reason = $reason;

        $lamaranRepo->update($lamaran);
    }

    public static function rejectApplication(int $jobId, int $applicantId, string $reason): void {
        $lamaranRepo = Repositories::$lamaran;
        $lamaran = $lamaranRepo->getLamaranByUserIdAndJobId($applicantId, $jobId);
        $lamaran->status = StatusLamaranEnum::REJECTED;

        $lamaran->status_reason = $reason;
        $lamaranRepo->update($lamaran);
    }

    // get lamaran
    public static function getLamaranHistory(User $user): string {
        $lamaranRepo = Repositories::$lamaran;
        $lamaranList = $lamaranRepo->getLamaranByUserId($user->user_id);
        return View::view('Page/Job/Jobseeker', 'History', [
            'css' => ['job/history.css'],
            'js' => ['job/jobseeker/history.js'],
            'title' => 'Riwayat Lamaran',
            'lamaranList' => $lamaranList,
        ]);
    }
}


