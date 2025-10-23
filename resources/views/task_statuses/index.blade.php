@extends('layouts.app')
@section('content')

    <div class="grid col-span-full">
        @include('flash::message')
        <h1 class="mb-5">{{ __('layout.task_statuses') }}</h1>
                <div>
                    @auth
                        <a href="{{ route('task_statuses.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">{{ __('layout.task_statuses_create') }}</a>
                    @endauth
                </div>

                <table class="mt-4">
                    <thead class="border-b-2 border-solid border-black text-left">
                    <tr>
                        <th>{{ __('layout.table_id') }}</th>
                        <th>{{ __('layout.table_name') }}</th>
                        <th>{{ __('layout.table_date_of_creation') }}</th>
                        @auth
                            <th>{{ __('layout.table_actions') }}</th>
                        @endauth
                    </tr>
                    </thead>
                    @foreach ($taskStatuses as $status)
                        <tr class="border-b border-dashed text-left">
                            <td>{{ $status->id }}</td>
                            <td>{{ $status->name }}</td>
                            <td>{{ $status->created_at->format('Y-m-d') }}</td>
                            @auth
                                <td>
                                    <a data-confirm="{{ __('layout.table_delete_question') }}"
                                       data-method="delete"
                                       class="text-red-600 hover:text-red-900"
                                       href="{{ route('task_statuses.destroy', $status->id) }}"
                                       rel="nofollow">{{ __('layout.table_delete') }}</a>
                                    <a class="text-blue-600 hover:text-blue-900"
                                       href="{{ route('task_statuses.edit', $status->id) }}">{{ __('layout.table_edit') }}</a>
                                </td>
                            @endauth
                        </tr>
                @endforeach
            </div>
    </section>
</div>
@endsection
@section('pagination')
    {{ $taskStatuses->links() }}
@endsection
