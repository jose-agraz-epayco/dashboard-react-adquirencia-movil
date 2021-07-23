export const config = {
  headerIn: {
    dispositivo: null,
    canal: null,
    medio: null,
    aplicacion: null,
    tipoTransaccion: null,
    usuario: null,
    uuid: null,
    fechaHora: null,
    idioma: null,
    empresa: null,
    geolocalizacion: null,
    token: '',
  },
  landingIdentifier: 'dashboardAdquirencia',
  api: process.env.URL_APIFY,
  amazonUrl: process.env.REACT_APP_BASE_PATH,
  cobraUrl: process.env.REACT_APP_URL_COBRA,
  captchaKey: process.env.REACT_APP_CAPTCHA_KEY || '',
  modeCheckout: process.env.REACT_APP_CHECKOUT_MODE !== 'prod',
  urlResultCheckout: process.env.REACT_APP_URL_RESULT_CHECKOUT,
  apiBack: process.env.API,
  rackspaceImages: process.env.RACKSPACE_CONTAINER_BASE_PUBLIC_URL,
}
