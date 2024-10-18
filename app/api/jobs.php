<?php
// api.php

header('Content-Type: application/json');

function generateJob($id) {
    $titles = ['Frontend Developer', 'Backend Developer', 'Full Stack Developer', 'UI/UX Designer', 'Product Manager'];
    $companies = ['TechCorp', 'InnoSoft', 'WebGenius', 'DataDrive', 'CloudNine'];
    $locations = ['New York, NY', 'San Francisco, CA', 'London, UK', 'Berlin, Germany', 'Tokyo, Japan'];

    return [
        'id' => $id,
        'title' => $titles[array_rand($titles)],
        'company' => $companies[array_rand($companies)],
        'location' => $locations[array_rand($locations)],
        'created' => rand(1, 30) . ' days ago'
    ];
}

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$perPage = 10;

$jobs = [];
for ($i = 0; $i < $perPage; $i++) {
    $jobs[] = generateJob(($page - 1) * $perPage + $i + 1);
}

echo json_encode($jobs);