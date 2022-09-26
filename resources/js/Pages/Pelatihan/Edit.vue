<template>
    <AppLayout>
        <template #breadcrumb>
            <li>
                <div class="flex items-center">
                    <ChevronRightIcon class="w-6 h-6 text-gray-400 mx-1" />

                    <Link
                        :href="route('pelatihan.index')"
                        class="text-sm font-medium text-sky-500"
                        >Pelatihan</Link
                    >
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <ChevronRightIcon class="w-6 h-6 text-gray-400 mx-1" />
                    <span class="text-sm font-medium text-gray-500"
                        >Tambah</span
                    >
                </div>
            </li>
        </template>
        <div class="mb-10">
            <Link
                :href="route('pelatihan.index')"
                class="btn btn-md btn-slate mb-3"
                ><ChevronLeftIcon class="w-5 h-5 mr-2" />Kembali</Link
            >
            <form @submit.prevent="submit">
                <div
                    class="bg-white shadow rounded-lg border-t-2 border-sky-500"
                >
                    <div class="p-6 space-y-3">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Jenis Pelatihan<i class="text-red-500 tex-xs"
                                    >*</i
                                >
                            </label>
                            <select
                                v-model="form.jenis_pelatihan"
                                class="f-input px-2"
                                required
                            >
                                <option value="">Pilih</option>
                                <option value="fungsional">
                                    Pelatihan Fungsional
                                </option>
                                <option value="teknis">Pelatihan Teknis</option>
                                <option value="mansoskul">
                                    Pelatihan Mansoskul
                                </option>
                            </select>
                        </div>
                        <div
                            v-if="form.jenis_pelatihan == 'fungsional'"
                            class="sm:flex items-start"
                        >
                            <label
                                class="text-sm font-medium text-gray-700 mb-1 mr-3"
                            >
                                Keterangan Jabatan<i class="text-red-500 tex-xs"
                                    >*</i
                                >
                            </label>
                            <div class="text-sm text-gray-700">
                                <div
                                    class="flex items-start"
                                    v-for="(item, index) in ket_jabatan"
                                    :key="index"
                                >
                                    <Checkbox
                                        :id="'ket_jabatan' + index"
                                        class="mr-2 mt-1"
                                        v-model:checked="form.ket_jabatan"
                                        :value="item"
                                    />
                                    {{ item }}
                                </div>
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Judul Pelatihan<i class="text-red-500 tex-xs"
                                    >*</i
                                >
                            </label>
                            <input
                                type="text"
                                v-model="form.judul"
                                class="f-input px-2"
                                required
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Deskripsi<i class="text-red-500 tex-xs"
                                    >*</i
                                > </label
                            ><ckeditor
                                ref="deskripsi"
                                :editor="editor"
                                v-model="form.deskripsi"
                                :config="editorConfig"
                            ></ckeditor>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Silabus<i class="text-red-500 tex-xs">*</i>
                            </label>
                            <ckeditor
                                ref="silabus"
                                :editor="editor"
                                v-model="form.silabus"
                                :config="editorConfig"
                            ></ckeditor>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Persyaratan Peserta<i
                                    class="text-red-500 tex-xs"
                                    >*</i
                                >
                            </label>
                            <ckeditor
                                ref="persyaratan"
                                :editor="editor"
                                v-model="form.persyaratan"
                                :config="editorConfig"
                            ></ckeditor>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Jenis JF yang dapat mengikuti<i
                                    class="text-red-500 tex-xs"
                                    >*</i
                                >
                            </label>
                            <div
                                class="grid grid-cols-2 border-gray-300 text-gray-700 text-sm rounded border px-2 py-2"
                            >
                                <div
                                    class="flex items-start"
                                    v-for="(item, index) in jabatans"
                                    :key="index"
                                >
                                    <Checkbox
                                        :id="'jabatan' + index"
                                        class="mr-2 mt-1"
                                        v-model:checked="form.kd_jabatan"
                                        :value="item.kd_jabatan"
                                    />
                                    {{ item.jabatan }}
                                </div>
                            </div>
                        </div>
                        <div class="sm:flex items-start">
                            <label
                                class="text-sm font-medium text-gray-700 mb-1 mr-3"
                            >
                                Instansi Peserta Pelatihan<i
                                    class="text-red-500 tex-xs"
                                    >*</i
                                >
                            </label>
                            <div class="text-sm text-gray-700">
                                <div>
                                    <input
                                        type="radio"
                                        name="instansi"
                                        value="pusat"
                                        v-model="form.instansi"
                                        class="checked:text-sky-500 checked:ring-sky-200 focus:checked:ring-sky-200 active:ring-sky-200 border-gray-300 mr-1"
                                        required
                                    />
                                    Pusat
                                </div>
                                <div>
                                    <input
                                        type="radio"
                                        name="instansi"
                                        value="daerah"
                                        v-model="form.instansi"
                                        class="checked:text-sky-500 checked:ring-sky-200 focus:checked:ring-sky-200 active:ring-sky-200 border-gray-300 mr-1"
                                    />
                                    Daerah
                                </div>
                                <div>
                                    <input
                                        type="radio"
                                        name="instansi"
                                        value="pusat_daerah"
                                        v-model="form.instansi"
                                        class="checked:text-sky-500 checked:ring-sky-200 focus:checked:ring-sky-200 active:ring-sky-200 border-gray-300 mr-1"
                                    />
                                    Pusat dan/atau Daerah
                                </div>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Kuota Peserta<i class="text-red-500 tex-xs"
                                    >*</i
                                >
                            </label>
                            <input
                                type="number"
                                min="1"
                                v-model="form.kuota"
                                class="f-input px-2"
                                required
                            />
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Tanggal Mulai<i class="text-red-500 tex-xs"
                                        >*</i
                                    >
                                </label>
                                <input
                                    type="date"
                                    v-model="form.tgl_mulai"
                                    class="f-input px-2"
                                    required
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Tanggal Selesai<i
                                        class="text-red-500 tex-xs"
                                        >*</i
                                    >
                                </label>
                                <input
                                    type="date"
                                    v-model="form.tgl_selesai"
                                    class="f-input px-2"
                                    required
                                />
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Bahan Pelatihan
                                <i class="text-xs">(Optional)</i>
                            </label>
                            <div
                                v-if="bahan.length > 0"
                                class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                            >
                                <ul
                                    role="list"
                                    class="border border-gray-200 rounded-md divide-y divide-gray-200"
                                >
                                    <li
                                        v-for="(file, index) in bahan"
                                        :key="index"
                                        class="flex items-center justify-between text-sm"
                                    >
                                        <div
                                            class="w-0 px-2 py-1.5 flex-1 flex items-center"
                                        >
                                            <PaperClipIcon
                                                class="h-5 w-5 mr-2 text-gray-400"
                                            />
                                            <a
                                                :href="file.file"
                                                class="ml-2 flex-1 w-0 truncate text-sky-400 hover:text-sky-600"
                                            >
                                                {{ file.name }}
                                            </a>
                                        </div>

                                        <div
                                            v-if="index == 0"
                                            @click="
                                                addInput(
                                                    counters.length > 0
                                                        ? counters[
                                                              counters.length -
                                                                  1
                                                          ] + 1
                                                        : 0
                                                )
                                            "
                                            class="px-2 cursor-pointer py-1.5 flex-shrink-0 border-l text-sky-300 bg-white hover:bg-sky-400 hover:text-white"
                                        >
                                            <PlusIcon class="h-5 w-5" />
                                        </div>
                                        <button
                                            type="button"
                                            @click.prevent="
                                                deleteImage(file.id)
                                            "
                                            class="px-2 py-1.5 flex-shrink-0 border-l text-red-300 hover:bg-red-400 hover:text-white"
                                        >
                                            <MinusIcon class="h-5 w-5" />
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <div
                                class="mt-1 relative rounded-md shadow-sm border"
                                ref="bahan"
                                v-for="(counter, index) in counters"
                                :key="index"
                            >
                                <input
                                    type="file"
                                    @input="
                                        form.file_bahan[index] =
                                            $event.target.files[0]
                                    "
                                    accept=".pdf,.xlsx,.xls,.docx,.doc"
                                    class="w-full focus:outline-none border-1 border-gray-300 focus:ring-2 focus:ring-sky-300 rounded sm:text-sm file:border-0 pr-14 file:mr-5 file:py-1.5 file:px-2 file:rounded-l file:text-md file:font-semibold file:bg-gray-300 file:text-gray-600 hover:file:cursor-pointer hover:file:bg-gray-400 hover:file:text-gray-700"
                                />
                                <div
                                    v-if="index == 0 && bahan.length == 0"
                                    class="absolute inset-y-0 right-0 px-2 flex border-l rounded-r items-center cursor-pointer text-sky-400 bg-white hover:bg-sky-400 hover:text-white"
                                    @click="
                                        addInput(
                                            counters[counters.length - 1] + 1
                                        )
                                    "
                                >
                                    <PlusIcon class="h-5 w-5" />
                                </div>
                                <div
                                    v-else
                                    class="absolute inset-y-0 right-0 px-2 flex border-l rounded-r items-center cursor-pointer text-red-400 hover:bg-red-400 hover:text-white"
                                    @click="deleteInput(counter)"
                                >
                                    <MinusIcon class="h-5 w-5" />
                                </div>
                            </div>
                        </div>
                        <div class="text-sm">
                            <Checkbox
                                class="mr-2"
                                v-model:checked="form.is_publish"
                            />Publish Pelatihan
                        </div>
                        <div
                            class="grid grid-cols-2 gap-3"
                            v-if="form.is_publish"
                        >
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Tanggal Mulai Pendaftaran<i
                                        class="text-red-500 tex-xs"
                                        >*</i
                                    >
                                </label>
                                <input
                                    type="date"
                                    v-model="form.mulai_pendaftaran"
                                    class="f-input px-2"
                                    required
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Tanggal Selesai<i
                                        class="text-red-500 tex-xs"
                                        >*</i
                                    >
                                </label>
                                <input
                                    type="date"
                                    v-model="form.selesai_pendaftaran"
                                    class="f-input px-2"
                                    required
                                />
                            </div>
                        </div>
                    </div>

                    <div
                        class="p-6 flex flex-col sm:block border-t bg-gray-50 rounded-b-lg space-y-1"
                    >
                        <button
                            v-if="form.is_publish"
                            @click="form.status_id = 2"
                            type="submit"
                            title="Simpan"
                            class="btn btn-md btn-sky"
                        >
                            <PaperAirplaneIcon class="h-5 w-5 mr-2" />
                            Simpan & Submit</button
                        ><button
                            @click="form.status_id = 1"
                            type="submit"
                            title="Simpan"
                            class="btn btn-md btn-yellow sm:ml-1"
                        >
                            <ArrowDownOnSquareIcon class="h-5 w-5 mr-2" />
                            Simpan
                        </button>
                        <Link
                            :href="route('pelatihan.index')"
                            class="btn btn-md btn-slate sm:ml-1"
                            ><XMarkIcon class="w-5 h-5 mr-2" />Batal</Link
                        >
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
import ClassicEditor from "@/ckeditor";
import CKEditor from "@ckeditor/ckeditor5-vue";
import Checkbox from "@/Components/Checkbox.vue";
import {
    ChevronLeftIcon,
    ChevronRightIcon,
    ArrowDownOnSquareIcon,
    PlusIcon,
    MinusIcon,
    XMarkIcon,
    PaperClipIcon,
    PaperAirplaneIcon,
} from "@heroicons/vue/24/solid";
export default defineComponent({
    components: {
        AppLayout,
        Link,
        ChevronLeftIcon,
        ChevronRightIcon,
        ArrowDownOnSquareIcon,
        ckeditor: CKEditor.component,
        Checkbox,
        PlusIcon,
        MinusIcon,
        XMarkIcon,
        PaperClipIcon,
        PaperAirplaneIcon,
    },

    props: {
        title: String,
        jabatans: Array,
        pelatihan: Object,
    },
    data() {
        let jabatan = this.pelatihan.jabatan;
        let kd_jabatan = Object.keys(jabatan).map(function (key) {
            return jabatan[key].kd_jabatan;
        });
        return {
            bahan: this.pelatihan.bahan,
            form: this.$inertia.form({
                judul: this.pelatihan.judul,
                deskripsi: this.pelatihan.deskripsi,
                silabus: this.pelatihan.silabus,
                persyaratan: this.pelatihan.persyaratan,
                kd_jabatan: kd_jabatan,
                tgl_mulai: this.pelatihan.tgl_mulai,
                tgl_selesai: this.pelatihan.tgl_selesai,
                mulai_pendaftaran: this.pelatihan.mulai_pendaftaran,
                selesai_pendaftaran: this.pelatihan.selesai_pendaftaran,
                kuota: this.pelatihan.kuota,
                file_bahan: [],
                is_publish: this.pelatihan.is_publish,
                status_id: this.pelatihan.status_id,
                jenis_pelatihan: this.pelatihan.jenis_pelatihan,
                ket_jabatan: JSON.parse(this.pelatihan.ket_jabatan),
                instansi: this.pelatihan.instansi,
            }),
            editor: ClassicEditor,
            editorConfig: {
                extraPlugins: [this.UploadAdapterPlugin],
            },
            counters: this.pelatihan.bahan.length > 0 ? [] : [0],
            ket_jabatan: [
                "Penyetaraan",
                "Inpassing",
                "Pengangkatan Pertama",
                "Perpindahan JF",
            ],
        };
    },

    methods: {
        addInput(item) {
            this.counters.push(item);
        },
        deleteInput(item) {
            this.$refs.bahan[item].remove();
        },
        UploadAdapterPlugin(editor) {
            editor.plugins.get("FileRepository").createUploadAdapter = (
                loader
            ) => {
                return new UploadAdapter(loader);
            };
        },
        validateEditor(ref) {
            scrollTo(0, this.$refs[ref].$el.scrollTop);
            let ck =
                this.$refs[ref].$el.nextSibling.querySelector(".ck-content");
            ck.classList.remove("ck-blurred");
            ck.classList.add("ck-focused");
            Swal.fire({
                text:
                    "Silahkan mengisi " +
                    ref.charAt(0).toUpperCase() +
                    ref.slice(1) +
                    " terlebih dahulu",
                icon: "warning",
                confirmButtonColor: "#0ea5e9",
            });
        },
        submit() {
            if (!this.form.deskripsi) {
                this.validateEditor("deskripsi");
            } else if (!this.form.silabus) {
                this.validateEditor("silabus");
            } else if (!this.form.persyaratan) {
                this.validateEditor("persyaratan");
            } else if (this.form.kd_jabatan.length == 0) {
                document.getElementById("jabatan0").focus();
                Swal.fire({
                    text: "Silahkan memilih Persyaratan Mengikuti Pelatihan",
                    icon: "warning",
                    confirmButtonColor: "#0ea5e9",
                });
            } else {
                let d1 = new Date(this.form.tgl_mulai);
                let d2 = new Date(this.form.tgl_selesai);
                let d3 = new Date(this.form.mulai_pendaftaran);
                let d4 = new Date(this.form.selesai_pendaftaran);
                if (this.form.is_publish && d1.getTime() > d2.getTime()) {
                    Swal.fire({
                        text: "Tanggal selesai pelatihan harus sama atau lebih besar dari tanggal mulai pelatihan",
                        icon: "warning",
                        confirmButtonColor: "#0ea5e9",
                    });
                } else if (d3.getTime() > d4.getTime()) {
                    Swal.fire({
                        text: "Tanggal selesai pendaftaran harus sama atau lebih besar dari tanggal mulai pendaftaran",
                        icon: "warning",
                        confirmButtonColor: "#0ea5e9",
                    });
                } else {
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
                            this.form.post(
                                this.route(
                                    "pelatihan.update",
                                    this.pelatihan.slug
                                )
                            );
                        }
                    });
                }
            }
        },
        deleteImage(item) {
            Swal.fire({
                text: "Apakah anda yakin akan menghapus bahan ini ?",
                icon: "question",
                confirmButtonText: "Ya, Lanjutkan",
                cancelButtonText: "Batalkan",
                showCancelButton: true,
                confirmButtonColor: "#0ea5e9",
                cancelButtonColor: "#ef4444",
            }).then((result) => {
                if (result.isConfirmed) {
                    axios({
                        url: this.route("bahan.destroy", item),
                        method: "delete",
                    })
                        .then((response) => {
                            this.bahan = response.data;
                            if (
                                response.data.length == 0 &&
                                this.counters.length == 0
                            ) {
                                this.counters = [0];
                            }
                        })
                        .catch((response) => {
                            console.log(response);
                            Swal.fire({
                                text: "Gagal menghapus gambar",
                                icon: "warning",
                                confirmButtonColor: "#0ea5e9",
                            });
                        });
                }
            });
        },
    },
});
class UploadAdapter {
    constructor(loader) {
        this.loader = loader;
    }
    upload() {
        return this.loader.file.then((uploadedFile) => {
            return new Promise((resolve, reject) => {
                const data = new FormData();
                data.append("file", uploadedFile);

                axios({
                    url: "/sipelatihan/editor/upload",
                    method: "post",
                    data,
                    headers: {
                        "Content-Type": "multipart/form-data;",
                    },
                })
                    .then((response) => {
                        if (response.data.result == "success") {
                            resolve({
                                default: response.data.data,
                            });
                        } else {
                            reject(response.data.data);
                        }
                    })
                    .catch((response) => {
                        reject("Koneksi terputus");
                    });
            });
        });
    }
}
</script>
