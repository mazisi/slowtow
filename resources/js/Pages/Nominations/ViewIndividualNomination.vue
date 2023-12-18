<script src="./view_nomination.js"></script>
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
</style>
<style src="@vueform/multiselect/themes/default.css"></style>
<template>
    <Layout>
        <Head title="View Nomination" />
        <div class="container-fluid">
            <Banner/>
            <div class="card card-body mx-3 mx-md-4 mt-n6">
                <div class="row">
                    <div class="col-lg-10 col-10">
                        <h6><Link :href="`/view-licence/?slug=${nomination.licence.slug}`" class="text-success">
                            {{ nomination.licence.trading_name ? nomination.licence.trading_name : '' }}</Link> - {{ nomination.year }} </h6>
                        <!--refactor this code -->
                        <p class="text-sm mb-0">Current Stage:
                            <span class="font-weight-bold ms-1" v-if="nomination.status == '1'">Client Quoted</span>
                            <span v-if="nomination.status == '2'" class="font-weight-bold ms-1">Client Invoiced</span>
                            <span v-if="nomination.status == '3'" class="font-weight-bold ms-1">Client Paid</span>
                            <span v-if="nomination.status == '4'" class="font-weight-bold ms-1">Payment to the Liquor Board</span>
                            <span v-if="nomination.status == '5'" class="font-weight-bold ms-1">Select Nominees</span>
                            <span v-if="nomination.status == '6'" class="font-weight-bold ms-1">Prepare Nomination Application </span>
                            <span v-if="nomination.status == '7'" class="font-weight-bold ms-1">Scanned Application</span>
                            <span v-if="nomination.status == '8'" class="font-weight-bold ms-1">Nomination Lodged </span>
                            <span v-if="nomination.status == '9'" class="font-weight-bold ms-1">Nomination Issued</span>
                            <span v-if="nomination.status == '10'" class="font-weight-bold ms-1">Nomination Delivered</span>
                            <span v-else class="font-weight-bold ms-1"></span>
                        </p>
                        <!--end refactor-->

                    </div>
                    <div class="col-lg-2 col-2 my-auto text-end">
                        <button v-if="$page.props.auth.has_slowtow_admin_role"
                                @click="deleteNomination" type="button" class="btn btn-sm btn-danger float-lg-end pe-4"> Delete</button>

                    </div>

                </div>

                <div class="row">
                    <div class="mt-3 row">
                        <div class="col-12 col-md-12 col-xl-12 position-relative">
                            <div class="card card-plain h-100">
                                <div class="p-3 card-body">
                                    <form @submit.prevent="updateNomination">

                                        <div class="row">
                                            <!--  client quoted-->
                                            <StageComponent
                                                :column=12
                                                :dbStatus="nomination.status"
                                                :errors="errors"
                                                :error="error"
                                                :stageValue=100
                                                :prevStage=null
                                                :licence_id=null
                                                :stageTitle="'Client Quoted'"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />
                                            <DocComponent
                                                :documentModel="nomination"
                                                @file-value-changed="submitDocument"
                                                @file-deleted="deleteDocument"
                                                :hasFile="hasFile('Client Quoted')"
                                                :errors="errors"
                                                :error="error"
                                                :orderByNumber=100
                                                :docType="'Client Quoted'"
                                                :success="success"
                                                :stage="100"
                                            />

                                            <hr>
                                            <!-- Client Invoiced -->
                                            <StageComponent
                                                :column=12
                                                :dbStatus="nomination.status"
                                                :errors="errors"
                                                :error="error"
                                                :stageValue=100
                                                :prevStage=null
                                                :licence_id=null
                                                :stageTitle="'Client Quoted'"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />
                                            <DocComponent
                                                :documentModel="nomination"
                                                @file-value-changed="submitDocument"
                                                @file-deleted="deleteDocument"
                                                :hasFile="hasFile('Client Quoted')"
                                                :errors="errors"
                                                :error="error"
                                                :orderByNumber=100
                                                :docType="'Client Quoted'"
                                                :success="success"
                                                :stage="100"
                                            />
                                            <hr>
                                            <!-- Client Paid -->
                                            <StageComponent
                                                :column=5
                                                :dbStatus="nomination.status"
                                                :errors="errors"
                                                :error="error"
                                                :stageValue=100
                                                :prevStage=null
                                                :licence_id=null
                                                :stageTitle="'Client Paid'"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />
                                            <DateComponent
                                                :stage="'Client Paid'"
                                                :model="nomination"
                                                :licence="client_paid_at"
                                                :canSave="$page.props.auth.has_slowtow_admin_role"
                                                :errors="errors"
                                                :error="error"
                                                :column=5
                                                :dated_at="nomination.client_paid_at"
                                                :success="success"
                                                @date-value-changed="updateDate"
                                            />
                                            <DocComponent
                                                :documentModel="nomination"
                                                @file-value-changed="submitDocument"
                                                @file-deleted="deleteDocument"
                                                :hasFile="hasFile('Client Quoted')"
                                                :errors="errors"
                                                :error="error"
                                                :orderByNumber=100
                                                :docType="'Client Quoted'"
                                                :success="success"
                                                :stage="100"
                                            />

                                            <hr>

                                            <!-- Payment To The Liquor Board -->
                                            <StageComponent
                                                :column=5
                                                :dbStatus="nomination.status"
                                                :errors="errors"
                                                :error="error"
                                                :stageValue=100
                                                :prevStage=null
                                                :licence_id=null
                                                :stageTitle="'Payment To The Liquor Board'"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />
                                            <DateComponent
                                                :stage="'Payment To The Liquor Board'"
                                                :model="nomination"
                                                :licence="client_paid_at"
                                                :canSave="$page.props.auth.has_slowtow_admin_role"
                                                :errors="errors"
                                                :error="error"
                                                :column=5
                                                :dated_at="nomination.client_paid_at"
                                                :success="success"
                                                @date-value-changed="updateDate"
                                            />
                                            <DocComponent
                                                :documentModel="nomination"
                                                @file-value-changed="submitDocument"
                                                @file-deleted="deleteDocument"
                                                :hasFile="hasFile('Payment To The Liquor Board')"
                                                :errors="errors"
                                                :error="error"
                                                :orderByNumber=100
                                                :docType="'Payment To The Liquor Board'"
                                                :success="success"
                                                :stage="100"
                                            />

                                            <hr>

                                            <!-- Payment To The Liquor Board -->
                                            <StageComponent
                                                :column=10
                                                :dbStatus="nomination.status"
                                                :errors="errors"
                                                :error="error"
                                                :stageValue=100
                                                :prevStage=null
                                                :licence_id=null
                                                :stageTitle="'Select Person(s) To Nominate'"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />
                                            <!--add nominiees button-->
                                            <div class="col-md-1">
                                                <button @click="show_modal=true" type="button" data-bs-toggle="modal" data-bs-target="#pop-modal" class="btn btn-sm btn-secondary">Add</button>
                                            </div>

                                            <!-- Nominiees Table -->
                                            <div  class="table-responsive mb-2">
                                                <table  class="table align-items-center mb-0">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                            Full Name And Surname
                                                        </th>
                                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                            Date OF Birth
                                                        </th>
                                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                            ID Number
                                                        </th>

                                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                            Action
                                                        </th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr v-if="nomination.people" v-for="person in nomination.people" :key="person.id" >
                                                        <td>
                                                            <div class="d-flex px-2 py-1" >

                                                                <div class="d-flex flex-column justify-content-left">
                                                                    <Link :href="`/view-person/${person.slug}`"><h6 class="mb-0 text-sm">{{ person.full_name }} </h6></Link>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">{{ person.date_of_birth }}</td>
                                                        <td class="text-center">{{ person.id_or_passport }}</td>
                                                        <td class="text-center">
                                                            <i @click="removeSelectedNominee(person.id)"
                                                               style="cursor: pointer;"
                                                               class="material-icons-round text-danger fs-10">highlight_off</i>
                                                            <Link :href="`/view-person/${person.slug}`">
                                                                <i @click="removeSelectedNominee(person.id)"
                                                                   style="cursor: pointer;"
                                                                   class="material-icons-round text-secondary fs-10">visibility</i>
                                                            </Link>
                                                        </td>
                                                    </tr>
                                                    <tr v-else >
                                                        <td></td>
                                                        <td><p class="text-danger text-center">No nominees found.</p></td>
                                                    </tr>

                                                    </tbody>
                                                </table>
                                                <!-- End Nominiees Table -->
                                            </div>
                                            <hr>

                                            <!-- Prepare Nomination Application -->
                                            <StageComponent
                                                :column=10
                                                :dbStatus="nomination.status"
                                                :errors="errors"
                                                :error="error"
                                                :stageValue=100
                                                :prevStage=null
                                                :licence_id=null
                                                :stageTitle="'Prepare Nomination Application'"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />


                                            <div class="col-md-4 columns">
                                                <ul class="list-group">
                                                    <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
                                                        <div class="avatar me-3" v-if="nomination_forms !== null">
                                                            <a :href="`${$page.props.blob_file_path}${nomination_forms.document}`" target="_blank">
                                                                <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                        <div class="d-flex align-items-start flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm"> Nomination Forms </h6>
                                                            <p v-if="nomination_forms !== null" class="mb-0 text-xs limit-file-name">{{ nomination_forms.document_name }}</p>
                                                            <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
                                                        </div>
                                                        <a v-if="nomination_forms !== null" @click="deleteDocument(nomination_forms.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" >
                                                            <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
                                                        </a>
                                                        <DocComponent
                                                            :documentModel="nomination"
                                                            @file-value-changed="submitDocument"
                                                            @file-deleted="deleteDocument"
                                                            :hasFile="hasFile('Client Quoted')"
                                                            :errors="errors"
                                                            :error="error"
                                                            :orderByNumber=100
                                                            :docType="'Client Quoted'"
                                                            :success="success"
                                                            :stage="100"
                                                        />
                                                        <a v-else @click="getDocType(6,'Nomination Forms')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" >
                                                            <i class="fa fa-upload h5 " aria-hidden="true"></i></a>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="col-md-4 columns">
                                                <ul class="list-group">
                                                    <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
                                                        <div class="avatar me-3" >
                                                        </div>
                                                        <div class="d-flex align-items-start flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">Proof Of Payment</h6>
                                                        </div>
                                                        <a v-if="liquor_board !== null"
                                                           :href="`${$page.props.blob_file_path}${liquor_board.document}`" target="_blank" class="mb-0 btn btn-link ms-auto" >
                                                            <i class="fa fa-link h5 " aria-hidden="true"></i></a>
                                                        <a v-else style="cursor: no-drop;" title="Liqour document not uploaded" class="mb-0 btn btn-link ms-auto" >
                                                            <i class="fa fa-link h5" aria-hidden="true"></i></a>
                                                    </li>
                                                </ul>
                                            </div>


                                            <div class="col-md-4 columns">
                                                <ul class="list-group">
                                                    <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
                                                        <div class="avatar me-3" v-if="attorney_doc !== null">
                                                            <a :href="`${$page.props.blob_file_path}${attorney_doc.document}`" target="_blank">
                                                                <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                        <div class="d-flex align-items-start flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">Signed Power Of Attorney And Resolution </h6>
                                                            <p v-if="attorney_doc !== null" class="mb-0 text-xs limit-file-name">{{ attorney_doc.document_name }}</p>
                                                            <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
                                                        </div>
                                                        <a v-if="attorney_doc !== null" @click="deleteDocument(attorney_doc.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" >
                                                            <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
                                                        </a>
                                                        <a v-else @click="getDocType(6,'Power of Attorney')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" >
                                                            <i class="fa fa-upload h5 " aria-hidden="true"></i></a>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="col-md-4 columns">
                                                <ul class="list-group">
                                                    <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
                                                        <div class="avatar me-3" v-if="certified_id_doc !== null">
                                                            <a :href="`${$page.props.blob_file_path}${certified_id_doc.document}`" target="_blank">
                                                                <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                        <div class="d-flex align-items-start flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">ID Documents</h6>
                                                            <p v-if="certified_id_doc !== null" class="mb-0 text-xs limit-file-name">{{ certified_id_doc.document_name }}</p>
                                                            <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
                                                        </div>
                                                        <a v-if="certified_id_doc !== null" @click="deleteDocument(certified_id_doc.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" >
                                                            <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
                                                        </a>
                                                        <DocComponent
                                                            :documentModel="nomination"
                                                            @file-value-changed="submitDocument"
                                                            @file-deleted="deleteDocument"
                                                            :hasFile="hasFile('Client Quoted')"
                                                            :errors="errors"
                                                            :error="error"
                                                            :orderByNumber=100
                                                            :docType="'Client Quoted'"
                                                            :success="success"
                                                            :stage="100"
                                                        />
                                                        <a v-else @click="getDocType(6,'ID Document')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" >
                                                            <i class="fa fa-upload h5 " aria-hidden="true"></i></a>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="col-md-4 columns">
                                                <ul class="list-group">
                                                    <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
                                                        <div class="avatar me-3" v-if="police_clearance_doc !== null">
                                                            <a :href="`${$page.props.blob_file_path}${police_clearance_doc.document}`" target="_blank">
                                                                <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                        <div class="d-flex align-items-start flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">Police Clearances </h6>
                                                            <p v-if="police_clearance_doc !== null" class="mb-0 text-xs limit-file-name">{{ police_clearance_doc.document_name }}</p>
                                                            <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
                                                        </div>
                                                        <a v-if="police_clearance_doc !== null" @click="deleteDocument(police_clearance_doc.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" >
                                                            <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
                                                        </a>
                                                        <DocComponent
                                                            :documentModel="nomination"
                                                            @file-value-changed="submitDocument"
                                                            @file-deleted="deleteDocument"
                                                            :hasFile="hasFile('Client Quoted')"
                                                            :errors="errors"
                                                            :error="error"
                                                            :orderByNumber=100
                                                            :docType="'Client Quoted'"
                                                            :success="success"
                                                            :stage="100"
                                                        />
                                                        <a v-else @click="getDocType(6,'Police Clearances')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" >
                                                            <i class="fa fa-upload h5 " aria-hidden="true"></i></a>
                                                    </li>
                                                </ul>
                                            </div>

                                                                                                    <DocComponent
                                                            :documentModel="nomination"
                                                            @file-value-changed="submitDocument"
                                                            @file-deleted="deleteDocument"
                                                            :hasFile="hasFile('Client Quoted')"
                                                            :errors="errors"
                                                            :error="error"
                                                            :orderByNumber=100
                                                            :docType="'Client Quoted'"
                                                            :success="success"
                                                            :stage="100"
                                                        />


                                            <div class="text-end ">
                                                <button v-if="police_clearance_doc !== null
                                                        && certified_id_doc !== null
                                                        && attorney_doc !== null
                                                        && liquor_board !== null
                                                        && nomination_forms !== null" @click="mergeDocument" type="button" class="btn btn-sm btn-secondary mx-2">Compile &amp; Merge
                                                </button>

                                                <Link v-else class="btn btn-sm btn-secondary mx-2 disabled">Compile &amp; Merge</Link>


                                                <a v-if="nomination.merged_document !== null"
                                                   :href="`/storage/app/public/${nomination.merged_document}`"
                                                   target="_blank" class="btn btn-sm btn-secondary">View</a>
                                            </div>
                                            <hr>

                                            <!-- Scanned Application -->
                                            <StageComponent
                                                :column=10
                                                :dbStatus="nomination.status"
                                                :errors="errors"
                                                :error="error"
                                                :stageValue=100
                                                :prevStage=null
                                                :licence_id=null
                                                :stageTitle="'Scanned Application'"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />

                                            <DocComponent
                                                :documentModel="nomination"
                                                @file-value-changed="submitDocument"
                                                @file-deleted="deleteDocument"
                                                :hasFile="hasFile('Client Quoted')"
                                                :errors="errors"
                                                :error="error"
                                                :orderByNumber=100
                                                :docType="'Client Quoted'"
                                                :success="success"
                                                :stage="100"
                                            />
                                            <hr/>

                                            <!-- Application Lodged -->
                                            <StageComponent
                                                :column=10
                                                :dbStatus="nomination.status"
                                                :errors="errors"
                                                :error="error"
                                                :stageValue=100
                                                :prevStage=null
                                                :licence_id=null
                                                :stageTitle="'Application Lodged'"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />

                                            <DocComponent
                                                :documentModel="nomination"
                                                @file-value-changed="submitDocument"
                                                @file-deleted="deleteDocument"
                                                :hasFile="hasFile('Client Quoted')"
                                                :errors="errors"
                                                :error="error"
                                                :orderByNumber=100
                                                :docType="'Client Quoted'"
                                                :success="success"
                                                :stage="100"
                                            />


                                            <template v-if="nomination.nomination_lodged_at == null">
                                                <div class="col-md-5 columns mb-4">
                                                    <div class="input-group input-group-outline null is-filled ">
                                                        <label class="form-label">Date</label>
                                                        <input type="date" class="form-control form-control-default"
                                                               v-model="updateForm.nomination_lodged_at" >
                                                    </div>
                                                    <div v-if="errors.nomination_lodged_at" class="text-danger">{{ errors.nomination_lodged_at }}</div>
                                                </div>
                                                <div class="col-md-1"></div>
                                                <div class="col-md-1 columns">
                                                    <button v-if="nomination.nomination_lodged_at == null"
                                                            @click="updateDate" type="button" class="btn btn-sm btn-secondary">Save</button>
                                                </div>
                                            </template>

                                            <template v-else>F
                                                <div class="col-md-5 columns mb-4">
                                                    <div class="input-group input-group-outline null is-filled ">
                                                        <label class="form-label">Date</label>
                                                        <input type="date" class="form-control form-control-default"
                                                               v-model="updateForm.nomination_lodged_at" >
                                                    </div>
                                                    <div v-if="errors.nomination_lodged_at" class="text-danger">{{ errors.nomination_lodged_at }}</div>
                                                </div>
                                                <div class="col-md-1"></div>
                                                <div class="col-md-1 columns">
                                                    <button v-if="$page.props.auth.has_slowtow_admin_role"
                                                            @click="updateDate" type="button" class="btn btn-sm btn-secondary">Save</button>
                                                </div>
                                            </template>
                                            <hr>

                                            <!-- Nomination Issued -->
                                            <StageComponent
                                                :column=10
                                                :dbStatus="nomination.status"
                                                :errors="errors"
                                                :error="error"
                                                :stageValue=100
                                                :prevStage=null
                                                :licence_id=null
                                                :stageTitle="'Nomination Issued'"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />


                                            <div class="col-md-5 columns">
                                                <ul class="list-group">
                                                    <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
                                                        <div class="avatar me-3" v-if="nomination_issued !== null">
                                                            <a :href="`${$page.props.blob_file_path}${nomination_issued.document}`" target="_blank">
                                                                <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                        <div class="d-flex align-items-start flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">Document</h6>
                                                            <p v-if="nomination_issued !== null" class="mb-0 text-xs limit-file-name">{{ nomination_issued.document_name }}</p>
                                                            <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
                                                        </div>
                                                        <a v-if="nomination_issued !== null" @click="deleteDocument(nomination_issued.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" >
                                                            <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
                                                        </a>
                                                        <DocComponent
                                                            :documentModel="nomination"
                                                            @file-value-changed="submitDocument"
                                                            @file-deleted="deleteDocument"
                                                            :hasFile="hasFile('Client Quoted')"
                                                            :errors="errors"
                                                            :error="error"
                                                            :orderByNumber=100
                                                            :docType="'Client Quoted'"
                                                            :success="success"
                                                            :stage="100"
                                                        />
                                                        <!--                                                        <a v-else @click="getDocType(9,'Nomination Issued')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-4" >-->
                                                        <!--                                                            <i class="fa fa-upload h5 " aria-hidden="true"></i></a>-->
                                                    </li>
                                                </ul>
                                            </div>



                                            <template v-if="nomination.nomination_issued_at == null">
                                                <div class="col-md-5 columns mb-4">
                                                    <div class="input-group input-group-outline null is-filled ">
                                                        <label class="form-label">Date</label>
                                                        <input type="date" class="form-control form-control-default"
                                                               v-model="updateForm.nomination_issued_at" >
                                                    </div>
                                                    <div v-if="errors.nomination_issued_at" class="text-danger">{{ errors.nomination_issued_at }}</div>
                                                </div>
                                                <div class="col-md-1"></div>
                                                <div class="col-md-1 columns">
                                                    <button v-if="nomination.nomination_issued_at == null"
                                                            @click="updateDate" type="button" class="btn btn-sm btn-secondary">Save</button>
                                                </div>
                                            </template>

                                            <template v-else>
                                                <div class="col-md-5 columns mb-4">
                                                    <div class="input-group input-group-outline null is-filled ">
                                                        <label class="form-label">Date</label>
                                                        <input type="date" class="form-control form-control-default"
                                                               v-model="updateForm.nomination_issued_at" >
                                                    </div>
                                                    <div v-if="errors.nomination_issued_at" class="text-danger">{{ errors.nomination_issued_at }}</div>
                                                </div>
                                                <div class="col-md-1"></div>
                                                <div class="col-md-1 columns">
                                                    <button v-if="$page.props.auth.has_slowtow_admin_role"
                                                            @click="updateDate" type="button" class="btn btn-sm btn-secondary">Save</button>
                                                </div>
                                            </template>
                                            <hr>
                                            <!-- Nomination Delivered -->
                                            <StageComponent
                                                :column=10
                                                :dbStatus="nomination.status"
                                                :errors="errors"
                                                :error="error"
                                                :stageValue=100
                                                :prevStage=null
                                                :licence_id=null
                                                :stageTitle="'Nomination Delivered'"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />

                                            <div class="col-md-5 columns">
                                                <ul class="list-group">
                                                    <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
                                                        <div class="avatar me-3" v-if="nomination_delivered !== null">
                                                            <a :href="`${$page.props.blob_file_path}${nomination_delivered.document}`" target="_blank">
                                                                <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                        <div class="d-flex align-items-start flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">Document</h6>
                                                            <p v-if="nomination_delivered !== null" class="mb-0 text-xs limit-file-name">{{ nomination_delivered.document_name }}</p>
                                                            <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
                                                            <p v-if="nomination_delivered !== null" class="mb-0 text-xs"></p>
                                                        </div>
                                                        <a v-if="nomination_delivered !== null" @click="deleteDocument(nomination_delivered.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" >
                                                            <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
                                                        </a>
                                                        <DocComponent
                                                            :documentModel="nomination"
                                                            @file-value-changed="submitDocument"
                                                            @file-deleted="deleteDocument"
                                                            :hasFile="hasFile('Nomination Delivered')"
                                                            :errors="errors"
                                                            :error="error"
                                                            :orderByNumber=100
                                                            :docType="'Nomination Delivered'"
                                                            :success="success"
                                                            :stage="100"
                                                        />

                                                    </li>
                                                </ul>
                                            </div>

                                            <template v-if="nomination.nomination_delivered_at == null">
                                                <div class="col-md-5 columns mb-4">
                                                    <div class="input-group input-group-outline null is-filled ">
                                                        <label class="form-label">Date</label>
                                                        <input type="date" class="form-control form-control-default"
                                                               v-model="updateForm.nomination_delivered_at" >
                                                    </div>
                                                    <div v-if="errors.nomination_delivered_at" class="text-danger">{{ errors.nomination_delivered_at }}</div>
                                                </div>

                                                <div class="col-md-1"></div>
                                                <div class="col-md-1 columns">
                                                    <button v-if="nomination.nomination_delivered_at == null"
                                                            @click="updateDate" type="button" class="btn btn-sm btn-secondary">Save</button>
                                                </div>
                                            </template>

                                            <template v-else>
                                                <div class="col-md-5 columns mb-4">
                                                    <div class="input-group input-group-outline null is-filled ">
                                                        <label class="form-label">Date</label>
                                                        <input type="date" class="form-control form-control-default"
                                                               v-model="updateForm.nomination_delivered_at" >
                                                    </div>
                                                    <div v-if="errors.nomination_delivered_at" class="text-danger">{{ errors.nomination_delivered_at }}</div>
                                                </div>

                                                <div class="col-md-1"></div>
                                                <div class="col-md-1 columns">
                                                    <button v-if="$page.props.auth.has_slowtow_admin_role"
                                                            @click="updateDate" class="btn btn-sm btn-secondary">Save</button>
                                                </div>
                                            </template>
                                            <hr>

                                        </div>
                                    </form>
                                </div>
                            </div>
                            <hr class="vertical dark" />
                        </div>

                    </div>

                    <Task
                        :tasks="tasks"
                        :model_id="nomination.id"
                        :success="success"
                        :error="error"
                        :errors="errors"
                        :model_type="'Nomination'"
                    />

                </div>
            </div>
        </div>

        <div v-if="show_modal" class="modal fade" id="pop-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Select person(s) To Nominate</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form @submit.prevent="saveNominneesToDatabase">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12 columns">
                                    <div class="input-group input-group-outline null is-filled">
                                        <Multiselect
                                            v-model="nomineeForm.selected_nominess"
                                            placeholder="Search..."
                                            mode="tags"
                                            :options="options"
                                            :searchable="true"
                                        />
                                    </div>
                                    <div v-if="errors.selected_nominess" class="text-danger">{{ errors.selected_nominess }}</div>
                                </div>
                            </div>

                            <div class="col-md-12" v-if="message">
                                <div class="alert text-white alert-success alert-dismissible fade show font-weight-light" role="alert">
                                    <span class="alert-icon"><i class=""></i></span>
                                    <span class="alert-text">
                                    <span class="text-sm">{{ message }}</span></span>
                                    <button type="button" class="btn-close d-flex justify-content-center align-items-center"
                                            data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true" class="text-lg font-weight-bold">×</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-secondary" :disabled="nomineeForm.processing">
                                <span v-if="nomineeForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </Layout>
</template>


