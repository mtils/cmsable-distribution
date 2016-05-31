<?php namespace Ems\App\Http\Forms\Fields;

use FormObject\Attributes;
use FormObject\Field;

class ImageDbField extends Field
{

    protected $inlineJsEnabled = false;

    public function enableInlineJs($enabled=true)
    {
        $this->inlineJsEnabled = $enabled;
        return $this;
    }

    public function isInlineJsEnabled()
    {
        return $this->inlineJsEnabled;
    }

    protected function updateAttributes(Attributes $attributes)
    {
        parent::updateAttributes($attributes);
        $attributes->set('type', 'hidden');
        $attributes->set('value', $this->value);
    }
}