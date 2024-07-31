<?php
$required_extensions = ['curl', 'gd', 'mysqli', 'openssl'];
foreach ($required_extensions as $extension) {
    if (extension_loaded($extension)) {
        echo "$extension extension is enabled.<br>";
    } else {
        echo "$extension extension is not enabled.<br>";
    }
}
?>