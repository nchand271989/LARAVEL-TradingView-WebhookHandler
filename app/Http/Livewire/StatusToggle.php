<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class StatusToggle extends Component
{
    public $model;
    public $detachRelations;
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
        
        try {
        
            DB::beginTransaction();
            $this->model->status = ($this->model->status === 'Active') ? 'Inactive' : 'Active';
            $this->model->save();
            if ($this->model->status === 'Inactive' && !empty($this->detachRelations)) {
                foreach ($this->detachRelations as $relation) {
                    if (method_exists($this->model, $relation)) {
                        $relationInstance = $this->model->$relation();
                        if ($relationInstance instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany) {
                            $relationInstance->detach();
                        }
                        elseif ($relationInstance instanceof \Illuminate\Database\Eloquent\Relations\HasMany) {
                            $relationInstance->delete();
                        }
                    }
                }
            }
            DB::commit();
            $this->dispatch('statusUpdated', $this->model->getKey(), $this->model->status);


        } catch (\Exception $e) {

            DB::rollBack();
            $requestID = generate_snowflake_id();           /** Unique log id to indetify request flow */
            logger()
                ->error(
                    $requestID.'-> Failed to update status', [
                        'error' =>  $e->getMessage()
                    ]
                );
            session()->flash('error', 'An error occurred while updating status.');
        }
    }

    public function render()
    {
        return view('livewire.status-toggle', ['model' => $this->model]);
    }
}
