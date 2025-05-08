<!-- Button aligned to the right -->
<div class="d-flex justify-content-end mb-3">
    <button  @click="showAdd_category = false; $wire.reset_admin_variables();  "   class="btn btn-outline-primary">Add New Category </button>
</div>

<!-- Underline hr -->
<hr class="border-2 border-top border-dark">


<div class="table-responsive-container"  >
    <div class="table-scrollable-body" x-data="adminCategoryData">
        <table class="table table-hover">
            <thead class="table-fixed-header">
                <tr>
                    <th class="col-2" scope="col" >Position</th>
                    <th class="col-6" scope="col" >Category Name</th>
                    <th class="col-4 scope="col" >Action</th>
                </tr>
            </thead>
            <tbody>
                @if(is_array($sortedData) || is_object($sortedData))
                    @foreach($sortedData as $index => $category)
                        <tr wire:key="row-{{ $category['id'] }}">
                            <td style="width: 100px;">
                                <input type="number"
                                    class="form-control form-control text-center"
                                    value={{ $sortedData[$index]['position'] }}
                                    min=1
                                    max={{$maxValue}}
                                    wire:model.blur="position.{{ $index }}"
                                    wire:key="position-{{ $index }}"
                                    required
                                />
                            </td>
                            <td>
                                <div class="p-2 rounded {{ $sortedData[$index]['bgColor'] }} {{ $sortedData[$index]['bgColor'] === 'bg-dark' ? 'text-white' : 'text-dark' }} 
                                    {{ $sortedData[$index]['bgColor'] === 'bg-light' ? 'border border-1' : '' }}">
                                    {{ $sortedData[$index]['name'] }}
                                </div>
                            </td>
                            <td>
                                <div class="row g-0">
                                    <div class="col-6">
                                        <button class="btn btn-link w-100 p-3" type="button"
                                           @click="updateMenuCategory({{ $sortedData[$index]['id']}} )">
                                            <i class="fas fa-pen text-success fs-4"></i>
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button class="btn btn-link w-100 p-3" type="button" @click="removeMenuCategory()">
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
        Alpine.data('adminCategoryData', () => ({
            removeMenuCategory(){
                alert("test");
            },
            updateMenuCategory(id){
                // Update the component's item_id with the clicked id
                $wire.set('category_id', id);
                // Call the component's update_menuItem method
                 $wire.update_menuCategory();
                this.showAdd_category=false ;
            }
        }))
    </script>
@endscript