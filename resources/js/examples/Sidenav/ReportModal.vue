<template>
  <div v-if="show_modal" class="modal fade" id="report-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">      
          <div class="row">
         
          
            <div class="text-center w-100">
              <a @click="redirect('retail')" href="#!" class="mb-0 btn btn-dark me-2" as="button">
                
                Retail </a>
                <a @click="redirect('wholesale')" href="#!" class="mb-0 btn btn-success me-2" as="button">
                  
                  Wholesale
                </a>
           </div>
  
   
        </div>
      </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-xs" data-bs-dismiss="modal">Close</button>
          
        </div>
      
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return{
      show_modal : true
    }
    
  },
  methods: {
    redirect(type) {
      this.show_modal= false
      document.querySelector('.modal-backdrop').remove();
      this.$emit('redirect-with-report-type', type);
    },
  },
};
</script>