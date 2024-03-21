<script src="./view_wholesale_transfer.js"></script>
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
.fa-upload{
    cursor: pointer;
}
.document-names { width: 60%; }

.curser-pointer{ cursor: pointer;}
</style>
<style src="@vueform/multiselect/themes/default.css"></style>
<template>
    <Layout>
        <Head title="View Transfer"/>
        <div class="container-fluid">
            <Banner/>
            <div class="card card-body mx-3 mx-md-4 mt-n6">
                <div class="row">
                    <div class="col-lg-10 col-10">
                        <h6>Transfer Wholesale Info for: <Link :href="`/view-licence?slug=${view_transfer.licence.slug}`" class="text-success">
                            {{ view_transfer.licence.trading_name ? view_transfer.licence.trading_name : '' }}</Link></h6>
                        <p class="text-sm mb-0">Current Stage:
                            {{ getStatus(view_transfer.status) }}
                        </p>
                    </div>
                    <div class="col-lg-2 col-2 my-auto text-end">
                        <button v-if="$page.props.auth.has_slowtow_admin_role"
                                @click="deleteTransfer" type="button" class="btn btn-sm btn-danger float-lg-end pe-4"> Delete</button>
                    </div>
                </div>
                <div class="row">
                    <div class="mt-3 row">
                        <div class="col-12 col-md-12 col-xl-12 position-relative">
                            <div class="card card-plain h-100">
                                <div class="p-3 card-body">
                                    <form @submit.prevent="submit">
                                        <div class="row">


                                            <StageComponent
                                                :column=5
                                                :dbStatus="view_transfer.status"
                                                :errors="errors"
                                                :error="error"
                                                :stageValue=100
                                                :prevStage=null
                                                :licence_id="view_transfer.slug"
                                                :stageTitle="'Client Quoted'"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />

                                            <DocComponent
                                                @file-value-changed="submitDocument"
                                                @file-deleted="deleteDocument"
                                                :documentModel="view_transfer"
                                                :hasFile="hasFile('Client Quoted')"
                                                :errors="errors"
                                                :error="error"
                                                orderByNumber=''
                                                stage='100'
                                                :docType="'Client Quoted'"
                                                :success="success"
                                            />
                                            <hr>

                                            <StageComponent
                                                :column=5
                                                :dbStatus="view_transfer.status"
                                                :errors="errors"
                                                :error="error"
                                                stageValue=200
                                                prevStage=100
                                                :licence_id="view_transfer.slug"
                                                :stageTitle="'Client Invoiced'"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />

                                            <DocComponent
                                                @file-value-changed="submitDocument"
                                                @file-deleted="deleteDocument"
                                                :documentModel="view_transfer"
                                                :hasFile="hasFile('Client Invoiced')"
                                                :errors="errors"
                                                :error="error"
                                                stage='200'
                                                :docType="'Client Invoiced'"
                                                :success="success"
                                            />


                                            <hr>

                                            <StageComponent
                                                :column=5
                                                :dbStatus="view_transfer.status"
                                                :errors="errors"
                                                :error="error"
                                                stageValue=300
                                                prevStage=200
                                                :licence_id="view_transfer.slug"
                                                :stageTitle="'Client Paid'"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />

                                          <DateComponent
                                              :licence="view_transfer"
                                              stage="Client Paid"
                                              :canSave="$page.props.auth.has_slowtow_admin_role"
                                              :errors="errors"
                                              :error="error"
                                              :column=5
                                              @date-value-changed="updateStageDate"
                                              :dated_at="view_transfer.payment_to_liquor_board_at"
                                              :success="success"
                                          />


                                          <hr>

                                            <hr>
                                            <!-- Strat merge========================================================================================== -->
                                            <StageComponent
                                                :column=5
                                                :dbStatus="view_transfer.status"
                                                :errors="errors"
                                                :error="error"
                                                stageValue=400
                                                prevStage=300
                                                :licence_id="view_transfer.slug"
                                                :stageTitle="'Transfer Documents Preparation'"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />

                                            <div class="col-12 columns"></div>
                                            <div class="col-4 columns">
                                                <div v-if="view_transfer.transfered_from === 'Company'" class="input-group input-group-outline null is-filled ">
                                                    <label class="form-label">Previous Licence Holder</label>
                                                    <input type="text" required readonly title="You can`t change this field." class="form-control form-control-default"
                                                           :value="view_transfer.old_company.name" >
                                                </div>

                                                <div v-else-if="view_transfer.transfered_from === 'Individual'" class="input-group input-group-outline null is-filled ">
                                                    <label class="form-label">Previous Licence Holder</label>
                                                    <input type="text" required readonly title="You can`t change this field." class="form-control form-control-default"
                                                           :value="view_transfer.old_person.full_name" >
                                                </div>
                                            </div>

                                            <div class="col-4 columns">
                                                <div class="input-group input-group-outline null is-filled ">
                                                    <label class="mx-6 mt-2">Transfering To</label>
                                                </div>
                                            </div>

                                            <div v-if="view_transfer.transfered_to === 'Company'" class="col-4 columns" >
                                                <div class="input-group input-group-outline null is-filled">
                                                    <label class="form-label">Current Licence Holder</label>
                                                    <input :value="view_transfer.new_company.name" type="text" required
                                                           disabled title="You can`t change this field." class="form-control form-control-default">

                                                </div>
                                                <div v-if="errors.new_company" class="text-danger">{{ errors.new_company }}</div>
                                            </div>

                                            <div v-else-if="view_transfer.transfered_to === 'Individual'" class="col-4 columns">
                                                <div class="input-group input-group-outline null is-filled">
                                                    <label class="form-label">Current Licence Holder</label>
                                                    <input :value="view_transfer.new_person.full_name" type="text" required readonly title="You can`t change this field." class="form-control form-control-default">
                                                </div>
                                                <div v-if="errors.new_company" class="text-danger">{{ errors.new_company }}</div>
                                            </div>

                                            <div class="d-flex justify-content-center w-100">

                                                <PrepareTransferApplicationComponent
                                                    @file-value-changed="submitDocument"
                                                    @file-deleted="deleteDocument"
                                                    :documentModel="view_transfer"
                                                    :hasFile="hasMergeFile('Transfer Forms','Old Licence Holder')"
                                                    :errors="errors"
                                                    :error="error"
                                                    stage="400"
                                                    belongsTo="Old Licence Holder"
                                                    orderByNumber='300'
                                                    :docType="'Transfer Forms'"
                                                    :success="success"
                                                />

                                                <button type="button" class=" w-30 px-3 mb-2 btn bg-gradient-success ms-2">NLA 1 and NLA 10 Forms </button>

                                                <PrepareTransferApplicationComponent
                                                    @file-value-changed="submitDocument"
                                                    @file-deleted="deleteDocument"
                                                    :documentModel="view_transfer"
                                                    :hasFile="hasMergeFile('Transfer Forms','Current Licence Holder')"
                                                    :errors="errors"
                                                    :error="error"
                                                    stage="400"
                                                    belongsTo="Current Licence Holder"
                                                    orderByNumber='400'
                                                    :docType="'Transfer Forms'"
                                                    :success="success"
                                                />

                                            </div>

                                            <div class="d-flex justify-content-center w-100">
                                              <PrepareTransferApplicationComponent
                                                  @file-value-changed="submitDocument"
                                                  @file-deleted="deleteDocument"
                                                  :documentModel="view_transfer"
                                                  :hasFile="hasMergeFile('CIPC Certificate','Old Licence Holder')"
                                                  :errors="errors"
                                                  :error="error"
                                                  stage="400"
                                                  belongsTo="Old Licence Holder"
                                                  orderByNumber='1100'
                                                  :dontUpload="true"
                                                  :docType="'CIPC Certificate'"
                                                  :success="success"
                                              />
                                                <button type="button" class=" w-30 px-3 mb-2 btn bg-gradient-success ms-2">Annual Turnover Sheet</button>

                                                <PrepareTransferApplicationComponent
                                                    @file-value-changed="submitDocument"
                                                    @file-deleted="deleteDocument"
                                                    :documentModel="view_transfer"
                                                    :hasFile="hasMergeFile('Smoking Affidavit','Current Licence Holder')"
                                                    :errors="errors"
                                                    :error="error"
                                                    stage="400"
                                                    belongsTo="Current Licence Holder"
                                                    orderByNumber='500'
                                                    :dontUpload="true"
                                                    :docType="'Smoking Affidavit'"
                                                    :success="success"
                                                />
                                            </div>


                                            <div class="d-flex justify-content-center w-100">
                                                <PrepareTransferApplicationComponent
                                                    @file-value-changed="submitDocument"
                                                    @file-deleted="deleteDocument"
                                                    :documentModel="view_transfer"
                                                    :hasFile="hasMergeFile('POA & RES','Old Licence Holder')"
                                                    :errors="errors"
                                                    :error="error"
                                                    stage="400"
                                                    :belongsTo="'Old Licence Holder'"
                                                    orderByNumber='600'
                                                    :dontUpload="true"
                                                    :docType="'POA & RES'"
                                                    :success="success"
                                                />
                                                <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2">ID Documents</button>


                                                <PrepareTransferApplicationComponent
                                                    @file-value-changed="submitDocument"
                                                    @file-deleted="deleteDocument"
                                                    :documentModel="view_transfer"
                                                    :hasFile="hasMergeFile('POA & RES','Current Licence Holder')"
                                                    :errors="errors"
                                                    :error="error"
                                                    stage="400"
                                                    :belongsTo="'Current Licence Holder'"
                                                    orderByNumber='700'
                                                    :dontUpload="true"
                                                    :docType="'POA & RES'"
                                                    :success="success"
                                                ></PrepareTransferApplicationComponent>

                                            </div>

                                            <div class="d-flex justify-content-center w-100">
                                                <PrepareTransferApplicationComponent
                                                    @file-value-changed="submitDocument"
                                                    @file-deleted="deleteDocument"
                                                    :documentModel="view_transfer"
                                                    :hasFile="hasMergeFile('Shareholding','Old Licence Holder')"
                                                    :errors="errors"
                                                    :error="error"
                                                    stage="400"
                                                    belongsTo="Old Licence Holder"
                                                    orderByNumber='1000'
                                                    :dontUpload="true"
                                                    :docType="'Shareholding'"
                                                    :success="success"
                                                />
                                                <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2">Police Clearance Certificates</button>

                                                <PrepareTransferApplicationComponent
                                                    @file-value-changed="submitDocument"
                                                    @file-deleted="deleteDocument"
                                                    :documentModel="view_transfer"
                                                    :hasFile="hasMergeFile('Shareholding','Current Licence Holder')"
                                                    :errors="errors"
                                                    :error="error"
                                                    stage="400"
                                                    belongsTo="Current Licence Holder"
                                                    orderByNumber='1200'
                                                    :dontUpload="true"
                                                    :docType="'Shareholding'"
                                                    :success="success"
                                                />
                                            </div>

                                            <div class="d-flex justify-content-center w-100">

                                                <PrepareTransferApplicationComponent
                                                    @file-value-changed="submitDocument"
                                                    @file-deleted="deleteDocument"
                                                    :documentModel="view_transfer"
                                                    :hasFile="hasMergeFile('CIPC Certificate','Old Licence Holder')"
                                                    :errors="errors"
                                                    :error="error"
                                                    stage="400"
                                                    belongsTo="Old Licence Holder"
                                                    orderByNumber='1100'
                                                    :dontUpload="true"
                                                    :docType="'CIPC Certificate'"
                                                    :success="success"
                                                />
                                                <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2">Contribution Certicate</button>

                                                <PrepareTransferApplicationComponent
                                                    @file-value-changed="submitDocument"
                                                    @file-deleted="deleteDocument"
                                                    :documentModel="view_transfer"
                                                    :hasFile="hasMergeFile('CIPC Certificate','Current Licence Holder')"
                                                    :errors="errors"
                                                    :error="error"
                                                    stage="400"
                                                    belongsTo="Current Licence Holder"
                                                    orderByNumber='1300'
                                                    :dontUpload="true"
                                                    :docType="'CIPC Certificate'"
                                                    :success="success"
                                                />

                                            </div>

                                            <div class="d-flex justify-content-center w-100">
                                              <PrepareTransferApplicationComponent
                                                  @file-value-changed="submitDocument"
                                                  @file-deleted="deleteDocument"
                                                  :documentModel="view_transfer"
                                                  :hasFile="hasMergeFile('CIPC Certificate','Old Licence Holder')"
                                                  :errors="errors"
                                                  :error="error"
                                                  stage="400"
                                                  belongsTo="Old Licence Holder"
                                                  orderByNumber='1100'
                                                  :dontUpload="true"
                                                  :docType="'CIPC Certificate'"
                                                  :success="success"
                                              />
                                                <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2">Tax Clearance</button>

                                                <PrepareTransferApplicationComponent
                                                    @file-value-changed="submitDocument"
                                                    @file-deleted="deleteDocument"
                                                    :documentModel="view_transfer"
                                                    :hasFile="hasMergeFile('Company Documents','Current Licence Holder')"
                                                    :errors="errors"
                                                    :error="error"
                                                    stage="400"
                                                    belongsTo="Current Licence Holder"
                                                    :docType="'Company Documents'"
                                                    :success="success"
                                                />
                                            </div>

                                            <div class="d-flex justify-content-center w-100">
                                              <PrepareTransferApplicationComponent
                                                  @file-value-changed="submitDocument"
                                                  @file-deleted="deleteDocument"
                                                  :documentModel="view_transfer"
                                                  :hasFile="hasMergeFile('CIPC Certificate','Old Licence Holder')"
                                                  :errors="errors"
                                                  :error="error"
                                                  stage="400"
                                                  belongsTo="Old Licence Holder"
                                                  orderByNumber='1100'
                                                  :dontUpload="true"
                                                  :docType="'CIPC Certificate'"
                                                  :success="success"
                                              />
                                                <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2">Zoning Certificate and LAA</button>

                                                <PrepareTransferApplicationComponent
                                                    @file-value-changed="submitDocument"
                                                    @file-deleted="deleteDocument"
                                                    :documentModel="view_transfer"
                                                    :hasFile="hasMergeFile('ID Documents','Current Licence Holder')"
                                                    :errors="errors"
                                                    :error="error"
                                                    stage="400"
                                                    belongsTo="Current Licence Holder"
                                                    :docType="'ID Documents'"
                                                    :orderByNumber='1400'
                                                    :success="success"
                                                />

                                            </div>

                                            <div class="d-flex justify-content-center w-100">
                                              <PrepareTransferApplicationComponent
                                                  @file-value-changed="submitDocument"
                                                  @file-deleted="deleteDocument"
                                                  :documentModel="view_transfer"
                                                  :hasFile="hasMergeFile('CIPC Certificate','Old Licence Holder')"
                                                  :errors="errors"
                                                  :error="error"
                                                  stage="400"
                                                  belongsTo="Old Licence Holder"
                                                  orderByNumber='1100'
                                                  :dontUpload="true"
                                                  :docType="'CIPC Certificate'"
                                                  :success="success"
                                              />
                                                <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2">BEE Certicate/Affidavit</button>

                                                <PrepareTransferApplicationComponent
                                                    @file-value-changed="submitDocument"
                                                    @file-deleted="deleteDocument"
                                                    :documentModel="view_transfer"
                                                    :hasFile="hasMergeFile('Police Clearances','Current Licence Holder')"
                                                    :errors="errors"
                                                    :error="error"
                                                    stage="400"
                                                    belongsTo="Current Licence Holder"
                                                    :docType="'Police Clearances'"
                                                    :orderByNumber='1500'
                                                    :success="success"
                                                />
                                            </div>

                                            <div class="d-flex justify-content-center w-100">
                                              <PrepareTransferApplicationComponent
                                                  @file-value-changed="submitDocument"
                                                  @file-deleted="deleteDocument"
                                                  :documentModel="view_transfer"
                                                  :hasFile="hasMergeFile('CIPC Certificate','Old Licence Holder')"
                                                  :errors="errors"
                                                  :error="error"
                                                  stage="400"
                                                  belongsTo="Old Licence Holder"
                                                  orderByNumber='1100'
                                                  :dontUpload="true"
                                                  :docType="'CIPC Certificate'"
                                                  :success="success"
                                              />
                                                <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2">CIPC Certificate</button>

                                                <PrepareTransferApplicationComponent
                                                    @file-value-changed="submitDocument"
                                                    @file-deleted="deleteDocument"
                                                    :documentModel="view_transfer"
                                                    :hasFile="hasMergeFile('Tax Clearance','Current Licence Holder')"
                                                    :errors="errors"
                                                    :error="error"
                                                    stage="400"
                                                    belongsTo="Current Licence Holder"
                                                    :docType="'Tax Clearance'"
                                                    :orderByNumber='1600'
                                                    :success="success"
                                                />
                                            </div>

                                            <div class="d-flex justify-content-center w-100">
                                                <NoneUploadComponent/>
                                                <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2">Shareholding</button>

                                                <PrepareTransferApplicationComponent
                                                    @file-value-changed="submitDocument"
                                                    @file-deleted="deleteDocument"
                                                    :documentModel="view_transfer"
                                                    :hasFile="hasMergeFile('LTA Certificate','Current Licence Holder')"
                                                    :errors="errors"
                                                    :error="error"
                                                    stage="400"
                                                    belongsTo="Current Licence Holder"
                                                    :docType="'LTA Certificate'"
                                                    :orderByNumber='1700'
                                                    :success="success"
                                                />
                                            </div>

                                            <div class="d-flex justify-content-center w-100">
                                                <NoneUploadComponent/>
                                                <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2">Proof of Payment</button>

                                                <NoneUploadComponent/>

                                            </div>


                                            <div >
                                                <div v-if="view_transfer.merged_document">
                                                    <a :href="`/storage/app/public/${view_transfer.merged_document}`" target="_blank" :style="{float: 'right'}" class="btn btn-secondary ms-2" >View</a>
                                                </div>

                                                <button
                                                    @click="mergeDocuments" :style="{float: 'right'}"
                                                    type="button" class="btn btn-secondary ms-2" :disabled="mergeForm.processing || !canMerge()">
                                                    <span v-if="mergeForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                    Compile And Merge
                                                </button>


                                            </div>


                                            <hr>

                                            <StageComponent
                                                :dbStatus="view_transfer.status"
                                                :errors="errors"
                                                :error="error"
                                                :stageValue=500
                                                prevStage=400
                                                :licence_id="view_transfer.slug"
                                                stageTitle="Proof of Payment to the National Liquor Authority"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />

                                            <div class="col-md-6">
                                                <DocComponent
                                                    :documentModel="view_transfer"
                                                    @file-value-changed="submitDocument"
                                                    @file-deleted="deleteDocument"
                                                    :hasFile="hasFile('Payment To The Liquor Board')"
                                                    :errors="errors"
                                                    :error="error"
                                                    :orderByNumber=null
                                                    stage='500'
                                                    :docType="'Payment To The Liquor Board'"
                                                    :success="success"
                                                />
                                            </div>
                                            <DateComponent
                                                :licence="view_transfer"
                                                stage="Payment To The Liquor Board"
                                                :canSave="$page.props.auth.has_slowtow_admin_role"
                                                :errors="errors"
                                                :error="error"
                                                :column=5
                                                @date-value-changed="updateStageDate"
                                                :dated_at="view_transfer.payment_to_liquor_board_at"
                                                :success="success"
                                            />

                                            <hr>

                                            <StageComponent
                                                :column=5
                                                :dbStatus="view_transfer.status"
                                                :errors="errors"
                                                :error="error"
                                                stageValue=600
                                                prevStage=500
                                                :licence_id="view_transfer.slug"
                                                :stageTitle="'Scanned Application'"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />

                                            <DocComponent
                                                @file-value-changed="submitDocument"
                                                @file-deleted="deleteDocument"
                                                :documentModel="view_transfer"
                                                :hasFile="hasFile('Scanned Application')"
                                                :errors="errors"
                                                :error="error"
                                                orderByNumber=''
                                                stage='600'
                                                :docType="'Scanned Application'"
                                                :success="success"
                                            />
                                            <hr/>


                                            <StageComponent
                                                :dbStatus="view_transfer.status"
                                                :errors="errors"
                                                :error="error"
                                                :stageValue=700
                                                prevStage=600
                                                :licence_id="view_transfer.slug"
                                                stageTitle="Application Lodged"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />

                                            <div class="col-md-6">
                                                <DocComponent
                                                    :documentModel="view_transfer"
                                                    @file-value-changed="submitDocument"
                                                    @file-deleted="deleteDocument"
                                                    :hasFile="hasFile('Application Lodged')"
                                                    :errors="errors"
                                                    :error="error"
                                                    :orderByNumber=null
                                                    stage='700'
                                                    :docType="'Application Lodged'"
                                                    :success="success"
                                                />
                                            </div>
                                            <DateComponent
                                                :licence="view_transfer"
                                                stage="Application Lodged"
                                                :canSave="$page.props.auth.has_slowtow_admin_role"
                                                :errors="errors"
                                                :error="error"
                                                :column=5
                                                @date-value-changed="updateStageDate"
                                                :dated_at="view_transfer.lodged_at"
                                                :success="success"
                                            />


                                            <hr>

                                          <StageComponent
                                              :column=6
                                              :errors="errors"
                                              :error="error"
                                              :stageValue=600
                                              :prevStage=null
                                              :licence_id=null
                                              :stageTitle="'Additional Documents Requested'"
                                              :success="success"
                                              @stage-value-changed="pushData"
                                          />

                                          <div class="container mt-2 text-left">

                                            <div v-if="$page.props.auth.has_slowtow_admin_role" class="row justify-content-center">

                                              <div class="col-4 columns">
                                                <div class="input-group input-group-outline null is-filled">
                                                  <label class="form-label">Documents/Information Submitted</label>
                                                  <textarea  required class="form-control form-control-default" rows="1" ></textarea>
                                                </div>
                                                <div v-if="errors.description">{{ errors.description }}</div>
                                              </div>

                                              <div class="col-3 columns mb-4">

                                                <label for="attach-doc" class="btn mb-0 bg-gradient-dark btn-md null null">
                                                  <input @change="getFileName" type="file" hidden id="attach-doc">
                                                  <i class="fas fa-paperclip me-2" aria-hidden="true"></i> Attach Document </label>
                                                <div v-if="errors.document" class="text-danger">{{ errors.document }}</div>
                                                <div class="text-sm" v-if="file_name">File Selected: <span class="text-success">{{ file_name }}</span></div>
                                                <p v-if="file_has_apostrophe" class="text-danger text-sm mt-4">
                                                  File cannot contain apostrophe(s).</p>
                                              </div>

                                              <div class="col-md-3 columns mb-4">
                                                <div class="input-group input-group-outline null is-filled ">
                                                  <label class="form-label">Date Requested</label>
                                                  <input  type="date" class="form-control form-control-default">
                                                </div>
                                                <div v-if="errors.uploaded_at" class="text-danger">{{ errors.uploaded_at }}</div>
                                              </div>
                                              <div class="col-2 mb-3 text-end">
                                                <button @click="submit" type="button" class="btn btn-sm btn-info">
                                                  <span  class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                  Submit</button>
                                              </div>
                                            </div>
                                          </div>
                                          <table class="table table-bordered mt-3">
                                            <thead>
                                            <tr>
                                              <th scope="col">Request Description</th>
                                              <th scope="col">Date Requested</th>
                                              <th scope="col">View Document</th>
                                              <th scope="col">Edit</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr >
                                              <th>test</th>
                                              <td>test</td>
                                              <td>
                                                <a   target="_blank">
                                                  <i class="fa fa-file-pdf text-lg text-danger" aria-hidden="true"></i></a>
                                              </td>
                                              <td  class="cursor-pointer fa fa-trash-alt text-lg text-danger" aria-hidden="true">
                                              </td>
                                            </tr>


                                            </tbody>
                                          </table>

                                          <hr>

                                            <StageComponent
                                                :dbStatus="view_transfer.status"
                                                :errors="errors"
                                                :error="error"
                                                :stageValue=900
                                                prevStage=800
                                                :licence_id="view_transfer.slug"
                                                stageTitle="Transfer Issued"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />

                                            <div class="col-md-6">
                                                <DocComponent
                                                    :documentModel="view_transfer"
                                                    @file-value-changed="submitDocument"
                                                    @file-deleted="deleteDocument"
                                                    :hasFile="hasFile('Transfer Issued')"
                                                    :errors="errors"
                                                    :error="error"
                                                    :orderByNumber=null
                                                    stage='900'
                                                    :docType="'Transfer Issued'"
                                                    :success="success"
                                                />
                                            </div>
                                            <DateComponent
                                                :licence="view_transfer"
                                                stage="Transfer Issued"
                                                :canSave="$page.props.auth.has_slowtow_admin_role"
                                                :errors="errors"
                                                :error="error"
                                                :column=5
                                                @date-value-changed="updateStageDate"
                                                :dated_at="view_transfer.issued_at"
                                                :success="success"
                                            />

                                            <hr>


                                            <StageComponent
                                                :dbStatus="view_transfer.status"
                                                :errors="errors"
                                                :error="error"
                                                :stageValue=1000
                                                prevStage=900
                                                :licence_id="view_transfer.slug"
                                                stageTitle="Transfer Delivered"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />

                                            <div class="col-md-6">
                                                <DocComponent
                                                    :documentModel="view_transfer"
                                                    @file-value-changed="submitDocument"
                                                    @file-deleted="deleteDocument"
                                                    :hasFile="hasFile('Transfer Delivered')"
                                                    :errors="errors"
                                                    :error="error"
                                                    :orderByNumber=null
                                                    stage='1000'
                                                    :docType="'Transfer Delivered'"
                                                    :success="success"
                                                />
                                            </div>
                                            <DateComponent
                                                :licence="view_transfer"
                                                stage="Transfer Delivered"
                                                :canSave="$page.props.auth.has_slowtow_admin_role"
                                                :errors="errors"
                                                :error="error"
                                                :column=5
                                                @date-value-changed="updateStageDate"
                                                :dated_at="view_transfer.delivered_at"
                                                :success="success"
                                            />


                                            <div>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <hr/>


                <Task :tasks="tasks" :model_id="view_transfer.id" :success="success" :error="error" :errors="errors" :model_type="'Transfer'"/>



            </div>
        </div>

    </Layout>
</template>
