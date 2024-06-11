<?php

use Random\RandomException;

date_default_timezone_set('Asia/Kuala_Lumpur');

$_SERVER['APP_ENV'] = 'development';
$base_url = '/CB22159/fkpark';  // satu lagi tempat kene sync is in vite.config.js, di line 5
$hasImportedVite = false;

session_start();

function vite_asset($path) {
    global $hasImportedVite, $base_url;
    // Check the environment
    if ($_SERVER['APP_ENV'] === 'development') {
        // Development environment, use Vite server
        if (!$hasImportedVite) {
            echo '<script type="module" src="http://localhost:5173/@vite/client"></script>';
            $hasImportedVite = true;
        }
            
        echo '<script type="module" src="http://localhost:5173/' . $path . '"></script>';
    } else {
        // Production environment, use build assets
        $manifest = json_decode(file_get_contents(__DIR__ . '/build/.vite/manifest.json'), true);
        $asset = $manifest[$path];

        // Output the script tag for the JavaScript file
        if (isset($asset['file'])) {
            echo '<script type="module" src="' . $base_url . '/build/' . $asset['file'] . '"></script>';
        }

        // Output the link tag for the associated CSS file, if any
        if (isset($asset['css']) && count($asset['css']) > 0) {
            foreach ($asset['css'] as $css) {
                echo '<link rel="stylesheet" href="' . $base_url . '/build/' . $css . '">';
            }
        }
    }
}

function session_pop($key) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION[$key])) {
        // Store the value in a temporary variable
        $value = $_SESSION[$key];

        // Unset the session variable
        unset($_SESSION[$key]);

        // Return the value
        return $value;
    }
    return null; // Return null if the key doesn't exist
}

function extract_destination_path($input_string) {
    $pattern = '/(storage.*$)/';
    preg_match($pattern, $input_string, $match);
    return $match[0];
}

function generateRandomString($length = 10, $defaultValue = '0'): string {
    try {
        // Generate random bytes and encode them to hexadecimal
        $randomBytes = random_bytes($length);
        $string = bin2hex($randomBytes);

        // Return the substring in case the length needs to be exact
        return substr($string, 0, $length);
    } catch (Exception $e) {
        // If an error occurs, return the default value
        return $defaultValue;
    }
}

function resolvePath($path) {
    // Remove any unnecessary slashes
    $path = preg_replace('#/+#','/', str_replace('\\', '/', $path));

    $parts = array_filter(explode('/', $path), 'strlen');
    $absolutes = array();

    foreach ($parts as $part) {
        if ($part === '.') {
            continue;
        }
        if ($part === '..') {
            array_pop($absolutes);
        } else {
            $absolutes[] = $part;
        }
    }

    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        return implode('/', $absolutes);
    } else {
        return '/' . implode('/', $absolutes);
    }
}

function to_url($url): void
{
    echo <<<EOL
<script>
    window.location.href= '$url'
</script>
EOL;
}

function get_base_url(): string
{
    $protocol = 'http://';
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $protocol = 'https://';
    }

    $host = $_SERVER['HTTP_HOST'];

    return $protocol . $host;
}

function generate_qr_code(String $value, String $identifier): string
{
    include_once ('phpqrcode/qrlib.php');

    $filename = generateRandomString(8, $identifier) . '.png';
    $qr_code_filepath = dirname(__FILE__) . '/storage/qr_codes/' . $filename;
    QRcode::png($value, $qr_code_filepath);
    return extract_destination_path($qr_code_filepath);
}

function handle_file_upload($file, $filename) {
    $tmp_name = $file['tmp_name'];
    $destination_path = dirname(__FILE__) .  '/storage/' . $filename;

    move_uploaded_file($tmp_name, resolvePath($destination_path));
    return extract_destination_path($destination_path);
}

function get_user_type($user_id = null) {
    global $conn;

    // if userid is null, get it from session
    if ($user_id === null) {
        $user_id = $_SESSION['user_id'];
    }

    // if userid still null, return null
    if ($user_id === null) {
        return null;
    }

    $select = 'SELECT user_type FROM users WHERE id = ?';
    $stmt = mysqli_prepare($conn, $select);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $user_type);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return $user_type;
}

function check_get_id() {
    if (!isset($_GET['id'])) {
        to_url('users.php');
    }
}

function qr_base64($value): ?string
{
    include_once ('phpqrcode/phpqrcode.php');

    $enc = QRencode::factory(0, 3, 4);
    return $enc->encodePNGGiveB64($value);
}
