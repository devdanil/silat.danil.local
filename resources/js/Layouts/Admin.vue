<template>
    <Head :title="$page.props.title" />
    <nav
        class="fixed top-0 left-0 right-0 h-16 bg-gray-800 shadow flex justify-between sm:justify-between items-center text-gray-200 z-20"
    >
        <button
            @click="showSideBar = !showSideBar"
            type="button"
            class="inline-flex items-center justify-center p-2 rounded-full hover:bg-gray-700 mx-3 hover:text-gray-100 focus:text-gray-100 focus:outline-none focus:bg-gray-700 transition sm:hidden"
        >
            <Bars3Icon
                class="h-6 w-6 transition"
                :class="{ 'rotate-90': !showSideBar }"
            />
        </button>
        <div class="sm:w-64 sm:px-3">
            <Link
                :href="route('dashboard')"
                class="text-xl xl:text-2xl font-bold flex items-center"
            >
                <img
                    :src="$page.props.base_url + '/img/icon.jpg'"
                    class="hidden sm:block h-9 w-auto rounded"
                />
                <header class="px-3">Si Pelatihan</header>
            </Link>
        </div>
        <button
            @click="showSideBar = !showSideBar"
            type="button"
            class="items-center justify-center p-2 rounded-full hover:bg-gray-700 hidden sm:inline-flex hover:text-gray-100 focus:text-gray-100 focus:outline-none focus:bg-gray-700 transition"
        >
            <Bars3Icon
                class="h-6 w-6 transition"
                :class="{ 'rotate-90': !showSideBar }"
            />
        </button>
        <!-- <div class="text-lg mx-5 hidden sm:inline-flex">
            Sistem Pengelolaan Data IDN
        </div> -->
        <div class="sm:ml-auto px-3 relative">
            <Dropdown align="right" width="48">
                <template #trigger>
                    <button
                        v-if="$page.props.auth.user.foto"
                        class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition"
                        :href="$page.props.auth.user.foto"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <img
                            class="h-8 w-8 rounded-full object-cover"
                            :src="$page.props.auth.user.foto"
                            :alt="$page.props.auth.user.nama"
                        />
                    </button>

                    <span v-else class="inline-flex rounded-md">
                        <button
                            type="button"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-semibold rounded-md hover:text-gray-100 focus:text-gray-1000 focus:outline-none transition"
                        >
                            {{ $page.props.auth.user.nama }}

                            <svg
                                class="ml-2 -mr-0.5 h-4 w-4"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </button>
                    </span>
                </template>

                <template #content>
                    <!-- Account Management -->
                    <!-- <div class="block px-4 py-2 text-xs text-gray-400">
                        Manage Account
                    </div>

                    <DropdownLink :href="route('dashboard')">
                        Profile
                    </DropdownLink> -->

                    <div class="border-t border-gray-100"></div>
                    <DropdownLink
                        as="button"
                        :href="route('logout')"
                        method="post"
                    >
                        Log Out
                    </DropdownLink>
                </template>
            </Dropdown>
        </div>
    </nav>
    <aside
        :class="{
            'w-0 sm:w-16': !showSideBar,
            'w-full sm:w-64': showSideBar,
        }"
        class="fixed left-0 bottom-0 top-0 bg-gray-800 transition-all overflow-auto text-gray-300 p-1 z-10"
    >
        <div class="flex flex-col min-h-full">
            <div
                :class="{
                    'px-3': showSideBar,
                }"
                class="mt-16 sm:px-3 py-5 bg-gray-700 flex items-center mb-3 rounded-lg shadow"
            >
                <div v-if="$page.props.auth.user.foto">
                    <img
                        class="h-8 w-8 rounded-full object-cover"
                        :src="$page.props.auth.user.foto"
                        :alt="$page.props.auth.user.nama"
                    />
                </div>
                <div v-else>
                    <UserIcon class="h-8 w-8" />
                </div>
                <div v-show="showSideBar" class="font-semibold pl-4">
                    <div
                        :title="$page.props.auth.user.nama"
                        class="w-44 whitespace-nowrap text-ellipsis overflow-hidden"
                    >
                        <div>{{ $page.props.auth.user.nama }}</div>
                        <div class="text-sm">
                            {{ $page.props.auth.user.nip }}
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="mb-1 flex flex-col"
                v-for="menu in $page.props.auth.menus"
                :key="menu.id"
            >
                <SideLink
                    v-if="menu.childs.length == 0"
                    :sidebar="showSideBar"
                    :href="route(menu.route)"
                    :active="isNavActive(menu.route)"
                >
                    <span v-html="menu.icon"></span>
                    <span
                        v-show="showSideBar"
                        :class="{
                            'ml-2': showSideBar,
                        }"
                        >{{ menu.name }}</span
                    >
                    <div class="ml-auto" v-if="$page.props.all_notif > 0">
                        <span
                            v-if="menu.route == 'notifications.index'"
                            class="px-1 bg-red-500 rounded-full text-sm font-semibold animate-ping"
                            >{{ $page.props.all_notif }}</span
                        >
                    </div>
                </SideLink>
                <a
                    v-if="menu.childs.length > 0"
                    @click="toggleDrop(menu.id)"
                    href="#"
                    class="px-3 py-2 inline-flex rounded-md"
                    :class="{
                        'justify-center ': !showSideBar,
                        'bg-gray-700 text-white': this.drop[menu.id],
                        'hover:text-sky-400': !this.drop[menu.id],
                    }"
                >
                    <span v-html="menu.icon"></span>
                    <span
                        v-show="showSideBar"
                        :class="{
                            'ml-2': showSideBar,
                        }"
                        >{{ menu.name }}</span
                    >
                    <ChevronRightIcon
                        class="ml-auto h-5 w-5 transition"
                        :class="{ 'rotate-90': this.drop[menu.id] }"
                    />
                </a>
                <div
                    class="flex flex-col"
                    v-show="this.drop[menu.id]"
                    v-for="menu2 in menu.childs"
                    :key="menu2.id"
                >
                    <Link
                        :href="route(menu2.route)"
                        class="px-3 py-2 hover:text-sky-400 inline-flex rounded-md"
                        :class="{
                            'justify-center ': !showSideBar,
                            'text-sky-400':
                                resource == menu2.route.split('.')[0],
                        }"
                        ><span v-html="menu2.icon"></span>
                        <span
                            v-show="showSideBar"
                            :class="{
                                'ml-2': showSideBar,
                            }"
                            >{{ menu2.name }}</span
                        >
                    </Link>
                </div>
            </div>
        </div>
    </aside>
    <main
        :class="{
            'sm:ml-64 ': showSideBar,
            'sm:ml-16 ': !showSideBar,
        }"
        class="pt-20 px-4 pb-4 text-gray-600 transition-all mx-auto bg-gray-100 min-h-screen flex flex-col"
    >
        <div
            class="h-14 mb-3 rounded-lg bg-white shadow flex items-center px-4 border-l-2 border-sky-500 font-semibold text-xl leading-tight justify-center sm:justify-between flex-col sm:flex-row"
        >
            <h1>{{ $page.props.title }}</h1>

            <nav class="flex overflow-auto w-100" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1">
                    <li class="inline-flex items-center">
                        <Link
                            :href="route('dashboard')"
                            class="inline-flex items-center text-sm font-medium text-sky-500 hover:text-sky-600"
                        >
                            <HomeIcon class="h-4 w-4" />
                        </Link>
                    </li>
                    <slot name="breadcrumb"></slot>
                </ol>
            </nav>
        </div>
        <div
            class="p-3 mb-3 shadow rounded-lg text-white flex items-center"
            :class="{
                'bg-teal-500': !$page.props.flash.error,
                'bg-red-500': $page.props.flash.error,
            }"
            v-if="$page.props.flash.msg"
        >
            <InformationCircleIcon
                v-if="$page.props.flash.error"
                class="h-5 w-5 mr-2"
            />
            <CheckCircleIcon v-else class="h-5 w-5 mr-2" />
            {{ $page.props.flash.msg }}
        </div>
        <div
            class="p-3 mb-3 rounded-lg shadow text-white bg-red-500"
            v-if="Object.keys($page.props.errors).length > 0"
        >
            <div
                class="flex items-center"
                v-for="item in $page.props.errors"
                :key="item"
            >
                <InformationCircleIcon class="h-5 w-5 mr-2" />
                {{ item }}
            </div>
        </div>
        <slot></slot>
        <div class="mt-auto text-center text-gray-500">
            © {{ new Date().getFullYear() + " " + $page.props.app.name }}. All
            rights reserved.
        </div>
    </main>
</template>
<script>
import { defineComponent } from "vue";
import { Link, Head } from "@inertiajs/inertia-vue3";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import SideLink from "@/Components/SideLink.vue";
import Banner from "@/Components/Banner.vue";
import {
    Bars3Icon,
    HomeIcon,
    ShoppingBagIcon,
    CogIcon,
    ChevronRightIcon,
    InformationCircleIcon,
    UserIcon,
    CheckCircleIcon,
} from "@heroicons/vue/24/solid";
export default defineComponent({
    components: {
        Bars3Icon,
        Link,
        HomeIcon,
        ShoppingBagIcon,
        CogIcon,
        ChevronRightIcon,
        InformationCircleIcon,
        Dropdown,
        DropdownLink,
        Head,
        SideLink,
        Banner,
        UserIcon,
        CheckCircleIcon,
    },
    props: {
        title: String,
    },
    data() {
        let resource = this.route().current().split(".");
        return {
            resource: resource[0],
            drop: {
                2: resource[0] == "katalog" || resource[0] == "pendaftaran",
                5:
                    resource[0] == "menus" ||
                    resource[0] == "roles" ||
                    resource[0] == "users",
                9:
                    resource[0] == "aplikasi" ||
                    resource[0] == "roles-menus" ||
                    resource[0] == "users-roles",
            },
            keyDrop: [2, 5, 9],
            showSideBar:
                /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
                    navigator.userAgent
                )
                    ? false
                    : true,
        };
    },
    methods: {
        toggleDrop(id) {
            this.keyDrop.forEach((element) => {
                if (element == id) {
                    this.drop[id] = !this.drop[id];
                } else {
                    this.drop[element] = false;
                }
            });
        },
        isNavActive(route) {
            let curr_route = route.split(".");
            return curr_route[0] == this.resource;
        },

        logout() {
            this.$inertia.post(route("logout"));
        },
    },
});
</script>
