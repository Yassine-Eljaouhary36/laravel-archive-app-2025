import './bootstrap';
import Swal from 'sweetalert2';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';


window.Chart = Chart; // Make it globally available

window.Alpine = Alpine;
window.Swal = Swal;

Alpine.start();

