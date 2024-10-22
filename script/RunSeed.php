<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\Repository\Db\Db;
use App\Util\EnvLoader;

// Load environment variables;
if (getenv('ENVIRONMENT') !== 'docker') {
    EnvLoader::load(__DIR__ . "/../.env.local");
}

// Create the db tables;
$db = Db::getInstance();
$conn = $db->getConnection();

// Function to generate random strings
function randomString($length = 10) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)))), 1, $length);
}

// Seed data for `users` table
function seedUsers($conn) {
    $roleOptions = ['jobseeker', 'company'];
    
    $stmt = $conn->prepare("INSERT INTO public.users (email, password, role, nama) VALUES (:email, :password, :role, :nama)");
    
    for ($i = 0; $i < 100; $i++) {
        $email = randomString(8) . '@example.com';
        $password = password_hash(randomString(8), PASSWORD_BCRYPT); // Simple hashed password
        $role = $roleOptions[array_rand($roleOptions)];
        $nama = randomString(8);
        
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':nama', $nama);
        
        $stmt->execute();
    }

    echo "Seeded 100 users.\n";
}

// Seed data for `lowongan` table
function seedLowongan($conn) {
    $jenisLokasiOptions = ['on-site', 'hybrid', 'remote'];
    
    $stmt = $conn->prepare("INSERT INTO public.lowongan (company_id, posisi, deskripsi, jenis_pekerjaan, jenis_lokasi, is_open, created_at, updated_at) VALUES (:company_id, :posisi, :deskripsi, :jenis_pekerjaan, :jenis_lokasi, :is_open, :created_at, :updated_at)");
    
    for ($i = 0; $i < 100; $i++) {
        $companyId = rand(1, 100); // Assuming there are already 100 users with role 'company'
        $posisi = randomString(8);
        $deskripsi = randomString(20);
        $jenisPekerjaan = ['full-time', 'part-time', 'internship'][array_rand(['full-time', 'part-time', 'internship'])];
        $jenisLokasi = $jenisLokasiOptions[array_rand($jenisLokasiOptions)];
        $isOpen = rand(0, 1);
        $createdAt = date('Y-m-d H:i:s', strtotime('-' . rand(1, 365) . ' days'));
        $updatedAt = date('Y-m-d H:i:s');
        
        $stmt->bindParam(':company_id', $companyId);
        $stmt->bindParam(':posisi', $posisi);
        $stmt->bindParam(':deskripsi', $deskripsi);
        $stmt->bindParam(':jenis_pekerjaan', $jenisPekerjaan);
        $stmt->bindParam(':jenis_lokasi', $jenisLokasi);
        $stmt->bindParam(':is_open', $isOpen);
        $stmt->bindParam(':created_at', $createdAt);
        $stmt->bindParam(':updated_at', $updatedAt);
        
        $stmt->execute();
    }

    echo "Seeded 100 lowongan.\n";
}

// Seed data for `lamaran` table
function seedLamaran($conn) {
    $statusOptions = ['accepted', 'rejected', 'waiting'];
    
    $stmt = $conn->prepare("INSERT INTO public.lamaran (user_id, lowongan_id, cv_path, video_path, status, status_reason, created_at) VALUES (:user_id, :lowongan_id, :cv_path, :video_path, :status, :status_reason, :created_at)");
    
    for ($i = 0; $i < 100; $i++) {
        $userId = rand(1, 100);
        $lowonganId = rand(1, 100);
        $cvPath = '/path/to/cv' . $i . '.pdf';
        $videoPath = '/path/to/video' . $i . '.mp4';
        $status = $statusOptions[array_rand($statusOptions)];
        $statusReason = randomString(20);
        $createdAt = date('Y-m-d H:i:s', strtotime('-' . rand(1, 365) . ' days'));
        
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':lowongan_id', $lowonganId);
        $stmt->bindParam(':cv_path', $cvPath);
        $stmt->bindParam(':video_path', $videoPath);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':status_reason', $statusReason);
        $stmt->bindParam(':created_at', $createdAt);
        
        $stmt->execute();
    }

    echo "Seeded 100 lamaran.\n";
}

// Seed data for `attachment_lowongan` table
function seedAttachmentLowongan($conn) {
    $stmt = $conn->prepare("INSERT INTO public.attachment_lowongan (lowongan_id, file_path) VALUES (:lowongan_id, :file_path)");
    
    for ($i = 0; $i < 100; $i++) {
        $lowonganId = rand(1, 100);
        $filePath = '/path/to/file' . $i . '.pdf';
        
        $stmt->bindParam(':lowongan_id', $lowonganId);
        $stmt->bindParam(':file_path', $filePath);
        
        $stmt->execute();
    }

    echo "Seeded 100 attachment_lowongan.\n";
}

// Seed data for `company_detail` table
function seedCompanyDetail($conn) {
    $stmt = $conn->prepare("INSERT INTO public.company_detail (user_id, lokasi, about) VALUES (:user_id, :lokasi, :about)");
    
    for ($i = 0; $i < 100; $i++) {
        $userId = $i + 1; 
        $lokasi = randomString(10);
        $about = randomString(30);
        
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':lokasi', $lokasi);
        $stmt->bindParam(':about', $about);
        
        $stmt->execute();
    }

    echo "Seeded 100 company_detail.\n";
}

// Call the seed functions
seedUsers($conn);
seedLowongan($conn);
seedLamaran($conn);
seedAttachmentLowongan($conn);
seedCompanyDetail($conn);

echo "All tables seeded.\n";
