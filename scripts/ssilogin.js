
const SIDEOS_SOCKET_URL = SIDEOS.sideosurl.replace('https:', 'wss:').replace('http:', 'ws:') + '/api/login/v2'

/* Show a QRCode as a canvas to scan a verifiable credential */
const setQr = (value) => {
  var options = {
  text: value,
      width: 210,
      height: 210,
      correctLevel : QRCode.CorrectLevel.L
  };
  new QRCode(document.getElementById('ssiqrcode'), options)
} 

/* Ask the backend to load the info submitted via the verifiable credential */
const ssi_login = (packet) => {
  jQuery.ajax({
    url:"/wp-admin/admin-ajax.php",
    type: 'POST',
    dataType: 'json',
    data: {
      action: 'username_login',
      challenge: packet.challenge,
      credential: packet.data
    },
    success:function(response){
      if(response.error_code == 1) {
          alert('Cannot log in...')
      } else {
        window.location.href = response.url
      }
    }
  });
}

const setBlurried = () => {
  setQr('ERROR')
  jQuery('#ssiqrcode').addClass('blurred')
}

/* ********************** OPEN A SOCKET TO THE PROXY SERVICE *************** */
jQuery( document ).ready(function() {
  
  if (SIDEOS_SOCKET_URL.startsWith('ws')) {
    const socket = new WebSocket(SIDEOS_SOCKET_URL);

    socket.addEventListener("open", () => {
      socket.send(JSON.stringify({message: 'ok'}))
    });

    socket.addEventListener("message", ({ data }) => {
      const packet = JSON.parse(data)
      if (packet.session) {
        setQr(packet.data.jwt)
        jQuery('#ssiqrcode').removeClass('blurred')
      }
      if (packet.challenge) {
        ssi_login(packet)
      }
    });

    socket.addEventListener('error', (event) => {
      setBlurried()
      console.log('WebSocket error: ', event);
    });
  } else {
    setBlurried()
  }
})
