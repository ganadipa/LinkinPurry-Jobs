<?php

namespace App\Service;

use App\Http\Exception\BadRequestException;
use App\Http\Exception\ForbiddenException;
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
            $lowonganRepo = Repositories::$lowongan;
            $lowongan = $lowonganRepo->getById($lowongan_id);

            if ($lowongan === null) {
                throw new BadRequestException('Job not found.');
            }

            if (!$lowongan->is_open) {
                throw new BadRequestException('Job is not open.');
            }

            $lamaranRepo = Repositories::$lamaran;
            $existingLamaran = $lamaranRepo->getLamaranByUserIdAndJobId($user_id, $lowongan_id);
            if ($existingLamaran !== null) {
                throw new BadRequestException('You have already applied for this job.');
            }

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

            throw new BadRequestException("Error applying job: " . $e->getMessage());
        }


    }

    public static function updateStatus(int $userId, int $lowonganId, string $status, string $statusReason): void {
        $lamaranRepo = Repositories::$lamaran;
        $lamaran = $lamaranRepo->getLamaranByUserIdAndJobId($userId, $lowonganId);
        $lamaran->status = StatusLamaranEnum::from($status);
        $lamaran->status_reason = $statusReason;
        $lamaranRepo->update($lamaran);
    }
    
    public static function acceptApplication(int $jobId, int $applicantId, string $reason, int $companyId): void {
        $lamaranRepo = Repositories::$lamaran;
        $lamaran = $lamaranRepo->getLamaranByUserIdAndJobId($applicantId, $jobId);

        $lowonganRepo = Repositories::$lowongan;
        $lowongan = $lowonganRepo->getById($jobId);

        if ($lamaran === null) {
            throw new Exception('Application not found.');
        }

        if ($lowongan->company_id != $companyId) {
            throw new ForbiddenException('You are not authorized to accept this application.');
        }

        $lamaran->status = StatusLamaranEnum::ACCEPTED;
        $lamaran->status_reason = $reason;

        $lamaranRepo->update($lamaran);
    }

    public static function rejectApplication(int $jobId, int $applicantId, string $reason, int $companyId): void {
        $lamaranRepo = Repositories::$lamaran;
        $lamaran = $lamaranRepo->getLamaranByUserIdAndJobId($applicantId, $jobId);
        $lamaran->status = StatusLamaranEnum::REJECTED;

        $lowonganRepo = Repositories::$lowongan;
        $lowongan = $lowonganRepo->getById($jobId);

        if ($lamaran === null) {
            throw new Exception('Application not found.');
        }

        if ($lowongan->company_id != $companyId) {
            throw new ForbiddenException('You are not authorized to reject this application.');
        }

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
            'user' => $user
        ]);
    }
}


