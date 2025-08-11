import './bootstrap';

// Importa o flatpickr
import flatpickr from "flatpickr";
import 'trix'; //
import './bootstrap';

// Adiciona o flatpickr ao objeto window para que o Alpine.js possa usá-lo
window.flatpickr = flatpickr;

import IMask from 'imask'; // 1. Importa a biblioteca
window.IMask = IMask; // 2. Deixa ela acessível globalmente

import TomSelect from "tom-select"; // <-- ADICIONE ESTA LINHA
window.TomSelect = TomSelect; // <-- E ESTA LINHA

