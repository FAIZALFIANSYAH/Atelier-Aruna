@props([
'variant' => 'success'
])

<div {{$attributes->merge ([
'class' => "alert alert-{$variant}" ]) }}>
    {{$slot}}
</div>