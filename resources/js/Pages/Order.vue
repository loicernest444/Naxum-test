<script>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head } from "@inertiajs/inertia-vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from '@/components/DangerButton.vue';
import Pagination from '@/Components/Pagination.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import pickBy from 'lodash/pickBy';
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import { useForm, usePage } from '@inertiajs/inertia-vue3';

export default {
    components: {
        GuestLayout,
        PrimaryButton,
        SecondaryButton,
        DangerButton,
        Pagination,
        TextInput,
        Link,
    },
    props: {
        orders: {
            type: Object,
            default: () => ({}),
        },
        filters: Object,
    },
    setup() {
        // const form = useForm({
        //     search: this.filters.search,
        //     trashed: this.filters.trashed,
        // });
    },
    data() {
        return {
            form: {
                search: this.filters.search,
                dateFrom: this.filters.dateFrom,
                dateTo: this.filters.dateTo,
            },
        }
    },
    watch: {
        form: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get('/', pickBy(this.form), { preserveState: true })
            }, 150),
        },
    },
    methods: {
        reset() {
            this.form = mapValues(this.form, () => null)
        },
        destroy(id) {
            if (confirm("Are you sure you want to Delete")) {
                this.$inertia.delete(route('contacts.destroy', id));
            }
        }
    },
    mounted() {
        console.log(this.orders[0])
    }
}


</script>

<template>
    <GuestLayout title="Dashboard">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Order list
        </h2>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    v-if="$page.props.flash && $page.props.flash.message"
                    class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                    role="alert"
                >
                    <span class="font-medium">
                        {{ $page.props.flash.message }}
                    </span>
                </div>
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-2 flex flex-row justify-between">
                            <div class="flex space-x-2">
                                <div class="flex flex-row space-x-1">
                                    <div class="flex flex-row">
                                        <label for="datefrom">Date From: </label>
                                        <input v-model="form.dateFrom" class="h-8" type="date" name="dateFrom" id="datefrom">
                                    </div>
                                    <div class="flex flex-row">
                                        <label for="dateto">Date To: </label>
                                        <input v-model="form.dateTo" class="h-8" type="date" name="dateTo" id="dateto">
                                    </div>
                                    <SecondaryButton @click="reset" class="h-8">Reset</SecondaryButton>
                                </div>
                            </div>
                            <div>
                                <TextInput
                                    id="search"
                                    v-model="form.search"
                                    type="text"
                                    class="mt-1 block w-full"
                                    placeholder="Search"
                                />
                            </div>
                        </div>
                        <div
                            class="relative overflow-x-auto shadow-md sm:rounded-lg"
                        >
                            <table
                                class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
                            >
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                                >
                                <tr>
                                    <th scope="col" class="px-6 py-3">#</th>
                                    <th scope="col" class="px-6 py-3">
                                        Invoice
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Purchaser
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Distributor
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Referred Distributors
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Order Date
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Order Total
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Percentage
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Commission
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr
                                    v-for="order in orders.data"
                                    :key="order.id"
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                                >
                                    <th
                                        scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                    >
                                        {{ order.id }}
                                    </th>
                                    <th
                                        scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                    >
                                        {{ order.invoice_number }}
                                    </th>
                                    <th
                                        scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                    >
                                        {{ order.purchaser.name }}
                                    </th>
                                    <th
                                        scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                    >
                                        {{ order.distributor?order.distributor.name:'-//-' }}
                                    </th>
                                    <th
                                        scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                    >
                                        {{ order.referred_distributors }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ order.order_date }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ order.order_total }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ order.percentage?order.percentage+'%':'' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ order.commission }}
                                    </td>


                                    <!--                                    <td class="px-6 py-4">-->
                                    <!--                                        <Link-->
                                    <!--                                            :href="-->
                                    <!--                                                    route(-->
                                    <!--                                                        'contacts.edit',-->
                                    <!--                                                        contact.id-->
                                    <!--                                                    )-->
                                    <!--                                                "-->
                                    <!--                                            class="px-4 py-2 text-white bg-blue-600 rounded-lg" >Edit</Link-->
                                    <!--                                        >-->
                                    <!--                                    </td>-->
                                    <!--                                    <td class="px-6 py-4">-->
                                    <!--                                        <DangerButton-->
                                    <!--                                            class="ml-3"-->
                                    <!--                                            :class="{ 'opacity-25': form.processing }"-->
                                    <!--                                            :disabled="form.processing"-->
                                    <!--                                            @click="destroy(contact.id)"-->
                                    <!--                                        >-->
                                    <!--                                            Delete-->
                                    <!--                                        </DangerButton>-->
                                    <!--                                    </td>-->
                                </tr>
                                <tr v-if="orders.data.length === 0">
                                    <td class="px-6 py-4 border-t text-center" colspan="11">No orders found.</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <Pagination :data="orders"/>
                    </div>
                </div>
            </div>
        </div>


    </GuestLayout>

</template>
