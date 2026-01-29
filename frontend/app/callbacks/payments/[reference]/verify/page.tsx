import GeneralLayout from "@/layouts/general.layout";
import VerifyPaymentComponent from "@/components/payments/payments.verify";

type PageProps = {
    params: {
        reference: string;
    };
};

export default async function VerifyPayment({params}: PageProps){

    const {reference} = await params;
    return (
        <GeneralLayout>
            <div className="flex items-center justify-center">
                <div className="w-full sm:max-w-md">
                    <VerifyPaymentComponent reference={reference} />
                </div>
            </div>
        </GeneralLayout>
    )
}