@php
    if (in_array($this->mealTypeID, [1,2])){
        $this->customise_item=$itemDetails->name;
        $this->customise_itemLogo=$itemDetails->logo;
    }
@endphp
<div class="logo-container mb-3">
    <img src="{{ asset('custom/app/img/clients/' . $companyID . '/' .   $itemDetails->logo) }}"  alt="Logo"  class="logo">
    <div class="logo-text"> {{$itemDetails->name}} </div>
    <div class="logo-text">  {{$currency}}{{$itemDetails->price}}</div>
</div>