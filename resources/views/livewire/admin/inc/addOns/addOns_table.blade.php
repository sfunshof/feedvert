<!-- Underline hr -->
<hr class="border-2 border-top border-dark">

<div x-data="{
    logo: $wire.entangle('logo_'),
    selectedImage: $wire.entangle('selectedImage'),
    selectedImageUrl: null,
    previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            this.selectedImageUrl = URL.createObjectURL(file); // Generate a temporary preview URL
        }
    },
    getLogoUrl(filename) {
        return filename ? '/custom/app/img/clients/{{ Auth::user()->companyID }}/' + filename : '';
    }
}">
    <div class="table-responsive-container">
        <div class="table-scrollable-body" id="ingredientsTableID">
            <table class="table table-hover">
                <thead class="table-fixed-header">
                    <tr>
                        <th scope="col" class="col-3">Logo</th>
                        <th scope="col" class="col-6">Name</th>
                        <th scope="col" class="col-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                     @foreach ($addonArray as $addOn)
                        <tr wire:key="addon-{{ $addOn->id }}">
                            <td class="col-3">
                           <!-- Logo acts as an upload button -->
                        <div x-data="{ selectedImageUrl: null }">
                            <!-- Show either the original logo or the preview -->
                            <img 
                                x-show="!selectedImageUrl"
                                src="{{ $addOn->logo ? asset('/custom/app/img/clients/' . Auth::user()->companyID . '/' . $addOn->logo) : '' }}" 
                                alt="Logo" 
                                class="img-thumbnail cursor-pointer" 
                                style="max-width: 50px;" 
                                @click="$refs.fileInput.click()"
                            >
                            <!-- Show the preview if a new image is selected -->
                            <img 
                                x-show="selectedImageUrl"
                                :src="selectedImageUrl"
                                class="img-thumbnail cursor-pointer" 
                                style="max-width: 50px;"
                                @click="$refs.fileInput.click()"
                            >
                            <!-- Hidden file input -->
                            <input 
                                type="file" 
                                x-ref="fileInput"
                                wire:model="selectedImage" 
                                class="d-none"
                                @change="
                                    const file = $event.target.files[0];
                                    if (file) {
                                        selectedImageUrl = URL.createObjectURL(file);
                                    }
                                "
                            >
                        </div>


                            </td>
                            <td class="col-6">
                                <input type="text" class="form-control" value="{{ $addOn->name }}" wire:model="addonArray.{{ $addOn->id }}.name">
                            </td>
                            <td class="col-3">
                                <div class="row g-0">
                                    <div class="col-6">
                                        <button class="btn btn-link w-100 p-3" type="button" @click="$wire.update_addon({{ $addOn->id }})">
                                            <i class="fas fa-file text-primary fs-4"></i>
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button class="btn btn-link w-100 p-3" type="button" @click="$wire.delete_addon({{ $addOn->id }})">
                                            <i class="fas fa-times text-danger fs-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    <!-- Add New Row -->
                    <tr>
                        <td class="col-3">
                            <div x-data="{
                                selectedImageUrl: null,
                                selectedImage: $wire.entangle('selectedImage')
                            }" x-effect="if (!selectedImage) { selectedImageUrl = null }">
                                <div class="relative mt-2" style="max-width: 100px;">
                                    <template x-if="selectedImageUrl">
                                        <img 
                                            :src="selectedImageUrl"
                                            class="img-thumbnail w-full h-full transition-opacity duration-500 ease-in-out cursor-pointer"
                                            @click="$refs.newFileInput.click()"
                                        >
                                    </template>
                                </div>
                                <!-- Always visible Upload Logo button -->
                                <button 
                                    type="button"
                                    class="btn btn-primary mt-2"
                                    @click="$refs.newFileInput.click()"
                                >
                                    Upload Logo
                                </button>
                                <!-- Hidden file input for new addon -->
                                <input 
                                    type="file" 
                                    x-ref="newFileInput"
                                    wire:model="selectedImage" 
                                    class="d-none"
                                    @change="
                                        const file = $event.target.files[0];
                                        if (file) {
                                            selectedImageUrl = URL.createObjectURL(file);
                                        }
                                    "
                                >
                            </div>
                        </td>
                        <td class="col-6">
                            <input type="text" class="form-control" placeholder="Enter the name" wire:model="new_addon">
                        </td>
                        <td class="col-3">
                            <button class="btn btn-link w-100 p-3" type="button" @click="$wire.add_addon">
                                <i class="fas fa-plus text-success fs-4"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@script
    <script>
        Livewire.on('update_addonsTable', () => {
            // Scroll to the bottom of the table
            const table = document.querySelector('table');
            table.scrollTop = table.scrollHeight;
            alert("added")
        });
    </script>
@endscript