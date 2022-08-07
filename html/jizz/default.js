document.addEventListener("click",function(event){
	event.preventDefault();
	if(event.target.tagName=='A' && event.target.href){
		//preload next website while animation plays out
		//(not working, but what the hell. I did it like in the MDN example. Not my fault.)
		let preload=document.createElement("link");
		preload.href=event.target.href;
		preload.rel="preload";
		preload.as="document";
		document.head.appendChild(preload);
		//reversing load-in animation
		let overlay = document.getElementById("overlay");
		overlay.getAnimations()[0].reverse();
		//wait 500ms for animation to finish and then redirect
		setTimeout(()=>{window.location.href=event.target.href},500);
	}
},false);