<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Classroom</title>
  <style>
    .full-height {
      height: 100%;
    }
    html,body {
      height: 100%;
    }
  </style>
</head>
<body>
  <div id="meet" class="full-height"></div>
  <script src='https://8x8.vc/external_api.js'></script>

  <script type="text/javascript">
    let api;

    const initIframeAPI = () => {
      const domain = '8x8.vc';
      const options = {
        roomName: '{{ env('JITSI_APP_ID') . '/' . $rn }}',
        jwt: '{{ $token }}',
        configOverwrite: {
        },
        
       
        parentNode: document.querySelector('#meet')
      };
      api = new JitsiMeetExternalAPI(domain, options);
    }

    window.onload = () => {
      initIframeAPI();
    }
  </script>
</body>
</html>