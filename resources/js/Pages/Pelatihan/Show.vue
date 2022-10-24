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
                <div class="flex flex-col sm:flex-row">
                    <button
                        type="button"
                        @click.prevent="submit(pelatihan.status.next_id)"
                        class="btn btn-md btn-teal"
                        v-if="
                            (pelatihan.status_id == 1 ||
                                ((pelatihan.status_id == 2 ||
                                    pelatihan.status_id == 3 ||
                                    pelatihan.status_id == 7) &&
                                    bobots.length > 0)) &&
                            hasRolesID([pelatihan.status.role_id])
                        "
                    >
                        <PaperAirplaneIcon class="h-5 w-5 mr-2" /> Submit
                        Pelatihan
                    </button>

                    <div
                        v-if="
                            (pelatihan.status_id == 2 ||
                                pelatihan.status_id == 3) &&
                            hasRolesID([1]) &&
                            status.registered == 0 &&
                            new Date(today).getTime() >
                                new Date(
                                    pelatihan.selesai_pendaftaran
                                ).getTime()
                        "
                    >
                        <button
                            type="button"
                            @click.prevent="tanggalPendaftaran"
                            class="btn btn-md btn-yellow ml-1"
                        >
                            <PencilSquareIcon class="h-5 w-5 mr-2" /> Tanggal
                            Pendaftaran
                        </button>
                        <button
                            type="button"
                            @click.prevent="batalkanPelatihan"
                            class="btn btn-md btn-red ml-1"
                        >
                            <XMarkIcon class="h-5 w-5 mr-2" /> Batalkan
                            Pelatihan
                        </button>
                    </div>
                    <div
                        v-if="
                            pelatihan.status_id == 4 &&
                            new Date(today).getTime() >
                                new Date(
                                    pelatihan.selesai_pendaftaran
                                ).getTime() &&
                            hasRolesID([pelatihan.status.role_id])
                        "
                    >
                        <button
                            type="button"
                            v-if="status.registered > 0 || status.confirmed > 0"
                            @click.prevent="showUploadPemanggilan = true"
                            class="btn btn-md btn-sky"
                        >
                            <CheckIcon class="h-5 w-5 mr-2" /> Lanjutkan
                            Pelatihan
                        </button>
                        <button
                            type="button"
                            v-if="pelatihan.kuota > status.registered"
                            @click.prevent="tanggalPendaftaran"
                            class="btn btn-md btn-yellow ml-1"
                        >
                            <PencilSquareIcon class="h-5 w-5 mr-2" /> Tanggal
                            Pendaftaran
                        </button>
                        <button
                            type="button"
                            v-if="
                                status.registered == 0 && status.confirmed == 0
                            "
                            @click.prevent="batalkanPelatihan"
                            class="btn btn-md btn-red ml-1"
                        >
                            <XMarkIcon class="h-5 w-5 mr-2" /> Batalkan
                            Pelatihan
                        </button>
                    </div>
                    <div
                        v-if="
                            pelatihan.status_id == 5 &&
                            hasRolesID([pelatihan.status.role_id]) &&
                            new Date(today).getTime() >
                                new Date(pelatihan.batas_konfirmasi).getTime()
                        "
                    >
                        <button
                            v-if="status.confirmed > 0"
                            type="button"
                            @click.prevent="submit(pelatihan.status.next_id)"
                            class="btn btn-md btn-sky"
                        >
                            <CheckIcon class="h-5 w-5 mr-2" /> Lanjutkan
                            Pelatihan
                        </button>
                        <button
                            v-if="pelatihan.kuota > status.confirmed"
                            type="button"
                            @click.prevent="batasKonfirmasi()"
                            class="btn btn-md btn-yellow ml-1"
                        >
                            <PencilSquareIcon class="h-5 w-5 mr-2" /> Batas
                            Konfirmasi
                        </button>
                        <button
                            v-if="pelatihan.kuota > status.confirmed"
                            type="button"
                            @click.prevent="
                                revisiPembobotan(pelatihan.status.prev_id)
                            "
                            class="btn btn-md btn-violet ml-1"
                        >
                            <ScaleIcon class="h-5 w-5 mr-2" /> Revisi Pembobotan
                        </button>
                        <button
                            type="button"
                            v-if="status.confirmed == 0"
                            @click.prevent="batalkanPelatihan"
                            class="btn btn-md btn-red ml-1"
                        >
                            <XMarkIcon class="h-5 w-5 mr-2" /> Batalkan
                            Pelatihan
                        </button>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow rounded-lg border-t-2 border-sky-500">
                <div class="p-3 border-b">
                    <h2
                        class="text-lg leading-6 font-medium inline-flex items-center"
                    >
                        <NewspaperIcon class="h-6 w-6 mr-2" />Katalog Pelatihan
                    </h2>
                </div>
                <div class="overflow-auto pb-3">
                    <table class="min-w-full">
                        <tr class="bg-gray-50">
                            <td
                                class="pl-6 pr-3 align-top py-2 whitespace-nowrap"
                            >
                                Jenis Pelatihan
                            </td>
                            <td class="td">:</td>
                            <td class="pl-3 pr-6 py-2 align-top text-justify">
                                Pelatihan
                                {{ ucfirst(katalog.jenis_pelatihan) }}
                            </td>
                            <td
                                class="px-3 py-2 align-top w-72 hidden sm:table-cell"
                                v-if="katalog.img"
                                rowspan="12"
                            >
                                <img
                                    :src="katalog.img"
                                    class="w-72 ml-auto rounded-full"
                                    alt="Gambar Ilustrasi"
                                />
                            </td>
                        </tr>
                        <tr v-if="katalog.ket_jabatan">
                            <td
                                class="pl-6 pr-3 align-top py-2 whitespace-nowrap"
                            >
                                Keterangan Jabatan
                            </td>
                            <td class="td">:</td>
                            <td class="pl-3 pr-6 py-2 align-top text-justify">
                                <span
                                    v-for="(item, index) in JSON.parse(
                                        katalog.ket_jabatan
                                    )"
                                    :key="index"
                                    >{{
                                        item +
                                        (index + 1 <
                                        JSON.parse(katalog.ket_jabatan).length
                                            ? ", "
                                            : ".")
                                    }}</span
                                >
                            </td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td
                                class="pl-6 pr-3 align-top py-2 whitespace-nowrap"
                            >
                                Judul Pelatihan
                            </td>
                            <td class="td">:</td>
                            <td class="pl-3 pr-6 py-2 align-top text-justify">
                                {{ katalog.judul }}
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
                                v-html="katalog.deskripsi"
                            ></td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td
                                class="pl-6 pr-3 align-top py-2 whitespace-nowrap"
                            >
                                Silabus
                            </td>
                            <td class="td">:</td>
                            <td
                                class="pl-3 pr-6 py-2 align-top text-justify"
                                v-html="katalog.silabus"
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
                                v-html="katalog.persyaratan"
                            ></td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td
                                class="pl-6 pr-3 align-top py-2 whitespace-nowrap w-10"
                            >
                                Jenis JF yang dapat mengikuti
                            </td>
                            <td class="td w-3">:</td>
                            <td class="pl-3 pr-6 py-2 align-top">
                                <div
                                    v-for="(item, index) in katalog.jabatan"
                                    :key="item.id"
                                >
                                    {{ index + 1 }}.
                                    {{ item.jabatan.jabatan }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="pl-6 pr-3 align-top py-2 whitespace-nowrap w-10"
                            >
                                Instansi Peserta Pelatihan
                            </td>
                            <td class="td w-3">:</td>
                            <td class="pl-3 pr-6 py-2 align-top">
                                {{ instansi[katalog.instansi] }}
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
                        <tr class="bg-gray-50">
                            <td
                                class="pl-6 pr-3 align-top py-2 whitespace-nowrap"
                            >
                                Tanggal Pendaftaran
                            </td>
                            <td class="td">:</td>
                            <td class="pl-3 pr-6 py-2 align-top">
                                {{
                                    dateIndo(pelatihan.mulai_pendaftaran) +
                                    " s/d " +
                                    dateIndo(pelatihan.selesai_pendaftaran)
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="pl-6 pr-3 align-top py-2 whitespace-nowrap"
                            >
                                Tanggal Pelatihan
                            </td>
                            <td class="td">:</td>
                            <td class="pl-3 pr-6 py-2 align-top">
                                {{
                                    dateIndo(pelatihan.mulai_pelatihan) +
                                    " s/d " +
                                    dateIndo(pelatihan.selesai_pelatihan)
                                }}
                            </td>
                        </tr>
                        <tr
                            class="bg-gray-50"
                            v-if="
                                pelatihan.status_id > 4 &&
                                pelatihan.batas_konfirmasi
                            "
                        >
                            <td
                                class="pl-6 pr-3 align-top py-2 whitespace-nowrap"
                            >
                                Batas Konfirmasi
                            </td>
                            <td class="td">:</td>
                            <td class="pl-3 pr-6 py-2 align-top">
                                {{ dateIndo(pelatihan.batas_konfirmasi) }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="pl-6 pr-3 align-top py-2 whitespace-nowrap"
                            >
                                Status
                            </td>
                            <td class="td">:</td>
                            <td class="pl-3 pr-6 py-2 align-top">
                                <span
                                    class="px-2 py-0.5 font-semibold text-xs tracking-wider rounded-full shadow bg-violet-200 text-violet-900 shadow-violet-500"
                                    v-if="
                                        pelatihan.status_id == 6 &&
                                        new Date(
                                            pelatihan.selesai_pelatihan
                                        ).getTime() >=
                                            new Date(today).getTime() &&
                                        new Date(today).getTime() >=
                                            new Date(
                                                pelatihan.mulai_pelatihan
                                            ).getTime()
                                    "
                                    >Pelatihan Sedang Berjalan</span
                                >
                                <span
                                    class="px-2 py-0.5 font-semibold text-xs tracking-wider rounded-full shadow bg-lime-200 text-lime-900 shadow-lime-500"
                                    v-else-if="
                                        pelatihan.status_id == 6 &&
                                        new Date(today).getTime() >
                                            new Date(
                                                pelatihan.selesai_pelatihan
                                            ).getTime()
                                    "
                                    >Pelatihan Selesai</span
                                >
                                <span
                                    v-else
                                    class="px-2 py-0.5 font-semibold text-xs tracking-wider rounded-full shadow"
                                    :class="{
                                        'bg-sky-200 text-sky-900 shadow-sky-500':
                                            pelatihan.status_id == 1,
                                        'bg-yellow-200 text-yellow-900  shadow-yellow-500':
                                            (pelatihan.status_id > 1 &&
                                                pelatihan.status_id < 6) ||
                                            pelatihan.status_id == 7,
                                        'bg-red-200 text-red-900 shadow-red-500':
                                            pelatihan.status_id == 8,
                                        'bg-teal-200 text-teal-900 shadow-teal-500':
                                            pelatihan.status_id == 6,
                                    }"
                                    >{{ pelatihan.status.name }}</span
                                >
                            </td>
                        </tr>
                        <tr class="bg-gray-50" v-if="pelatihan.status_id == 8">
                            <td
                                class="pl-6 pr-3 align-top py-2 whitespace-nowrap"
                            >
                                Alasan Pembatalan
                            </td>
                            <td class="td">:</td>
                            <td class="pl-3 pr-6 py-2 align-top">
                                {{ pelatihan.keterangan }}
                            </td>
                        </tr>
                        <tr v-if="katalog.bahan.length > 0">
                            <td
                                class="pl-6 pr-3 align-top py-2 whitespace-nowrap"
                            >
                                Bahan Pelatihan
                            </td>
                            <td class="td">:</td>
                            <td class="pl-3 pr-6 py-2 align-top">
                                <ul role="list">
                                    <li
                                        v-for="(file, index) in katalog.bahan"
                                        :key="index"
                                        class="flex items-center"
                                    >
                                        <PaperClipIcon
                                            class="h-5 w-5 mr-1 text-gray-400"
                                        />
                                        <a
                                            target="_blank"
                                            :href="file.file"
                                            class="ml-2 flex-1 w-0 truncate text-sky-400 hover:text-sky-600"
                                        >
                                            {{ file.name }}
                                        </a>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr
                            class="bg-gray-50"
                            v-if="pelatihan.surat_pemanggilan"
                        >
                            <td
                                class="pl-6 pr-3 align-top py-2 whitespace-nowrap"
                            >
                                Surat Pemanggilan
                            </td>
                            <td class="td">:</td>
                            <td
                                class="pl-3 pr-6 py-2 align-top flex items-center"
                            >
                                <PaperClipIcon
                                    class="h-5 w-5 mr-1 text-gray-400"
                                />
                                <a
                                    target="_blank"
                                    :href="pelatihan.surat_pemanggilan"
                                    class="ml-2 flex-1 w-0 truncate text-sky-400 hover:text-sky-600"
                                >
                                    Download
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="mt-3 sm:text-right">
                <button
                    v-if="
                        (pelatihan.status_id == 2 ||
                            pelatihan.status_id == 3 ||
                            pelatihan.status_id == 7) &&
                        hasRolesID([pelatihan.status.role_id])
                    "
                    type="button"
                    class="btn btn-md btn-sky"
                    @click="
                        (showModal = !showModal),
                            (bobot.id = null),
                            (bobot.key = ''),
                            (bobot.bobot = null),
                            (bobot.nilai = null),
                            (bobot.kd_jabatan = '')
                    "
                >
                    <PlusIcon class="h-5 w-5 mr-2" />Bobot
                </button>
            </div>
            <div
                class="bg-white shadow rounded-lg mt-3 border-t-2 border-sky-500"
                v-if="pelatihan.status_id > 1"
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
                                <th scope="col" class="text-left th">
                                    Jenis JF
                                </th>
                                <th scope="col" class="text-right th">
                                    Nilai Minimal
                                </th>
                                <th scope="col" class="text-right th">Bobot</th>
                                <th
                                    scope="col"
                                    class="text-right pl-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Jumlah Peserta
                                </th>
                                <th
                                    scope="col"
                                    class="pl-3 pr-6 text-xs font-medium text-gray-500 uppercase tracking-wider text-center"
                                    v-if="
                                        (pelatihan.status_id == 2 ||
                                            pelatihan.status_id == 3 ||
                                            pelatihan.status_id == 7) &&
                                        hasRolesID([pelatihan.status.role_id])
                                    "
                                >
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-if="bobots.length == 0">
                                <td colspan="8" class="text-center">Kosong</td>
                            </tr>
                            <tr v-for="(item, index) in bobots" :key="index">
                                <td
                                    class="pl-6 pr-3 py-2 align-top w-1 whitespace-nowrap"
                                >
                                    {{ index + 1 }}.
                                </td>
                                <td class="td">
                                    {{ item.variable.value }}
                                </td>
                                <td class="td">
                                    {{
                                        item.jabatan
                                            ? item.jabatan.jabatan
                                            : "-"
                                    }}
                                </td>
                                <td class="td text-right">
                                    {{ item.nilai ? item.nilai + "%" : "-" }}
                                </td>
                                <td class="td text-right">{{ item.bobot }}%</td>
                                <td class="pl-3 pr-6 py-2 align-top text-right">
                                    {{
                                        item.jumlah_peserta
                                            ? item.jumlah_peserta + " Orang"
                                            : "0"
                                    }}
                                </td>
                                <td
                                    class="pl-3 pr-6 py-2 align-top text-center"
                                    v-if="
                                        (pelatihan.status_id == 2 ||
                                            pelatihan.status_id == 3 ||
                                            pelatihan.status_id == 7) &&
                                        hasRolesID([pelatihan.status.role_id])
                                    "
                                >
                                    <button
                                        type="button"
                                        title="Ubah"
                                        @click="
                                            (showModal = true),
                                                (bobot.id = item.id),
                                                (bobot.key = item.key),
                                                (bobot.nilai = item.nilai),
                                                (bobot.kd_jabatan =
                                                    item.kd_jabatan),
                                                (bobot.bobot = item.bobot)
                                        "
                                        class="ml-1 btn btn-sm btn-yellow"
                                    >
                                        <PencilSquareIcon class="h-5 w-5" />
                                    </button>
                                    <button
                                        title="Hapus"
                                        type="button"
                                        @click="deleteBobot(item.id)"
                                        class="ml-1 btn btn-sm btn-red"
                                    >
                                        <TrashIcon class="h-5 w-5" />
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div
                class="text-right mt-3"
                v-if="
                    pelatihan.status_id == 6 ||
                    pelatihan.status_id == 5 ||
                    (pelatihan.status_id == 4 &&
                        new Date(today).getTime() >
                            new Date(pelatihan.selesai_pendaftaran).getTime() &&
                        (status.registered > 0 || status.confirmed > 0))
                "
            >
                <a
                    v-if="hasRolesID([pelatihan.status.role_id])"
                    target="_blank"
                    :href="route('peserta.export', pelatihan.slug)"
                    class="btn btn-md btn-teal"
                    ><ArrowDownTrayIcon class="h-5 w-5 mr-2" /><span
                        v-if="pelatihan.status_id == 6"
                        >Export Peserta</span
                    ><span v-else>Export Calon Peserta</span></a
                >
            </div>
            <div
                class="bg-white shadow rounded-lg mt-3 border-t-2 border-sky-500"
                v-if="pelatihan.status_id > 1"
            >
                <div class="p-3 border-b">
                    <h2
                        class="text-lg leading-6 font-medium inline-flex items-center"
                    >
                        <UsersIcon class="h-6 w-6 mr-2" />Daftar Peserta
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
                        <div
                            class="rounded-md flex pt-3 sm:pt-0 sm:ml-3"
                            v-if="pelatihan.status_id > 1"
                        >
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
                                <option value="registered">Terdaftar</option>
                                <option
                                    value="unregistered"
                                    v-if="hasRolesID([2, 3])"
                                >
                                    Tidak Terdaftar
                                </option>
                                <option value="confirmed">Dikonfirmasi</option>
                                <option value="approved">Disetujui</option>
                                <option value="rejected">Ditolak</option>
                            </select>
                        </div>
                        <div class="rounded-md flex sm:ml-auto mt-3 sm:mt-0">
                            <select
                                class="focus:ring-sky-500 focus:border-sky-500 w-full sm:w-50 rounded-none rounded-l-md sm:text-sm bg-sky-50 text-gray-500 text-sm border-gray-300 border-r-transparent"
                                v-model="filter.key"
                            >
                                <option value="nip">NIP</option>
                                <option value="nama">Nama</option>
                                <option value="jabatan">Jabatan</option>
                                <option
                                    value="jumlah_bobot"
                                    v-if="hasRolesID([2, 3])"
                                >
                                    Jumlah Bobot
                                </option>
                            </select>
                            <input
                                @keyup.enter="filters"
                                type="text"
                                v-model="filter.search"
                                class="focus:ring-sky-500 focus:border-sky-500 w-full sm:w-auto rounded-none sm:text-sm border-gray-300"
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
                                <th scope="col" class="text-left th">Foto</th>
                                <th scope="col" class="text-left th">Nip</th>
                                <th scope="col" class="text-left th">Nama</th>
                                <th scope="col" class="text-left th">
                                    Jabatan
                                </th>

                                <th
                                    scope="col"
                                    class="text-center th"
                                    v-if="pelatihan.status_id > 1"
                                >
                                    Status
                                </th>
                                <th
                                    v-if="
                                        pelatihan.status_id > 1 &&
                                        bobots.length > 0 &&
                                        hasRolesID([2, 3])
                                    "
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
                                    colspan="6"
                                >
                                    Kosong
                                </td>
                            </tr>
                            <tr
                                v-for="(item, index) in peserta.data"
                                :key="index"
                            >
                                <td
                                    class="pl-6 pr-3 py-2 align-top w-1 whitespace-nowrap"
                                    :class="{
                                        // 'bg-teal-400 text-white':
                                        //   (peserta.current_page - 1) * peserta.per_page +
                                        //     index +
                                        //     1 <=
                                        //     pelatihan.kuota &&
                                        //   !filter.status &&
                                        //   !filter.search &&
                                        //   item.pendaftaran[0].registered_at,
                                        // 'bg-red-400 text-white':
                                        //   (peserta.current_page - 1) * peserta.per_page +
                                        //     index +
                                        //     1 >
                                        //     pelatihan.kuota &&
                                        //   !filter.status &&
                                        //   !filter.search &&
                                        //   item.pendaftaran[0].registered_at,
                                    }"
                                >
                                    {{
                                        (peserta.current_page - 1) *
                                            peserta.per_page +
                                        index +
                                        1
                                    }}.
                                </td>

                                <td class="td">
                                    <a
                                        v-if="item.approved_foto"
                                        target="_blank"
                                        :href="
                                            'https://metrologi.kemendag.go.id/sdmk/public' +
                                            item.approved_foto
                                        "
                                        ><img
                                            class="h-8 w-8 rounded-full object-cover"
                                            :src="
                                                'https://metrologi.kemendag.go.id/sdmk/public' +
                                                item.approved_foto
                                            "
                                            alt="N/A"
                                        />
                                    </a>

                                    <div
                                        v-else
                                        class="h-8 w-8 bg-slate-500 rounded-full flex items-center justify-center text-white text-xs shadow shadow-slate-900"
                                    >
                                        N/A
                                    </div>
                                </td>
                                <td class="td">
                                    {{ item.nip }}
                                </td>
                                <td class="td">{{ item.nama_lengkap }}</td>
                                <td class="td">{{ item.jabatan.jabatan }}</td>

                                <td
                                    class="td text-center whitespace-nowrap"
                                    v-if="pelatihan.status_id > 1"
                                >
                                    <span
                                        class="px-2 py-0.5 font-semibold text-xs tracking-wider rounded-full shadow bg-teal-200 text-teal-900 shadow-teal-500"
                                        v-if="item.pendaftaran[0].approved_at"
                                    >
                                        Disetujui </span
                                    ><span
                                        class="px-2 py-0.5 font-semibold text-xs tracking-wider rounded-full shadow bg-red-200 text-red-900 shadow-red-500"
                                        v-else-if="
                                            item.pendaftaran[0].rejected_at
                                        "
                                    >
                                        Ditolak </span
                                    ><span
                                        class="px-2 py-0.5 font-semibold text-xs tracking-wider rounded-full shadow bg-sky-200 text-sky-900 shadow-sky-500"
                                        v-else-if="
                                            item.pendaftaran[0].confirmed_at &&
                                            !item.pendaftaran[0].approved_at
                                        "
                                    >
                                        Dikonfirmasi </span
                                    ><span
                                        class="px-2 py-0.5 font-semibold text-xs tracking-wider rounded-full shadow bg-yellow-200 text-yellow-900 shadow-yellow-500"
                                        v-else-if="
                                            item.pendaftaran[0].registered_at &&
                                            !item.pendaftaran[0].confirmed_at
                                        "
                                    >
                                        Terdaftar
                                    </span>
                                    <span
                                        class="px-2 py-0.5 font-semibold text-xs tracking-wider rounded-full shadow bg-gray-200 text-gray-900 shadow-gray-500"
                                        v-else
                                    >
                                        Tidak Terdaftar
                                    </span>
                                </td>
                                <td
                                    class="pl-3 pr-6 py-2 align-top text-right"
                                    v-if="
                                        pelatihan.status_id > 1 &&
                                        bobots.length > 0 &&
                                        hasRolesID([2, 3])
                                    "
                                >
                                    {{ item.pendaftaran[0].jumlah_bobot }}
                                </td>
                            </tr>
                        </tbody>
                        <tfoot
                            class="bg-gray-50 divide-y divide-gray-200"
                            v-if="pelatihan.status_id > 1 && hasRolesID([2, 3])"
                        >
                            <tr>
                                <td
                                    class="pl-6 pr-3 py-2 align-top"
                                    colspan="6"
                                >
                                    Jumlah Peserta yang Disetujui
                                </td>

                                <td class="pl-3 pr-6 py-2 align-top text-right">
                                    {{ status.approved }}
                                </td>
                            </tr>
                            <tr>
                                <td
                                    class="pl-6 pr-3 py-2 align-top"
                                    colspan="6"
                                >
                                    Jumlah Peserta yang Ditolak
                                </td>

                                <td class="pl-3 pr-6 py-2 align-top text-right">
                                    {{ status.rejected }}
                                </td>
                            </tr>
                            <tr>
                                <td
                                    class="pl-6 pr-3 py-2 align-top"
                                    colspan="6"
                                >
                                    Jumlah Peserta yang Dikonfirmasi
                                </td>

                                <td class="pl-3 pr-6 py-2 align-top text-right">
                                    {{ status.confirmed }}
                                </td>
                            </tr>
                            <tr>
                                <td
                                    class="pl-6 pr-3 py-2 align-top"
                                    colspan="6"
                                >
                                    Jumlah Peserta yang Terdaftar
                                </td>

                                <td class="pl-3 pr-6 py-2 align-top text-right">
                                    {{ status.registered }}
                                </td>
                            </tr>
                            <tr>
                                <td
                                    class="pl-6 pr-3 py-2 align-top"
                                    colspan="6"
                                >
                                    Jumlah Peserta yang Tidak Terdaftar
                                </td>

                                <td class="pl-3 pr-6 py-2 align-top text-right">
                                    {{ status.unregistered }}
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
        <form
            @submit.prevent="saveBobot"
            method="post"
            v-if="
                (pelatihan.status_id == 2 ||
                    pelatihan.status_id == 3 ||
                    pelatihan.status_id == 7) &&
                hasRolesID([pelatihan.status.role_id])
            "
        >
            <Modal :show="showModal">
                <div class="bg-gray-50 px-4 py-3 sm:px-6">
                    <div
                        class="text-xl font-semibold tracking-wider flex items-center text-gray-500"
                    >
                        <PencilSquareIcon class="w-6 h-6 mr-2" /> Form Bobot
                    </div>
                </div>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="grid grid-cols-3 gap-3">
                        <div class="col-span-1">Kriteria</div>
                        <div class="col-span-2">
                            <select
                                v-model="bobot.key"
                                class="f-input px-2 focus:border-transparent"
                                required
                            >
                                <option value="">Pilih</option>
                                <option
                                    v-for="(item, index) in kriteria"
                                    :key="index"
                                    :value="item.key"
                                >
                                    {{ item.value }}
                                </option>
                            </select>
                        </div>
                        <div
                            class="col-span-1"
                            v-if="
                                bobot.key == 'angka_kredit' ||
                                bobot.key == 'prestasi'
                            "
                        >
                            Jenis JF
                        </div>
                        <div
                            class="col-span-2"
                            v-if="
                                bobot.key == 'angka_kredit' ||
                                bobot.key == 'prestasi'
                            "
                        >
                            <select
                                v-model="bobot.kd_jabatan"
                                class="f-input px-2 focus:border-transparent"
                                required
                            >
                                <option value="">Pilih</option>
                                <option
                                    v-for="(item, index) in katalog.jabatan"
                                    :key="index"
                                    :value="item.jabatan.kd_jabatan"
                                >
                                    {{ item.jabatan.jabatan }}
                                </option>
                            </select>
                        </div>
                        <div
                            class="col-span-1"
                            v-if="
                                bobot.key == 'angka_kredit' ||
                                bobot.key == 'prestasi'
                            "
                        >
                            Nilai Minimal
                        </div>
                        <div
                            class="col-span-2"
                            v-if="
                                bobot.key == 'angka_kredit' ||
                                bobot.key == 'prestasi'
                            "
                        >
                            <div
                                class="border flex items-center rounded border-gray-300"
                            >
                                <input
                                    v-model="bobot.nilai"
                                    type="number"
                                    min="0"
                                    max="100"
                                    class="focus:ring-sky-300 py-1.5 focus:border-sky-400 w-full border-0 rounded-l sm:text-sm border-gray-300 px-2"
                                    required
                                />
                                <span
                                    class="position-absolute border-l right-0 px-2 py-1.5 rounded-r sm:text-sm border-gray-300"
                                    >%</span
                                >
                            </div>
                        </div>
                        <div class="col-span-1">Bobot</div>
                        <div class="col-span-2">
                            <div
                                class="border flex items-center rounded border-gray-300"
                            >
                                <input
                                    v-model="bobot.bobot"
                                    type="number"
                                    min="0"
                                    max="100"
                                    class="focus:ring-sky-300 py-1.5 focus:border-sky-400 w-full border-0 rounded-l sm:text-sm border-gray-300 px-2"
                                    required
                                />
                                <span
                                    class="position-absolute border-l right-0 px-2 py-1.5 rounded-r sm:text-sm border-gray-300"
                                    >%</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 text-right">
                    <button
                        type="submit"
                        title="Simpan"
                        class="btn btn-md btn-sky"
                    >
                        <ArrowDownOnSquareIcon class="h-5 w-5 mr-1" />
                        Simpan
                    </button>
                    <button
                        @click="showModal = false"
                        type="button"
                        class="btn btn-sm btn-red ml-1"
                    >
                        <XMarkIcon class="w-5 h-5 mr-1" /> Batal
                    </button>
                </div>
            </Modal>
        </form>
        <form
            @submit.prevent="uploadPemanggilan"
            method="post"
            v-if="
                pelatihan.status_id == 4 &&
                hasRolesID([pelatihan.status.role_id])
            "
        >
            <Modal :show="showUploadPemanggilan">
                <div class="bg-gray-50 px-4 py-3 sm:px-6">
                    <div
                        class="text-xl font-semibold tracking-wider flex items-center text-gray-500"
                    >
                        <PencilSquareIcon class="w-6 h-6 mr-2" /> Form
                        Pemanggilan
                    </div>
                </div>
                <div class="bg-white px-4 pt-5 pb-4 sm:px-6 sm:py-4">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Batas Waktu Konfirmasi<i class="text-red-500 tex-xs"
                                >*
                            </i>
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm border">
                            <input
                                type="date"
                                :min="today"
                                :max="max_konfirmasi"
                                v-model="pemanggilan.batas_konfirmasi"
                                class="f-input px-2"
                                required
                            />
                        </div>
                    </div>
                    <div class="mt-3">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Upload Surat Pemanggilan<i
                                class="text-red-500 tex-xs"
                                >*
                            </i>
                            <i class="text-xs text-red-500">(Max: 2MB)</i>
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm border">
                            <input
                                type="file"
                                @input="
                                    pemanggilan.surat_pemanggilan =
                                        $event.target.files[0]
                                "
                                accept=".pdf,.doc,.docx"
                                class="w-full focus:outline-none border-1 border-gray-300 focus:ring-2 focus:ring-sky-300 rounded sm:text-sm file:border-0 pr-14 file:mr-5 file:py-1.5 file:px-2 file:rounded-l file:text-md file:font-semibold file:bg-gray-300 file:text-gray-600 hover:file:cursor-pointer hover:file:bg-gray-400 hover:file:text-gray-700"
                                required
                            />
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 text-right">
                    <button
                        type="submit"
                        title="Simpan"
                        class="btn btn-md btn-sky"
                    >
                        <ArrowDownOnSquareIcon class="h-5 w-5 mr-1" />
                        Simpan
                    </button>
                    <button
                        @click="showUploadPemanggilan = false"
                        type="button"
                        class="btn btn-sm btn-red ml-1"
                    >
                        <XMarkIcon class="w-5 h-5 mr-1" /> Batal
                    </button>
                </div>
            </Modal>
        </form>
    </AppLayout>
</template>

<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/Admin.vue";
import Pagination from "@/Components/Pagination.vue";
import Modal from "@/Components/Modal.vue";
import {
    ChevronLeftIcon,
    ChevronRightIcon,
    ArrowDownOnSquareIcon,
    PlusIcon,
    XMarkIcon,
    PaperClipIcon,
    InformationCircleIcon,
    PencilSquareIcon,
    AcademicCapIcon,
    NewspaperIcon,
    CheckIcon,
    MagnifyingGlassIcon,
    TrashIcon,
    PaperAirplaneIcon,
    UsersIcon,
    ArrowDownTrayIcon,
    ScaleIcon,
    ArrowUpTrayIcon,
} from "@heroicons/vue/24/solid";

export default defineComponent({
    components: {
        AppLayout,
        Link,
        Pagination,
        Modal,
        ChevronLeftIcon,
        ChevronRightIcon,
        ArrowDownOnSquareIcon,
        PlusIcon,
        XMarkIcon,
        PaperClipIcon,
        InformationCircleIcon,
        PencilSquareIcon,
        AcademicCapIcon,
        NewspaperIcon,
        CheckIcon,
        MagnifyingGlassIcon,
        TrashIcon,
        PaperAirplaneIcon,
        UsersIcon,
        ArrowDownTrayIcon,
        ScaleIcon,
        ArrowUpTrayIcon,
    },
    props: {
        title: String,
        katalog: Object,
        pelatihan: Object,
        filter: Object,
        peserta: Object,
        status: Object,
        kriteria: Array,
        bobots: Array,
    },

    data() {
        let curr_date = new Date();
        let max_konfirmasi =
            new Date(this.pelatihan.mulai_pelatihan).getTime() - 86400000;
        max_konfirmasi = new Date(max_konfirmasi);
        return {
            showModal: false,
            showUploadPemanggilan: false,
            curr_date: curr_date,
            bobot: this.$inertia.form({
                id: null,
                key: "",
                kd_jabatan: "",
                nilai: null,
                bobot: null,
                pelatihan_id: this.pelatihan.id,
            }),
            instansi: {
                pusat: "Pusat",
                uml: "Daerah",
                pusat_uml: " Pusat dan/atau Daerah",
            },
            pemanggilan: this.$inertia.form({
                surat_pemanggilan: null,
                status_id: 5,
                batas_konfirmasi: null,
            }),
            max_konfirmasi:
                max_konfirmasi.getFullYear() +
                "-" +
                (max_konfirmasi.getMonth() + 1) +
                "-" +
                max_konfirmasi.getDate(),
            today:
                curr_date.getFullYear() +
                "-" +
                (curr_date.getMonth() + 1) +
                "-" +
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
        ucfirst(string) {
            return !string
                ? string
                : string.charAt(0).toUpperCase() + string.slice(1);
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
                        {
                            only: ["pelatihan", "peserta", "flash", "errors"],
                        }
                    );
                }
            });
        },
        saveBobot() {
            Swal.fire({
                text: "Apakah anda yakin akan menyimpan data ini?",
                icon: "question",
                confirmButtonText: "Ya, Lanjutkan",
                cancelButtonText: "Batalkan",
                showCancelButton: true,
                confirmButtonColor: "#0ea5e9",
                cancelButtonColor: "#ef4444",
            }).then((result) => {
                if (result.isConfirmed) {
                    this.bobot.post(this.route("bobot.store"), {
                        only: ["bobots", "peserta", "flash", "errors"],
                        onFinish: () => {
                            this.showModal = false;
                        },
                        preserveScroll: true,
                    });
                }
            });
        },
        deleteBobot(item) {
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
                    this.$inertia.delete(this.route("bobot.destroy", item), {
                        preserveScroll: true,
                        only: ["bobots", "peserta", "flash"],
                    });
                }
            });
        },
        batalkanPelatihan() {
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
                        return Swal.showValidationMessage(
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
        tanggalPendaftaran() {
            let mulai_pendaftaran = this.pelatihan.mulai_pendaftaran;
            let selesai_pendaftaran = this.pelatihan.selesai_pendaftaran;
            Swal.fire({
                title: "Tanggal Pendaftaran Pelatihan",
                html:
                    '<input type="date" class="focus:ring-sky-300 focus:border-sky-400 w-full rounded-lg text-xl border-gray-300 px-6 py-2" id="mulai-pendaftaran" value="' +
                    mulai_pendaftaran +
                    '"><div class="py-2">Sampai Dengan</div><input type="date" class="focus:ring-sky-300 focus:border-sky-400 w-full rounded-lg text-xl border-gray-300 px-6 py-2" id="selesai-pendaftaran" value="' +
                    selesai_pendaftaran +
                    '">',
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
                        new Date(mulai_pendaftaran).getTime() >
                        new Date(selesai_pendaftaran).getTime()
                    ) {
                        return Swal.showValidationMessage(
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
                                    status_id: this.pelatihan.status.prev_id,
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
        uploadPemanggilan() {
            let text = "";
            if (!this.pemanggilan.batas_konfirmasi) {
                text = "Silahkan mengisi batas konfirmasi terlebih dahulu";
            } else if (
                new Date(this.pemanggilan.batas_konfirmasi).getTime() <
                new Date(this.today).getTime()
            ) {
                text = "Batas konfirmasi sudah berlalu";
            } else if (
                new Date(this.pemanggilan.batas_konfirmasi).getTime() >=
                new Date(this.pelatihan.mulai_pelatihan).getTime()
            ) {
                text =
                    "Batas Konfirmasi tidak boleh sama atau lebih besar dari Mulai Pelatihan";
            }
            if (text) {
                Swal.fire({
                    text: text,
                    icon: "error",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#0ea5e9",
                });
            } else {
                Swal.fire({
                    text: "Apakah anda yakin akan menyimpan data ini ?",
                    icon: "question",
                    confirmButtonText: "Ya, Lanjutkan",
                    cancelButtonText: "Batalkan",
                    showCancelButton: true,
                    confirmButtonColor: "#0ea5e9",
                    cancelButtonColor: "#ef4444",
                }).then((result1) => {
                    if (result1.isConfirmed) {
                        this.pemanggilan.post(
                            this.route(
                                "pelatihan.process",
                                this.pelatihan.slug
                            ),
                            {
                                only: ["flash", "pelatihan", "errors"],
                                onFinish: () => {
                                    this.showUploadPemanggilan = false;
                                },
                            }
                        );
                    }
                });
            }
        },
        batasKonfirmasi() {
            let today = this.today;
            let mulai_pelatihan = this.pelatihan.mulai_pelatihan;
            Swal.fire({
                title: "Batas Waktu Konfirmasi",
                html:
                    '<input type="date" value="' +
                    this.pelatihan.batas_konfirmasi +
                    '" class="focus:ring-sky-300 focus:border-sky-400 w-full rounded-lg text-xl border-gray-300 px-6 py-3" id="batas-konfirmasi">' +
                    ``,
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
                        return Swal.showValidationMessage(
                            "Silahkan mengisi batas konfirmasi terlebih dahulu"
                        );
                    } else if (
                        new Date(batas).getTime() < new Date(today).getTime()
                    ) {
                        return Swal.showValidationMessage(
                            "Batas konfirmasi sudah berlalu"
                        );
                    } else if (
                        new Date(batas).getTime() >=
                        new Date(mulai_pelatihan).getTime()
                    ) {
                        return Swal.showValidationMessage(
                            "Batas Konfirmasi tidak boleh sama atau lebih besar dari Mulai Pelatihan"
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
                                    status_id: 5,
                                    batas_konfirmasi: result.value,
                                },
                                {
                                    only: ["flash", "pelatihan", "peserta"],
                                }
                            );
                        }
                    });
                }
            });
        },
        revisiPembobotan(status) {
            Swal.fire({
                text: "Apakah anda yakin akan merevisi pembobotan pelatihan ini?",
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
                        {
                            only: ["pelatihan", "peserta", "flash", "errors"],
                        }
                    );
                }
            });
        },
    },
});
</script>
