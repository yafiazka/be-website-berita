<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Category;

class CategoryCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup(): void
    {
        CRUD::setModel(Category::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/category');
        CRUD::setEntityNameStrings('kategori', 'kategori');
    }

    protected function setupListOperation(): void
    {
        CRUD::column('name')->label('Nama Kategori');
        CRUD::column('slug')->label('Slug');
        CRUD::column('parent_id')->type('select')->entity('parent')->attribute('name')->label('Induk Kategori');
        CRUD::column('created_at')->label('Dibuat Pada');
    }

    protected function setupCreateOperation(): void
    {
        CRUD::setValidation([
            'name' => 'required|min:2|max:255',
            'slug' => 'nullable|unique:categories,slug',
        ]);

        CRUD::field('name')->label('Nama Kategori');
        CRUD::field('slug')->label('Slug (opsional / otomatis)');
        CRUD::field('parent_id')->type('select')->entity('parent')->attribute('name')->label('Induk Kategori (opsional)');
        CRUD::field('description')->type('textarea')->label('Deskripsi');
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }
}
