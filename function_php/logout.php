<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Hapus semua data session
    session_unset();
    // Hancurkan session
    session_destroy();

    // Bersihkan cookies
    if (isset($_SERVER['HTTP_COOKIE'])) {
        $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
        foreach($cookies as $cookie) {
            $parts = explode('=', $cookie);
            $name = trim($parts[0]);
            setcookie($name, '', time()-3600, '/');
            setcookie($name, '', time()-3600);
        }
    }

    // Mengirim respons berhasil
    http_response_code(200);
    echo json_encode(array('message' => 'Logout success'));
} else {
    // Mengirim respons error jika halaman ini diakses secara langsung
    http_response_code(403);
    echo json_encode(array('message' => 'Forbidden'));
}
?>
