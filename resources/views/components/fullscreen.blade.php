<li class="nav-item">
    <a href="javascript:void(0);" class="nav-link nav-fullscreen">
        <i class="icon-expand-arrows-alt"></i><i class="icon-compress-arrows-alt d-none"></i>
    </a>
</li>

<script>
    function launchFullscreen(element) {
        document.querySelector(".nav-fullscreen .icon-compress-arrows-alt").classList.remove("d-none");
        document.querySelector(".nav-fullscreen .icon-expand-arrows-alt").classList.add("d-none");

        if(element.requestFullscreen) {
            element.requestFullscreen();
        } else if(element.mozRequestFullScreen) {
            element.mozRequestFullScreen();
        } else if(element.msRequestFullscreen){
            element.msRequestFullscreen();
        } else if(element.webkitRequestFullscreen) {
            element.webkitRequestFullScreen();
        }
    }

    function exitFullscreen() {
        document.querySelector(".nav-fullscreen .icon-compress-arrows-alt").classList.add("d-none");
        document.querySelector(".nav-fullscreen .icon-expand-arrows-alt").classList.remove("d-none");

        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        }
    }

    document.querySelector('.nav-fullscreen').addEventListener("click",function () {
        if (document.fullscreenElement) {
            exitFullscreen();
        } else {
            launchFullscreen(document.body)
        }
    });
</script>
