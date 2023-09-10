const likeElement = document.getElementById("like");
const dislikeElement = document.getElementById("dislike");
const dis_likearr = [likeElement,dislikeElement];
const dis_likearr_str = ["Like","Dislike"];

likeElement.addEventListener('click',change_like);
dislikeElement.addEventListener('click',change_like);

function change_like(e){
	let target = e.target;
	let type = -1;
	while(type == -1 && target){
		console.log(type,target);
		switch(target.id){
			case 'like':
				type = 0;
				break;
			case 'dislike':
				type = 1;
				break;
			default:
				target = target.parentElement;
		}
	}
	
	let active = dis_likearr[type];
	let inactive = dis_likearr[type ^ 1];
	
	let xhr = new XMLHttpRequest();
	xhr.open("GET", '/api.php?c=votesong&song='+active.getAttribute('songid')+'&type='+dis_likearr_str[type],true);
	xhr.onload=function(){
		if(xhr.status==200){
			if(!active.classList.contains('active')){
				active.classList.add('active');
				set_votes(type,get_votes(type)+1);
			}
			if(inactive.classList.contains('active')){
				inactive.classList.remove('active');
				set_votes(type^1,get_votes(type^1)-1);
			}
		}
	}
	xhr.send();
}

function get_votes(type){
	return Number(
		(dis_likearr[type].childNodes[type *2].textContent).trim()
	);
}
function set_votes(type,votes){
	dis_likearr[type].childNodes[type *2].textContent = votes;
}