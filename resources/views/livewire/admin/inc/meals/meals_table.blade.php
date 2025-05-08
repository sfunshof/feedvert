<!-- Button aligned to the right -->
<div class="d-flex justify-content-end mb-3">
    <button  @click="showAdd_meal = false"   class="btn btn-outline-primary">Add New Meal </button>
</div>

<!-- Underline hr -->
<hr class="border-2 border-top border-dark">

<div class="table-responsive-container">
    <div class="table-scrollable-body" x-data="adminData">
        <table class="table table-hover">
            <thead class="table-fixed-header">
                <tr>
                    <th class="text-start" scope="col">Name</th>
                    <th scope="col">Category</th>
                    <th scope="col">Sub Category</th>
                    <th scope="col">Price</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Sample data - add more rows to test scrolling -->
                @if($meals)
                    @foreach($meals as $meal)
                        <tr>
                            <td class="text-start">
                                <p> {{ $meal['name'] }} </p>
                            </td>
                            <td>
                                @if(!empty($meal['categories']))
                                    <ul class="list-group">
                                        @foreach($meal['categories'] as $category)
                                            <li class="list-group-item">{{ $category['name'] }}</li>
                                        @endforeach
                                    </ul>
                                 @endif
                            </td>
                            <td>
                                @if(!empty($meal['subcategories']))
                                    <ul class="list-group">
                                         @foreach($meal['subcategories'] as $subcategory)
                                             <li class="list-group-item">{{ $subcategory['name'] }}</li>
                                        @endforeach
                                    </ul>
                             @endif
                            </td>
                            <td>
                                <p> {{ $meal['cost'] }}
                            </td>
                            <td>
                                <div class="row g-0">
                                    <div class="col-6">
                                        <button class="btn btn-link w-100 p-3" type="button" @click="updateMenuMeal({{ $meal['id'] }})">
                                            <i class="fas fa-pen text-success fs-4"></i>
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button class="btn btn-link w-100 p-3" type="button" @click="removeMenuMeal()">
                                            <i class="fas fa-times text-danger fs-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <p>No Meals available</p>
                @endif
            </tbody>
        </table>
    </div>
</div>
@script
    <script>
         Alpine.data('adminData', () => ({
            removeMenuMeal(){
                alert("test");
            },
            updateMenuMeal(id){
                // Update the component's meal_id with the clicked id
                $wire.set('meal_id', id);
                // Call the component's update_menuItem method
                $wire.update_menuMeal();
                this.showAdd_meal=false ;
            }
        }))
    </script>
@endscript
