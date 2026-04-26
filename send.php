<?php

// Basic validation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name  = isset($_POST['name']) ? trim($_POST['name']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';

    if (empty($name) || empty($phone)) {
        echo "Missing fields";
        exit;
    }

    // Sanitize (basic)
    $name  = str_replace(["\n", "\r", ","], " ", $name);
    $phone = str_replace(["\n", "\r", ","], " ", $phone);

    // CSV file
    $file = 'leads.csv';

    // Create file with header if not exists
    if (!file_exists($file)) {
        file_put_contents($file, "Name,Phone,Date\n");
    }

    // Append data
    $data = [$name, $phone, date('Y-m-d H:i:s')];

    $fp = fopen($file, 'a');
    fputcsv($fp, $data);
    fclose($fp);

    echo "Submitted successfully";
}
else {
    echo "Invalid request";
}