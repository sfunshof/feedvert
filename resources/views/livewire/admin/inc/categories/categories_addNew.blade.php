    <!-- Button aligned to the right -->
    <div class="row justify-content-end mb-3"
        x-data="{
            name: $wire.entangle('name_'),
            mealTypeID: $wire.entangle('mealType_id'),
            categoryEntities:$wire.entangle('categoryEntities_'),
            save(){
                let ques = '';
                if(!this.name){
                    ques += 'Please Enter the Category Name. ';
                }
                if(!this.mealTypeID){
                    ques += 'Please Select the Category Type. ';
                }
                if (this.categoryEntities.length==0){
                     ques += 'Please Assign some Entities to the Category ';
                }
                if (ques){
                    /* 
                    Fnon.Hint.Warning(ques, {
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
                    $wire.save_category();
                    showAdd_category =true;
                }
            },
        }"
    >

        <div class="col-5 d-flex justify-content-end">
            <button @click="save(); $wire.save_image('CategorySaved');" class="btn btn-outline-primary me-2 w-50">Save Category</button>
            <button @click="showAdd_category = true;$wire.clearLogo();" class="btn btn-outline-success w-50">Back</button>
        </div>
    </div>

    <!-- Underline hr -->
    <hr class="border-2 border-top border-dark">
    <div class="scroll-container" id="scrollContainer">
        <div class="scroll-content">
            <div class="mt-1"
                x-data="{
                    name: $wire.entangle('name_'),
                    mealTypeID: $wire.entangle('mealType_id'),
                    bgColor: $wire.entangle('bgColor_'),
                    logo: $wire.entangle('logo_'),
                    selectedImageUrl: $wire.entangle('selectedImageUrl_'),
                    categoryType: '',
                    selectedItems:$wire.entangle('categoryArray'),
                    isSelectDisabled: false,
                    userChanged: false,
                    categoryTypes: {
                        1: 'Item Category',
                        2: 'Item SubCategory',
                        3: 'Meal Category',
                        4: 'Meal SubCategory'
                    },
                    assignToCategory: {
                        1: 'Assign Items to Category',
                        2: 'Assign Items to SubCategory',
                        3: 'Assign Meals to  Category',
                        4: 'Assign Meals to SubCategory'
                    },
                    categoryNew: {
                        1: 'New Item',
                        2: 'New Item SubCategory',
                        3: 'New Meal',
                        4: 'New Meal SubCategory'
                    },
                    categoryItems:$wire.entangle('categoryItems'),
                    subCategoryItems:$wire.entangle('subCategoryItems'),
                    subCategoryMeals:$wire.entangle('subCategoryMeals'),
                    categoryMeals:$wire.entangle('categoryMeals'),
                    categoryEntities:$wire.entangle('categoryEntities_'),
                    categoryID:$wire.entangle('category_id'),
                    buttonCaption() {
                        return this.mealTypeID 
                        ? ` ${this.categoryNew[this.mealTypeID]}`
                        : '';
                    },
                    labelCaption() {
                        return this.mealTypeID 
                        ? `${this.assignToCategory[this.mealTypeID]}`
                        : '';
                    },
                    toggleSelection(id) {
                        if (this.selectedItems.includes(id)) {
                            this.selectedItems = this.selectedItems.filter(item => item !== id);
                        } else {
                            this.selectedItems.push(id);
                        }
                    },
                    toggleCategoryEntities(id) {
                        if (this.categoryEntities.includes(id)) {
                            this.categoryEntities = this.categoryEntities.filter(aid => aid !== id);
                        } else {
                            this.categoryEntities.push(id);
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
                    save() {
                        let jsonObject = {};
                        if (this.mealTypeID == 1) {
                            jsonObject = { item: this.selectedItems };
                        } else if (this.mealTypeID == 2 || this.mealTypeID == 4) {
                            jsonObject = { itemsubfolder: this.selectedItems };
                        } else if (this.mealTypeID == 3) {
                            jsonObject = { meal: this.selectedItems };
                        }
                        $wire.save_category({
                            name: this.name,
                            mealTypeID: this.mealTypeID,
                            logo: this.logo,
                            bgColor: this.bgColor,
                            json: jsonObject
                        });
                    },
                    reset() {
                        this.name = '';
                        this.logo = '';
                        this.bgColor = '';
                        this.selectedItems = [];
                        if ($wire.getSortedDataCount() == 0) {
                            this.mealTypeID = null;
                        }
                        $wire.resetFields();
                    },
                    updateSelectState() {
                        if (this.userChanged) {
                            this.isSelectDisabled = false;
                            this.userChanged = false;
                            //always pre selection clear for add new
                            if(!this.categoryID){
                               this.categoryEntities=[];
                            }
                            //alert(JSON.stringify(this.categoryEntities));
                            //load a new item1 item2 etc
                            $wire.update_categoryEntities(this.mealTypeID);
                        } else {
                            this.isSelectDisabled = this.mealTypeID !== null && this.mealTypeID >= 1;
                        }
                    },
                    clearPreview() {
                        this.selectedImageUrl = null; // Clear the preview image
                    }
                }",
                x-init="
                    updateSelectState();
                    $watch('mealTypeID', (value) => {
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
                            x-model="mealTypeID"
                            class="form-control"
                            :disabled="isSelectDisabled"
                            @change="userChanged = true; updateSelectState();"
                        >
                        <option value="">Select Category Type</option>
                        <template x-for="(name, id) in categoryTypes" :key="id">
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
                        @if ($mealType_id == 1)
                            <table>
                                @foreach ($categoryItems as $categoryItem)
                                    <tr>
                                        <td style="display: none;">{{ $categoryItem->id }}</td>
                                        <td>
                                            <button class="btn square-btn btn-outline-secondary"
                                                    :class="categoryEntities.includes({{ $categoryItem->id }}) ? 'btn-dark' : ''"
                                                    x-text="categoryEntities.includes({{ $categoryItem->id }}) ? 'X' : ' '"
                                                    @click="toggleCategoryEntities({{ $categoryItem->id }})">
                                            </button>
                                        </td>
                                        <td>{{ $categoryItem->name }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif

                        @if ($mealType_id == 2)
                            <table>
                                @foreach ($subCategoryItems as $subCategoryItem)
                                    <tr>
                                        <td style="display: none;">{{ $subCategoryItem->id }}</td>
                                        <td>
                                            <button class="btn square-btn btn-outline-secondary"
                                                    :class="categoryEntities.includes({{ $subCategoryItem->id }}) ? 'btn-dark' : ''"
                                                    x-text="categoryEntities.includes({{ $subCategoryItem->id }}) ? 'X' : ' '"
                                                    @click="toggleCategoryEntities({{ $subCategoryItem->id }})">
                                            </button>
                                        </td>
                                        <td>{{ $subCategoryItem->name }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif

                        @if ($mealType_id == 3)
                            <table>
                                @foreach ($categoryMeals as $categoryMeal)
                                    <tr>
                                        <td style="display: none;">{{ $categoryMeal->id }}</td>
                                        <td>
                                            <button class="btn square-btn btn-outline-secondary"
                                                    :class="categoryEntities.includes({{ $categoryMeal->id }}) ? 'btn-dark' : ''"
                                                    x-text="categoryEntities.includes({{ $categoryMeal->id }}) ? 'X' : ' '"
                                                    @click="toggleCategoryEntities({{ $categoryMeal->id }})">
                                            </button>
                                        </td>
                                        <td>{{ $categoryMeal->name }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif

                        @if ($mealType_id == 4)
                            <table>
                                @foreach ($subCategoryMeals as $subCategoryMeal)
                                    <tr>
                                        <td style="display: none;">{{ $subCategoryMeal->id }}</td>
                                        <td>
                                            <button class="btn square-btn btn-outline-secondary"
                                                    :class="categoryEntities.includes({{ $subCategoryMeal->id }}) ? 'btn-dark' : ''"
                                                    x-text="categoryEntities.includes({{ $subCategoryMeal->id }}) ? 'X' : ' '"
                                                    @click="toggleCategoryEntities({{ $subCategoryMeal->id }})">
                                            </button>
                                        </td>
                                        <td>{{ $subCategoryMeal->name }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif

                    </td>
                    @if ($mealType_id >0)
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