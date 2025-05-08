<div class="row">
    <div class="row justify-content-center">
        <div class="col-auto">
            <div class="card mb-0" style="width: 120px;" >
                <img 
                   src="{{ asset('custom/app/img/clients/' . $companyID . '/' . $customise_itemLogo) }}"
                   class="card-img-top" style="height: 120px; object-fit: cover;" alt="Description"
                >
            </div>
        </div>
    </div>
    <p class="text-center">{{ $customise_item}} </p>
</div>
@include('livewire.mobile.fc.inc.details_scrollList')