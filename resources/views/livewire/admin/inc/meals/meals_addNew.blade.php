<!-- Button aligned to the right -->
<div class="row justify-content-end mb-3">
    <div class="col-5 d-flex justify-content-end">
        <button @click="showAdd_meal = true; $wire.save_image('mealSaved');" class="btn btn-outline-primary me-2 w-50">Save Meal</button>
        <button @click="showAdd_meal = false;" class="btn btn-outline-success w-50">Back</button> <!-- Fixed redundant @click -->
    </div>
</div>

<!-- Underline hr -->
<hr class="border-2 border-top border-dark">

<div class="scroll-container" id="scrollContainer">
    <div class="scroll-content">
        <div class="mt-1" 
            x-data="{
                name: $wire.entangle('name_'),
                cost: $wire.entangle('cost_'),
                bgColor: $wire.entangle('bgColor_'),
                logo: $wire.entangle('logo_'),
                subCategory_: $wire.entangle('subCategory_'),
                mealItems_: $wire.entangle('meal_items_'),
                selectedImage: $wire.entangle('selectedImage'),
                selectedImageUrl: null,

                getLogoUrl(filename) {
                    return filename ? '/custom/app/img/clients/{{ Auth::user()->companyID }}/' + filename : '';
                },
                toggleSubCategory(id) {
                    if (this.subCategory_.includes(id)) {
                        this.subCategory_ = this.subCategory_.filter(aid => aid !== id);
                    } else {
                        this.subCategory_.push(id);
                    }
                },
                toggleMealItems(id) {
                    if (this.mealItems_.includes(id)) {
                        this.mealItems_ = this.mealItems_.filter(aid => aid !== id);
                    } else {
                        this.mealItems_.push(id);
                    }
                },
                save() {
                    $wire.save();
                },
                reset() {
                    this.name = '';
                    this.cost = 0;
                    this.bgColor = '';
                    this.logo = '';
                    this.subCategory_ = [];
                    this.mealItems_ = [];
                    $wire.resetFields();
                },
            }">
            
            <!-- Row 1: Name and Cost -->
            <div class="row row-divider">
                <div class="col-9">
                    <div class="form-floating">
                        <input type="text" class="form-control" x-model="name" placeholder="Enter name">
                        <label>Name</label>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-floating">
                        <input type="number" class="form-control" x-model="cost" placeholder="0.00">
                        <label>Cost</label>
                    </div>
                </div>
            </div>
            
            <!-- Row 2: Background Color and Logo -->
            <div class="row row-divider">
                <div class="col-9">
                    <label>Background Color:</label><br>
                    <div class="d-flex flex-wrap">
                        <template x-for="color in ['bg-primary', 'bg-secondary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'bg-light', 'bg-dark']" :key="color">
                            <div :class="['color-box', color]" @click="$wire.set('bgColor_', color)" style="position: relative; text-align: center; font-size: 24px; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 50px; height: 50px;">
                                <span x-show="bgColor === color" :style="{'color': (color === 'bg-dark' ? '#fff' : '#000'), 'font-size': '40px', 'font-weight': 'bold', 'line-height': '50px'}">X</span>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="col-3" x-data="{ openFileInput: false }">
                    <label>Logo:</label><br>
                    <button class="btn btn-secondary"
                        @click="openFileInput = true; $nextTick(() => document.getElementById('meal-upload').click())">
                        Upload Logo
                    </button>
                    <!-- Hidden file input bound to Livewire -->
                    <input
                        type="file"
                        id="meal-upload"
                        wire:model="selectedImage"
                        class="d-none"
                        @change="selectedImageUrl = URL.createObjectURL($event.target.files[0])"
                    >

                    <!-- Smooth transition between predetermined logo and selected image -->
                    <div class="relative mt-2" style="max-width: 100px;">
                        <!-- Show new uploaded image -->
                        <img x-show="selectedImageUrl"
                            :src="selectedImageUrl"
                            class="img-thumbnail absolute inset-0 w-full h-full transition-opacity duration-500 ease-in-out"
                            x-transition:enter="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="opacity-100"
                            x-transition:leave-end="opacity-0"
                        >

                        <!-- Show predetermined image -->
                        <img x-show="!selectedImageUrl"
                            :src="getLogoUrl(logo)"
                            class="img-thumbnail w-full h-full transition-opacity duration-500 ease-in-out"
                            x-transition:enter="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="opacity-100"
                            x-transition:leave-end="opacity-0"
                        >
                    </div>
                </div> <!-- Added missing closing div -->
            </div>

            <!-- Row 3: Sub Category & Meal_items -->
            <div class="row">
                <!-- Sub Category -->
                <div class="col-7">
                    <label>Offers:</label>
                    <table class="table table-bordered">
                        @foreach ($subCategory as $offer)
                            <tr>
                                <td style="display: none;">{{ $offer->id }}</td>
                                <td>
                                    <button class="btn square-btn btn-outline-secondary" 
                                            :class="subCategory_.includes({{ $offer->id }}) ? 'btn-dark' : ''" 
                                            x-text="subCategory_.includes({{ $offer->id }}) ? 'X' : ' '" 
                                            @click="toggleSubCategory({{ $offer->id }})"></button>
                                </td>
                                <td>{{ $offer->name }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                
                <!-- Meal Items -->
                <div class="col-5">
                    <label>Meal Items:</label>
                    <table class="table table-bordered">
                        @foreach ($meal_items as $mealItem)
                            <tr>
                                <td style="display: none;">{{ $mealItem->id }}</td>
                                <td>
                                    <button class="btn square-btn btn-outline-secondary" 
                                            :class="mealItems_.includes({{ $mealItem->id }}) ? 'btn-dark' : ''" 
                                            x-text="mealItems_.includes({{ $mealItem->id }}) ? 'X' : ' '" 
                                            @click="toggleMealItems({{ $mealItem->id }})"></button>
                                </td>
                                <td>{{ $mealItem->name }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            
            <!-- Save Button -->
            <div class="row">
                <div class="col-6">
                    <button class="btn btn-success w-100" @click="save">Save</button>
                </div>
                <div class="col-6">
                    <button class="btn btn-danger w-100" @click="reset">Reset</button>
                </div>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        Livewire.on('mealSaved', () => {
            alert('Success! Image saved successfully.');
        });
    </script>
@endscript