@extends('layouts.app')
@section('content')
    <div class="grid col-span-full">
        <h2 class="mb-5">
            {{ __('layout.view_task') }}: {{ $task->name }}

            {{-- Иконка редактирования только для авторизованных --}}
            @auth
                <a href="{{ route('tasks.edit', $task) }}" class="text-gray-500 hover:text-gray-700">&#9881;</a>
            @endauth
        </h2>

        <p><span class="font-black">{{ __('layout.table_name') }}:</span> {{ $task->name }}</p>
        <p><span class="font-black">{{ __('layout.table_task_status') }}:</span> {{ $taskStatus }}</p>
        <p><span class="font-black">{{ __('layout.table_description') }}:</span>{{ $task->description }}</p>
        <p><span class="font-black">{{ __('layout.labels') }}:</span></p>

        <div>
            @foreach ($task->labels as $label)
                <div class="text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 bg-blue-200 text-blue-700 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    {{ $label->name }}
                </div>
            @endforeach
        </div>

        {{-- Кнопки действий --}}
        <div class="mt-4">
            <a href="{{ route('tasks.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('layout.back') }}
            </a>

            @auth
                {{-- Кнопка удаления только для создателя --}}
                @can('delete', $task)
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2"
                                onclick="return confirm('{{ __('layout.table_delete_question') }}')">
                            {{ __('layout.table_delete') }}
                        </button>
                    </form>
                @endcan
            @endauth
        </div>
    </div>
@endsection
