@props([
'name',
'label' => null,
'type' => 'text',
'value' => null,
'placeholder' => null,
])

<div class="mb-3">
    @if($label)
    <label for="{{$name}}" class="form-label">
        {{$label}}
    </label>
    @endif

    <input type="{{$type}}" id="{{$name}}" name="{{$name}}"
        value="{{old($name, $value)}}" placeholder="{{$placeholder}}"
        {{ $attributes->merge(['class' => 'form-control' ]) }}>

    @error($name)
    <div class="text-danger"> {{$message}} </div>
    @enderror
</div>