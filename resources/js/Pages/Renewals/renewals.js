import Layout from "../../Shared/Layout.vue";
import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import LiquorBoardRequest from "../components/LiquorBoardRequest.vue";
import Banner from '../components/Banner.vue';
import Task from "../Tasks/Task.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import { ref,onMounted } from 'vue';
import StageComponent from "@/Pages/components/slotow-components/StageComponent.vue";
import DocComponent from "@/Pages/components/slotow-components/DocComponent.vue";
import DateComponent from "@/Pages/Renewals/Components/DateComponent.vue";

export default {
    props: {
        liqour_board_requests: Object,
        tasks: Object,
        errors: Object,
        licence_dropdowns: Object,
        licence: Object,
        success: String,
        error: String,
        renewal: Object,
        liqour_board: Object
    },

    setup (props) {
        const year = ref(new Date().getFullYear());


        const form = useForm({
            year: props.renewal.date,
            licence_id: props.renewal.licence_id,
            status: [],
            unChecked: false,
            client_paid_at: props.renewal.client_paid_at,
            payment_to_liquor_board_at: props.renewal.payment_to_liquor_board_at,
            renewal_issued_at: props.renewal.renewal_issued_at,
            renewal_delivered_at: props.renewal.renewal_delivered_at,
            renewal_id: props.renewal.id,
            client_invoiced_at: props.renewal.client_invoiced_at
        })

        function hasFile(doc_type) {
            if (!props.renewal.renewal_documents) {
                return {}; // Return an empty object if props.licence.documents doesn't exist
            } else {
                let licence_documents = props.renewal.renewal_documents; // Object with all licence docs
                const foundDocument = licence_documents.find(doc =>
                    doc.licence_renewal_id === props.renewal.id &&
                    doc.doc_type === doc_type &&
                    doc.document &&
                    doc.id
                );



                if (foundDocument) {
                    return {
                        fileName: foundDocument.document_name,
                        docPath: foundDocument.document,
                        id: foundDocument.id
                    };
                } else {
                    return {}; // Return an empty object if no document satisfies the conditions
                }
            }
        }



        function deleteDocument(id){
            if(confirm('Document will be deleted...Continue ??')){
                Inertia.delete(`/delete-renewal-document/${id}`, {
                    onSuccess: () => {
                        if(props.success){
                            notify(props.success)
                        }else if(props.error){
                            notify(props.error)
                        }
                    }
                })
            }
        }

        function limit(string='', limit = 25) {
            if(string){
                if(string.length >= limit){
                    return string.substring(0, limit) + '...'
                }
                return string.substring(0, limit)
            }
        }

        function submitDocument(uploadDoc){
            uploadDoc.post('/submit-renewal-documents', {
                preserveScroll: true,
                onSuccess: () => {

                    if(props.success){
                        notify(props.success)
                    }else if(props.error){
                        notify(props.error)
                    }
                    uploadDoc.reset();
                },
            })
        }

        function updateRenewal() {
            form.patch('/update-renewal', {
                preserveScroll: true,
                onSuccess: () => {
                    if(props.success){
                        notify(props.success)
                    }else if(props.error){
                        notify(props.error)
                    }
                }
            })
        }

        function getRenewalYear(date){
            let computed_date = new Date(date).getFullYear();
            return computed_date + 1;
        }

        function deleteRenewal(){
            if(confirm('Are you sure you want to delete this licence renewal??')){
                Inertia.delete(`/delete-licence-renewal/${props.renewal.licence.slug}/${props.renewal.slug}`)
            }
        }



        function pushData(e,status_value, prevStage){
            if (e.target.checked) {
                form.status[0] = status_value;
                form.unChecked = false;
            }else if(!e.target.checked){
                form.unChecked = true
                form.status[0] = status_value;
            }
            updateRenewal();
        }



        let file_has_apostrophe = ref();
        function getFileName(e){
            this.uploadDoc.document = e.target.files[0];
            this.file_name = e.target.files[0].name;
            this.file_has_apostrophe = this.file_name.includes("'");
        }

        const notify = (message) => {
            if(props.success){
                toast.success(message, {
                    autoClose: 2000,
                });
                props.success='';
                props.error=''
            }else if(props.error){
                toast.error(message, {
                    autoClose: 2000,
                });
            }

        }

        function checkingFileProgress(message){
            setTimeout(() => {
                toast.remove();
            }, 3000);
            toast.loading(message);
        }



        function viewFile(model_id) {
            let model = 'RenewalDocument';
            Inertia.visit(`/view-file/${model}/${model_id}`,{
                replace: true,
                onStart: () => {
                    checkingFileProgress('Checking file availability...')
                },

            })
        }

        function getStatus(status_param) {
            let status;

            switch (status_param) {
                case '100':
                    status = 'Client Quoted'
                    break;
                case '200':
                    status = 'Client Invoiced'
                    break;
                case '300':
                    status = 'Client Paid'
                    break;
                case '400':
                    status = 'Payment To The Liquor Board'
                    break;
                case '500':
                    status = 'Renewal Issued'
                    break;
                case '600':
                    status = 'Renewal Delivered'
                    break;
                default:
                    status='Not Set';
                    break;
            }
            return status;
        }

        function getRenewalDate(licence_id, stage){

            return { }

        }

        return { year,form, notify,
            updateRenewal,getStatus,
            getRenewalYear, pushData, submitDocument,
            deleteDocument,
            deleteRenewal,
            getRenewalDate,
            limit,toast,viewFile,checkingFileProgress,
            hasFile
        }
    },
    components: {
        DateComponent,
        DocComponent,
        StageComponent,
        Layout,
        Link,
        Head,
        Datepicker,
        LiquorBoardRequest,
        Banner,
        Task
    },

};


