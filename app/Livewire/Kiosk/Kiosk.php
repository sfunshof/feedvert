<?php

namespace App\Livewire\Kiosk;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Cache;

class Kiosk extends Component
{
    public $intro=true;
    public $menu=false;
    public $menuName=""; //
    public $oldMenuName=""; //menu name when back button is clicked
    public $menuNameOption=""; //keeps the menu name before the Option is selected
    public $imageName="";
    public $itemPrice=0;  
    public $itemName="";
    public $itemID;
    public $itemCountInit=1;  //Intro count
    public $menuType; //Item or Meal or itemsubfolder-
    public $mealTypeID; //1- Item, 2- item folder 3 - meal  4 meal Folder
    public $cart=[];
    public $totalCost=0;
    public $totalOrder=0;
    public $mealOptions=[];      //subfoolders 
    public $mealOption_logos=[]; // selected logos
    public $mealOption_names=[];  //sekected names
    public $mealOption_ids=[];   //selected id
    public $mealOption_index=-1;
    public $mealItems=[]; //this is the items that make up the meal
    public $customise_logo="";
    public $customise_name="";
    public $customise_addOnQuery;
    public $serverMethodID=0; //1- Dine in, 2- Take Out
    public $orderNo=0; //
    public $final_payment_message="";

   // #[ModelOf('menuResults')] 
    public $menuResults;

    public $subMenuResults;
    public $subResultsV1;        //1st display
    public $subResultsV2;       //2nd display
    public $subResultsOption;   //Options fror the meal

    public $isPriced;          //if the price is shown or not
    public $isMainMenu=true;   //Where menu is shown
    public $itemBackToSubMenu=false;
    public $isItemDetails=false;
    public $activeMenu="";     //active menu that was clicked
    public $isItemUpdate=false;
    public $isViewOrder=false;
    public $isMealOption=false;
    public $isCustomise=false;
    public $item_addOn_names=[]; //This is the array to hold the salt, pepper ketchup for itemss
    public $item_addOn_ids=[];//This is the array to hold the ids
    public $isHowMealIsServed=false;
    public $isPaymentMethod=false; 
    public $isFinalPaymentAtCashier =false; 
    public $isFinalPaymentAtKiosk =false;
    public $isFinalPaymentWithMobile=false;
    public $isFinalPaymentMessage=false;
    public $isInitCardPayment=true;

    public $addOnJson=[]; //hold the addons for meals
    public $addOnJson_customise=[]; //customise addOns [1,0,0,2]: common to both meal and meal items
    public $addOnJson_item=[]; //hold the addons for each meal item 

    public $mealObj_customise=[]; //holds the customised  items for the meal
    public $currentMealObj; //This is the current meal object 
    public $addOnJson_query;
    public $itemParts=[]; //item parts for the meal ?? this looks wrong seem to be the option 

    public $mealTypeID_beforeOption=0;

    //** Timer 
    public $countdown = 10; // Initial countdown value
    public $isRunning = false; // To track if the timer is running

    //** Keypads */
    public $inputText = '';
    public $showAlphaKeyboard = true;
    public $maxLength = 20; // Maximum character limit for payment reference

    public $eat_in;
    public $take_away;
    public $collectionMethod;

    //polling
    public $orderPolling="orderPolling";
    public $refPolling="refPolling"; 

    public function ks_intro(){
       $this->intro=true;
       $this->menu=false;
    }
    public function ks_menu(){
        $this->intro=false;
        $this->menu=true; 
    }

    private function resetFunction(){
        $this->isMainMenu=false;
        $this->isItemDetails=false;
        $this->isItemUpdate=false;
       // $this->isMealOption=false;
        $this->isViewOrder=false;
        $this->isCustomise=false;
        $this->isHowMealIsServed=false;
        $this->isPaymentMethod=false; 
        $this->isFinalPaymentAtCashier =false; 
        $this->isFinalPaymentAtKiosk =false;
        $this->isFinalPaymentWithMobile=false;
        $this->isFinalPaymentMessage=false;
     }

    //This is  function called by the Back button
    public function itemBackToSubMenuFunc(){
        $this->itemBackToSubMenu=false;
        //coming from mealOptions
        if ($this->isMealOption==true){
            $this->resetFunction();
            $this->isItemDetails=true;
            //$this->isMainMenu=false;
            $this->mealTypeID=$this->mealTypeID_beforeOption;
            $this->isMealOption=false; //otherwise it will show on the item details
            return false;
        } 
        $this->isPriced=false;
        $this->subMenuResults= $this->subResultsV1;      //1st display
        $this->menuName=$this->oldMenuName;
    }
       
    //When the cancel btn is clicked
    public function cancelItemDetailsFunc(){
         //$this->isItemDetails=false;
        $this->resetFunction();
        $this->isMainMenu=true;
        if  (count($this->mealOptions)>0)  { //Reverts back to the original sub folder and name
            $this->subMenuResults=$this->subResultsOption; //This is the original kept in the option
            $this->menuName=$this->menuNameOption;
            $this->mealOptions=[];
            $this->initMealOptions();
            //dump($this->menuNameOption);
            return 0;
        }
        $this->mealOptions=[];
        $this->initMealOptions();
        if (($this->mealTypeID==2) || ($this->mealTypeID==4)){ //both are subfolder meals
            $this->subMenuResults=$this->subResultsV2;
        }else{
            $this->subMenuResults=$this->subResultsV1;
        }
    }

    private function  updateCart(){
        $totalCost=0;
        $totalOrder=0;
        foreach ($this->cart as $obj) {
            $totalCost += $obj->price *  $obj->qty;  // Add price to total
            $totalOrder += $obj->qty;  // Add order to total
            //$this->js('console.log('  . $totalOrder  . ')');
        }
        $this->totalOrder=$totalOrder;
        $this->totalCost= number_format($totalCost, 2);
        //dump($this->cart);
    }
    
    public function incrementQty($index){
        $this->cart[$index]->qty++;
        $this->updateCart();
    }
    public function decrementQty($index){
        if ($this->cart[$index]->qty > 1) {
            $this->cart[$index]->qty--;
            $this->updateCart();
        }
    }
    public function removeItem($index){
        array_splice($this->cart, $index, 1);
        $this->updateCart();
        if (count($this->cart)==0){
            $this->emptyCart();
        }
    }

    public function emptyCart(){
       $this-> orderMoreFromViewMyOrder();
    }

    private function initMealOptions(){
        $this->isMealOption=false;
        $this->mealOption_logos=[];
        $this->mealOption_names=[];
        $this->mealOption_ids=[];
    }

    //This from Add to  Order
    public function updateItemFunc(){
        //check if it is a meal with some options
        //dump($this->mealOptions);
        //MealOptions[] : has selectOption, logo, name, id as members done automatically
        //mealOptions_logos[] musr be filled manually by selecting it
        //mealOptions[$i]->id is the subfolder id
            
        //This is a meal withoptions
        if (count($this->mealOptions) > 0){
            for($i=0;$i< count($this->mealOptions); $i++ ){
                //if it has not yet been selected bring it up
                if (empty($this->mealOption_logos[$i])) {
                    $this->getMealOptions($this->mealOptions[$i]->id, 
                        $this->mealOptions[$i]->selectOption, $i );
                        return 0;  
                }
            }
        }
    
        
        //$this->isItemDetails=false;
        $this->resetFunction();
        $this->isItemUpdate=true;

        $itemObj=new \stdClass();

        $itemObj->logo=$this->imageName;
        $itemObj->name=$this->itemName;
        $itemObj->price=$this->itemPrice;
        $itemObj->id=$this->itemID;
        $itemObj->qty=$this->itemCountInit;
        
        //$addonArray = $data['addons'];
        $itemObj->itemParts=$this->itemParts;
        $itemObj->addOns=$this->addOnJson;
        
        $itemObj->options = array_map(function($id, $name, $logo) {
            return (object)[
                'id' => $id,
                'name' => $name,
                'logo' => $logo
            ];
        }, $this->mealOption_ids, $this->mealOption_names, $this->mealOption_logos);

        $itemObj->mealTypeID=$this->mealTypeID;
      
        //dump($this->item_addOn_names);
        //dump($this->item_addOn_ids);
        //dump($this->addOnJson_customise);   
        $itemObj->customise=[];
        if (in_array($this->mealTypeID, [1, 2])){          
            $customiseObj=new \stdClass();
            $customiseObj->addOn_names=$this->item_addOn_names;
            $customiseObj->ids=$this->item_addOn_ids;
            $customiseObj->addOnJson_customise=$this->addOnJson_customise;
            $customiseObj->itemName="";
            $itemObj_array=[];
            array_push($itemObj_array,$customiseObj);
            if (count($this->item_addOn_names) > 0){
                $itemObj->customise=$itemObj_array;
            }
        }elseif (in_array($this->mealTypeID, [3, 4])){ 
            if (count($this->mealObj_customise) > 0){
                $itemObj->customise=$this->mealObj_customise;
            }
        }

       // dump($this->mealObj_customise);
        array_push($this->cart,$itemObj);
        $this->updateCart($itemObj);
        $this->dispatch('show-main'); 
        $this->itemCountInit=1;
        if (count($this->mealOptions) > 0){   
            $this->menuName=$this->menuNameOption;
            if ($this->mealTypeID==3){ //direct meal
                $this->subMenuResults=$this->subResultsV1;   
            }elseif ($this->mealTypeID==4) { //subfolder meal
                $this->subMenuResults=$this->subResultsV2;
            }
        }

       $this->initMealOptions();
    } 

    #[On('show-main')] 
    public function updateShowMain()
    {
         sleep(2);
         //$this->isItemUpdate=false;
         $this->resetFunction();
         $this->isMainMenu=true;
    }
    
    //Cancel Order
    public function mainMenuInit(){
        $this->totalCost=0;
        $this->totalOrder=0;
        //repeated jut leave it
        $this->mealOptions=[];
        $this->initMealOptions();
        //end of repeated
        $this->cancelItemDetailsFunc(); //cancel item
        $this->cart=[];

    }
    
    private function numberToArray($number) {
        return preg_split('//', (string)$number, -1, PREG_SPLIT_NO_EMPTY);
    }
    
    //This function gets the meal's items
    private function get_mealItems($json){
        //Decode the JSON to an associative array
        $data = json_decode($json, true);
        // Extract the items array
        $items = $data['item'];
        // Define the companyID (assuming it's coming from elsewhere, e.g., request or session)
        $companyID=Auth::user()->companyID;
        // Query the table using Laravel Query Builder
        $results = DB::table('itemtable')
            ->where('companyID', $companyID)
            ->whereIn('id', $items)
            ->get();
        
        // Output or process the results
        return $results;
    }

    //This is for the meals where mealtypeid =2 or 3 
    private function get_mealOptions($id, $price, $name,$logo){
        $tableName="mealtable";
        $value=$this->getJsonField($tableName, $id,"itemsubfolder");
        $this->mealOptions=$this->getSelectOptions($value);
    }
    private function get_valueFromKey($key,$jsonData){
        $data = json_decode($jsonData, true);
        $array = $data[$key] ?? null;
        //Sort it so that it can tally with customised version
        if (is_array($array)) {
             sort($array); 
        }
        return $array;
    }
    private function initializeArray($size) {
        // Initialize an array with the given size, all elements set to 1
        return array_fill(0, $size, 1);
    }
    
    private function show_customise_template(){
        $this->resetFunction();
        $this->isCustomise=true;
    }
    public function show_customise(){
        //This is for the item
        //$this->isCustomise_type=1;    
        $companyID=Auth::user()->companyID;
        $this->addOnJson_query = DB::table('addonstable')
        ->whereIn('id', $this->addOnJson)
        ->where('companyID', $companyID)
        ->orderBy('id')
        ->get();
        $this->customise_logo=$this->imageName;
        $this->customise_name=$this->itemName;
        $this->customise_addOnQuery=$this->addOnJson_query;
        $this->item_addOn_names=$this->addOnJson_query->pluck('name')->toArray();
        $this->item_addOn_ids=$this->addOnJson_query->pluck('id')->toArray();
        $this->show_customise_template();
    }

    private function get_mealObj($itemID) {
        foreach ($this->mealObj_customise as $meal) {
            if ($meal->itemID == $itemID) { 
                return $meal; }
            } 
        return null; 
    }
    public function show_customise_item($id, $logo, $name,  $json){
        //This is for the item
        //$this->isCustomise_type=2;  
        $this->currentMealObj=$this->get_mealObj($id);
        
       // dump($this->currentMealObj);

        //Used the currently saved one
        if ( $this->currentMealObj){
            $this->addOnJson_customise=$this->currentMealObj->addOnJson_customise;
            $this->customise_addOnQuery = $this->currentMealObj->customise_addOnQuery;
            $this->customise_logo= $this->currentMealObj->itemLogo;
            $this->customise_name=$this->currentMealObj->itemName;
        }else{    
            $this->currentMealObj=new \stdClass();
            $this->currentMealObj->itemID=$id;
            $this->currentMealObj->itemLogo=$logo;
            $this->currentMealObj->itemName=$name;
             //get the array of the json
            $jsonArray=$this->get_valueFromKey("addons", $json);
            $this->currentMealObj->addOnJson= $jsonArray; //[1,2,6]
            $companyID=Auth::user()->companyID;
            $this->customise_addOnQuery = DB::table('addonstable')
                ->whereIn('id', $jsonArray)
                ->where('companyID', $companyID)
                ->orderBy('id')   //remeber this is to make it tallys with the orginal lsit of addons
                ->get(); 
            $this->customise_logo=$logo; //logo name used in blade
            $this->customise_name=$name;
            $this->addOnJson_customise= $this->initializeArray(count($jsonArray));
            $this->currentMealObj->customise_addOnQuery =  $this->customise_addOnQuery;
            $this->currentMealObj->addOn_names=$this->customise_addOnQuery->pluck('name')->toArray();
        }
        $this->show_customise_template();
    }

    
    private function removeMealObj($itemID){
        foreach ($this->mealObj_customise as $key => $meal) {
            if ($meal->itemID == $itemID) {
                unset($this->mealObj_customise[$key]);
                // Reindex the array to maintain continuous keys
                $this->mealObj_customise = array_values($this->mealObj_customise);
                break;
            }
        }
    } 

    public function back_customise(){
        //$this->addOnJson_customise= $this->initializeArray(count($this->addOnJson));
        // $this->isCustomise=false;
        $this->resetFunction();
        $this->isItemDetails=true;  
    }
    public function cancel_customise(){
        if(($this->mealTypeID==3) || ($this->mealTypeID==4)){
           $this->removeMealObj($this->currentMealObj->itemID);
        }elseif(($this->mealTypeID==1) || ($this->mealTypeID==2)){
            $this->addOnJson_customise = array_map(
                function($value) { 
                    return 1; }, 
            $this->addOnJson_customise);
        } 
        $this->back_customise();
    }
    private function updateMealObj($mealObj){
        $found = false;
        foreach ($this->mealObj_customise as &$meal) {
            if ($meal->itemID == $mealObj->itemID) {
                // Update existing meal object
                $meal = $mealObj;
                $found = true;
                break;
            }
        }
        if (!$found) {
            // Insert new meal object if it doesn't exist
            $this->mealObj_customise[] = $mealObj;
        }
    }

    public function save_customise(){
        $this->back_customise();  
        if (($this->mealTypeID==3) || ($this->mealTypeID==4)){
            $this->currentMealObj->addOnJson_customise= $this->addOnJson_customise;
            $this->updateMealObj($this->currentMealObj);
            $this->addOnJson_customise=[];
        }
    }
    public function inc_customise($index){
        if (isset($this->addOnJson_customise[$index]) && $this->addOnJson_customise[$index] < 2) {
            $this->addOnJson_customise[$index]++;
        }
    }
    public function dec_customise($index){
        if (isset($this->addOnJson_customise[$index]) && $this->addOnJson_customise[$index] > 0) {
            $this->addOnJson_customise[$index]--;
        }

    }
    private function get_orderNo($isReady){
        $appController = app(AppController::class);
        // Set the desired timezone
        $timezone = Auth::user()->companySettings->timezone;
        $companyID= Auth::user()->companyID;
        $ingredientsArray=$this->processItems($this->cart);
        $kiosk_receipt=$this->kiosk_receiptItems();
        //dump($kiosk_receipt);

        //dump($ingredientsArray);
        return $appController->get_orderNo($kiosk_receipt, [], $ingredientsArray, $companyID, $timezone, $isReady);
    }

    private function kiosk_receiptItems(){
        $result = [];
        foreach ($this->cart as $index => $item) {
            // Initialize the result for the current index
            $result[$index] = [
                "qty" => $item->qty,
                "name" => $item->name,
                "price" => $item->price,
                "mealTypeID" => $item->mealTypeID,
            ];
            // Check if mealTypeID is 1 or 2
            if (in_array($item->mealTypeID, [1, 2])) {
                // Process customise data
                $addOns3D = [];
                
                if (isset($item->customise) && is_array($item->customise) && count($item->customise) > 0) {
                    foreach ($item->customise[0]->addOnJson_customise as $key => $value) {
                        if ($value === 2) {
                            $addOns3D[] = "Extra " . $item->customise[0]->addOn_names[$key];
                        } elseif ($value === 0) {
                            $addOns3D[] = "No " . $item->customise[0]->addOn_names[$key];
                        }
                    }
                }
                // Add addOns3D to the result
                $result[$index]["addOns3D"] = $addOns3D;
            }
            // Check if mealTypeID is 3 or 4
            if (in_array($item->mealTypeID, [3, 4])) {
                // Process options data
                $offers = [];
                foreach ($item->options as $option) {
                    $offers[] = $option->name;
                }
                $result[$index]["offers"] = $offers;

                // Process customise data
                $meal_items_addOns3D = [];
                foreach ($item->customise as $customise) {
                    $addOns3D = [];
                    foreach ($customise->addOnJson_customise as $key => $value) {
                        if ($value === 2) {
                            $addOns3D[] = "Extra " . $customise->addOn_names[$key];
                        } elseif ($value === 0) {
                            $addOns3D[] = "No " . $customise->addOn_names[$key];
                        }
                    }
                    $meal_items_addOns3D[$customise->itemName] = $addOns3D;
                }
                $result[$index]["meal_items_addOns3D"] = $meal_items_addOns3D;
            }
        }

        $result['payment_method']="";
        $result['amount_tendred']=0;
        $result['collection_method']=$this->collectionMethod;
        $result['total_cost']= 0;
        $result['change_given']=0;
        $result['currency']=Auth::user()->companySettings->currency ;
        $result['cashier_id']= "";
        $result['cashier_name']= "" ;
        $result['medium']="Kiosk";

        // Convert the result to JSON
         return  $result;
    }

    
    private function processItems($items) {
        $resultArray = [];
    
        foreach ($items as $item) {
            $qty = $item->qty; // Access object property
            $mealTypeID = $item->mealTypeID; // Access object property
    
            if ($mealTypeID == 3 || $mealTypeID == 4) {
                // Process options and itemParts
                $optionsArray = $item->options; // Access object property
                $itemPartsArray = $item->itemParts; // Access object property
    
                // Combine options and itemParts into a single array
                $combineArray = [];
                foreach ($optionsArray as $option) {
                    $combineArray[$option->id] = ($combineArray[$option->id] ?? 0) + 1; // Access object property
                }
                foreach ($itemPartsArray as $part) {
                    $combineArray[$part] = ($combineArray[$part] ?? 0) + 1;
                }
    
                // Multiply by qty
                foreach ($combineArray as $key => $value) {
                    $combineArray[$key] = $value * $qty;
                }
    
                // Fetch ingredients_json and process
                foreach ($combineArray as $itemId => $count) {
                    $ingredients = DB::table('itemtable')
                        ->where('id', $itemId)
                        ->value('ingredients_json');
    
                    if ($ingredients) {
                        $ingredients = json_decode($ingredients, true);
                        foreach ($ingredients as $ingredientId => $ingredientQty) {
                            $resultArray[$ingredientId] = ($resultArray[$ingredientId] ?? 0) + ($ingredientQty * $count);
                        }
                    }
                }
            } elseif ($mealTypeID == 1 || $mealTypeID == 2) {
                // Process single item
                $itemId = $item->id; // Access object property
                $ingredients = DB::table('itemtable')
                    ->where('id', $itemId)
                    ->value('ingredients_json');
    
                if ($ingredients) {
                    $ingredients = json_decode($ingredients, true);
                    foreach ($ingredients as $ingredientId => $ingredientQty) {
                        $resultArray[$ingredientId] = ($resultArray[$ingredientId] ?? 0) + ($ingredientQty * $qty);
                    }
                }
            }
        }
    
        return $resultArray;
    }

    public function mount(){
        $this->customise_addOnQuery= (object)[];
        $this->eat_in= config('shared.eat_in');
        $this->take_away= config('shared.take_away');
    }
    public function show_howMealIsServed(){
        $this->resetFunction();
        $this->isHowMealIsServed=true;
    }
    public function back_fromHowMealIsServed(){
        $this->resetFunction();
        $this->isViewOrder=true;
    }

     //This is for updating order views and kitchen
     //*** Not used until we implement card payment system when 
     //get_order() is set to 1
     //
     private function syncCustomerOrder(){
        $companyID=Auth::user()->companyID;
        $key = "cart_polling:{$companyID}:{$this->orderPolling}";
        $data = [
            'timestamp' => now()->timestamp,
        ];
        Cache::put($key, $data, now()->addMinutes(10));
    }

    private function syncPaymentReference(){
        $companyID=Auth::user()->companyID;
        $key = "ref_polling:{$companyID}:{$this->refPolling}";
        $data = [
            'timestamp' => now()->timestamp,
        ];
        Cache::put($key, $data, now()->addMinutes(10));
    }

    public function show_paymentPage($serveMethodID){
        $this->serverMethodID=$serveMethodID;
        $this->resetFunction();
        $this->isPaymentMethod=true;
        if ($serveMethodID==1){
            $this->collectionMethod=$this->eat_in;
        }else if ($serveMethodID==2){
            $this->collectionMethod=$this->take_away;
        }
    }
    public function back_fromPaymentPage(){
        $this->resetFunction();
        $this->isHowMealIsServed=true;
    }
    public function back_fromPaymentMobilePage(){
        $this->show_paymentPage($this->collectionMethod);
    }
    public function show_finalPaymentAtCashier(){
        //$this->resetFunction();
        //$this->orderNo=$this->get_orderNo();
        //$this->isFinalPaymentAtCashier=true;
        //$this->dispatch('start-countdown');
        $msg1=" Please take picture of your Order No. Then proceed to the cashier to 
        make your payment";
        $grandCost = number_format($this->totalCost + ($this->totalCost * Auth::user()->companySettings->tax),2); 
        $grandCost=Auth::user()->companySettings->currency . $grandCost;
        $msg2=' <span class="fw-bold fs-3 text-success"> '
            . $grandCost .
              ' </span> ';
        $this->final_payment_message=$msg1 . $msg2;
        $this->orderNo=$this->get_orderNo(0);
        $this->show_finalPaymentMessage();
    }
    public function show_finalPaymentWithMobile(){
        $this->resetFunction();
        $this->isFinalPaymentWithMobile=true;
    }
    public function show_finalPaymentMessage(){
        $this->resetFunction();
        $this->isFinalPaymentMessage =true;
        $this->dispatch('start-countdown'); 
    }

    //Simulate  failed card payment
    public function show_finalPaymentAtKiosk_failed(){
        $this->resetFunction();
        $this->isFinalPaymentAtKiosk=true;
        $this->isInitCardPayment=true;
        //Then we assume it has failed after 10 seconds

        $this->dispatch('failed-card'); 
    }

    #[On('failed-card')] 
    public function faild_cardFallback()
    {
        sleep(5);
        $this->orderNo=$this->get_orderNo(0);
        $this->isInitCardPayment=false;
        $this->startCountdown();
    }

    
    //Here isPriced is true
    public function showItemDetails($id, $price, $name,$logo, $json){
       
         //if it is meal option show the image
        if ($this->isMealOption){
            $this->mealOption_logos[$this->mealOption_index]  =$logo;
            $this->mealOption_names[$this->mealOption_index]  =$name;
            $this->mealOption_ids[$this->mealOption_index]  =$id;
            //goback
            $this->itemBackToSubMenuFunc();
            return true;
        }
        $this->itemCountInit=1;
        //if this is a meal, then we shoud get the meal options
        if (($this->mealTypeID==3 ) || ($this->mealTypeID==4)) { //folder of meal
            if (count($this->mealOptions)==0){
                $this->get_mealOptions($id, $price, $name,$logo);
            }
            //get the items that make up the meals
            $this->mealItems=$this->get_mealItems($json);
        }
        //Otherwise clear the options 
        if (($this->mealTypeID==1 ) || ($this->mealTypeID==2)) {
            $this->mealOptions=[];
        }    
        $this->item_addOn_names=[];
        $this->item_addOn_ids=[];
        
        $this->resetFunction();
        $this->isItemDetails=true;
        //$this->isMainMenu=false;
        $this->itemID=$id;
        $this->itemPrice= $price;
        $this->itemName=$name;
        $this->imageName=$logo;
        
        $this->addOnJson=[];
        if (($this->mealTypeID==3)||($this->mealTypeID==4)){
            //get the simply get the item parts
            $this->itemParts=$this->get_valueFromKey("item",$json); //Now changed ***//Option Array
            $this->mealObj_customise=[]; //clear the customise array before a new one
        }else if (($this->mealTypeID==2)||($this->mealTypeID=1)){
             $this->addOnJson=$this->get_valueFromKey("addons", $json);
        }
        $this->addOnJson_customise= $this->initializeArray(count($this->addOnJson ?? []));
        $this->menuNameOption=$this->menuName; //Just in case we go into option we need to remember the name
        $this->itemPrice= number_format($price, 2);
  
    }
    
    //This is clicking from the main Menu
    public function get_mealsFromMenu( $key, $json,$menuName,$mealTypeID, $v){
         $this->menuName=$menuName; //so that the meniu name changes
         $this->mealTypeID=$mealTypeID;    
        //we need to get the meals from the table
        $tableName=$key . "table";
        $companyID = Auth()->User()->companyID; // Replace with your actual company ID
        $results = DB::table($tableName)
                ->where('companyID', $companyID)
                ->whereIn( 'id', $json)
                ->get();
        $this->isPriced = ($key == 'item') ? true : false;  //forces false for non item
          
        
        if ($this->isMealOption){
            if ($this->mealTypeID==3){ //direct meal
                $this->subResultsOption=$this->subResultsV1;    //$this->subMenuResults; //keep the original o be used later
            }elseif ($this->mealTypeID==4) { //subfolder meal
                $this->subResultsOption=$this->subResultsV2; 
            }
        }else{
            if (in_array($this->mealTypeID, [3, 4])){    //This is to be used in cancel itemd in details form
                $this->subResultsOption=$results;
            }   
        }        
        
        if ($v==1){ //From main menu
            $this->subResultsV1=$results;
            $this->subMenuResults=$results;   
            $this->oldMenuName=$menuName;
            $this->activeMenu=$menuName;
            $this->menuType=$key;
            $this->mealTypeID=$mealTypeID;
        }else  if($v==2){ //From Sub menu
            //If it is options please do not save
            if (!$this->isMealOption){
                $this->subResultsV2=$results;
            }
            $this->subMenuResults=$results;
        }   
        $this->itemBackToSubMenu=false; 
    }
     
    
    private function getJsonFieldKey($id) {
        // Query the databasegetMealOptions
        $companyID = Auth()->User()->companyID; //
        $result = DB::table('itemsubfoldertable')
            ->select('json')
            ->where('companyID', $companyID)
            ->where('id', $id)
            ->first();

        // Decode the JSON field
        $json = json_decode($result->json, true);
      
        // Return the key (either 'item' or 'meal')
        foreach ($json as $key => $value) {
            if ($key === 'item' || $key === 'meal') {
                return $key;
            }
        }
        return null; // or handle the case where neither key exists
    }

    //When Options are clicked from item details: A lot of changes happen to the display
     public function getMealOptions($id,$selectCommand, $index){
        $price=0;
        $logo="";
        $this->mealTypeID_beforeOption=$this->mealTypeID; //keep te maltypeid b/4 options
        $this->isMealOption=true;
        $this->isItemDetails=false;
        $this->mealOption_index=$index;
        $this->showItemsFromSubMenu($id, $selectCommand,$price,$logo,[]);
    }

    private function getSelectOptions($array) {
        // Flatten the array to handle both 1D and 2D arrays
        $ids = Arr::flatten($array);
        $companyID = Auth()->User()->companyID; //
        // Query the database
        $results = DB::table('itemsubfoldertable')
            ->select('selectOption', 'name', 'logo', 'id')
            ->where('companyID', $companyID)
            ->whereIn('id', $ids)
            ->get();

        // Prepare the output
        $output = [];
        foreach ($results as $result) {
            $output[] = (object) [
                'selectOption' => $result->selectOption,
                'logo' => $result->logo,
                'name' =>$result->name,
                'id' => $result->id,
            ];
        }
        return $output;
    }

    private function getJsonField( $tableName,  $id, $searchCriteria) {
          // Query the database
        $companyID = Auth()->User()->companyID; //
        $result = DB::table($tableName)
            ->select('json')
            ->where('companyID', $companyID)
            ->where('id', $id)
            ->first();
           // Decode the JSON field
        $json = json_decode($result->json, true);
        // Return the appropriate array based on the search criteria
        return $json[$searchCriteria];
    }

    public function showItemsFromSubMenu($id, $name,$price,$logo,$json){
        //*** Anytime a subfolder is clicked it returns key="meal or subfolder
        // The subfolder is a subfolder from items or meal ie a subfolder of items or meals
        
        //this->menuType is the tablename
        //go into the database and get the array [1,2,3] and key to enanle us know are dealing
        //with items or meals
        //*** This should have been done in blade but was generating error so what 
        //a waste that we have to do sql again */
      
        //This is a folder of meal
        if ($price>0){
            $this->get_mealOptions($id,$price,$name,$logo,$json);
            //We included price and logo because of the meals to be dispayed
            $this->showItemDetails($id, $price, $name,$logo, $json);
            return true;
        }
        //**** End of direct meal */ 
        
        //Now inside itemsubfoldertable we need to knoww if we are sealing with
        //meal or Item        
        $menuType=$this->getJsonFieldKey($id);
        $tableName=$this->menuType."table"; //Both meal and item are itemsubfolders  
        
        //if it is mealOptions then it is always subfolder
        if ($this->isMealOption){
             $tableName="itemsubfoldertable";
        }        

        $value=$this->getJsonField($tableName,$id, $menuType);
        $this->get_mealsFromMenu($menuType,$value,$name,-1, 2 );
    
        $this->itemBackToSubMenu=true;
    }
    
    public function showViewOrder(){
        $this->resetFunction();
        $this->isViewOrder=true;
        //dump($this->cart);
        //$this->isMainMenu=false; 
    }
    
    public function orderMoreFromViewMyOrder(){
        $this->resetFunction();
        $this->isMainMenu=true; 
        //$this->isViewOrder=false;
    }
    
   //** Timer  */
   public function countdownComplete(){
        // This function will be called when the countdown reaches 0
       // dump("Finished");
    }
    public function startCountdown(){
        $this->dispatch('start-countdown'); 
    }

    //keypad for payment
    public function addCharacter($char)
    {
        // Only add character if we haven't reached the maximum length
        if (strlen($this->inputText) < $this->maxLength) {
            $this->inputText .= $char;
        }
    }

    public function deleteCharacter()
    {
        if (strlen($this->inputText) > 0) {
            $this->inputText = substr($this->inputText, 0, -1);
        }
    }

    public function toggleKeyboard()
    {
        $this->showAlphaKeyboard = !$this->showAlphaKeyboard;
    }

    public function clearInput()
    {
        $this->inputText = '';
    }

    public function submitReference()
    {
        $this->final_payment_message="Once your payment reference no has been validated, your order will be processed";
        $this->orderNo=$this->get_orderNo(0);
        //put the refNo into a database
        $timezone = Auth::user()->companySettings->timezone;
        $companyID= Auth::user()->companyID;
        $currentDateTime = Carbon::now($timezone);
        DB::table('payrefstable')->insert([
            'companyID' => $companyID,
            'dateTime' => $currentDateTime->format('Y-m-d H:i:s'),
            'orderNo' => $this->orderNo,
            'refNo' => $this->inputText,
            'amount' => $this->totalCost
        ]);
        $this->syncPaymentReference();
        //dump($this->inputText);
        // For now, we'll just reset the input
        $this->reset('inputText');
        $this->show_finalPaymentMessage();
    }


    public function render()
    {
         return view('livewire.kiosk.kiosk');
    }
} 
