<?php namespace Ems\App\Http\Forms;

use Ems\App\Helpers\ProvidesTexts;


trait ProvidesFormTexts
{

    use ProvidesTexts;

    protected $_translationKey;

    protected function actionTitle($key, array $replace = array(), $locale = null)
    {

        $directKey = $this->transKey('form') . ".actions.$key";

        if ($this->translator()->has($directKey)) {
            return $this->trans($directKey, $replace, $locale);
        }

        $globalKey = $this->baseTransKey('form') . ".base.$key";

        return $this->trans($globalKey, $replace, $locale);
    }

    protected function fieldTitle($key, array $replace = array(), $locale = null)
    {
        $transKey = $this->transKey('form');
        return $this->trans("$transKey.$key", $replace, $locale);
    }

    protected function confirmedName($key, array $replace = array(), $locale = null)
    {
        $transKey = $this->transKey('form');
        $confirmKey = $this->baseTransKey('form') . ".base.field_confirmed";

        $fieldName = $this->fieldTitle(str_replace('_confirmation','',$key), $replace, $locale);

        return $this->trans($confirmKey, ['attribute'=>$fieldName], $locale);
    }

    protected function transKey($root)
    {
        if ($this->_translationKey) {
            return $this->_translationKey;
        }

        if ($root != 'form') {
            return parent::transKey($root);
        }

        $formName = $this->getName();

        if (ends_with($formName,'-form')) {
            $formName = substr($formName, 0, strlen($formName)-5);
        }

        $this->_translationKey = $this->baseTransKey('form') . '.' . $formName;

        return $this->_translationKey;
    }


}