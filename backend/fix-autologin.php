<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Str;
use App\Models\Website;

$w = Website::where('slug', 'online-store-co')->first();
if (!$w) {
    echo "Website not found\n";
    exit(1);
}

$token = Str::random(32);
$password = $w->wp_admin_password ?: 'password';
$sitePath = 'C:/xampp/htdocs/online-store-co';

$script = <<<PHPSCRIPT
<?php
\$token = isset(\$_GET['token']) ? \$_GET['token'] : '';
\$expected = '{$token}';
\$password = '{$password}';

if (empty(\$token) || !hash_equals(\$expected, \$token)) {
    http_response_code(403);
    die('Invalid token');
}

define('ABSPATH', __DIR__ . '/');
require_once ABSPATH . 'wp-load.php';

\$creds = array('user_login' => 'admin', 'user_password' => \$password, 'remember' => true);
\$user = wp_signon(\$creds, false);

if (is_wp_error(\$user)) { wp_die('Auto-login failed: ' . \$user->get_error_message()); }

wp_set_current_user(\$user->ID);
wp_set_auth_cookie(\$user->ID, true);

\$redirect = isset(\$_GET['redirect']) ? \$_GET['redirect'] : '';
if (!empty(\$redirect) && strpos(\$redirect, '/wp-admin') === 0) {
    wp_safe_redirect(site_url(\$redirect));
} else {
    wp_safe_redirect(admin_url());
}
exit;
PHPSCRIPT;

file_put_contents($sitePath . '/wp-auto-login.php', $script);
$w->update(['wp_auto_login_token' => encrypt($token)]);
echo "Done! Auto-login script created for online-store-co\n";
echo "Token: $token\n";