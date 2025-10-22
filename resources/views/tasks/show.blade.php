@extends('layouts.app')
@section('content')
        <div class="grid col-span-full">
            <h2 class="mb-5">Просмотр задачи: Доработать команду подготовки БД        <a href="{{ route('tasks.edit', [$task->id])}}">&#9881;</a>
            </h2>
                <p><span class="font-black">Имя:</span> {{ $task->name }}</p>
                <p><span class="font-black">Статус:</span> {{ $taskStatus }}</p>
                <p><span class="font-black">Описание:</span>{{ $task->description }}</p>
                <p><span class="font-black">Метки:</span></p>
        </div>

@endsection
