    <!-- Button aligned to the right -->
    <div class="row justify-content-end mb-3"
        x-data="{
            name: $wire.entangle('name_'),
            subMealTypeID: $wire.entangle('subMealType_id'),
            subCategoryEntities:$wire.entangle('categoryEntities_'),
            save(){
                let ques = '';
                if(!this.name){
                    ques += 'Please Enter the Sub Category Name. ';
                }
                if(!this.subMealTypeID){
                    ques += 'Please Select the Sub Category Type. ';
                }
                if (this.subCategoryEntities.length==0){
                     ques += 'Please Assign some Entities to the Sub Category ';
                }
                if (ques){
                   /*
                    Fxon.Hint.Warning(ques, {
                        callback:function(){
                        },
                        position:'center-center',
                        textColor: '#212529'
                    });
                    */
                     new Notify({
                        status: 'warning',
                        title: '',
                        type:'filled',
                        text: ques,
                        position:'center'
                    });
                }else{
                    $wire.save_subCategory();
                    showAdd_subCategory =true;
                }
            },
        }"
    >

        <div class="col-5 d-flex justify-content-end">
            <button @click="save(); $wire.save_image('SubCategorySaved');" class="btn btn-outline-primary me-2 w-50">Save Sub Category</button>
            <button @click="showAdd_subCategory = true;$wire.clearLogo();" class="btn btn-outline-success w-50">Back</button>
        </div>
    </div>

    <!-- Underline hr -->
    <hr class="border-2 border-top border-dark">
    <div class="scroll-container" id="scrollContainer">
        <div class="scroll-content">
            <div class="mt-1"
                x-data="{
                    name: $wire.entangle('name_'),
                    subMealTypeID: $wire.entangle('subMealType_id'),
                    bgColor: $wire.entangle('bgColor_'),
                    logo: $wire.entangle('logo_'),
                    selectedImageUrl: $wire.entangle('selectedImageUrl_'),
                    subCategoryType: '',
                    selectedItems:$wire.entangle('subCategoryArray'),
                    isSelectDisabled: false,
                    userChanged: false,
                    subCategoryTypes: {
                        1: 'Item SubCategory',
                        2: 'Meal SubCategory'
                    },
                    assignToSubCategory: {
                        1: 'Assign Items to SubCategory',
                        2: 'Assign Meals to SubCategory'
                    },
                    subCategoryNew: {
                        1: 'New Item SubCategory',
                        2: 'New Meal SubCategory'
                    },
                    subCategoryItems:$wire.entangle('subCategoryItems'),
                    subCategoryMeals:$wire.entangle('subCategoryMeals'),
                    subCategoryEntities:$wire.entangle('categoryEntities_'),
                    subCategoryID:$wire.entangle('subCategory_id'),
                    buttonCaption() {
                        return this.subMealTypeID 
                        ? ` ${this.subCategoryNew[this.subMealTypeID]}`
                        : '';
                    },
                    labelCaption() {
                        return this.subMealTypeID 
                        ? `${this.assignToSubCategory[this.subMealTypeID]}`
                        : '';
                    },
                    toggleSelection(id) {
                        if (this.selectedItems.includes(id)) {
                            this.selectedItems = this.selectedItems.filter(item => item !== id);
                        } else {
                            this.selectedItems.push(id);
                        }
                    },
                    toggleSubCategoryEntities(id) {
                        if (this.subCategoryEntities.includes(id)) {
                            this.subCategoryEntities = this.subCategoryEntities.filter(aid => aid !== id);
                        } else {
                            this.subCategoryEntities.push(id);
                        }
                    },
                    getLogoUrl(filename) {
                        return filename ? '/custom/app/img/clients/{{ Auth::user()->companyID }}/' + filename : '';
                    },
                    previewImage(event) {
                        const file = event.target.files[0];
                        if (file) {
                            this.selectedImageUrl = URL.createObjectURL(file);
                        }
                    },
                    reset() {
                        this.name = '';
                        this.logo = '';
                        this.bgColor = '';
                        this.selectedItems = [];
                        if ($wire.getSortedDataCount() == 0) {
                            this.subMealTypeID = null;
                        }
                        $wire.resetFields();
                    },
                    updateSelectState() {
                        if (this.userChanged) {
                            this.isSelectDisabled = false;
                            this.userChanged = false;
                            //always pre selection clear for add new
                            if(!this.subCategoryID){
                               this.subCategoryEntities=[];
                            }
                            //alert(JSON.stringify(this.categoryEntities));
                            //load a new item1 item2 etc
                            $wire.update_subCategoryEntities(this.subMealTypeID);
                        } else {
                            this.isSelectDisabled = this.subMealTypeID !== null && this.subMealTypeID >= 1;
                        }
                    },
                    clearPreview() {
                        this.selectedImageUrl = null; // Clear the preview image
                    }
                }",
                x-init="
                    updateSelectState();
                    $watch('subMealTypeID', (value) => {
                        updateSelectState();
                    });
                    $watch('logo', (newLogo) => {
                        if (!newLogo) {
                            clearPreview(); // Clear preview if 'logo' is set to empty
                        }
                    })
                "
            >
            <table class="table table-bordered">
                <tr>
                    <td class="col-6">
                        <input type="text" class="form-control" x-model="name" placeholder="Category Name">
                        <br>
                        <label>Background Color:</label><br>
                        <div class="d-flex flex-wrap">
                            <template x-for="color in ['bg-primary', 'bg-secondary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'bg-light', 'bg-dark']" :key="color">
                                <div :class="['color-box', color]" @click="$wire.set('bgColor_', color)" style="position: relative; text-align: center; font-size: 24px; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 50px; height: 50px;">
                                    <span x-show="bgColor === color" :style="{'color': (color === 'bg-dark' ? '#fff' : '#000'), 'font-size': '40px', 'font-weight': 'bold', 'line-height': '50px'}">X</span>
                                </div>
                            </template>
                        </td>
                    <td class="col-4">
                        <select
                            x-model="subMealTypeID"
                            class="form-control"
                            :disabled="isSelectDisabled"
                            @change="userChanged = true; updateSelectState();"
                        >
                        <option value="">Select Sub Category Type</option>
                        <template x-for="(name, id) in subCategoryTypes" :key="id">
                            <option :value="id" x-text="name"></option>
                        </template>
                    </select>
                    </td>
                    <td class="col-2">
                        <!-- Upload Logo Button -->
                        <button class="btn btn-secondary"
                            @click="$refs.fileInput.click()">
                            Upload Logo
                        </button>

                        <!-- Hidden File Input -->
                        <input
                            type="file"
                            id="item-upload"
                            wire:model="selectedImage"
                            class="d-none"
                            x-ref="fileInput"
                            @change="previewImage($event)"
                        >
                        <!-- Image Display Container -->
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
                            <img x-show="!selectedImageUrl && logo"
                                :src="getLogoUrl(logo)"
                                class="img-thumbnail w-full h-full transition-opacity duration-500 ease-in-out"
                                x-transition:enter="opacity-0"
                                x-transition:enter-end="opacity-100"
                                x-transition:leave="opacity-100"
                                x-transition:leave-end="opacity-0"
                            >
                            <!-- Placeholder when no logo is available -->
                            <div x-show="!selectedImageUrl && !logo"
                                class="flex items-center justify-center w-full h-full bg-gray-200 text-gray-500">
                                No Logo
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="col-8">
                        <label x-text="labelCaption"></label>
                        @if ($subMealType_id == 1)
                            <table>
                                @foreach ($categoryItems as $subCategoryItem)
                                    <tr>
                                        <td style="display: none;">{{ $subCategoryItem->id }}</td>
                                        <td>
                                            <button class="btn square-btn btn-outline-secondary"
                                                    :class="subCategoryEntities.includes({{ $subCategoryItem->id }}) ? 'btn-dark' : ''"
                                                    x-text="subCategoryEntities.includes({{ $subCategoryItem->id }}) ? 'X' : ' '"
                                                    @click="toggleSubCategoryEntities({{ $subCategoryItem->id }})">
                                            </button>
                                        </td>
                                        <td>{{ $subCategoryItem->name }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif

                        @if ($subMealType_id == 2)
                            <table>
                                @foreach ($categoryMeals as $subCategoryMeal)
                                    <tr>
                                        <td style="display: none;">{{ $subCategoryMeal->id }}</td>
                                        <td>
                                            <button class="btn square-btn btn-outline-secondary"
                                                    :class="subCategoryEntities.includes({{ $subCategoryMeal->id }}) ? 'btn-dark' : ''"
                                                    x-text="subCategoryEntities.includes({{ $subCategoryMeal->id }}) ? 'X' : ' '"
                                                    @click="toggleSubCategoryEntities({{ $subCategoryMeal->id }})">
                                            </button>
                                        </td>
                                        <td>{{ $subCategoryMeal->name }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif

                        
                    </td>
                    @if ($subMealType_id >0)
                        <td colspan="3">
                            <button class="btn btn-primary" x-text="buttonCaption"></button>
                        </td>
                    @endif
                </tr>
            </table>
        
            <button class="btn btn-success" @click="save">Save</button>
            <button class="btn btn-danger" @click="reset">Reset</button>
        </div>
    </div>
</div>     