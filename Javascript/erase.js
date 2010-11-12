$(document).ready(function())
{
$('.default-value').each(function() 
	{
    	var default_value = this.value;
    	$(this).focus(function() 
	{
        	if(this.value == default_value) 
		{
            		this.value = '';
        	}
    	});
    	$(this).blur(function() 
	{
        	if(this.value == '') 
		{
            	this.value = default_value;
        	}
    	});
	});

   
});

