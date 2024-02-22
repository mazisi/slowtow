
<script src="../../TemporalLicences/view_temp_licence.js"></script>
<style src="@vueform/multiselect/themes/default.css"></style>

<style scoped>
.columns{
  margin-bottom: 1rem;
}
#active-checkbox{
  margin-top: 3px;
  margin-left: 3px;
}
.form-control {
  border-color: #4caf50 !important;
}
</style>

<template>
<Layout>
  <Head title="View Temporary Licence"/>
<div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row ">
       
        <div class="col-lg-6 col-7">
          <div class="h-100">
            <h5 class="mb-1">View Temporary Licence</h5>
          </div>
        </div>
       
      </div>
      <div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
  <form @submit.prevent="update">
<div class="row">
  
  <TextInputComponent 
      :inputType="'text'"
      v-model="form.event_name" 
      :value="form.event_name"  
      :column="'col-4'" 
      :label="'Event Name'" 
      :errors="errors.event_name"
      :input_id="form.event_name"
    />

    <TextInputComponent 
      :inputType="'date'"
      v-model="form.start_date" 
      :value="form.start_date"  
      :column="'col-4'" 
      :label="'Event Start Date'" 
      :errors="errors.start_date"
      :input_id="form.start_date"
      @input="computeLogdementDate"
    />

    <TextInputComponent 
      :inputType="'date'"
      v-model="form.end_date" 
      :value="form.end_date"  
      :column="'col-4'" 
      :label="'Event Ending Date'" 
      :errors="errors.end_date"
      :input_id="form.end_date"
      @input="computeLogdementDate"
    />

    <TextInputComponent 
      :inputType="'text'"
      v-model="form.latest_lodgment_date" 
      :value="form.latest_lodgment_date"  
      :column="'col-4'" 
      :label="'Latest Lodgment Date'" 
      :errors="errors.latest_lodgment_date"
      :input_id="form.latest_lodgment_date"
      disabled="true"
    />

    <TextInputComponent 
      :inputType="'text'"
      v-model="form.liquor_licence_number" 
      :value="form.liquor_licence_number"  
      :column="'col-4'" 
      :label="'Licence Number'" 
      :errors="errors.liquor_licence_number"
      :input_id="form.liquor_licence_number"
    />
 
    <TextInputComponent 
      :inputType="'text'"
      v-model="form.address" 
      :value="form.address"  
      :column="'col-4'" 
      :label="'Event Address Region'" 
      :errors="errors.address"
      :input_id="form.address"
    />

    <LiquorBoardRegionComponent 
        :dropdownList="computedBoardRegions"
        :label="'Event Address Region'"
        :defaultDisabledText="'Select Event Address Region'"
        :column="'col-4'"
        :value="form.board_region"
        v-model="form.board_region"
        :errors="errors.board_region"
        :input_id="board_region"
        :required="false"
    />
 
   

 <div class="col-md-4 columns">
  <div class="input-group input-group-outline null is-filled ">
  
  <select class="form-control form-control-default" v-model="form.application_type" >
    <option :value="''" disabled selected >Application Type</option>
    <option value="Off-Consumption">Off-Consumption</option>
    <option value="On-Consumption">On-Consumption</option>
  </select>
   </div>
 <div v-if="errors.application_type" class="text-danger">{{ errors.application_type }}</div>
 </div>



 <TextInputComponent 
 v-if="form.belongs_to =='Company'"
      :inputType="'text'"
      v-model="form.company_name" 
      :value="form.company_name"  
      :column="'col-4'" 
      :label="'Company Name'" 
      :errors="errors.company_name"
      :input_id="form.company_name"
      disabled="true"
    />

    <TextInputComponent 
      v-if="form.belongs_to =='Individual'"
      :inputType="'text'"
      v-model="form.person" 
      :value="form.person"  
      :column="'col-4'" 
      :label="'Person Name'" 
      :errors="errors.person"
      :input_id="form.person"
      disabled="true"
    />

    <TextInputComponent 
      v-if="form.belongs_to =='Company'"
      :inputType="'text'"
      v-model="form.reg_number" 
      :value="form.reg_number"  
      :column="'col-4'" 
      :label="'Registration Number'" 
      :errors="errors.reg_number"
      :input_id="form.reg_number"
      disabled="true"
    />
  
    <TextInputComponent 
      v-if="form.belongs_to =='Individual'"
      :inputType="'text'"
      v-model="form.id_or_passport" 
      :value="form.id_or_passport"  
      :column="'col-4'" 
      :label="'ID Number'" 
      :errors="errors.id_or_passport"
      :input_id="form.id_or_passport"
      disabled="true"
    />
 
  
            </div>
            </form>
              </div>
            </div>
            <hr class="vertical dark" />
          </div>
      <!-- //tasks were here -->
        
        </div>
        
      </div>
    </div>
  </div>
  </Layout>
</template>
