@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border border-gray-300 px-4 py-2.5 focus:border-[#083321] focus:ring-[#083321] rounded-xl shadow-sm']) }}>
