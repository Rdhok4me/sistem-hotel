@props(['active' => false])

<a {{ $attributes }}
   class="flex items-center gap-2.5 px-3 py-2 text-sm rounded-lg transition-colors
          {{ $active
              ? 'bg-indigo-50 text-indigo-700 font-medium'
              : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    {{ $slot }}
</a>
