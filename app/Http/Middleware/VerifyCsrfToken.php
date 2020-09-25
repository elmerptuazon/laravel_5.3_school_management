<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        // 'shows/*',
        'api/tattendance/post',
        'api/assignment/post',
        'api/assignment/e',
        'api/assignment/d',
        'api/test/e',
        'api/test/d',
        'api/as/e',
        'api/as/e',
        'api/ho/e',
        'api/ho/d',
        'api/mchat/post',
        '/post/device/*',
        'api/notificationsView/update',
        '/api/mobile/config/setting/grading_teacher_edit',
        '/api/mobile/config/setting/reportcard_view_teacher',
        '/api/config/fetch/data',
        '/api/mobile/config/setting/reportcard_view_parent',
        '/api/mobile/admin/quarter/select/first/period',
        '/api/mobile/admin/quarter/select/second/period',
        '/api/mobile/admin/quarter/select/third/period',
        '/api/mobile/admin/quarter/select/fourth/period',
        '/api/mobile/admin/quarter/select/finalgrade/period',
        'api/admin/mobile/notif/send',
        'api/admin/notif/test',
        '/api/post/activity',
        '/api/activity/teachers',
        '/api/activity/parents',
        '/activity_user',
    ];
}
