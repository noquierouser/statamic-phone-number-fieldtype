<?php

namespace Kadegray\StatamicPhoneNumberFieldtype\FieldtypeFilters;

use Statamic\Extend\HasFields;

class PhoneNumberFieldtypeFilter
{
    use HasFields;

    protected $fieldtype;

    public function __construct($fieldtype)
    {
        $this->fieldtype = $fieldtype;
    }

    public function fieldItems()
    {
        return [
            'value' => [
                'type' => 'text',
            ]
        ];
    }

    public function apply($query, $handle, $values)
    {
        $searchValue = data_get($values, 'value');
        if ($searchValue) {
            $query->where($handle, 'like', "%$searchValue%");
        }
    }

    public function badge($values)
    {
        $searchValue = data_get($values, 'value');
        if (!$searchValue) {

            return;
        }

        $field = $this->fieldtype->field()->display();

        return $field . " contains $searchValue";
    }
}
