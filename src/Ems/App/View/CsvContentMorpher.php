<?php


namespace Ems\App\View;

use Illuminate\Contracts\View\View;
use Versatile\Search\Contracts\Search;
use Versatile\View\Contracts\ModelPresenter;
use Versatile\View\Contracts\CollectionFactory;


class CsvContentMorpher
{

    protected $listing;

    protected $scaffold;

    public $delimiter = ';';

    public $enclosure = '"';

    public $escapeChar = '\\';

    public function __construct(CollectionFactory $listing, ModelPresenter $scaffold)
    {
        $this->listing = $listing;
        $this->scaffold = $scaffold;
    }

    public function morphIfQueried($response, $morpher)
    {
        if (!$search = $this->findSearch($response)) {
            return;
        }

        return $this->morphToCsv($search, $response);
    }

    protected function morphToCsv(Search $search, $response)
    {

        $keys = $this->scaffold->keys($search->modelClass(), 'download' );

        $search->replaceKeys($keys) ;

        $collection = $this->listing->create($search);

        $result = $search->get();

        // Open a memory "file" for read/write...
        $fp = fopen('php://temp', 'r+');

        $cols = [];
        foreach ($collection->columns as $col) {
            $cols[] = $col->title;
        }

        $written = fputcsv(
            $fp,
            $cols,
            $this->delimiter,
            $this->enclosure,
            $this->escapeChar
        );

        foreach ($collection as $row) {

            $new = [];

            foreach ($collection->columns as $col) {
                $new[] = $col->value;
            }

            $written = fputcsv(
                $fp,
                $new,
                $this->delimiter,
                $this->enclosure,
                $this->escapeChar
            );
        }

        rewind($fp);

        $csvString = fread($fp, 1048576);

        fclose($fp);

        $response->setContent($csvString);

        $response->header('Content-Type', 'text/csv');
        $response->header('Content-Disposition', 'attachment; filename=export.csv');

    }

    protected function addShortNames($result)
    {
        foreach ($result as $model) {
            $model->setAttribute('short_name', $this->scaffold->shortName($model, ModelPresenter::VIEW_PREVIEW));
        }
    }

    protected function findSearch($response)
    {

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