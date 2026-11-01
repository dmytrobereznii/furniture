@extends('admin.home')

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route("home") }}">Головна</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.categories.projects.index', ['categoryKey' => $categoryKey]) }}">{{ $categoryName }}</a></li>
            <li class="breadcrumb-item active">Проєкт №{{ $project->id }}</li>
            <li class="breadcrumb-item active" aria-current="page">Редагувати</li>
        </ol>
    </nav>
@endsection

@section('main')
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('admin.categories.projects.update', ['categoryKey' => $categoryKey, 'id' => $project->id]) }}" method="POST" enctype='multipart/form-data'>
                @csrf
                <input type="hidden" name="category" value="{{ $categoryKey }}">
                <input type="hidden" name="projectId" value="{{ $project->id }}">
                <h3>Інформація про проєкт</h3>
                <div class="form-group">
                    <label for="name">Назва проєкту</label>
                    <input type="text" name="name" class="form-control" id="name" aria-describedby="nameHelp" value="{{ $project->name }}">
                    <small id="nameHelp" class="form-text text-muted">Необов'язково</small>
                </div>
                <div class="form-group">
                    <label for="slug">Slug</label>
                    <input type="text" name="slug" class="form-control" id="slug" aria-describedby="slug" value="{{ $project->slug }}">
                    <small id="slug" class="form-text text-muted">Обов'язково</small>
                </div>
                <div class="form-group">
                    <label for="description">Опис</label>
                    <textarea name="description" class="form-control rich" id="description" rows="15" aria-describedby="descriptionHelp">
                        {!! $project->description !!}
                    </textarea>
                    <small id="descriptionHelp" class="form-text text-muted">Необов'язково</small>
                </div>
                <h3>Фотографії</h3>
                <div class="form-group">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                        <tr>
                            <td>
                                <b>Фонове зображення *</b>
                                <br>
                                <small id="nameHelp" class="form-text text-muted">Обов'язково</small>
                            </td>
                            <td>
                                <input name="backgroundImage" type="file" id="primaryFile" onchange="document.getElementById('image1').src = window.URL.createObjectURL(this.files[0])">
                            </td>
                            <td>
                                <img src="{{ $backgroundImage[0]->getUrl() }}" id="image1" width="300">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Головне зображення *</b>
                                <br>
                                <small id="nameHelp" class="form-text text-muted">Обов'язково</small>
                            </td>
                            <td>
                                <input name="primaryImage" type="file" id="primaryFile" onchange="document.getElementById('image2').src = window.URL.createObjectURL(this.files[0])">
                            </td>
                            <td>
                                <img src="{{ $primaryImage[0]->getUrl() }}" id="image2" width="300">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <b>Додаткові фото</b>
                                <br>
                                <small id="nameHelp" class="form-text text-muted">Необов'язково</small>
                            </td>
                        </tr>
                        @foreach(range(1, 10) as $image)
                            <tr>
                                <td>
                                    <b>#{{ $image }}</b>
                                </td>
                                <td>
                                    <input name="secondaryImages[image{{ $image }}]" type="file" onchange="document.getElementById('secondaryImages{{ $image }}').src = window.URL.createObjectURL(this.files[0])">
                                    <br>
                                    <br>
                                    @if(count($project->getMedia("image{$image}")) > 0)
                                        <a href="{{ route('admin.categories.projects.deleteImage', ['categoryKey' => $categoryKey, 'id' => $project->id, 'image' => "image{$image}"]) }}"
                                           type="button"
                                           class="btn btn-danger mb-3"
                                           onclick="return confirm('Ви впевнені, що хочете видалити зображення?');">
                                            Видалити
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <img src="{{ count($project->getMedia("image{$image}")) > 0 ? $project->getMedia("image{$image}")[0]->getUrl() : null }}" id="secondaryImages{{ $image }}" width="300">
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    </div>
                </div>
                <h3>Метадані</h3>
                <div class="form-group">
                    <label for="name">Заголовок сторінки</label>
                    <input name="meta_title" value="{{ $project->meta_title }}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="name">Ключові слова</label>
                    <textarea name="meta_keywords" rows="7" class="form-control">{{ $project->meta_keywords }}</textarea>
                </div>
                <div class="form-group">
                    <label for="name">Опис</label>
                    <textarea name="meta_description" rows="7" class="form-control">{{ $project->meta_description }}</textarea>
                </div>
                <hr>
                <button type="submit" class="btn btn-success">Зберегти</button>
            </form>
        </div>
    </div>
@endsection
