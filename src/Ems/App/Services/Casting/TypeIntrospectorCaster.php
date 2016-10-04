<?php


namespace Ems\App\Services\Casting;

use XType\Casting\Contracts\InputCaster;
use Versatile\Introspection\Contracts\TypeIntrospector;
use Cmsable\Resource\Contracts\Mapper;
use XType\TemporalType;
use Illuminate\Translation\Translator;
use DateTime;

class TypeIntrospectorCaster
{

    /**
    * @var \Versatile\Introspection\Contracts\TypeIntrospector
    **/
    protected $types;

    /**
     * @var \Cmsable\Resource\Contracts\Mapper
     **/
    protected $mapper;

    /**
     * @var \Illuminate\Translation\Translator
     **/
    protected $lang;

    /**
     * @param \Versatile\Introspection\Contracts\TypeIntrospector $types
     * @param \Illuminate\Translation\Translator $lang
     **/
    public function __construct(TypeIntrospector $types, Mapper $mapper, Translator $lang)
    {
        $this->types = $types;
        $this->mapper = $mapper;
        $this->lang = $lang;
    }

    /**
     * {@inheritdoc}
     *
     * @param array $input
     * @param array $metadata (optional)
     * @return array
     **/
    public function cast(array $input, array $metadata=[], $resource=null)
    {

        if (!$resource) {
            return $input;
        }

        if (!$class = $this->mapper->modelClass($resource)) {
            return $input;
        }

        $casted = [];

        foreach ($input as $key=>$value) {
            $casted[$key] = $this->castByXType($class, $key, $value);
        }

        return $casted;

    }

    public function format($class, array $input)
    {

        $casted = [];

        foreach ($input as $key=>$value) {
            $casted[$key] = $this->formatByXType($class, $key, $value);
        }

        return $casted;
    }

    /**
     * Use it in a chain
     *
     * @param array $input
     * @param array $metadata (optional)
     * @return array
     **/
    public function __invoke(array $input, array $metadata=[], $resource=null)
    {
        return $this->cast($input, $metadata, $resource);
    }

    protected function castByXType($class, $key, $value) {

        if (is_array($value)) {
            return $value;
        }

        if (!$type = $this->types->keyType($class, $key)) {
            return $value;
        }

        if (!$type instanceof TemporalType) {
            return $value;
        }

        // A very strict check on dates vs. times
        if ($this->looksLikeADate($key, $value)) {

            if($date = DateTime::createFromFormat($this->lang->get('ems::base.date-format'), $value)) {
                $date->setTime(0,0,0);
                return $date;
            }
            return $date;
        }


        return DateTime::createFromFormat($this->lang->get('ems::base.datetime-format'), $value);

    }

    protected function formatByXType($class, $key, $value) {

        if (is_array($value)) {
            return $value;
        }

        if (!$value instanceof DateTime) {
            return $value;
        }

        
        if ($this->looksLikeADate($key, $value)) {
            return $value->format($this->lang->get('ems::base.date-format'));
        }


        return $value->format($this->lang->get('ems::base.datetime-format'));

    }

    protected function looksLikeADate($key, $value) {
        // A very strict check on dates vs. times
        return !ends_with($key, '_at');
    }

}
