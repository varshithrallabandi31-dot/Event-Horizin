<?php
echo "<h1>PHP Mail Config Check</h1>";
echo "<p><strong>Loaded Configuration File (php.ini):</strong> " . php_ini_loaded_file() . "</p>";
echo "<p><strong>sendmail_path:</strong> " . ini_get('sendmail_path') . "</p>";
echo "<p><strong>SMTP:</strong> " . ini_get('SMTP') . "</p>";
echo "<p><strong>smtp_port:</strong> " . ini_get('smtp_port') . "</p>";
echo "<p><strong>sendmail_from:</strong> " . ini_get('sendmail_from') . "</p>";

if (strpos(ini_get('sendmail_path'), 'sendmail.exe') !== false) {
    echo "<p style='color: green;'>✅ sendmail_path seems correct.</p>";
} else {
    echo "<p style='color: red;'>❌ sendmail_path is NOT pointing to sendmail.exe. PHP will try to use internal SMTP.</p>";
}

if (!empty(ini_get('SMTP')) && ini_get('SMTP') !== 'localhost') {
    echo "<p style='color: orange;'>⚠️ Warning: SMTP is set to '" . ini_get('SMTP') . "'. On Windows, this can sometimes interfere if sendmail_path is not perfectly configured.</p>";
}
?>
