<?php

namespace App\Livewire\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
//use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class Admin extends Component
{
    use WithFileUploads; // Required for file uploa
    public $isItems=false;
    public $isMeals=false;
    public $isCategories=false;
    public $isSubCategories=false;
    public $isModifiers=false;
    public $isIngredients=false;
    public $isUsers=false;
    public $isReports=true;
    public $isMyAccount=false;

    public $companyID;

    public $items;  //results of items
    public $ingredients = []; //saved ingredients
    public $addons = [];  //saved addons
    public $item_id; //selected item id

    public $name_=""; //seleected item + meal  name
    public $cost_=0; // selectd item  + meal cost
    public $bgColor_=""; // selected item + meal bg Color
    public $logo_=""; // selected logo item + meal 
    public $ingredients_=[]; // selected ingtredients
    public $addons_=[];
    public $selectedImageUrl_=null; //put here so that we can clear selected image from livewire

    public $meals;  //results of meals
    public $meal_id; //selected item id
    public $subCategory=[]; // Everything subCategory
    public $meal_items=[];  // Everything meal_items
    public $subCategory_=[]; //Selected Subcategory offer
    public $meal_items_=[];  //selected meal items

    public $categoryArray=[]; //Everything category
    public $category_id; //selected category
    public $mealType_id =null; //selected mealTypeID
    public $position=[]; //selected postion for change
    public $maxValue; //No of categores
    public $json_=[]; //selected json
    public $categoryEntities_=[]; // these are items, sub category meals already selectedetc
    public $sortedData;
    public $position_keep=[];
    public $categoryItems=[]; //Everything items: independent of category
    public $categoryMeals=[]; //Everything meals independent of category
    public $subCategoryItems=[]; //Everything sub cateory of items: independent of category
    public $subCategoryMeals=[]; //Everything sub category of meals: independent of category

    public $subCategoryArray=[]; //Everything  subcategory
    public $subCategory_id; //selected subcategory
    public $subMealType_id =null; //selected submealTypeID


    public $ingredientArray=[];//Everything
    public $new_ingredient='';

    public $addonArray = [];// Everything
    public $new_addon = '';


    // Temporary upload property
    public $tempLogo;
    public $selectedImage = null;

    private function reset_admin(){
        $this->isItems=false;
        $this->isMeals=false;
        $this->isCategories=false;
        $this->isSubCategories=false;
        $this->isModifiers=false;
        $this->isIngredients=false;
        $this->isUsers=false;
        $this->isReports=false;
        $this->isMyAccount=false;
    }
    public function show_items(){
        $itemsArray=$this->get_itemDetails();
        $this->ingredients = DB::select("SELECT * FROM ingredientstable WHERE companyID = ?", [$this->companyID]);
        $this->addons = DB::select("SELECT * FROM addonstable WHERE companyID = ?", [$this->companyID]);

        $this->items=$itemsArray['items'];
        $this->reset_admin();
        $this->isItems=true;
    }
    public function update_menuItem(){
        // Get the record based on item_id
        //dump("hello");
        $record = $this->items->where('id', $this->item_id)->first();
        if ($record) {
            // Extract values from the record dataset made in items: so price is now cost
            $this->name_ = $record['name'];
            $this->cost_ = $record['cost'];
            $this->logo_ = $record['logo'];
            $this->bgColor_ = $record['bgColor'];
            $addons  = $record['json'];
            if ($addons !== null) {
                $addonsArray = json_decode($addons, true);
                if (isset($addonsArray['addons'])) {
                    $this->addons_ = $addonsArray['addons'];
                } else {
                    // Handle the case where 'addons' is not present in the JSON
                    $this->addons_ = []; // or any other default value
                }
            } else {
                // Handle the case where 'addons' is null
                $this->addons_ = []; // or any other default value
            }

            $ingredients_ = $record['ingredients_json'];
            if ($ingredients_ !== null) {
                $this->ingredients_ = json_decode($ingredients_, true);
            } else {
                // Handle the case where 'ingredients_' is null
                $this->ingredients_ = []; // or any other default value
            }
        }
    }

    public function save_item($jsonString){
        dump($jsonString);
    }
    public function show_meals(){
        $mealsArray=$this->get_mealDetails();
        $this->meals=$mealsArray['meals'];
        $this->subCategory = DB::select("SELECT * FROM itemsubfoldertable WHERE companyID = ?", [$this->companyID]);
        $this->meal_items = DB::select("SELECT * FROM itemtable WHERE companyID = ?", [$this->companyID]);

        $this->reset_admin();
        $this->isMeals=true;
    }
    public function update_menuMeal(){
        $record = $this->meals->where('id', $this->meal_id)->first();
        if ($record) {
            // Extract values from the record dataset made in items: so price is now cost
            $this->name_ = $record['name'];
            $this->cost_ = $record['cost'];
            $this->logo_ = $record['logo'];
            $this->bgColor_ = $record['bgColor'];
            $json  = $record['json'];
            if ($json !== null) {
                $jsonArray = json_decode($json, true);
                if (isset($jsonArray['item'])) {
                    $this->meal_items_ = $jsonArray['item'];
                } else {
                    // Handle the case where 'addons' is not present in the JSON
                    $this->meal_items_  = []; // or any other default value
                }
            } else {
                // Handle the case where 'addons' is null
                $this->meal_items_  = []; // or any other default value
            }

            if ($json !== null) {
                $jsonArray = json_decode($json, true);
                if (isset($jsonArray['itemsubfolder'])) {
                    $this->subCategory_ = $jsonArray['itemsubfolder'];
                } else {
                    // Handle the case where 'addons' is not present in the JSON
                    $this->subCategory_  = []; // or any other default value
                }
            } else {
                // Handle the case where 'addons' is null
                $this->subCategory_  = []; // or any other default value
            }
        }
    }

    public function show_categories(){
        $this->categoryArray=$this->get_categoryDetails();
        // Assume $data is passed from the controller containing the query result
        $this->sortedData = collect($this->categoryArray)->sortBy('position');
        // Initialize positions
        foreach ($this->sortedData as $index => $category) {
            $this->position[$index] =   $category['position'];
            $this->position_keep[$index] =$category['position'];
        }
        $this->reset_admin();
        $this->getCategoryEntities($this->mealType_id);
        $this->isCategories=true;
    }
    public function update_menuCategory(){
        $this->reset_admin_variables();
        $result = $this->sortedData->firstWhere('id', $this->category_id);
        if ($result){
            $this->name_=$result['name'];
            $this->logo_=$result['logo'];
            $this->bgColor_=$result['bgColor'];
            $this->mealType_id=$result['mealTypeID'];
            $json=$result['json'];
            $data = json_decode($json, true);
            if ($data !== null) {
                $firstKey = array_key_first($data);
                $this->categoryEntities_= $data[$firstKey];
            }
            $this->json_=$json;
            $this->update_categoryEntities($this->mealType_id);
        }
    }
    public function update_categoryEntities($mealTypeID){
        $this->getCategoryEntities($mealTypeID);
    }
    public function save_category(){
        // Determine JSON key based on mealType_id
        $key = match($this->mealType_id) {
            1,'1' => 'item',
            3,'3' => 'meal',
            2,4,'2', '4' => 'itemsubfolder',
        };
        // Build the array data
        $arrayData = [
            'name' => $this->name_,
            'json' => json_encode([$key => $this->categoryEntities_]), // Laravel will automatically JSON encode
            'mealTypeID' => $this->mealType_id,
        ];
        // Conditionally add optional fields
        if ($this->bgColor_) {
            $arrayData['bgColor'] = $this->bgColor_;
        }
        if ($this->logo_) {
            $arrayData['logo'] = $this->logo_;
        }
        // Handle insert/update operations
        if (!$this->category_id) {
            // Add position only for new entries
            $arrayData['position'] = count($this->sortedData) + 1;
            DB::table('itemfoldertable')->insert($arrayData);
        } else {
            DB::table('itemfoldertable')
                ->where('id', $this->category_id)
                ->update($arrayData);
        }
        $this->categoryArray=$this->get_categoryDetails();
        // Assume $data is passed from the controller containing the query result
        $this->sortedData = collect($this->categoryArray)->sortBy('position');
        foreach ($this->sortedData as $index => $category) {
            $this->position[$index] =   $category['position'];
            $this->position_keep[$index] =$category['position'];
        }
    }

    public function getCategoryEntities($menuTypeID){
        if ($menuTypeID==1){
            $this->categoryItems= $this->get_categoryEntity("itemtable");
        }else if ($menuTypeID==3){
            $this->categoryMeals= $this->get_categoryEntity("mealtable");
        }else if ($menuTypeID==2){
            $this->subCategoryItems= $this->get_subCategoryEntity("item");
        }else if ($menuTypeID==4){
            $this->subCategoryMeals= $this->get_subCategoryEntity("meal");
        }
    }

    public function show_subCategories(){
        $this->subCategoryArray=$this->get_subCategoryDetails();
        $this->reset_admin();
        $this->isSubCategories=true;
    }
    public function update_menuSubCategory(){
        $this->reset_admin_variables();
        $this->subCategoryArray=$this->get_subCategoryDetails();
        $subCategoryCollection=collect($this->subCategoryArray);
        $result = $subCategoryCollection->firstWhere('id', $this->subCategory_id);
        if ($result){
            $this->name_=$result['name'];
            $this->logo_=$result['logo'];
            $this->bgColor_=$result['bgColor'];
            $this->subMealType_id=$result['subMealTypeID'];
            $json=$result['json'];
            $data = json_decode($json, true);
            if ($data !== null) {
                $firstKey = array_key_first($data);
                $this->categoryEntities_= $data[$firstKey];
            }
            $this->json_=$json;
            $this->update_subCategoryEntities($this->subMealType_id);
        }
    }
    public function update_subCategoryEntities($subMealTypeID){
        $this->getSubCategoryEntities($subMealTypeID);
    }
    public function getSubCategoryEntities($subMenuTypeID){
        if ($subMenuTypeID==1){
            $this->categoryItems= $this->get_categoryEntity("itemtable");
        }else if ($subMenuTypeID==2){
            $this->categoryMeals= $this->get_categoryEntity("mealtable");
        }
    }

    public function save_subCategory(){
        // Determine JSON key based on mealType_id
        $key = match($this->subMealType_id) {
            1,'1' => 'item',
            2,'2' => 'meal',
       };
        // Build the array data
        $arrayData = [
            'name' => $this->name_,
            'json' => json_encode([$key => $this->categoryEntities_]), // Laravel will automatically JSON encode
            'subMealTypeID' => $this->subMealType_id,
        ];

        // Conditionally add optional fields
        if ($this->bgColor_) {
            $arrayData['bgColor'] = $this->bgColor_;
        }
        if ($this->logo_) {
            $arrayData['logo'] = $this->logo_;
        }
        // Handle insert/update operations
        if (!$this->subCategory_id) {
             DB::table('itemsubfoldertable')->insert($arrayData);
        } else {
            DB::table('itemsubfoldertable')
                ->where('id', $this->subCategory_id)
                ->update($arrayData);
        }
        $this->subCategoryArray=$this->get_subCategoryDetails();
        
    }

    public function show_ingredients(){
        $this->reset_admin();
        $this->isIngredients=true;
        $this->load_Ingredients();
    }
    public function show_modifiers(){
        $this->reset_admin();
        $this->isModifiers=true;
        $this->load_addons();
    }
    public function show_users(){
        $this->reset_admin();
        $this->isUsers=true;
    }
    public function show_reports(){
        $this->reset_admin();
        $this->isReports=true;
    }
    public function show_myAccount(){
        $this->reset_admin();
        $this->isMyAccount=true;
    }
    public function logout(){
        $this->reset_admin();
    }

    public function update_ingredient($id){
        $name = $this->ingredientArray[$id]['name'] ?? null;
        if ($name) {
            DB::table("ingredientstable")
                ->where('id', $id)
                ->update(['name' => $name]);
        }
        $this->dispatch('update_ingredientsTable');
    }
    public function load_ingredients(){
            $this->ingredientArray = DB::table("ingredientstable")
            ->where('companyID', $this->companyID)
            ->get()
            ->keyBy('id')
            ->toArray();
    }
    public function delete_ingredient($id){
        DB::table("ingredientstable")
            ->where('id', $id)
            ->delete();
        unset($this->ingredientArray[$id]);    
        $this->load_ingredients();
    }
    public function add_ingredient(){
        if (!empty($this->new_ingredient)) {
            DB::table("ingredientstable")->insert(['name' => $this->new_ingredient, 'companyID' => auth()->user()->companyID]);
            $this->new_ingredient = '';
            $this->load_ingredients();
            $this->dispatch('update_ingredientsTable');
        }
    }

    public function add_addon(){
        if (!empty($this->new_addon)) {
            // Save the image if one is selected
            $this->save_image('image_saved_for_addon');

            // Insert the new addon into the database
            DB::table("addonstable")->insert([
                'name' => $this->new_addon,
                'logo' => $this->logo_,
                'companyID' => $this->companyID,
            ]);

            // Clear input fields
            $this->new_addon = '';
            $this->logo_ = null;
            // Existing logic to add the addon
            $this->selectedImage = null; // Reset the image after adding

            // Refresh addons list
            $this->load_addons();

            // Trigger table update event
            $this->dispatch('update_addonsTable');
        }
    }
    public function load_addons(){
        $this->addonArray = DB::table("addonstable")
        ->where('companyID', $this->companyID)
        ->get()
        ->keyBy('id')
        ->toArray();
    }
    public function update_addon($id){
        $addon = $this->addonArray[$id] ?? null;
        if ($addon) {
            // Save the image if one is selected
            $this->save_image('image_saved_for_update');
            // Update the addon in the database
            DB::table("addonstable")
                ->where('id', $id)
                ->update([
                    'name' => $addon->name,
                    'logo' => $this->logo_,
                ]);
            // Clear input fields
            $this->logo_ = null;
            // Refresh addons list
            $this->load_addons();
            // Trigger table update event
            $this->dispatch('update_addonsTable');
        }
    }
    public function delete_addon($id){
        DB::table("addonstable")
            ->where('id', $id)
            ->delete();
        unset($this->addonArray[$id]);
        $this->load_addons();
        $this->dispatch('update_addonsTable');
    }

     



    public function save_image($imageMsg){
        if (!$this->selectedImage) {
            return;
        }
        $this->validate([
            'selectedImage' => 'image|max:1024', // Max 1MB
        ]);
        try {
            // Get company ID
            $companyID = Auth::user()->companyID;
            // Define the directory path
            $directory = public_path("custom/app/img/clients/{$companyID}");
              // Create directory if it doesn't exist - with proper permissions
            if (!File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }
            // Get original filename and sanitize it
            $originalFilename = $this->selectedImage->getClientOriginalName();
            // Remove potentially problematic characters
            $safeFilename = preg_replace('/[^a-zA-Z0-9_.-]/', '', $originalFilename);
            // Store the file temporarily using Livewire's built-in method
            $tempPath = $this->selectedImage->getRealPath();
            // Copy the file to the destination
            File::copy($tempPath, $directory . '/' . $safeFilename);
            // Update logo property with the filename
            $this->logo_ = $safeFilename;
            // Clear the temporary upload
            $this->selectedImage = null;
            // Dispatch success event (in Livewire v3 style)
            $this->dispatch($imageMsg);

        } catch (\Exception $e) {
            // Flash error message in Livewire v3 style
            $this->dispatch('error_'.$imageMsg, [
                'type' => 'error',
                'message' => 'Failed to save image: ' . $e->getMessage()
            ]);
            //dump( $e->getMessage());
        }
    }
    public function mount(){
        $this->companyID=Auth::user()->companyID;
    }
    public function render()
    {
        return view('livewire.admin.admin');
    }

    private function get_itemDetails(){
        $companyID = $this->companyID;
        $items = DB::table('itemtable')
            ->where('companyID', $companyID)
            ->get()
            ->map(function ($item) use ($companyID) {
                // Decode JSON fields
                $itemJson = json_decode($item->json, true);
                $ingredientJson = json_decode($item->ingredients_json, true);

                // Fetch categories
                $categories = DB::table('itemfoldertable')
                    ->where('companyID', $companyID)
                    ->get()
                    ->filter(function ($folder) use ($item) {
                        $json = json_decode($folder->json, true);
                        return isset($json['item']) && in_array($item->id, $json['item']);
                    })
                    ->map(function ($folder) {
                        return [
                            'id' => $folder->id,
                            'name' => $folder->name,
                        ];
                    });

                // Fetch subcategories
                $subcategories = DB::table('itemsubfoldertable')
                    ->where('companyID', $companyID)
                    ->get()
                    ->filter(function ($subfolder) use ($item) {
                        $json = json_decode($subfolder->json, true);
                        return isset($json['item']) && in_array($item->id, $json['item']);
                    })
                    ->map(function ($subfolder) {
                        return [
                            'id' => $subfolder->id,
                            'name' => $subfolder->name,
                        ];
                    });

                // Fetch addons
                $addons = collect();
                if (isset($itemJson['addons']) && is_array($itemJson['addons'])) {
                    $addons = DB::table('addonstable')
                        ->whereIn('id', $itemJson['addons'])
                        ->get()
                        ->map(function ($addon) {
                            return [
                                'id' => $addon->id,
                                'name' => $addon->name,
                            ];
                        });
                }

                // Fetch ingredients
                $ingredients = collect();
                if (is_array($ingredientJson)) {
                    $ingredientIds = array_keys($ingredientJson);
                    $ingredients = DB::table('ingredientstable')
                        ->whereIn('id', $ingredientIds)
                        ->get()
                        ->map(function ($ingredient) use ($ingredientJson) {
                            return [
                                'id' => $ingredient->id,
                                'name' => $ingredient->name,
                                'value' => $ingredientJson[$ingredient->id] ?? null,
                            ];
                        });
                }

                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'json' => $item->json,
                    'ingredients_json' => $item->ingredients_json,
                    'cost' => number_format($item->price,2),
                    'logo'=>$item->logo,
                    'bgColor'=>$item->bgColor,
                    'categories' => $categories->toArray(),
                    'subcategories' => $subcategories->toArray(),
                    'addons' => $addons->toArray(),
                    'ingredients' => $ingredients->toArray(),
                ];
            });
        return  compact('items');
    }
    private function get_mealDetails(){
        $companyID = $this->companyID;
        $meals = DB::table('mealtable')
            ->where('companyID', $companyID)
            ->get()
            ->map(function ($meal) use ($companyID) {
                // Decode JSON fields
                $mealJson = json_decode($meal->json, true);

                // Fetch categories
                $categories = DB::table('itemfoldertable')
                    ->where('companyID', $companyID)
                    ->get()
                    ->filter(function ($folder) use ($meal) {
                        $json = json_decode($folder->json, true);
                        return isset($json['meal']) && in_array($meal->id, $json['meal']);
                    })
                    ->map(function ($folder) {
                        return [
                            'id' => $folder->id,
                            'name' => $folder->name,
                        ];
                    });

                // Fetch subcategories
                $subcategories = DB::table('itemsubfoldertable')
                    ->where('companyID', $companyID)
                    ->get()
                    ->filter(function ($subfolder) use ($meal) {
                        $json = json_decode($subfolder->json, true);
                        return isset($json['meal']) && in_array($meal->id, $json['meal']);
                    })
                    ->map(function ($subfolder) {
                        return [
                            'id' => $subfolder->id,
                            'name' => $subfolder->name,
                        ];
                    });

                return [
                    'id' => $meal->id,
                    'name' => $meal->name,
                    'json' => $meal->json,
                    'cost' => number_format($meal->price,2),
                    'logo'=>$meal->logo,
                    'bgColor'=>$meal->bgColor,
                    'categories' => $categories->toArray(),
                    'subcategories' => $subcategories->toArray(),
                ];
            });
        return  compact('meals');
    }
    private function get_categoryDetails(){
        $results = DB::table('itemfoldertable')
            ->where('companyID', $this->companyID)
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();
        $this->maxValue=count($results);
        return $results;
    }
    private function get_subCategoryDetails(){
        $results = DB::table('itemsubfoldertable')
            ->where('companyID', $this->companyID)
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();
        return $results;
    }

    public function updatedPosition($value, $index){
        // If the entered value is out of range, revert to the original
        if ($value < 1 || $value > $this->maxValue) {
            $this->position[$index] = $this->position_keep[$index];
            return 0;
        }
        //new value $value
        //original value  $this->position_keep[$index]
        //dump($this->sortedData);
       // return 0;
       $this->sortedData= $this->updatePositions(
            $this->sortedData,
            $this->position_keep[$index],
            $value
        );
       foreach ($this->sortedData as $index => $category) {
            $this->position[$index] = $category['position'];
            $this->position_keep[$index] = $category['position'];
        }
    }

    private function updatePositions(Collection $collection, $origValue, $newValue){
        // Ensure the collection is sorted by the current position
        $collection = $collection->sortBy('position');
        // Find the item that needs to be moved
        $itemToMove = $collection->firstWhere('position', $origValue);
        if (!$itemToMove) {
            return $collection; // Item not found, return the original collection
        }
        // Determine the new position is the max position
        $maxPosition = count($collection);
        if ($newValue == $maxPosition) {
            // Assign the itemToMove's position to the max
            $collection->transform(function ($item) use ($itemToMove, $maxPosition) {
                if ($item === $itemToMove) {
                    $item['position'] = $maxPosition;
                } else if ($item['position'] > $itemToMove['position']) {
                    $item['position'] -= 1;
                }
                return $item;
            });
        } else {
            // Update positions normally if newValue is not the max position
            if ($newValue < $origValue) {
                // Moving up: Increment positions of items between newValue and origValue
                $collection->transform(function ($item) use ($newValue, $origValue) {
                    if ($item['position'] >= $newValue && $item['position'] < $origValue) {
                        $item['position'] += 1;
                    }
                    return $item;
                });
            } elseif ($newValue > $origValue) {
                // Moving down: Decrement positions of items between origValue and newValue
                $collection->transform(function ($item) use ($newValue, $origValue) {
                    if ($item['position'] > $origValue && $item['position'] <= $newValue) {
                        $item['position'] -= 1;
                    }
                    return $item;
                });
            }
            // Update the position of the item being moved within the collection
            $collection->transform(function ($item) use ($itemToMove, $newValue) {
                if ($item === $itemToMove) {
                    $item['position'] = $newValue;
                }
                return $item;
            });
        }
        // Re-sort the collection by position
        $collection = $collection->sortBy('position');
        return $collection;
    }
    private function get_categoryEntity($table){
        $result= DB::table($table)
        ->where('companyID', $this->companyID)
        ->get()
        ->keyBy('id')
        ->toArray();
        return $result;
    }
    private function get_subCategoryEntity($key){
        $result= DB::table("itemsubfoldertable")
        ->where('companyID', $this->companyID)
        ->when($key, function ($query) use ($key) {
            $query->whereRaw('JSON_EXTRACT(json, "$.'.$key.'") IS NOT NULL');
        })
        ->get()
        ->keyBy('id')
        ->toArray();
        return $result;
    }
    public function reset_admin_variables(){
        $this->name_=""; //seleected item + meal  name
        $this->cost_=0; // selectd item  + meal cost
        $this->bgColor_=""; // selected item + meal bg Color
        $this->json_=[]; //selected json
        $this->categoryEntities_=[];//already selected
        $this->mealType_id=null;
        $this->subMealType_id=null;
        $this->clearLogo();
        /*
        $this->ingredients_=[]; // selected ingtredients
        $this->addons_=[];
        $this->subCategory_=[]; //Selected Subcategory offer
        $this->meal_items_=[];  //selected meal items
        */
    }
    public function clearLogo(){
        $this->selectedImageUrl_=null;
        $this->logo_=""; // selected logo item + meal 
    }
}
