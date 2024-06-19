import Layout from "../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';
import { Link,useForm, Head } from '@inertiajs/inertia-vue3';
import { ref,onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import LiquorBoardRequest from "../components/LiquorBoardRequest.vue";
import Banner from '../components/Banner.vue';
import LinkComponent from "../New Applications/LinkComponent.vue";
import Task from "../Tasks/Task.vue";
import DocComponent from "../components/slotow-components/DocComponent.vue";
import StageComponent from "../components/slotow-components/StageComponent.vue";
import PrepareTransferApplicationComponent from './Transfers/PrepareTransferApplicationComponent.vue';
import MergeDocumentComponent from "../components/slotow-components/MergeDocumentComponent.vue";
import DateComponent from "./Transfers/DateComponent.vue";
import NoneUploadComponent from './Transfers/NoneUploadComponent.vue';
import AdditionalDocsComponent from '../components/slotow-components/AdditionalDocsComponent.vue';

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import useToaster from '../../store/useToaster';
import useTransferStatus from '../../store/useTransferStatus'

export default {
    props: {
        liqour_board_requests: Object,
        errors: Object,
        view_transfer: Object,
        original_licence: Object,
        success: String,
        error: String,
        tasks: Object
    },

    setup (props) {
        let showMenu = false;
        const { notifySuccess, notifyError } = useToaster();
        const { getPlainStatus } = useTransferStatus();

        const form = useForm({

            trading_name: props.view_transfer.licence.trading_name,
            new_company: props.view_transfer.company_id,
            transfer_date: props.view_transfer.date,
            lodged_at: props.view_transfer.lodged_at,
            activation_fee_paid_at: props.view_transfer.activation_fee_paid_at,
            issued_at: props.view_transfer.issued_at,
            delivered_at: props.view_transfer.delivered_at,
            payment_to_liquor_board_at: props.view_transfer.payment_to_liquor_board_at,
            slug: props.view_transfer.slug,
            licence_id: props.view_transfer.id,
            status: [],
            unChecked: false,
        })

        const documentsForm = useForm({
            document_type: '',
            belong_to: '',
            document_number: '',
            document: null,
            stage: null
        })

        const mergeForm = useForm({
            transfer_id: props.view_transfer.id,
            latest_renewal: props.latest_renewal,
            original_licence: props.original_licence,
            licence_id: props.view_transfer.licence.id
        })

        function mergeDocuments(){
            mergeForm.post(`/transfer-documents-merge`, {
                preserveScroll: true,
                onSuccess: () => {
                    if(props.success){
                        notifySuccess(props.success)
                    }else if(props.error){
                        notifyError(props.error)
                    }
                }
            })
        }


        function submitDocument(form_data){ console.log(form_data, "form data");
            form_data.post(`/submit-transfer-documents/${props.view_transfer.id}`, {
                preserveScroll: true,
                onSuccess: () => {
                    if(props.success){
                        notifySuccess(props.success)
                    }else if(props.error){
                        notifyError(props.error)
                    }

                },
            })
        }



        function pushData(e,status_value, prevStage) {

            let dateForm = useForm({
                status: status_value,
                prevStage: prevStage,
                checked: e.target.checked,
                slug: props.view_transfer.slug
            })

            dateForm.patch(`/update-licence-transfer`, {
                preserveScroll: true,
                onSuccess: () => {
                    if(props.success){
                        notifySuccess(props.success)
                    }else if(props.error){
                        notifyError(props.error)
                    }
                },
            })
        }



        function deleteDocument(id){
            if(confirm('Document will be deleted permanently...Continue ??')){
                Inertia.delete(`/delete-transfer-document/${id}`, {
                    onSuccess: () => {
                        if(props.success){
                            notifySuccess(props.success)
                        }else if(props.error){
                            notifyError(props.error)
                        }
                    }
                })
            }
        }

        function hasMergeFile(doc_type, belong_to) {
            if (!props.view_transfer.transfer_documents) {
                return {};
            } else {
                let transfer_documents = props.view_transfer.transfer_documents;

                const foundDocument = transfer_documents.find(
                    (doc) =>
                        doc.belongs_to === belong_to &&
                        doc.licence_transfer_id === props.view_transfer.id &&
                        doc.doc_type === doc_type &&
                        doc.document &&
                        doc.document_name &&
                        doc.id
                );

                if (foundDocument) {
                    return {
                        fileName: foundDocument.document_name,
                        docPath: foundDocument.document,
                        id: foundDocument.id,
                    };
                } else {
                    return {};
                }
            }
        }

        function hasFile(doc_type) {
            if (!props.view_transfer.transfer_documents) {
                return {};
            } else {
                let transfer_documents = props.view_transfer.transfer_documents;

                const foundDocument = transfer_documents.find(
                    (doc) =>
                        doc.licence_transfer_id === props.view_transfer.id &&
                        doc.doc_type === doc_type &&
                        doc.document &&
                        doc.document_name &&
                        doc.id
                );

                if (foundDocument) {
                    return {
                        fileName: foundDocument.document_name,
                        docPath: foundDocument.document,
                        id: foundDocument.id,
                    };
                } else {
                    return {};
                }
            }
        }



        function deleteTransfer() {
            if(confirm('Are you sure??')){
                Inertia.delete(`/delete-licence-transfer/${props.view_transfer.slug}/${props.view_transfer.licence.slug}`)
            }
        }


        function updateStageDate(form_data){

            form_data.patch(`/update-transfer-date/${props.view_transfer.slug}`, {
                preserveScroll: true,
                onSuccess: () => {
                    if(props.success){
                        notifySuccess(props.success)
                    }else if(props.error){
                        notifyError(props.error)
                    }
                }
            })
        }


        function getStatus(statusParam) {
            return getPlainStatus(statusParam);
        }

        function canMerge() {
            //find documents with num != null
            let docsNum = props.view_transfer.transfer_documents.filter((doc) => doc.num !== null);
            return docsNum?.length >= 18 ? true : false;
        }

        function getDate(transfer_id, stage){

            if(!props.view_transfer.dates){
                return {}; // Return an empty object if props.licence.dates doesn't exist
            } else {
                let transfer_dates = props.view_transfer.dates;

                const dateFound = transfer_dates.find(date =>
                    date.transfer_id === transfer_id  &&
                    date.stage === stage
                );

                if (dateFound) {

                    return {
                        dated_at: dateFound.dated_at
                    };

                } else {

                    return {};
                }
            }

        }

        return {
            canMerge,
            updateStageDate,
            getStatus,
            pushData,
            hasMergeFile,
            hasFile,
            mergeForm,
            mergeDocuments,
            submitDocument,
            form,
            deleteDocument,
            deleteTransfer,
            toast,
            getDate
        }
    },
    components: {
        Layout,
        LinkComponent,
        DocComponent,
        PrepareTransferApplicationComponent,
        NoneUploadComponent,
        StageComponent,
        MergeDocumentComponent,
        DateComponent,
        Multiselect,
        Link,
        Head,
        AdditionalDocsComponent,
        LiquorBoardRequest,
        Banner,
        Task
    },
    beforeUnmount() {
        this.$store.state.isAbsolute = false;
    },
};
