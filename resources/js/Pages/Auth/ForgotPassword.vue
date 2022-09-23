<template>
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-b from-gray-600 to-gray-700 px-2"
    >
        <div class="w-full sm:w-96 mt-6">
            <Head :title="title" />
            <div
                v-if="$page.props.errors.email || $page.props.errors.captcha"
                class="px-6 py-4 bg-red-500 rounded-lg mb-3 text-center text-white"
            >
                <span v-if="$page.props.errors.email">
                    {{ $page.props.errors.email }}</span
                >
                <span v-else>{{ $page.props.errors.captcha }}</span>
            </div>
            <div
                v-if="status"
                class="px-6 py-4 bg-sky-500 rounded-lg mb-3 text-center text-white"
            >
                {{ status }}
            </div>
            <div class="px-6 py-4 bg-white shadow-md rounded-lg relative">
                <Link
                    :href="route('login')"
                    :class="{ 'opacity-25': form.processing }"
                    class="absolute left-6 top-4 focus:outline-none"
                    :disabled="form.processing"
                >
                    <ArrowLeftIcon
                        class="h-5 w-5 text-sky-500 hover:text-sky-600"
                    />
                </Link>
                <div class="flex flex-col items-center justify-center mt-1">
                    <img
                        :src="$page.props.base_url + '/img/icon.jpg'"
                        class="block h-12 w-auto"
                    />
                    <div
                        class="font-extrabold text-2xl text-gray-600 text-center mt-2"
                    >
                        <div>Sistem Pelatihan <br />Kemetrologian</div>
                    </div>
                </div>
                <hr class="my-3" />

                <form @submit.prevent="submit">
                    <div>
                        <label class="text-gray-600">Email</label>
                        <input
                            type="email"
                            v-model="form.email"
                            maxlength="150"
                            class="focus:ring-sky-300 focus:border-sky-400 w-full rounded sm:text-sm border-gray-300"
                            required
                        />
                    </div>

                    <div class="rounded-lg flex mt-3">
                        <img :src="captcha" alt="Captcha" class="rounded-l" />
                        <Link
                            :href="route('password.request')"
                            :only="['captcha']"
                            as="button"
                            class="btn-yellow px-3 py-2 text-white justify-center rounded-r focus:outline-none"
                            preserve-state
                            preserve-scroll
                        >
                            <ArrowPathIcon class="h-5 w-5" />
                        </Link>
                    </div>
                    <div class="mt-2">
                        <label class="text-gray-600">Captcha</label>
                        <input
                            type="number"
                            v-model="form.captcha"
                            class="focus:ring-sky-300 focus:border-sky-400 w-full rounded sm:text-sm border-gray-300"
                            required
                        />
                    </div>

                    <hr class="my-3" />
                    <div class="flex items-center justify-end mt-2">
                        <button
                            type="submit"
                            :class="{ 'opacity-25': form.processing }"
                            class="btn btn-lg btn-sky btn-block w-full"
                            :disabled="form.processing"
                        >
                            Lupa Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { ArrowPathIcon, ArrowLeftIcon } from "@heroicons/vue/24/solid";
export default defineComponent({
    components: {
        Link,
        Head,
        ArrowPathIcon,
        ArrowLeftIcon,
    },
    data() {
        return {
            form: this.$inertia.form({
                email: "",
                captcha: "",
            }),
        };
    },
    methods: {
        submit() {
            this.form.post(this.route("password.email"), {
                onFinish: () => {
                    this.form.reset("email");
                    this.form.reset("captcha");
                },
            });
        },
    },
    props: {
        rel: String,
        captcha: String,
        status: String,
        title: String,
    },
});
</script>
