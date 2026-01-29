"use client";

import usePurchase from "@/hooks/user.purchase";
import {useEffect} from "react";

export default function CancelPaymentComponent({reference}: {reference: string}) {
    const {cancelPayment, isSubmitting} = usePurchase();

    useEffect(() => {
        cancelPayment(reference);
    }, [reference])

    return (
        <div>
            <div className="flex items-center justify-center min-h-screen">
                <div className="w-full sm:max-w-md mx-auto flex flex-col items-center justify-center">
                    {
                        isSubmitting && (<div className="w-24 h-24 rounded-full border-t-2 border-r-2 animate-spin border-danger"></div>)
                    }
                    <h1 className="text-2xl md:text-4xl font-semibold mt-5">Cancelling Payment</h1>
                </div>
            </div>
        </div>
    )
}