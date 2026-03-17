import './bootstrap';
import 'laravel-datatables-vite';
import moment from "moment";


import Alpine from 'alpinejs';


window.moment = moment();
window.Alpine = Alpine;

Alpine.start();
