var blockElement = null;

$(window).load(function(){
	if(blockElement != null){
		$(blockElement).unblock();
	}
});

function blockUi(element){
	blockElement = element;
	element.block({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } });
}

