<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Tag;

class TagCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup(): void
    {
        CRUD::setModel(Tag::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/tag');
        CRUD::setEntityNameStrings('tag', 'tag');
    }

    protected function setupListOperation(): void
    {
        CRUD::column('name')->label('Nama Tag');
        CRUD::column('slug')->label('Slug');
        CRUD::column('created_at')->label('Dibuat Pada');
    }

    protected function setupCreateOperation(): void
    {
        CRUD::setValidation([
            'name' => 'required|min:2|max:255',
            'slug' => 'nullable|unique:tags,slug',
        ]);

        CRUD::field('name')->label('Nama Tag');
        CRUD::field('slug')->label('Slug (opsional / otomatis)');
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }
}
