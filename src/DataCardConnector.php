<?php

namespace CreativeCard\DataCard;

use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;

class DataCardConnector
{
    use ListOperation;
    /**
     * Component that simply pulg into blade as static style 
     * 
     */
    private $components;

    /**
     * Laravel Resource if the custom api is providing
     */
    private $resourceClass;

    /**
     * With Card View is extended function
     * check the backpack card is enable view card or not
     * 
     * @override
     */
    protected function isEnableCardView()
    {
        return true;
    }

    /**
     * 
     * 
     */
    protected function withResource($resource)
    {
        $this->resourceClass = $resource;
    }

    /**
     * 
     * 
     */
    protected function withComponent($name)
    {
        $this->components = $name;
    }


    /**
     * The search function that is called by the data table.
     *
     * @return array JSON Array of cells in HTML form.
     */ 
    public function search()
    {

        $perPage = 3 * 4;

        $this->crud->hasAccessOrFail('list');

        // Pagination Length Of One page
        $this->crud->take($perPage);

        $this->crud->applyUnappliedFilters();

        $totalRows = $this->crud->model->count();
        $filteredRows = $this->crud->query->toBase()->getCountForPagination();
        $startIndex = 0;
        // if a search term was present
        if (request()->has('search')) {

            $search = request()->post('search');

            // filter the results accordingly
            // $this->crud->applySearchTerm(request()->post('search'));

            $this->crud->addClause('where', 'title', 'like', "%$search%");
            // recalculate the number of filtered rows
            $filteredRows = $this->crud->count();
        }

        // Starting New Request
        if (request()->post('page', 1) > 1)
            $this->crud->skip(((int) request()->post('page') - 1) * $perPage);


        $entries = $this->crud->getEntries();

        $isNotCollectionClass = !isset($this->collectionClass);

        return [
            'data' => $isNotCollectionClass ? $entries : $this->collectionClass::collection($entries),
            'filteredRows' => $filteredRows,
            'totalPage' => ceil($filteredRows / $perPage),
            'totalRows' => $totalRows,
        ];
    }
}
