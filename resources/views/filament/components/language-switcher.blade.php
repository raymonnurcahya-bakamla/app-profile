@php
    $currentLocale = app()->getLocale();
    $languages = [
        'id' => [
            'label' => 'Bahasa Indonesia',
            'flag' => '🇮🇩',
        ],
        'en' => [
            'label' => 'English',
            'flag' => '🇬🇧',
        ],
    ];
@endphp

<div class="flex items-center me-2">
    <x-filament::dropdown placement="bottom-end" teleport>
        <x-slot name="trigger">
            <button
                type="button"
                class="flex items-center gap-x-1.5 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-500/10 dark:text-gray-200 dark:hover:bg-white/10 transition ring-1 ring-gray-950/10 dark:ring-white/20"
                title="{{ __('Ganti Bahasa / Switch Language') }}"
            >
                <x-filament::icon
                    icon="heroicon-m-language"
                    class="h-4 w-4 text-gray-500 dark:text-gray-400"
                />
                <span class="font-bold uppercase tracking-wider">{{ $currentLocale }}</span>
                <x-filament::icon
                    icon="heroicon-m-chevron-down"
                    class="h-3 w-3 text-gray-400 dark:text-gray-500"
                />
            </button>
        </x-slot>

        <x-filament::dropdown.list>
            @foreach ($languages as $code => $data)
                <x-filament::dropdown.list.item
                    :href="route('switch-language', ['locale' => $code])"
                    tag="a"
                    :spa-mode="false"
                    :badge="$currentLocale === $code ? ($code === 'id' ? 'Aktif' : 'Active') : null"
                    :badge-color="$currentLocale === $code ? 'primary' : null"
                >
                    <span class="me-1.5">{{ $data['flag'] }}</span>
                    <span>{{ $data['label'] }}</span>
                </x-filament::dropdown.list.item>
            @endforeach
        </x-filament::dropdown.list>
    </x-filament::dropdown>
</div>

