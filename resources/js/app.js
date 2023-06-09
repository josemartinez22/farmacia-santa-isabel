import './bootstrap';
import "vue-select/dist/vue-select.css";
import 'vue-multiselect/dist/vue-multiselect.css';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { modal } from "momentum-modal"
import LaravelPermissionToVueJS from 'laravel-permission-to-vuejs'


const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Farmacia Santa Isabel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(LaravelPermissionToVueJS)
            .use(modal, {
                resolve: (name) => import(`./Pages/${name}.vue`),
            })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
    progress: {
        delay: 250,
        color: '#1A73E8',
    },
});
