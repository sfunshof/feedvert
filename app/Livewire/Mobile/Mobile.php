<?php

namespace App\Livewire\Mobile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Livewire\Component;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Cache;

class Mobile extends Component
{
    public $user;
    public $items;
    public $error;

    public $isSplash=false;
    public $isMenu=false;
    public $isError=false;    
    public $isSubMenu=false;
    public $isSubMeal=false;
    public $isMeal=false;
    public $isItem=false;
    public $isItemDetails=false;
    public $isMealDetails=false;
    public $isMealOption_show=false;
    public $isFooter=false;
    public $isAddToOrder=false;
    public $isCheckout=false; // 1st part
    public $isCheckout_payment=false; //to show checkout and payment 
    public $isPayment=false; //payment form
    public $isPayment_cash=false;
    public $isPayment_mobile=false;
    public $isOrderCollectionMethod=false;

    
    public $isCustomise=false;
    public $itemAddOns=[];
    public $addOnResult=[];
    public $addOnStates=[];
    public $meal_addOnStates=[];
    public $addOnArray=[];
    public $addOnArray3D=[];
    public $addOnArrayLogo=[];
    public $meal_addOnArray=[];
    public $meal_addOnArray3D=[];
    public $meal_addOnArrayLogo=[];
    public $meal_itemsArray=[];
    public $customise_item="";
    public $customised_itemID=-1;
    public $customise_itemLogo="";

    public $itemList=[];
    public $mealList=[];
    public $subMenuList=[];
    public $menuList=[]; //not used 
    public $subMealList=[];
    public $mealOptionList=[];
    public $cart=[];
    public $mealOption_logos=[]; // selected logos
    public $mealOption_names=[];  //sekected names
    public $mealOption_ids=[];   //selected id
    public $mealOptions=[]; //This is the 1s "select drink etc
    public $mealOption_count=0; //No of meal options
    public $isMealCounter=false; //f to show counter or not
  
    public $collectionMethodTitle="Please Select";
    public $orderTitle="My Order";
    public $eat_in;
    public $take_away;
    public $collectionMethod;

    public $totalCost=0;
    public $totalOrder=0;
    public $totalTax=0;
    public $orderNo="";

    public $goBackMenuTypeID; //1,2,3
    public $isBackBtn=false;
    public $isCloseBtn=false;

    public $itemName;
    public $mealName;
    public $subMenuName;
    public $menuName;
    public $mealTypeID;
    public $itemParentName; //this item came from a parent  - menu or submenu changes
    public $backMenuName="";
    public $mainMenuName=""; //does not change
    public $OldParentNameChangedByOrder;
    public $oldParentNameChangedByOption;
    public $oldParentNameChangedByPayment;
    public $oldParentNameChangedByCheckout;
    public $optionSelectPosition=0;
    public $oldItemQtyChangedByOptionClose=0;

    public $paymentRef;
 
    public  $itemDetails;
    public  $itemOrder=0; //individual order
    public  $companyID=-1;
    public  $currency="";
    public  $companyName="";
    public  $discount=0;
    public  $tax;
    public  $timezone;

    //Polling
    public $orderPolling="orderPolling";
    public $refPolling="refPolling";

    private function initMealOption(){
        $this->mealOption_logos=[]; // selected logos
        $this->mealOption_names=[];  //sekected names
        $this->mealOption_ids=[];   //selected id
        $this->mealOptions=[]; //This is the 1s "select drink etc
        $this->mealOption_count=0; //No of meal options
    }

    private function resetAll(){
        $this->isSplash=false;
        $this->isMenu=false;
        $this->isError=false;
        $this->isSubMenu=false;
        $this->isSubMeal=false;
        $this->isMeal=false;
        $this->isItem=false;
        $this->isMealCounter=false;
        $this->isMealOption_show=false;
        $this->isCloseBtn=false;
        $this->isPayment=false;
        $this->isPayment_cash=false;
        $this->isPayment_mobile=false;
        //$this->isCheckout=false;
        //$this->isCheckout_payment=false;
        $this->isItemDetails=false;
        $this->isMealDetails=false;
        $this->isFooter = count($this->cart) > 0;
        $this->isOrderCollectionMethod=false;
    }
    public function show_menu(){
        $this->resetAll();
        $this->resetFooter();
        $this->isMenu=true;
        $this->isBackBtn=false;
        $this->itemParentName=$this->mainMenuName;
        $this->isCheckout=true;
    }
    public function init_menu(){
        $this->show_menu();
        $this->cart=[];
        $this->isCheckout=false;
    }
    public function show_subMenu(){
        $this->resetAll();
        $this->isSubMenu=true;
        $this->isBackBtn=true;
        $this->goBackMenuTypeID=2;
        $this->resetFooter();
        $this->isCheckout=true;
    }
    public function show_meal(){
        $this->resetAll();
        $this->isMeal=true;
        $this->isBackBtn=true;
        $this->resetFooter();
        $this->isCheckout=true;
    }
    public function show_subMeal(){
        $this->resetAll();
        $this->isSubMeal=true;
        $this->isBackBtn=true;
        $this->resetFooter();
        $this->isCheckout=true;
     }
    public function show_item(){
        $this->resetAll();
        $this->isItem=true;
        $this->isBackBtn=true;
        $this->resetFooter();
        $this->isCheckout=true;
    }
    public function show_itemDetails(){
        $this->resetAll();
        $this->isItemDetails=true;
        $this->isBackBtn=true;
        $this->itemOrder=1;
        $this->show_addToOrder();
        $this->isAddToOrder=true;
    } 
    public function show_mealDetails(){
        $this->resetAll();
        $this->isMealDetails=true;
        $this->isBackBtn=true;
        $this->itemOrder=1;
        
        $this->show_addToOrder();
        if (count($this->mealOption_logos) == $this->mealOption_count) {
            $this->isAddToOrder = true;
            $this->isMealCounter = true;
        } elseif (count($this->mealOption_logos) < $this->mealOption_count) {
            $this->isMealCounter = false;
        }
    } 
    
    public function show_mealOption(){
        $this->resetAll();
        $this->isMealOption_show=true;
        $this->isBackBtn=false;
        $this->isCloseBtn=true;
        $this->resetFooter();
    }
    
    public function show_payment(){
        $this->oldParentNameChangedByPayment=$this->itemParentName;
        $this->itemParentName="Payment";
        $this->resetAll();
        $this->isPayment=true;
        $this->isBackBtn=false;
        $this->isCloseBtn=true;
        $this->resetFooter();
    }
    public function show_orderCollectionMethod(){
        $this->oldParentNameChangedByPayment=$this->itemParentName;
        $this->itemParentName=$this->collectionMethodTitle;
        $this->resetAll();
        $this->isOrderCollectionMethod=true;
        $this->isBackBtn=false;
        $this->isCloseBtn=true;
        $this->resetFooter();
    }
    public function set_eatIn(){
        $this->collectionMethod=$this->eat_in;
        $this->show_payment();
    }
    public function set_takeAway(){
        $this->collectionMethod=$this->take_away;
        $this->show_payment();
    }

    public function show_payment_cash(){ 
        $this->resetAll();
        $this->resetFooter();
        $this->isBackBtn=false;
        $this->isCloseBtn=false;
        $this->isPayment_cash=true;
        $mobileData=$this->mobile_receiptItems();
        $ingredientData=$this->get_ingredients();
         $appController = app(AppController::class);
        $this->orderNo=$appController->get_orderNo($mobileData, [], $ingredientData, $this->companyID, $this->timezone,0);
        $this->itemParentName="Complete Cash Payment";
    }

    public function show_payment_mobile(){ 
        $this->resetAll();
        $this->resetFooter();
        $this->isBackBtn=false;
        $this->isCloseBtn=false;
        $this->isPayment_mobile=true;
        $mobileData=$this->mobile_receiptItems();
        $ingredientData=$this->get_ingredients();
        $appController = app(AppController::class);
        $this->orderNo=$appController->get_orderNo($mobileData, [], $ingredientData, $this->companyID, $this->timezone,0);
        $this->itemParentName="Complete Mobile Payment";
        $currentDateTime = Carbon::now($this->timezone);
        DB::table('payrefstable')->insert([
            'companyID' => $this->companyID,
            'dateTime' => $currentDateTime->format('Y-m-d H:i:s'),
            'orderNo' => $this->orderNo,
            'refNo' => $this->paymentRef,
            'amount' => $this->totalCost
        ]);
        $this->syncPaymentReference();
        $this->paymentRef="";
        //dump($this->paymentRef);
    }
    public function close_payment(){
        $this->resetAll();
        $this->resetFooter();
        $this->isCheckout_payment=true;
        $this->isCloseBtn=false;
        $this->itemParentName=$this->orderTitle;
        
    }

    private function resetFooter(){
        $this->isAddToOrder=false;
        $this->isCheckout=false;
        $this->isCheckout_payment=false;
    }
    private function show_addToOrder(){
        $this->resetFooter();
        //$this->isAddToOrder=true;
        $this->isFooter=true;
     }
     //This the checkout btn click
    private function show_checkout(){
        $this->resetFooter();
        $this->resetAll();
        $this->isCheckout_payment=true;
        $this->isFooter=true;
        $this->oldParentNameChangedByCheckout=$this->itemParentName;
        $this->itemParentName="My Order";
    }

    public function goBackMenu(){
        $typeID=$this->goBackMenuTypeID; //go back to menu, submenu, submeal
        if ($typeID==1){ //Back to Menu
            $this->show_menu();
        }elseif ($typeID==2){ //Back to SubMenu
            $this->show_subMenu();
            $this->goBackMenuTypeID=1;
            $this->backMenuName="< " .  $this->mainMenuName;
        }elseif ($typeID==3){ //Back to subMeal
            $this->show_subMeal();
            $this->goBackMenuTypeID=1;
            $this->backMenuName="< " .  $this->mainMenuName;
        }elseif ($typeID==100){ //Back to subMenu or item
           if ($this->mealTypeID==1) {
                $this->show_item();
           }elseif ($this->mealTypeID==2){
                $this->show_subMeanu();
           }    
            $this->backMenuName="< " .  $this->mainMenuName;
            //Reset based on typeID
            $this->goBackMenuTypeID=$this->mealTypeID; //1 or 2
         } elseif ($typeID==200){ //Back to subMeal or meal
            $this->show_meal();
            $this->backMenuName="< " .  $this->mainMenuName;
            //Reset based on typeID
            if ($this->mealTypeID==3){
                $this->goBackMenuTypeID=1;
            }elseif ($this->mealTypeID==4){
                $this->goBackMenuTypeID=3;
            }
        }
        if ($this->itemParentName==$this->orderTitle){
            $this->itemParentName=$this->OldParentNameChangedByOrder;
        }else {
            //$this->itemParentName="Order";
        }
        
    }

    public function get_mealsFromMenu( $key, $json,$menuName,$mealTypeID, $v){
        $tableName=$key . "table";
        $companyID = $this->companyID; // Replace with your actual company ID
        $results = DB::table($tableName)
                ->where('companyID', $companyID)
                ->whereIn( 'id', $json)
                ->get();

        //$this->itemParentName=$menuName;
        $this->backMenuName="< " . $this->itemParentName;
        $this->itemParentName=$menuName;
        $this->goBackMenuTypeID=$v;
        $this->mealTypeID=$mealTypeID;

        //pure item
        if ($mealTypeID==1){
            $this->itemList=$results;
            $this->show_item();
        }elseif ($mealTypeID==2){ //sub menu pure item
            $this->subMenuList=$results;
            $this->show_subMenu();
        }elseif ($mealTypeID==3){ //meal
            $this->mealList=$results;
            $this->show_meal();
        }elseif ($mealTypeID==4){ //sub meal
            $this->subMealList=$results;
            $this->show_subMeal();
        }
    }   
    
    private function getItemSubfolderOptions($companyID, $idArray){
        $mealOptions = DB::table('itemsubfoldertable')
        ->where('companyID', $companyID)
        ->whereIn('id', $idArray)
        ->get()
        ->toArray();
        return $mealOptions;
    }

    private function getItemSubfolderValue($jsonString){
        // Decode the JSON string into an associative array
        $data = json_decode($jsonString, true);
        // Return the value of the 'itemsubfolder' key if it exists
        return $data['itemsubfolder'] ?? null;
    }

    private function getJsonStatus($json): int {
        // If the JSON field is null, return 1
        if (is_null($json)) {
            return 1;
        }
        // Attempt to decode the JSON field
        $decoded = json_decode($json, true);
        // Check for JSON decoding errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Log the JSON error for debugging purposes
             // Depending on requirements, you might choose to return a default value or throw an exception
            // Here, we'll return a default value of 0 to indicate an error
            return 0;
        }
        // Ensure the decoded JSON is an array
        if (!is_array($decoded)) {
             // Return a default value
            return 0;
        }
        // Check for the presence of specific keys
        if (array_key_exists('addons', $decoded)) {
            return 1;
        }
        if (array_key_exists('item', $decoded)) {
            return 2;
        }
        // If none of the keys are present, return a default value
        // Adjust this as per your requirements
        return 0;
    }

    public function addToOrderFunc(){ 
        $this->itemDetails->qty=$this->itemOrder;
        //These are options
        $this->itemDetails->optionNames=$this->mealOption_names;
        $this->itemDetails->optionLogos=$this->mealOption_logos;
        $this->itemDetails->optionIDs=$this->mealOption_ids;
        $this->itemDetails->addOnArray=$this->addOnArray;
        $this->itemDetails->addOnArray3D=$this->addOnArray3D;
        $this->itemDetails->addOnArrayLogo=$this->addOnArrayLogo;

        array_push($this->cart, $this->itemDetails);
        $this->updateCart();
        $this->show_menu();
        //dump($this->cart);
    }
    public function checkoutFunc(){
       //array_push($this->cart, $this->itemDetails);
        $this->show_checkout();
    }
    public function updateQuantity($index, $newQty){
        // Update the quantity for the specific item
        $this->cart[$index]->qty = $newQty;
        // Recalculate totals
        $this->updateCart();
    }
    private function  updateCart(){
        $totalCost=0;
        $totalOrder=0;
        foreach ($this->cart as $obj) {
            $totalCost += $obj->price *  $obj->qty;  // Add price to total
            $totalOrder += $obj->qty;  // Add order to total

        }
        $this->totalOrder=$totalOrder;
        $this->totalTax = number_format($totalCost *  $this->tax,2); 
        $totalCost = $totalCost + $this->totalTax;
        $this->totalCost= number_format($totalCost, 2);
        //dump($this->cart);
    }

    public function removeItem($index){
        array_splice($this->cart, $index, 1);
        $this->updateCart();
        if (count($this->cart)==0){
            $this->emptyCart();
        }
    }
    public function emptyCart(){
       $this->show_menu();
    }
    private function convert_mobileDataToCashier(array $form1){
        $form2 = [];
        foreach ($form1 as $index => $item) {
            // Add as associative array with numeric string keys
            $form2[(string)$index] = $item;
        }
        return  json_encode($form2);
    }
    private function mobile_receiptItems(){
        $processedArray = [];

        foreach ($this->cart as $item) {
            $processedItem = [
                'name'  => $item->name,
                'price' => $item->price,
                'qty'   => $item->qty,
                'mealTypeID' =>$item->mealTypeID
            ];

            if (in_array($item->mealTypeID, [1, 2])) {
                // Process addOnArray and addOnArray3D
                $addOns3D = [];
                foreach ($item->addOnArray as $key => $value) {
                    if ($value == 0) {
                        $addOns3D[] = "No " . $item->addOnArray3D[$key];
                    } elseif ($value == 2) {
                        $addOns3D[] = "Extra " . $item->addOnArray3D[$key];
                    }
                }
                if (!empty($addOns3D)) {
                    $processedItem['addOns3D'] = $addOns3D;
                }
            }

            if (in_array($item->mealTypeID, [3, 4])) {
                $processedItem['offers'] = array_values($item->optionNames);

                // Process meal_items_addOns3D
                $mealItemsAddOns = [];
                foreach ($item->meal_itemsArray as $key => $mealName) {
                    $filteredAddOns = [];
                    if (isset($item->meal_addOnArray[$key])) {
                        foreach ($item->meal_addOnArray[$key] as $addonKey => $value) {
                            if ($value == 0) {
                                $filteredAddOns[] = "No " . $item->meal_addOnArray3D[$key][$addonKey];
                            } elseif ($value == 2) {
                                $filteredAddOns[] = "Extra " . $item->meal_addOnArray3D[$key][$addonKey];
                            }
                        }
                    }
                    if (!empty($filteredAddOns)) {
                        $mealItemsAddOns[$mealName] = $filteredAddOns;
                    }
                }

                if (!empty($mealItemsAddOns)) {
                    $processedItem['meal_items_addOns3D'] = $mealItemsAddOns;
                }
            }
            $processedArray[] = $processedItem;
        }
        $processedArray['payment_method']="";
        $processedArray['amount_tendred']=0;
        $processedArray['total_cost']= 0;
        $processedArray['change_given']=0;
        $processedArray['currency']=$this->currency;
        $processedArray['cashier_id']= "";
        $processedArray['cashier_name']= "" ;
        $processedArray['medium']="Mobile";
        $processedArray['collection_method']=$this->collectionMethod;

        return $processedArray;
    }

    private function get_ingredients(){
         $ingredientsArray = [];
        foreach ($this->cart as $item) {
            $qty = $item->qty;
            $idArray = [];
            if (in_array($item->mealTypeID, [1, 2])) {
                $idArray[] = $item->id;
            } elseif (in_array($item->mealTypeID, [3, 4])) {
                $idArray = array_values($item->meal_items_id);
                $optionIDs = array_values($item->optionIDs);
                $idArray=array_merge($idArray,$optionIDs);

            }
            // Fetch ingredients_json from itemtable
            $ingredients = DB::table('itemtable')
                ->whereIn('id', $idArray)
                ->pluck('ingredients_json', 'id')
                ->toArray();
        
            // Multiply ingredient values by quantity and consolidate
            foreach ($ingredients as $json) {
                $ingredientData = json_decode($json, true);
        
                foreach ($ingredientData as $ingredientId => $value) {
                    if (!isset($ingredientsArray[$ingredientId])) {
                        $ingredientsArray[$ingredientId] = 0;
                    }
                    $ingredientsArray[$ingredientId] += $value * $qty;
                }
            }
        }
        return $ingredientsArray;
    }

    public function showItemDetails($id, $price, $name,$logo, $json){
        $meal_items_id=[];
        $this->initMealOption();
        $this->backMenuName="< " .  "Back";
        if ($this->getJsonStatus($json) < 2){ //0,1  null and addons as ky
            $this->goBackMenuTypeID=100;
        }elseif ($this->getJsonStatus($json) == 2){ // 2 is meal
            $this->goBackMenuTypeID=200;
            //get the meal items here. This is a fix/hack. when the meal is customised
            //meal_itemArray will swap key=>value. Not wanting to touch it we define a new variable
            //to bypass it
            $meal_items_id= json_decode($json, true)['item'];
        }

        $itemDetails= new \stdClass();
        $itemDetails->logo=$logo;
        $itemDetails->price=number_format($price,2);
        $itemDetails->id=$id;
        $itemDetails->name=$name;
        $itemDetails->meal_addOnArray=$this->meal_addOnArray;
        $itemDetails->meal_itemsArray=$this->meal_itemsArray;
        $itemDetails->meal_items_id=$meal_items_id;

        //$itemDetails->typeID= ($this->goBackMenuTypeID/100);
        $itemDetails->mealTypeID=$this->mealTypeID;
        $itemDetails->qty=$this->itemOrder;
        $this->itemDetails=$itemDetails;
        $this->OldParentNameChangedByOrder=$this->itemParentName;
        $this->itemParentName=$this->orderTitle;
        if ($this->goBackMenuTypeID==100){
            $this->show_itemDetails();
        }elseif ($this->goBackMenuTypeID==200){
           $values=$this->getItemSubfolderValue($json);//[1,3] from mealtable
           $this->mealOption_count=count($values);
           $this->mealOptions = $this->getItemSubfolderOptions($this->companyID, $values);
           $this->show_mealDetails();
        }
        $this->addOnArray=[];
        $this->addOnArray3D=[];
        $this->addOnArrayLogo=[];
        $this->meal_addOnArray=[];
        $this->meal_addOnArray3D=[];
        $this->meal_addOnArrayLogo=[];
        $this->customise_item="";

        //*** No longer needed  */
        $this->itemAddOns= $json;
        $this->addOnResult=[];
        if (($json) && (($this->mealTypeID==1)|| ($this->mealTypeID==2)) ) {
            $addonsArray = json_decode($json, true)['addons'];
            $this->addOnResult = DB::table('addonstable')->whereIn('id', $addonsArray)->get();
        }
        //*** End of no longer needed */

        $this->meal_itemsArray=[];
        $this->meal_addOnStates=[];
        //get the items of the meal
        if (($json) && (($this->mealTypeID==3)|| ($this->mealTypeID==4)) ) {
            $meal_itemsArray = json_decode($json, true)['item'];
            $this->meal_itemsArray = DB::table('itemtable')->whereIn('id', $meal_itemsArray)->get();
        }

         $this->customise_item="";
         // Initialize states for each addon
         $this->addOnStates = array_fill(0, count($this->addOnResult), [
            'prepend' => '',
            'classes' => ''
        ]);
    }

    private function getMealOptions($id){
        $companyID = $this->companyID; // 
        // First, retrieve the JSON field from itemsubfoldertable
        $subfolder = DB::table('itemsubfoldertable')
            ->where('companyID', $companyID)
            ->where('id', $id)
            ->first();
                    
        // Extract the 'item' array from the JSON field
        $itemIds = json_decode($subfolder->json, true)['item'];

        // Now query the itemtable with the extracted IDs
        $mealOptionList = DB::table('itemtable')
            ->where('companyID', $companyID)
            ->whereIn('id', $itemIds)
            ->get()
            ->toArray();

        // In your blade view, you can now use it like:
        // @foreach ($mealOptionList as $item)
        //     {{ $item->name }}
        // @endforeach

        return $mealOptionList;
    }

     //This is for updating order views and kitchen
     //** Not used until  get_order() is set to 1 when we implemnent
     //  the card payment
     //
     private function syncCustomerOrder(){
        $key = "cart_polling:{$this->companyID}:{$this->orderPolling}";
        $data = [
            'timestamp' => now()->timestamp,
        ];
        Cache::put($key, $data, now()->addMinutes(10));
    }
    private function syncPaymentReference(){
        $key = "ref_polling:{$this->companyID}:{$this->refPolling}";
        $data = [
            'timestamp' => now()->timestamp,
        ];
        Cache::put($key, $data, now()->addMinutes(10));
    }

    public function optionClose(){
        $this->oldItemQtyChangedByOptionClose= $this->itemOrder;
        $this->itemParentName="My Order";    //$this->oldParentNameChangedByOption;
        $this->isCloseBtn=false;
        $this->isMealDetails=true;
        $this->show_mealDetails();
        $this->itemOrder=$this->oldItemQtyChangedByOptionClose;
     }

    public function display_meal_option($id, $name, $optionSelectPosition){
        $this->optionSelectPosition=$optionSelectPosition;
        $this->mealOptionList=$this-> getMealOptions($id);
        $this->oldParentNameChangedByOption= $this->itemParentName;
        $this->itemParentName=$name;
        $this->show_mealOption();
    }
    
    //Clicking the options
    public function optionAssignFunc($id, $name, $logo){
        $this->mealOption_logos[$this->optionSelectPosition]=$logo; // selected logos
        $this->mealOption_names[$this->optionSelectPosition]=$name;  //sekected names
        $this->mealOption_ids[$this->optionSelectPosition]=$id;
        $this->optionClose();
    }
   
    public function show_cutomisation(){
        $this->isAddToOrder=false;
        $this->isCustomise=true;
        if (in_array($this->mealTypeID, [3,4])){
            $this->isMealDetails=false;
        }else if (in_array($this->mealTypeID, [1,2])){
            $this->isItemDetails=false;
        }
    }
    public function back_toMyOrder(){
        $this->isAddToOrder=true;
        $this->isCustomise=false;
        if (in_array($this->mealTypeID, [3,4])){
            $this->isMealDetails=true;
        }else if (in_array($this->mealTypeID, [1,2])){
            $this->isItemDetails=true;
        }
    }
    public function show_customisation($name, $id){
        $this->customise_item=$name;
        $this->addOnArray=[];
        $this->addOnArray3D=[];
        $this->addOnArrayLogo=[];
        $result = DB::table('itemtable')
            ->select('logo', 'json')
            ->where('id', $id) // Adjust the condition based on your requirements
            ->first();

        // Ensure the result is not null
        if ($result) {
            $logo = $result->logo;
            $json = $result->json;
            $addonsArray = json_decode($json, true)['addons'];
            $this->addOnResult = DB::table('addonstable')->whereIn('id', $addonsArray)->get();
            $this->itemAddOns= $json;
             // Initialize states for each addon
            $this->addOnStates = array_fill(0, count($this->addOnResult), [
                'prepend' => '',
                'classes' => ''
            ]);
            if (in_array($this->mealTypeID, [3,4])){

                if (isset($this->meal_addOnStates[$id]) && !empty($this->meal_addOnStates[$id])) {
                    $this->addOnStates = $this->meal_addOnStates[$id];
                }
            }
        }
        $this->customised_itemID=$id;
    }


    public function mount($data=null){
        $this->user = $data['user'];
        $this->items = $data['items'];
        $this->error = $data['error'];
        if (!$this->user){
            $this->isError =true;
        }else{
            $this->isSplash=true;
        }

        $this->itemDetails= new \stdClass();
        $this->itemDetails->logo="";
        $this->itemDetails->price=0;
        $this->itemDetails->id=0;
        $this->itemDetails->name='';
        $this->itemDetails->qty=0;
        $this->itemDetails->mealTypeID=0;
        $this->itemDetails->itemParts=[];
        $this->itemDetails->addOns=[];
        $this->itemDetails->optionNames=[];
        $this->itemDetails->optionLogos=[];
        $this->itemDetails->optionIDs=[];
        $this->itemDetails->meal_addOnArray=[];
        $this->itemDetails->meal_addOnArray3D=[];
        $this->itemDetails->meal_addOnArrayLogo=[];
        $this->itemDetails->meal_itemsArray=[];
        $this->itemDetails->meal_items_id=[];
        

        $this->mainMenuName="Main Menu";
        $this->itemParentName=$this->mainMenuName;
        $this->companyID= Session::get('companyID');;
        // Query to get the company settings
        $result = DB::table('companySettingsTable')
            ->select('companyName', 'tax', 'discount', 'currency', 'timezone')
            ->where('companyID', $this->companyID)
            ->first();

        // Ensure the result is not null
        if ($result) {
            $this->companyName = $result->companyName;
            $this->tax = $result->tax;
            $this->discount = $result->discount;
            $this->currency = $result->currency;
            $this->timezone = $result->timezone;
        }
        $this->eat_in= config('shared.eat_in');
        $this->take_away= config('shared.take_away');
    }

    public function set_addOns($state, $index, $name, $id,$logo){
        $this->addOnArray[$id]=$state;
        $this->addOnArray3D[$id]=$name;
        $this->addOnArrayLogo[$id]=$logo;
        switch ($state) {
            case 0: // Remove
                $this->addOnStates[$index] = [
                    'prepend' => 'No ',
                    'classes' => 'text-danger'
                ];
                break;
            case 1: // Reset
                $this->addOnStates[$index] = [
                    'prepend' => '',
                    'classes' => ''
                ];
                break;
            case 2: // Add
                $this->addOnStates[$index] = [
                    'prepend' => 'Extra ',
                    'classes' => 'text-success fw-bold'
                ];
                break;
        }

        $meal_itemsArray = [];
        $meal_itemArrayLogo=[];
        foreach ($this->meal_itemsArray as $row) {
            $meal_itemsArray[$row->id] = $row->name;
            $meal_itemsArrayLogo[$row->id] = $row->logo;
        }
        if (in_array($this->mealTypeID, [3,4])){
            $this->meal_addOnStates[$this->customised_itemID]=$this->addOnStates;
            $this->meal_addOnArray[$this->customised_itemID]=$this->addOnArray;
            $this->meal_addOnArray3D[$this->customised_itemID]=$this->addOnArray3D;
            $this->meal_addOnArrayLogo[$this->customised_itemID]=$this->addOnArrayLogo;
            $this->itemDetails->meal_addOnArray=$this->meal_addOnArray;
            $this->itemDetails->meal_addOnArray3D=$this->meal_addOnArray3D;
            $this->itemDetails->meal_addOnArrayLogo=$this->meal_addOnArrayLogo;
            $this->itemDetails->meal_itemsArray=$meal_itemsArray;
            $this->itemDetails->meal_itemsArrayLogo=$meal_itemsArrayLogo;
        }
    }
    public function render()
    {
        return view('livewire.mobile.mobile', ['user' => $this->user, 'items' => $this->items, 'error'=>$this->error]);
    }
}
