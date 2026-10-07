<x-app-layout>

    <x-slot name="header">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    @yield('title', 'Administration')
                </h2>
                @hasSection('subtitle')
                    <p class="text-sm text-gray-500 mt-1">@yield('subtitle')</p>
                @endif
            </div>
            @hasSection('actions')
                <div>@yield('actions')</div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

        </div>
    </div>

</x-app-layout>
