import GeneralLayout from "@/layouts/general.layout";
import CancelPaymentComponent from "@/components/payments/payments.cancel";

type PageProps = {
    params: {
        reference: string;
    };
};

export default async function CancelPayment({params}: PageProps){
    const {reference} = await params;
    return (
        <GeneralLayout>
            <div className="flex items-center justify-center">
                <div className="w-full sm:max-w-md">
                    <CancelPaymentComponent reference={reference} />
                </div>
            </div>
        </GeneralLayout>
    )
}