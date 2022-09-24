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
                        >Detail</span
                    >
                </div>
            </li>
        </template>
        <div
            class="p-3 mb-3 rounded-lg text-sky-900 bg-sky-200 shadow flex items-center"
            v-if="
                pelatihan.status.notification &&
                hasRolesID([pelatihan.status.role_id])
            "
        >
            <InformationCircleIcon class="h-5 w-5 mr-2" />
            {{ pelatihan.status.notification }}
        </div>
        <div class="mb-3">
            <div class="sm:flex justify-between items-center mb-3">
                <Link
                    :href="route('pelatihan.index')"
                    class="btn btn-md btn-slate"
                    ><ChevronLeftIcon class="w-5 h-5 mr-2" />Kembali</Link
                >
                <div v-if="pelatihan.is_publish">
                    <button
                        type="button"
                        @click.prevent="submit(pelatihan.status.next_id)"
                        class="btn btn-md btn-teal"
                        v-if="
                            (pelatihan.status_id == 1 ||
                                pelatihan.status_id == 3) &&
                            hasRolesID([pelatihan.status.role_id])
                        "
                    >
                        <PaperAirplaneIcon class="h-5 w-5 mr-2" /> Submit
                        Pelatihan
                    </button>
                    <button
                        type="button"
                        @click.prevent="reject(pelatihan.status.prev_id)"
                        class="btn btn-md btn-red"
                        v-if="
                            (pelatihan.status_id == 2 ||
                                pelatihan.status_id == 5) &&
                            hasRolesID([pelatihan.status.role_id])
                        "
                    >
                        <XMarkIcon class="h-5 w-5 mr-2" /> Reject Pelatihan
                    </button>
                    <div
                        v-if="
                            pelatihan.status_id == 4 &&
                            hasRolesID([pelatihan.status.role_id])
                        "
                    >
                        <button
                            type="button"
                            @click.prevent="approve(pelatihan.status.next_id)"
                            class="btn btn-md btn-sky"
                        >
                            <CheckIcon class="h-5 w-5 mr-2" /> Approve Pelatihan
                        </button>
                        <button
                            type="button"
                            @click.prevent="reject(pelatihan.status.prev_id)"
                            class="btn btn-md btn-red ml-1"
                        >
                            <XMarkIcon class="h-5 w-5 mr-2" /> Reject Pelatihan
                        </button>
                    </div>
                    <div
                        v-if="
                            pelatihan.status_id == 6 &&
                            hasRolesID([pelatihan.status.role_id])
                        "
                    >
                        <button
                            type="button"
                            @click.prevent="batasKonfirmasi()"
                            class="btn btn-md btn-sky"
                        >
                            <CheckIcon class="h-5 w-5 mr-2" /> Lanjutkan
                            Pelatihan
                        </button>
                        <button
                            v-if="peserta.total < pelatihan.kuota"
                            type="button"
                            @click.prevent="batalkan"
                            class="btn btn-md btn-red ml-1"
                        >
                            <XMarkIcon class="h-5 w-5 mr-2" /> Batalkan
                            Pelatihan
                        </button>
                    </div>
                    <button
                        v-if="pelatihan.status_id == 7"
                        type="button"
                        @click.prevent="ubahTanggal"
                        class="btn btn-md btn-yellow ml-1"
                    >
                        <PencilSquareIcon class="h-5 w-5 mr-2" /> Tanggal
                        Pendaftaran
                    </button>
                    <div
                        v-if="
                            (pelatihan.status_id == 8 ||
                                pelatihan.status_id == 10) &&
                            hasRolesID([pelatihan.status.role_id]) &&
                            new Date(today).getTime() >
                                new Date(pelatihan.batas_konfirmasi).getTime()
                        "
                    >
                        <button
                            type="button"
                            @click.prevent="submit(pelatihan.status.next_id)"
                            class="btn btn-md btn-sky"
                        >
                            <CheckIcon class="h-5 w-5 mr-2" /> Lanjutkan
                            Pelatihan
                        </button>

                        <button
                            v-if="pelatihan.kuota > confirmed.approved"
                            type="button"
                            @click.prevent="batasKonfirmasi()"
                            class="btn btn-md btn-yellow ml-1"
                        >
                            <PencilSquareIcon class="h-5 w-5 mr-2" /> Batas
                            Konfirmasi
                        </button>
                    </div>
                    <div
                        v-if="
                            (pelatihan.status_id == 9 ||
                                pelatihan.status_id == 11 ||
                                pelatihan.status_id == 12) &&
                            hasRolesID([pelatihan.status.role_id])
                        "
                    >
                        <button
                            type="button"
                            @click.prevent="approve(pelatihan.status.next_id)"
                            class="btn btn-md btn-sky"
                        >
                            <CheckIcon class="h-5 w-5 mr-2" /> Approve Pelatihan
                        </button>
                        <button
                            type="button"
                            @click.prevent="reject(pelatihan.status.prev_id)"
                            class="btn btn-md btn-red ml-1"
                        >
                            <XMarkIcon class="h-5 w-5 mr-2" /> Reject Pelatihan
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg border-t-2 border-sky-500">
                <div class="p-3 border-b">
                    <h2
                        class="text-lg leading-6 font-medium inline-flex items-center"
                    >
                        <NewspaperIcon class="h-6 w-6 mr-2" />Katalog Penilaian
                    </h2>
                </div>
                <div class="overflow-auto pb-3">
                    <table class="min-w-full">
                        <tr>
                            <td
                                class="pl-6 pr-3 align-top py-2 bg-gray-50 whitespace-nowrap"
                            >
                                Jenis Pelatihan
                            </td>
                            <td class="td bg-gray-50">:</td>
                            <td
                                class="pl-3 pr-6 py-2 align-top bg-gray-50 text-justify"
                            >
                                Pelatihan
                                {{ ucfirst(pelatihan.jenis_pelatihan) }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="pl-6 pr-3 align-top py-2 bg-gray-50 whitespace-nowrap"
                            >
                                Keterangan Jabatan
                            </td>
                            <td class="td bg-gray-50">:</td>
                            <td
                                class="pl-3 pr-6 py-2 align-top bg-gray-50 text-justify"
                            >
                                {{ pelatihan.ket_jabatan }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="pl-6 pr-3 align-top py-2 bg-gray-50 whitespace-nowrap"
                            >
                                Judul Pelatihan
                            </td>
                            <td class="td bg-gray-50">:</td>
                            <td
                                class="pl-3 pr-6 py-2 align-top bg-gray-50 text-justify"
                            >
                                {{ pelatihan.judul }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="pl-6 pr-3 align-top py-2 whitespace-nowrap"
                            >
                                Deskripsi
                            </td>
                            <td class="td">:</td>
                            <td
                                class="pl-3 pr-6 py-2 align-top text-justify"
                                v-html="pelatihan.deskripsi"
                            ></td>
                        </tr>
                        <tr>
                            <td
                                class="pl-6 pr-3 align-top py-2 bg-gray-50 whitespace-nowrap"
                            >
                                Silabus
                            </td>
                            <td class="td bg-gray-50">:</td>
                            <td
                                class="pl-3 pr-6 py-2 align-top bg-gray-50 text-justify"
                                v-html="pelatihan.silabus"
                            ></td>
                        </tr>
                        <tr>
                            <td
                                class="pl-6 pr-3 align-top py-2 whitespace-nowrap"
                            >
                                Persyaratan Peserta
                            </td>
                            <td class="td">:</td>
                            <td
                                class="pl-3 pr-6 py-2 align-top text-justify"
                                v-html="pelatihan.persyaratan"
                            ></td>
                        </tr>
                        <tr>
                            <td
                                class="pl-6 pr-3 align-top py-2 bg-gray-50 whitespace-nowrap w-10"
                            >
                                Persyaratan Mengikuti Pelatihan
                            </td>
                            <td class="td bg-gray-50 w-3">:</td>
                            <td class="pl-3 pr-6 py-2 align-top bg-gray-50">
                                <div
                                    v-for="(item, index) in pelatihan.jabatan"
                                    :key="item.id"
                                >
                                    {{ index + 1 }}.
                                    {{ item.jabatan.jabatan }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="pl-6 pr-3 align-top py-2 whitespace-nowrap"
                            >
                                Kuota Peserta
                            </td>
                            <td class="td">:</td>
                            <td class="pl-3 pr-6 py-2 align-top">
                                {{ pelatihan.kuota }} Orang
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="pl-6 pr-3 align-top py-2 bg-gray-50 whitespace-nowrap"
                            >
                                Tanggal Pelatihan
                            </td>
                            <td class="td bg-gray-50">:</td>
                            <td class="pl-3 pr-6 py-2 align-top bg-gray-50">
                                {{
                                    dateIndo(pelatihan.tgl_mulai) +
                                    " s/d " +
                                    dateIndo(pelatihan.tgl_selesai)
                                }}
                            </td>
                        </tr>
                        <tr v-if="pelatihan.is_publish">
                            <td
                                class="pl-6 pr-3 align-top py-2 bg-gray-50 whitespace-nowrap"
                            >
                                Tanggal Pendaftaran
                            </td>
                            <td class="td bg-gray-50">:</td>
                            <td class="pl-3 pr-6 py-2 align-top bg-gray-50">
                                {{
                                    dateIndo(pelatihan.mulai_pendaftaran) +
                                    " s/d " +
                                    dateIndo(pelatihan.selesai_pendaftaran)
                                }}
                            </td>
                        </tr>
                        <tr
                            v-if="
                                pelatihan.status_id > 7 &&
                                pelatihan.batas_konfirmasi &&
                                pelatihan.is_publish
                            "
                        >
                            <td
                                class="pl-6 pr-3 align-top py-2 bg-gray-50 whitespace-nowrap"
                            >
                                Batas Konfirmasi
                            </td>
                            <td class="td bg-gray-50">:</td>
                            <td class="pl-3 pr-6 py-2 align-top bg-gray-50">
                                {{ dateIndo(pelatihan.batas_konfirmasi) }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="pl-6 pr-3 align-top py-2 whitespace-nowrap"
                            >
                                Publish Pelatihan
                            </td>
                            <td class="td">:</td>
                            <td class="pl-3 pr-6 py-2 align-top">
                                {{ pelatihan.is_publish ? "Ya" : "Tidak" }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="pl-6 pr-3 align-top py-2 bg-gray-50 whitespace-nowrap"
                            >
                                Status
                            </td>
                            <td class="td bg-gray-50">:</td>
                            <td class="pl-3 pr-6 py-2 align-top bg-gray-50">
                                <span
                                    class="px-2 py-0.5 font-semibold text-xs tracking-wider rounded-full shadow"
                                    :class="{
                                        'bg-sky-200 text-sky-900 shadow-sky-500':
                                            pelatihan.status_id == 1,
                                        'bg-yellow-200 text-yellow-900  shadow-yellow-500':
                                            pelatihan.status_id == 2 ||
                                            pelatihan.status_id == 4 ||
                                            pelatihan.status_id == 6 ||
                                            pelatihan.status_id == 8 ||
                                            pelatihan.status_id == 9 ||
                                            pelatihan.status_id == 11,
                                        'bg-red-200 text-red-900 shadow-red-500':
                                            pelatihan.status_id == 3 ||
                                            pelatihan.status_id == 5 ||
                                            pelatihan.status_id == 7 ||
                                            pelatihan.status_id == 10 ||
                                            pelatihan.status_id == 12,
                                        'bg-teal-200 text-teal-900 shadow-teal-500':
                                            pelatihan.status_id == 13,
                                    }"
                                    >{{ pelatihan.status.name }}</span
                                >
                            </td>
                        </tr>
                        <tr
                            v-if="
                                pelatihan.status_id == 3 ||
                                pelatihan.status_id == 5 ||
                                pelatihan.status_id == 7 ||
                                pelatihan.status_id == 10 ||
                                pelatihan.status_id == 12
                            "
                        >
                            <td
                                class="pl-6 pr-3 align-top py-2 whitespace-nowrap"
                            >
                                Alasan Penolakan
                            </td>
                            <td class="td">:</td>
                            <td class="pl-3 pr-6 py-2 align-top">
                                {{ pelatihan.keterangan }}
                            </td>
                        </tr>
                        <tr v-if="pelatihan.bahan.length > 0">
                            <td
                                class="pl-6 pr-3 align-top py-2 bg-gray-50 whitespace-nowrap"
                            >
                                Bahan Pelatihan
                            </td>
                            <td class="td bg-gray-50">:</td>
                            <td class="pl-3 pr-6 py-2 align-top bg-gray-50">
                                <ul role="list">
                                    <li
                                        v-for="(file, index) in pelatihan.bahan"
                                        :key="index"
                                        class="flex items-center"
                                    >
                                        <PaperClipIcon
                                            class="h-5 w-5 mr-1 text-gray-400"
                                        />
                                        <a
                                            :href="file.file"
                                            class="ml-2 flex-1 w-0 truncate text-sky-400 hover:text-sky-600"
                                        >
                                            {{ file.name }}
                                        </a>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div
                class="bg-white shadow rounded-lg mt-3 border-t-2 border-sky-500"
                v-if="
                    hasRolesID([pelatihan.status.role_id]) &&
                    (pelatihan.status_id == 2 || pelatihan.status_id == 5)
                "
            >
                <form @submit.prevent="saveBobot">
                    <div
                        class="p-3 border-b rounded-t-lg bg-gray-50 text-lg font-semibold flex items-center"
                    >
                        <PencilSquareIcon class="w-6 h-6 mr-2" /> Form
                        Pembobotan Nilai
                    </div>
                    <div class="p-3 overflow-auto">
                        <table>
                            <tr
                                v-for="(item, index) in pelatihan.jabatan"
                                :key="index"
                            >
                                <td class="td">{{ item.jabatan.jabatan }}</td>
                                <td class="td">:</td>
                                <td class="whitespace-nowrap">
                                    <div
                                        class="border flex items-center rounded border-gray-300"
                                    >
                                        <input
                                            v-model="form[item.kd_jabatan]"
                                            type="number"
                                            min="0"
                                            max="100"
                                            class="focus:ring-sky-300 py-1.5 focus:border-sky-400 w-full border-0 rounded-l sm:text-sm border-gray-300 px-2"
                                            required
                                        />
                                        <span
                                            class="position-absolute right-0 px-2 py-1.5 border-l-0 rounded-r sm:text-sm border-gray-300"
                                            >%</span
                                        >
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="td">
                                    Pernah Mengikuti Pelatihan Fungsional
                                </td>
                                <td class="td">:</td>
                                <td>
                                    <div
                                        class="border flex items-center rounded border-gray-300"
                                    >
                                        <input
                                            v-model="form.ikut_pelatihan"
                                            type="number"
                                            min="0"
                                            max="100"
                                            class="focus:ring-sky-300 py-1.5 focus:border-sky-400 w-full border-0 rounded-l sm:text-sm border-gray-300 px-2"
                                            required
                                        />
                                        <span
                                            class="position-absolute right-0 px-2 py-1.5 border-l-0 rounded-r sm:text-sm border-gray-300"
                                            >%</span
                                        >
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="td">
                                    Belum Pernah Mengikuti Pelatihan Fungsional
                                </td>
                                <td class="td">:</td>
                                <td>
                                    <div
                                        class="border flex items-center rounded border-gray-300"
                                    >
                                        <input
                                            v-model="form.tidak_pelatihan"
                                            type="number"
                                            min="0"
                                            max="100"
                                            class="focus:ring-sky-300 py-1.5 focus:border-sky-400 w-full border-0 rounded-l sm:text-sm border-gray-300 px-2"
                                            required
                                        />
                                        <span
                                            class="position-absolute right-0 px-2 py-1.5 border-l-0 rounded-r sm:text-sm border-gray-300"
                                            >%</span
                                        >
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div
                        class="px-6 py-3 border-t bg-gray-50 rounded-b-lg text-center sm:text-left"
                    >
                        <button
                            @click="form.status_id = 4"
                            type="submit"
                            title="Simpan"
                            class="btn btn-md btn-sky w-full sm:w-auto mt-1 sm:mt-0"
                        >
                            <PaperAirplaneIcon class="h-5 w-5 mr-2" />
                            Simpan & Submit
                        </button>
                        <button
                            @click="form.status_id = 2"
                            type="submit"
                            title="Simpan"
                            class="btn btn-md btn-yellow sm:ml-1 w-full sm:w-auto mt-1 sm:mt-0 text-center sm:text-left"
                        >
                            <ArrowDownOnSquareIcon class="h-5 w-5 mr-2" />
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
            <div
                class="bg-white shadow rounded-lg mt-3 border-t-2 border-sky-500"
                v-if="pelatihan.status_id == 4 || pelatihan.status_id > 5"
            >
                <div class="p-3 border-b">
                    <h2
                        class="text-lg leading-6 font-medium inline-flex items-center"
                    >
                        <AcademicCapIcon class="h-6 w-6 mr-2" />Bobot Penilaian
                    </h2>
                </div>
                <div class="overflow-auto pb-3">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    scope="col"
                                    class="text-left pl-6 pr-3 text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    No.
                                </th>
                                <th scope="col" class="text-left th">
                                    Kriteria
                                </th>
                                <th scope="col" class="text-right th">Bobot</th>
                                <th
                                    scope="col"
                                    class="pl-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider text-right"
                                >
                                    Jumlah Peserta
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-for="(item, index) in pelatihan.bobot"
                                :key="index"
                            >
                                <td class="pl-6 pr-3 py-2 align-top w-3">
                                    {{ index + 1 }}.
                                </td>
                                <td class="td">
                                    {{
                                        item.jabatan
                                            ? item.jabatan.jabatan
                                            : custom_var[item.key]
                                    }}
                                </td>
                                <td class="td text-right">{{ item.bobot }}</td>
                                <td class="pl-3 pr-6 py-2 align-top text-right">
                                    {{ riwayat[item.key] }}
                                    Orang
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div
                class="bg-white shadow rounded-lg mt-3 border-t-2 border-sky-500"
                v-if="pelatihan.status_id == 4 || pelatihan.status_id > 5"
            >
                <div class="p-3 border-b">
                    <h2
                        class="text-lg leading-6 font-medium inline-flex items-center"
                    >
                        <AcademicCapIcon class="h-6 w-6 mr-2" />Daftar Peserta
                        yg Memenuhi Syarat
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
                                <option :value="peserta.total">Semua</option>
                            </select>
                        </div>
                        <div class="rounded-md flex pt-3 sm:pt-0 sm:ml-3">
                            <span
                                class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-sky-50 text-gray-500 text-sm"
                            >
                                Status Peserta
                            </span>

                            <select
                                @change.prevent="filters"
                                class="focus:ring-sky-500 focus:border-sky-500 w-full sm:w-auto rounded-none rounded-r-md sm:text-sm border-gray-300"
                                v-model="filter.new"
                            >
                                <option value="">Semua</option>
                                <option value="yes">
                                    Pernah Mengikuti Pelatihan
                                </option>
                                <option value="no">
                                    Belum Pernah Mengikuti Pelatihan
                                </option>
                            </select>
                        </div>
                        <div class="rounded-md flex pt-3 sm:pt-0 sm:ml-3">
                            <span
                                class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-sky-50 text-gray-500 text-sm"
                            >
                                Status Pendaftaran
                            </span>

                            <select
                                @change.prevent="filters"
                                class="focus:ring-sky-500 focus:border-sky-500 w-full sm:w-auto rounded-none rounded-r-md sm:text-sm border-gray-300"
                                v-model="filter.confirmed"
                            >
                                <option value="">Semua</option>
                                <option value="approved">Disetujui</option>
                                <option value="confirmed">Dikonfirmasi</option>
                                <option value="rejected">Ditolak</option>
                                <option value="waiting">
                                    Belum Dikonfirmasi
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
                                <th
                                    scope="col"
                                    class="text-left pl-6 pr-3 text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    No.
                                </th>
                                <th scope="col" class="text-left th">Nip</th>
                                <th scope="col" class="text-left th">Nama</th>
                                <th scope="col" class="text-left th">
                                    Jabatan
                                </th>
                                <th
                                    scope="col"
                                    class="pl-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider text-center"
                                >
                                    Pernah Mengikuti Pelatihan
                                </th>
                                <th
                                    scope="col"
                                    class="text-center th"
                                    v-if="pelatihan.status_id > 7"
                                >
                                    Status
                                </th>
                                <th
                                    v-if="pelatihan.status_id > 7"
                                    scope="col"
                                    class="pl-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider text-right"
                                >
                                    Jumlah Bobot
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td
                                    v-if="peserta.total == 0"
                                    class="td text-center"
                                    colspan="7"
                                >
                                    Kosong
                                </td>
                            </tr>
                            <tr
                                v-for="(item, index) in peserta.data"
                                :key="index"
                            >
                                <td class="pl-6 pr-3 py-2 align-top w-3">
                                    {{
                                        (peserta.current_page - 1) *
                                            peserta.per_page +
                                        index +
                                        1
                                    }}.
                                </td>
                                <td class="td">
                                    {{ item.nip }}
                                </td>
                                <td class="td">{{ item.nama_lengkap }}</td>
                                <td class="td">{{ item.jabatan.jabatan }}</td>

                                <td class="pl-3 pr-6 py-2 align-top">
                                    <CheckIcon
                                        class="h-6 w-6 text-teal-500 mx-auto"
                                        v-if="item.riwayat_pelatihan_count > 0"
                                    />
                                    <XMarkIcon
                                        class="h-6 w-6 text-rose-500 mx-auto"
                                        v-else
                                    />
                                </td>
                                <td
                                    class="td text-center whitespace-nowrap"
                                    v-if="pelatihan.status_id > 7"
                                >
                                    <span
                                        class="px-2 py-0.5 font-semibold text-xs tracking-wider rounded-full shadow bg-teal-200 text-teal-900 shadow-teal-500"
                                        v-if="
                                            item.pendaftaran[0].approved_at !=
                                            null
                                        "
                                    >
                                        Disetujui </span
                                    ><span
                                        class="px-2 py-0.5 font-semibold text-xs tracking-wider rounded-full shadow bg-sky-200 text-sky-900 shadow-sky-500"
                                        v-else-if="
                                            item.pendaftaran[0].confirmed_at !=
                                            null
                                        "
                                    >
                                        Dikonfirmasi
                                    </span>
                                    <span
                                        class="px-2 py-0.5 font-semibold text-xs tracking-wider rounded-full shadow bg-red-200 text-red-900 shadow-red-500"
                                        v-else-if="
                                            item.pendaftaran[0].rejected_at !=
                                            null
                                        "
                                    >
                                        Ditolak </span
                                    ><span
                                        class="px-2 py-0.5 font-semibold text-xs tracking-wider rounded-full shadow bg-yellow-200 text-yellow-900 shadow-yellow-500"
                                        v-else
                                    >
                                        Belum Dikonfirmasi
                                    </span>
                                </td>
                                <td
                                    class="pl-3 pr-6 py-2 align-top text-right"
                                    v-if="pelatihan.status_id > 7"
                                >
                                    {{
                                        item.pendaftaran.length > 0
                                            ? item.pendaftaran[0].jumlah_bobot
                                            : "-"
                                    }}
                                </td>
                            </tr>
                        </tbody>
                        <tfoot
                            class="bg-gray-50 divide-y divide-gray-200"
                            v-if="pelatihan.status_id > 7"
                        >
                            <tr>
                                <td
                                    class="pl-6 pr-3 py-2 align-top"
                                    colspan="6"
                                >
                                    Jumlah Pendaftaran yang Disetujui
                                </td>

                                <td class="pl-3 pr-6 py-2 align-top text-right">
                                    {{ confirmed.approved }}
                                </td>
                            </tr>
                            <tr>
                                <td
                                    class="pl-6 pr-3 py-2 align-top"
                                    colspan="6"
                                >
                                    Jumlah Pendaftaran yang Dikonfirmasi
                                </td>

                                <td class="pl-3 pr-6 py-2 align-top text-right">
                                    {{ confirmed.confirmed }}
                                </td>
                            </tr>
                            <tr>
                                <td
                                    class="pl-6 pr-3 py-2 align-top"
                                    colspan="6"
                                >
                                    Jumlah Pendaftaran yang Ditolak
                                </td>

                                <td class="pl-3 pr-6 py-2 align-top text-right">
                                    {{ confirmed.rejected }}
                                </td>
                            </tr>
                            <tr>
                                <td
                                    class="pl-6 pr-3 py-2 align-top"
                                    colspan="6"
                                >
                                    Jumlah Pendaftaran yang Belum Dikonfirmasi
                                </td>

                                <td class="pl-3 pr-6 py-2 align-top text-right">
                                    {{ confirmed.waiting }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div
                    class="border-t flex flex-col sm:flex-row items-center justify-between p-4"
                >
                    <div>
                        Menampilkan {{ peserta.data.length }} dari
                        {{ peserta.total }} Data
                    </div>
                    <Pagination
                        :links="peserta.links"
                        :only="['peserta', 'filter']"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/Admin.vue";
import { Link } from "@inertiajs/inertia-vue3";
import Pagination from "@/Components/Pagination.vue";
import {
    ChevronLeftIcon,
    ChevronRightIcon,
    HomeIcon,
    ArrowDownOnSquareIcon,
    PlusIcon,
    MinusIcon,
    XMarkIcon,
    CheckCircleIcon,
    PaperClipIcon,
    PaperAirplaneIcon,
    InformationCircleIcon,
    PencilSquareIcon,
    AcademicCapIcon,
    NewspaperIcon,
    CheckIcon,
    MagnifyingGlassIcon,
} from "@heroicons/vue/24/solid";
export default defineComponent({
    components: {
        AppLayout,
        Link,
        ChevronLeftIcon,
        ChevronRightIcon,
        HomeIcon,
        ArrowDownOnSquareIcon,
        PlusIcon,
        MinusIcon,
        XMarkIcon,
        CheckCircleIcon,
        PaperClipIcon,
        PaperAirplaneIcon,
        InformationCircleIcon,
        PencilSquareIcon,
        AcademicCapIcon,
        NewspaperIcon,
        CheckIcon,
        MagnifyingGlassIcon,
        Pagination,
    },
    props: {
        title: String,
        pelatihan: Object,
        riwayat: Object,
        filter: Object,
        peserta: Object,
        confirmed: Object,
    },

    data() {
        let fields = [];
        let jumlah_bobot = 0;
        this.pelatihan.jabatan.forEach((item) => {
            fields[item.kd_jabatan] = null;
        });
        fields["ikut_pelatihan"] = null;
        fields["tidak_pelatihan"] = null;
        if (this.pelatihan.bobot) {
            this.pelatihan.bobot.forEach((item) => {
                fields[item.key] = item.bobot;
                jumlah_bobot = jumlah_bobot + item.bobot;
            });
        }

        fields["status_id"] = this.pelatihan.status_id;

        let curr_date = new Date();

        return {
            jumlah_bobot: jumlah_bobot,
            form: this.$inertia.form(fields),
            curr_date: curr_date,
            today:
                curr_date.getFullYear() +
                "/" +
                (curr_date.getMonth() + 1) +
                "/" +
                curr_date.getDate(),
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
            custom_var: {
                ikut_pelatihan: "Pernah Mengikuti Pelatihan Fungsional",
                tidak_pelatihan: "Belum Pernah Mengikuti Pelatihan Fungsional",
            },
        };
    },
    methods: {
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
        submit(status) {
            Swal.fire({
                text: "Apakah anda yakin akan mengirim data pelatihan ini?",
                icon: "question",
                confirmButtonText: "Ya, Lanjutkan",
                cancelButtonText: "Batalkan",
                showCancelButton: true,
                confirmButtonColor: "#0ea5e9",
                cancelButtonColor: "#ef4444",
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$inertia.post(
                        this.route("pelatihan.process", this.pelatihan.slug),
                        {
                            status_id: status,
                        },
                        { only: ["pelatihan", "flash", "errors"] }
                    );
                }
            });
        },
        saveBobot() {
            Swal.fire({
                text: "Apakah anda yakin akan menyimpan data pelatihan ini?",
                icon: "question",
                confirmButtonText: "Ya, Lanjutkan",
                cancelButtonText: "Batalkan",
                showCancelButton: true,
                confirmButtonColor: "#0ea5e9",
                cancelButtonColor: "#ef4444",
            }).then((result) => {
                if (result.isConfirmed) {
                    this.form.post(
                        this.route("bobot.store", this.pelatihan.slug),
                        {
                            only: [
                                "pelatihan",
                                "flash",
                                "errors",
                                "riwayat",
                                "peserta",
                            ],
                        }
                    );
                }
            });
        },
        reject(status) {
            Swal.fire({
                title: "Alasan Penolakan",
                input: "textarea",
                inputAttributes: {
                    autocapitalize: "off",
                },
                showCancelButton: true,
                confirmButtonText: "Reject",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#ef4444",
                cancelButtonColor: "#6b7280",
                preConfirm: function (value) {
                    if (!value) {
                        Swal.showValidationMessage(
                            `Silahkan mengisi alasan penolakan terlebih dahulu`
                        );
                    } else {
                        return value;
                    }
                },
                allowOutsideClick: () => !Swal.isLoading(),
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        text: "Apakah anda yakin akan menolak data pelatihan ini ?",
                        icon: "question",
                        confirmButtonText: "Ya, Lanjutkan",
                        cancelButtonText: "Batalkan",
                        showCancelButton: true,
                        confirmButtonColor: "#0ea5e9",
                        cancelButtonColor: "#ef4444",
                    }).then((result1) => {
                        if (result1.isConfirmed) {
                            this.$inertia.post(
                                this.route(
                                    "pelatihan.process",
                                    this.pelatihan.slug
                                ),
                                {
                                    status_id: status,
                                    keterangan: result.value,
                                },
                                {
                                    only: ["flash", "pelatihan"],
                                }
                            );
                        }
                    });
                }
            });
        },
        approve(status) {
            Swal.fire({
                text: "Apakah anda yakin akan menyetujui data pelatihan ini?",
                icon: "question",
                confirmButtonText: "Ya, Lanjutkan",
                cancelButtonText: "Batalkan",
                showCancelButton: true,
                confirmButtonColor: "#0ea5e9",
                cancelButtonColor: "#ef4444",
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$inertia.post(
                        this.route("pelatihan.process", this.pelatihan.slug),
                        {
                            status_id: status,
                        },
                        { only: ["pelatihan", "peserta", "flash", "errors"] }
                    );
                }
            });
        },
        filters() {
            this.$inertia.get(
                this.route("pelatihan.show", this.pelatihan.slug),
                this.filter,
                {
                    preserveState: true,
                    preserveScroll: true,
                    only: ["filter", "peserta"],
                }
            );
        },
        batasKonfirmasi() {
            let today = this.today;
            Swal.fire({
                title: "Batas Waktu Konfirmasi",
                html: '<input type="date" class="focus:ring-sky-300 focus:border-sky-400 w-full rounded-lg text-xl border-gray-300 px-6 py-3" id="batas-konfirmasi">',
                stopKeydownPropagation: false,
                showCancelButton: true,
                confirmButtonText: "Lanjutkan",
                cancelButtonText: "Tutup",
                confirmButtonColor: "#0ea5e9",
                cancelButtonColor: "#ef4444",
                preConfirm: function () {
                    let batas =
                        document.getElementById("batas-konfirmasi").value;

                    if (!batas) {
                        Swal.showValidationMessage(
                            "Silahkan mengisi batas konfirmasi terlebih dahulu"
                        );
                    } else if (
                        new Date(batas).getTime() < new Date(today).getTime()
                    ) {
                        Swal.showValidationMessage(
                            "Batas konfirmasi sudah berlalu"
                        );
                    } else {
                        return batas;
                    }
                },
                allowOutsideClick: () => !Swal.isLoading(),
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        text: "Apakah anda yakin akan menyimpan pelatihan ini ?",
                        icon: "question",
                        confirmButtonText: "Ya, Lanjutkan",
                        cancelButtonText: "Batalkan",
                        showCancelButton: true,
                        confirmButtonColor: "#0ea5e9",
                        cancelButtonColor: "#ef4444",
                    }).then((result1) => {
                        if (result1.isConfirmed) {
                            this.$inertia.post(
                                this.route(
                                    "pelatihan.process",
                                    this.pelatihan.slug
                                ),
                                {
                                    status_id: 8,
                                    batas_konfirmasi: result.value,
                                },
                                {
                                    only: [
                                        "flash",
                                        "pelatihan",
                                        "peserta",
                                        "confirmed",
                                    ],
                                }
                            );
                        }
                    });
                }
            });
        },
        batalkan() {
            Swal.fire({
                title: "Alasan Pembatalan",
                input: "textarea",
                inputAttributes: {
                    autocapitalize: "off",
                },
                showCancelButton: true,
                confirmButtonText: "Reject",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#ef4444",
                cancelButtonColor: "#6b7280",
                preConfirm: function (value) {
                    if (!value) {
                        Swal.showValidationMessage(
                            `Silahkan mengisi alasan pembatalan terlebih dahulu`
                        );
                    } else {
                        return value;
                    }
                },
                allowOutsideClick: () => !Swal.isLoading(),
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        text: "Apakah anda yakin akan membatalkan pelatihan ini ?",
                        icon: "question",
                        confirmButtonText: "Ya, Lanjutkan",
                        cancelButtonText: "Tutup",
                        showCancelButton: true,
                        confirmButtonColor: "#0ea5e9",
                        cancelButtonColor: "#ef4444",
                    }).then((result1) => {
                        if (result1.isConfirmed) {
                            this.$inertia.post(
                                this.route(
                                    "pelatihan.process",
                                    this.pelatihan.slug
                                ),
                                {
                                    status_id: 7,
                                    keterangan: result.value,
                                },
                                {
                                    only: ["flash", "pelatihan"],
                                }
                            );
                        }
                    });
                }
            });
        },
        ubahTanggal() {
            Swal.fire({
                title: "Tanggal Pendaftaran Pelatihan",
                html: '<input type="date" class="focus:ring-sky-300 focus:border-sky-400 w-full rounded-lg text-xl border-gray-300 px-6 py-2" id="mulai-pendaftaran"><div class="py-2">Sampai Dengan</div><input type="date" class="focus:ring-sky-300 focus:border-sky-400 w-full rounded-lg text-xl border-gray-300 px-6 py-2" id="selesai-pendaftaran">',
                stopKeydownPropagation: false,
                showCancelButton: true,
                confirmButtonText: "Submit",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#0ea5e9",
                cancelButtonColor: "#ef4444",
                preConfirm: function () {
                    let mulai_pendaftaran =
                        document.getElementById("mulai-pendaftaran").value;
                    let selesai_pendaftaran = document.getElementById(
                        "selesai-pendaftaran"
                    ).value;

                    if (
                        new Date(mulai_pendaftaran).getTime() >=
                        new Date(selesai_pendaftaran).getTime()
                    ) {
                        Swal.showValidationMessage(
                            "Tanggal mulai pelatihan tidak boleh lebih besar dari tanggal selesai pelatihan"
                        );
                    } else {
                        return {
                            mulai_pendaftaran: mulai_pendaftaran,
                            selesai_pendaftaran: selesai_pendaftaran,
                        };
                    }
                },
                allowOutsideClick: () => !Swal.isLoading(),
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        text: "Apakah anda yakin mengubah tanggal pelatihan ini ?",
                        icon: "question",
                        confirmButtonText: "Ya, Lanjutkan",
                        cancelButtonText: "Batalkan",
                        showCancelButton: true,
                        confirmButtonColor: "#0ea5e9",
                        cancelButtonColor: "#ef4444",
                    }).then((result1) => {
                        if (result1.isConfirmed) {
                            this.$inertia.post(
                                this.route(
                                    "pelatihan.process",
                                    this.pelatihan.slug
                                ),
                                {
                                    status_id: 6,
                                    mulai_pendaftaran:
                                        result.value.mulai_pendaftaran,
                                    selesai_pendaftaran:
                                        result.value.selesai_pendaftaran,
                                },
                                {
                                    only: ["flash", "pelatihan"],
                                }
                            );
                        }
                    });
                }
            });
        },
        ucfirst(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
    },
});
</script>
