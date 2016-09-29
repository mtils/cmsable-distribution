<?php


namespace Ems\App\Services\Validation;

use Illuminate\Translation\Translator;


class LokalizedRules
{

    /**
     * @var \Illuminate\Translation\Translator
     **/
    protected $lang;

    /**
     * @param \Illuminate\Translation\Translator $lang
     **/
    public function __construct(Translator $lang)
    {
        $this->lang = $lang;
    }

    public function validateLocalDate($attribute, $value, $parameters, $validator)
    {
        $dateFormat = $this->lang->get('ems::base.date-format');

        if (! is_string($value) && ! is_numeric($value)) {
            return false;
        }

        $parsed = date_parse_from_format($dateFormat, $value);

        return $parsed['error_count'] === 0 && $parsed['warning_count'] === 0;

    }

}
