<!-- Language Switcher Component -->
<div class="relative inline-block text-left" x-data="{ open: false }" @click.away="open = false">
    <button @click="open = !open"
            class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
        <i class="fas fa-globe"></i>
        <span>{{ strtoupper(app()->getLocale()) }}</span>
        <i class="fas fa-chevron-down text-xs" :class="{ 'rotate-180': open }"></i>
    </button>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 z-50 mt-2 w-48 origin-top-right rounded-lg bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
        <div class="py-1">
            <!-- English -->
            <button onclick="switchLanguage('en')"
                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition {{ app()->getLocale() === 'en' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : '' }}">
                <span class="mr-3 text-lg">🇬🇧</span>
                <span class="flex-1 text-left">English</span>
                @if(app()->getLocale() === 'en')
                    <i class="fas fa-check text-blue-600 dark:text-blue-400"></i>
                @endif
            </button>

            <!-- Indonesian -->
            <button onclick="switchLanguage('id')"
                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition {{ app()->getLocale() === 'id' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : '' }}">
                <span class="mr-3 text-lg">🇮🇩</span>
                <span class="flex-1 text-left">Bahasa Indonesia</span>
                @if(app()->getLocale() === 'id')
                    <i class="fas fa-check text-blue-600 dark:text-blue-400"></i>
                @endif
            </button>
        </div>
    </div>
</div>

<script>
async function switchLanguage(locale) {
    try {
        const response = await fetch('{{ route("language.switch") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ locale: locale })
        });

        const result = await response.json();

        if (result.success) {
            // Reload page to apply new language
            window.location.reload();
        } else {
            console.error('Failed to switch language:', result.message);
            alert('Failed to switch language. Please try again.');
        }
    } catch (error) {
        console.error('Error switching language:', error);
        alert('An error occurred while switching language.');
    }
}
</script>
