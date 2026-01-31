"use client";

import {SubmitEvent, useMemo} from "react";
import usePurchase from "@/hooks/user.purchase";
import Button from "@/components/ui/button.ui";
import {money, randomInt} from "@/helpers";

export default function SimulatePurchase(){
    const {isSubmitting, simulatePurchase} = usePurchase();

    const amount = useMemo(() => randomInt(100, 5000), []);

    const onSubmit = async (e: SubmitEvent) => {
        e.preventDefault();
        simulatePurchase(amount,(checkoutUrl: string) => {
            window.location.href = checkoutUrl;
        })
    }
    return (
        <div>
            <form onSubmit={onSubmit} className="flex items-center justify-center w-full" id="purchaseForm">
                <Button
                    type="submit"
                    isDisabled={isSubmitting}
                    isLoading={isSubmitting}
                >
                    Purchase ({money(amount)})
                </Button>
            </form>
        </div>
    )
}