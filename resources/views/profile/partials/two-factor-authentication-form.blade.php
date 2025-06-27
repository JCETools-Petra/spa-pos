<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Two-Factor Authentication') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Manage your two-factor authentication settings.') }}
        </p>
    </header>

    <form method="post" action="{{ url('user/two-factor-authentication') }}" class="mt-6 space-y-6">
        @csrf
        @if(auth()->user()?->two_factor_secret)
            @method('delete')
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('Two-factor authentication is currently enabled.') }}
            </p>
            <x-primary-button>{{ __('Disable') }}</x-primary-button>
        @else
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('You have not enabled two-factor authentication.') }}
            </p>
            <x-primary-button>{{ __('Enable') }}</x-primary-button>
        @endif
    </form>
</section>
