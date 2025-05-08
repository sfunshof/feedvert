    <!-- Scrollable list container -->
    @if ($addOnResult && $addOnResult->isNotEmpty())
        <div class="col-12">
            <div class="list-container" >
                @foreach($addOnResult as $index => $result)
                    <!-- List item -->
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="row g-0 align-items-center p-2">
                            <!-- Column 1: Image and Description (col-8) -->
                            <div class="col-7">
                                <div class="row g-0 align-items-center">
                                    <!-- Image container -->
                                    <div class="col-auto">
                                        <div class="image-container bg-warning" style="width: 64px; height: 64px;">
                                            <img src="{{ asset('custom/app/img/clients/' . $companyID . '/' .   $result->logo) }}"
                                                class="img-fluid rounded object-fit-cover" 
                                                style="width: 64px; height: 64px;"
                                                alt="Item image">
                                        </div>
                                    </div>
                                    <!-- Description -->
                                    <div class="col ps-3">
                                        <p class="mb-0 {{ $addOnStates[$index]['classes'] }}">
                                            {{ $addOnStates[$index]['prepend'] }}  {{ $result->name }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Column 2: Action Icons (col-4) -->
                            <div class="col-5">
                                <div class="d-flex justify-content-around align-items-center h-100">
                                    <!-- Remove Icon -->
                                    <button @click="$wire.set_addOns(0, {{ $index }}, '{{$result->name}}', {{$result->id}},'{{$result->logo}}'  )"  class="btn btn-danger h-100 d-flex align-items-center">
                                        <i class="bi bi-x-lg fs-4"></i>
                                    </button>

                                    <!-- Refresh Icon -->
                                    <button @click="$wire.set_addOns(1, {{ $index }}, '{{$result->name}}', {{$result->id}},'{{$result->logo}}' )" class="btn btn-secondary h-100 d-flex align-items-center">
                                        <i class="bi bi-arrow-clockwise fs-4"></i>
                                    </button>

                                    <!-- Add Icon -->
                                    <button @click="$wire.set_addOns(2, {{ $index}}, '{{$result->name}}', {{$result->id}}, '{{$result->logo}}')" class="btn btn-success h-100 d-flex align-items-center">
                                        <i class="bi bi-plus-lg fs-4"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- End of the Icons -->

                        </div>
                    </div>
                @endforeach
                <!-- End of the list  -->
            </div>
        </div>
    @endif
