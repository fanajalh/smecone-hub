<?php
header('Content-Type: application/json');
if (isset($_FILES['media'])) {
    echo json_encode(['success' => false, 'error' => 'UPLOAD_ERROR_CODE: ' . $_FILES['media']['error'] . ', tmp_name: ' . $_FILES['media']['tmp_name']]);
} else {
    echo json_encode(['success' => false, 'error' => 'NO FILES FOUND. POST: ' . json_encode($_POST)]);
}
