//rather than circumventing this check, consider getting a better browser :)
let fuckchrome = false;
nochrome: {
	let params = new URLSearchParams(window.location.search);
	if (params.has('i_will_choose_a_better_browser_next_time')) {
		fuckchrome = true;
		break nochrome;
	}
	
	let isChrome = !!window.chrome;
	let isIOSChrome = window.navigator.userAgent.match("CriOS");

	if (isChrome || isIOSChrome) {
		window.location = "/unsupported.html";
	}
}

document.addEventListener("click",function(event){
	let anchor = event.target
	while(anchor){
		if(anchor.tagName=='A' && anchor.href){
			event.preventDefault();
			
			if (fuckchrome) {
				let decodedurl = new URL(anchor.href);
				decodedurl.searchParams.set('i_will_choose_a_better_browser_next_time','');
				anchor.href = decodedurl.toString();
			}
			
			//preload next website while animation plays out
			//(not working, but what the hell. I did it like in the MDN example. Not my fault.)
			let preload=document.createElement("link");
			preload.href=anchor.href;
			preload.rel="preload";
			preload.as="document";
			document.head.appendChild(preload);
			//reversing load-in animation
			let overlay = document.getElementById("overlay");
			overlay.getAnimations()[0].reverse();
			//wait 500ms for animation to finish and then redirect
			setTimeout(()=>{window.location.href=anchor.href},500);
			return;
		}
		anchor=anchor.parentElement;//checking if parent contains a link
	}
},false);

window.addEventListener("focus",function(event){
	let overlay = document.getElementById("overlay");
	let anim=overlay.getAnimations()[0];
	if(anim.currentTime==0 && anim.playState=="finished"){
		anim.reverse();
	}
});