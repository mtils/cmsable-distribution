<?php


namespace Ems\App\Widgets;

use Collection\Map\Extractor;
use FormObject\Form;
use Ems\App\Http\Forms\Fields\ImageDbField;
use Cmsable\Widgets\Contracts\Widget;
use Cmsable\Widgets\Contracts\WidgetItem;
use Cmsable\Widgets\Contracts\AreaRepository;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use FileDB\Model\FileDBModelInterface as FileDB;
use Ems\App\Http\Forms\Fields\NestedSelectField;
use Cmsable\Model\SiteTreeModelInterface;
use Files;


/**
 * This is a very simple widget which shows an image
 **/
class ImageBoxWidget implements Widget
{

    public static $typeId = 'cmsable.widgets.image-box';

    /**
     * @var \Symfony\Component\Translation\TranslatorInterface
     **/
    protected $lang;

    protected $rules = [
        'image_id' => 'numeric',
        'link'  => 'numeric',
        'caption' => 'min:3'
    ];

    protected $validationFactory;

    public function __construct(ValidationFactory $validationFactory, FileDB $fileDB,
                                SiteTreeModelInterface $siteTree)
    {
        $this->validationFactory = $validationFactory;
        $this->fileDB = $fileDB;
        $this->siteTree = $siteTree;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     **/
    public function getTypeId()
    {
        return static::$typeId;
    }

    /**
     * {@inheritdoc}
     *
     * @param array
     * @return bool
     * @throws \Illuminate\Contracts\Validation\ValidationException
     **/
    public function validate(array $data)
    {
        $validator = $this->validationFactory->make($data, $this->rules);
        if ($validator->passes()) {
            return true;
        }
        throw new ValidationException($validator);
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     **/
    public function defaultData()
    {
        return [ 'image_id' => null, 'link' => 0];
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     **/
    public function category()
    {
        return 'banners';
    }

    /**
     * {@inheritdoc}
     *
     * @return int
     **/
    public function getMaxColumnSpan()
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     *
     * @return int
     **/
    public function getMaxRowSpan()
    {
        return 2;
    }

    /**
     * {@inheritdoc}
     *
     * @return bool
     **/
    public function isEditable()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $pageTypeId
     * @param string $areaName
     * @return bool
     **/
    public function isAllowedOn($pageTypeId, $areaName=AreaRepository::CONTENT)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Cmsable\Widgets\Contracts\WidgetItem $item
     * @return string
     **/
    public function render(WidgetItem $item)
    {
        $data = $item->getData();

        if (!isset($data['image_id']) || !$data['image_id']) {
            $imageUrl = '/cmsable/img/no-img.png';
        } elseif($file = $this->fileDB->getById($data['image_id'])) {
            $imageUrl = $file->url;
        }

        return "<img class=\"image-box\" src=\"$imageUrl\" />";
    }

    /**
     * {@inheritdoc}
     *
     * @param \Cmsable\Widgets\Contracts\WidgetItem $item
     * @return string
     **/
    public function renderPreview(WidgetItem $item)
    {
        return $this->render($item);
    }

    /**
     * {@inheritdoc}
     *
     * @param \Cmsable\Widgets\Contracts\WidgetItem $item
     * @return string
     **/
    public function renderForm(WidgetItem $item, $params=[])
    {
        $form = Form::create('image-box');

        $imageField = ImageDbField::create('image_id')->enableInlineJs();
        $form->push($imageField);

        $form->push(Form::text('caption'));

        $siteTreeSelect = NestedSelectField::create('link');
        $siteTreeSelect->setModel($this->siteTree)
                       ->setSrc([], new Extractor('id','menu_title'));
        $form->push($siteTreeSelect);
        $form->fillByArray($item->getData());

        $imageField->setAttribute('onlick','console.log(9)');
        
        return (string)$form;
    }

    protected function trKey($key, $namespace='')
    {
        return $namespace . 'widgets.' . str_replace('.','/',$this->getTypeId()) . ".$key";
    }
}