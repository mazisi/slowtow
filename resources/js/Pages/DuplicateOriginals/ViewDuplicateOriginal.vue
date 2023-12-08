
<script src="./view_duplicate_originals.js"></script>
<style>
.columns{
  margin-bottom: 1rem;
}
.active-checkbox{
  margin-top: -10px;
}
.status-heading{
  font-weight: 700;
}


</style>
<style src="@vueform/multiselect/themes/default.css"></style>


<template>
<Layout>
  <Head title="View Alteration" />
<div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row">   
  <div class="col-lg-9 col-9">
   <h6> Alteration Info for: 
    <Link :href="`#`">
      <span class="text-success">{{ duplicate_original.licence.trading_name }}</span></Link></h6>
  </div>
  <div class="col-lg-3 col-3 my-auto text-end">
    <button v-if="$page.props.auth.has_slowtow_admin_role" 
    @click="deleteAlteration(duplicate_original.slug)" type="button" class="btn btn-sm btn-danger float-lg-end pe-4"> Delete</button>
  </div>
</div>
      <div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
  <form @submit.prevent="update">
<div class="row">

  <StageComponent
      :column=12
      :dbStatus="duplicate_original.status"
      :errors="errors"
      :error="error"
      :stageValue=100
      prevStage=0
      :prevStage='0'
      :licence_id="duplicate_original"
      :stageTitle="'Client Quoted'"
      :success="success"
      @stage-value-changed="pushData"
    />

  
   
    
    <div class="col-9 columns">
      <DocComponent
      @file-value-changed="submitDocument"
      @file-deleted="deleteDocument"
      :documentModel="duplicate_original"
      :hasFile="hasFile('Client Quoted')"
      :errors="errors"
      :error="error"
      :orderByNumber=100
      :docType="'Client Quoted'"
      :success="success"
      />
    </div>
<hr/>
 

<StageComponent
      :column=12
      :dbStatus="duplicate_original.status"
      :errors="errors"
      :error="error"
      :stageValue=200
      :prevStage='100'
      :licence_id="duplicate_original"
      :stageTitle="'Client Invoiced'"
      :success="success"
      @stage-value-changed="pushData"
    />


<div class="col-9 columns">

<DocComponent
@file-value-changed="submitDocument"
          @file-deleted="deleteDocument"
      :documentModel="duplicate_original"
      :hasFile="hasFile('Client Invoiced')"
      :errors="errors"
      :error="error"
      :orderByNumber=200
      :docType="'Client Invoiced'"
      :success="success"
      />
</div>

<hr/>
<div class="col-5 columns">
<StageComponent
      :column=5
      :dbStatus="duplicate_original.status"
      :errors="errors"
      :error="error"
      :stageValue=300
      :prevStage=200
      :licence_id="duplicate_original"
      :stageTitle="'Client Paid'"
      :success="success"
      @stage-value-changed="pushData"
    />

    </div>


    <DateComponent
    :licence="duplicate_original"
    :stage="'Client Paid'"
    :canSave="$page.props.auth.has_slowtow_admin_role"
    :errors="errors"
    :error="error"
    :column=5
    @date-value-changed="updateduplicate_originalDate"
    :dated_at="duplicate_original.paid_at"
    :success="success"
    /> 
<hr>

<StageComponent
      :column=5
      :dbStatus="duplicate_original.status"
      :errors="errors"
      :error="error"
      :stageValue=400
      :prevStage=300
      :licence_id="duplicate_original"
      :stageTitle="'Duplicate Original Request Letter'"
      :success="success"
      @stage-value-changed="pushData"
    />


<div class="row">
  
<div class="col-md-6 columns"> 
  <DocComponent
        @file-value-changed="submitDocument"
        @file-deleted="deleteDocument"
        :documentModel="duplicate_original"
        :hasFile="hasFile('Duplicate Original Request Letter')"
        :errors="errors"
        :error="error"
        :orderByNumber=400
        :docType="'Duplicate Original Request Letter'"
        :success="success"
        />
</div>

</div>
<hr>
    <div class="col-5 columns">
      <StageComponent
            :column=5
            :dbStatus="duplicate_original.status"
            :errors="errors"
            :error="error"
            :stageValue=500
            :prevStage=400
            :licence_id="duplicate_original"
            :stageTitle="'Scanned Application'"
            :success="success"
            @stage-value-changed="pushData"
          />
      
      
          <DocComponent
          @file-value-changed="submitDocument"
          @file-deleted="deleteDocument"
              :documentModel="duplicate_original"
              :hasFile="hasFile('Scanned Application')"
              :errors="errors"
              :error="error"
              :orderByNumber=500
              :docType="'Scanned Application'"
              :success="success"
              />
          </div>
      <hr>



      <div class="col-5 columns">
        <StageComponent
              :column=5
              :dbStatus="duplicate_original.status"
              :errors="errors"
              :error="error"
              :stageValue=600
              :prevStage=500
              :licence_id="duplicate_original"
              :stageTitle="'Application Lodged'"
              :success="success"
              @stage-value-changed="pushData"
            />
        
        
            <DocComponent
                @file-value-changed="submitDocument"
                @file-deleted="deleteDocument"
                :documentModel="duplicate_original"
                :hasFile="hasFile('Application Lodged')"
                :errors="errors"
                :error="error"
                :orderByNumber=600
                :docType="'Application Lodged'"
                :success="success"
                />
            </div>
        
        
            <DateComponent
            :licence="duplicate_original"
            :stage="'Application Lodged'"
            :canSave="$page.props.auth.has_slowtow_admin_role"
            :errors="errors"
            :error="error"
            :column=5
            @date-value-changed="updateAlterationDate"
            :dated_at="duplicate_original.lodged_at"
            :success="success"
            /> 
        <hr>


        <div class="col-5 columns">
          <StageComponent
                :column=5
                :dbStatus="duplicate_original.status"
                :errors="errors"
                :error="error"
                :stageValue=700
                :prevStage=600
                :licence_id="duplicate_original"
                :stageTitle="'Duplicate Original Issued'"
                :success="success"
                @stage-value-changed="pushData"
              />
          
          
              <DocComponent
              @file-value-changed="submitDocument"
          @file-deleted="deleteDocument"
                  :documentModel="duplicate_original"
                  :hasFile="hasFile('Duplicate Original Issued')"
                  :errors="errors"
                  :error="error"
                  :orderByNumber=700
                  :docType="'Duplicate Original Issued'"
                  :success="success"
                  />
              </div>
          
          
              <DateComponent
              :licence="duplicate_original"
              :stage="'Duplicate Original Issued'"
              :canSave="$page.props.auth.has_slowtow_admin_role"
              :errors="errors"
              :error="error"
              :column=5
              @date-value-changed="updateAlterationDate"
              :dated_at="duplicate_original.issued_at"
              :success="success"
              /> 
          <hr>

          <div class="col-5 columns">
            <StageComponent
                  :column=5
                  :dbStatus="duplicate_original.status"
                  :errors="errors"
                  :error="error"
                  :stageValue=800
                  :prevStage=700
                  :licence_id="duplicate_original"
                  :stageTitle="'Duplicate Original Delivered'"
                  :success="success"
                  @stage-value-changed="pushData"
                />
            
            
                <DocComponent
                @file-value-changed="submitDocument"
          @file-deleted="deleteDocument"
                    :documentModel="duplicate_original"
                    :hasFile="hasFile('Duplicate Original Delivered')"
                    :errors="errors"
                    :error="error"
                    :orderByNumber=800
                    :docType="'Duplicate Original Delivered'"
                    :success="success"
                    />
                </div>
            
            
                <DateComponent
                :licence="duplicate_original"
                :stage="'Duplicate Original Delivered'"
                :canSave="$page.props.auth.has_slowtow_admin_role"
                :errors="errors"
                :error="error"
                :column=5
                @date-value-changed="updateAlterationDate"
                :dated_at="duplicate_original.delivered_at"
                :success="success"
                /> 
            <hr>

            </div>
            </form>
              </div>
            </div>
          
          </div>


      <Task :tasks="tasks" :model_id="duplicate_original.id" :success="success" :error="error" :errors="errors" :model_type="'Alteration'"/>
        
        </div>
        
      </div>
    </div>
  </div>

  </Layout>
</template>
