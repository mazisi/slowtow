
  <template>
  <Layout>
    <Head title="Process New Application" />
  <div class="container-fluid">

      <Banner/>

      <div class="card card-body mx-3 mx-md-4 mt-n6">
        <div class="row">
    <div class="col-lg-12 col-12">
     <h6>Process New Application for: <Link :href="`/view-licence/?slug=${licence.slug}`" class="text-success">
      {{ licence.trading_name ? licence.trading_name : '' }}</Link></h6>
     <p class="text-sm mb-0">Current Stage: 
      <span class="font-weight-bold ms-1">{{ getStatus(licence.status) }}</span>
   </p>
  </div>
    <div class="col-lg-6 col-5 my-auto text-end">
      <!-- <button v-if="$page.props.auth.has_slowtow_admin_role"
       @click="deleteRegistration" class="dropdown-item border-radius-md text-danger" >
       <i class="fa fa-trash-o cursor-pointer" aria-hidden="true"></i> Delete</button>     -->
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
      :stageValue=100
      :licence_id="licence.slug"
      :stageTitle="'Client Quoted'"
      :success="success"
      @stage-value-changed="pushData"
    />
    <hr/>
    <DocComponent
       :documentModel="client_quoted"
       :errors="errors"
       :orderByNumber=100
       :docType="'Client Quoted'"
       :success="success"
       />
  
  <hr>
  
   <StageComponent
      :column=12
      :dbStatus="licence.status"
      :errors="errors"
      :stageValue=200
      :licence_id="licence.slug"
      :stageTitle="'Deposit Invoice'"
      :success="success"
      @stage-value-changed="pushData"
    />
    <DocComponent
    :documentModel="client_invoiced"
    :errors="errors"
    :orderByNumber=200
    :docType="'Client Invoiced'"
    :success="success"
    />
    
    <hr>
  

  <StageComponent
  :column=5
  :dbStatus="licence.status"
  :errors="errors"
  :stageValue=300
  :licence_id="licence.slug"
  :stageTitle="'Deposit Paid'"
  :success="success"
  @stage-value-changed="pushData"
/>
  <div class="col-md-1 columns"></div>
  
  <template v-if="licence.deposit_paid_at">
    <DateComponent
        :canSave="$page.props.auth.has_slowtow_admin_role"
        :errors="errors"
        :column=4
        :dated_at="'Pass the Deposit paid stage date here'"
        :success="success"
      />  
   </template>
   
   <template v-else>
    <DateComponent
        :canSave="$page.props.auth.has_slowtow_admin_role"
        :errors="errors"
        :column=4
        :dated_at="'Pass the Deposit paid stage date here'"
        :success="success"
      />  
   </template>
  
  <hr>

    
    <StageComponent
    :column=5
    :dbStatus="licence.status"
    :errors="errors"
    :stageValue=400
    :licence_id="licence.slug"
    :stageTitle="'Payment To The Liquor Board'"
    :success="success"
    @stage-value-changed="pushData"
  />
    
    <div class="col-md-1 columns"></div>
     
       <template v-if="licence.liquor_board_at">   
        <DateComponent
        :canSave="$page.props.auth.has_slowtow_admin_role"
        :errors="errors"
        :column=4
        :dated_at="'Pass the Liquor Board stage date here'"
        :success="success"
      />   
       
         </template>
         
         <template v-else>
          <DateComponent
          :canSave="$page.props.auth.has_slowtow_admin_role"
          :errors="errors"
          :column=4
          :dated_at="'Pass the Liquor Board stage date here'"
          :success="success"
        />       
           
         </template> 
    

         <DocComponent
          :documentModel="licence"
          :errors="errors"
          :orderByNumber=400
          :docType="'Payment To The Liquor Board'"
          :success="success"
          />

    <hr>


      <StageComponent
      :column=12
      :dbStatus="licence.status"
      :errors="errors"
      :stageValue=500
      :licence_id="licence.slug"
      :stageTitle="'Prepare New Application'"
      :success="success"
      @stage-value-changed="pushData"
    />


    
  <div class="col-md-6">
    <MergeDocumentComponent
    :column=6
    :model_id="licence"
    :docTitle="'GLB Application Forms'"
    :docType="'GLB Application Forms'"
    :docModel="'gba_application_form'"
    :stage=500
    :mergeNum=1
    />
    


    <div class="row"><div class="col-md-7" >
      <button type="button" class="btn btn-outline-success w-95">Proof of Payment</button>
    </div>
    <a v-if="payment_to_liqour_board !== null" :href="`${$page.props.blob_file_path}${payment_to_liqour_board.document_file}`" target="_blank" class="col-md-1">
      <i class="fa fa-link h5 upload-icon col-md-3 disabled"></i>
    </a>
  </div>

  <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'Application Forms'"
  :docType="'Application Forms'"
  :docModel="'application_forms'"
  :stage=500
  :mergeNum=3
  />

  <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'Company Documents'"
  :docType="'Company Documents'"
  :docModel="'company_docs'"
  :stage=500
  :mergeNum=4
  />
  
  <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'CIPC Documents'"
  :docType="'CIPC Documents'"
  :docModel="'cipc_docs'"
  :stage=500
  :mergeNum=5
  />

  <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'ID Documents'"
  :docType="'ID Documents'"
  :docModel="'id_docs'"
  :stage=500
  :mergeNum=6
  />

  <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'Police Clearance'"
  :docType="'Police Clearance'"
  :docModel="'id_docs'"
  :stage=500
  :mergeNum=7
  />

  <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'Tax Clearance'"
  :docType="'Tax Clearance'"
  :docModel="'tax_clearance'"
  :stage=500
  :mergeNum=8
  />

  <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'LTA Certificate'"
  :docType="'LTA Certificate'"
  :docModel="'lta_certificate'"
  :stage=500
  :mergeNum=9
  />

  <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'Shareholding Info'"
  :docType="'Shareholding Info'"
  :docModel="'shareholding_info'"
  :stage=500
  :mergeNum=10
  />

  <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'Financial Interests'"
  :docType="'Financial Interests'"
  :docModel="'financial_interests'"
  :stage=500
  :mergeNum=11
  />

  <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'500m Affidavit'"
  :docType="'500m Affidavit'"
  :docModel="'_500m_affidavict'"
  :stage=500
  :mergeNum=12
  />


  <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'Government Gazette Adverts'"
  :docType="'Government Gazette Adverts'"
  :docModel="'government_gazette'"
  :stage=500
  :mergeNum=13
  />

  <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'Advert Affidavit'"
  :docType="'Advert Affidavit'"
  :docModel="'advert_affidavict'"
  :stage=500
  :mergeNum=14
  />

  <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'Proof of Occupation'"
  :docModel="'proof_of_occupation'"
  :stage=500
  :mergeNum=15
  />

  <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'Representations'"
  :docType="'Representations'"
  :docModel="'representations'"
  :stage=500
  :mergeNum=16
  />



  <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'Menu-If applicable'"
  :docType="'Menu'"
  :docModel="'representations'"
  :stage=500
  :mergeNum=17
  />

  <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'Photographs'"
  :docType="'Photographs'"
  :docModel="'photographs'"
  :stage=500
  :mergeNum=17
  />

  <MergeDocumentComponent
  v-if="licence.province === 'Mpumalanga'"
  :column=6
  :model_id="licence"
  :docTitle="'Municipal Consent Ltr'"
  :docType="'Municipal Consent Ltr'"
  :docModel="'consent_letter'"
  :stage=500
  :mergeNum=19
  />
  <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'Zoning Certificate'"
  :docType="'Zoning Certificate'"
  :docModel="'zoning_certificate'"
  :stage=500
  :mergeNum=20
  />

  <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'Local Authority Letter'"
  :docType="'Local Authority Letter'"
  :docModel="'local_authority'"
  :stage=500
  :mergeNum=21
  />

  <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'Local Authority Letter'"
  :docType="'Local Authority Letter'"
  :docModel="'local_authority'"
  :stage=500
  :mergeNum=21
  />
    

  <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'Mapbook Plans'"
  :docType="'Mapbook Plans'"
  :docModel="'mapbook_plans'"
  :stage=500
  :mergeNum=22
  />

   <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'Google Map Plans'"
  :docType="'Google Map Plans'"
  :docModel="'google_map_plans'"
  :stage=500
  :mergeNum=23
  />

  <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'Google Map Plans'"
  :docType="'Google Map Plans'"
  :docModel="'google_map_plans'"
  :stage=500
  :mergeNum=23
  />

  <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'Description'"
  :docType="'Description'"
  :docModel="'description'"
  :stage=500
  :mergeNum=24
  />

  <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'Site Plans'"
  :docType="'Site Plans'"
  :docModel="'site_plans'"
  :stage=500
  :mergeNum=25
  />

  <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'Fully Dimensioned Plans'"
  :docType="'Full Dimensioned Plans'"
  :docModel="'dimensional_plans'"
  :stage=500
  :mergeNum=26
  />

  <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'Advert Photographs'"
  :docType="'Advert Photographs'"
  :docModel="'advert_photographs'"
  :stage=500
  :mergeNum=27
  />

  <MergeDocumentComponent
  :column=6
  :model_id="licence"
  :docTitle="'Newspaper Adverts'"
  :docType="'Newspaper Adverts'"
  :docModel="'newspaper_adverts'"
  :stage=500
  :mergeNum=28
  />

  <br><br>
  
   <div class="d-flex ">
    <MergeButtonComponent
    :title="'Compile Application'"
    :isLecenceMerged="licence.merged_document"
    />
    <!-- remember to bind disbaled this btn when all required files are not uploaded -->
    </div>

</div> 
      <hr>
  
  <StageComponent
  :column=12
  :dbStatus="licence.status"
  :errors="errors"
  :stageValue=600
  :licence_id="licence.slug"
  :stageTitle="'Scanned Application'"
  :success="success"
  @stage-value-changed="pushData"
/>

  <div class="col-md-6">
    <DocComponent
    :documentModel="scanned_application"
    :errors="errors"
    orderByNumber=600
    :docType="'Scanned Application'"
    :success="success"
/>
 
  </div>
  <hr>


  
 <!-- When its Mpumalanga province ============================================== -->
 <StageComponent
    :dbStatus="licence.status"
    :errors="errors"
    :stageValue=700
    :licence_id="licence.slug"
    :stageTitle="'Lodged with Municipality'"
    :success="success"
    @stage-value-changed="pushData"
 />

 <div class="col-md-6">
  <DocComponent
  :documentModel="licence"
  :errors="errors"
  :orderByNumber=700
  :docType="'Lodged with Municipality'"
  :success="success"
/>
</div>
<hr/> 

<!-- When its Mpumalanga province ============================================== -->
<StageComponent
:dbStatus="licence.status"
:errors="errors"
:stageValue=800
:licence_id="licence.slug"
:stageTitle="'Municipal Comments'"
:success="success"
@stage-value-changed="pushData"
/>

<div class="col-md-6">
<DocComponent
:documentModel="licence"
:errors="errors"
:orderByNumber=800
:docType="'Municipal Comments'"
:success="success"
/>
</div>
<hr/> 
<!-- //Mpumalanga================= -->
<StageComponent
:dbStatus="licence.status"
:errors="errors"
:stageValue=900
:licence_id="licence.slug"
:stageTitle="'Completed Application Scanned'"
:success="success"
@stage-value-changed="pushData"
/>

<div class="col-md-6">
  <DocComponent
  :documentModel="licence"
  :errors="errors"
  :orderByNumber=900
  :docType="'Completed Application Scanned'"
  :success="success"
  />
  </div>
<hr>

<StageComponent
:dbStatus="licence.status"
:errors="errors"
:stageValue=1000
:licence_id="licence.slug"
:stageTitle="'Lodged with MER'"
:success="success"
@stage-value-changed="pushData"
/>

<div class="col-md-6">
  <DocComponent
  :documentModel="licence"
  :errors="errors"
  :orderByNumber=1000
  :docType="'Lodged with MER'"
  :success="success"
  />
  </div>
<hr>

<!-- Limpopo and Northwest ====================== -->
<StageComponent
:dbStatus="licence.status"
:errors="errors"
:stageValue=1100
:licence_id="licence.slug"
:stageTitle="'Lodged with Magistrate'"
:success="success"
@stage-value-changed="pushData"
/>

<div class="col-md-6">
  <DocComponent
  :documentModel="licence"
  :errors="errors"
  :orderByNumber=1100
  :docType="'Lodged with Magistrate'"
  :success="success"
  />
  </div>
<hr>

<!-- Limpopo and Northwest ====================== -->
<StageComponent
:dbStatus="licence.status"
:errors="errors"
:stageValue=1200
:licence_id="licence.slug"
:stageTitle="'Lodged with DPO'"
:success="success"
@stage-value-changed="pushData"
/>

<div class="col-md-6">
  <DocComponent
  :documentModel="licence"
  :errors="errors"
  :orderByNumber=1200
  :docType="'Lodged with DPO'"
  :success="success"
  />
  </div>
<hr>

<!-- Limpopo and Northwest ====================== -->
<StageComponent
:dbStatus="licence.status"
:errors="errors"
:stageValue=1300
:licence_id="licence.slug"
:stageTitle="'Police Report'"
:success="success"
@stage-value-changed="pushData"
/>

<div class="col-md-6">
  <DocComponent
  :documentModel="licence"
  :errors="errors"
  :orderByNumber=1300
  :docType="'Police Report'"
  :success="success"
  />
  </div>
<hr>




<!-- If its Mpumalanga , renamed this stage-->
    <StageComponent
    :dbStatus="licence.status"
    :errors="errors"
    :stageValue=1400
    :licence_id="licence.slug"
    :stageTitle="'Lodged With Liqour Board(renamed)'"
    :success="success"
    @stage-value-changed="pushData"
    />   
    
     <template v-if="licence.application_lodged_at">   
      <DateComponent
      :canSave="$page.props.auth.has_slowtow_admin_role"
      :errors="errors"
      :column=5
      :dated_at="'Pass the stage date here'"
      :success="success"
      />      
       </template>
       
       <template v-else>
        
        <DateComponent
        :canSave="$page.props.auth.has_slowtow_admin_role"
        :errors="errors"
        :column=5
        :dated_at="'Pass the stage date here'"
        :success="success"
      />   
       </template> 

       <DocComponent
        :documentModel="application_logded"
        :errors="errors"
        :orderByNumber=1400
        :docType="'Application Lodged'"
        :success="success"
       />
<hr/>

<!-- If its other provinces keep this stage-->
<StageComponent
:dbStatus="licence.status"
:errors="errors"
:stageValue=1500
:licence_id="licence.slug"
:stageTitle="'Application Lodged (Proof of Lodgement)'"
:success="success"
@stage-value-changed="pushData"
/>   

 <template v-if="licence.application_lodged_at">   
  <DateComponent
  :canSave="$page.props.auth.has_slowtow_admin_role"
  :errors="errors"
  :column=5
  :dated_at="'Pass the stage date here'"
  :success="success"
  />      
   </template>
   
   <template v-else>
    
    <DateComponent
    :canSave="$page.props.auth.has_slowtow_admin_role"
    :errors="errors"
    :column=5
    :dated_at="'Pass the stage date here'"
    :success="success"
  />   
   </template> 

   <DocComponent
    :documentModel="application_logded"
    :errors="errors"
    :orderByNumber=1500
    :docType="'Application Lodged'"
    :success="success"
   />
<hr/>

<!-- this stage must appear once licence lodged is ticked -->
<template v-if="licence.status >= 1500">

<StageComponent
:dbStatus="licence.status"
:errors="errors"
:stageValue=1600
:licence_id="licence.slug"
:stageTitle="'Additional Documents/Information'"
:success="success"
@stage-value-changed="pushData"
/>

    
  <AdditionalDocsComponent 
  :licence_id="licence.id" 
  :additional_docs="additional_docs"
  :success="success" 
  :error="error" 
  :errors="errors"/>
</template>
<hr/>

  <StageComponent
    :dbStatus="licence.status"
    :errors="errors"
    :stageValue=1700
    :licence_id="licence.slug"
    :stageTitle="'Initial Inspection'"
    :success="success"
    @stage-value-changed="pushData"
    />
        
        
        
  <template v-if="licence.initial_inspection_at">   
  
  <DateComponent
        :canSave="$page.props.auth.has_slowtow_admin_role"
        :errors="errors"
        :column=4
        :dated_at="'Pass the Inspection stage date here'"
        :success="success"
      />   
  </template>
    
    <template v-else>
      <DateComponent
      :canSave="$page.props.auth.has_slowtow_admin_role"
      :errors="errors"
      :column=4
      :dated_at="'Pass the Inspection stage date here'"
      :success="success"
    />    
  </template>  

  <DocComponent
  :documentModel="application_logded"
  :errors="errors"
  :orderByNumber=1700
  :docType="'Initial Inspection'"
  :success="success"
  />
<hr/>

  

    <StageComponent
    :dbStatus="licence.status"
    :errors="errors"
    :stageValue=1800
    :licence_id="licence.slug"
    :stageTitle="'Final Inspection'"
    :success="success"
    @stage-value-changed="pushData"
    />

  <template v-if="licence.final_inspection_at"> 
       
  <DateComponent
      :canSave="$page.props.auth.has_slowtow_admin_role"
      :errors="errors"
      :column=4
      :dated_at="'Pass the Final Inspection stage date here'"
      :success="success"
    />  
   </template>
           
  <template v-else>      
    <DateComponent
    :canSave="$page.props.auth.has_slowtow_admin_role"
    :errors="errors"
    :column=4
    :dated_at="'Pass the Final Inspection stage date here'"
    :success="success"
  />  

   
 </template>  

 <DocComponent
 :documentModel="final_inspection_doc"
 :errors="errors"
 :orderByNumber=1800
 :docType="'Final Inspection'"
 :success="success"
 />

  <hr/>

    
    <StageComponent
    :dbStatus="licence.status"
    :errors="errors"
    :stageValue=1900
    :licence_id="licence.slug"
    :stageTitle="'Activation Fee Requested'"
    :success="success"
    @stage-value-changed="pushData"
    />
    

    <template v-if="licence.activation_fee_requested_at"> 
       
    <DateComponent
    :canSave="$page.props.auth.has_slowtow_admin_role"
    :errors="errors"
    :column=4
    :dated_at="'Pass the Activation Fee Requested stage date here'"
    :success="success"
  />  

 </template>
               
 <template v-else>      
  <DateComponent
  :canSave="$page.props.auth.has_slowtow_admin_role"
  :errors="errors"
  :column=4
  :dated_at="'Pass the Activation Fee Requested stage date here'"
  :success="success"
/>  
     </template>  

    <hr/> 

    
      <StageComponent
      :dbStatus="licence.status"
      :errors="errors"
      :column=12
      :stageValue=2000
      :licence_id="licence.slug"
      :stageTitle="'Client Finalisation Invoice'"
      :success="success"
      @stage-value-changed="pushData"
      />
           
      <DocComponent
      :documentModel="final_inspection_doc"
      :errors="errors"
      :orderByNumber=2000
      :docType="'Client Finalisation Invoiced'"
      :success="success"
      />
    
      <hr/>
      
      <!-- this was previouly client paid -->
      <StageComponent
      :dbStatus="licence.status"
      :errors="errors"
      :column=6
      :stageValue=2100
      :licence_id="licence.slug"
      :stageTitle="'Finalisation Paid'"
      :success="success"
      @stage-value-changed="pushData"
      />

        
 <template v-if="licence.client_paid_at"> 

  <DateComponent
  :canSave="$page.props.auth.has_slowtow_admin_role"
  :errors="errors"
  :column=4
  :dated_at="'Pass the Finalisation Paid date here'"
  :success="success"
/> 
  
</template>
                     
<template v-else>      
  <DateComponent
  :canSave="$page.props.auth.has_slowtow_admin_role"
  :errors="errors"
  :column=4
  :dated_at="'Pass the Finalisation Paid date here'"
  :success="success"
/>  
 </template>  
        <hr/>

      
          <StageComponent
          :dbStatus="licence.status"
          :errors="errors"
          :column=6
          :stageValue=2200
          :licence_id="licence.slug"
          :stageTitle="'Activation Fee Paid'"
          :success="success"
          @stage-value-changed="pushData"
          />
          


           <template v-if="licence.activation_fee_paid_at"> 
            <DateComponent
            :canSave="$page.props.auth.has_slowtow_admin_role"
            :errors="errors"
            :column=4
            :dated_at="'Pass the Activation Fee Paid date here'"
            :success="success"
          />  
          </template>
                               
          <template v-if="licence.activation_fee_paid_at"> 
            <DateComponent
            :canSave="$page.props.auth.has_slowtow_admin_role"
            :errors="errors"
            :column=4
            :dated_at="'Pass the Activation Fee Paid Else date here'"
            :success="success"
          />  
       </template> 


       <DocComponent
       :documentModel="final_inspection_doc"
       :errors="errors"
       :orderByNumber=2200
       :docType="'Activation Fee Paid'"
       :success="success"
       />
          <hr/>
          
          
          <StageComponent
          :dbStatus="licence.status"
          :errors="errors"
          :column=6
          :stageValue=2300
          :licence_id="licence.slug"
          :stageTitle="'Licence Issued'"
          :success="success"
          @stage-value-changed="pushData"
          />
           
   
             
             <template v-if="licence.licence_issued_at"> 
              <DateComponent
              :canSave="$page.props.auth.has_slowtow_admin_role"
              :errors="errors"
              :column=4
              :dated_at="'Pass the Licence Issued date here'"
              :success="success"
            />  
              
          </template>
                               
          <template v-else>      
            <DateComponent
            :canSave="$page.props.auth.has_slowtow_admin_role"
            :errors="errors"
            :column=4
            :dated_at="'Pass the Licence Issued date here'"
            :success="success"
          />  
       </template> 

       <DocComponent
       :documentModel="final_inspection_doc"
       :errors="errors"
       :orderByNumber=2300
       :docType="'Licence Issued '"
       :success="success"
       />
         <hr/>

              <StageComponent
                :dbStatus="licence.status"
                :errors="errors"
                :column=6
                :stageValue=2400
                :licence_id="licence.slug"
                :stageTitle="'Licence Delivered'"
                :success="success"
                @stage-value-changed="pushData"
                />
               <template v-if="licence.licence_delivered_at"> 
                <DateComponent
                :canSave="$page.props.auth.has_slowtow_admin_role"
                :errors="errors"
                :column=4
                :dated_at="'Pass the Licence Delivered date here'"
                :success="success"
              /> 
                
            </template>
                                 
            <template v-else>      
              <DateComponent
              :canSave="$page.props.auth.has_slowtow_admin_role"
              :errors="errors"
              :column=4
              :dated_at="'Pass the Licence Delivered date here'"
              :success="success"
            /> 
         </template> 

         <DocComponent
         :documentModel="final_inspection_doc"
         :errors="errors"
         :orderByNumber=2400
         :docType="'Licence Delivered '"
         :success="success"
         />
           
  
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
  v-if="licence.status >= 150"
  :tasks="tasks" 
  :model_id="licence.id" 
  :success="success" 
  :error="error" 
  :errors="errors" 
  :model_type="'Licence'"/>



  </div>
  </div>

  
  <!-- <div v-if="show_modal" class="modal fade" id="documents" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Upload Document</h5>
          
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <p v-if="uploadDoc.doc_type == 'Licence Issued'
        || uploadDoc.doc_type == 'Licence Delivered'" class="p-2 text-danger">
          Please note that this licence will no longer be a new application and this action 
          is irreversible once saved.</p>

        <form @submit.prevent="submitDocument">
        <input type="hidden" v-model="uploadDoc.doc_type">
        <input type="hidden" v-model="uploadDoc.num">
        <div class="modal-body">      
          <div class="row">    
          <div class="col-md-12 columns">
          <label for="licence-doc" class="btn btn-dark w-100" href="">Select File</label>
           <input type="file" @change="getFileName"
           hidden id="licence-doc" accept=".pdf"/>
           <div v-if="errors.doc" class="text-danger">{{ errors.doc }}</div>
           <div v-if="file_name && show_file_name">File Selected: <span class="text-success" v-text="file_name"></span></div>
             <p v-if="file_has_apostrophe" class="text-danger text-sm mt-4">Sorry <span class="text-success">{{ file_name }}</span> cannot contain apostrophe(s).Replace apostrophes with backticks.</p>  
           </div>
         <div class="col-md-12">
            <progress v-if="uploadDoc.progress" :value="uploadDoc.progress.percentage" max="100">
           {{ uploadDoc.progress.percentage }}%
           </progress>
           </div>
           </div> 
        </div>
    
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" :disabled="uploadDoc.processing || file_has_apostrophe">
           <span v-if="uploadDoc.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
           Save</button>
           </div>
        </form>
        
      </div>
    </div>
  </div> -->

    </Layout>
  </template>
    <style src="@vueform/multiselect/themes/default.css"></style>
  <style scoped>
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

  <script src="./registration.js"></script>
  
  