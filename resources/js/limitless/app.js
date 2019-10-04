//Core JS files
window.$ = window.jQuery = require('jquery')

require('./main/bootstrap.bundle.min.js')
require('./plugins/loaders/blockui.min.js')
require('./plugins/ui/ripple.min.js')
require('./assets/app.js')

// Vue JS
window.Vue = require('vue');
window.axios = require('axios');
window._ = require('lodash');

window.echarts = require('./plugins/charts/echarts/echarts.min.js');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.baseURL = 'http://localhost/sales/';
