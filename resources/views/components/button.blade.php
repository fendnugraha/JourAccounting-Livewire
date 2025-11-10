@props(['style' => 'primary'])

@php
switch ($style) {
case 'primary':
$color = 'bg-indigo-500 hover:bg-indigo-600';
break;

case 'secondary':
$color = 'bg-gray-500 hover:bg-gray-600';
break;

case 'success':
$color = 'bg-green-500 hover:bg-green-600';
break;

case 'danger':
$color = 'bg-red-500 hover:bg-red-600';
break;

case 'warning':
$color = 'bg-yellow-500 hover:bg-yellow-600';
break;

case 'info':
$color = 'bg-blue-500 hover:bg-blue-600';
break;

case 'light':
$color = 'bg-gray-200 hover:bg-gray-300';
break;

case 'dark':
$color = 'bg-gray-800 hover:bg-gray-900';
break;

default:
$color = 'bg-gray-500';
break;
}
@endphp

<button {{ $attributes->merge([
    'class' => 'inline-flex items-center justify-center px-4 py-2 ' . $color .
    ' border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition
    ease-in-out duration-150'
    ]) }}>
    {{ $slot }}
</button>