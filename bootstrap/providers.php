<?php

use Mews\Captcha\CaptchaServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,
    Approval\ApprovalServiceProvider::class,
    CaptchaServiceProvider::class,
];