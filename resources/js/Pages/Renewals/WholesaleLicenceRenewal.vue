<template>
  <Layout>
    <Head title="View Renewal" />
    <div class="container-fluid">
      <Banner/>
      <div class="card card-body mx-3 mx-md-4 mt-n6">
        <div class="row">
          <div class="col-lg-9 col-9">
            <h6>Whole sale Process Renewal for: {{ renewal.date  }}/{{ getRenewalYear(renewal.date)  }}
              <Link @click="redirect(renewal.licence)" class="text-success">: {{ renewal.licence.trading_name }}</Link></h6>
            <p class="text-sm mb-0">Current Stage:

              <span class="font-weight-bold ms-1">{{ getStatus(renewal.status)}}</span>
            </p>

          </div>
          <div class="col-lg-3 col-3 my-auto text-end">
            <button v-if="$page.props.auth.has_slowtow_admin_role" @click="deleteRenewal" type="button" class="btn btn-sm btn-danger float-lg-end pe-4"> Delete</button>
          </div>
        </div>

        <div class="row">
          <div class="mt-3 row">
            <div class="col-12 col-md-12 col-xl-12 position-relative">
              <div class="card card-plain h-100">
                <div class="p-3 card-body">
                  <!--  start injecting components-->
                  <form @submit.prevent="updateRenewal">

                    <div class="row">

                      <StageComponent
                          :column=6
                          :dbStatus="renewal.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=100
                          :prevStage=null
                          :licence_id=null
                          :stageTitle="'Renewal Notice Received'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />
                  

                      <DateComponent
                          :stage="'Renewal Notice Received'"
                          :model="renewal"
                          :licence="renewal_notice_received_at"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          :dated_at="getRenewalDate('Renewal Notice Received').dated_at"
                          @date-value-changed="updateDate"
                          :success="success"
                      />

                      <hr>


                      <StageComponent
                          :column=6
                          :dbStatus="renewal.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=200
                          :prevStage=100
                          :licence_id=null
                          :stageTitle="'Turnover Information Requested'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />

                      <DateComponent
                          :stage="'Turnover Information Requested'"
                          :model="renewal"
                          :licence="turnover_information_requested_at"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          :dated_at="getRenewalDate('Turnover Information Requested').dated_at"
                          @date-value-changed="updateDate"
                          :success="success"
                      />

                      <hr>

                      <StageComponent
                          :column=6
                          :dbStatus="renewal.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=300
                          :prevStage=200
                          :licence_id=null
                          :stageTitle="'Turnover Information Received'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />

                      <DateComponent
                          :stage="'Turnover Information Received'"
                          :model="renewal"
                          :licence="turnover_information_received_at"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          :dated_at="getRenewalDate('Turnover Information Received').dated_at"
                          @date-value-changed="updateDate"
                          :success="success"
                      />

                      <DocComponent
                          :documentModel="renewal"
                          @file-value-changed="submitDocument"
                          @file-deleted="deleteDocument"
                          :hasFile="hasFile('Turnover Information Received')"
                          :errors="errors"
                          :error="error"
                          :docType="'Turnover Information Received'"
                          :success="success"
                          :stage="300"
                      />
                      <hr/>
                      <TurnOverInformationComponent 
                      :renewal="renewal"
                      :error="error"
                      :success="success"
                      />

                      <hr>

                      <StageComponent
                          :column=6
                          :dbStatus="renewal.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=400
                          :prevStage=300
                          :licence_id=null
                          :stageTitle="'Annual Return Submitted'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />

                      <DateComponent
                          :stage="'Annual Return Submitted'"
                          :model="renewal"
                          :licence="annual_return_submited_at"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          :dated_at="getRenewalDate('Annual Return Submitted').dated_at"
                          @date-value-changed="updateDate"
                          :success="success"

                      />

                      <hr>

                      <StageComponent
                          :column=12
                          :dbStatus="renewal.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=500
                          :prevStage=400
                          :licence_id=null
                          :stageTitle="'Client Invoiced'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />


                      <DocComponent
                          :documentModel="renewal"
                          @file-value-changed="submitDocument"
                          @file-deleted="deleteDocument"
                          :hasFile="hasFile('Client Invoiced')"
                          :errors="errors"
                          :error="error"
                          :docType="'Client Invoiced'"
                          :success="success"
                          :stage="500"
                      />

                      <hr>
                      <StageComponent
                          :column=6
                          :dbStatus="renewal.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=600
                          :prevStage=500
                          :licence_id=null
                          :stageTitle="'Client Paid'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />

                      <DateComponent
                          :stage="'Client Paid'"
                          :model="renewal"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          :dated_at="getRenewalDate('Client Paid').dated_at"
                          @date-value-changed="updateDate"
                          :success="success"
                      />


                      <hr>
                      <StageComponent
                          :column=6
                          :dbStatus="renewal.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=700
                          :prevStage=600
                          :licence_id=null
                          :stageTitle="'Payment to the National Liquor Authority'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />

                      <DateComponent
                          :stage="'Payment to the National Liquor Authority'"
                          :model="renewal"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          :dated_at="getRenewalDate('Payment to the National Liquor Authority').dated_at"
                          @date-value-changed="updateDate"
                          :success="success"
                      />

                      <DocComponent
                          :documentModel="renewal"
                          @file-value-changed="submitDocument"
                          @file-deleted="deleteDocument"
                          :hasFile="hasFile('Payment to the National Liquor Authority')"
                          :errors="errors"
                          :error="error"
                          :orderByNumber=500
                          :docType="'Payment to the National Liquor Authority'"
                          :success="success"
                          :stage="500"
                      />

                      <hr>
                      <StageComponent
                          :column=6
                          :dbStatus="renewal.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=800
                          :prevStage=700
                          :licence_id=null
                          :stageTitle="'Renewal Forms Sent'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />

                      <DateComponent
                          :stage="'Renewal Forms Sent'"
                          :model="renewal"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          :dated_at="getRenewalDate('Renewal Forms Sent').dated_at"
                          @date-value-changed="updateDate"
                          :success="success"
                      />

                      <hr>
                      <StageComponent
                          :column=6
                          :dbStatus="renewal.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=900
                          :prevStage=800
                          :licence_id=null
                          :stageTitle="'Renewal Forms Received'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />

                      <DateComponent
                          :stage="'Renewal Forms Received'"
                          :model="renewal"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          :dated_at="getRenewalDate('Renewal Forms Received').dated_at"
                          @date-value-changed="updateDate"
                          :success="success"
                      />
                      <DocComponent
                      :documentModel="renewal"
                      @file-value-changed="submitDocument"
                      @file-deleted="deleteDocument"
                      :hasFile="hasFile('Renewal Forms Received')"
                      :errors="errors"
                      :error="error"
                      :orderByNumber=500
                      :docType="'Renewal Forms Received'"
                      :success="success"
                      :stage="900"
                  />

                      <hr>
             
                      <StageComponent
                          :column=12
                          :dbStatus="renewal.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=1000
                          :prevStage=900
                          :licence_id=null
                          :stageTitle="'Renewal Documents Preparation'"
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
                            :docTitle="'Tax Clearance Certificate'"
                            :docType="'Tax Clearance Certificate'"
                            :docModel="renewal"
                            :stage=1000
                            :hasFile="hasFile('Tax Clearance Certificate')"
                        />

                        <MergeDocumentComponent
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :success="success"
                            :error="error"
                            :errors="errors"
                            :column=6
                            :model_id="renewal"
                            :docTitle="'Contribution Certificate'"
                            :docType="'Contribution Certificate'"
                            :docModel="renewal"
                            :stage=1000
                            :hasFile="hasFile('Contribution Certificate')"
                        />

                        <MergeDocumentComponent
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :success="success"
                            :error="error"
                            :errors="errors"
                            :column=6
                            :model_id="renewal"
                            :docTitle="'Police Clearance Certificates'"
                            :docType="'Police Clearance Certificates'"
                            :docModel="renewal"
                            :stage=1000
                            :hasFile="hasFile('Police Clearance Certificates')"
                        />

                       
                        <LinkComponent
                        :hasFile="hasFile('Payment to the National Liquor Authority')"
                        :docTitle="'Proof of Payment'"
                         :docType="'Payment to the National Liquor Authority'"
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
                            :model_id="renewal"
                            :docTitle="'BEE Certificate/Affidavit'"
                            :docType="'BEE Certificate/Affidavit'"
                            :docModel="renewal"
                            :stage=1000
                            :hasFile="hasFile('BEE Certificate/Affidavit')"
                        />

                        <MergeDocumentComponent
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :success="success"
                            :error="error"
                            :errors="errors"
                            :column=6
                            :model_id="renewal"
                            :docTitle="'Annual Turnover Sheet'"
                            :docType="'Annual Turnover Sheet'"
                            :docModel="renewal"
                            :stage=1000
                            :hasFile="hasFile('Annual Turnover Sheet')"
                        />

                        <MergeDocumentComponent
                            @file-value-changed="submitDocument"
                            @file-deleted="deleteDocument"
                            :success="success"
                            :error="error"
                            :errors="errors"
                            :column=6
                            :model_id="renewal"
                            :docTitle="'Proxy Documents'"
                            :docType="'Proxy Documents'"
                            :docModel="renewal"
                            :stage=1000
                            :hasFile="hasFile('Proxy Documents')"
                        />

                      </div>

                      <hr>

                      <StageComponent
                          :column=6
                          :dbStatus="renewal.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=1100
                          :prevStage=1000
                          :licence_id=null
                          :stageTitle="'Annual Return Submitted'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />

                      <DateComponent
                          :stage="'Annual Return Submitted'"
                          :model="renewal"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          :dated_at="getRenewalDate('Annual Return Submitted').dated_at"
                          @date-value-changed="updateDate"
                          :success="success"
                      />

                      <hr>

                      <StageComponent
                          :column=6
                          :dbStatus="renewal.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=1200
                          :prevStage=1100
                          :licence_id=null
                          :stageTitle="'Renewal Submitted – Processing'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />

                      <DateComponent
                          :stage="'Renewal Submitted – Processing'"
                          :model="renewal"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          :dated_at="getRenewalDate('Renewal Submitted – Processing').dated_at"
                          @date-value-changed="updateDate"
                          :success="success"
                      />

                      <hr>

                      <StageComponent
                          :column=6
                          :dbStatus="renewal.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=1300
                          :prevStage=1200
                          :licence_id=null
                          :stageTitle="'Additional Documents/Information Requested'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />

                      <AdditionalDocsComponent
                            :licence_id="renewal.id"
                            modelable_type="LicenceRenewal"
                            :additional_docs="renewal.additional_docs"
                            :success="success"
                            :errors="errors"
                            :error="error"
                            />


                      <hr>
                      <StageComponent
                          :column=6
                          :dbStatus="renewal.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=1400
                          :prevStage=1300
                          :licence_id=null
                          :stageTitle="'Renewal - Pending QA'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />

                      <DateComponent
                          :stage="'Renewal - Pending QA'"
                          :model="renewal"
                          :licence="renewal_pending_qa_at"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          :dated_at="getRenewalDate('Renewal Pending QA').dated_at"
                          @date-value-changed="updateDate"
                          :success="success"

                      />
                      <hr>
                      <StageComponent
                          :column=6
                          :dbStatus="renewal.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=1500
                          :prevStage=1400
                          :licence_id=null
                          :stageTitle="'Renewal - Awaiting Sign Off'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />

                      <DateComponent
                          :stage="'Renewal - Awaiting Sign Off'"
                          :model="renewal"
                          :licence="renewal_awaiting_sign_off_at"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          :dated_at="getRenewalDate('Renewal Awaiting Sign Off').dated_at"
                          @date-value-changed="updateDate"
                          :success="success"

                      />

                      <hr>

                      <StageComponent
                          :column=6
                          :dbStatus="renewal.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=1600
                          :prevStage=1500
                          :licence_id=null
                          :stageTitle="'Renewal - Approved'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />

                      <DateComponent
                          :stage="'Renewal Approved'"
                          :model="renewal"
                          :licence="renewal_approved_at"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          :dated_at="getRenewalDate('Renewal Approved').dated_at"
                          @date-value-changed="updateDate"
                          :success="success"

                      />

                      <DocComponent
                          :documentModel="renewal"
                          @file-value-changed="submitDocument"
                          @file-deleted="deleteDocument"
                          :hasFile="hasFile('Renewal Approved')"
                          :errors="errors"
                          :error="error"
                          :orderByNumber=500
                          :docType="'Renewal Approved'"
                          :success="success"
                          :stage="500"
                      />
                      <hr>
                      <StageComponent
                          :column=6
                          :dbStatus="renewal.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=1700
                          :prevStage=1600
                          :licence_id=null
                          :stageTitle="'NLA 33 Delivered'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />

                      <DateComponent
                          :stage="'NLA 33 Delivered'"
                          :model="renewal"
                          :licence="nla_33_delivered_at"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          :dated_at="getRenewalDate('NLA 33 Delivered').dated_at"
                          @date-value-changed="updateDate"
                          :success="success"

                      />

                      <DocComponent
                          :documentModel="renewal"
                          @file-value-changed="submitDocument"
                          @file-deleted="deleteDocument"
                          :hasFile="hasFile('NLA 33 Delivered')"
                          :errors="errors"
                          :error="error"
                          :orderByNumber=500
                          :docType="'NLA 33 Delivered'"
                          :success="success"
                          :stage="500"
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
        <hr>

        <Task

            :tasks="tasks"
            :model_id="renewal.id"
            :success="success"
            :error="error"
            :errors="errors"
            :model_type="'Licence Renewal'"/>

      </div>
    </div>


  </Layout>
</template>
<style scoped>
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

.limit-file-namee{
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 200px;
}
</style>
<style src="@vueform/multiselect/themes/default.css"></style>

<script src="./wholesale_renewals.js"></script>
