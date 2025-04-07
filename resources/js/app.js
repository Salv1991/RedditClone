import { createApp, h } from 'vue'
import { createInertiaApp, Head, Link, router } from '@inertiajs/vue3';
import NProgress from 'nprogress'
import Layout from './Shared/Layout.vue';

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });

    // Resolve the page and assign the layout
    const page = pages[`./Pages/${name}.vue`];

    // if(!page.default.layout){
    //   page.default.layout = Layout;
    // }


    // Set the layout for the page (if necessary)
    if(page.default.layout == undefined){
      page.default.layout = Layout;
    }

    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .component('Link', Link)
      .component('Head', Head)
      .mount(el)
  },
  progress: {
    delay: 250,
    color: 'darkgreen',
    includeCSS: true,
  },

  title: title => `${title} - Reddit Clone `,
})

NProgress.configure({ showSpinner: false });
router.on('start', () => NProgress.start())
router.on('finish', () => NProgress.done())
