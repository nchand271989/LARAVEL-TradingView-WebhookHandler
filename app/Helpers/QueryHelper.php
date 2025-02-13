<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/**
 * Fetch and filter records with optional eager loading and pagination.
 *
 * @param string $model The model class to query.
 * @param Request $request The current request instance.
 * @param array $filterableColumns Columns that can be filtered.
 * @param string $view The view to return.
 * @param array $relations Optional relationships to eager load.
 * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
 */
function fetchFilteredRecords($model, Request $request, array $filterableColumns, string $view, array $relations = [], array $withSum = [])
{
    try {

         $query = $model::query()
            ->when(!empty($relations), fn($q) => $q->with($relations))
            ->when(!empty($withSum), fn($q) => $q->tap(fn($q) => collect($withSum)->each(fn($col, $rel) => $q->withSum($rel, $col))));

        // $query = !empty($relations) ? $model::with($relations) : $model::query();               /** Initialize query with optional eager loading */ 

        $query = applyFilters($query, $request, $filterableColumns);                            /** Apply search and sorting filters */ 

        $records = $query->paginate(10);                                                        /** Paginate results */ 

    } catch (\Exception $e) {
        
        logger()->error("Failed to fetch records", ['error' => $e->getMessage()]);              /** Logging -> error to fetch records */

        return back()->with('error', 'An error occurred while fetching records.');
    }

    return view($view, compact('records'));
}


/**
 * Apply search and sorting filters to a query.
 *
 * @param Builder $query
 * @param Request $request
 * @param array $sortableFields
 * @param string $defaultSortBy
 * @return Builder
 */
function applyFilters(Builder $query, Request $request, array $sortableFields, string $defaultSortBy = 'created_at'): Builder
{
    /** Apply search filter */ 
    if ($request->has('search')) {
        $query->where(function ($query) use ($request, $sortableFields) {
            foreach ($sortableFields as $field) {
                $query->orWhere($field, 'like', "%{$request->search}%");
            }
        });
    }

    /** Apply sorting if valid parameters are provided */ 
    if ($request->has('sortBy') && in_array($request->sortBy, $sortableFields)) {
        $query->orderBy($request->sortBy, $request->sortOrder === 'desc' ? 'desc' : 'asc');
    } else {
        $query->orderBy($defaultSortBy, 'desc');
    }

    return $query;
}
