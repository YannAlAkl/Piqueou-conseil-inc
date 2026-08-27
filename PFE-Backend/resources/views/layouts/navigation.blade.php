@php
    $user = Auth::user();
    $isAdmin = $user && $user->hasRole('admin');
    $isAnalyst = $user && $user->hasRole('analyst');
@endphp

<nav class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="text-lg font-bold text-blue-700">
                    {{ config('app.name') }}
                </a>

                <div class="hidden sm:flex sm:items-center sm:space-x-6">
                    <a href="{{ route('dashboard') }}"
                       class="text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-blue-700' : 'text-gray-500 hover:text-gray-800' }}">
                        Tableau de bord
                    </a>

                    @if ($isAdmin)
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-gray-800">
                            Administration
                        </a>
                    @endif

                   @if($isAnalyst)
                    <a href="{{ route('analyst.questionnaire.index') }}"
                    class="text-sm font-medium {{ request()->routeIs('analyst.questionnaire.*') ? 'text-blue-700' : 'text-gray-500 hover:text-gray-800' }}">
                        Questionnaires à analyser
                    </a>
                @endif
                </div>
            </div>

            <div class="flex items-center gap-4 text-sm text-gray-600">
                <a href="{{ route('profile.edit') }}" class="hover:text-gray-900">{{ $user->name ?? '' }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-red-600 hover:underline">Déconnexion</button>
                </form>
            </div>

        </div>
    </div>
</nav>
