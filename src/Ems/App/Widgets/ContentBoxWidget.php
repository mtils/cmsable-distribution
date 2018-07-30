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
use Illuminate\Translation\Translator;
use FileDB\Model\FileDBModelInterface as FileDB;
use Ems\App\Http\Forms\Fields\NestedSelectField;
use Cmsable\Model\SiteTreeModelInterface;
use Files;
use Cmsable\Widgets\AbstractWidget;


/**
 * This is a very simple widget which shows one Sentence in a box
 **/
class ContentBoxWidget extends AbstractWidget
{

    public static $typeId = 'cmsable.widgets.content-box';

    /**
     * @var Translator
     **/
    protected $lang;

    protected $rules = [
        'content' => 'required|min:2',
        'css_class' => ''
    ];

    protected $cssClasses = [];

    public function __construct(Translator $translator,
                                SiteTreeModelInterface $siteTree)
    {
        $this->lang = $translator;
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
        return [ 'content' => $this->lang->get($this->trKey('default-content','ems::')) ];
    }

    /**
     * {@inheritdoc}
     *
     * @param \Cmsable\Widgets\Contracts\WidgetItem $item
     **/
    public function configure(WidgetItem $item) {
        $data = $item->getData();
        if (!isset($data['css_class']) || !$data['css_class']) {
            return;
        }
        $item->cssClasses()->append($data['css_class']);
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
        if (!isset($data['content'])) {
            return '';
        }
        return $data['content'];
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
        $form = Form::create('content-box');

        if ($this->cssClasses) {
            $form->push(Form::selectOne('css_class')->setSrc($this->cssClasses));
        }

        $form->push(Form::html('content')->enableInlineJs());

        $form->fillByArray($item->getData());
        return (string)$form;
    }

    public function getCssClasses()
    {
        return $this->cssClasses;
    }

    public function setCssClasses(array $classes)
    {
        $this->cssClasses = $classes;
        return $this;
    }

}