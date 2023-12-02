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
// import DateComponent from "../components/slotow-components/DateComponent.vue";

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
        const form = useForm({
            licence_slug: props.alteration.licence.slug,
            slug: props.alteration.slug,
            status: [],
            unChecked: false,
            prevStage: null
        });

       

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
                        notify(props.success);
                    } else if (props.error) {
                        notify(props.error);
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

        function updateDate() {
            form.patch(`/update-alteration-date/${props.alteration.slug}`, {
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
            if (!props.alteration.documents) {
                return {}; // Return an empty object if props.licence.documents doesn't exist
            } else {
                let alteration_documents = props.alteration.documents; // Object with all licence docs

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
                    return {}; // Return an empty object if no document satisfies the conditions
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
                          notify(props.success)
                        }else if(props.error){
                          notify(props.error)
                        }
                        },
          })     
        }

        function submitDocument(form_data){
            form_data.post('/submit-alteration-document', {
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
            Inertia.delete(`/delete-alteration-document/${id}`, {
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

  
        

        return {
            form,
            updateAlterationDate,
            showMenu,
            updateDate,
            update,
            pushData,
            hasFile,
            toast,
            getAlterationDate,
            deleteAlteration,
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
        // DateComponent,
        Task,
    },
    beforeUnmount() {
        this.$store.state.isAbsolute = false;
    },
};
