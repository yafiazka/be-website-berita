<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Article;
use App\Services\ImageService;

class ArticleCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup(): void
    {
        CRUD::setModel(Article::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/article');
        CRUD::setEntityNameStrings('artikel', 'artikel');
    }

    // ==========================================
    // Override store/update untuk handle upload
    // ==========================================

    public function store()
    {
        $this->crud->hasAccessOrFail('create');
        $request = $this->crud->validateRequest();
        $this->crud->registerFieldEvents();

        $data = $this->prepareDataFromRequest($request);

        $item = Article::create($data);
        $this->data['entry'] = $this->crud->entry = $item;

        // Sync tags jika ada
        if (!empty($data['tags'])) {
            $item->tags()->sync($data['tags']);
        }

        \Alert::success(trans('backpack::crud.insert_success'))->flash();
        $this->crud->setSaveAction();
        return $this->crud->performSaveAction($item->getKey());
    }

    public function update()
    {
        $this->crud->hasAccessOrFail('update');
        $request = $this->crud->validateRequest();
        $this->crud->registerFieldEvents();

        $data = $this->prepareDataFromRequest($request);

        $id = $request->get($this->crud->model->getKeyName()) ?? $this->crud->getCurrentEntryId();
        $item = Article::findOrFail($id);
        $item->update($data);
        $this->data['entry'] = $this->crud->entry = $item;

        // Sync tags jika ada
        if (array_key_exists('tags', $data)) {
            $item->tags()->sync($data['tags'] ?? []);
        }

        \Alert::success(trans('backpack::crud.update_success'))->flash();
        $this->crud->setSaveAction();
        return $this->crud->performSaveAction($item->getKey());
    }

    /**
     * Proses request menjadi data array yang siap disimpan ke DB.
     * Menangani upload file thumbnail langsung via ImageService.
     */
    protected function prepareDataFromRequest($request): array
    {
        // Ambil semua field terdaftar kecuali thumbnail (kita handle manual)
        $fieldNames = collect($this->crud->getAllFieldNames())
            ->reject(fn($f) => in_array($f, ['thumbnail', 'thumbnail_file']))
            ->values()
            ->toArray();

        $data = $request->only($fieldNames);

        // Handle thumbnail: prioritas file upload, fallback ke URL teks
        $file = $request->file('thumbnail') ?? $request->file('thumbnail_file');
        if ($file && $file->isValid()) {
            $processed = ImageService::processAndStore($file, 'articles');
            if ($processed) {
                $data['thumbnail'] = $processed;
            }
        } elseif ($request->filled('thumbnail')) {
            $urlOrPath = $request->input('thumbnail');
            // Jika berupa URL atau path teks biasa, simpan langsung
            if (filter_var($urlOrPath, FILTER_VALIDATE_URL)) {
                $data['thumbnail'] = $urlOrPath;
            }
        }

        return $data;
    }

    // ==========================================
    // List Columns
    // ==========================================

    protected function setupListOperation(): void
    {
        CRUD::column('thumbnail_url')
            ->label('Thumbnail')
            ->type('custom_html')
            ->value(function ($entry) {
                if ($entry->thumbnail_url) {
                    return '<img src="' . e($entry->thumbnail_url) . '" style="width: 60px; height: 45px; object-fit: cover; border-radius: 4px;" alt="thumbnail" onerror="this.style.display=\'none\'" />';
                }
                return '<span class="text-secondary small">-</span>';
            });

        CRUD::column('title')->label('Judul Artikel');
        CRUD::column('category_id')->type('select')->entity('category')->attribute('name')->label('Kategori');
        CRUD::column('author_source')->label('Jurnalis / Penulis');
        CRUD::column('status')->type('enum')->label('Status');
        CRUD::column('is_featured')->type('boolean')->label('Featured');
        CRUD::column('is_breaking')->type('boolean')->label('Breaking');
        CRUD::column('views_count')->label('Views');
        CRUD::column('published_at')->label('Publikasi');
    }

    // ==========================================
    // Create / Update Fields
    // ==========================================

    protected function setupCreateOperation(): void
    {
        CRUD::setValidation([
            'title'       => 'required|min:3|max:255',
            'category_id' => 'required|exists:categories,id',
            'content'     => 'required',
            'status'      => 'required|in:draft,review,published,archived',
            'thumbnail'   => 'nullable',
        ]);

        CRUD::field('title')->label('Judul Artikel');
        CRUD::field('slug')->label('Slug (opsional / otomatis terbuat)');
        CRUD::field('category_id')->type('select')->entity('category')->attribute('name')->label('Kategori');
        CRUD::field('author_id')->type('select')->entity('author')->attribute('name')->default(backpack_user()?->id)->label('Akun Pengunggah (User ID)');
        CRUD::field('author_source')->type('text')->label('Nama Jurnalis / Penulis (Opsional)')->hint('Contoh: Budi Santoso (Koresponden IKN)');
        CRUD::field('source')->type('text')->label('Sumber Berita (Opsional)')->hint('Contoh: LKBN Antara, Reuters, Siaran Pers Kementerian');

        // Upload File Gambar (tidak pakai withFiles - kita handle manual via ImageService)
        CRUD::field([
            'name'       => 'thumbnail',
            'type'       => 'upload',
            'label'      => 'Upload File Gambar / Thumbnail',
            'upload'     => true,
            'hint'       => 'Pilih berkas gambar (JPG, PNG, WEBP, GIF, dll). File akan otomatis dikompresi oleh sistem.',
        ]);

        CRUD::field('video_url')->type('text')->label('URL Video Liputan (YouTube / Video Embed)')->hint('Contoh: https://www.youtube.com/watch?v=...');
        CRUD::field('excerpt')->type('textarea')->label('Ringkasan / Excerpt');
        CRUD::field('content')->type('textarea')->label('Konten Berita');
        CRUD::field('tags')->type('select_multiple')->entity('tags')->attribute('name')->pivot(true)->label('Tags');
        CRUD::field('status')->type('select_from_array')->options([
            'draft'    => 'Draft',
            'review'   => 'Menunggu Review',
            'published' => 'Dipublikasikan',
            'archived' => 'Diarsipkan',
        ])->default('draft')->label('Status');
        CRUD::field('is_featured')->type('checkbox')->label('Tandai sebagai Berita Pilihan (Featured)');
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
