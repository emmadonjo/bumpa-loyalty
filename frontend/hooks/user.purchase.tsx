import {useState} from "react";
import http from "@/libs/http";
import {AxiosError} from "axios";
import {addToast} from "@heroui/react";

const usePurchase = () => {
    const [isSubmitting, setIsSubmitting] = useState<boolean>(false);

    const simulatePurchase = async (
        onSuccess: () => void,
    ) => {
        setIsSubmitting(true);
        try {
            const response = await http.post(`/api/users/purchase`);
            addToast({
                description: response.data.message ?? 'Purchase successful',
                color: 'success',
            });
            onSuccess();
        }catch (e) {
            const error = e as AxiosError;
            const message = error.response
                ? (error.response.data as { message?: string }).message
                : 'Customers could not be retrieved.';
            addToast({description: message, color: 'danger'});
        }finally {
            setIsSubmitting(false);
        }

    }

    return {
        isSubmitting,
        simulatePurchase,
    }
}

export default usePurchase;
