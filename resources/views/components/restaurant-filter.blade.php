@props(['restaurants' => collect(), 'selected' => null])

@if($restaurants->count() > 1)
<select class="form-select form-select-sm restaurant-filter" style="width:auto;" onchange="window.location.href=this.value ? '{{ request()->url() }}?restaurant_id='+this.value : '{{ request()->url() }}'">
    <option value="">{{ __('restaurant.restaurants.all') }}</option>
    @foreach($restaurants as $r)
        <option value="{{ $r->id }}" @selected((int)$selected === $r->id)>{{ $r->localized_name }}</option>
    @endforeach
</select>
@endif
