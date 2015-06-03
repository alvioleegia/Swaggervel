<?php
header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers: X-Requested-With");
?>
<html>
<head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Droid+Sans:400,700">
    <link rel="stylesheet" href="vendor/swaggervel/css/reset.css">
    <link rel="stylesheet" href="vendor/swaggervel/css/reset.css">
    <link rel="stylesheet" href="vendor/swaggervel/css/screen.css">
    <link rel="stylesheet" href="vendor/swaggervel/css/screen.css">

    <script src='vendor/swaggervel/lib/jquery-1.8.0.min.js' type='text/javascript'></script>
    <script src='vendor/swaggervel/lib/jquery.slideto.min.js' type='text/javascript'></script>
    <script src='vendor/swaggervel/lib/jquery.wiggle.min.js' type='text/javascript'></script>
    <script src='vendor/swaggervel/lib/jquery.ba-bbq.min.js' type='text/javascript'></script>
    <script src='vendor/swaggervel/lib/handlebars-2.0.0.js' type='text/javascript'></script>
    <script src='vendor/swaggervel/lib/underscore-min.js' type='text/javascript'></script>
    <script src='vendor/swaggervel/lib/backbone-min.js' type='text/javascript'></script>
    <script src='vendor/swaggervel/swagger-ui.js' type='text/javascript'></script>
    <script src='vendor/swaggervel/lib/highlight.7.3.pack.js' type='text/javascript'></script>
    <script src='vendor/swaggervel/lib/marked.js' type='text/javascript'></script>
    <script src='vendor/swaggervel/lib/swagger-oauth.js' type='text/javascript'></script>

    <!-- enabling this will enable oauth2 implicit scope support -->
    {{--    {{ HTML::script('packages/jlapp/swaggervel/lib/swagger-oauth.js' , array(), $secure); !!}--}}

    <script type="text/javascript">
        $(function () {
          var url = "{!! $urlToDocs !!}";
          if (url && url.length > 1) {
            url = url;
          } else {
            url = "http://petstore.swagger.io/v2/swagger.json";
          }
          window.swaggerUi = new SwaggerUi({
            url: url,
            dom_id: "swagger-ui-container",
            supportedSubmitMethods: ['get', 'post', 'put', 'delete', 'patch'],
            onComplete: function(swaggerApi, swaggerUi){
              log("Loaded SwaggerUI");
              @if(isset($requestHeaders))
                @foreach($requestHeaders as $requestKey => $requestValue)
                window.authorizations.add("{!!$requestKey!!}", new ApiKeyAuthorization("{!!$requestKey!!}", "{!!$requestValue!!}", "header"));
                @endforeach
              @endif
              
              // if(typeof initOAuth == "function") {
              //   initOAuth({
              //     clientId: "your-client-id",
              //     realm: "your-realms",
              //     appName: "your-app-name"
              //   });
              // }

              $('pre code').each(function(i, e) {
                hljs.highlightBlock(e)
              });

              addApiKeyAuthorization();
            },
            onFailure: function(data) {
              log("Unable to Load SwaggerUI");
            },
            docExpansion: "none",
            apisSorter: "alpha"
          });

          function addApiKeyAuthorization(){
            var key = encodeURIComponent($('#input_apiKey')[0].value);
            if(key && key.trim() != "") {
                log("key: " + key);
                if (key && key.trim() != "") {
                    log("added key " + key);
                    window.authorizations.add("key", new ApiKeyAuthorization("{!! Config::get('swaggervel.api-key') !!}", key, "query"));
                } else {
                    window.authorizations.remove("key");
                }
            }
          }

          $('#input_apiKey').change(addApiKeyAuthorization);

          // if you have an apiKey you would like to pre-populate on the page for demonstration purposes...
          /*
            var apiKey = "myApiKeyXXXX123456789";
            $('#input_apiKey').val(apiKey);
          */

          window.swaggerUi.load();

          function log() {
            if ('console' in window) {
              console.log.apply(console, arguments);
            }
          }
      });
      </script>
</head>
<body class="swagger-section">
<div id='header'>
  <div class="swagger-ui-wrap">
    <a id="logo" href="http://swagger.io">swagger</a>
    <form id='api_selector'>
      <div class='input'><input placeholder="http://example.com/api" id="input_baseUrl" name="baseUrl" type="text"/></div>
      <div class='input'><input placeholder="api_key" id="input_apiKey" name="apiKey" type="text"/></div>
      <div class='input'><a id="explore" href="#">Explore</a></div>
    </form>
  </div>
</div>

<div id="message-bar" class="swagger-ui-wrap">&nbsp;</div>
<div id="swagger-ui-container" class="swagger-ui-wrap"></div>
</body>
</html>
