"use client";

import {useState} from "react";
import http from "@/libs/http";
import {AxiosError} from "axios";
import {addToast} from "@heroui/react";
import { useRouter } from "next/navigation";
import {Purchase} from "@/types";

const usePurchase = () => {
    const [isSubmitting, setIsSubmitting] = useState<boolean>(false);
    const router = useRouter();

    const simulatePurchase = async (
        amount: number,
        onSuccess: (checkoutUrl: string) => void,
    ) => {
        setIsSubmitting(true);

        try {
            const response = await http.post(`/users/purchases`, {amount });
            addToast({
                description: response.data.message ?? 'Payment link generated',
                color: 'success',
            });
            onSuccess(response.data.data.checkout_url as string);
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

    const verifyPayment = async (
        onSuccess: (purchase: Purchase) => void,
        reference: string
    ) => {
        setIsSubmitting(true);
        try {
            const response = await http.post(`/payments/${reference}/verify`);
            const data = response.data.data as Purchase;
            if (data.status === 'pending') {
                addToast({
                    description: response.data.message ?? 'Payment is still pending.',
                    color: 'success',
                });

                router.replace('/');
            } else {
                setIsSubmitting(false);
                onSuccess(data);
            }
        }catch (e) {
            const error = e as AxiosError;
            const message = error.response
                ? (error.response.data as { message?: string }).message
                : 'Customers could not be retrieved.';
            addToast({description: message, color: 'danger'});
            router.replace('/');
        }finally {
            setIsSubmitting(false);
        }
    }

    const cancelPayment = async (
        reference: string
    ) => {
        setIsSubmitting(true);
        try {
            await http.post(`/payments/${reference}/cancel`);
            addToast({description: 'Payment cancelled', color: 'secondary'});
            router.replace('/');
        }catch (e) {
            console.log(e);
        }finally {
            setIsSubmitting(false);
        }
    }

    return {
        isSubmitting,
        simulatePurchase,
        verifyPayment,
        cancelPayment,
    }
}

export default usePurchase;
