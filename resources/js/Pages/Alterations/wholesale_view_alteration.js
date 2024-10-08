import Layout from "../../Shared/Layout.vue";
import Multiselect from "@vueform/multiselect";
import { Link, useForm, Head } from "@inertiajs/inertia-vue3";
import Banner from "../components/Banner.vue";
import { Inertia } from "@inertiajs/inertia";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import Task from "../Tasks/Task.vue";
import DocComponent from "../components/slotow-components/DocComponent.vue";
import StageComponent from "../components/slotow-components/StageComponent.vue";
import MergeDocumentComponent from "../components/slotow-components/MergeDocumentComponent.vue";
import DateComponent from "../components/slotow-components/DateComponent.vue";
import LinkComponent from "../New Applications/LinkComponent.vue";
import useAlteration from "./composables/useAlteration.js";
import useToaster from '../../store/useToaster';
import AdditionalDocsComponent from "../components/slotow-components/AdditionalDocsComponent.vue";

export default {
    name: "ViewAlteration",
    props: {
        errors: Object,
        alteration: Object,
        success: String,
        error: String,
        tasks: Object,

    },
    setup(props) {
        let showMenu = false;

        const { getWholesaleBadgeStatus } = useAlteration();
        const { notifySuccess, notifyError } = useToaster();

        const form = useForm({
            licence_slug: props.alteration.licence.slug,
            slug: props.alteration.slug,
            status: [],
            unChecked: false,
            prevStage: null
        });


        function getStatus(status) {
            return getWholesaleBadgeStatus(status);
        }



        function update() {
            form.patch(`/update-alteration`, {
                onStart: () => {
                    setTimeout(() => {
                        toast.remove();
                    }, 3000);
                    toast.loading("Updating stage...");
                },
                onSuccess: () => {
                    if (props.success) {
                        notifySuccess(props.success);
                    } else if (props.error) {
                        notifyError(props.error);
                    }
                },
            });
        }

        function deleteAlteration(
            slug,
            licence_slug = props.alteration.licence.slug
        ) {
            if (confirm("Are you sure you want to delete this alteration?")) {
                Inertia.delete(
                    `/delete-altered-licence/${slug}/${licence_slug}`
                );
            }
        }

        function abandon(slug) {
            if (confirm("Are you sure you want to abandon this additional depot/relocation?")) {
                Inertia.post(`/abandon-alteration/${slug}`);
                notifySuccess('Additional depot/relocation abandoned successfully');
            }
        }

        function updateDate() {
            form.patch(`/update-alteration-date/${props.alteration.slug}`, {
                preserveScroll: true,
                onSuccess: () => {
                    if (props.success) {
                        notifySuccess(props.success);
                    } else if (props.error) {
                        notifyError(props.error);
                    }
                },
            });
        }



        function pushData(e, status_value,prevStage) {
            if (e.target.checked) {
                form.status[0] = status_value;
                form.unChecked = false;
            } else if (!e.target.checked) {
                form.unChecked = true;
                form.status[0] = status_value;
            }
            form.prevStage = prevStage;
            update();
        }

        function hasFile(doc_type) {
            if (!props.alteration.documents) {
                return {};
            } else {
                let alteration_documents = props.alteration.documents;

                const foundDocument = alteration_documents.find(
                    (doc) =>
                        doc.alteration_id === props.alteration.id &&
                        doc.doc_type === doc_type &&
                        doc.path &&
                        doc.document_name &&
                        doc.id
                );

                if (foundDocument) {
                    return {
                        fileName: foundDocument.document_name,
                        docPath: foundDocument.path,
                        id: foundDocument.id,
                    };
                } else {
                    return {};
                }
            }
        }

        function getAlterationDate(alteration_id, stage) {

            if (!props.alteration.dates) {
                return {}; // Return an empty object if props.alteration.dates doesn't exist
            } else {
                let alteration_dates = props.alteration.dates;

                const dateFound = alteration_dates.find(date =>
                    date.alteration_id === props.alteration.id &&
                    date.stage === stage
                );

                if (dateFound) {
                    return {
                        dated_at: dateFound.dated_at
                    };
                } else {
                    return {}; // Return an empty object if no date satisfies the conditions
                }
            }
        }

        function updateAlterationDate(form_data){
            form_data.patch(`/update-alteration-date/${props.alteration.id}`, {
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

        function submitDocument(form_data){
            form_data.post('/submit-alteration-document', {
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

        function deleteDocument(id,prevStage){
            if(confirm('Document will be deleted...Continue ??')){
                Inertia.delete(`/delete-alteration-document/${id}/${prevStage}`, {
                    onSuccess: () => {
                        if(props.success){
                            notifySuccess(props.success)
                        }else if(props.error){
                            notifyError(props.error)
                        }
                    },
                })
            }
        }




        function hasAllMergeDocs(){
            let baseDocs = ['NLA 14 Form','Proof of Payment','Annual Turnover Sheet','ID Documents','Police Clearance Certicates',
                'Contribution Certificate','Tax Clearance Certificates','BEE Certificate','CIPC Certificate','Zoning and LAA Certificate',
                'Latest NLA 33 Certificate'
            ]
            const allDocTypesPresent = baseDocs.every(docType => {
                return props.alteration.documents.some(document => document.doc_type === docType);
            });

            if (allDocTypesPresent) {
                return baseDocs.length;
            } else {
                return 0;
            }
        }

        function mergeDocuments(){

            Inertia.post(`/merge-wholesale-alteration-documents/${props.alteration.id}`, {
                onSuccess: () => {
                    if(props.success){
                        notifySuccess(props.success)
                    }else if(props.error){
                        notifyError(props.error)
                    }
                },
            })
            
        }

        const redirect = (licence) => {
            let url = '';
          if(licence.type == 'retail'){
             url = `/view-licence?slug=${licence.slug}`
          }else{
             url = `/view-wholesale-licence?slug=${licence.slug}`
          }
            Inertia.get(url);
          }

        return {
            form,hasAllMergeDocs,
            redirect,
            updateAlterationDate,
            showMenu,mergeDocuments,
            updateDate,
            update,
            abandon,
            pushData,
            hasFile,
            getAlterationDate,
            deleteAlteration,
            getStatus,
            submitDocument,
            deleteDocument
        };
    },

    components: {
        Layout,
        Multiselect,
        Link,
        Banner,
        Head,
        DocComponent,
        StageComponent,
        MergeDocumentComponent,
        DateComponent,
        AdditionalDocsComponent,
        LinkComponent,
        Task,
    },
    beforeUnmount() {
        this.$store.state.isAbsolute = false;
    },
};
