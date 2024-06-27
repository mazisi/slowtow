<template>
<Layout>
  <Head title="Reports"/>
  <div class="container-fluid">
    <Banner/>
      
        <div class="card card-body mx-3 mx-md-4 mt-n6">
          <div class="col-12">
          <div class="row">   
             <div class="col-10">
                    <h6>Reports</h6>              
                  </div>
                  <div class="col-2 my-auto text-end">
                    <div class="dropdown float-lg-end pe-4">
                    
                    </div>
                  </div>

           </div>
        </div>

        <div class="row">
          <div class="col-4">
            <button @click="getType($event,'All')" type="button" class="btn btn-success w-50">All</button>
          </div>

          <div class="col-4">
            <button @click="getType($event,report_type == 'retail' ? 'Alterations' : 'Additional Depot/Relocation')" 
            type="button" class=" btn btn-success w-50">{{ report_type == 'retail' ? 'Alterations' : 'Additional Depot/Relocation' }}</button>
          </div>

          <div class="col-4">
            <button @click="getType($event,'Existing Licences')" type="button" class=" btn btn-success w-50">Existing Licences</button>
          </div>


          <div class="col-4">
            <button @click="getType($event,'New Applications')" type="button" class=" btn btn-success w-50">New Applications</button>
          </div>

          <div class="col-4" v-if="report_type == 'retail'">
            <button @click="getType($event,'Nominations')" type="button" class=" btn btn-success w-50">Nominations</button>
          </div>
          
          <div class="col-4">
            <button @click="getType($event,'Renewals')" type="button" class=" btn btn-primary btn-success  w-50" 
            >Renewals</button>
          </div>

          <div class="col-4">
            <button @click="getType($event,'Temporary Licences')" type="button" class=" btn btn-success w-50">Temporary Applications</button>
          </div>

          <div class="col-4">
            <button @click="getType($event,'Transfers')" type="button" class=" btn btn-success w-50">Transfers</button>
          </div>

          
        </div>
<hr/>

<!-- ################################################ -->

 <div class="row" v-if="form.variation">
  

  <h5 class="text-center">{{ form.variation }}</h5>   

 

  <div class="col-6 columns">
     
    <Multiselect
            v-model="form.month_from"           
               :options="computedMonths"
               :taggable="true"
               placeholder="From"
             />
  </div>

  <div class="col-6 columns">
     
    <Multiselect
            v-model="form.month_to"           
               :options="computedMonths"
               :taggable="true"
               :required="true"
               placeholder="To"
             />
  </div>

  <div v-if="form.variation === 'Renewals'" class="col-6 columns" >
    <Multiselect v-if="report_type == 'retail'"
        v-model="form.renewal_stages"           
        :options="computedRenewalStages"
          mode="tags"
        :taggable="true"
        placeholder="Filter By Stage"/>

        <Multiselect v-if="report_type == 'wholesale'"
        v-model="form.renewal_stages"           
        :options="computedWholesaleRenewalStages"
          mode="tags"
        :taggable="true"
        placeholder="Filter By Stage"/>
  </div>

  <div v-if="form.variation === 'Transfers'" class="col-6 columns" >
    <Multiselect v-if="report_type == 'retail'"
        v-model="form.transfer_stages"           
        :options="computedTransferStages"
          mode="tags"
        :taggable="true"
        placeholder="Filter By Stage"/>

        <Multiselect v-if="report_type == 'wholesale'"
        v-model="form.transfer_stages"           
        :options="computedWholesaleTransferStages"
          mode="tags"
        :taggable="true"
        placeholder="Filter By Stage"/>
  </div>

  <div v-if="form.variation === 'Nominations' && report_type == 'retail'" class="col-6 columns" >
    <Multiselect
        v-model="form.nomination_stages"           
        :options="computedNominationStages"
        mode="tags"
        :taggable="true"
        placeholder="Filter By Stage"/>
  </div>

  <div v-if="form.variation === 'New Applications' || form.variation === 'All'" class="col-6 columns" >
    <Multiselect v-if="report_type == 'retail'"
    v-model="form.new_app_stages"           
    :options="computedNewAppStages"
     mode="tags"
    :taggable="true"
    placeholder="Filter By Stage"/>

    <Multiselect v-if="report_type == 'wholesale'"
    v-model="form.new_app_stages"           
    :options="computedWholesaleNewAppStages"
     mode="tags"
    :taggable="true"
    placeholder="Filter By Stage"/>
  </div>

  <div v-if="form.variation === 'Alterations'" class="col-6 columns" >
    <Multiselect
    v-model="form.alteration_stages"           
    :options="computedAlterationStages"
     mode="tags"
    :taggable="true"
    placeholder="Filter By Stage"/>
  </div>

  <div v-if="form.variation === 'Temporary Licences'" class="col-6 columns">
    <Multiselect
    v-model="form.temp_licence_stages"           
    :options="computedTempLicenceStages"
     mode="tags"
    :taggable="true"
    placeholder="Filter By Stage"/>
  </div>

  <div v-if="form.variation === 'Existing Licences'" class="col-6 columns">
    <Multiselect v-if="report_type == 'retail'"
    v-model="form.new_app_stages"           
    :options="computedNewAppStages"
     mode="tags"
    :taggable="true"
    placeholder="Filter By Stage"/>
    
    <Multiselect v-if="report_type == 'wholesale'"
    v-model="form.new_app_stages"           
    :options="computedWholesaleNewAppStages"
     mode="tags"
    :taggable="true"
    placeholder="Filter By Stage"/>
  </div>

 
  <div v-if="form.variation !== 'Temporary Licences'" class="col-6 columns" >
      <div class="input-group input-group-outline null is-filled" >
        <Multiselect 
          v-model="form.province"          
          :options="computedProvinces"
           mode="tags"
          :taggable="true"
          @select="fetchNewAppWithStages"
          placeholder="Province"/>
      </div>
    </div>





      <div class="col-6 columns" >
        <Multiselect
        v-model="form.year" 
         :options="years"
        :searchable="true"
        placeholder="Select Year"
        />
      </div>
  
  
  <div v-if="form.variation !== 'Temporary Licences' && report_type == 'retail'" class="col-6">
    <div class="input-group input-group-outline null is-filled">
      <Multiselect
      v-model="form.boardRegion"           
      :options="computedBoardRegions"
       mode="tags"
      :taggable="true"
      @select="fetchNewAppWithStages"
      placeholder="Liquor Board Region"/>
      </div>
  </div>

  
  <div v-if="form.variation !== 'Temporary Licences'" class="col-6 ">
    <div class="input-group input-group-outline null is-filled">
      <Multiselect v-if="report_type == 'retail'"
      v-model="form.licence_types"           
      :options="licenceTypes"
       mode="tags"
       :searchable="true"
      :taggable="true"
      @select="fetchNewAppWithStages"
      placeholder="Licence Type"/>

      <Multiselect v-if="report_type == 'wholesale'"
      v-model="form.licence_types"           
      :options="licenceTypes"
       mode="tags"
       :searchable="true"
      :taggable="true"

      @select="fetchNewAppWithStages"
      placeholder="Licence Type"/>
      </div>
  </div>

  <div v-if="form.variation == 'Temporary Licences'" class="col-6 ">
    <div class="input-group input-group-outline null is-filled">
      <Multiselect
      v-model="form.temp_licence_region"           
      :options="computedBoardRegions"
       mode="tags"
      :taggable="true"
      @select="fetchNewAppWithStages"
      placeholder="Region"/>
      </div>
  </div>
  <!-- :class="{'mt-3' : form.variation == 'All'}"
:class="{'mt-3' : form.variation == 'All'}" -->
  
  <!-- :class="{'mt-3' : form.variation !== 'Temporary Licences'}"  -->
  <div class="col-6" :class="{'mt-3' : form.variation == 'Upcoming Renewals'}" >
    <div class="input-group columns input-group-outline null is-filled">
      <select v-model="form.applicant" class="form-control form-control-default">
      <option :value="''" disabled selected>Select Applicant</option>
      <option value="Company">Company</option>
      <option value="Individual">Individual</option>
      </select>
      </div>
  </div>
  <div class="col-6 columns ">
    <div class="input-group input-group-outline null is-filled" >
      <select v-model="form.activeStatus" @change="fetchNewAppWithStages" class="input-have-green-color form-control form-control-default">
          <option :value="''" disabled selected>Active/Inactive</option>
          <option value="Active">Active</option>
          <option value="Inactive">Inactive</option>
       </select>
    </div>
  </div>
  

<div class="d-flex mt-4" style="justify-content: space-between">
  
    <button @click="resetFilters" class="btn btn-sm btn-primary">Reset All Filters</button>
  
  

      <button v-if="form.variation == 'All'" @click="exportAll" :disabled="form.processing || !form.variation" 
      type="button" class="btn btn-success float-end">
      <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Generate All Reports</button>

      <button v-else @click="exportReport" :disabled="form.processing || !form.variation" 
      type="button" class="btn btn-success float-end">
      <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Export</button>
    
</div>

  
</div>


</div>
</div>





</Layout>
</template>
<style scoped>
.columns{
  margin-bottom: 1rem;
}

</style>
<script src="./reports-js/report.js"></script>

<style src="@vueform/multiselect/themes/default.css"></style>