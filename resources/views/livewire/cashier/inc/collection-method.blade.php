<div class="row" >
    <h5 class="text-center"> 
       Please select how this Order will be collected
    </h5>
 </div>     
 <div  class='d-flex justify-content-center gap-5 mt-5 mb-5  pb-5'>
    <button @click='$wire.eatInFunc()'  class='btn btn-lg btn-outline-primary px-4' >{{ $eat_in }}</button>
    <button  @click='$wire.takeAwayFunc()'  class='btn btn-lg btn-outline-success px-4'>{{ $take_away }}</button>
    <button  @click='$wire.closeModal()'  class='btn btn-lg btn-outline-warning px-4'>Cancel</button> 
 </div>