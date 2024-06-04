<?php

use Random\RandomException;

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

/**
 * @throws RandomException
 */
function generateRandomString($length = 10): string {
    // Generate random bytes and encode them to hexadecimal
    $randomBytes = random_bytes($length);
    $string = bin2hex($randomBytes);

    // Return the substring in case the length needs to be exact
    return substr($string, 0, $length);
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
