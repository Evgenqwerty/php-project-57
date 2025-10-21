@extends('layouts.app')
@section('content')
    <div class="grid col-span-full">
        <h1 class="mb-5">Создать задачу</h1>
        <form class="w-50" method="POST" action="{{ route('tasks.store') }}">
            @csrf
            <div class="flex flex-col">
                <div>
                    <label for="name">Имя</label>
                </div>
                <div class="mt-2">
                    <input class="rounded border-gray-300 w-1/3" type="text" name="name" id="name" value="{{ old('name') }}">
                </div>
                <div class="mt-2">
                    <label for="description">Описание</label>
                </div>
                <div>
                    <textarea class="rounded border-gray-300 w-1/3 h-32" name="description" id="description">{{ old('description') }}</textarea>
                </div>
                <div class="mt-2">
                    <label for="status_id">Статус</label>
                </div>
                <div>
                    <select class="rounded border-gray-300 w-1/3" name="status_id" id="status_id">
                        <option value selected="selected"></option>
                        @foreach ($taskStatuses->all() as $status)
                            <option value="{{ $status->id }}" {{ $status->id == old('status_id') ? 'selected' : '' }}>{{ $status->name }}</option>
                            @endforeach
                            </option>
                    </select>
                </div>

            </div>
            <div class="mt-2">
                <label for="status_id">Исполнитель</label>
            </div>
            <div>
                <select class="rounded border-gray-300 w-1/3" name="assigned_by_id" id="assigned_by_id">
                    <option value selected="selected"></option>
                    @foreach ($users->all() as $user)
                        <option value="{{ $user->id }}" {{ $user->id == old('assigned_by_id') ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                        </option>
                </select>
            </div>
            <div class="mt-2">
                <input type="hidden" name="_method" id="_method" value="POST">
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="text-rose-600">{{ $error }}</div>
                    @endforeach
                @endif
            </div>
            <div class="mt-2">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Создать</button>
            </div>
    </div>
    </form>
@endsection
