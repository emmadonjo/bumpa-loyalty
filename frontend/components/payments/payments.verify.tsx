"use client";

import usePurchase from "@/hooks/user.purchase";
import {useEffect, useState} from "react";
import {Purchase} from "@/types";
import AchievementPop from "@/components/achievement.notify.component";
import {money} from "@/helpers";
import {useRouter} from "next/navigation";

export default function VerifyPaymentComponent({reference}: {reference: string}) {
    const [isSuccess, setIsSuccess] = useState(false);
    const {verifyPayment, isSubmitting} = usePurchase();
    const [amount, setAmount] = useState<number>(0);
    const router = useRouter();

    useEffect(() => {
        verifyPayment(
            (purchase: Purchase) => {
                setIsSuccess(true);
                setAmount(purchase.amount);

                setTimeout(() => {
                    setIsSuccess(false);
                    router.replace('/');
                }, 2500);
            },
            reference
        );
    }, [reference, router])

    return (
        <div>
            <div className="flex items-center justify-center min-h-screen">
                <div className="w-full sm:max-w-md mx-auto flex flex-col items-center justify-center">
                    <div className="w-24 h-24 rounded-full border-t-2 border-r-2 animate-spin border-primary"></div>
                    <h1 className="text-2xl md:text-4xl font-semibold mt-5">Verifying Payment</h1>
                </div>
            </div>
            {
                !isSubmitting && isSuccess && (
                   <AchievementPop
                       title="Achievement Unlocked 🎉!"
                       description={`${money(amount)} payment confirmed. You are definitely rocking it with this new achievement of yours`}
                   />
                )
            }
        </div>
    )
}