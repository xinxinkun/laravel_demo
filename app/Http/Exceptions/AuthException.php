<?php
/**
 * Created by PhpStorm.
 * User: eastown
 * Date: 2018/12/15
 * Time: 15:58
 */

namespace App\Http\Exceptions;

class AuthException extends BasicException
{
    const OTP = 1;

    const PERMISSION = 2;

    const IP_WHITELIST = 3;

    const IP_BLACKLIST = 4;

    const OTP_NOT_SET = 5;

    const PASSWORD_ERROR = 6;

    const EASY_PASSWORD = 7;

    const GROUP_PERMISSION = 8;

    const GROUP_ACTIVE_PERIOD = 9;

    const GROUP_MAX_CONFIRMED_DEPOSIT_ORDER_AMOUNT = 10;

    const GROUP_MAX_CANCELLED_WITHDRAWAL_ORDER_AMOUNT = 11;
}
