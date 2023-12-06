import Layout from "../../Shared/Layout.vue";
import Multiselect from "@vueform/multiselect";
import { Link, useForm, Head } from "@inertiajs/inertia-vue3";
import Banner from "../components/Banner.vue";
import { Inertia } from "@inertiajs/inertia";
import { ref, onMounted } from "vue";
import Task from "../Tasks/Task.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import DocComponent from "../components/slotow-components/DocComponent.vue";
import StageComponent from "../components/slotow-components/StageComponent.vue";
import MergeDocumentComponent from "../components/slotow-components/MergeDocumentComponent.vue";
import DateComponent from "../components/slotow-components/DateComponent.vue";

export default {
    name: "ViewDuplicate",
    props: {
        errors: Object,
        duplicate_original: Object,
        success: String,
        error: String,
        tasks: Object,
       
    },
    setup(props) {
        let showMenu = false;
        const form = useForm({
            slug: props.duplicate_original.slug,
            status: [],
            unChecked: false,
            prevStage: null
        });

       

        function update() {
            form.patch(`/update-duplicate_original`, {
                onStart: () => {
                    setTimeout(() => {
                        toast.remove();
                    }, 3000);
                    toast.loading("Updating stage...");
                },
                onSuccess: () => {
                    if (props.success) {
                        notify(props.success);
                    } else if (props.error) {
                        notify(props.error);
                    }
                },
            });
        }

        function deleteDuplicateOriginal() {
            if (confirm("Are you sure you want to delete this duplicate_original?")) {
                Inertia.delete(
                    `/delete-altered-licence/${slug}/${licence_slug}`
                );
            }
        }

        function updateDate() {
            form.patch(`/update-duplicate_original-date/${props.duplicate_original.slug}`, {
                preserveScroll: true,
                onSuccess: () => {
                    if (props.success) {
                        notify(props.success);
                    } else if (props.error) {
                        notify(props.error);
                    }
                },
            });
        }

        const notify = (message) => {
            if (props.success) {
                toast.success(message, {
                    autoClose: 2000,
                });
            } else if (props.error) {
                toast.error(message, {
                    autoClose: 2000,
                });
            }
        };

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
            if (!props.duplicate_original.duplicate_documents) {
                return {}; 
            } else {
                let duplicate_original_documents = props.duplicate_original.duplicate_documents; 

                const foundDocument = duplicate_original_documents.find(
                    (doc) =>
                        doc.duplicate_original_id === props.duplicate_original.id &&
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

        function updateDate(form_data){
          form_data.patch(`/update-duplicate_original-date/${props.duplicate_original.id}`, {
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

        function submitDocument(form_data){
            form_data.post('/submit-duplicate_original-document', {
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
          if(confirm('Document will be deleted...Continue ??')){
            Inertia.delete(`/delete-duplicate_original-document/${id}`, {
              onSuccess: () => { 
               if(props.success){
                  notify(props.success)
                }else if(props.error){
                  notify(props.error)
                }
              },
            })
          }
        }

        function mergeDocuments(){alert('Cool')
            Inertia.post(`/merge-duplicate-documents/${props.duplicate_original.id}`, {
                    //
            })
          }
        
        return {
            form,
            // updateduplicateDate,
            showMenu,mergeDocuments,
            updateDate,
            update,
            pushData,
            hasFile,
            toast,
            deleteDuplicateOriginal,
            notify,
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
        Task,
    },
    beforeUnmount() {
        this.$store.state.isAbsolute = false;
    },
};
