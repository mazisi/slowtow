
<template>
    <Layout>
      <Head title="Process New Application" />
    <div class="container-fluid">

        <Banner/>

        <div class="card card-body mx-3 mx-md-4 mt-n6">
          <div class="row">
      <div class="col-lg-12 col-12">
       <h6>Process New Wholesale Application - <Link :href="`/view-licence/?slug=${licence.slug}`" class="text-success">
        {{ licence.trading_name ? licence.trading_name : '' }} - {{ licence?.licence_number }}</Link></h6>
       <p class="text-sm mb-0">Current Stage:
        <span class="font-weight-bold ms-1">{{ getStatus(licence.status) }}</span>
     </p>
    </div>
    </div>

    <div class="row">
            <div class="mt-3 row">
              <div class="col-12 col-md-12 col-xl-12 position-relative">
                <div class="card card-plain h-100">
                  <div class="p-3 card-body">
    <form @submit.prevent="updateRegistration">
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
      <hr/>
      <DocComponent
      @file-value-changed="submitDocument"
          @file-deleted="deleteDocument"
         :documentModel="licence"
         :hasFile="hasFile('Client Quoted')"
         :errors="errors"
         :error="error"
         :orderByNumber=100
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
        prevStage=100
        :licence_id="licence.slug"
        :stageTitle="'Deposit Invoiced'"
        :success="success"
        @stage-value-changed="pushData"
      />
      <DocComponent
      @file-value-changed="submitDocument"
       @file-deleted="deleteDocument"
      :documentModel="licence"
      :hasFile="hasFile('Client Invoiced')"
      :errors="errors"
      :error="error"
      :orderByNumber=200
      :docType="'Client Invoiced'"
      :success="success"
      />

      <hr>


    <StageComponent
    :column=5
    :dbStatus="licence.status"
    :errors="errors"
    :error="error"
    :stageValue=300
    prevStage=200
    :licence_id="licence.slug"
    :stageTitle="'Client Paid'"
    :success="success"
    @stage-value-changed="pushData"
  />
    <div class="col-md-1 columns"></div>

      <DateComponent
      @date-value-changed="updateStageDate"
          :stage="'Client Paid'"
          :licence="licence"
          :canSave="$page.props.auth.has_slowtow_admin_role"
          :errors="errors"
          :error="error"
          :column=5
          :dated_at="getLicenceDate(licence.id, 'Client Paid')"
          :success="success"
        />


    <hr>


      <StageComponent
      :column=12
      :dbStatus="licence.status"
      :errors="errors"
      :error="error"
      :stageValue=400
      prevStage=300
      :licence_id="licence.slug"
      :stageTitle="'Prepare New Application'"
      :success="success"
      @stage-value-changed="pushData"
    />




    <div class="col-md-6">
      <MergeDocumentComponent
      @file-value-changed="submitDocument"
      @file-deleted="deleteDocument"
      :success="success"
      :error="error"
      :errors="errors"
      :column=6
      :docTitle="'BEE Certificate/Affidavit'"
      :docType="'BEE Certificate/Affidavit'"
      :docModel="licence"
      :stage=400
      :hasFile="hasFile('BEE Certificate/Affidavit')"
      />



    <MergeDocumentComponent
    @file-value-changed="submitDocument"
     @file-deleted="deleteDocument"
    :success="success"
    :error="error"
    :errors="errors"
    :column=6
    :model_id="licence"
    :docTitle="'Tax Clearance'"
    :docType="'Tax Clearance'"
    :docModel="licence"
    :stage=400
    :hasFile="hasFile('Tax Clearance')"
    />

    <MergeDocumentComponent
    @file-value-changed="submitDocument"
     @file-deleted="deleteDocument"
    :success="success"
    :error="error"
    :errors="errors"
    :column=6
    :model_id="licence"
    :docTitle="'ID Documents'"
    :docType="'ID Documents'"
    :docModel="licence"
    :stage=400
    :hasFile="hasFile('ID Documents')"
    />

    <MergeDocumentComponent
    @file-value-changed="submitDocument"
     @file-deleted="deleteDocument"
    :success="success"
    :error="error"
    :errors="errors"
    :column=6
    :model_id="licence"
    :docTitle="'Company Documents'"
    :docType="'Company Documents'"
    :docModel="licence"
    :stage=400
    :mergeNum="4"
    :hasFile="hasFile('Company Documents')"
    />

    <MergeDocumentComponent
    @file-value-changed="submitDocument"
     @file-deleted="deleteDocument"
    :success="success"
    :error="error"
    :errors="errors"
    :column=6
    :model_id="licence"
    :docTitle="'Zoning Certificate'"
    :docType="'Zoning Certificate'"
    :docModel="licence"
    :stage=400
    :hasFile="hasFile('Zoning Certificate')"
    />

    <MergeDocumentComponent
    @file-value-changed="submitDocument"
     @file-deleted="deleteDocument"
    :success="success"
    :error="error"
    :errors="errors"
    :column=6
    :model_id="licence"
    :docTitle="'LAA Certificate'"
    :docType="'LAA Certificate'"
    :docModel="licence"
    :stage=400
    :hasFile="hasFile('LAA Certificate')"
    />

    <MergeDocumentComponent
    @file-value-changed="submitDocument"
     @file-deleted="deleteDocument"
    :success="success"
    :error="error"
    :errors="errors"
    :column=6
    :model_id="licence"
    :docTitle="'Proof of rightful occupation'"
    :docType="'Proof of rightful occupation'"
    :docModel="licence"
    :stage=400
    :hasFile="hasFile('Proof of rightful occupation')"
    />

  </div>
  <div class="col-6">
    <MergeDocumentComponent
    @file-value-changed="submitDocument"
     @file-deleted="deleteDocument"
    :success="success"
    :error="error"
    :errors="errors"
    :column=6
    :docTitle="'Maps/Plans'"
    :docType="'Maps/Plans'"
    :docModel="licence"
    :stage="400"
    :hasFile="hasFile('Maps/Plans')"
    />

    <MergeDocumentComponent
    @file-value-changed="submitDocument"
     @file-deleted="deleteDocument"
    :success="success"
    :error="error"
    :errors="errors"
    :column=6
    :model_id="licence"
    :docTitle="'Photographs'"
    :docType="'Photographs'"
    :docModel="licence"
    :stage="500"
    :hasFile="hasFile('Photographs')"
    />


    <!-- This stage must only appear if  manufacturing -->
    <MergeDocumentComponent v-if="licence.licence_type === 'Manufacturing' && licence.licence_type !== 'Manufacturing and Distributing' "
    @file-value-changed="submitDocument"
     @file-deleted="deleteDocument"
    :success="success"
    :error="error"
    :errors="errors"
    :column=6
    :model_id="licence"
    :docTitle="'Product Analysis Certificate'"
    :docType="'Product Analysis Certificate'"
    :docModel="licence"
    :stage=400
    :mergeNum="19"
    :hasFile="hasFile('Product Analysis Certificate')"
    />

    <MergeDocumentComponent v-if="licence.import_export =='yes'"
    @file-value-changed="submitDocument"
     @file-deleted="deleteDocument"
    :success="success"
    :error="error"
    :errors="errors"
    :column=6
    :model_id="licence"
    :docTitle="'Import/Export'"
    :docType="'Import/Export'"
    :docModel="licence"
    :stage=400
    :hasFile="hasFile('Import/Export')"
    />

    <MergeDocumentComponent
    @file-value-changed="submitDocument"
     @file-deleted="deleteDocument"
    :success="success"
    :error="error"
    :errors="errors"
    :column=6
    :model_id="licence"
    :docTitle="'NLA Application Form'"
    :docType="'NLA Application Form'"
    :docModel="licence"
    :stage=400
    :mergeNum="21"
    :hasFile="hasFile('NLA Application Form')"
    />


    <MergeDocumentComponent
    @file-value-changed="submitDocument"
     @file-deleted="deleteDocument"
    :success="success"
    :error="error"
    :errors="errors"
    :column=6
    :model_id="licence"
    :docTitle="'Signed NLA 1 Form'"
    :docType="'Signed NLA 1 Form'"
    :docModel="licence"
    :stage=400
    :mergeNum="22"
    :hasFile="hasFile('Signed NLA 1 Form')"
    />

     <MergeDocumentComponent
     @file-value-changed="submitDocument"
     @file-deleted="deleteDocument"
     :success="success"
    :error="error"
    :errors="errors"
    :column="6"
    :model_id="licence"
    :docTitle="'Signed POA & RES'"
    :docType="'Signed POA & RES'"
    :docModel="licence"
    :stage="400"
    :hasFile="hasFile('Signed POA & RES')"
    />



  </div>
        <hr>

    <StageComponent
    :column=5
    :dbStatus="licence.status"
    :errors="errors"
    :error="error"
    :stageValue=500
    prevStage=400
    licence_id="licence.slug"
    :stageTitle="'Application Submitted'"
    :success="success"
    @stage-value-changed="pushData"
  />
  <div class="col-md-1 columns"></div>
    <DateComponent
    @date-value-changed="updateStageDate"
        :licence="licence"
        :stage="'Scanned Application'"
        :canSave="$page.props.auth.has_slowtow_admin_role"
        :errors="errors"
        :error="error"
        :column=5
        :dated_at="getLicenceDate(licence.id, 'Scanned Application')"
        :success="success"
        />



<hr/>
   <StageComponent
      :dbStatus="licence.status"
      :errors="errors"
      :error="error"
      :stageValue=600
      prevStage=500
      :licence_id="licence.slug"
      :stageTitle="'Initial Application Fee'"
      :success="success"
      @stage-value-changed="pushData"
   />

   <div class="col-md-6">
    <DocComponent
    @file-value-changed="submitDocument"
          @file-deleted="deleteDocument"
    :documentModel="licence"
    :hasFile="hasFile('Initial Application Fee')"
    :errors="errors"
    :error="error"
    :orderByNumber=600
    :docType="'Initial Application Fee'"
    :success="success"
  />
  </div>
  <DateComponent
  @date-value-changed="updateStageDate"
        :licence="licence"
        :stage="'Initial Application Fee'"
        :canSave="$page.props.auth.has_slowtow_admin_role"
        :errors="errors"
        :error="error"
        :column=5
        :dated_at="getLicenceDate(licence.id, 'Initial Application Fee')"
        :success="success"
        />
  <hr/>


  <StageComponent
  :column=5
  :dbStatus="licence.status"
  :errors="errors"
  :error="error"
  :stageValue=700
  prevStage=600
  :licence_id="licence.slug"
  :stageTitle="'Application Lodged'"
  :success="success"
  @stage-value-changed="pushData"
  />

  <div class="col-md-1 columns"></div>
  <DateComponent
  @date-value-changed="updateStageDate"
        :licence="licence"
        :stage="'Application Lodged'"
        :canSave="$page.props.auth.has_slowtow_admin_role"
        :errors="errors"
        :error="error"
        :column=5
        :dated_at="getLicenceDate(licence.id, 'Application Lodged')"
        :success="success"
        />
  <hr/>

  <StageComponent
  :dbStatus="licence.status"
  :errors="errors"
  :error="error"
  :stageValue=800
  prevStage=700
  :licence_id="licence.slug"
  :stageTitle="'Additional Documents Request'"
  :success="success"
  @stage-value-changed="pushData"
  />


    <AdditionalDocsComponent
    :licence_id="licence.id"
    :additional_docs="licence.additional_docs"
    :success="success"
    :errors="errors"
    modelable_type="Licence"
    :error="error"/>

  <hr/>
  <StageComponent
  :dbStatus="licence.status"
  :errors="errors"
  :error="error"
  :stageValue=900
  prevStage=800
  :licence_id="licence.slug"
  :stageTitle="'NLA 6 Proposed'"
  :success="success"
  @stage-value-changed="pushData"
  />

  <div class="col-md-6">
    <DocComponent
    @file-value-changed="submitDocument"
          @file-deleted="deleteDocument"
    :documentModel="licence"
    :hasFile="hasFile('NLA 6 Proposed')"
    :errors="errors"
    :error="error"
    :orderByNumber=900
    :docType="'NLA 6 Proposed'"
    :success="success"
    />
    </div>

    <DateComponent
    @date-value-changed="updateStageDate"
        :licence="licence"
        :stage="'NLA 6 Proposed'"
        :canSave="$page.props.auth.has_slowtow_admin_role"
        :errors="errors"
        :error="error"
        :column=5
        :dated_at="getLicenceDate(licence.id, 'NLA 6 Proposed')"
        :success="success"
        />
  <hr>

  <StageComponent
  :dbStatus="licence.status"
  :errors="errors"
  :error="error"
  :stageValue=1000
  prevStage=900
  :licence_id="licence.slug"
  :stageTitle="'NLA 7 Submitted'"
  :success="success"
  @stage-value-changed="pushData"
  />

  <div class="col-md-6">
    <DocComponent
    @file-value-changed="submitDocument"
          @file-deleted="deleteDocument"
    :documentModel="licence"
    :hasFile="hasFile('NLA 7 Submitted')"
    :errors="errors"
    :error="error"
    :orderByNumber=1000
    :docType="'NLA 7 Submitted'"
    :success="success"
    />
    </div>
    <DateComponent
    @date-value-changed="updateStageDate"
        :licence="licence"
        :stage="'NLA 7 Submitted'"
        :canSave="$page.props.auth.has_slowtow_admin_role"
        :errors="errors"
        :error="error"
        :column=5
        :dated_at="getLicenceDate(licence.id, 'NLA 7 Submitted')"
        :success="success"
        />
  <hr>

  <!-- Limpopo and Northwest ====================== -->
  <StageComponent
  :dbStatus="licence.status"
  :errors="errors"
  :error="error"
  :stageValue=1100
  prevStage=1000
  :licence_id="licence.slug"
  :stageTitle="'NLA 8 Issued'"
  :success="success"
  @stage-value-changed="pushData"
  />

  <div class="col-md-6">
    <DocComponent
    @file-value-changed="submitDocument"
          @file-deleted="deleteDocument"
    :documentModel="licence"
    :hasFile="hasFile('NLA 8 Issued')"
    :errors="errors"
    :error="error"
    :orderByNumber=1100
    :docType="'NLA 8 Issued'"
    :success="success"
    />
    </div>
    <DateComponent
        @date-value-changed="updateStageDate"
        :licence="licence"
        :stage="'NLA 8 Issued'"
        :canSave="$page.props.auth.has_slowtow_admin_role"
        :errors="errors"
        :error="error"
        :column=5
        :dated_at="getLicenceDate(licence.id, 'NLA 8 Issued')"
        :success="success"
        />
  <hr>

  <StageComponent
  :dbStatus="licence.status"
  :errors="errors"
  :error="error"
  :stageValue=1200
   prevStage=1100
  :licence_id="licence.slug"
  :stageTitle="'Activation Fee'"
  :success="success"
  @stage-value-changed="pushData"
  />

  <div class="col-md-6">
    <DocComponent
    @file-value-changed="submitDocument"
          @file-deleted="deleteDocument"
    :documentModel="licence"
    :hasFile="hasFile('Activation Fee')"
    :errors="errors"
    :error="error"
    :orderByNumber=1200
    :docType="'Activation Fee'"
    :success="success"
    />
    </div>
    <DateComponent
    @date-value-changed="updateStageDate"
        :licence="licence"
        :stage="'Activation Fee'"
        :canSave="$page.props.auth.has_slowtow_admin_role"
        :errors="errors"
        :error="error"
        :column=5
        :dated_at="getLicenceDate(licence.id, 'Activation Fee')"
        :success="success"
        />
  <hr>

  <!-- Limpopo and Northwest ====================== -->
  <StageComponent
  :dbStatus="licence.status"
  :errors="errors"
  :error="error"
  :stageValue=1300
  prevStage=1200
  :licence_id="licence.slug"
  :stageTitle="'NLA 9 Issued'"
  :success="success"
  @stage-value-changed="pushData"
  />

  <div class="col-md-6">
    <DocComponent
    @file-value-changed="submitDocument"
    @file-deleted="deleteDocument"
    :documentModel="licence"
    :hasFile="hasFile('NLA 9 Issued')"
    :errors="errors"
    :error="error"
    :orderByNumber=1300
    :docType="'NLA 9 Issued'"
    :success="success"
    />
    </div>
    <DateComponent
    @date-value-changed="updateStageDate"
        :licence="licence"
        :stage="'NLA 9 Issued'"
        :canSave="$page.props.auth.has_slowtow_admin_role"
        :errors="errors"
        :error="error"
        :column=5
        :dated_at="getLicenceDate(licence.id, 'NLA 9 Issued')"
        :success="success"
        />
  <hr>

      <StageComponent
      :dbStatus="licence.status"
      :errors="errors"
      :error="error"
      :stageValue=1400
      prevStage=1300
      :licence_id="licence.slug"
      :stageTitle="'Original Licence'"
      :success="success"
      @stage-value-changed="pushData"
      />
      <div class="col-md-6" >
      <DocComponent
      @file-value-changed="submitDocument"
          @file-deleted="deleteDocument"
      :documentModel="licence"
      :hasFile="hasFile('Original-Licence')"
      :errors="errors"
      :error="error"
      :orderByNumber=1400
      :docType="'Original-Licence'"
      :success="success"
      />
      </div>


  <hr/>

  <StageComponent
  :dbStatus="licence.status"
  :errors="errors"
  :error="error"
  :stageValue=1500
  prevStage=1400
  :licence_id="licence.slug"
  :stageTitle="'Original Licence Delivered'"
  :success="success"
  @stage-value-changed="pushData"
  />

  <div class="col-md-6">
    <DocComponent
    @file-value-changed="submitDocument"
          @file-deleted="deleteDocument"
    :documentModel="licence"
    :hasFile="hasFile('Duplicate-Original-Licence-Delivered')"
    :errors="errors"
    :error="error"
    :orderByNumber=1500
    :docType="'Duplicate-Original-Licence-Delivered'"
    :success="success"
   />
  </div>
    <DateComponent
    @date-value-changed="updateStageDate"
    :licence="licence"
    :stage="'Original Licence Delivered'"
    :canSave="$page.props.auth.has_slowtow_admin_role"
    :errors="errors"
    :error="error"
    :column=5
    :dated_at="getLicenceDate(licence.id, 'Original Licence Delivered')"
    :success="success"
    />

  <hr/>




    <div>
      <!-- If its issued stage -->
      <div v-if="form.status >= 2300" class="text-xs text-danger d-flex">Please note that this licence will no longer be a
        new application and this action is irreversible once saved.</div>
    </div>
    </div>
    </form>
      </div>
    </div>
    <hr class="vertical dark" />
    </div>

    </div>

    </div>
    <hr>

    <Task
    v-if="licence.status >= 1100"
    :tasks="tasks"
    :model_id="licence.id"
    :success="success"
    :error="error"
    :errors="errors"
    :model_type="'Licence'"/>



    </div>
    </div>



      </Layout>
    </template>
      <style src="@vueform/multiselect/themes/default.css"></style>
    <style >
    .columns{
      margin-bottom: 1rem;
  }
  .upload-icon{
      cursor: pointer;
  }
  .active-checkbox{
      margin-top: -10px;
      margin-left: 3px;
  }
  .status-heading{
      font-weight: 700;
  }
  .list-group{
      margin-top: -1.4rem;
  }


  </style>

  <script src="./wholesale.js"></script>

