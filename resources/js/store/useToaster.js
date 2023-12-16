import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

export default function useNotify() {
    // Function to display success notification
    const notifySuccess = (message) => {
        toast.success(message, { autoClose: 2000 });
    };

    // Function to display error notification
    const notifyError = (message) => {
        toast.error(message, { autoClose: 2000 });
    };

    return {
        notifySuccess,
        notifyError,
    };
}
