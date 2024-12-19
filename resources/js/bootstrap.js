import axios from 'axios';
import 'core-js/stable'; // Polyfills for modern JavaScript features
import 'regenerator-runtime/runtime';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
