<?php

namespace App\Livewire;

use Livewire\Component;
//This is for the logout
use Laravel\Fortify\Contracts\LogoutResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

//** End of Logout */
class FoodApp extends Component
{
    
    public $login = true; 
    public $registration = false;
    public $forgot=false;
    public $menu=false;
    public $kiosk = false; 
    public $kitchen = false;
    public $cashier = false; 
    public $orderDisplay = false;
    public $CDU = false;
    public $admin = false; 
    public $is_printerSettings=false;
    public $printerList =[];
    public $paymentRef=false;
    public $isFromApp=false;
    public $isFromRef=false;

    // Boolean to control whether the confirmation prompt is displayed
    public $showConfirmation;
    
    public $printerName='';
    public $printerName_stored='';
    public $ipAddress;
    public $isSwitchOn = false; // Default state of the switch
    public $companyID;
    public $printerID=-1;
    public $printerTitle="Add a new Printer";
    public $printerStatus=0; // -1 failed, 0 untested 1 pass

    protected $rules = [
        'printerName' => 'required|string|max:255',
        'ipAddress' => 'required|ip|unique:printers,ipAddress,NULL,id,companyID',
    ];

    protected $messages = [
        'printerName.required' => 'Printer Name is required.',
        'ipAddress.required' => 'IP Address is required when Local Connection is off.',
        'ipAddress.ip' => 'The IP Address must be a valid IP format.',
        'ipAddress.unique' => 'The IP Address must be unique within the company.',
    ];

   
    private function resetAll(){
        $this->login = false; 
        $this->registration = false;
        $this->forgot=false;
        $this->menu=false;
        $this->kiosk = false; 
        $this->kitchen = false;
        $this->cashier = false; 
        $this->orderDisplay = false;
        $this->admin = false;
        $this->CDU=false;
        $this->paymentRef=false;
    }

    public function showLoginForm(){
        $this->resetAll();
        $this->login=true; 
    }

    public function showRegisterForm(){
        $this->resetAll();
        $this->registration=true; 
    }
    
    public function showForgotForm(){
        $this->resetAll();
        $this->forgot=true;   
    }
    
    public function showMenu(){
        // Get the referring URL which will contain the actual page URL
        $referrer = request()->header('referer');
        // Alternative approach: use Livewire's HTTP_REFERER or session data
        if (!$referrer) {
            $referrer = request()->server('HTTP_REFERER', '');
        }
        // Check for ref domain or path
        if (str_contains($referrer, config('app.domains.ref')) || 
            str_contains($referrer, '/ref')) {
                $this->isFromRef = true;
                $this->isFromApp = false;
            } 
        // Check for app domain or path
        elseif (str_contains($referrer, config('app.domains.app')) || 
            str_contains($referrer, '/app')) {
                $this->isFromApp = true;
                $this->isFromRef = false;
            } 
        // Default case - neither ref nor app
        else {
            $this->isFromRef = false;
            $this->isFromApp = false;
        }
        $this->resetAll();
        if ($this->isFromApp){
            $this->menu=true;
            $this->is_printerSettings=false;
        }elseif ($this->isFromRef){
            $this->paymentRef=true;
        }
    }

    public $menuResults;
    public function showKiosk(){
        $this->resetAll();
        $this->kiosk=true; 
        //we need to get the meals from the table
        $companyID = Auth()->User()->companyID; // Replace with your actual company ID 
        $this->menuResults = DB::table('itemfoldertable')
                                ->where('companyID', $companyID)
                                ->orderBy('position', 'asc') // Replace 'column_name' with the actual column you want to order by 
                                ->get();

    }

    public function showKitchen(){
        $this->resetAll();
        $this->kitchen=true; 
    }
    
    public function showCashier(){
        $this->resetAll();
        $this->cashier=true;   
    }
    
    public function showOrderDisplay(){
        $this->resetAll();
        $this->orderDisplay=true;  
    }

    public function showCDU(){
        $this->resetAll();
        $this->CDU=true;  
    }
    public function showAdmin(){
        $this->resetAll();
        $this->admin=true;  
    }
    
    public function showPrinterSettings(){
        $this->is_printerSettings=true;
        $this->printerStatus=0;
    }  

    public function logout(){
        //Implement clearing all
        // Log the user out using Fortify's logout functionality
        Auth::logout();

        // Invalidate the session to prevent session fixation attacks
       // session()->invalidate();
  
        // Regenerate the session token for security
       // session()->regenerateToken();

        $this->showLoginForm();
    }
    
    //for network/local printer switch
    public function toggleSwitch(){
        $this->isSwitchOn = !$this->isSwitchOn;
        $this->printerStatus=0;
    }
   

    public function savePrinter() {
        $this->printerStatus=0;
        // Validate the printerName with uniqueness check for the companyID
        $this->validate([
            'printerName' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $exists = DB::table('printertable')
                        ->where('printerName', $value)
                        ->where('companyID', $this->companyID)
                        ->where('keyID', '<>', $this->printerID) // Exclude current record if updating
                        ->exists();
    
                    if ($exists) {
                        $fail('The printer name must be unique within the company.');
                    }
                },
            ],
        ]);
    
        // Additional validation for ipAddress if $isSwitchOn is false
        if (!$this->isSwitchOn) {
            $this->validate([
                'ipAddress' => 'required|ip',
            ]);
    
            // Ensure the ipAddress is unique for the given companyID
            $exists = DB::table('printertable')
                ->where('ipAddress', $this->ipAddress)
                ->where('companyID', $this->companyID)
                ->where('keyID', '<>', $this->printerID) // Exclude current record if updating
                ->exists();
    
            if ($exists) {
                $this->addError('ipAddress', 'The IP Address must be unique within the company.');
                return;
            }
        }
    
        // Determine if this is an insert or update based on keyID
        if (empty($this->printerID) || $this->printerID == 0 || $this->printerID == -1) {
            // Insert a new record
            DB::table('printertable')->insert([
                'printerName' => $this->printerName,
                'ipAddress' => $this->isSwitchOn ? null : $this->ipAddress,
                'companyID' => $this->companyID,
            ]);
        } else {
            // Update an existing record or reject if validation fails
            $existingPrinter = DB::table('printertable')->where('keyID', $this->printerID)->first();
    
            if ($existingPrinter) {
                // Check for conflicts with printerName or ipAddress
                $nameConflict = DB::table('printertable')
                    ->where('printerName', $this->printerName)
                    ->where('companyID', $this->companyID)
                    ->where('keyID', '<>', $this->printerID)
                    ->exists();
    
                if ($nameConflict) {
                    $this->addError('printerName', 'The printer name must be unique within the company.');
                    return;
                }
    
                $ipConflict = DB::table('printertable')
                    ->where('ipAddress', $this->ipAddress)
                    ->where('companyID', $this->companyID)
                    ->where('keyID', '<>', $this->printerID)
                    ->exists();
    
                if (!$this->isSwitchOn && $ipConflict) {
                    $this->addError('ipAddress', 'The IP Address must be unique within the company.');
                    return;
                }
    
                // Perform the update
                DB::table('printertable')
                    ->where('keyID', $this->printerID)
                    ->update([
                        'printerName' => $this->printerName,
                        'ipAddress' => $this->isSwitchOn ? null : $this->ipAddress,
                    ]);
            }
        }
        // Reset form fields and provide feedback
        $this->printerList = DB::table('printertable')->select('*')->where('companyID', $this->companyID)->get();
        $this->reset(['printerName', 'ipAddress']);
        $this->isSwitchOn = false;
        //JS to bring up the save notice
        $this->dispatch('savePrinterNotice');
        $this->addPrinter();
    }
    

    
    public function addPrinter(){
        $this->isSwitchOn=false;
        $this->printerID=-1;
        $this->printerName='';
        $this->ipAddress='';
        $this->printerTitle="Add a new printer";
        $this->printerStatus=0;
       // JS check box and buttons will not clear in livvewire
        $this->dispatch('resetSwitch');
    }
    //When you click on printer list
    public function showPrinterDetails($id, $name, $ip){
       $this->printerID=$id;
       $this->printerName=$name;
       $this->ipAddress=$ip;
       $this->printerStatus=0;
       $this->printerTitle="Update this printer";
       //JS to adjust the swich
       $this->dispatch('js_showPrinterDetails');
    }
    public function removePrinter(){
        DB::table('printertable')->where('keyID', $this->printerID)->delete();
        $this->printerList = DB::table('printertable')->select('*')->where('companyID', $this->companyID)->get();
        $this->addPrinter();
    }
    public function testPrinter(){
        try {
            $connector=null; 
            //dump($this->ipAddress);
            // Replace "192.168.0.100" with your printer's IP address and "9100" with the port
            if ($this->ipAddress){
                $connector = new NetworkPrintConnector($this->ipAddress, 9100);
            }else{
                $connector = new WindowsPrintConnector($this->printerName);
            }
            $printer = new Printer($connector);

            // Print receipt content
            $printer->text($this->printerName . "\n" );
            if ($this->ipAddress){
                $printer->text($this->ipAddress . "\n" );
            }

            // Cut the receipt
            $printer->cut();

            // Close the printer connection
            $printer->close();
            $this->printerStatus=1;
        } catch (\Exception $e) {
           $this->printerStatus=-1;
           //dump($e->getMessage());
        }
    }
    
    public function mount(){
        if (Auth::user()){
            $this->companyID=Auth::user()->companyID;      
            $this->printerList = DB::table('printertable')->select('*')->where('companyID', $this->companyID)->get();
        }
    }

    public function render()
    {
         return view('livewire.food-app');
    }
}
 