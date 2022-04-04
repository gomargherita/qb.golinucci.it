import { createApp, h } from 'vue';
import { createInertiaApp, Link, Head } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import FrontLayout from "@/Shared/Front/FrontLayout";
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
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            // make Link a global component
            .component('Head', Head)
            .component('Link', Link)
            .component('Icon', Icon)
            .mixin({ methods: { route } })
            .mixin(require('./lang'))
            .mount(el);
    },
});

InertiaProgress.init({
    color: '#4B5563',
    showSpinner: true,
});
