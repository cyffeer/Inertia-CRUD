import '../css/app.css';
import './bootstrap';
import 'core-js/stable'; 
import 'regenerator-runtime/runtime';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Inertia-CRUD';

createInertiaApp({
    title: (title) => `${title}`,
    resolve: (name) => {
        console.log('Resolving page component for:', name);
        const componentPath = `./Pages/${name}.vue`;
        console.log('Component path:', componentPath);  // Log the full path
        return resolvePageComponent(
            componentPath,
            import.meta.glob('./Pages/**/*.vue'),
        );
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});