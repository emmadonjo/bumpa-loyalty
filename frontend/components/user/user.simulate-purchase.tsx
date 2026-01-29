"use client";

import {useState, SubmitEvent} from "react";
import usePurchase from "@/hooks/user.purchase";
import Button from "@/components/ui/button.ui";
import AchievementPop from "@/components/achievement.notify.component";

export default function SimulatePurchase(){
    const [isSuccess, setIsSuccess] = useState<boolean>(false);
    const {isSubmitting, simulatePurchase} = usePurchase();

    const onSubmit = async (e: SubmitEvent) => {
        e.preventDefault();
        simulatePurchase(() => {
            setIsSuccess(true);
            setTimeout(() => {
                setIsSuccess(false);
            }, 2500);
        })
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

            {
                !isSubmitting && isSuccess && (
                   <AchievementPop title="Achievement Unlocked" description="You are definitely rocking it with this new achievement of yours" />
                )
            }
        </div>
    )
}