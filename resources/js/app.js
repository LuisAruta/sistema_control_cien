import Vue from 'vue'
import store from '~/store'
import router from '~/router'
import i18n from '~/plugins/i18n'
import App from '~/components/App'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import PortalVue from 'portal-vue'
import Vuelidate from 'vuelidate'
import vSelect from 'vue-select'
import VueGraph from 'vue-graph'

import '~/plugins'
import '~/components'

// Import Bootstrap an BootstrapVue CSS files (order is important)
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

import '../js/assets/scss/argon.scss'

Vue.use(PortalVue)
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(Vuelidate)
Vue.component('VSelect', vSelect)
Vue.use(VueGraph)

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  i18n,
  store,
  router,
  ...App
})
