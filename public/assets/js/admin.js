	function textCounter(field,cnt, maxlimit) {         
		var cntfield = document.getElementById(cnt)	
	     if (field.value.length > maxlimit) // if too long...trim it!
			field.value = field.value.substring(0, maxlimit);
			// otherwise, update 'characters left' counter
			else
			cntfield.innerHTML = maxlimit - field.value.length;
	}

	function toggleSwitch(el, classname){
		if(el.checked){
			el.parentNode.classList.add(classname);
		}else{
			el.parentNode.classList.remove(classname);
		}
		
	}
	function initHeight(el){
		var fixHeight = document.getElementsByClassName('fix-height');
		
		for (var i=0;i<fixHeight.length;i++){
			var divHeight = fixHeight[i].clientHeight;
			fixHeight[i].style.height = divHeight + "px";	
		}
	}
	initHeight();

	function toggleClass(ev, el, classname){
		ev.preventDefault();
		if(el.parentNode.classList.contains(classname)){
			el.parentNode.classList.remove(classname);
		}else{
			el.parentNode.classList.add(classname);
		}
		
	}

	// Copy Span's Content in Input's Value
	function spanToInput(element, id) {
	    document.getElementById(id).value = element.innerHTML;
	    if(element.innerHTML==''){
	    	element.classList.add("empty");
	    }else{
	    	element.classList.remove("empty");
	    }
	}
	/* ##### 
		AUTO RESIZE TEXTAREA
	####* */

		var auto_grow_INIT = function(){
			var textareas = document.getElementsByClassName('autoGrow');

			for (var i=0;i<textareas.length;i++){
			    textareas[i].addEventListener('input', auto_grow(textareas[i]), false);
			    textareas[i].style.height = (textareas[i].scrollHeight)+"px";
			}
		}

	function auto_grow(element) {
	    element.style.height = "auto";
	    element.style.height = (element.scrollHeight)+"px";
	}

	/* ##### 
		CLOSE PARENT
	####* */

	function closeParent(e, element){
		e.preventDefault();
		element.parentNode.classList.add('displayNone');
	}

	/* ##### 
		SHOW/HIDE FILE INPUT
	####* */

	function showHideFileInput(e, element){
		e.preventDefault();
		if (element.parentNode.parentNode.classList.contains('hideFileInput')) {
			element.parentNode.parentNode.classList.remove('hideFileInput');
			element.innerHTML="<span>Ne pas modifier l'image</span>";
		}else{
			element.parentNode.parentNode.classList.add('hideFileInput');
			element.innerHTML="<span>Modifier l'image</span>";
		}
		
	}

	/* ##### 
		VERIFY BOX
	####* */

	function verifyboxINIT(){
		verifyboxShow();
		verifyboxClose();
		verifyboxCheck();
	}


		//Show Verify Box

		var verifyboxShow = function() {
			document.getElementById('showVerifyBox').addEventListener('click', function (e) {
			    e.preventDefault();
			    document.getElementById('verify').classList.add('show');
			    document.getElementById('verifyBack').classList.add('show');
			}, false);
		}
		function closeOverbox(e){
			e.preventDefault();
		    document.getElementById('verify').classList.remove('show');
		    document.getElementById('verifyBack').classList.remove('show');
		}
		var verifyboxClose = function(){
			document.getElementById('verify--close').addEventListener('click', function (e) {
			    closeOverbox(e);
			}, false);
			document.getElementById('verifyBack').addEventListener('click', function (e) {
			    closeOverbox(e);
			}, false);
			document.getElementById('overbox--cross').addEventListener('click', function (e) {
			    closeOverbox(e);
			}, false);
		};

		//Verify Checklist
		var verifyboxCheck = function(){
			var checklistItems = document.getElementsByClassName('checklist--item');
			document.getElementById('submit').disabled = true;
			var checkAllChecked = function(){
				var checked = true;
				for (var i=0;i<checklistItems.length;i++){
					if(!checklistItems[i].checked){
						checked = false;
					}
				}
				if(checked){
					document.getElementById('submit').disabled = false;
				}else{
					document.getElementById('submit').disabled = true;
				}
			}
			for (var i=0;i<checklistItems.length;i++){
			    checklistItems[i].addEventListener('click', checkAllChecked, false);
			}
		}


	/*Prevent Tab Close En attente pour exclure l'événement "submit"
	var warn_on_unload = 'Les modifications apportées ne seront pas sauvegardées. Voulez-vous vraiment quitter ?';
	window.onbeforeunload = function (e) {
	  return warn_on_unload;
	};*/

	/* ##### 
		SIDEBAR
	####* */
		//Sidebar Accordeon
		var sidebarTitle = document.getElementsByClassName('sidebar--title--link');

		var accordeon = function(){
		    var num = arguments[0];
		    var e = arguments[1];
		    e.preventDefault();
		    for (var i=0; i < sidebarTitle.length; i++){
		    	sidebarTitle[i].parentNode.parentNode.classList.add('hide');
		    }
		    sidebarTitle[num].parentNode.parentNode.classList.remove('hide');
		}

		for (var i=0; i < sidebarTitle.length; i++){
		    var title = sidebarTitle[i];
		    title.addEventListener('click', accordeon.bind(this, i ), false);
		}

		//Sidebar Close Open
		var sidebarCloser = document.getElementsByClassName('sidebar--closer')[0];
		sidebarCloser.addEventListener('click', function(e){
			e.preventDefault();
			sidebarCloser.parentNode.classList.add('hide');
			document.body.classList.add('sidebarHided');
		});
		var sidebarOpener = document.getElementsByClassName('sidebar--opener')[0];
		sidebarOpener.addEventListener('click', function(e){
			e.preventDefault();
			sidebarOpener.parentNode.classList.remove('hide');
			document.body.classList.remove('sidebarHided');
		});
		