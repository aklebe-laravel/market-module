<?php

namespace Modules\Market\app\Models;

use Modules\WebsiteBase\app\Models\NotificationConcern as WebsiteBaseNotificationConcern;

/**
 * @mixin IdeHelperNotificationConcern
 */
class NotificationConcern extends WebsiteBaseNotificationConcern
{

    public const string REASON_CODE_USER_ASSIGNED_TO_ACL_GROUP = "market_user_assigned_to_acl_group";
    public const string REASON_CODE_USER_ASSIGNED_TO_TRADER = "market_user_assigned_to_trader";
}