<?php

// Don't squawk.
!defined('BANTAM') ? exit() : null;

use \App\Utils\Misc\Sundial;

return array(
    // -------------------------------------------------------------------------
    // Bantam internal configuration settings.

    'bantam_session_max_idle_time' => Sundial::now()->minutes(10)->raw()->time(),
    'bantam_app_secret_key'        => '11111111112222222222333333333344',
    'bantam_bcrypt_cost'           => 10,
    'bantam_default_locale'        => 'en',
);
