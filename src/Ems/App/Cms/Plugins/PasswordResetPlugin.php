<?php namespace Ems\App\Cms\Plugins;

use Cmsable\Model\SiteTreeNodeInterface;
use Cmsable\Controller\SiteTree\Plugin\ConfigurablePlugin;
use FormObject\FieldList;
use FormObject\Field\TextField;
use FormObject\Field\Action;
use FormObject\Field\CheckboxField;
use FormObject\Field\BooleanRadioField;
use FormObject\Field\SelectOneField;
use FormObject\Validator\ValidatorInterface;
use FormObject\Field\SelectManyField;
use FormObject\Factory;

class PasswordResetPlugin extends ConfigurablePlugin
{

    public function modifyFormFields(FieldList $fields, SiteTreeNodeInterface $page)
    {

        $factory = new Factory;

        $emailFields = $this->fieldList('resetmail');

        $fields->push($emailFields)->before('settings');

        $this->addEmailFields($emailFields);

        $resetPageFields = $this->fieldList('resetpage');

        $fields->push($resetPageFields)->before('settings');

        $this->addResetPageFields($resetPageFields);

    }

    protected function addEmailFields(FieldList $emailFields)
    {
        $emailFields->push(
            $this->textField($this->fieldName('resetmail_subject'))
        );

        $emailFields->push(
            $this->htmlField($this->fieldName('resetmail_body'))
        );
    }

    protected function addResetPageFields(FieldList $emailFields)
    {
        $emailFields->push(
            $this->textField($this->fieldName('resetpage_title'))
        );

        $emailFields->push($this->htmlField($this->fieldName('resetpage_content')));
    }

}