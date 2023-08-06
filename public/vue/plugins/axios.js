import { URL  } from '../../config.json'
export default function ({app, $axios}) {
  if (localStorage.getItem('url')) {
    process.env.baseUrl = localStorage.getItem('url');
    $axios.defaults.baseURL = localStorage.getItem('url') + '/api/';
  } else
    $axios.defaults.baseURL = URL;
/*
    $axios.get(window.location.origin + "/config.json").then(response => {
      localStorage.setItem('url', response.data.URL);
      process.env.baseUrl = response.data.URL;
      $axios.defaults.baseURL = response.data.URL + '/api/';
    });
*/


  // $axios.defaults.headers.common['Access-Control-Allow-Origin'] = process.env.baseUrl;

  $axios.onError((error) => {
    if (error.response && error.response.status === 401) {
      app.$auth.reset();
    }

    return Promise.reject(error);
  });
}
