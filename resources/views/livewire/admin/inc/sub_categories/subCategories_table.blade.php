<!-- Button aligned to the right -->
<div class="d-flex justify-content-end mb-3">
    <button  @click="showAdd_subCategory = false; $wire.reset_admin_variables();"   class="btn btn-outline-primary">Add New Sub Category </button>
</div>

<!-- Underline hr -->
<hr class="border-2 border-top border-dark">

<div class="table-responsive-container"  >
    <div class="table-scrollable-body" x-data="adminSubCategoryData">
        <table class="table table-hover">
            <thead class="table-fixed-header">
                <tr>
                    <th class="col-6" scope="col" >SubCategory Name</th>
                    <th class="col-4 scope="col" >Action</th>
                </tr>
            </thead>
            <tbody>
                @if(is_array($subCategoryArray) || is_object($subCategoryArray))
                    @foreach($subCategoryArray as $index => $subCategory)
                        <tr wire:key="row-{{ $subCategory['id'] }}">
                            <td>
                                <div class="p-2 rounded {{ $subCategoryArray[$index]['bgColor'] }} {{ $subCategoryArray[$index]['bgColor'] === 'bg-dark' ? 'text-white' : 'text-dark' }} 
                                    {{ $subCategoryArray[$index]['bgColor'] === 'bg-light' ? 'border border-1' : '' }}">
                                    {{ $subCategoryArray[$index]['name'] }}
                                </div>
                            </td>
                            <td>
                                <div class="row g-0">
                                    <div class="col-6">
                                        <button class="btn btn-link w-100 p-3" type="button"
                                           @click="updateMenuSubCategory({{ $subCategoryArray[$index]['id']}} )">
                                            <i class="fas fa-pen text-success fs-4"></i>
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button class="btn btn-link w-100 p-3" type="button" @click="removeMenuSubCategory()">
                                            <i class="fas fa-times text-danger fs-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@script
    <script>
        Alpine.data('adminSubCategoryData', () => ({
            removeMenuSubCategory(){
                alert("test");
            },
            updateMenuSubCategory(id){
                // Update the component's item_id with the clicked id
                $wire.set('subCategory_id', id);
                // Call the component's update_menuItem method
                 $wire.update_menuSubCategory();
                this.showAdd_subCategory=false ;
            }
        }))
    </script>
@endscript