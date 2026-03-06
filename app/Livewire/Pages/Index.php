<?php

namespace App\Livewire\Pages;

use App\Models\Pages;
use Brian2694\Toastr\Facades\Toastr;
use Livewire\Component;

class Index extends Component
{
    public $pages, $content, $page_id;

    public function mount()
    {
        $this->pages = Pages::get();
    }

    public function edit($id)
    {
        $this->page_id = $id;
        $page = Pages::find($id);
        $this->content = $page->content;
    }

    public function reset_fields()
    {
        $this->content = '';
        $this->page_id = '';
    }

    public function submit()
    {
        $page = Pages::find($this->page_id);
        $page->update([
            'content' => $this->content
        ]);

        Toastr::success('Content Updated', 'Success');
        return redirect()->route('pages');
    }

    public function render()
    {
        return view('livewire.pages.index')->extends('layouts.master');
    }
}
