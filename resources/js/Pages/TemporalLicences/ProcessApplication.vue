<script src="./process_app.js"></script>
<style>
.columns{
  margin-bottom: 1rem;
}
.active-checkbox{
  margin-top: -10px;
  margin-left: 3px;
}
.status-heading{
  font-weight: 700;
}
.document-names { width: 50%; }

.curser-pointer{ cursor: pointer;}
</style>
<style src="@vueform/multiselect/themes/default.css"></style>
<template>
<Layout>
  <Head title="Process Application"/>
<div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row">
  <div class="col-lg-9 col-9">
  <h6 v-if="licence.people === null">Temporary Licence For: 
      <Link :href="`/view-company/${licence.company.slug}`" class="text-success">{{ licence.company.name }}
      </Link>
  </h6>
    <h6 v-else>Temporary Licence For: <Link :href="`/view-person/${licence.people.slug}`" class="text-success">{{ licence.people.full_name }}</Link></h6>
    <p class="text-sm mb-0">Current Stage: 
      <span class="font-weight-bold ms-1">{{ getStatus(licence.status)}}</span>
   </p>

  </div>
  <div class="col-lg-3 col-3 my-auto text-end">
    

      <button v-if="$page.props.auth.has_slowtow_admin_role" 
       @click="deleteTemporalLicence" type="button" class="btn btn-sm btn-danger">
        <i class="fa fa-trash-alt text-md"></i> Delete
      </button>
  </div>
</div>

<div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
  <form @submit.prevent="updateLicence">
<div class="row">
  <StageComponent
  :column=12
  :dbStatus="licence.status"
  :errors="errors"
  :error="error"
  :stageValue=100
  :prevStage=null
  :licence_id="licence.slug"
  :stageTitle="'Client Quoted'"
  :success="success"
  @stage-value-changed="pushData"
/>

<DocComponent
:documentModel="licence"
@file-value-changed="submitDocument"
@file-deleted="deleteDocument"
:hasFile="hasFile('Client Quoted')"
:errors="errors"
:error="error"
:orderByNumber=null
stage=100
:docType="'Client Quoted'"
:success="success"
/>

<hr>


<StageComponent
:column=12
:dbStatus="licence.status"
:errors="errors"
:error="error"
:stageValue=200
:prevStage=100
:licence_id="licence.slug"
:stageTitle="'Client Invoiced'"
:success="success"
@stage-value-changed="pushData"
/>

<DocComponent
:documentModel="licence"
@file-value-changed="submitDocument"
@file-deleted="deleteDocument"
:hasFile="hasFile('Client Invoiced')"
:errors="errors"
:error="error"
:orderByNumber=100
stage=200
:docType="'Client Invoiced'"
:success="success"
/>

<hr>

<StageComponent
:column=6
:dbStatus="licence.status"
:errors="errors"
:error="error"
:stageValue=300
:prevStage=200
:licence_id="licence.slug"
:stageTitle="'Client Paid'"
:success="success"
@stage-value-changed="pushData"
/>


<DateComponent
:stage="'Client Paid'"
:licence="licence"
:canSave="$page.props.auth.has_slowtow_admin_role"
:errors="errors"
:error="error"
:column=4
@date-value-changed="updateStageDate"
:dated_at="form.client_paid_at"
:success="success"
/>

<hr>

<StageComponent
:column=12
:dbStatus="licence.status"
:errors="errors"
:error="error"
:stageValue=400
:prevStage=300
:licence_id="licence.slug"
:stageTitle="'Prepare Temporary Application'"
:success="success"
@stage-value-changed="pushData"
/>

<div class="">

<!-- ===============   Company File Uploads ===========================-->
<div v-if="!licence?.people_id" class="d-flex row">
  <div class="col-sm-2"></div>
  
  <div class="col-sm-5">
    <PrepareTemp
    :errors="errors"
    :docModel="licence"
    stage=400
    @file-value-changed="submitDocument"
    @file-deleted="deleteDocument"
    :hasFile="hasMergeFile('Application Form','Company')"
    doc_type="Application Form"
    title="Application Form"
    belongsTo="Company"
    orderByNum=100
    />


    <LinkComponent v-if="hasFile('Payment To The Liquor Board').id"
    :title="'Proof Of Payment'" 
    :linkModel="hasFile('Payment To The Liquor Board')
    "/>


    <PrepareTemp
    :errors="errors"
    :docModel="licence"
    stage=400
    @file-value-changed="submitDocument"
    @file-deleted="deleteDocument"
    :hasFile="hasMergeFile('POA And RES','Company')"
    doc_type="POA And RES"
    belongsTo="Company"
    orderByNum=300
    title="POA &amp; RES"
    />

    <PrepareTemp
    :errors="errors"
    :docModel="licence"
    stage=400
    @file-value-changed="submitDocument"
    @file-deleted="deleteDocument"
    :hasFile="hasMergeFile('Annexure B','Company')"
    doc_type="Annexure B"
    belongsTo="Company"
    orderByNum=400
    title="Annexure B & C"
    />



        <!-- <button type="button" class="btn btn-outline-success document-names">Annexure C</button>
         <i v-if="company_annexure_c == null" @click="getDocType('Annexure C','Company',5)" data-bs-toggle="modal" data-bs-target="#documents" 
         class="fa fa-upload h5 mx-2 curser-pointer"></i>
        <i v-if="company_annexure_c !== null" @click="deleteDocument(company_annexure_c.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="company_annexure_c !== null" :href="`${$page.props.blob_file_path}${company_annexure_c.document}`" target="_blank">
        <i v-if="company_annexure_c !== null" class="fa fa-file-pdf h4 text-danger"></i></a><br> -->
        <PrepareTemp
        :errors="errors"
        :docModel="licence"
        stage=400
        @file-value-changed="submitDocument"
        @file-deleted="deleteDocument"
        :hasFile="hasMergeFile('CIPC Certificate','Company')"
        doc_type="CIPC Certificate"
        belongsTo="Company"
        orderByNum=600
        title="CIPC Certificate"
        />

    <div class="col-sm-1"> </div>
  </div>
  
  <div class="col-sm-5">

    <PrepareTemp
    :errors="errors"
    :docModel="licence"
    stage=400
    @file-value-changed="submitDocument"
    @file-deleted="deleteDocument"
    :hasFile="hasMergeFile('ID Document','Company')"
    doc_type="ID Document"
    belongsTo="Company"
    orderByNum=700
    title="ID Document"
    />

    <PrepareTemp
    :errors="errors"
    :docModel="licence"
    stage=400
    @file-value-changed="submitDocument"
    @file-deleted="deleteDocument"
    :hasFile="hasMergeFile('Representations','Company')"
    doc_type="Representations"
    belongsTo="Company"
    orderByNum=800
    title="Representations"
    />

    <PrepareTemp
    :errors="errors"
    :docModel="licence"
    stage=400
    @file-value-changed="submitDocument"
    @file-deleted="deleteDocument"
    :hasFile="hasMergeFile('Landlord Letter','Company')"
    doc_type="Landlord Letter"
    belongsTo="Company"
    orderByNum=900
    title="Landlord Letter"
    />

    <PrepareTemp
    :errors="errors"
    :docModel="licence"
    stage=400
    @file-value-changed="submitDocument"
    @file-deleted="deleteDocument"
    :hasFile="hasMergeFile('Security Letter','Company')"
    doc_type="Security Letter"
    belongsTo="Company"
    orderByNum=1000
    title="Security Letter"
    />


    <PrepareTemp
    :errors="errors"
    :docModel="licence"
    stage=400
    @file-value-changed="submitDocument"
    @file-deleted="deleteDocument"
    :hasFile="hasMergeFile('Advert/Blurb','Company')"
    doc_type="Advert/Blurb"
    belongsTo="Company"
    orderByNum=1100
    title="Advert/Blurb"
    />

    <PrepareTemp
    :errors="errors"
    :docModel="licence"
    stage=400
    @file-value-changed="submitDocument"
    @file-deleted="deleteDocument"
    :hasFile="hasMergeFile('Plan/Maps','Company')"
    doc_type="Plan/Maps"
    belongsTo="Company"
    orderByNum=1200
    title="Plan/Maps"
    />


  <div class="col-sm-1"> </div>
      <div class="d-flex">
      <MergeButton 
        v-if="licence?.company_id && countMergeFiles('Company')"
        @time-to-merge="mergeDocuments('Company')"
        :licence="licence"
        :mergeForm="mergeForm"
        :disabled="countMergeFiles('Company') <= 0 || mergeForm.processing"
      />
    </div>
  </div>
  
</div>





<!-- ===============   Individual File Uploads ===========================-->
<div v-if="!licence?.company_id" class="d-flex row">
  <div class="col-sm-2"></div>
  
  <div class="col-sm-5">

    <PrepareTemp
    :errors="errors"
    :docModel="licence"
    stage=400
    @file-value-changed="submitDocument"
    @file-deleted="deleteDocument"
    :hasFile="hasMergeFile('Application Form','Individual')"
    doc_type="Application Form"
    belongsTo="Individual"
    orderByNum=100
    title="Application Form"
    />

  
    <LinkComponent v-if="hasFile('Payment To The Liquor Board').id"
    :title="'Proof Of Payment'" 
    :linkModel="hasFile('Payment To The Liquor Board')
    "/>

    <PrepareTemp
    :errors="errors"
    :docModel="licence"
    stage=400
    @file-value-changed="submitDocument"
    @file-deleted="deleteDocument"
    :hasFile="hasMergeFile('Power Of Attorney','Individual')"
    doc_type="Power Of Attorney"
    belongsTo="Individual"
    orderByNum=300
    title="Power Of Attorney"
    />

    <PrepareTemp
    :errors="errors"
    :docModel="licence"
    stage=400
    @file-value-changed="submitDocument"
    @file-deleted="deleteDocument"
    :hasFile="hasMergeFile('Annexure B','Individual')"
    doc_type="Annexure B"
    belongsTo="Individual"
    orderByNum=400
    title="Annexure B & C"
    />

    

<div v-if="!licence.company_id">


  <LinkComponent v-if="hasFile('ID Document').id"
  :title="'ID Document'" 
  :linkModel="hasFile('ID Document')
  "/>
  
    <div class="col-sm-1"> </div>
</div>
      
  </div>
  
  <div class="col-sm-5">

    <PrepareTemp
    :errors="errors"
    :docModel="licence"
    stage=400
    @file-value-changed="submitDocument"
    @file-deleted="deleteDocument"
    :hasFile="hasMergeFile('Representations','Individual')"
    doc_type="Representations"
    belongsTo="Individual"
    orderByNum=700
    title="Representations"
    />

    <PrepareTemp
    :errors="errors"
    :docModel="licence"
    stage=400
    @file-value-changed="submitDocument"
    @file-deleted="deleteDocument"
    :hasFile="hasMergeFile('Landlord Letter','Individual')"
    doc_type="Landlord Letter"
    belongsTo="Individual"
    orderByNum=800
    title="Landlord Letter"
    />

    <PrepareTemp
    :errors="errors"
    :docModel="licence"
    stage=400
    @file-value-changed="submitDocument"
    @file-deleted="deleteDocument"
    :hasFile="hasMergeFile('Security Letter','Individual')"
    doc_type="Security Letter"
    belongsTo="Individual"
    orderByNum=900
    title="Security Letter"
    />

    <PrepareTemp
    :errors="errors"
    :docModel="licence"
    stage=400
    @file-value-changed="submitDocument"
    @file-deleted="deleteDocument"
    :hasFile="hasMergeFile('Advert/Blurb','Individual')"
    doc_type="Advert/Blurb"
    belongsTo="Individual"
    orderByNum=1000
    title="Advert/Blurb"
    />

    <PrepareTemp
    :errors="errors"
    :docModel="licence"
    stage=400
    @file-value-changed="submitDocument"
    @file-deleted="deleteDocument"
    :hasFile="hasMergeFile('Plan/Maps','Individual')"
    doc_type="Plan/Maps"
    belongsTo="Individual"
    orderByNum=1100
    title="Plan/Maps"
    />
   

  <div class="col-sm-1"> </div>
 
  <div class="d-flex">
      <MergeButton 
      v-if="licence?.company_id && countMergeFiles('Individual')"
      @time-to-merge="mergeDocuments('Individual')"
      :licence="licence"
      :mergeForm="mergeForm"
      :disabled="countMergeFiles('Individual') <= 0 || mergeForm.processing"
    />
  </div>
 




  </div>
</div>

</div>

<hr>


<StageComponent
:column=6
:dbStatus="licence.status"
:errors="errors"
:error="error"
:stageValue=500
:prevStage=400
:licence_id="licence.slug"
:stageTitle="'Payment To The Liquor Board'"
:success="success"
@stage-value-changed="pushData"
/>

<DateComponent
:stage="'Payment To The Liquor Board'"
:licence="licence"
:canSave="$page.props.auth.has_slowtow_admin_role"
:errors="errors"
:error="error"
:column=4
@date-value-changed="updateStageDate"
:dated_at="form.payment_to_liquor_board_at"
:success="success"
/>


<DocComponent
:documentModel="licence"
@file-value-changed="submitDocument"
@file-deleted="deleteDocument"
:hasFile="hasFile('Payment To The Liquor Board')"
:errors="errors"
:error="error"
:orderByNumber=null
stage=500
:docType="'Payment To The Liquor Board'"
:success="success"
/>


<hr/>

<StageComponent
:column=12
:dbStatus="licence.status"
:errors="errors"
:error="error"
:stageValue=600
:prevStage=500
:licence_id="licence.slug"
:stageTitle="'Scanned Application'"
:success="success"
@stage-value-changed="pushData"
/>


<div class="col-md-6" style="margin-top: -1rem;">
  <DocComponent
  :documentModel="licence"
  @file-value-changed="submitDocument"
  @file-deleted="deleteDocument"
  :hasFile="hasFile('Scanned Application')"
  :errors="errors"
  :error="error"
  :orderByNumber=null
  stage=600
  :docType="'Scanned Application'"
  :success="success"
  />

</div>
<hr/>

<StageComponent
:column=6
:dbStatus="licence.status"
:errors="errors"
:error="error"
:stageValue=700
:prevStage=600
:licence_id="licence.slug"
:stageTitle="'Temporary Licence Lodged'"
:success="success"
@stage-value-changed="pushData"
/>

<DateComponent
:stage="'Temporary Licence Lodged'"
:licence="licence"
:canSave="$page.props.auth.has_slowtow_admin_role"
:errors="errors"
:error="error"
:column=4
@date-value-changed="updateStageDate"
:dated_at="form.logded_at"
:success="success"
/>



<div class="col-md-6" style="margin-top: -1rem;">

  <DocComponent
  :documentModel="licence"
  @file-value-changed="submitDocument"
  @file-deleted="deleteDocument"
  :hasFile="hasFile('Licence Lodged')"
  :errors="errors"
  :error="error"
  :orderByNumber=null
  stage=700
  :docType="'Licence Lodged'"
  :success="success"
  />

</div>
<hr>


<StageComponent
:column=6
:dbStatus="licence.status"
:errors="errors"
:error="error"
:stageValue=800
:prevStage=700
:licence_id="licence.slug"
:stageTitle="'Temporary Licence Issued'"
:success="success"
@stage-value-changed="pushData"
/>

<DateComponent
:stage="'Temporary Licence Issued'"
:licence="licence"
:canSave="$page.props.auth.has_slowtow_admin_role"
:errors="errors"
:error="error"
:column=4
@date-value-changed="updateStageDate"
:dated_at="form.issued_at"
:success="success"
/>

<DocComponent
  :documentModel="licence"
  @file-value-changed="submitDocument"
  @file-deleted="deleteDocument"
  :hasFile="hasFile('Licence Issued')"
  :errors="errors"
  :error="error"
  :orderByNumber=null
  stage="800"
  :docType="'Licence Issued'"
  :success="success"
  />
 
<hr>



<StageComponent
:column=6
:dbStatus="licence.status"
:errors="errors"
:error="error"
:stageValue=900
:prevStage=800
:licence_id="licence.slug"
:stageTitle="'Temporary Licence Delivered'"
:success="success"
@stage-value-changed="pushData"
/>

<DateComponent
:stage="'Temporary Licence Delivered'"
:licence="licence"
:canSave="$page.props.auth.has_slowtow_admin_role"
:errors="errors"
:error="error"
:column=4
@date-value-changed="updateStageDate"
:dated_at="form.delivered_at"
:success="success"
/>

<DocComponent
  :documentModel="licence"
  @file-value-changed="submitDocument"
  @file-deleted="deleteDocument"
  :hasFile="hasFile('Licence Delivered')"
  :errors="errors"
  :error="error"
  :orderByNumber=null
  stage="900"
  :docType="'Licence Delivered'"
  :success="success"
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

<hr/>
      <!-- <LiquorBoardRequest 
      :model_type='`Temporal Licence`'
      :model_id="licence.id" 
      :liqour_board_requests="liqour_board_requests"
      /> 
<hr>-->
<Task :tasks="tasks" :model_id="licence.id" :success="success" :error="error" :errors="errors" :model_type="'Temporal Licence'"/>

</div>
</div>

  </Layout>
</template>
