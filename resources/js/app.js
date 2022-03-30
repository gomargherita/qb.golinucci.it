import { createApp, h } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import FrontLayout from "@/Shared/Front/FrontLayout";

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        let page = require(`./Pages/${name}`).default;

        page.layout ??= FrontLayout;

        return page;
    },
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            // make Link a global component
            .component('Link', Link)
            .mixin({ methods: { route } })
            .mixin(require('./lang'))
            .mount(el);
    },
});

InertiaProgress.init({
    color: '#4B5563',
    showSpinner: true,
});
