document.getElementById("loadmore").firstElementChild.onclick=function(){
	let amount = document.getElementById("filter_amount").valueAsNumber;
	let typebl = "";//blacklist
	if(!document.getElementById("filter_originals").checked)
		typebl+="&nooriginals";
	if(!document.getElementById("filter_rremixes").checked)
		typebl+="&norremixes";
	if(!document.getElementById("filter_commissions").checked)
		typebl+="&nocommissions";
	if(!document.getElementById("filter_rrequests").checked)
		typebl+="&norrequests";
	//"yes, let's make doing GET requests as weird and convoluted as possible"
	let loadmore=document.getElementById("loadmore");
	let xhr = new XMLHttpRequest();
	let lastdate = loadmore.dataset.lastdate.split('.').reverse().join("-");
	xhr.open("GET", '/music/api?c=moresongs&startdate='+lastdate+'&max_amount='+amount+'&ashtml=1'+typebl,true);
	xhr.onload=function(){
		if(xhr.status == 200){
			let grid=document.getElementById("tracks");
			let allanchors=this.responseXML.getElementsByTagName("a");
			if(allanchors.length<amount){
				loadmore.style.display='none';
			}
			loadmore.dataset.lastdate=allanchors[allanchors.length-1].children[3].textContent;
			for(let child of [...this.responseXML.body.children]){//WHY THE FUCK
				grid.appendChild(child);
				if(child.tagName=="BUTTON"){
					prepare_player(child.firstElementChild);
				}
			}
		}
	};
	xhr.responseType = "document";
	xhr.send();
};
document.getElementById("filter_form").onsubmit=function(event){
	event.preventDefault();
	let trackHeader = document.getElementById("tracks_header");
	while(trackHeader.nextElementSibling){
		trackHeader.nextElementSibling.remove();
	}
	let loadmore = document.getElementById("loadmore");
	loadmore.dataset.lastdate='2100-01-01';
	loadmore.firstElementChild.click();
}