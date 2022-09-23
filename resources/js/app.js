require("./bootstrap");

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
import Swal from "sweetalert2";

window.Swal = Swal;
const appName =
    window.document.getElementsByTagName("title")[0]?.innerText || "Laravel";
const btn = {
    confirmButtonColor: "#0ea5e9",
    cancelButtonColor: "#ef4444",
};
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        el.removeAttribute("data-page");
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .mixin({ methods: { route } })
            .mount(el);
    },
});

InertiaProgress.init({ color: "#67e8f9" });
