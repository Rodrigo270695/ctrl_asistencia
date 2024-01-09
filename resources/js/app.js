import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

// Importa oh-vue-icons y los iconos que deseas usar
import { OhVueIcon, addIcons } from "oh-vue-icons";
import
{
    FaFlag, RiZhihuFill, IoAddCircleSharp, MdModeeditRound, GiCancel, FaCheck,
    PrImages, BiTrash, FaEye, BiCartFill, BiClockFill, IoReloadCircleSharp,
    ViFileTypePdf, ViFileTypeWord, ViFileTypeImage, ViDefaultFile, ViFileTypeExcel, BiFileEarmarkExcelFill,
    RiUserFollowFill, LaCitySolid, BiCheck2Circle, BiCheck2Square, BiCalendar2X, MdVisibilityRound, FcServices,
    ViFileTypeRed, FcMindMap, SiTarget, BiFileEarmarkArrowUpFill, IoCloseCircleSharp, BiCheckCircleFill, BiDashCircleFill,
    FaSearch
} from "oh-vue-icons/icons";

// Añade los iconos a oh-vue-icons
addIcons(
FaFlag, RiZhihuFill, IoAddCircleSharp, MdModeeditRound, GiCancel, FaCheck,
PrImages, BiTrash, FaEye, BiCartFill, BiClockFill, IoReloadCircleSharp,
ViFileTypePdf, ViFileTypeWord, ViFileTypeImage, ViDefaultFile, ViFileTypeExcel, BiFileEarmarkExcelFill,
RiUserFollowFill, LaCitySolid, BiCheck2Circle, BiCheck2Square, BiCalendar2X, MdVisibilityRound, FcServices,
ViFileTypeRed, FcMindMap, SiTarget, BiFileEarmarkArrowUpFill, IoCloseCircleSharp, BiCheckCircleFill, BiDashCircleFill,
FaSearch
);

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue);

        // Registra el componente de icono en tu aplicación
        app.component("v-icon", OhVueIcon);

        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
