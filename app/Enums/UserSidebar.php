<?php

namespace App\Enums;

enum UserSidebar: int
{
    case QUIZ = 1;
    case PARTICIPANT_USER = 2;
    case POLL = 3;
    case MANAGE_SUBSCRIPTION = 4;
    case QUIZ_SETTINGS = 5;
}
