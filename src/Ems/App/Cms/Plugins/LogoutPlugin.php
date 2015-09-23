<?php namespace Ems\App\Cms\Plugins;

use Cmsable\Model\SiteTreeNodeInterface;
use Cmsable\Controller\SiteTree\Plugin\Plugin;
use FormObject\FieldList;
use FormObject\Field\TextField;
use FormObject\Field\Action;
use FormObject\Field\CheckboxField;
use FormObject\Field\BooleanRadioField;
use FormObject\Field\SelectOneField;
use FormObject\Validator\ValidatorInterface;
use FormObject\Field\SelectManyField;
use FormObject\Factory;

class LogoutPlugin extends Plugin
{

    public function modifyFormFields(FieldList $fields, SiteTreeNodeInterface $page)
    {
        $fields('main')->offsetUnset('content');
    }

}