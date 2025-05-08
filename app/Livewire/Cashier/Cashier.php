<?php

namespace App\Livewire\Cashier;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On; 
use Carbon\Carbon;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use App\Http\Controllers\AppController;

use Illuminate\Support\Facades\Cache;
use App\Traits\PayRefsTrait;

class Cashier extends Component 
{
    use PayRefsTrait;
    public $is_mainMenu=true;
    public $is_itemDetails=false;
    public $is_options=false;
    public $is_category=true;
    public $is_menuBtns=true;
    public $is_optionsComplete=false;
    public $is_customiseMeal=false;

    public $is_payment=false;
    public $is_setPayment=false;
    public $is_setDiscount=false;
    public $is_cash=false;
    public $is_card=false;
    public $is_mobile=false;
    public $is_voucher=false;
    public $is_house=false;
    public $is_paymentSucceed=false;
    public $change_given=0;
    public $paymentLabel="";
    public $cashTendered="Cash Tendered";
    public $payment_method="";
    public $amount_tendered=0;
    public $payNames=[];
    public $refResults = [];

    public $is_paymentProcessing=false; 

    public $is_backVisible=false;
    public $options_backVisible;
    public $cart=[];

    public $current_mealTypeID;
    public $current_name; //menu caption
    public $previous_name; //menu
    public $options_name;

    public $current_results; //result of the query
    public $previous_results; //result of the query
    public $options_results=[];
    public $keep_current_results;//used for holding the current resukts before distruption by option
    public $meal_item_results;
    public $meal_items=[]; //1=>"coke" 2=> "Fries" 3=> "burger"
    public $meal_items_addOns=[]; //4=>[1=>1,3=>1,6=>1], 5->[1=>1,3=>1,6=>1]
    public $meal_items_addOns3D=[];
    public $options_index;
    public $options_array=[]; //9=>0,6=>0
    public $options_array3D=[]; //9="Coke", 6="Fries"
    public $options_key; // This is the  folderID of interest
    public $firstCategoryData; //=[2];
    public $CDUpolling="CDUpolling";
    public $orderPolling="orderPolling";
    public $userID=null; // added becuase of polling
    public $categoryResults;
    public $orderNo='';
    public $orderDateTime='';
    public $showError = false;
    public $showErrorV2 = false; //For datetime
    public $errorMsgV2="";
    public $menu_order=[];
    public $is_showSalesOrder=true; //if it is pay_for or pay normal
    public $order_menu="pay"; //pay or cancel
    public $keyID;

    public $details_name;
    public $details_qty;
    public $details_index;
    public $details_mealTypeID;
    public $details_itemKey; //[3=>[1=>1,4=>1 ]] we are dealing with 3 and 4
     
    public $printerName='CashierPrinter1';
    public $message;
    public $is_refundReady=false;
   
    public $addOns=[]; //This is akey value 2=>1, 3=>1, 4=>0 etc
   
    
    public $totalCost=0;
    public $totalOrder=0;
    public $subTotal=0;
    public $tax=0;
    public $discount=0;
    public $totalSales=[];
    
    public $eat_in;
    public $take_away;
    public bool $showModal = false; 
    public $collectionMethod;
    public $modalSize = '';
    public $modalFile = ''; 
    public $receiptData=[];
    public $receiptDate="";
    public $printerIPaddress='';

    public $display = ''; //Thhis this the amount tendered
    public $blankKeys = [
        ['position' => 3, 'value' => ''], 
        ['position' => 7, 'value' => ''],
        ['position' => 11, 'value' => ''],
        ['position' => 15, 'value' => '']
    ];
   

    private function get_categories(){
        $companyID=Auth::user()->companyID;
        $this->categoryResults = DB::table('itemfoldertable') 
            ->where('companyID', $companyID) 
            ->orderBy('position', 'asc')
            ->get();
    }
    private function payment_reset(){
        $this->is_cash=false;
        $this->is_card=false;
        $this->is_mobile=false;
        $this->is_voucher=false;
        $this->is_house=false;
    } 


    //item. subfolder, meal
    private function get_jsonKeyFromMealTypelD($mealTypeID){
        if ($this->is_options){
            return "item";
        }
        $key="";
        switch ($mealTypeID) {
           case 1:
                //item
                $key="item";
            break;
            case 2:
                //sub menu
                $key="itemsubfolder";
            break;
            case 3:
               //meal;
               $key="meal";
            break;
            case 4:
               // sub meal;
               $key="itemsubfolder";
            break;
            default:
                // echo "Invalid: Error";
             break;
       }
       return $key;
    }
    private function init_options($results){
        $this->is_options=true;
        $this->is_menuBtns=false;
        $this->is_category=false;
        $this->is_optionsComplete=false;

        $this->options_name= $this->current_name;
        $this->current_name="Meal Options"; 
        $this->options_backVisible= $this->is_backVisible;
        $this->current_results=[]; 
        $this->options_results= $results;

        $this->is_backVisible=true;   
     }
    private function exit_options(){
        $this->is_options=false;
        $this->is_menuBtns=true;
        $this->is_category=true;
        $this->is_optionsComplete=false;

        $this->current_name=$this->options_name;
        $this->current_results=$this->keep_current_results;
        $this->is_backVisible= false; //   $this->options_backVisible;
    }
    public function get_items_from_category($categoryID, $json, $categoryName,  $mealTypeID){
        $companyID=Auth::user()->companyID;
        $key=$this->get_jsonKeyFromMealTypelD($mealTypeID);
        $tableName=$key . "table";
        $values=$this->get_jsonValue($json,$key);
         $results = DB::table($tableName) 
            ->where('companyID', $companyID) 
            ->whereIn('id', $values)
            ->orderBy('position', 'asc')
            ->get();
        $this->current_name=$categoryName;
        $this->current_results=$results;   
        $this->current_mealTypeID=$mealTypeID;
        $this->is_backVisible=false;
        //This is to keep direct from category
        if (in_array($this->current_mealTypeID, [3])){
            $this->keep_current_results=$this->current_results;
       }
       $this->dispatch('scrollBtnStatus'); 
    }
    public function get_items_from_first_category(){
        $arrayData = json_decode($this->firstCategoryData, true);
        //$arrayData = $this->firstCategoryData;
        $id = $arrayData["id"];
        $json=$arrayData["json"];
        $name= $arrayData["categoryName"];
        $mealTypeID=$arrayData["mealTypeID"];
        $this->get_items_from_category($id, $json, $name,  $mealTypeID);
    }
   
    private function is_optionsComplete($array) {
        return !in_array(0, $array, true);
    }
    //turns [9=>0] to[9=>1]
    private function update_optionsArray(&$array, $key, $value) {
        if (array_key_exists($key, $array)) {
            $array[$key] = $value;
        }
        return $array;
    }
    
    //1=>[1=>fries state=>1]
    private function set_meal_items_addOns3D($associativeArray){
        $keys = array_keys($associativeArray);
        $result = [];
        foreach ($keys as $key) {
            $addons = json_decode($associativeArray[$key], true)['addons'];
            $addonData = DB::table('addonstable')
                ->select('id', 'name')
                ->selectRaw('1 as state')
                ->whereIn('id', $addons)
                ->get()
                ->toArray();
            $result[$key] = array_map(function($item) {
            return (array) $item;
           }, $addonData);
        }
        return $result;
    }

    //1=>[1=>1,4=>1]
    private function set_meal_items_addOns($meal_items_addOns){
        $keys = array_keys($meal_items_addOns);
        $result = [];
        foreach ($keys as $key) {
            $addons = json_decode($meal_items_addOns[$key], true)['addons'];
             $addonIds = DB::table('addonstable')
                ->select('id')
                ->whereIn('id', $addons)
                ->pluck('id');
            $result[$key] = array_fill_keys($addonIds->toArray(), 1);
        }
        return $result;
    } 
    //one
    public function get_items_from_menu_or_meal($menuID,  $menuName, $price){
        $this->meal_items_addOns3D=[];
        //$this->addOns3D=[];
        $companyID=Auth::user()->companyID;
        //So you ckick on fanta at last during options slection
        //from meal as meal
        if ($this->is_options){
            $this->update_optionsArray($this->options_array, $this->options_key,$menuID);
            $this->update_optionsArray($this->options_array3D, $this->options_key,$menuName);
            
            $this->cart[$this->options_index]->options_array=$this->options_array; //i=>1, 3=>0, 4=>1
            $this->cart[$this->options_index]->options_array3D=$this->options_array3D; //i=>'coke' 3=>'fanta
            if ($this->is_optionsComplete($this->options_array)){
               //$this->exit_options();
               $this->is_optionsComplete=true;
            }
            $this->updateCart();
            $this->dispatch('scrollBtnStatus'); 
            return 0;
        }
        
        //Need to determine if subfolder or item
        //if current_mealtypeid=2 or 4 we need to determine if it is the 1st time
        //The 1st time eamns no cost
        if (($this->current_mealTypeID==2) ||  ($this->current_mealTypeID==4)) { //subfolder
            if (!$price){  //1st timesubfolder click uses itemsubfoldertable
                $json = DB::table("itemsubfoldertable") 
                    ->where('companyID', $companyID)
                    ->where('id', $menuID) 
                    ->value('json');
                //The key here is either item (for 2) or meal(for 4) so we need to go bak bak here -1
                $tempKey=$this->get_jsonKeyFromMealTypelD($this->current_mealTypeID-1);
                $decodedJson = json_decode($json, true);
                $values = $decodedJson[$tempKey];
                $tempTableName=$tempKey . "table";                
                $results = DB::table($tempTableName) 
                    ->where('companyID', $companyID) 
                    ->whereIn('id', $values)
                    ->orderBy('position', 'asc')
                    ->get();
                
                $this->previous_name=$this->current_name;
                $this->previous_results=$this->current_results;
                $this->current_name=$menuName;
                $this->current_results=$results;
                $this->is_backVisible=true;
                //Keep for options reversl
                if (in_array($this->current_mealTypeID, [4])){
                     $this->keep_current_results=$this->current_results;
                }
                $this->dispatch('scrollBtnStatus'); 
                return 0;
            }  
            //Now this is if it is priced: Then it must be item or meal
            $key=$this->get_jsonKeyFromMealTypelD($this->current_mealTypeID-1);
        }else{ //This was never a subfolder. It is an item or a meal
    
             //This meal is either a meal or an item
             $key=$this->get_jsonKeyFromMealTypelD($this->current_mealTypeID); 
        }
        $tableName=$key . "table";
        $json = DB::table($tableName) 
            ->where('companyID', $companyID)
            ->where('id', $menuID) 
            ->value('json');
         
        //if json is not null then it contains the addOns Id which needs to be converted to key value
        //the ids are the key and the values 1. this is the inital  state
        //e.g. [2,3,4] converted to [2=>1, 3=>1, 4=>1]    
        //if the price is known simply save it to the cart
        $addOns=[];
        if ($json){
            if (in_array($this->current_mealTypeID, [1, 2])){   
                //get the values
                $addOns=$this->get_jsonValue($json, "addons"); //[1,3,6]
                $addOns = array_combine($addOns, array_fill(0, count($addOns), 1)); //[1=>1,3=>1,6=>1]
            }elseif  (in_array($this->current_mealTypeID, [3, 4])){
                //This is a meal: we have options and items that make up the meal
                //The options are inside the subfolder table
                $values=$this->get_jsonValue($json, "itemsubfolder"); //[9]
                if ($values){
                    $results = DB::table("itemsubfoldertable") 
                        ->where('companyID', $companyID)
                        ->whereIn('id', $values) 
                        ->get();
                     $this->init_options($results);  //select drinks, select the flies select the drips                   
                }
                //we need to create the options array like 9=>0,3=>0 9 and 3 are subfolders like
                //drinks and fries.They have not yet been selected so they are 0. once selected  it bomes
                //9=>2, 3=>7 2 and 7 are 
                $this->options_array=array_fill_keys(array_values($values), 0); //9=>0,6=>0   
                $this->options_array3D=array_fill_keys(array_values($values), ''); //9=>'',6=>''             
                
                //consitiieunt parts of the meal
                $values=$this->get_jsonValue($json, "item"); //[9]
                if ($values){
                    $results = DB::table("itemtable") 
                        ->where('companyID', $companyID)
                        ->whereIn('id', $values)
                        ->get();
                        /* 
                          //$meal_items_addOns
                        [
                          3=>"{"addons":[1,3,6]}",  5=>"addons":[7,3,16]}", 17=>"addons":[11,32,16]}"
                        ]
                           $this->meal_item
                           [1=>"Fries", 3=>"Bueger"]
                           $this->meal_items_addOns
                           3=>[1,3,4], 5=>[7,13,16], 17=>[11=>1,32=>1,16=>1]
                        */
                    $meal_items_addOns=$results->pluck('json', 'id')->toArray();  
                    $meal_itemNames_addOns=$results->pluck('json', 'name')->toArray();  
                    $this->meal_items=$results->pluck('name', 'id')->toArray();
                    $this->meal_items_addOns=$this->set_meal_items_addOns($meal_items_addOns);
                    $this->meal_items_addOns3D=$this->set_meal_items_addOns3D($meal_itemNames_addOns);
                }
               
            }    
        }
        $this->details_qty=1;  //1st time
        $itemObj=new \stdClass();
        $itemObj->name=$menuName;
        $itemObj->price= $price;  
        $itemObj->id=$menuID;
        $itemObj->qty=$this->details_qty;
        $itemObj->addOns=$addOns;
        $itemObj->addOns3D=[]; //where names are stored
        $itemObj->mealTypeID=$this->current_mealTypeID;        
        $itemObj->options_array=$this->options_array;
        $itemObj->options_array3D=[];
        $itemObj->meal_items=$this->meal_items; //[1=>"fries", 12=> "Burger"]: To allow us list the items
        $itemObj->meal_items_addOns=$this->meal_items_addOns; //[1=>[1=>1,2=>1], 12=>[3=>1.5=>1]]
        $itemObj->meal_items_addOns3D= $this->meal_items_addOns3D; //Where names are stored

        array_push($this->cart,$itemObj);
        $this->updateCart();
        $this->options_index=count($this->cart)-1;
        $this->details_index=$this->options_index; //needed for the counter
        $this->details_mealTypeID=$this->current_mealTypeID;
        $this->dispatch('scrollBtnStatus');    
    }    

    public function show_mealOptions($id,$index,$name){
        $companyID=Auth::user()->companyID;
        $json = DB::table("itemsubfoldertable") 
            ->where('companyID', $companyID)
            ->where('id', $id) 
            ->value('json');
        //{item:[3,4]}
        $value=$this->get_jsonValue($json, "item"); //[3,4]
      
        $results = DB::table("itemtable") 
            ->where('companyID', $companyID)
            ->whereIn('id', $value) 
            ->get();//fanta, coke, spirite
        $this->current_name=$name;
        $this->current_results=$results;  
       // $this->options_index=$index;
        $this->options_key=$id;
        //$this->options_backVisible= $this->is_backVisible;
        $this->dispatch('scrollBtnStatus'); 
    }


    public function empty_cart(){
        //dump($this->cart);
        $this->cart=[];
        $this->meal_items_addOns3D=[];
        //$this->addOns3D=[];
        $this->empty_auxCart();
        $this->updateCart();
    }
    
    private function empty_auxCart(){
        $this->payment_method=null; //Added for polling
        $this->collectionMethod=null;
        $this->display='';
        $this->totalCost=0;
    }
    private function  updateCart(){
        $subTotal=0;
        $totalOrder=0;
        $tax=Auth::user()->companySettings->tax;
        $discount=Auth::user()->companySettings->discount;
        foreach ($this->cart as $obj) {
            $subTotal += $obj->price *  $obj->qty;  // Add price to total
            $totalOrder += $obj->qty;  // Add order to total
            //$this->js('console.log('  . $totalOrder  . ')');
        }
        $this->totalOrder=$totalOrder; 
        $this->discount= $subTotal * $discount / 100;
        $this->subTotal = $subTotal + $this->discount; 

        $this->totalCost = $subTotal + ($subTotal * $tax / 100); 
        $this->tax=$this->totalCost-$subTotal;

        $this->totalCost= number_format($this->totalCost, 2);
        $this->tax= number_format($this->tax, 2);
        $this->subTotal= number_format($this->subTotal, 2);
        $this->syncCartToCache();
    }
    private function syncCartToCache(){
        $companyID=Auth::user()->companyID;
        $key = "cdu_polling:{$companyID}:{$this->userID}:{$this->CDUpolling}";
        $aux_cart=[
            'payment_method' => $this->payment_method,
            'collection_method' => $this->collectionMethod,
            'amount_tendered' => is_numeric($this->display) || $this->display === '0' ? number_format($this->display, 2) : '',
            'total_cost' => number_format($this->totalCost, 2),
            'change_given' => number_format($this->change_given,2),
        ];
        $data = [
            'cart' => $this->cart,
            'aux_cart' =>$aux_cart,
            'timestamp' => now()->timestamp,
        ];
        Cache::put($key, $data, now()->addMinutes(10));
    }
    //This is for updating order views and kitchen
    private function syncCustomerOrder(){
        $companyID=Auth::user()->companyID;
        $key = "cart_polling:{$companyID}:{$this->orderPolling}";
        $data = [
            'timestamp' => now()->timestamp,
        ];
        Cache::put($key, $data, now()->addMinutes(10));
    }
    private function update_auxCart(){
        $this->syncCartToCache();
    }
    //return 1 means successful processing 0 means failed
    private function process_payment(){
        if ($this->is_card){
            //card processing

            //Database processing
            return 1;
        }elseif($this->is_cash) {
            //datbase processing

            return 1;
        }elseif($this->is_mobile){
             //Mobile processing

             //database processing
             return 1;
        }elseif($this->is_voucher){
            //database processing

            return 1;
        }elseif ($this->is_house){
            //databsse processing

            return 1;
        }
    }
    public function show_payment(){
        $this->display='';
        $this->is_mainMenu=false;
        $this->is_payment=true;
        $this->is_setPayment=true;
        $this->payment_reset();
        $this->is_cash=true;
        $this->payment_method=$this->payNames[0];
        $this->update_auxCart();
    }

    public function show_cashPayment(){
        $this->payment_reset();
        $this->is_cash=true;
        $this->paymentLabel=$this->cashTendered;
    }

    public function show_cardPayment(){
        $this->payment_reset();
        $this->dispatch('init_cardPayment');
    }
    public function complete_cardPayment(){
        $this->is_card=true;
        $this->is_paymentProcessing=true;
        $this->payment_method=$this->payNames[1];
        $this->update_auxCart();
        $this->dispatch('stop-cardProcessing'); 
    } 
    #[On('stop-cardProcessing')] 
    public function stop_cardProcess(){
        sleep(4);
        $this->is_paymentProcessing=false;
        $this->is_paymentSucceed=$this->process_payment();
        //Normal
        if ($this->is_showSalesOrder){
            $this->init_pay_into_orderstable();
        }else{
            //This is for pay_from
            $this->update_pay_in_orderstable();
        }
        //On successful
        if ($this->is_paymentSucceed==1){
            $this->printReceipt();
        }
    }
    public function  show_mobilePayment(){
        $this->payment_reset();
        $this->dispatch('init_mobilePayment');
    }
    public function complete_mobilePayment(){
        $this->is_mobile=true;
        $this->payment_method=$this->payNames[2];
        $this->is_paymentProcessing=true;
        $this->update_auxCart();
        $this->dispatch('stop-cardProcessing');
    }
    public function show_voucher(){
        $this->payment_reset();
        $this->is_voucher=true;
        $this->payment_method=$this->payNames[3];
        $this->update_auxCart();
        $this->paymentLabel="Voucher Amount";
        
    }
    public function show_onTheHouse(){
        $this->payment_reset();
        $this->dispatch('init_onTheHousePayment');
    }
    public function complete_onTheHouse(){
        $this->is_house=true;
        $this->payment_method=$this->payNames[4];
        $this->update_auxCart();
        $this->is_paymentSucceed=$this->process_payment();
         //Normal
         if ($this->is_showSalesOrder){
            $this->init_pay_into_orderstable();
        }else{
            //This is for pay_from
            $this->update_pay_in_orderstable();
        }
    }
    //Also called by cancel payments
    public function show_menu(){
        $this->is_mainMenu=true;
        $this->is_payment=false;
        $this->is_setPayment=false;
        $this->updateCart(); //this is to set the totalCost to be used by cancel from pay
        $this->is_paymentSucceed=false;
        $this->is_showSalesOrder=true;
    }

    public function init_menu(){
        $this->show_menu();
        $this->dispatch('initCategoryList'); 

    }

    private function set_discount(){

    }
    private function cancel_discount(){

    }
    public function toggleDiscount() { 
        $this->is_setDiscount = !$this->is_setDiscount; // Call a method based on the status 
        if ($this->is_setDiscount) { 
            $this->set_discount(); 
        } else { 
            $this->cancel_discount(); 
        }
    }
    
    private function get_jsonValue($jsonString, $key) {
        if ($jsonString === null) {
            return null;
        }
        // Remove surrounding quotes if present
        $jsonString = trim($jsonString, '"');
        $decodedJson = json_decode($jsonString, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON string');
        }
        return $decodedJson[$key] ?? null;
    }

    private function set_addOns($addOns,$companyID){
        //get al the add-ons names
        $results = DB::table("addonstable") 
          ->where('companyID', $companyID) 
          ->whereIn('id', array_keys($addOns))
          ->orderBy('position', 'asc')
          ->pluck('name', 'id');   
          
        // Combine labels and states into a single array
        $combined = [];
        foreach ($addOns as $key => $addOn) {
            $combined[] = [
                'id' => $key,
                'name' => $results[$key] ?? 'Unknown', // Use 'Unknown' if a label is not found
                'state' => $addOn,
            ];
        } 
        $this->addOns= $combined ;
     }

    private  function get_optionsForMeal($mealID){

        // Step 1: Get the JSON field from the mealtable
        $jsonField = DB::table('mealtable')
        ->where('id', $mealID)
        ->value('json'); // Retrieve only the JSON field

        // Step 2: Decode the JSON to extract the `itemsubfolder` values
        //[2,4]
        $jsonData = json_decode($jsonField, true); // Decode JSON into an associative array
        $itemSubfolderIds = $jsonData['itemsubfolder'] ?? []; // Get the itemsubfolder values, or an empty array if not found

        // Step 3: Query the itemsubfoldertable using the extracted IDs
        $result = DB::table('itemsubfoldertable')
        ->whereIn('id', $itemSubfolderIds)
        ->get(); // Retrieve all matching rows

        // Return the result or process it further
        return $result;
    }
    //There is no need to have all these parameters
    //all thath is needed is is the index and then we query it based on the cart
    //we all learn
    public function show_itemDetails($name, $qty,  $mealTypeID, $index){
        $this->details_mealTypeID=$mealTypeID;        
        //when options is true do not show details
        if ($this->is_options){
            return 0;
        }

        $this->details_name=$name;
        $this->details_index=$index;
        $this->details_qty=$qty;
    
        //we need to get the options
        if (in_array($mealTypeID, [3,4])){
            $mealID= $this->cart[$index]->id;
            $results=$this->get_optionsForMeal($mealID);      
            $this->init_options($results);
            $this->is_optionsComplete=true;   
            return 0;
        }      
          

        $this->addOns=[];
        $this->is_mainMenu = false;
        $this->is_itemDetails=true;
        $this->is_menuBtns=false;
        $companyID=Auth::user()->companyID;
      
              
        $addOns= $this->cart[$index]->addOns; //no need to pass this json anymore get it from cart[index]
        
        //Customisation of statndard item
        if (in_array($mealTypeID, [1,2])){
            $this->is_customiseMeal=false;
            $this->set_addOns($addOns,$companyID);
        }
        
    }
    
    private function updateArrayValue (array $mainArray,  $key,  array $newValue): array {
        // Check if the key exists in the main array
        if (array_key_exists($key, $mainArray)) {
            // Update only the existing values in the main array
            foreach ($newValue as $subKey => $value) {
                if (array_key_exists($subKey, $mainArray[$key])) {
                    $mainArray[$key][$subKey] = $value;
                }
            }
        }
        
        return $mainArray;
    }
    //two
    public function updateAddOn($index, $value){
        $this->addOns[$index]['state']=$value;
        $this->addOns = $this->addOns;
        //index is index of the addOn 0-3 how many addons are there@
        //convert back to key=> value
        $convertedArray = collect($this->addOns)->pluck('state', 'id')->toArray();
        if (in_array($this->details_mealTypeID, [1,2])){
            $this->cart[$this->details_index]->addOns=$convertedArray; //i=>1, 3=>0, 4=>1
            $this->cart[$this->details_index]->addOns3D=$this->addOns;// Thses contain salt, pepper etc
            $this->cart[$this->details_index]->meal_items_addOns3D=[];
            $this->meal_items_addOns3D=[];
         }elseif (in_array($this->details_mealTypeID, [3,4])){
            $this->meal_items_addOns3D= $this->cart[$this->details_index]->meal_items_addOns3D;  // Thses contain salt, pepper e   
            $this->meal_items_addOns= $this->cart[$this->details_index]->meal_items_addOns;  // Thses contain salt, pepper e   

            $convertedArray_meal=$this->updateArrayValue($this->meal_items_addOns, $this->details_itemKey, $convertedArray);
            $convertedArray_meal3D=$this->updateArrayValue($this->meal_items_addOns3D, $this->details_name, $this->addOns);

            $this->cart[$this->details_index]->meal_items_addOns=$convertedArray_meal ;      //$convertedArray; //i=>1, 3=>0, 4=>1
            $this->cart[$this->details_index]->meal_items_addOns3D= $convertedArray_meal3D;  // Thses contain salt, pepper e
            //$this->addOns=[];
            $this->cart[$this->details_index]->addOns3D=[];
        }
        $this->updateCart();
    }    
    private function update_details_qty(){
        $this->cart[$this->details_index]->qty=$this->details_qty; 
        $this->updateCart();
    } 

    public function decreaseQty(){
        if ($this->details_qty > 1) {
            $this->details_qty--;
        }
        $this->update_details_qty();
    }
    public function increaseQty(){
        if ($this->details_qty < 20) {
            $this->details_qty++;
        }
        $this->update_details_qty();
    }
    public function close_itemDetails(){
        $this->is_mainMenu = true;
        $this->is_itemDetails=false;
        $this->is_menuBtns=true;
    }
    
    private function removeObjectAtIndex($array, $index) {
        if (isset($array[$index])) {
            unset($array[$index]); // Remove the object at the specified index
        }
        // Reindex the array to maintain numeric indexing
        $array = array_values($array);
        return $array;
    }

    
    private function get_addOns_from_itemKey($key, $array){
        return $array[$key] ?? null; 
    }

    //This is from customised meal
    public function get_addOns_from_meal_item($id,$name){
        //3=>[1=>1,3=>1,6=>1],  
        //    5=>[7=>1,3=>1,16=>1],
		//	17=>[11=>1,32=>1,16=>1
        $this->details_name=$name;
        $this->details_itemKey=$id;
        //These 2 above changes the meal's details to to items's details
        $this->meal_items_addOns3D= $this->cart[$this->details_index]->meal_items_addOns3D;  // Thses contain salt, pepper e   
        
        $this->addOns=$this->get_addOns_from_itemKey($name,$this->meal_items_addOns3D);
      
    }
    public function customise_meal(){ 
        $this->exit_options();
        $this->is_mainMenu = false;
        $this->is_itemDetails=true;
        $this->is_menuBtns=false;
        $this->is_customiseMeal=true;
        $this->addOns=[]; //clears the original listing in details;
    }
    public function delete_meal(){
        $this->cart=$this->removeObjectAtIndex($this->cart,$this->details_index);    
        if ($this->is_options){
            $this->exit_options();
        }else{
            $this->close_itemDetails();
        }    
        $this->updateCart();
        $this->dispatch('scrollBtnStatus');
    }

    public function close_mealDetails(){
        $this->exit_options();   
    }
   
    public function done_menu(){
        $this->show_menu();
    }

   
    public function processOupdateBlankKey($position, $value){
        foreach ($this->blankKeys as $index => $key) {
            if ($key['position'] == $position) {
                $this->blankKeys[$index]['value'] = $value;
                break;
            }
        }
    }
    private function paymentSucceed(){
        $this->is_paymentSucceed=true;
        $this->syncCustomerOrder();
    }

    public function startProcessing(){
        // After 2 seconds, update the variable
        sleep(2);
        //$this->is_paymentLoading  = true;
    }
    private function init_pay_into_orderstable(){
        $appController = app(AppController::class);
        $outputX= $this->processOrderItems();
        $this->receiptData= $this->initialize_receiptItems();
        // Set the desired timezone
        $timezone = Auth::user()->companySettings->timezone;
        $companyID= Auth::user()->companyID;
        $this->orderNo=  $appController->get_orderNo($this->receiptData, $outputX['addOn'], $outputX['ingredients'], $companyID, $timezone);
        $this->update_auxCart();
        $this->is_paymentSucceed=1;
    }
    private function update_pay_in_orderstable(){
        $this->is_paymentSucceed=1;
        
        // Input variables
        $totalCost = $this->totalCost;
        $changeGiven = $this->change_given;
        $cashierId = Auth::user()->id;
        $cashierName = Auth::user()->firstName . ' '. Auth::user()->lastName ;
        $paymentMethod = $this->payment_method;
        $companyID =$this->companyID;
        $orderNo = intval($this->orderNo); //convrt to numeric

        //dump($companyID);
        //dump($orderNo);

        DB::table('orderstable')
            ->where('companyID', $companyID)
            ->where('orderNo', $orderNo)
            ->where('isReady', 0)
            ->orderBy('orderDatetime', 'desc')
            ->limit(1)
            ->update([
                'order_json->total_cost' => $totalCost,
                'order_json->change_given' => $changeGiven,
                'order_json->cashier_id' => $cashierId,
                'order_json->cashier_name' => $cashierName,
                'order_json->payment_method' => $paymentMethod,
                'isReady' => 1
            ]);

      
    }
    public function checkBalance(){
        if ((float)$this->display >= $this->totalCost) {
            $this->change_given=$this->display - $this->totalCost;
            $this->paymentSucceed();
            //Normal
            if ($this->is_showSalesOrder){
                $this->init_pay_into_orderstable();
            }else{
                //This is for pay_from
                $this->update_pay_in_orderstable();
            }
            $this->dispatch('printReceipt'); //This is to reduce time processing 
           // dump($outputX['ingredients']);
       }
    }

    #[On('printReceipt')] 
    public function printReceipt(){
        $timezone = Auth::user()->companySettings->timezone;
        $this->receiptDate = Carbon::now($timezone)->format('Y-m-d H:i:s');
        DB::table('paymenttable')->insert([
            'dateTime' => $this->receiptDate, // Replace with dynamic user ID
            'companyID'=> Auth::user()->companyID,
            'userName' => Auth::user()->email,
            'payNameID' => array_search($this->payment_method, $this->payNames),
            'amount' =>  $this->totalCost,
            'collectionMethod' => $this->collectionMethod,
            'medium' => "Cashier"
        ]);
        $this->dispatch('set_printerIPaddress');

    }
    public function complete_printReceipt(){
            $this->print_to_receiptPrinter($this->receiptData, $this->orderNo,
                 $this->receiptDate,$this->printerIPaddress, true);
    }

    public function menu_backFunc(){
        if ($this->is_options){
            array_pop($this->cart);
            $this->exit_options();
            $this->updateCart();
            $this->dispatch('scrollBtnStatus'); 
            return 0;
        }
        $this->current_name=$this->previous_name;
        $this->current_results=$this->previous_results;
        $this->is_backVisible=false;
        $this->updateCart();
        $this->dispatch('scrollBtnStatus'); 
    }

    private function initialize_receiptItems(){
        $processedArray = [];
        foreach ($this->cart as $item) {
            $processedItem = [
                'name'  => $item->name,
                'price' => $item->price,
                'qty'   => $item->qty,
                'mealTypeID' => $item->mealTypeID,
            ];
            if (in_array($item->mealTypeID, [1, 2])) {
                // Process addOns3D
                $addOns3D = [];
                foreach ($item->addOns3D as $addon) {
                    if ($addon['state'] == 0) {
                        $addOns3D[] = "No " . $addon['name'];
                    } elseif ($addon['state'] == 1) {
                        $addOns3D[] = "Extra " . $addon['name'];
                    }
                }
                if (!empty($addOns3D)) {
                    $processedItem['addOns3D'] = $addOns3D;
                }
            }
            if (in_array($item->mealTypeID, [3, 4])) {
                $processedItem['offers'] = array_values($item->options_array3D);

                // Process meal_items_addOns3D
                $mealItemsAddOns = [];
                foreach ($item->meal_items_addOns3D as $mealName => $addons) {
                    $filteredAddOns = [];
                    foreach ($addons as $addon) {
                        if ($addon['state'] == 0) {
                            $filteredAddOns[] = "No " . $addon['name'];
                        } elseif ($addon['state'] == 2) {
                            $filteredAddOns[] = "Exra " . $addon['name'];
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
        $processedArray['payment_method']=$this->payment_method;
        $processedArray['collection_method']=$this->collectionMethod;
        if($this->display){ //to handle non cash payments
            $processedArray['amount_tendred']=number_format($this->display,2);
        }
        $processedArray['total_cost']= number_format($this->totalCost, 2);
        $processedArray['change_given']=number_format($this->change_given,2);
        $processedArray['currency']=Auth::user()->companySettings->currency ;
        $processedArray['cashier_id']= Auth::user()->id;
        $processedArray['cashier_name']= Auth::user()->firstName . ' '. Auth::user()->lastName ;
        $processedArray['medium']="Cashier";
        //dump($processedData);
        return $processedArray;
    }


    public function processOrderItems(){
        $totalOccurrences = count($this->cart); // Total records in the array
        $combinedAddOns = [];
        $combinedIngredients = [];
        foreach ($this->cart as $item) {
            $id = $item->id;
            $qty = $item->qty; // Always 1 per entry
            $mealTypeID = $item->mealTypeID;

            // Initialize arrays for this iteration
            $addOnsCount = [];
            $ingredients = [];

            // Process meal types 3 and 4
            if ($mealTypeID == 3 || $mealTypeID == 4) {
                $meal_items = array_keys($item->meal_items);
                $options_array = array_values($item->options_array);
                $meal_items=array_merge($meal_items,$options_array);
                foreach ($meal_items as $meal_item) {
                    if (isset($item->meal_items_addOns[$meal_item])) {
                        foreach ($item->meal_items_addOns[$meal_item] as $key => $value) {
                            if ($value > 0) { //Only take the addons that are included
                                // Count how many times each add-on appears
                                if (!isset($addOnsCount[$key])) {
                                    $addOnsCount[$key] = 0;
                                }
                                $addOnsCount[$key] += $value;
                            }
                        }
                    }

                    // Fetch ingredients for each meal_item
                    $ingredientsJson = DB::table('itemtable')
                        ->where('id', $meal_item)
                        ->value('ingredients_json');

                    $mealIngredients = json_decode($ingredientsJson, true);

                    // Combine ingredients
                    foreach ($mealIngredients as $ingredientKey => $ingredientValue) {
                        if (!isset($ingredients[$ingredientKey])) {
                            $ingredients[$ingredientKey] = $ingredientValue;
                        } else {
                            $ingredients[$ingredientKey] += $ingredientValue;
                        }
                    }
                }
                // Multiply occurrences by qty (not total count!)
                foreach ($addOnsCount as $key => $count) {
                    $combinedAddOns[$key] = ($combinedAddOns[$key] ?? 0) + ($count * $qty);
                }

                // Multiply ingredients by qty
                foreach ($ingredients as $key => $value) {
                    $combinedIngredients[$key] = ($combinedIngredients[$key] ?? 0) + ($value * $qty);
                }
            }
        
            // Process meal types 1 and 2
            if ($mealTypeID == 1 || $mealTypeID == 2) {
                foreach ($item->addOns as $key => $value) {
                    if ($value > 0) {
                        $combinedAddOns[$key] = ($combinedAddOns[$key] ?? 0) + ($value  * $qty);
                    }
                }

                // Fetch ingredients based on item id
                $ingredientsJson = DB::table('itemtable')
                    ->where('id', $id)
                    ->value('ingredients_json');
                $mealIngredients = json_decode($ingredientsJson, true);
                // Initialize an empty array to store combined ingredients
                $combinedIngredients = [];
                // Multiply ingredients values by qty and consolidate the keys
                foreach ($mealIngredients as $key => $value) {
                    if (isset($combinedIngredients[$key])) {
                        $combinedIngredients[$key] += $value * $qty;
                    } else {
                        $combinedIngredients[$key] = $value * $qty;
                    }
                }
            }
        }
       $outputX['addOn']= $combinedAddOns;
       $outputX['ingredients']=$combinedIngredients;
       return $outputX;
       //dump($combinedAddOns);
       //dump($combinedIngredients);
    }

    public function eatInFunc(){
        $this->show_payment();
        $this->closeModal();
        $this->collectionMethod=$this->eat_in;
        $this->update_auxCart();
    }
    public function takeAwayFunc(){
        $this->show_payment();
        $this->closeModal();
        $this->collectionMethod=$this->take_away;
        $this->update_auxCart();
    }
    public function openModalCollectionMethod()
    {
        $this->paymentLabel=$this->cashTendered;
        $this->showModal = true;
        $this->modalSize = 'modal-md-down';
        $this->modalFile = 'livewire.cashier.inc.collection-method';
    }
   
    public function openModalFunctions()
    {
        $this->showModal = true;
        $this->modalSize = 'modal-fullscreen';
        $this->modalFile = 'livewire.cashier.inc.modal-functions';
    }
    public function closeModal()
    {
        $this->showModal = false;
        $this->collectionMethod=null;
        $this->update_auxCart();
        $this->updateCart(); //this is to set the totalCost to be used by cancel from pay
        $this->is_showSalesOrder=true;
    }

    // Total Sales including Refund Adjustment
    public function getTotalSales(){
        $timezone = Auth::user()->companySettings->timezone;
        $companyID = Auth::user()->companyID;
        // Set the timezone for today's date
        $today = Carbon::now($timezone)->startOfDay();
        // Step 1: Sales data grouped by payment method
        $salesData = DB::table('paymenttable')
            ->select('payNameID', DB::raw('SUM(amount) as totalAmount'))
            ->where('companyID', $companyID)
            ->whereDate('dateTime', $today->toDateString())
            ->groupBy('payNameID')
            ->get();
        // Step 2: Get payment method names
        $payNames = DB::table('paytable')
            ->select('payNameID', 'payName')
            ->whereIn('payNameID', $salesData->pluck('payNameID'))
            ->get()
            ->keyBy('payNameID');
        // Step 3: Combine payment data
        $totalSales = [];
        $subTotal = 0;

        foreach ($salesData as $sale) {
            $payNameID = $sale->payNameID;
            $amount = $sale->totalAmount;

            $totalSales[] = [
                'payName' => $payNames[$payNameID]->payName ?? 'Unknown',
                'amount' => $amount,
            ];

            $subTotal += $amount;
        }

        // Step 4: Calculate today's refunds
        $refund = DB::table('refundtable')
            ->where('companyID', $companyID)
            ->whereDate('refundDateTime', $today->toDateString())
            ->sum('amount');

        // Step 5: Final total after refund
        $finalTotal = $subTotal - $refund;

        // Step 6: Add subtotal, refund and final total to array
        $totalSales[] = [
            'payName' => 'SubTotal',
            'amount' => $subTotal,
        ];
        $totalSales[] = [
            'payName' => 'Refund',
            'amount' => $refund,
        ];
        $totalSales[] = [
            'payName' => 'Total',
            'amount' => $finalTotal,
        ];

        $this->totalSales = $totalSales;
    }

    public function generate()
    {
        // Validate if the order number is empty
        if (empty($this->orderNo)) {
            $this->showError = true;
        } else {
            $this->showError = false;
            // Perform any additional logic here (e.g., API call, database query)
            if ($this->order_menu=="pay"){
                 $menu_order=$this->get_orderNoFromPayment();
            }elseif ($this->order_menu=="cancel"){
                $menu_order=$this->get_orderNoForCancellation();
            }
            $this->menu_order = json_decode($menu_order, true);
            $this->totalCost=$this->get_totalCost($this->menu_order);
            $this->collectionMethod=$this->get_collectionMethod($this->menu_order);
        }
    }


    //This includes ones with datetime: Refund
    public function generateV2(){
        // Validate if the order number is empty
        if (empty($this->orderNo)) {
            $this->showError = true;
        }
        if (!$this->isValidDateTimeFormat($this->orderDateTime)) {
            $this->showErrorV2 = true;
            $this->errorMsgV2 = 'Please enter a valid date and time in the format YYYY-MM-DD HH:MM:SS.';
            $this->showErrorV2 = true;
        }
        if (empty($this->orderDateTime)) {
            $this->showErrorV2 = true;
            $this->errorMsgV2 = 'The Purchase Date and Time field is required.';
        }
        if (($this->showError)||($this->showErrorV2)){
            //Do nothing
        }else {
            $this->showError = false;
            $this->showErrorV2=false;
            // Perform any additional logic here (e.g., API call, database query)
            $menu_order=$this->get_orderNoForRefund();
            $this->menu_order = json_decode($menu_order, true);
            $this->totalCost=$this->get_totalCost($this->menu_order);
            $this->is_refundReady=true;
        }
    }
  
    public function cashUp_endOfDay(){
        $conditions = [
            ['companyID', '=', $this->companyID],
        ];
        $this->setup_archive("paymenttable", "paymenttable_archive",$conditions);
        $this->setup_archive("refundtable", "refundtable_archive",$conditions);
        $this->getTotalSales();
    }
    public function print_cashUp_endOfDay(){
        $this->getTotalSales();
        $this->dispatch('set_printerIPaddress_endOfDay');
    }
    public function complete_print_cashUp_endOfDay(){
        // Connect to the network printer (update IP and port as needed)
        $connector = new NetworkPrintConnector($this->printerIPaddress, 9100);
        $printer = new Printer($connector);
        $totalSales=$this->totalSales;
        // Initial setup
        $timezone = Auth::user()->companySettings->timezone;
        $currentDate = Carbon::now($timezone)->format('Y-m-d H:i:s');
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer->selectPrintMode();
        $printer->feed();
        // Print header with some emphasis
        $printer->setEmphasis(true);
        // Create header - set column widths
        $typeWidth = 24;
        $amountWidth = 16;
        // Print headers
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text(str_pad("Type", $typeWidth));
        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->text(str_pad("Amount", $amountWidth) . "\n");
        $printer->text(str_repeat("-", $typeWidth + $amountWidth) . "\n");
        $printer->setEmphasis(false);
        $currency= Auth::user()->companySettings->currency ;
        // Print data rows
        $lastIndex = count($totalSales) - 1;
        foreach ($totalSales as $index => $payment) {
            if ($index === $lastIndex) {
                // This is the last row (total) - make it emphasized
                $printer->setEmphasis(true);
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text(str_pad("Total", $typeWidth));
                $printer->setJustification(Printer::JUSTIFY_RIGHT);
                $printer->text(str_pad($currency . number_format($payment['amount'], 2), $amountWidth) . "\n");
                $printer->setEmphasis(false);
            } else {
                // Regular row
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text(str_pad($payment['payName'], $typeWidth));
                $printer->setJustification(Printer::JUSTIFY_RIGHT);
                $printer->text(str_pad($currency . number_format($payment['amount'], 2), $amountWidth) . "\n");
            }
        }
        // Footer
        $printer->feed(2);
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("End of the Day " . $currentDate . "  \n");
        // Cut the receipt (if printer supports it)
        $printer->cut();
        // Close the printer
        $printer->close();
    }
    private function isValidDateTimeFormat($value): bool
    {
        // Define the regex pattern for YYYY-MM-DD HH:MM:SS
        $pattern = '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/';
        // Check if the value matches the pattern
        return preg_match($pattern, $value) === 1;
    }
    public function updatedOrderNo($value)
    {
        // Reset error message when the user starts typing
        $this->showError = false;
    }
    private function get_totalCost($data){
        $totalCost = 0;
        // Iterate through the numeric keys in the JSON array
        if (!empty($data)){
            foreach ($data as $key => $item) {
                // Check if the key is numeric (to avoid processing non-item fields like "medium", "currency", etc.)
                if (is_numeric($key)) {
                    // Ensure the price is treated as a float and add it to the total cost
                    $totalCost += (float) $item['price'] * $item['qty'];
                }
            }
        }
        return number_format($totalCost, 2); // Format to 2 decimal places
    }
    public function init_generateOrderNo()
    {
        $this->orderNo='';
        $this->showError = false;
        $this->showErrorV2=false;
        $this->menu_order=[];
        $this->totalCost=0;
        $this->orderDateTime='';
        $this->is_refundReady=false;
    }
    public function get_orderNoFromPayment(){
        $menu_order = DB::table('orderstable')
            ->where('orderNo', $this->orderNo)
            ->where('companyID', $this->companyID)
            ->where('isReady', 0)
            ->orderBy('orderDatetime', 'desc')
            ->value('order_json');
        return $menu_order;
    }
    public function pay_fromOrder(){
        $keep_totalCost=$this->totalCost;
        $keep_collectionMethod=$this->collectionMethod;
        $this->show_payment();
        $this->closeModal();
        $this->totalCost=$keep_totalCost;
        $this->collectionMethod=$keep_collectionMethod;
        $this->is_showSalesOrder=false;
    }
    public function cancel_fromOrder(){
        DB::table('orderstable')
            ->where('orderNo', $this->orderNo)
            ->where('companyID', $this->companyID)
            ->where('isReady', '>', 0)
            ->where('isReady', '<', 3)
            ->update(['isReady' => 6]);
        $this->closeModal();
        $this->syncCustomerOrder();
        $this->dispatch('cancel_orderSuccessMsg');
    }
    public function refund_order(){
        DB::table('orderstable')
            ->where('orderNo', $this->orderNo)
            ->where('companyID', $this->companyID)
            ->where('isReady', '>', 0)
            ->where('orderDatetime', $this->orderDateTime)
            ->update(['isReady' => -1]);
        //This is into the paymenttable
        $this->insert_refundtable();
        $this->closeModal();
        $this->syncCustomerOrder();
        $this->dispatch('refund_orderSuccessMsg');
        $conditions = [
            ['companyID', '=', $this->companyID],
            ['orderNo', '=', $this->orderNo],
            ['orderDatetime', '=',  $this->orderDateTime],
        ];
        $this->setup_archive("orderstable", "orderstable_archive",$conditions);
    }
    private function insert_refundtable(){
        $timezone = Auth::user()->companySettings->timezone;
        $currentDate = Carbon::now($timezone)->format('Y-m-d H:i:s');
        DB::table('refundtable')->insert([
            'refundDateTime' => $currentDate, // Replace with dynamic user ID
            'companyID'=> Auth::user()->companyID,
            'orderDateTime' =>$this->orderDateTime,
            'cashierID'=>Auth::user()->id,
            'orderNo' => $this->orderNo,
            'amount' => $this->totalCost
        ]);
    }
    //waiting (1) and preparing (2)
    public function get_orderNoForCancellation(){
        $menu_order = DB::table('orderstable')
            ->where('orderNo', $this->orderNo)
            ->where('companyID', $this->companyID)
            ->where('isReady', '>', 0)
            ->where('isReady', '<', 3)
            ->value('order_json');
        return $menu_order;
    }
    public function get_collectionMethod($orderData): string{
        if (empty($orderData) || !is_array($orderData)) {
            return '';
        }
         return $orderData['collection_method'] ?? 'N/A';
    }

    public function get_orderNoForRefund(){
        $menu_order = DB::table('orderstable')
            ->where('orderNo', $this->orderNo)
            ->where('companyID', $this->companyID)
            ->where('isReady', '>', 0)
            ->where('orderDatetime', $this->orderDateTime)
            ->value('order_json');
        return $menu_order;
    }    
    //public function close_modalFromPay(){
     //   $this->updateCart(); //this is to set the totalCost to be used by cancel from pay
    //}
    public function verifyPayReference(){
        $this->get_refNoFromTable();
    }

    public function mount(){
        // Initialize blank keys if needed
        $this->blankKeys[0]['value'] = '';
        $this->blankKeys[1]['value'] = '';
        $this->updateCart();
        $this->get_categories();
        $this->eat_in= config('shared.eat_in');
        $this->take_away= config('shared.take_away');
        $this->userID=Auth::user()->id; //Added here becuae of polling
        $this->empty_auxCart(); //due to polling
        $this->updateCart();//due to polling
        $this->payNames = DB::table('paytable')->pluck('payName')->toArray();
        $this->getTotalSales();
        $this->is_pure_payRef=false;
        $this->get_refNoFromTable();
    }

    public function render()
    {
        return view('livewire.cashier.cashier');
    }
}
