<template>
    <AppLayout>
        <template #breadcrumb>
            <li>
                <div class="flex items-center">
                    <ChevronRightIcon class="w-6 h-6 text-gray-400 mx-1" />

                    <Link
                        :href="route('users.index')"
                        class="text-sm font-medium text-sky-500"
                        >Users</Link
                    >
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <ChevronRightIcon class="w-6 h-6 text-gray-400 mx-1" />
                    <span class="text-sm font-medium text-gray-500">Ubah</span>
                </div>
            </li>
        </template>
        <div class="mb-10">
            <Link :href="route('users.index')" class="btn btn-md btn-slate mb-3"
                ><ChevronLeftIcon class="w-5 h-5 mr-2" />Kembali</Link
            >
            <form @submit.prevent="submit">
                <div
                    class="bg-white shadow rounded-lg border-t-2 border-sky-500"
                >
                    <div
                        class="overflow-auto py-6 text-gray-700 text-sm font-medium"
                    >
                        <div class="grid sm:grid-cols-3 gap-3 px-6 pb-3">
                            <div class="col-span-1">
                                <label class="block">
                                    NIP<i class="text-red-500 tex-xs">*</i>
                                </label>
                            </div>
                            <div class="col-span-2">
                                <input
                                    type="text"
                                    v-model="nip"
                                    class="f-input px-2 bg-gray-100"
                                    readonly
                                />
                            </div>
                            <div class="col-span-1">
                                <label class="block">
                                    Nama<i class="text-red-500 tex-xs">*</i>
                                </label>
                            </div>
                            <div class="col-span-2">
                                <input
                                    maxlength="150"
                                    type="text"
                                    v-model="form.nama"
                                    class="f-input px-2"
                                    required
                                />
                            </div>
                            <div class="col-span-1">
                                <label class="block">
                                    Email<i class="text-red-500 tex-xs">*</i>
                                </label>
                            </div>
                            <div class="col-span-2">
                                <input
                                    type="email"
                                    maxlength="150"
                                    v-model="form.email"
                                    class="f-input px-2"
                                    required
                                />
                            </div>

                            <div class="col-span-1">
                                <label class="block">
                                    Role<i class="text-red-500 tex-xs">*</i>
                                </label>
                            </div>
                            <div class="col-span-2">
                                <select
                                    class="f-input px-2"
                                    v-model="form.role_id"
                                >
                                    <option value="" disabled>Pilih</option>
                                    <option
                                        :value="role.id"
                                        v-for="role in roles"
                                        :key="role.id"
                                    >
                                        {{ role.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-span-1">
                                <label class="block">
                                    Foto <i class="pl-1 tex-xs">(Optional)</i>
                                </label>
                            </div>
                            <div class="col-span-2">
                                <div
                                    class="w-20 aspect-w-1 aspect-h-1 overflow-hidden"
                                    v-if="user.foto"
                                >
                                    <img
                                        :src="foto"
                                        @click="upload"
                                        class="object-cover object-center cursor-pointer rounded-full shadow"
                                        alt="Foto Profile"
                                    />
                                </div>
                                <div
                                    class="mt-1 relative rounded-md shadow-sm border"
                                    :class="{ hidden: user.foto }"
                                >
                                    <input
                                        type="file"
                                        @input="
                                            form.foto = $event.target.files[0]
                                        "
                                        @change="setFoto"
                                        ref="foto"
                                        accept=".jpg,.jpeg,.png"
                                        class="w-full focus:outline-none border-1 border-gray-300 focus:ring-2 focus:ring-sky-300 rounded sm:text-sm file:border-0 pr-14 file:mr-5 file:py-1.5 file:px-2 file:rounded-l file:text-md file:font-semibold file:bg-gray-300 file:text-gray-600 hover:file:cursor-pointer hover:file:bg-gray-400 hover:file:text-gray-700"
                                    />
                                </div>
                            </div>
                        </div>
                        <div
                            class="grid bg-gray-50 sm:grid-cols-3 gap-3 px-6 pt-3 border-t"
                        >
                            <div class="col-span-1"></div>
                            <div class="col-span-2">
                                <button
                                    type="submit"
                                    title="Simpan"
                                    class="btn btn-md btn-sky sm:ml-1"
                                >
                                    <ArrowDownOnSquareIcon
                                        class="h-5 w-5 mr-2"
                                    />
                                    Simpan
                                </button>
                                <Link
                                    :href="route('users.index')"
                                    class="btn btn-md btn-slate sm:ml-1"
                                    ><XMarkIcon
                                        class="w-5 h-5 mr-2"
                                    />Batal</Link
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/Admin.vue";
import { Link } from "@inertiajs/inertia-vue3";
import {
    ChevronLeftIcon,
    ChevronRightIcon,
    ArrowDownOnSquareIcon,
    XMarkIcon,
} from "@heroicons/vue/24/solid";
export default defineComponent({
    components: {
        AppLayout,
        Link,
        ChevronLeftIcon,
        ChevronRightIcon,
        ArrowDownOnSquareIcon,
        XMarkIcon,
    },

    props: {
        title: String,
        roles: Array,
        user: Object,
        user_roles: Object,
    },
    data() {
        return {
            nip: this.user.nip,
            foto: this.user.foto,
            form: this.$inertia.form({
                nama: this.user.nama,
                email: this.user.email,
                role_id: this.user_roles[0],
                foto: null,
            }),
        };
    },

    methods: {
        upload() {
            this.$refs.foto.click();
        },
        setFoto(e) {
            this.foto = URL.createObjectURL(e.target.files[0]);
        },
        submit() {
            Swal.fire({
                text: "Apakah anda yakin akan menyimpan data ini ?",
                icon: "question",
                confirmButtonText: "Ya, Lanjutkan",
                cancelButtonText: "Batalkan",
                confirmButtonColor: "#0ea5e9",
                cancelButtonColor: "#ef4444",
                showCancelButton: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    this.form.post(this.route("users.update", this.user.nip));
                }
            });
        },
    },
});
</script>
