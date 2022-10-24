<template>
    <AppLayout>
        <template #breadcrumb>
            <li aria-current="page">
                <div class="flex items-center">
                    <ChevronRightIcon class="w-6 h-6 text-gray-400 mx-1" />
                    <span class="text-sm font-medium text-gray-500">Users</span>
                </div>
            </li>
        </template>
        <div class="text-right">
            <Link
                v-if="hasRolesID([1])"
                :href="route('users.create')"
                class="btn btn-md btn-sky mb-3"
                ><PlusIcon class="mr-2 h-5 w-5" />User</Link
            >
        </div>
        <div class="bg-white shadow rounded-lg border-t-2 border-sky-500">
            <div class="px-4 py-5 border-b">
                <h2
                    class="text-lg leading-6 font-medium inline-flex items-center"
                >
                    <NewspaperIcon class="h-6 w-6 mr-2" />Daftar User
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
                            <th scope="col" class="text-left th">NIP</th>
                            <th scope="col" class="text-left th">Nama</th>
                            <th scope="col" class="text-left th">Email</th>
                            <th scope="col" class="text-left th">Roles</th>
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
                                {{ item.nip }}
                            </td>
                            <td class="td">
                                {{ item.nama }}
                            </td>
                            <td class="td">
                                {{ item.email }}
                            </td>
                            <td class="td">
                                <div
                                    v-for="item2 in item.roles"
                                    :key="item2.id"
                                >
                                    {{ item2.role.name }}
                                </div>
                            </td>

                            <td
                                valign="top"
                                class="td whitespace-nowrap text-center"
                            >
                                <Link
                                    title="Ubah"
                                    :href="route('users.edit', item.nip)"
                                    class="ml-1 btn btn-sm btn-yellow"
                                >
                                    <PencilSquareIcon class="h-5 w-5" />
                                </Link>
                                <button
                                    v-if="item.nip != $page.props.auth.user.nip"
                                    title="Hapus"
                                    type="button"
                                    @click="deleteItem(item.nip)"
                                    class="ml-1 btn btn-sm btn-red"
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
                <Pagination :links="list.links" />
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
    },

    methods: {
        filters() {
            this.$inertia.get(this.route("users.index"), this.filter, {
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
                    this.$inertia.delete(this.route("users.destroy", item), {
                        preserveScroll: true,
                        only: ["list", "flash"],
                    });
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
    },
});
</script>
