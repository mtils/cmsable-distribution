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
use Cmsable\Widgets\AbstractWidget;
use Files;
use URL;
use Lang;

/**
 * This is a very simple widget which shows an image
 **/
class ImageBoxWidget extends AbstractWidget
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
     * @return array
     **/
    public function rules()
    {
        return $this->rules;
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
     * @param \Cmsable\Widgets\Contracts\WidgetItem $item
     * @return string
     **/
    public function render(WidgetItem $item)
    {
        $data = $item->getData();

        $imageUrl = $this->getImageUrl($item);

        if (!$link = $this->renderLink($item, $imageUrl)) {
            return $this->renderPreview($item);
        }

        if (!isset($data['caption']) || !$data['caption']) {
            return $link . $this->renderPreview($item) . '</a>';
        }

        return "$link<figure>". $this->renderPreview($item) . "<figcaption>{$data['caption']}</figcaption></figure></a>";
    }

    /**
     * {@inheritdoc}
     *
     * @param \Cmsable\Widgets\Contracts\WidgetItem $item
     * @return string
     **/
    public function renderPreview(WidgetItem $item)
    {
        return $this->renderImageTag($this->getImageUrl($item));
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
                       ->setSrc([], new Extractor('id','menu_title'))
                       ->nullEntry(Lang::get($this->trKey('link-no-page','ems::')),'0');
        $form->push($siteTreeSelect);
        $form->fillByArray($item->getData());

        return (string)$form;
    }

    protected function renderLink(WidgetItem $item, $imageUrl)
    {
        $data = $item->getData();

        if (!$data['link']) {
            return $this->renderLightBoxLink($item, $imageUrl);
        }

        if (!is_numeric($data['link'])) {
            return $this->renderLightBoxLink($item, $imageUrl);
        }

        if (!$page = $this->siteTree->pageById($data['link'])) {
            return $this->renderLightBoxLink($item, $imageUrl);
        }

        return '<a href="' . URL::to($page) . '">';

    }

    protected function renderImageTag($imageUrl)
    {
        return "<img class=\"image-box\" src=\"$imageUrl\" />";
    }

    protected function getImageUrl(WidgetItem $item)
    {

        $data = $item->getData();

        if (!isset($data['image_id']) || !$data['image_id']) {
            return '/cmsable/img/no-img.png';
        }

        if($file = $this->fileDB->getById($data['image_id'])) {
            return $file->url;
        }

        return '/cmsable/img/no-img.png';
    }

    protected function renderLightBoxLink(WidgetItem $item, $imageUrl)
    {
        return '<a href="javascript: return false;" data-href="' . $imageUrl . '" data-lightbox="sidebar-images">';
    }

}