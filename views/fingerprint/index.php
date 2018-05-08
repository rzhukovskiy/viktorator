<script>
    function loadJs(url) {
        var script = document.createElement('script');
        script.onload = showRes;
        script.src = url;
        script.setAttribute('async', 'true');
        document.documentElement.firstChild.appendChild(script);
    }

    function showRes() {
        new Fingerprint2().get(function(result, components) {
            alert(result);
        });
    }

    setTimeout(function(){
        loadJs("https://cdnjs.cloudflare.com/ajax/libs/fingerprintjs2/1.8.0/fingerprint2.min.js");
    },50);
</script>
<div class="container">
    <div class="block">
    </div>
</div> <!-- /.container -->