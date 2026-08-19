<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Comment;

class CommentCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup(): void
    {
        CRUD::setModel(Comment::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/comment');
        CRUD::setEntityNameStrings('komentar', 'komentar');
    }

    protected function setupListOperation(): void
    {
        CRUD::column('name')->label('Pengirim');
        CRUD::column('email')->label('Email');
        CRUD::column('article_id')->type('select')->entity('article')->attribute('title')->label('Artikel');
        CRUD::column('content')->label('Isi Komentar');
        CRUD::column('status')->type('enum')->label('Status Moderasi');
        CRUD::column('created_at')->label('Waktu');
    }

    protected function setupUpdateOperation(): void
    {
        CRUD::field('status')->type('select_from_array')->options([
            'pending' => 'Pending',
            'approved' => 'Approved',
            'spam' => 'Spam',
        ])->label('Status Moderasi');
        CRUD::field('content')->type('textarea')->label('Isi Komentar');
    }
}
