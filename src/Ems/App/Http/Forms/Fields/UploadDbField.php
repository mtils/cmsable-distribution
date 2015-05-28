<?php namespace Ems\App\Http\Forms\Fields;

use FormObject\Attributes;
use FormObject\Field;

class UploadDbField extends Field
{
    protected function updateAttributes(Attributes $attributes)
    {
        parent::updateAttributes($attributes);
        $attributes->set('type', 'hidden');
        $attributes->set('value', $this->value);
    }
}