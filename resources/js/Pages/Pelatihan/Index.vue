<template>
    <AppLayout>
        <template #breadcrumb>
            <li aria-current="page">
                <div class="flex items-center">
                    <ChevronRightIcon class="w-6 h-6 text-gray-400 mx-1" />
                    <span class="text-sm font-medium text-gray-500"
                        >Pelatihan</span
                    >
                </div>
            </li>
        </template>
        <div class="text-right">
            <Link
                v-if="hasRolesID([1])"
                :href="route('pelatihan.create')"
                class="btn btn-md btn-sky mb-3"
                ><PlusIcon class="mr-2 h-5 w-5" />Pelatihan</Link
            >
        </div>
        <div class="bg-white shadow rounded-lg border-t-2 border-sky-500 mb-10">
            <div class="px-4 py-5 border-b">
                <h2
                    class="text-lg leading-6 font-medium inline-flex items-center"
                >
                    <NewspaperIcon class="h-6 w-6 mr-2" />Daftar Pelatihan
                </h2>
            </div>
            <div class="px-4 py-2 border-b bg-gray-50">
                <div class="mt-1 flex flex-col sm:flex-row">
                    <div class="rounded-md flex">
                        <span
                            class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-sky-50 text-gray-500 text-sm"
                        >
                            Tampilkan
                        </span>

                        <select
                            @change.prevent="filters"
                            class="focus:ring-sky-500 focus:border-sky-500 w-full sm:w-auto rounded-none rounded-r-md sm:text-sm border-gray-300"
                            v-model="filter.limit"
                        >
                            <option value="10">10</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="500">500</option>
                            <option :value="list.total">Semua</option>
                        </select>
                    </div>
                    <div class="rounded-md flex pt-3 sm:pt-0 sm:ml-3">
                        <span
                            class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-sky-50 text-gray-500 text-sm"
                        >
                            Status
                        </span>

                        <select
                            @change.prevent="filters"
                            class="focus:ring-sky-500 focus:border-sky-500 w-full sm:w-auto rounded-none rounded-r-md sm:text-sm border-gray-300"
                            v-model="filter.status"
                        >
                            <option value="">Semua</option>
                            <option
                                :value="stts.slug"
                                v-for="stts in status"
                                :key="stts.slug"
                            >
                                {{ stts.name }}
                            </option>
                        </select>
                    </div>
                    <div class="rounded-md flex sm:ml-auto mt-3 sm:mt-0">
                        <input
                            @keyup.enter="filters"
                            type="text"
                            v-model="filter.search"
                            class="focus:ring-sky-500 focus:border-sky-500 w-full sm:w-auto rounded-none rounded-l-md sm:text-sm border-gray-300"
                        />
                        <button
                            type="button"
                            @click.prevent="filters"
                            class="bg-sky-500 rounded-none rounded-r-md px-2 text-white hover:bg-sky-600 focus:outline-none"
                        >
                            <MagnifyingGlassIcon class="h-5 w-5" />
                        </button>
                    </div>
                </div>
            </div>
            <div class="overflow-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="text-left th">No.</th>
                            <th scope="col" class="text-left th">Judul</th>
                            <th scope="col" class="text-left th">
                                Tanggal Mulai
                            </th>
                            <th scope="col" class="text-left th">
                                Tanggal Selesai
                            </th>
                            <th scope="col" class="text-left th">Kuota</th>
                            <th scope="col" class="text-center th">Publish</th>
                            <th scope="col" class="text-left th">Status</th>
                            <th scope="col" class="th">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-if="list.total == 0">
                            <td colspan="8" class="text-center px-3 py-2">
                                Kosong
                            </td>
                        </tr>
                        <tr
                            v-else
                            v-for="(item, index) in list.data"
                            :key="item.id"
                        >
                            <td class="td">
                                {{
                                    (list.current_page - 1) * list.per_page +
                                    index +
                                    1
                                }}
                            </td>
                            <td class="td">
                                {{ item.judul }}
                            </td>

                            <td class="td">
                                {{ dateIndo(item.tgl_mulai) }}
                            </td>
                            <td class="td">
                                {{ dateIndo(item.tgl_selesai) }}
                            </td>
                            <td class="td">
                                {{ item.kuota }}
                            </td>
                            <td class="td flex items-center justify-center">
                                <CheckCircleIcon
                                    class="h-6 w-6 text-teal-500"
                                    v-if="item.is_publish"
                                />
                                <MinusCircleIcon
                                    class="h-6 w-6 text-rose-500"
                                    v-else
                                />
                            </td>
                            <td class="td whitespace-nowrap">
                                <span
                                    class="px-2 py-0.5 font-semibold text-xs tracking-wider rounded-full shadow"
                                    :class="{
                                        'bg-sky-200 text-sky-900 shadow-sky-500':
                                            item.status_id == 1,
                                        'bg-yellow-200 text-yellow-900  shadow-yellow-500':
                                            item.status_id == 2 ||
                                            item.status_id == 4 ||
                                            item.status_id == 6 ||
                                            item.status_id == 8 ||
                                            item.status_id == 9 ||
                                            item.status_id == 11,
                                        'bg-red-200 text-red-900 shadow-red-500':
                                            item.status_id == 3 ||
                                            item.status_id == 5 ||
                                            item.status_id == 7 ||
                                            item.status_id == 10 ||
                                            item.status_id == 12,
                                        'bg-teal-200 text-teal-900 shadow-teal-500':
                                            item.status_id == 13,
                                    }"
                                    >{{ item.status.name }}</span
                                >
                            </td>

                            <td
                                valign="top"
                                class="td whitespace-nowrap text-center"
                            >
                                <Link
                                    title="Detail"
                                    :href="route('pelatihan.show', item.slug)"
                                    class="ml-1 btn btn-sm btn-teal"
                                >
                                    <EyeIcon class="h-5 w-5" />
                                </Link>
                                <Link
                                    title="Ubah"
                                    :href="route('pelatihan.edit', item.slug)"
                                    class="ml-1 btn btn-sm btn-yellow"
                                    v-if="
                                        item.created_by ==
                                            $page.props.auth.user.id &&
                                        (item.status_id == 1 ||
                                            item.status_id == 3)
                                    "
                                >
                                    <PencilSquareIcon class="h-5 w-5" />
                                </Link>
                                <button
                                    title="Hapus"
                                    type="button"
                                    @click="deleteItem(item.slug)"
                                    class="ml-1 btn btn-sm btn-red"
                                    v-if="
                                        item.created_by ==
                                            $page.props.auth.user.id &&
                                        (item.status_id == 1 ||
                                            item.status_id == 3)
                                    "
                                >
                                    <TrashIcon class="h-5 w-5" />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div
                class="border-t flex flex-col sm:flex-row items-center justify-between p-4"
            >
                <div>
                    Menampilkan {{ list.data.length }} dari
                    {{ list.total }} Data
                </div>
                <Pagination :links="list.links" :only="['filter', 'list']" />
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/Admin.vue";
import Pagination from "@/Components/Pagination.vue";
import { Link } from "@inertiajs/inertia-vue3";
import {
    PencilSquareIcon,
    TrashIcon,
    PlusIcon,
    MagnifyingGlassIcon,
    NewspaperIcon,
    ChevronRightIcon,
    HomeIcon,
    CheckCircleIcon,
    MinusCircleIcon,
    EyeIcon,
} from "@heroicons/vue/24/solid";
export default defineComponent({
    components: {
        AppLayout,
        Pagination,
        Link,
        PencilSquareIcon,
        TrashIcon,
        PlusIcon,
        MagnifyingGlassIcon,
        NewspaperIcon,
        ChevronRightIcon,
        HomeIcon,
        CheckCircleIcon,
        MinusCircleIcon,
        EyeIcon,
    },
    props: {
        title: String,
        list: Object,
        filter: Object,
        status: Array,
    },
    data() {
        return {
            bulan: [
                "Januari",
                "Februari",
                "Maret",
                "April",
                "Mei",
                "Juni",
                "Juli",
                "Agustus",
                "September",
                "Oktober",
                "November",
                "Desember",
            ],
        };
    },
    methods: {
        filters() {
            this.$inertia.get(this.route("pelatihan.index"), this.filter, {
                preserveState: true,
                only: ["filter", "list"],
            });
        },
        deleteItem(item) {
            Swal.fire({
                text: "Apakah anda yakin akan menghapus data ini ?",
                icon: "question",
                confirmButtonText: "Ya, Lanjutkan",
                cancelButtonText: "Batalkan",
                showCancelButton: true,
                confirmButtonColor: "#0ea5e9",
                cancelButtonColor: "#ef4444",
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$inertia.delete(
                        this.route("pelatihan.destroy", item),
                        {
                            preserveScroll: true,
                            only: ["list", "flash"],
                        }
                    );
                }
            });
        },
        hasRolesID(roles_id) {
            let result = false;
            if (this.$page.props.auth.user) {
                if (!roles_id) {
                    result = true;
                } else {
                    let checker;
                    roles_id.forEach((role_id) => {
                        checker = this.$page.props.auth.roles.id.some(
                            (item) => item == role_id
                        );
                        if (checker) {
                            result = true;
                        }
                    });
                }
            }
            return result;
        },
        dateIndo(param) {
            param = new Date(param);
            let result =
                "" +
                (param.getDate() > 9 ? "" : "0") +
                param.getDate() +
                " " +
                this.bulan[param.getMonth()] +
                " " +
                param.getFullYear();
            return result;
        },
    },
});
</script>
