//rather than circumventing this check, consider getting a better browser :)
{
	let isChrome = !!window.chrome;
	let isIOSChrome = window.navigator.userAgent.match("CriOS");

	if (isChrome || isIOSChrome) {
		window.location = "/unsupported.html";
	}
}

const preload=document.createElement("link");
preload.rel="preload";
//not supported by some Chromium based browsers https://developer.mozilla.org/en-US/docs/Web/HTML/Attributes/rel/preload#browser_compatibility
//Firefox also warns "Preload of ... was ignored due to unknown “as” or “type” values, or non-matching “media” attribute."
preload.as="document";

document.addEventListener("click",function(event){
    let anchor = event.target
    while(anchor) {
        if (anchor.tagName == 'A' && anchor.href) {
            const href=anchor.href;
            event.preventDefault();
            //preload next website while animation plays out
            preload.href=href;
            document.head.appendChild(preload);
            //reversing load-in animation
            const animation = document.getElementById("overlay").getAnimations()[0];
            // wait for the animation to finish and then redirect
            animation.addEventListener('finish', () => window.location.href = href);
            animation.reverse();
        }
        anchor = anchor.parentElement;
    }
});

window.addEventListener("focus",function(event){
	let overlay = document.getElementById("overlay");
	let anim=overlay.getAnimations()[0];
	if(anim.currentTime==0 && anim.playState=="finished"){
		anim.reverse();
	}
});