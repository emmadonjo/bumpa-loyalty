"use client";

import {useState, SubmitEvent} from "react";
import usePurchase from "@/hooks/user.purchase";
import Button from "@/components/ui/button.ui";

export default function SimulatePurchase(){
    const [isSuccess, setIsSuccess] = useState<boolean>(false);
    const {isSubmitting, simulatePurchase} = usePurchase();

    const onSubmit = async (e: SubmitEvent) => {
        e.preventDefault();
    }
    return (
        <div>
            <form onSubmit={onSubmit} className="flex items-center justify-center w-full">
                <Button
                    type="submit"
                    isDisabled={isSubmitting}
                    isLoading={isSubmitting}
                >
                    Simulate Purchase
                </Button>
            </form>
        </div>
    )
}