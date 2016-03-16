var bobroidsTabs = {

	init: function(tabs){
		tabs = tabs + " .tabsLinks > *";
		tabs = document.querySelectorAll(tabs);

		for(var i = 0; i < tabs.length; i++){
			tabs[i].addEventListener('click', function(e){
				bobroidsTabs.changeTab(e);
			}, false);
		}
	},
	changeTab: function(event){
		var tab = event.target.getAttribute('tabName');
        var otherTabs = document.querySelectorAll(".tabInfo > *");
		for(var i = 0; i < otherTabs.length; i++){
			otherTabs[i].style.display = 'none';
		}
		document.querySelector("#" + tab).style.display = 'block';
		var oldActive = document.querySelector(".tabsLinks .active");
		if(oldActive != null){
			var oldItemSubClass = oldActive.getAttribute('subClass');
			if(oldItemSubClass === null){
				oldItemSubClass = '';
			}else{
				oldItemSubClass = " " + oldItemSubClass;
			}
			oldActive.setAttribute('id', 'oldActive');
			oldActive = document.querySelector(".tabsLinks #oldActive");
			oldActive.removeAttribute('class');
			oldActive.setAttribute('class', 'oneTabItem' + oldItemSubClass);
			oldActive.removeAttribute('id');
		}
		var subClass = event.target.getAttribute('subClass');
		if(subClass === null){
			subClass = '';
		}else{
			subClass = " " + subClass;
		}
		event.target.removeAttribute('class');
		event.target.setAttribute('class', 'oneTabItem active' + subClass);
	}

};
