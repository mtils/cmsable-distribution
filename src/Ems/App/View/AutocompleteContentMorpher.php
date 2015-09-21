<?php


namespace Ems\App\View;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Versatile\Search\Contracts\Search;
use Versatile\View\Contracts\ModelPresenter;


class AutocompleteContentMorpher
{

    protected $request;

    protected $scaffold;

    public function __construct(Request $request, ModelPresenter $scaffold)
    {
        $this->request = $request;
        $this->scaffold = $scaffold;
    }

    public function morphIfQueried($response, $morpher)
    {
        if (!$search = $this->findSearch($response)) {
            return;
        }

        return $this->morphToAutocomplete($search, $response);
    }

    protected function morphToAutocomplete(Search $search, $response)
    {

        $keys = $this->scaffold->keys($search->modelClass(), 
ModelPresenter::VIEW_PREVIEW );

        $search->replaceKeys($keys) ;

        $result = $search->paginate([], 20);

        $this->addShortNames($result);

        $array = $result->toArray();

        $array['keys'] = $keys;

        $response->setContent($array);

    }

    protected function addShortNames($result)
    {
        foreach ($result as $model) {
            $model->setAttribute('short_name', $this->scaffold->shortName($model, ModelPresenter::VIEW_PREVIEW));
        }
    }

    protected function findSearch($response)
    {

        if ($this->request->input('_view') != 'autocomplete') {
            return;
        }

        if (!method_exists($response, 'getOriginalContent') ) {
            return;
        }

        if (!$view = $response->getOriginalContent()) {
            return;
        }

        if (!$view instanceof View) {
            return;
        }

        if ( !isset($view['search']) ) {
            return;
        }

        $search = $view['search'];

        if ($search instanceof Search) {
            return $search;
        }

    }
}