<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;

class ArticleCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup(): void
    {
        CRUD::setModel(Article::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/article');
        CRUD::setEntityNameStrings('artikel', 'artikel');
    }

    protected function setupListOperation(): void
    {
        CRUD::column('title')->label('Judul Artikel');
        CRUD::column('category_id')->type('select')->entity('category')->attribute('name')->label('Kategori');
        CRUD::column('author_id')->type('select')->entity('author')->attribute('name')->label('Penulis');
        CRUD::column('status')->type('enum')->label('Status');
        CRUD::column('is_breaking')->type('boolean')->label('Breaking News');
        CRUD::column('views_count')->label('Views');
        CRUD::column('published_at')->label('Publikasi');
    }

    protected function setupCreateOperation(): void
    {
        CRUD::setValidation([
            'title' => 'required|min:5|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required',
            'status' => 'required|in:draft,review,published,archived',
        ]);

        CRUD::field('title')->label('Judul Artikel');
        CRUD::field('slug')->label('Slug (opsional / otomatis)');
        CRUD::field('category_id')->type('select')->entity('category')->attribute('name')->label('Kategori');
        CRUD::field('author_id')->type('select')->entity('author')->attribute('name')->default(backpack_user()?->id)->label('Penulis');
        CRUD::field('thumbnail')->type('url')->label('URL Gambar Thumbnail');
        CRUD::field('excerpt')->type('textarea')->label('Ringkasan / Excerpt');
        CRUD::field('content')->type('textarea')->label('Konten Berita');
        CRUD::field('tags')->type('select_multiple')->entity('tags')->attribute('name')->pivot(true)->label('Tags');
        CRUD::field('status')->type('select_from_array')->options([
            'draft' => 'Draft',
            'review' => 'Menunggu Review',
            'published' => 'Dipublikasikan',
            'archived' => 'Diarsipkan',
        ])->default('draft')->label('Status');
        CRUD::field('is_breaking')->type('checkbox')->label('Tandai sebagai Breaking News');
        CRUD::field('published_at')->type('datetime')->label('Waktu Publikasi');
        CRUD::field('meta_title')->label('SEO Meta Title');
        CRUD::field('meta_description')->type('textarea')->label('SEO Meta Description');
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }
}
