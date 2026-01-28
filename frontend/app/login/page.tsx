import GuestLayout from "@/layouts/guest.layout";
import { Roboto_Condensed} from "next/font/google";
import LoginForm from "@/components/layouts/login.form";

const robotoCondensed = Roboto_Condensed({
    subsets: ['latin']
})

export default function Login(){
    return (
        <GuestLayout>
            <div className="w-full min-h-screen flex items-center justify-center">
                <div className="w-full sm:max-w-xl mx-auto flex flex-col justify-center bg-white rounded-md px-6 sm:px-10 py-12">
                    <div className="bg-white">
                        <h1 className={`text-2xl sm:text-4xl font-semibold mb-4 ${robotoCondensed.className} `}>Welcome Back</h1>
                        <p className="text-default-600">See how much you{"'"}ve earned from purchases.</p>
                    </div>
                    <LoginForm/>
                </div>
            </div>
        </GuestLayout>
    )
}