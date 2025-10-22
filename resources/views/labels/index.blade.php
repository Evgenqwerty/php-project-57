@extends('layouts.app')
@section('content')

    <div class="grid col-span-full">
        @include('flash::message')
        <h1 class="mb-5">Метки</h1>
        <div>
            @auth
                <a href="{{ route('labels.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Создать метку
                </a>
            @endauth
        </div>
        <table class="mt-4">
            <thead class="border-b-2 border-solid border-black text-left">
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Описание</th>
                <th>Дата создания</th>
                @auth
                    <th>Действия</th>
                @endauth
            </tr>
            </thead>
            @foreach ($labels as $label)
                <tr class="border-b border-dashed text-left">
                    <td>{{ $label->id }}</td>
                    <td>{{ $label->name }}</td>
                    <td>{{ $label->description }}</td>
                    <td>{{ $label->created_at->format('Y-m-d') }}</td>
                    @auth
                        <td>
                            <a data-confirm="Вы уверены?"
                               data-method="delete"
                               class="text-red-600 hover:text-red-900"
                               href="{{ route('labels.destroy', $label->id) }}"
                               rel="nofollow">
                                Удалить                        </a>
                            <a class="text-blue-600 hover:text-blue-900" href="{{ route('labels.edit', $label->id ) }}">
                                Изменить                        </a>
                        </td>
                    @endauth
                </tr>
        @endforeach
    </div>
    </section>
    </div>
    @endsection
@section('pagination')
    {{ $labels->links() }}
@endsection
