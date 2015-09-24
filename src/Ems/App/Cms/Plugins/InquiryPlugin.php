<?php namespace Ems\App\Cms\Plugins;

use Cmsable\Model\SiteTreeNodeInterface;
use Cmsable\Controller\SiteTree\Plugin\ConfigurablePlugin;
use FormObject\Form;
use FormObject\FieldList;
use FormObject\Field\TextField;
use FormObject\Field\Action;
use FormObject\Field\CheckboxField;
use FormObject\Field\BooleanRadioField;
use FormObject\Field\SelectOneField;
use FormObject\Field\SelectOneGroup;
use FormObject\Validator\ValidatorInterface;
use FormObject\Field\SelectManyField;
use FormObject\Factory;
use Lang;

class InquiryPlugin extends ConfigurablePlugin
{

    public function modifyFormFields(FieldList $fields, SiteTreeNodeInterface $page)
    {

        $recipientFields = $this->fieldList('recipients_tab');

        $fields->push($recipientFields)->before('settings');

        $oneOrMany = [
            'single' => Lang::get('ems::sitetree-plugins.inquiry.send_to_single.title'),
            'multiple' => Lang::get('ems::sitetree-plugins.inquiry.send_to_multiple.title')
        ];

        $oneOrManyField = SelectOneGroup::create(
            $this->fieldName('single_or_multiple'), ''
        )->setSrc($oneOrMany);

        $oneOrManyField->push(
            $this->textField($this->fieldName('single_recipient'))
        );

        $oneOrManyField->push(
            $this->createTopicsField()
        );

        $recipientFields->push($oneOrManyField);

        return;

        $emailFields = $this->fieldList('resetmail');

        $fields->push($emailFields)->before('settings');

        $this->addEmailFields($emailFields);

        $resetPageFields = $this->fieldList('resetpage');

        $fields->push($resetPageFields)->before('settings');

        $this->addResetPageFields($resetPageFields);

    }

    protected function createTopicsField(){

        $itemForm = new Form();

        $itemForm->push(
            $this->text('topic', Lang::get('ems::sitetree-plugins.inquiry.topic.title')),
            $this->text('recipient', Lang::get('ems::sitetree-plugins.inquiry.recipient.title'))
        );

        $itemForm->validationRules = [
            'topic'=>'required|min:2',
            'recipient'=>'required|email',
        ];

        $fieldsEdit = $this->editMany($this->fieldName('recipients'),'')
                           ->setItemForm($itemForm);

        $fieldsEdit->addCssClass('draggable')
                   ->addCssClass('removable')
                   ->addCssClass('addable');

        return $fieldsEdit;

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