<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

function fetchFilteredRecords($model, Request $request, array $filterableColumns, string $view, array $relations = [], array $withSum = [])
{
    try {

         $query = $model::query()
            ->when(!empty($relations), fn($q) => $q->with($relations))
            ->when(!empty($withSum), fn($q) => $q->tap(fn($q) => collect($withSum)->each(fn($col, $rel) => $q->withSum($rel, $col))));

        $query = applyFilters($query, $request, $filterableColumns);                            /** Apply search and sorting filters */ 

        $records = $query->paginate(10);                                                        /** Paginate results */ 

    } catch (\Exception $e) {
        
        $requestID = generate_snowflake_id();           /** Unique log id to indetify request flow */
        logger()
            ->error(
                $requestID.'-> Failed to fetch records', [
                    'error' =>  $e->getMessage()
                ]
            );
        return back()->with('error', 'An error occurred while fetching records.');
    }

    return view($view, compact('records'));
}

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
