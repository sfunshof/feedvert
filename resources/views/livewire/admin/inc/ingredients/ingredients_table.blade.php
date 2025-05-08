<div class="table-responsive-container">
    <div class="table-scrollable-body" id="ingredientsTableID">
        <table class="table table-hover">
            <thead class="table-fixed-header">
                <tr>
                    <th class="col-7">Name</th>
                    <th class="col-5">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ingredientArray as $ingredient)
                    <tr>
                        <td class="col-7">
                            <input type="text" class="form-control" wire:model.defer="ingredientArray.{{ $loop->index }}.name">
                        </td>
                        <td class="col-5">
                            <div class="row g-0">
                                <div class="col-6">
                                    <button class="btn btn-link w-100 p-3" type="button" @click="$wire.update_ingredient({{ $ingredient->id }})">
                                        <i class="fas fa-file text-primary fs-4"></i>
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-link w-100 p-3" type="button" @click="$wire.delete_ingredient({{ $ingredient->id }})">
                                        <i class="fas fa-times text-danger fs-4"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                 @endforeach
                
                <tr>
                    <td class="col-7">
                        <input type="text" class="form-control" wire:model.defer="new_ingredient">
                    </td>
                    <td class="col-5">
                        <button class="btn btn-link w-100 p-3" type="button" @click="$wire.add_ingredient">
                            <i class="fas fa-plus text-success fs-4"></i>
                        </button>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>
@script
    <script>
        Livewire.on('update_ingredientsTable', () => {
            //window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
            //window.scrollTo(0, document.body.scrollHeight);
            const scrollableDiv = document.getElementById("ingredientsTableID");
            if (scrollableDiv) {
                scrollableDiv.scrollTop = scrollableDiv.scrollHeight;
            } else {
                //console.error('Div not found!');
            }
            //alert("test");
        });
    </script>
@endscript