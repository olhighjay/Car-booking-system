// /**
//  * First we will load all of this project's JavaScript dependencies which
//  * includes Vue and other libraries. It is a great starting point when
//  * building robust, powerful web applications using Vue and Laravel.
//  */

// require('../../resources/js/bootstrap');

// window.Vue = require('vue');

// /**
//  * The following block of code may be used to automatically register your
//  * Vue components. It will recursively scan this directory for the Vue
//  * components and automatically register them with their "basename".
//  *
//  * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
//  */

// // const files = require.context('./', true, /\.vue$/i)
// // files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

// /**
//  * Next, we will create a fresh Vue application instance and attach it to
//  * the page. Then, you may begin adding components to this application
//  * or customize the JavaScript scaffolding to fit your unique needs.
//  */

// const app = new Vue({
//     el: '#app',
// });

// // const DEPTS = [{name: 'Software', carId: '1'}, 
// // {name: 'Accounting', carId: '2'}, 
// // {name: 'Engineering', carId: '3'}]


// // const CARS = [
// //     {name: 'Honda', id: '1'},
// //     {name: 'Toyota', id: '2'},
// //     {name: 'Volvo', id: '3'}
// // ]

// // document.querySelector('.select').addEventListener('change', ()=>{
// //     const newArray = CARS.filter(el=> el.id === el.id) //[i,2]
    
// // })
// // <Select>

// // </Select>
// // function populateOptions(){
// //     for(let i = 0; i< newArray.length; i++){
// //         const option = `<option>${newArray[i].name}</option>`
// //         document.querySelector('.secondselect').appendChild()
// //     }
// // }