<?php namespace Ems\App\Cms\Plugins;

use Cmsable\Model\SiteTreeNodeInterface;
use Cmsable\Controller\SiteTree\Plugin\ConfigurablePlugin;
use Ems\App\Http\Forms\ProvidesFormTexts;
use FormObject\FieldList;
use FormObject\Field\TextField;
use FormObject\Field\Action;
use FormObject\Field\CheckboxField;
use FormObject\Field\BooleanRadioField;
use FormObject\Field\SelectOneField;
use FormObject\Validator\ValidatorInterface;
use FormObject\Field\SelectManyField;

class PasswordResetPlugin extends ConfigurablePlugin
{

    use ProvidesFormTexts;

    public function modifyFormFields(FieldList $fields, SiteTreeNodeInterface $page)
    {

        $emailFields = FieldList::create('resetmail', $this->fieldTitle('resetmail'));

        $fields->push($emailFields)->before('settings');

        $this->addEmailFields($emailFields);

        $resetPageFields = FieldList::create('resetpage', $this->fieldTitle('resetpage'));

        $fields->push($resetPageFields)->before('settings');

        $this->addResetPageFields($resetPageFields);

    }

    protected function addEmailFields(FieldList $emailFields)
    {
        $emailFields->push(
            TextField::create($this->fieldName('resetmail_subject'), $this->fieldTitle('resetmail_subject'))
        );

        $emailFields->push(
            TextField::create($this->fieldName('resetmail_body'), $this->fieldTitle('resetmail_body'))
                       ->setMultiLine(true)
                       ->addCssClass('html')
                       ->setDescription($this->fieldTitle('resetmail_body_description'))
        );
    }

    protected function addResetPageFields(FieldList $emailFields)
    {
        $emailFields->push(
            TextField::create($this->fieldName('resetpage_title'), $this->fieldTitle('resetpage_title'))
        );

        $emailFields->push(
            TextField::create($this->fieldName('resetpage_content'), $this->fieldTitle('resetpage_content'))
                       ->setMultiLine(true)
                       ->addCssClass('html')
                       ->setDescription($this->fieldTitle('resetpage_description'))
        );
    }

}