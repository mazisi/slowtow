
<script src="./view_alteration.js"></script>
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
    <Link :href="`/view-licence?slug=${alteration.licence.slug}`">
      <span class="text-success">{{ alteration.licence.trading_name }}</span></Link></h6>
  </div>
  <div class="col-lg-3 col-3 my-auto text-end">
    <button v-if="$page.props.auth.has_slowtow_admin_role" 
    @click="deleteAlteration(alteration.slug)" type="button" class="btn btn-sm btn-danger float-lg-end pe-4"> Delete</button>
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
      :dbStatus="alteration.status"
      :errors="errors"
      :error="error"
      :stageValue=100
      prevStage=0
      :prevStage='0'
      :licence_id="alteration.slug"
      :stageTitle="'Client Quoted'"
      :success="success"
      @stage-value-changed="pushData"
    />

  
   
    
    <div class="col-9 columns">
      <DocComponent
      @file-value-changed="submitDocument"
          @file-deleted="deleteDocument"
      :documentModel="alteration"
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
      :dbStatus="alteration.status"
      :errors="errors"
      :error="error"
      :stageValue=200
      :prevStage='100'
      :licence_id="alteration.slug"
      :stageTitle="'Client Invoiced'"
      :success="success"
      @stage-value-changed="pushData"
    />


<div class="col-9 columns">

<DocComponent
@file-value-changed="submitDocument"
          @file-deleted="deleteDocument"
      :documentModel="alteration"
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
      :dbStatus="alteration.status"
      :errors="errors"
      :error="error"
      :stageValue=300
      :prevStage=200
      :licence_id="alteration.slug"
      :stageTitle="'Client Paid'"
      :success="success"
      @stage-value-changed="pushData"
    />


    <DocComponent
        @file-value-changed="submitDocument"
        @file-deleted="deleteDocument"
        :documentModel="alteration"
        :hasFile="hasFile('Client Paid')"
        :errors="errors"
        :error="error"
        :orderByNumber=300
        :docType="'Client Paid'"
        :success="success"
        />
    </div>


    <DateComponent
    :licence="alteration"
    :stage="'Client Paid'"
    :canSave="$page.props.auth.has_slowtow_admin_role"
    :errors="errors"
    :error="error"
    :column=5
    @date-value-changed="updateAlterationDate"
    :dated_at="getAlterationDate(alteration.id, 'Client Paid')"
    :success="success"
    /> 
<hr>

<StageComponent
      :column=5
      :dbStatus="alteration.status"
      :errors="errors"
      :error="error"
      :stageValue=400
      :prevStage=300
      :licence_id="alteration.slug"
      :stageTitle="'Prepare Alterations Application'"
      :success="success"
      @stage-value-changed="pushData"
    />


<div class="row">
  
<div class="col-md-6 columns">

 
    <MergeDocumentComponent
    @file-value-changed="submitDocument"
    @file-deleted="deleteDocument"
    :success="success"
    :error="error"
    :errors="errors"
    :column=6
    :docTitle="'Application Form'"
    :docType="'Application Form'"
    :docModel="alteration"
    :stage=400
    :mergeNum="1"
    :hasFile="hasFile('Application Form')"
    />

  <MergeDocumentComponent
   @file-value-changed="submitDocument"
   @file-deleted="deleteDocument"
  :success="success"
  :error="error"
  :errors="errors"
  :column=6
  :docTitle="'Fully Dimensional Plans'"
  :docType="'Fully Dimensional Plans'"
  :docModel="alteration"
  :stage=400
  :mergeNum="2"
  :hasFile="hasFile('Fully Dimensional Plans')"
  />


  <MergeDocumentComponent
   @file-value-changed="submitDocument"
     @file-deleted="deleteDocument"
  :success="success"
  :error="error"
  :errors="errors"
  :column=6
  :docTitle="'Payment To The Liquor Board'"
  :docType="'Payment To The Liquor Board'"
  :docModel="alteration"
  :stage=400
  :mergeNum="5"
  :hasFile="hasFile('Payment To The Liquor Board')"
  />

<hr class="vertical dark" />
</div>

<div class="col-md-6 columns">

  <MergeDocumentComponent
   @file-value-changed="submitDocument"
     @file-deleted="deleteDocument"
  :success="success"
  :error="error"
  :errors="errors"
  :column=6
  :docTitle="'POA & RES'"
  :docType="'POA & RES'"
  :docModel="alteration"
  :stage=400
  :mergeNum="3"
  :hasFile="hasFile('POA & RES')"
  />

  <MergeDocumentComponent
   @file-value-changed="submitDocument"
     @file-deleted="deleteDocument"
  :success="success"
  :error="error"
  :errors="errors"
  :column=6
  :docTitle="'Smoking Affidavit'"
  :docType="'Smoking Affidavit'"
  :docModel="alteration"
  :stage=400
  :mergeNum="4"
  :hasFile="hasFile('Smoking Affidavit')"
  />

<a v-if="alteration.merged_document" :href="`/storage/app/public/${alteration.merged_document}`" target="_blank"  class="btn btn-sm btn-success float-end mx-2" >View</a>

<button 
v-if="application_form !== null
&& smoking_affidavict !== null
&& poa_res !== null
&& payment_to_liquor_board !== null
&& dimensional_plans !== null" 
 type="button" 
 @click="mergeDocuments" class="btn btn-sm btn-secondary float-end">Compile & Merge</button>
 <button v-else class="btn btn-sm btn-secondary float-end" disabled="disabled">Compile & Merge</button>
</div>
</div>
<hr>

    <div class="col-5 columns">
      <StageComponent
            :column=5
            :dbStatus="alteration.status"
            :errors="errors"
            :error="error"
            :stageValue=500
            :prevStage=400
            :licence_id="alteration.slug"
            :stageTitle="'Payment to the Liquor Board'"
            :success="success"
            @stage-value-changed="pushData"
          />
      
      
          <DocComponent
          @file-value-changed="submitDocument"
          @file-deleted="deleteDocument"
              :documentModel="alteration"
              :hasFile="hasFile('Payment to the Liquor Board')"
              :errors="errors"
              :error="error"
              :orderByNumber=500
              :docType="'Payment to the Liquor Board'"
              :success="success"
              />
          </div>
      
      
          <DateComponent
          :licence="alteration"
          :stage="'Payment to the Liquor Board'"
          :canSave="$page.props.auth.has_slowtow_admin_role"
          :errors="errors"
          :error="error"
          :column=5
          @date-value-changed="updateAlterationDate"
          :dated_at="getAlterationDate(alteration.id, 'Payment to the Liquor Board')"
          :success="success"
          /> 
      <hr>



      <div class="col-5 columns">
        <StageComponent
              :column=5
              :dbStatus="alteration.status"
              :errors="errors"
              :error="error"
              :stageValue=600
              :prevStage=500
              :licence_id="alteration.slug"
              :stageTitle="'Alterations Lodged'"
              :success="success"
              @stage-value-changed="pushData"
            />
        
        
            <DocComponent
            @file-value-changed="submitDocument"
          @file-deleted="deleteDocument"
                :documentModel="alteration"
                :hasFile="hasFile('Alterations Lodged')"
                :errors="errors"
                :error="error"
                :orderByNumber=600
                :docType="'Alterations Lodged'"
                :success="success"
                />
            </div>
        
        
            <DateComponent
            :licence="alteration"
            :stage="'Alterations Lodged'"
            :canSave="$page.props.auth.has_slowtow_admin_role"
            :errors="errors"
            :error="error"
            :column=5
            @date-value-changed="updateAlterationDate"
            :dated_at="getAlterationDate(alteration.id, 'Alterations Lodged')"
            :success="success"
            /> 
        <hr>


        <div class="col-5 columns">
          <StageComponent
                :column=5
                :dbStatus="alteration.status"
                :errors="errors"
                :error="error"
                :stageValue=700
                :prevStage=600
                :licence_id="alteration.slug"
                :stageTitle="'Alterations Certificate Issued'"
                :success="success"
                @stage-value-changed="pushData"
              />
          
          
              <DocComponent
              @file-value-changed="submitDocument"
          @file-deleted="deleteDocument"
                  :documentModel="alteration"
                  :hasFile="hasFile('Alterations Certificate Issued')"
                  :errors="errors"
                  :error="error"
                  :orderByNumber=700
                  :docType="'Alterations Certificate Issued'"
                  :success="success"
                  />
              </div>
          
          
              <DateComponent
              :licence="alteration"
              :stage="'Alterations Certificate Issued'"
              :canSave="$page.props.auth.has_slowtow_admin_role"
              :errors="errors"
              :error="error"
              :column=5
              @date-value-changed="updateAlterationDate"
              :dated_at="getAlterationDate(alteration.id, 'Alterations Certificate Issued')"
              :success="success"
              /> 
          <hr>

          <div class="col-5 columns">
            <StageComponent
                  :column=5
                  :dbStatus="alteration.status"
                  :errors="errors"
                  :error="error"
                  :stageValue=800
                  :prevStage=700
                  :licence_id="alteration.slug"
                  :stageTitle="'Alterations Delivered'"
                  :success="success"
                  @stage-value-changed="pushData"
                />
            
            
                <DocComponent
                @file-value-changed="submitDocument"
          @file-deleted="deleteDocument"
                    :documentModel="alteration"
                    :hasFile="hasFile('Alterations Delivered')"
                    :errors="errors"
                    :error="error"
                    :orderByNumber=800
                    :docType="'Alterations Delivered'"
                    :success="success"
                    />
                </div>
            
            
                <DateComponent
                :licence="alteration"
                :stage="'Alterations Delivered'"
                :canSave="$page.props.auth.has_slowtow_admin_role"
                :errors="errors"
                :error="error"
                :column=5
                @date-value-changed="updateAlterationDate"
                :dated_at="getAlterationDate(alteration.id, 'Alterations Delivered')"
                :success="success"
                /> 
            <hr>

            </div>
            </form>
              </div>
            </div>
          
          </div>


      <Task :tasks="tasks" :model_id="alteration.id" :success="success" :error="error" :errors="errors" :model_type="'Alteration'"/>
        
        </div>
        
      </div>
    </div>
  </div>

  </Layout>
</template>
