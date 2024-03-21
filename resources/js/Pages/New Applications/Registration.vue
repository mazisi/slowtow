
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
                          :documentModel="licence"
                          @file-value-changed="submitDocument"
                          @file-deleted="deleteDocument"
                          :hasFile="hasFile('Client Quoted')"
                          :errors="errors"
                          :error="error"
                          :orderByNumber=100
                          :docType="'Client Quoted'"
                          :success="success"
                      />

                      <hr>

                      <stage-component
                          :column=12
                          :dbStatus="licence.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=200
                          prevStage=100
                          :licence_id="licence.slug"
                          :stageTitle="'Client Invoice'"
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
                          :stageTitle="'Deposit Paid'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />
                      <div class="col-md-1 columns"></div>
                      <!-- v-if="getLicenceDate(licence.id, 'Deposit Paid') == 'Deposit Paid'" -->

                      <DateComponent
                          :stage="'Deposit Paid'"
                          :licence="licence"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          @date-value-changed="updateStageDate"
                          :dated_at="getLicenceDate(licence.id, 'Deposit Paid')"
                          :success="success"
                      />


                      <hr>


                      <StageComponent
                          :column=5
                          :dbStatus="licence.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=400
                          prevStage=300
                          :licence_id="licence.slug"
                          :stageTitle="'Payment To The Liquor Board'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />

                      <div class="col-md-1 columns"></div>


                      <DateComponent
                          :stage="'Payment To The Liquor Board'"
                          :licence="licence"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          @date-value-changed="updateStageDate"
                          :dated_at="getLicenceDate(licence.id, 'Payment To The Liquor Board')"
                          :success="success"
                      />




                      <DocComponent
                          :documentModel="licence"
                          @file-value-changed="submitDocument"
                          @file-deleted="deleteDocument"
                          :hasFile="hasFile('Payment To The Liquor Board')"
                          :errors="errors"
                          :error="error"
                          :orderByNumber=400
                          :docType="'Payment To The Liquor Board'"
                          :success="success"
                      />

                      <hr>


                      <StageComponent
                          :column=12
                          :dbStatus="licence.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=500
                          prevStage=400
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
                            :docTitle="'GLB Application Forms'"
                            :docType="'GLB Application Forms'"
                            :docModel="licence"
                            :stage=500
                            :mergeNum="1"
                            :hasFile="hasFile('GLB Application Forms')"
                        />

                        <LinkComponent
                           :hasFile="hasFile('Payment To The Liquor Board')"
                           :docTitle="'Payment To The Liquor Board'"
                            :docType="'Payment To The Liquor Board'"
                        />

                        <MergeDocumentComponent
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :success="success"
                            :error="error"
                            :errors="errors"
                            :column=6
                            :model_id="licence"
                            :docTitle="'Application Forms'"
                            :docType="'Application Forms'"
                            :docModel="licence"
                            :stage=500
                            :mergeNum=3
                            :hasFile="hasFile('Application Forms')"
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
                            :stage=500
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
                            :docTitle="'CIPC Documents'"
                            :docType="'CIPC Documents'"
                            :docModel="licence"
                            :stage=500
                            :mergeNum="5"
                            :hasFile="hasFile('CIPC Documents')"
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
                            :stage=500
                            :mergeNum="6"
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
                            :docTitle="'Police Clearance'"
                            :docType="'Police Clearance'"
                            :docModel="licence"
                            :stage=500
                            :hasFile="hasFile('Police Clearance')"
                            :mergeNum="7"
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
                            :stage=500
                            :mergeNum="8"
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
                            :docTitle="'LTA Certificate'"
                            :docType="'LTA Certificate'"
                            :docModel="licence"
                            :stage=500
                            :mergeNum="9"
                            :hasFile="hasFile('LTA Certificate')"
                        />

                        <MergeDocumentComponent
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :success="success"
                            :error="error"
                            :errors="errors"
                            :column=6
                            :model_id="licence"
                            :docTitle="'Shareholding Info'"
                            :docType="'Shareholding Info'"
                            :docModel="licence"
                            :stage=500
                            :mergeNum="10"
                            :hasFile="hasFile('Shareholding Info')"
                        />

                        <MergeDocumentComponent
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :success="success"
                            :error="error"
                            :errors="errors"
                            :column=6
                            :model_id="licence"
                            :docTitle="'Financial Interests'"
                            :docType="'Financial Interests'"
                            :docModel="licence"
                            :stage=500
                            :mergeNum="11"
                            :hasFile="hasFile('Financial Interests')"
                        />

                        <MergeDocumentComponent
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :success="success"
                            :error="error"
                            :errors="errors"
                            :column=6
                            :model_id="licence"
                            :docTitle="'500m Affidavit'"
                            :docType="'500m Affidavit'"
                            :docModel="licence"
                            :stage="500"
                            :mergeNum="12"
                            :hasFile="hasFile('500m Affidavit')"
                        />


                        <MergeDocumentComponent
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :success="success"
                            :error="error"
                            :errors="errors"
                            :column=6
                            :model_id="licence"
                            :docTitle="'Government Gazette Adverts'"
                            :docType="'Government Gazette Adverts'"
                            :docModel="licence"
                            :stage="500"
                            :mergeNum="13"
                            :hasFile="hasFile('Government Gazette Adverts')"
                        />

                        <MergeDocumentComponent
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :success="success"
                            :error="error"
                            :errors="errors"
                            :column=6
                            :docTitle="'Advert Affidavit'"
                            :docType="'Advert Affidavit'"
                            :docModel="licence"
                            :stage=500
                            :mergeNum="14"
                            :hasFile="hasFile('Advert Affidavit')"
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
                            :docTitle="'Proof of Occupation'"
                            :docType="'Proof of Occupation'"
                            :docModel="licence"
                            :stage="500"
                            :mergeNum="15"
                            :hasFile="hasFile('Proof of Occupation')"
                        />

                        <MergeDocumentComponent
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :success="success"
                            :error="error"
                            :errors="errors"
                            :column=6
                            :model_id="licence"
                            :docTitle="'Representations'"
                            :docType="'Representations'"
                            :docModel="licence"
                            :stage="500"
                            :mergeNum="16"
                            :hasFile="hasFile('Representations')"
                        />



                        <MergeDocumentComponent
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :success="success"
                            :error="error"
                            :errors="errors"
                            :column=6
                            :model_id="licence"
                            :docTitle="'Menu-If applicable'"
                            :docType="'Menu'"
                            :docModel="licence"
                            :stage="500"
                            :mergeNum="17"
                            :hasFile="hasFile('Menu')"
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
                            :stage=500
                            :mergeNum="17"
                            :hasFile="hasFile('Photographs')"
                        />

                        <MergeDocumentComponent
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :success="success"
                            :error="error"
                            :errors="errors"
                            v-if="licence.province === 'Mpumalanga'"
                            :column=6
                            :model_id="licence"
                            :docTitle="'Municipal Consent Ltr'"
                            :docType="'Municipal Consent Ltr'"
                            :docModel="licence"
                            :stage=500
                            :mergeNum="19"
                            :hasFile="hasFile('Municipal Consent Ltr')"
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
                            :stage=500
                            :mergeNum="20"
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
                            :docTitle="'Local Authority Letter'"
                            :docType="'Local Authority Letter'"
                            :docModel="licence"
                            :stage=500
                            :mergeNum="21"
                            :hasFile="hasFile('Local Authority Letter')"
                        />


                        <MergeDocumentComponent
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :success="success"
                            :error="error"
                            :errors="errors"
                            :column=6
                            :model_id="licence"
                            :docTitle="'Mapbook Plans'"
                            :docType="'Mapbook Plans'"
                            :docModel="licence"
                            :stage=500
                            :mergeNum="22"
                            :hasFile="hasFile('Mapbook Plans')"
                        />

                        <MergeDocumentComponent
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :success="success"
                            :error="error"
                            :errors="errors"
                            :column="6"
                            :model_id="licence"
                            :docTitle="'Google Map Plans'"
                            :docType="'Google Map Plans'"
                            :docModel="licence"
                            :stage="500"
                            :mergeNum="23"
                            :hasFile="hasFile('Google Map Plans')"
                        />

                        <MergeDocumentComponent
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :success="success"
                            :error="error"
                            :errors="errors"
                            :column=6
                            :model_id="licence"
                            :docTitle="'Description'"
                            :docType="'Description'"
                            :docModel="licence"
                            :stage="500"
                            :mergeNum="24"
                            :hasFile="hasFile('Description')"
                        />

                        <MergeDocumentComponent
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :success="success"
                            :error="error"
                            :errors="errors"
                            :column=6
                            :model_id="licence"
                            :docTitle="'Site Plans'"
                            :docType="'Site Plans'"
                            :docModel="licence"
                            :stage="500"
                            :mergeNum="25"
                            :hasFile="hasFile('Site Plans')"
                        />

                        <MergeDocumentComponent
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :success="success"
                            :error="error"
                            :errors="errors"
                            :column=6
                            :model_id="licence"
                            :docTitle="'Fully Dimensioned Plans'"
                            :docType="'Full Dimensioned Plans'"
                            :docModel="licence"
                            :stage="500"
                            :mergeNum="26"
                            :hasFile="hasFile('Full Dimensioned Plans')"
                        />

                        <MergeDocumentComponent
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :success="success"
                            :error="error"
                            :errors="errors"
                            :column=6
                            :model_id="licence"
                            :docTitle="'Advert Photographs'"
                            :docType="'Advert Photographs'"
                            :docModel="licence"
                            :stage="500"
                            :mergeNum="27"
                            :hasFile="hasFile('Advert Photographs')"
                        />

                        <MergeDocumentComponent
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :success="success"
                            :error="error"
                            :errors="errors"
                            :column=6
                            :model_id="licence"
                            :docTitle="'Newspaper Adverts'"
                            :docType="'Newspaper Adverts'"
                            :docModel="licence"
                            :stage=500
                            :mergeNum="28"
                            :hasFile="hasFile('Newspaper Adverts')"
                        />

                        <br><br>

                        <div class="d-flex ">
                          <MergeButtonComponent
                              :title="'Compile Application'"
                              :licence="licence"
                          />
                          <!-- remember to bind disbaled this btn when all required files are not uploaded -->
                        </div>

                      </div>
                      <hr>

                      <StageComponent
                          :column=12
                          :dbStatus="licence.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=600
                          prevStage=500
                          licence_id="licence.slug"
                          :stageTitle="'Scanned Application'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />

                      <div class="col-md-6">
                        <DocComponent
                            :documentModel="licence"
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :hasFile="hasFile('Scanned Application')"
                            :errors="errors"
                            :error="error"
                            :orderByNumber=600
                            :docType="'Scanned Application'"
                            :success="success"
                        />

                      </div>
                      <DateComponent
                          :licence="licence"
                          :stage="'Scanned Application'"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          @date-value-changed="updateStageDate"
                          :dated_at="getLicenceDate(licence.id, 'Scanned Application')"
                          :success="success"
                      />



                      <!-- When its Mpumalanga province ============================================== -->
                      <template v-if="licence.province == 'Mpumalanga'">
                        <StageComponent
                            v-if="licence.province == 'Mpumalanga'"
                            :dbStatus="licence.status"
                            :errors="errors"
                            :error="error"
                            :stageValue=700
                            prevStage=600
                            :licence_id="licence.slug"
                            :stageTitle="'Lodged with Municipality'"
                            :success="success"
                            @stage-value-changed="pushData"
                        />

                        <div class="col-md-6">
                          <DocComponent
                              :documentModel="licence"
                              @file-value-changed="submitDocument"
                              @file-deleted="deleteDocument"
                              :hasFile="hasFile('Lodged with Municipality')"
                              :errors="errors"
                              :error="error"
                              :orderByNumber=700
                              :docType="'Lodged with Municipality'"
                              :success="success"
                          />
                        </div>
                        <DateComponent
                            :licence="licence"
                            :stage="'Lodged with Municipality'"
                            :canSave="$page.props.auth.has_slowtow_admin_role"
                            :errors="errors"
                            :error="error"
                            :column=5
                            @date-value-changed="updateStageDate"
                            :dated_at="getLicenceDate(licence.id, 'Lodged with Municipality')"
                            :success="success"
                        />
                        <hr/>

                        <!-- When its Mpumalanga province ============================================== -->
                        <StageComponent
                            :dbStatus="licence.status"
                            :errors="errors"
                            :error="error"
                            :stageValue=800
                            prevStage=700
                            :licence_id="licence.slug"
                            :stageTitle="'Municipal Comments'"
                            :success="success"
                            @stage-value-changed="pushData"
                        />

                        <div class="col-md-6">
                          <DocComponent
                              :documentModel="licence"
                              @file-value-changed="submitDocument"
                              @file-deleted="deleteDocument"
                              :hasFile="hasFile('Municipal Comments')"
                              :errors="errors"
                              :error="error"
                              :orderByNumber=800
                              :docType="'Municipal Comments'"
                              :success="success"
                          />
                        </div>

                        <DateComponent
                            :licence="licence"
                            :stage="'Municipal Comments'"
                            :canSave="$page.props.auth.has_slowtow_admin_role"
                            :errors="errors"
                            :error="error"
                            :column=5
                            @date-value-changed="updateStageDate"
                            :dated_at="getLicenceDate(licence.id, 'Municipal Comments')"
                            :success="success"
                        />
                        <hr/>
                        <!-- //Mpumalanga================= -->
                        <StageComponent
                            :dbStatus="licence.status"
                            :errors="errors"
                            :error="error"
                            :stageValue=900
                            prevStage=800
                            :licence_id="licence.slug"
                            :stageTitle="'Completed Application Scanned'"
                            :success="success"
                            @stage-value-changed="pushData"
                        />

                        <div class="col-md-6">
                          <DocComponent
                              :documentModel="licence"
                              @file-value-changed="submitDocument"
                              @file-deleted="deleteDocument"
                              :hasFile="hasFile('Completed Application Scanned')"
                              :errors="errors"
                              :error="error"
                              :orderByNumber=900
                              :docType="'Completed Application Scanned'"
                              :success="success"
                          />
                        </div>
                        <hr>

                        <StageComponent
                            :dbStatus="licence.status"
                            :errors="errors"
                            :error="error"
                            :stageValue=1000
                            prevStage=900
                            :licence_id="licence.slug"
                            :stageTitle="'Lodged with MER'"
                            :success="success"
                            @stage-value-changed="pushData"
                        />

                        <div class="col-md-6" >
                          <DocComponent
                              :documentModel="licence"
                              @file-value-changed="submitDocument"
                              @file-deleted="deleteDocument"
                              :hasFile="hasFile('Lodged with MER')"
                              :errors="errors"
                              :error="error"
                              :orderByNumber=1000
                              :docType="'Lodged with MER'"
                              :success="success"
                          />
                        </div>
                        <DateComponent
                            :licence="licence"
                            :stage="'Lodged with MER'"
                            :canSave="$page.props.auth.has_slowtow_admin_role"
                            :errors="errors"
                            :error="error"
                            :column=5
                            @date-value-changed="updateStageDate"
                            :dated_at="getLicenceDate(licence.id, 'Lodged with MER')"
                            :success="success"
                        />
                        <hr>
                      </template>






                      <template v-if="licence.province == 'Limpopo' || licence.province == 'North West'">
                        <!-- Limpopo and Northwest ====================== -->
                        <StageComponent
                            :dbStatus="licence.status"
                            :errors="errors"
                            :error="error"
                            :stageValue=1100
                            prevStage=1000
                            :licence_id="licence.slug"
                            :stageTitle="'Lodged with Magistrate'"
                            :success="success"
                            @stage-value-changed="pushData"
                        />

                        <div class="col-md-6">
                          <DocComponent
                              :documentModel="licence"
                              @file-value-changed="submitDocument"
                              @file-deleted="deleteDocument"
                              :hasFile="hasFile('Lodged with Magistrate')"
                              :errors="errors"
                              :error="error"
                              :orderByNumber=1100
                              :docType="'Lodged with Magistrate'"
                              :success="success"
                          />
                        </div>
                        <DateComponent
                            :licence="licence"
                            :stage="'Lodged with Magistrate'"
                            :canSave="$page.props.auth.has_slowtow_admin_role"
                            :errors="errors"
                            :error="error"
                            :column=5
                            @date-value-changed="updateStageDate"
                            :dated_at="getLicenceDate(licence.id, 'Lodged with Magistrate')"
                            :success="success"
                        />
                        <hr>

                        <!-- Limpopo and Northwest ====================== -->
                        <StageComponent
                            :dbStatus="licence.status"
                            :errors="errors"
                            :error="error"
                            :stageValue=1200
                            prevStage=1100
                            :licence_id="licence.slug"
                            :stageTitle="'Lodged with DPO'"
                            :success="success"
                            @stage-value-changed="pushData"
                        />

                        <div class="col-md-6">
                          <DocComponent
                              :documentModel="licence"
                              @file-value-changed="submitDocument"
                              @file-deleted="deleteDocument"
                              :hasFile="hasFile('Lodged with DPO')"
                              :errors="errors"
                              :error="error"
                              :orderByNumber=1200
                              :docType="'Lodged with DPO'"
                              :success="success"
                          />
                        </div>
                        <DateComponent
                            :licence="licence"
                            :stage="'Lodged with DPO'"
                            :canSave="$page.props.auth.has_slowtow_admin_role"
                            :errors="errors"
                            :error="error"
                            :column=5
                            @date-value-changed="updateStageDate"
                            :dated_at="getLicenceDate(licence.id, 'Lodged with DPO')"
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
                            :stageTitle="'Police Report'"
                            :success="success"
                            @stage-value-changed="pushData"
                        />

                        <div class="col-md-6" >
                          <DocComponent
                              :documentModel="licence"
                              @file-value-changed="submitDocument"
                              @file-deleted="deleteDocument"
                              :hasFile="hasFile('Police Report')"
                              :errors="errors"
                              :error="error"
                              :orderByNumber=1300
                              :docType="'Police Report'"
                              :success="success"
                          />
                        </div>
                        <DateComponent
                            :licence="licence"
                            :stage="'Police Report'"
                            :canSave="$page.props.auth.has_slowtow_admin_role"
                            :errors="errors"
                            :error="error"
                            :column=5
                            @date-value-changed="updateStageDate"
                            :dated_at="getLicenceDate(licence.id, 'Police Report')"
                            :success="success"
                        />
                        <hr>
                      </template>



                      <!-- If its Mpumalanga , renamed this stage-->
                      <template v-if="licence.province == 'Mpumalanga' || licence.province == 'North West' || licence.province == 'Limpopo'">

                        <StageComponent
                            :dbStatus="licence.status"
                            :errors="errors"
                            :error="error"
                            :stageValue=1400
                            prevStage=1300
                            :licence_id="licence.slug"
                            :stageTitle="'Lodged With Liquor Board'"
                            :success="success"
                            @stage-value-changed="pushData"
                        />
                        <div class="col-md-6" >
                          <DocComponent
                              :documentModel="licence"
                              @file-value-changed="submitDocument"
                              @file-deleted="deleteDocument"
                              :hasFile="hasFile('Lodged With Liquor Board')"
                              :errors="errors"
                              :error="error"
                              :orderByNumber=1400
                              :docType="'Lodged With Liquor Board'"
                              :success="success"
                          />
                        </div>



                        <DateComponent
                            :licence="licence"
                            :stage="'Lodged With Liqour Board'"
                            :canSave="$page.props.auth.has_slowtow_admin_role"
                            :errors="errors"
                            :error="error"
                            :column=5
                            @date-value-changed="updateStageDate"
                            :dated_at="getLicenceDate(licence.id, 'Lodged With Liquor Board')"
                            :success="success"
                        />


                        <hr/>

                      </template>

                      <!-- If its other provinces keep this stage-->
                      <template v-if="licence.province !== 'Mpumalanga' || licence.province !== 'North West' || licence.province !== 'Limpopo'">
                        <StageComponent
                            :dbStatus="licence.status"
                            :errors="errors"
                            :error="error"
                            :stageValue=1500
                            prevStage=1400
                            :licence_id="licence.slug"
                            :stageTitle="'Application Lodged (Proof of Lodgement)'"
                            :success="success"
                            @stage-value-changed="pushData"
                        />

                        <div class="col-md-6">
                          <DocComponent
                              :documentModel="licence"
                              @file-value-changed="submitDocument"
                              @file-deleted="deleteDocument"
                              :hasFile="hasFile('Application Lodged')"
                              :errors="errors"
                              :error="error"
                              :orderByNumber=1500
                              :docType="'Application Lodged'"
                              :success="success"
                          />
                        </div>
                        <DateComponent
                            :licence="licence"
                            :stage="'Application Lodged (Proof of Lodgement)'"
                            :canSave="$page.props.auth.has_slowtow_admin_role"
                            :errors="errors"
                            :error="error"
                            :column=5
                            @date-value-changed="updateStageDate"
                            :dated_at="getLicenceDate(licence.id, 'Application Lodged (Proof of Lodgement)')"
                            :success="success"
                        />
                      </template>
                      <hr/>

                      <!-- this stage must appear once licence lodged is ticked -->
                      <template>

                        <StageComponent
                            :dbStatus="licence.status"
                            :errors="errors"
                            :error="error"
                            :stageValue=1600
                            prevStage=1500
                            :licence_id="licence.slug"
                            :stageTitle="'Additional Documents/Information'"
                            :success="success"
                            @stage-value-changed="pushData"
                        />


                        <AdditionalDocsComponent
                            :licence_id="licence.id"
                            :additional_docs="licence.additional_docs"
                            :success="success"
                            :errors="errors"
                            :error="error"/>
                        <hr/>
                      </template>


                      <StageComponent
                          :dbStatus="licence.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=1700
                          prevStage=1600
                          :licence_id="licence.slug"
                          :stageTitle="'Initial Inspection'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />

                      <div class="col-md-6">
                        <DocComponent
                            :documentModel="licence"
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :hasFile="hasFile('Initial Inspection')"
                            :errors="errors"
                            :error="error"
                            :orderByNumber=1700
                            :docType="'Initial Inspection'"
                            :success="success"
                        />
                      </div>


                      <DateComponent
                          :licence="licence"
                          :stage="'Initial Inspection'"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          @date-value-changed="updateStageDate"
                          :dated_at="getLicenceDate(licence.id, 'Initial Inspection')"
                          :success="success"
                      />

                      <hr/>



                      <StageComponent
                          :dbStatus="licence.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=1800
                          prevStage=1700
                          :licence_id="licence.slug"
                          :stageTitle="'Final Inspection'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />

                      <div class="col-md-6">
                        <DocComponent
                            :documentModel="licence"
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :hasFile="hasFile('Final Inspection')"
                            :errors="errors"
                            :error="error"
                            :orderByNumber=1800
                            :docType="'Final Inspection'"
                            :success="success"
                        />
                      </div>

                      <DateComponent
                          :licence="licence"
                          :stage="'Final Inspection'"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          @date-value-changed="updateStageDate"
                          :dated_at="getLicenceDate(licence.id, 'Final Inspection')"
                          :success="success"
                      />




                      <hr/>


                      <StageComponent
                          :column="5"
                          :dbStatus="licence.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=1900
                          prevStage=1800
                          :licence_id="licence.slug"
                          :stageTitle="'Activation Fee Requested'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />

                      <div class="col-md-1 columns"></div>
                      <DateComponent
                          :licence="licence"
                          :stage="'Activation Fee Requested'"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          @date-value-changed="updateStageDate"
                          :dated_at="getLicenceDate(licence.id, 'Activation Fee Requested')"
                          :success="success"
                      />


                      <hr/>


                      <StageComponent
                          :dbStatus="licence.status"
                          :errors="errors"
                          :error="error"
                          :column=12
                          :stageValue=2000
                          prevStage=1900
                          :licence_id="licence.slug"
                          :stageTitle="'Client Finalisation Invoice'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />

                      <DocComponent
                          :documentModel="licence"
                          @file-value-changed="submitDocument"
                          @file-deleted="deleteDocument"
                          :hasFile="hasFile('Client Finalisation Invoiced')"
                          :errors="errors"
                          :error="error"
                          :orderByNumber=2000
                          :docType="'Client Finalisation Invoiced'"
                          :success="success"
                      />

                      <hr/>

                      <!-- this was previouly client paid -->
                      <StageComponent
                          :dbStatus="licence.status"
                          :errors="errors"
                          :error="error"
                          :column=6
                          :stageValue=2100
                          prevStage=2000
                          :licence_id="licence.slug"
                          :stageTitle="'Finalisation Paid'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />


                      <DateComponent
                          :licence="licence"
                          :stage="'Finalisation Paid'"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          @date-value-changed="updateStageDate"
                          :dated_at="getLicenceDate(licence.id, 'Finalisation Paid')"
                          :success="success"
                      />

                      <hr/>


                      <StageComponent
                          :dbStatus="licence.status"
                          :errors="errors"
                          :error="error"
                          :column=6
                          :stageValue=2200
                          prevStage=2100
                          :licence_id="licence.slug"
                          :stageTitle="'Activation Fee Paid'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />




                      <DateComponent
                          :licence="licence"
                          :stage="'Activation Fee Paid'"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          @date-value-changed="updateStageDate"
                          :dated_at="getLicenceDate(licence.id, 'Activation Fee Paid')"
                          :success="success"
                      />



                      <DocComponent
                          :documentModel="licence"
                          @file-value-changed="submitDocument"
                          @file-deleted="deleteDocument"
                          :hasFile="hasFile('Activation Fee Paid')"
                          :errors="errors"
                          :error="error"
                          :orderByNumber=2200
                          :docType="'Activation Fee Paid'"
                          :success="success"
                      />
                      <hr/>


                      <StageComponent
                          :dbStatus="licence.status"
                          :errors="errors"
                          :error="error"
                          :column=6
                          :stageValue=2300
                          prevStage=2200
                          :licence_id="licence.slug"
                          :stageTitle="'Licence Issued'"
                          :success="success"
                          :canActivate="getLicenceDate(licence.id, 'Licence Issued')"
                          @stage-value-changed="pushData"
                      />



                      <DateComponent
                          :licence="licence"
                          :stage="'Licence Issued'"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          @date-value-changed="updateStageDate"
                          :dated_at="getLicenceDate(licence.id, 'Licence Issued')"
                          :success="success"
                      />



                      <DocComponent
                          :documentModel="licence"
                          @file-value-changed="submitDocument"
                          @file-deleted="deleteDocument"
                          :hasFile="hasFile('Licence Issued')"
                          :errors="errors"
                          :error="error"
                          :orderByNumber=2300
                          :docType="'Licence Issued'"
                          :success="success"
                      />
                      <hr/>

                      <StageComponent
                          :dbStatus="licence.status"
                          :errors="errors"
                          :error="error"
                          :column=6
                          :stageValue=2400
                          prevStage=2300
                          :licence_id="licence.slug"
                          :stageTitle="'Licence Delivered'"
                          :success="success"
                          :canActivate="getLicenceDate(licence.id, 'Licence Delivered')"
                          @stage-value-changed="pushData"
                      />

                      <DateComponent
                          :licence="licence"
                          :stage="'Licence Delivered'"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          @date-value-changed="updateStageDate"
                          :dated_at="getLicenceDate(licence.id, 'Licence Delivered')"
                          :success="success"
                      />

                      <DocComponent
                          :documentModel="licence"
                          @file-value-changed="submitDocument"
                          @file-deleted="deleteDocument"
                          :hasFile="hasFile('Licence Delivered')"
                          :errors="errors"
                          :error="error"
                          :orderByNumber=2400
                          :docType="'Licence Delivered'"
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
            v-if="licence.status >= 2300"
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

<script src="./registration.js"></script>

