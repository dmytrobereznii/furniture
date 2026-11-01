@extends('admin.home')

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route("home") }}">Головна</a></li>
            <li class="breadcrumb-item active">{{ $categoryName }}</li>
        </ol>
    </nav>
@endsection

@section('main')
    <div class="card">
        <div class="card-body">
            <a href="{{ route('admin.categories.projects.create', ['categoryKey' => $categoryKey]) }}" type="button" class="btn btn-primary mb-3">Додати проєкт</a>
            <a href="{{ route('admin.categories.meta.edit', ['categoryKey' => $categoryKey]) }}" type="button" class="btn btn-secondary mb-3">Редагувати метадані категорії</a>
            <br>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Назва</th>
                        <th scope="col">Опис</th>
                        <th scope="col"></th>
                        <th scope="col">Дії</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($projects as $project)
                        <tr>
                            <th scope="row">{{ $project->id }}</th>
                            <td>{!! $project->name ?? "<i>Немає</i>" !!}</td>
                            <td>{{ strip_tags($project->description ? mb_substr($project->description, 0, 50) . '...' : "<i>Немає</i>") }}</td>
                            <td>
                                <img src="{{ $project->getMedia('primaryImage')[0]->getUrl() }}" width="120" alt="Фото">
                            </td>
                            <td>
                                <a href="{{ route('admin.categories.projects.edit', ['categoryKey' => $categoryKey, 'id' => $project->id]) }}" type="button" class="btn btn-success mb-3">Редагувати</a>
                                <a href="{{ route('admin.categories.projects.delete', ['categoryKey' => $categoryKey, 'id' => $project->id]) }}"
                                   type="button"
                                   class="btn btn-danger mb-3"
                                   onclick="return confirm('Ви впевнені, що хочете видалити проєкт?');">
                                    Видалити
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
