<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class StatusToggle extends Component
{
    public $model;
    public $detachRelations;                                                                        /** Dynamic relations to detach */ 
    public $actions;
    public $editRoute;
    public $deleteRoute;

    public function mount($model, $detachRelations = [], $actions=[], $editRoute, $deleteRoute)
    {
        $this->model = $model;
        $this->detachRelations = $detachRelations;
        $this->actions = $actions;
        $this->$editRoute = $editRoute;
        $this->$deleteRoute = $deleteRoute;
    }

    public function toggleStatus()
    {
        $requestID = generate_snowflake_id();                                                       /** Unique log ID for request flow */ 

        logger()->info("{$requestID} -> Requested to toggle status");

        try {
            DB::beginTransaction();

            $this->model->status = ($this->model->status === 'Active') ? 'Inactive' : 'Active';     /** Toggle status */
            $this->model->save();

            if ($this->model->status === 'Inactive' && !empty($this->detachRelations)) {
                foreach ($this->detachRelations as $relation) {
                    if (method_exists($this->model, $relation)) {
                        $relationInstance = $this->model->$relation();
            
                        // Check if the relation is many-to-many (BelongsToMany)
                        if ($relationInstance instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany) {
                            $relationInstance->detach(); // Detach for many-to-many relations
                        }
                        // Check if the relation is one-to-many (HasMany)
                        elseif ($relationInstance instanceof \Illuminate\Database\Eloquent\Relations\HasMany) {
                            $relationInstance->delete(); // Delete for one-to-many relations
                        }
                    }
                }
            }
            

            DB::commit();

            logger()->info("{$requestID} -> Status toggled to {$this->model->status}");

            $this->dispatch('statusUpdated', $this->model->getKey(), $this->model->status);


        } catch (\Exception $e) {
            DB::rollBack();

            logger()->error("{$requestID} -> Failed to update status", [
                'error' => $e->getMessage()
            ]);

            session()->flash('error', 'An error occurred while updating status.');
        }
    }

    public function render()
    {
        return view('livewire.status-toggle', ['model' => $this->model]);
    }
}
