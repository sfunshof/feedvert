<!-- Button aligned to the right -->
<div class="d-flex justify-content-end mb-3">
    <button  @click="showAdd = false; $wire.reset_admin_variables(); "   class="btn btn-outline-primary">Add New Item </button>
</div>

<!-- Underline hr -->
<hr class="border-2 border-top border-dark">

<div class="table-responsive-container">
    <div class="table-scrollable-body" x-data="adminItemData">
        <table class="table table-hover">
            <thead class="table-fixed-header">
                <tr>
                    <th class="text-start" scope="col">Name</th>
                    <th scope="col">Category</th>
                    <th scope="col">Sub Category</th>
                    <th scope="col">Cost</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Sample data - add more rows to test scrolling -->
                @if($items)
                    @foreach($items as $item)
                        <tr>
                            <td class="text-start">
                                <p> {{ $item['name'] }} </p>
                            </td>
                            <td>
                                @if(!empty($item['categories']))
                                    <ul class="list-group">
                                        @foreach($item['categories'] as $category)
                                            <li class="list-group-item">{{ $category['name'] }}</li>
                                        @endforeach
                                    </ul>
                                 @endif
                            </td>
                            <td>
                                @if(!empty($item['subcategories']))
                                <ul class="list-group">
                                         @foreach($item['subcategories'] as $subcategory)
                                             <li class="list-group-item">{{ $subcategory['name'] }}</li>
                                        @endforeach
                                    </ul>
                             @endif
                            </td>
                            <td>
                                <p> {{ $item['cost'] }}
                            </td>
                            <td>
                                <div class="row g-0">
                                    <div class="col-6">
                                        <button class="btn btn-link w-100 p-3" type="button" @click="updateMenuItem({{ $item['id'] }})">
                                            <i class="fas fa-pen text-success fs-4"></i>
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button class="btn btn-link w-100 p-3" type="button" @click="removeMenuItem()">
                                            <i class="fas fa-times text-danger fs-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <p>No items available</p>
                @endif
            </tbody>
        </table>
    </div>
</div>
@script
    <script>
         Alpine.data('adminItemData', () => ({
            removeMenuItem(){
                alert("test");
            },
            updateMenuItem(id){
                // Update the component's item_id with the clicked id
                $wire.set('item_id', id);
                // Call the component's update_menuItem method
                $wire.update_menuItem();
                this.showAdd=false ;
            }
        }))
    </script>
@endscript