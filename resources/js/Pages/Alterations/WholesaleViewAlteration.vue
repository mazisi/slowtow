
<script src="./wholesale_view_alteration.js"></script>
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
            <h6>Wholesale Additional Depot/Relocation Info for:
              <Link @click="redirect(alteration.licence)" href="#!">
                <span class="text-success">{{ alteration.licence.trading_name }}</span></Link></h6>
            <p class="text-sm mb-0">Current Stage:
              <span v-html="getStatus(alteration.status)"></span>
            </p>
          </div>
          <div class="col-lg-3 col-3 my-auto text-end">
            <div class="dropdown float-lg-end pe-4">
        
                <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-ellipsis-v text-secondary" aria-hidden="true"></i>
                </a>
                <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
                    <li class="text-center"><button @click="abandon(alteration.slug)" type="button" class="btn btn-sm btn-warning"> Abandon</button></li>
                    <li class="text-center"><button v-if="$page.props.auth.has_slowtow_admin_role"
                      @click="deleteAlteration(alteration.slug)"
                      type="button" class="btn btn-sm btn-danger">
                Delete</button></li>
                </ul>
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

                      <StageComponent
                          :column=12
                          :dbStatus="alteration.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=100
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
                             stage="100"
                             prevStage="0"
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
                            stage="200"
                            prevStage="100"
                            :docType="'Client Invoiced'"
                            :success="success"
                        />
                      </div>

                      <hr/>
                      <div class="col-6 columns">
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
                          :stageTitle="'Prepare NLA 14 Application'"
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
                              :docTitle="'NLA 14 Form'"
                              :docType="'NLA 14 Form'"
                              :docModel="alteration"
                              :stage=400
                              :mergeNum="1"
                              :hasFile="hasFile('NLA 14 Form')"
                          />

                          <!-- <MergeDocumentComponent
                              @file-value-changed="submitDocument"
                              @file-deleted="deleteDocument"
                              :success="success"
                              :error="error"
                              :errors="errors"
                              :column=6
                              :docTitle="'Proof of Payment'"
                              :docType="'Proof of Payment'"
                              :docModel="alteration"
                              :stage=400
                              :mergeNum="2"
                              :hasFile="hasFile('Proof of Payment')"
                          /> -->
                     

  <LinkComponent
                             :hasFile="hasFile('Payment to the National Liquor Authority')"
                             :docTitle="'Payment to the National Liquor Authority'"
                              :docType="'Payment to the National Liquor Authority'"
                          />


                          <MergeDocumentComponent
                              @file-value-changed="submitDocument"
                              @file-deleted="deleteDocument"
                              :success="success"
                              :error="error"
                              :errors="errors"
                              :column=6
                              :docTitle="'Annual Turnover Sheet'"
                              :docType="'Annual Turnover Sheet'"
                              :docModel="alteration"
                              :stage=400
                              :mergeNum="5"
                              :hasFile="hasFile('Annual Turnover Sheet')"
                          />

                          <MergeDocumentComponent
                              @file-value-changed="submitDocument"
                              @file-deleted="deleteDocument"
                              :success="success"
                              :error="error"
                              :errors="errors"
                              :column=6
                              :docTitle="'ID Documents'"
                              :docType="'ID Documents'"
                              :docModel="alteration"
                              :stage=400
                              :mergeNum="1"
                              :hasFile="hasFile('ID Documents')"
                          />

                          <MergeDocumentComponent
                              @file-value-changed="submitDocument"
                              @file-deleted="deleteDocument"
                              :success="success"
                              :error="error"
                              :errors="errors"
                              :column=6
                              :docTitle="'Police Clearance Certicates'"
                              :docType="'Police Clearance Certicates'"
                              :docModel="alteration"
                              :stage=400
                              :mergeNum="2"
                              :hasFile="hasFile('Police Clearance Certicates')"
                          />


                          <MergeDocumentComponent
                              @file-value-changed="submitDocument"
                              @file-deleted="deleteDocument"
                              :success="success"
                              :error="error"
                              :errors="errors"
                              :column=6
                              :docTitle="'Contribution Certificate'"
                              :docType="'Contribution Certificate'"
                              :docModel="alteration"
                              :stage=400
                              :mergeNum="5"
                              :hasFile="hasFile('Contribution Certificate')"
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
                              :docTitle="'Tax Clearance Certificates'"
                              :docType="'Tax Clearance Certificates'"
                              :docModel="alteration"
                              :stage=400
                              :mergeNum="3"
                              :hasFile="hasFile('Tax Clearance Certificates')"
                          />

                          <MergeDocumentComponent
                              @file-value-changed="submitDocument"
                              @file-deleted="deleteDocument"
                              :success="success"
                              :error="error"
                              :errors="errors"
                              :column=6
                              :docTitle="'BEE Certificate'"
                              :docType="'BEE Certificate'"
                              :docModel="alteration"
                              :stage=400
                              :mergeNum="4"
                              :hasFile="hasFile('BEE Certificate')"
                          />
                          <MergeDocumentComponent
                              @file-value-changed="submitDocument"
                              @file-deleted="deleteDocument"
                              :success="success"
                              :error="error"
                              :errors="errors"
                              :column=6
                              :docTitle="'CIPC Certificate'"
                              :docType="'CIPC Certificate'"
                              :docModel="alteration"
                              :stage=400
                              :mergeNum="3"
                              :hasFile="hasFile('CIPC Certificate')"
                          />

                          <MergeDocumentComponent
                              @file-value-changed="submitDocument"
                              @file-deleted="deleteDocument"
                              :success="success"
                              :error="error"
                              :errors="errors"
                              :column=6
                              :docTitle="'Zoning and LAA Certificate'"
                              :docType="'Zoning and LAA Certificate'"
                              :docModel="alteration"
                              :stage=400
                              :mergeNum="4"
                              :hasFile="hasFile('Zoning and LAA Certificate')"
                          />
                          <MergeDocumentComponent
                              @file-value-changed="submitDocument"
                              @file-deleted="deleteDocument"
                              :success="success"
                              :error="error"
                              :errors="errors"
                              :column=6
                              :docTitle="'Latest NLA 33 Certificate'"
                              :docType="'Latest NLA 33 Certificate'"
                              :docModel="alteration"
                              :stage=400
                              :mergeNum="4"
                              :hasFile="hasFile('Latest NLA 33 Certificate')"
                          />

                          <a v-if="alteration.merged_document"
                             :href="`/storage/app/public/${alteration.merged_document}`" target="_blank"  class="btn btn-sm btn-success float-end mx-2" >View</a>

                          <button
                              :disabled="hasAllMergeDocs() == 0"
                              type="button"
                              @click="mergeDocuments"
                              class="btn btn-sm btn-secondary float-end">
                            Compile & Merge
                          </button>

                        </div>
                      </div>
                      <hr>

                      <div class="col-6 columns">
                        <StageComponent
                            :column=5
                            :dbStatus="alteration.status"
                            :errors="errors"
                            :error="error"
                            :stageValue=500
                            :prevStage=400
                            :licence_id="alteration.slug"
                            :stageTitle="'Payment to the National Liquor Authority'"
                            :success="success"
                            @stage-value-changed="pushData"
                        />


                        <DocComponent
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :documentModel="alteration"
                            :hasFile="hasFile('Payment to the National Liquor Authority')"
                            :errors="errors"
                            :error="error"
                            :orderByNumber=500
                            stage="500"
                            prevStage="400"
                            :docType="'Payment to the National Liquor Authority'"
                            :success="success"
                        />
                      </div>


                      <DateComponent
                          :licence="alteration"
                          :stage="'Payment to the National Liquor Authority'"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          @date-value-changed="updateAlterationDate"
                          :dated_at="getAlterationDate(alteration.id, 'Payment to the National Liquor Authority')"
                          :success="success"
                      />
                      <hr>



                      <div class="col-6 columns">
                        <StageComponent
                            :column=5
                            :dbStatus="alteration.status"
                            :errors="errors"
                            :error="error"
                            :stageValue=600
                            :prevStage=500
                            :licence_id="alteration.slug"
                            :stageTitle="'NLA 14 Application Lodged'"
                            :success="success"
                            @stage-value-changed="pushData"
                        />


                        <DocComponent
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :documentModel="alteration"
                            :hasFile="hasFile('NLA 14 Application Lodged')"
                            :errors="errors"
                            :error="error"
                            :orderByNumber=600
                            stage="600"
                            prevStage="500"
                            :docType="'NLA 14 Application Lodged'"
                            :success="success"
                        />
                      </div>


                      <DateComponent
                          :licence="alteration"
                          :stage="'NLA 14 Application Lodged'"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          @date-value-changed="updateAlterationDate"
                          :dated_at="getAlterationDate(alteration.id, 'NLA 14 Application Lodged')"
                          :success="success"
                      />
                      <hr>

                      <StageComponent
                          :column=5
                          :dbStatus="alteration.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=700
                          :prevStage=600
                          :licence_id="alteration.slug"
                          :stageTitle="'Additional Documents Requested'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />

                      
                      <AdditionalDocsComponent
                            :licence_id="alteration.id"
                            modelable_type="Alteration"
                            :additional_docs="alteration.additional_docs"
                            :success="success"
                            :errors="errors"
                            :error="error"
                            />
                      <hr>
                      <div class="col-6 columns">
                        <StageComponent
                            :column=5
                            :dbStatus="alteration.status"
                            :errors="errors"
                            :error="error"
                            :stageValue=800
                            :prevStage=700
                            :licence_id="alteration.slug"
                            :stageTitle="'NLA 15 Certificate Issued'"
                            :success="success"
                            @stage-value-changed="pushData"
                        />


                        <DocComponent
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :documentModel="alteration"
                            :hasFile="hasFile('NLA 15 Certificate Issued')"
                            :errors="errors"
                            :error="error"
                            :orderByNumber=800
                            stage="800"
                            prevStage="700"
                            :docType="'NLA 15 Certificate Issued'"
                            :success="success"
                        />
                      </div>


                      <DateComponent
                          :licence="alteration"
                          :stage="'NLA 15 Certificate Issued'"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          @date-value-changed="updateAlterationDate"
                          :dated_at="getAlterationDate(alteration.id, 'NLA 15 Certificate Issued')"
                          :success="success"
                      />
                      <hr>

                      <div class="col-6 columns">
                        <StageComponent
                            :column=5
                            :dbStatus="alteration.status"
                            :errors="errors"
                            :error="error"
                            :stageValue=900
                            :prevStage=800
                            :licence_id="alteration.slug"
                            :stageTitle="'NLA 15 Certificate Delivered'"
                            :success="success"
                            @stage-value-changed="pushData"
                        />


                        <DocComponent
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :documentModel="alteration"
                            :hasFile="hasFile('NLA 15 Certificate Delivered')"
                            :errors="errors"
                            :error="error"
                            :orderByNumber=900
                            stage="900"
                            prevStage="800"
                            :docType="'NLA 15 Certificate Delivered'"
                            :success="success"
                        />
                      </div>


                      <DateComponent
                          :licence="alteration"
                          :stage="'NLA 15 Certificate Delivered'"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          @date-value-changed="updateAlterationDate"
                          :dated_at="getAlterationDate(alteration.id, 'NLA 15 Certificate Delivered')"
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
