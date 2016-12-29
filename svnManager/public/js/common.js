$(function(){
	supportPlaceholder='placeholder'in document.createElement('input');
    placeholder=function(input){
    	var text = input.attr('placeholder');
    	defaultValue = input.defaultValue;
	    if(!defaultValue){
	    	input.val(text).addClass("phcolor");
	    }
	    input.focus(function(){
	    	if(input.val() == text){ 
	        	$(this).val("");
	      	}
	    }); 
	    input.blur(function(){ 
	      	if(input.val() == ""){      
	        	$(this).val(text).addClass("phcolor");
	      	}
	    });
    	//输入的字符不为灰色
	    input.keydown(function(){ 
	      	$(this).removeClass("phcolor");
	    });
  	}
  	pass_placeholder=function(input){
    	var text = input.attr('placeholder');
    	defaultValue = input.defaultValue;
	    if(!defaultValue){
	    	input.val(text).addClass("phcolor");
	    }
	    input.attr('type',"text");
	    input.attr('val',text);
	    input.focus(function(){
	    	if(input.val() == text){ 
	        	$(this).val("");
	      	}
	      	$(this).attr('type',"password");
	    }); 
	    input.blur(function(){ 
	      	if(input.val() == ""){    
	      		$(this).attr('type',"text");  
	        	$(this).val(text).addClass("phcolor");
	      	}
	    });
    	//输入的字符不为灰色
	    input.keydown(function(){ 
	      	$(this).removeClass("phcolor");
	    });
  	}
  	if(!supportPlaceholder){
    	$('input').each(function(){
      		text = $(this).attr("placeholder");
      		if($(this).attr("type") == "text"){
        		placeholder($(this));
      		}
      		if($(this).attr("type") == "password"){
        		pass_placeholder($(this));
            }
    	});
    }
  	$('.lnav li a').hover(function(){
	  		if($(this).attr("class")=='lost'){
	  			$(this).removeClass('lost');
	  			$(this).addClass('pointing');	
	  		} 			
  		},function(){
		  	if($(this).attr("class")=='pointing'){ 			
		  		$(this).removeClass('pointing');
		  		$(this).addClass('lost');
		  	}	
  	});
  	$('.page li a').hover(function(){
	  		if($(this).attr("class")!='active'){
	  			$(this).addClass('pagepointing');	
	  		} 			
  		},function(){
		  	if($(this).attr("class")=='grey pagepointing'){
		  		$(this).removeClass('pagepointing');
				$(this).addClass('grey');
		  	}	
  	});
	$('.page li a').hover(function(){
		if($(this).attr("class")!='active'){
			$(this).addClass('pagepointing');
		}
	},function(){
		if($(this).attr("class")=='pagepointing'){
			$(this).removeClass('pagepointing');
			$(this).addClass('grey');
		}
	});
  	$('.basic_edit').click(function(){
  		$('.personal_all').hide();
  		$('.edit_personal').show();
  		$('.edit_pass').hide();
  		$('.con_right').hide();
  	});
  	$('.pass_edit').click(function(){
  		$('.personal_all').hide();
  		$('.edit_pass').show();
  		$('.edit_personale').hide();
  		$('.con_right').hide();
  	});
  	$('.wbasic_edit').click(function(){
  		$('.personal_all').hide();
  		$('.edit_personal').show();
  		$('.edit_pass').hide();
  		$('.con_right').hide();
  	});
  	//操作管理界面button动态效果
  	$('.opreat button').click(function(){
  		$(this).siblings('button').addClass('lost_btn');
  		$(this).removeClass('lost_btn');
  		$(this).addClass('cur_btn');
  	});
  	$('.all_btn').click(function(){
		$('.view_usera_bor').show();
		$('.view_proa_bor').hide();
		$('.view_bor form').hide();
  		$('.view_all').show();
  	});
  	$('.add_btn').click(function(){
  		$('.view_all').hide();
  		$('.view_bor form').hide();
  		$('.user_add').show();
  	});
  	$('.edit_btn').click(function(){
		$('.view_all').hide();
		$('.view_bor form').hide();
		$('.user_edit').show();
	});
	$('.proa_all_btn').click(function(){
		$('.view_usera_bor').hide();
		$('.view_proa_bor').show();
		$('.view_proa_bor .view_all').show();
		$('.view_proa_bor form').hide();
	});
	$('.view_proa_bor .add_btn').click(function(){
		$('.view_usera_bor').hide();
		$('.view_all').hide();
		$('.view_proa_bor form').hide();
		$('.view_proa_bor .user_add').show();
	});
	$('.view_proa_bor .edit_btn').click(function(){
		$('.view_usera_bor').hide();
		$('.view_proa_bor .view_all').hide();
		$('.view_proa_bor form').hide();
		$('.view_proa_bor .user_edit').show();
	});
	$('.view_proa_bor .del_btn').click(function(){
		$('.view_usera_bor').hide();
		$('.view_proa_bor .view_all').hide();
		$('.view_proa_bor form').hide();
		$('.view_proa_bor .user_del').show();
	});
});