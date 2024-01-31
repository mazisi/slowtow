import Layout from "../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';
import { Link,useForm,Head } from '@inertiajs/inertia-vue3';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { Inertia } from '@inertiajs/inertia';
import { ref,onMounted } from "vue";
import LiquorBoardRequest from "../components/LiquorBoardRequest.vue";
import Banner from '../components/Banner.vue';
import Task from "../Tasks/Task.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import StageComponent from "@/Pages/components/slotow-components/StageComponent.vue";
import DocComponent from "@/Pages/components/slotow-components/DocComponent.vue";
import MergeNominationDocsComponent from "./MergeNominationDocsComponent.vue";
import DateComponent from "@/Pages/components/DateComponent.vue";
import MergeNominationDocs from './MergeNominationDocs.vue'
import moment from 'moment';
import useNomination from './useNomination.js';

export default{
    props:{
        errors: Object,
        tasks: Object,
        nominees: Array,
        licence: Object,
        success: String,
        error: String,
        nomination: Object,
        latest_renewal_licence_doc: Object,
        liqour_board_requests: Object
    },

    setup (props) {
        let options = props.nominees;
        let show_modal = ref(true);
        let show_file_name = ref(false);
        let file_name = ref('');
        const { getPlainStatus } = useNomination();

        const updateForm = useForm({
            nomination_year: props.nomination.year,
            nomination_id: props.nomination.id,
            client_paid_date: props.nomination.client_paid_date,
            nomination_lodged_at: props.nomination.nomination_lodged_at,
            nomination_issued_at: props.nomination.nomination_issued_at,
            nomination_delivered_at: props.nomination.nomination_delivered_at,
            payment_to_liquor_board_at: props.nomination.payment_to_liquor_board_at,
            status: [],
            unChecked: false,
        });

        const uploadDoc = useForm({
            document: null,
            doc_type: null,
            date: null,
            stage: null,
            file_name: file_name,
            nomination_id: props.nomination.id
        })


        function hasFile(doc_type) {
            if (props.nomination.nomination_documents) {
                let nomination_documents = props.nomination.nomination_documents; // Object with all licence docs
                const foundDocument = nomination_documents.find(doc =>
                    doc.nomination_id === props.nomination.id &&
                    doc.doc_type === doc_type &&
                    doc.document &&
                    doc.id
                );
                if (foundDocument) {
                    return {
                        fileName: foundDocument.document_name,
                        docPath: foundDocument.path,
                        id: foundDocument.id
                    };
                }
                    return {}; // Return an empty object if no document satisfies the conditions

            }
        }

        const nomineeForm = useForm({//This handles attachment of nominees
            selected_nominess: [],
            nomination_id: props.nomination.id
        });




        function submitDocument(formDoc){
            formDoc.post('/submit-nomination-document', {
                preserveScroll: true,
                onSuccess: () => {
                    if(props.success){
                        notify(props.success)
                    }else if(props.error){
                        notify(props.error)
                    }
                },
            })
        }

        function deleteDocument(id){
            if(confirm('Document will be deleted permanently...Continue ??')){
                Inertia.delete(`/delete-nomination-document/${id}`, {
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

        function computeDocumentDate(date_param){
            return new Date(date_param).toLocaleString().split(',')[0]
        };


        function saveNominneesToDatabase() {
            nomineeForm.post('/add-selected-nominees', {
                preserveScroll: true,
                onSuccess: () => {
                    this.show_modal = false;
                    document.querySelector('.modal-backdrop').remove();
                    if(props.success){
                        notify(props.success)
                    }else if(props.error){
                        notify(props.error)
                    }

                    nomineeForm.reset();
                },
            })
        }


        function removeSelectedNominee(nominee_id) {
            Inertia.post(`/detach-nominee/${props.nomination.id}/${nominee_id}`,{
                onSuccess: () => {
                    if(props.success){
                        notify(props.success)
                    }else if(props.error){
                        notify(props.error)
                    }
                },
            })
        }


        function updateNomination() {
            updateForm.patch(`/update-nominee`,{
                onSuccess: () => {
                    if(props.success){
                        notify(props.success)
                    }else if(props.error){
                        notify(props.error)
                    }
                },
            })
        }

        const mergeDocument = () => {
            Inertia.get(`/merge-document/${props.nomination.id}`,{
                onStart: () => {
                    checkingFileProgress('Getting your files ready...')
                }
            })
        }


        function deleteNomination() {
            if(confirm('Are you sure you want to delete this nomination??')){
                Inertia.delete(`/delete-nomination/${props.nomination.licence.slug}/${props.nomination.slug}`)
            }
        }

        function pushData(e,status_value) {
            if (e.target.checked) {
                updateForm.status[0] = status_value;
                updateForm.unChecked = false;
            }else if(!e.target.checked){
                updateForm.unChecked = true
                updateForm.status[0] = status_value;
            }
            updateNomination();
        }

        function updateDate(data){
            data.patch(`/update-nomination-date/${props.nomination.slug}`, {
                preserveScroll: true,
                onSuccess: () => {
                    if(props.success){
                        notify(props.success)
                    }else if(props.error){
                        notify(props.error)
                    }
                },
            })
        }

        let file_has_apostrophe = ref();
        function getFileName(e){
            this.show_file_name = true;
            this.uploadDoc.document = e.target.files[0];
            this.file_name = e.target.files[0].name;
            this.file_has_apostrophe = this.file_name.includes("'");
        }

        const notify = (message) => {
            if(props.success){
                toast.success(message, {
                    autoClose: 2000,
                });
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



        function getStatus(status_param) {
            return getPlainStatus(status_param);
        }

        function getMomentDate(date){
            return moment(date).format('YYYY-MM-DD');
          }

        function canMerge() {
            let baseDocs = [
              "ID Document", "Nomination Forms", "Payment To The Liquor Board",
              "Police Clearances","Power of Attorney", "Latest Renewal/Licence"
            ];

              const allDocTypesPresent = baseDocs.every(docType => {
                console.log('DocType',docType);
                return props.nomination.nomination_documents.some(document => document.doc_type === docType);
              });
              console.log('All',allDocTypesPresent);
              if (allDocTypesPresent) {
                return baseDocs.length;
              } else {
                return 0;
              }

          }


        return{getMomentDate,
            options,pushData,updateNomination,updateDate,canMerge,
            removeSelectedNominee,saveNominneesToDatabase,show_modal,file_has_apostrophe,
            computeDocumentDate,deleteDocument,submitDocument,show_file_name,nomineeForm,
            uploadDoc,updateForm,file_name,getFileName,notify,checkingFileProgress,
            deleteNomination,mergeDocument,hasFile,getStatus



        }
    },

    components: {
        DateComponent,
        DocComponent,
        StageComponent,
        MergeNominationDocsComponent,
        Layout,
        Link,
        Head,
        Multiselect,
        Datepicker,
        MergeNominationDocs,
        LiquorBoardRequest,
        Banner,
        Task
    },

    // 1= > Client Quoted
    // 2 => Client Invoiced
    // 3 => Client Paid
    // 4 => Payment to the Liquor Board
    // 5 => Select nominees
    // 6 => Prepare Nomination Application
    // 7  => Scanned Application
    // 8 => Nomination Lodged
    // 9 => Nomination Issued
    // 10 => Nomination Delivered
}
