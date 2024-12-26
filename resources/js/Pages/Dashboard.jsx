import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function Dashboard() {

    

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Dashboard
                </h2>
            }
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="">
                    <div className="overflow-hidden ">
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            you are in the admin dashboard
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}