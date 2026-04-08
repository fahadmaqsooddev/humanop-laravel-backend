<?php

namespace App\Enums\UserActions;

enum UserActions: string
{
    case NEW_DAILY_TIP = 'New Daily Tip';
    case MESSAGE_SENT = 'Message Sent';
    case ACCEPT_GROUP_REQUEST = 'Accept Group Request';
    case REJECT_GROUP_REQUEST = 'Reject Group Request';
    case REMOVE_COMPANY = 'Remove Company';
    case CONNECTION_REQUEST_SENT = 'Connection Request Sent';
    case CONNECTION_REMOVED = 'Connection Removed';
    case CONNECTION_ACCEPTED = 'Connection Accepted';
    case FOLLOWED = 'Followed';
    case UNFOLLOWED = 'Unfollowed';
    case GROUP_REQUEST_SENT = 'Group Request Sent';
    case ASSESSMENT_COMPLETED = 'Assessment Completed';
    case ACTION_PLAN = 'Action Plan';
}