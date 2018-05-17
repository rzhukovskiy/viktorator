<script>
    function loadJs(url) {
        var script = document.createElement('script');
        script.onload = showRes;
        script.src = url;
        script.setAttribute('async', 'true');
        document.documentElement.firstChild.appendChild(script);
    }

    function showRes() {
        new Fingerprint2({ excludeUserAgent: true }).get(function(result, components) {
            var md = new MobileDetect();
            var hash = murmurHash3.x64.hash128(
                result +
                md.mobile() +
                md.phone() +
                md.mobileGrade() +
                md.os() +
                md.version('Mobile') +
                md.version('iPhone') +
                md.version('iOs') +
                md.version('Safari') +
                md.version('Webkit') +
                md.versionStr('Build')
            );
            var span = document.createElement('span');
            span.innerHTML = hash;
            document.getElementById('hash').appendChild(span);
            $.post("/fingerprint/", { Lead: {hash: hash, config: JSON.stringify(components) }});
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