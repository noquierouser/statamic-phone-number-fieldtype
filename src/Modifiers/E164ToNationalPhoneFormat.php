<?php

namespace Kadegray\StatamicPhoneNumberFieldtype\Modifiers;

use Statamic\Modifiers\Modifier;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;

class E164ToNationalPhoneFormat extends Modifier
{
    protected static $handle = 'e164_to_national';

    /**
     * Modify a value.
     *
     * @param mixed  $value    The value to be modified
     * @param array  $params   Any parameters used in the modifier
     * @param array  $context  Contextual values
     * @return mixed
     */
    public function index($e164PhoneNumber, $params, $context)
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        $parsedPhoneNumber = $phoneUtil->parse($e164PhoneNumber);
        $formattedPhoneNumber = $phoneUtil->format($parsedPhoneNumber, PhoneNumberFormat::NATIONAL);

        return $formattedPhoneNumber;
    }
}
