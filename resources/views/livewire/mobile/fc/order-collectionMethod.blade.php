<div class="responsive-container"
    x-data="{
        eat_in:$wire.entangle('eat_in'),
        take_away:$wire.entangle('take_away'),
    }"
    >
    <div class="container-box" @click="$wire.set_eatIn();">
        <div class="box-text">{{ $eat_in }}</div>
        <div class="box-image-container">
            <img src="{{ asset('custom/app/img/admin/eatin.png') }}" 
             class="box-image" alt="Eat In">
        </div>
    </div>

    <div class="container-box" @click="$wire.set_takeAway();">
        <div class="box-text">{{ $take_away }}</div>
        <div class="box-image-container">
            <img src="{{ asset('custom/app/img/admin/paper-bag.png') }}" 
            class="box-image" alt="Take Away">
        </div>
    </div>
</div>