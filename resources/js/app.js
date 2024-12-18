import { createApp, h } from 'vue';
import { createInertiaApp, Link, Head } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import FrontLayout from "@/Shared/Layouts/FrontLayout";
import Icon from "@/Shared/Icon";

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Q&B Golinucci';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: async (name) => {
        let page = (await import(`./Pages/${name}`)).default;

        page.layout ??= FrontLayout;

        return page;
    },
    setup({ el, app, props, plugin }) {
        const appp = createApp({ render: () => h(app, props) })
            .use(plugin)
            // make Link a global component
            .component('Head', Head)
            .component('Link', Link)
            .component('Icon', Icon)
            .mixin({ methods: { route } })
            .mixin(require('./lang'));
        appp.config.globalProperties.$filters = {
            formatPrice(value) {
                if (typeof value !== "number") {
                    return value;
                }
                const formatter = new Intl.NumberFormat("it-IT", {
                    style: "currency",
                    currency: "EUR",
                    minimumFractionDigits: 2,
                });
                return formatter.format(value);
            },
        }

        return appp.mount(el);
    },
});

InertiaProgress.init({
    color: '#4B5563',
    showSpinner: true,
});
