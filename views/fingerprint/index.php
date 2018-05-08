<script>
    function loadJs(url) {
        var script = document.createElement('script');
        script.onload = showRes;
        script.src = url;
        script.setAttribute('async', 'true');
        document.documentElement.firstChild.appendChild(script);
    }

    function showRes() {
        new Fingerprint2({excludeDeviceMemory: true }).get(function(result, components) {
            var span = document.createElement('span');
            span.innerHTML = result;
            document.getElementById('hash').appendChild(span);
            $.post("/fingerprint/", { Lead: {hash: result, config: components[0].value }});
        });
    }

    setTimeout(function(){
        loadJs("https://cdnjs.cloudflare.com/ajax/libs/fingerprintjs2/1.8.0/fingerprint2.min.js");
    },50);
</script>
<div class="container">
    <div class="block">
        <div class="block__body bg-white body_main" id="hash">

        </div>
    </div>
</div> <!-- /.container -->