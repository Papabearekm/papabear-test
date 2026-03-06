<?php

namespace App\Livewire\Filter;

use App\Models\Filter;
use Brian2694\Toastr\Facades\Toastr;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Index extends Component
{
    public $filters, $filter_id = '';

    #[Rule('required')]
    public $name;

    public function mount()
    {
        $this->filters = Filter::get();
    }

    public function submit()
    {
        $this->validate();

        if ($this->filter_id) {
            $filter = Filter::find($this->filter_id);

            $filter->update([
                'name' => $this->name,
            ]);

            $message = 'Filter Updated!';
        } else {
            Filter::create([
                'name' => $this->name,
            ]);

            $message = 'Filter Added!';
        }

        $this->reset_fields();

        Toastr::success($message, 'Success');
        return redirect()->route('filters');
    }

    public function edit($id)
    {
        $filter = Filter::find($id);
        $this->filter_id = $id;
        $this->name = $filter->name;
    }

    public function delete($id)
    {
        $this->filter_id = $id;
    }

    public function destroy()
    {
        $filter = Filter::find($this->filter_id);
        $filter->delete();

        $this->reset_fields();
        
        Toastr::success('Filter Deleted', 'Success');
        return redirect()->route('filters');
    }

    public function reset_fields()
    {
        $this->filter_id = '';
        $this->name = '';
    }

    public function render()
    {
        return view('livewire.filter.index')->extends('layouts.master');
    }
}
