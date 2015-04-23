<?php

// Don't squawk.
!defined('BANTAM') ? exit() : null;

use \App\Utils\Misc\Sundial;

return array(
    // -------------------------------------------------------------------------
    // Bantam internal configuration settings.

    'bantam_session_max_idle_time' => Sundial::now()->minutes(10)->raw()->time(),
    // Change your secret key to something unique and secure.
    'bantam_app_secret_key'        => 'Change me!',
    'bantam_bcrypt_cost'           => 10,
    'bantam_default_locale'        => 'en',
    'bantam_allowed_file_exts'     => 'jpg, jpeg, png, gif',
    'bantam_max_file_size'         => 2097152
);
