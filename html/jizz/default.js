//rather than circumventing this check, consider getting a better browser :)
nochrome: {
	let params = new URLSearchParams(window.location.search);
	if (params.has('i_will_choose_a_better_browser_next_time')) {
		document.addEventListener("click",function(event){
			let anchor = event.target
			while(anchor){
				if(anchor.tagName=='A' && anchor.href){
					event.preventDefault();
					
					let decodedurl = new URL(anchor.href);
					decodedurl.searchParams.set('i_will_choose_a_better_browser_next_time','');
					anchor.href = decodedurl.toString();
					window.location=anchor.href
					
					return;
				}
				anchor=anchor.parentElement;//checking if parent contains a link
			}
		},false);
		break nochrome;
	}
	
	let isChrome = !!window.chrome;
	let isIOSChrome = window.navigator.userAgent.match("CriOS");

	if (isChrome || isIOSChrome) {
		window.location = "/unsupported.html";
	}
}