<template>
  <div  class="modal fade" id="select-licence-type" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Create New Licence</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
       
        <div class="modal-body">      
          <div class="row">
         
          
            <div class="text-center w-100">
              <h6 class="mt-3">Select Licence Type!</h6>
              <a href="/create-licence?type='wholesale'" class="mb-0 btn btn-dark me-2" as="button">
                <i class="fab fa-twitter me-1" aria-hidden="true"></i> 
                Retail Liquor Licence </a>
                <a href="/create-licence?type='retail'" class="mb-0 btn btn-dark me-2" as="button">
                  <i class="fab fa-facebook-square me-1" aria-hidden="true"></i> 
                  Wholesale Liquor Licence
                </a>
           </div>
  
   
        </div>
      </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          
        </div>
      
      </div>
    </div>
  </div>
</template>