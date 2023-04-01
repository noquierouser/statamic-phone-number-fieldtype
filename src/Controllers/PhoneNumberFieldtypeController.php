<?php

namespace Kadegray\StatamicPhoneNumberFieldtype\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;
use Sokil\IsoCodes\IsoCodesFactory;

class PhoneNumberFieldtypeController extends BaseController
{
    public function getCountries($lang)
    {
        $locale = App::getLocale();
        putenv("LANGUAGE=$locale.UTF-8");
        putenv("LC_ALL=$locale.UTF-8");
        setlocale(LC_ALL, "$locale.UTF-8");

        $countries = [];
        $isoCodes = new IsoCodesFactory();
        foreach ($isoCodes->getCountries() as $country) {
            $countries[strtolower($country->getAlpha2())] = $country->getLocalName();
        }

        return $countries;
    }
}
