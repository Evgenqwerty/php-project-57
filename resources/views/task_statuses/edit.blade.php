<html lang="ru">

@include('layouts.head')

<body>
<div id="app">
    @include('layouts.header')
    <section class="bg-white dark:bg-gray-900">
        <div class="grid max-w-screen-xl px-4 pt-20 pb-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12 lg:pt-28">
            <div class="grid col-span-full">
                <h1 class="mb-5">Изменение статуса</h1>

                <form class="w-50" method="POST" action="/task_statuses/{{ $taskStatus->id }}"><input type="hidden" name="_token" value="vJsHuzHqLLfexNN2urYZ3dFBb7Fx4dZegBCO3Awf">
                    @csrf
                    <div class="flex flex-col">
                        <div>
                            <label for="name">Имя</label>
                        </div>
                        <div class="mt-2">
                            <input type="hidden" name="_method" id="_method" value="PATCH">
                            <input class="rounded border-gray-300 w-1/3" type="text" name="name" id="name" value="{{ $taskStatus->name }}">
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <div class="text-rose-600">Статус с таким именем уже существует</div>
                                @endforeach
                            @endif
                        </div>
                        <div class="mt-2">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Обновить</button>
                        </div>
                    </div>
                </form>
</body>
</html>
