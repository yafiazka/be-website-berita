<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\User;

class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup(): void
    {
        CRUD::setModel(User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('pengguna', 'pengguna');
    }

    protected function setupListOperation(): void
    {
        CRUD::column('name')->label('Nama');
        CRUD::column('email')->label('Email');
        CRUD::column('roles')->type('select_multiple')->entity('roles')->attribute('name')->label('Roles');
        CRUD::column('is_active')->type('boolean')->label('Aktif');
        CRUD::column('created_at')->label('Terdaftar');
    }

    protected function setupCreateOperation(): void
    {
        CRUD::setValidation([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        CRUD::field('name')->label('Nama Lengkap');
        CRUD::field('email')->type('email')->label('Email');
        CRUD::field('password')->type('password')->label('Kata Sandi');
        CRUD::field('roles')->type('select_multiple')->entity('roles')->attribute('name')->pivot(true)->label('Peran (Role)');
        CRUD::field('is_active')->type('checkbox')->default(1)->label('Akun Aktif');
    }

    protected function setupUpdateOperation(): void
    {
        CRUD::setValidation([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . CRUD::getCurrentEntryId(),
            'password' => 'nullable|min:8',
        ]);

        CRUD::field('name')->label('Nama Lengkap');
        CRUD::field('email')->type('email')->label('Email');
        CRUD::field('password')->type('password')->label('Kata Sandi (biarkan kosong jika tidak diubah)');
        CRUD::field('roles')->type('select_multiple')->entity('roles')->attribute('name')->pivot(true)->label('Peran (Role)');
        CRUD::field('is_active')->type('checkbox')->label('Akun Aktif');
    }
}
