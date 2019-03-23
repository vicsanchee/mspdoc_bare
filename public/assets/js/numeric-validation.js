function checkNumberMinMax(obj)
{	
	var inputVal = $(obj)	.val();

	if(isNaN(inputVal))
	{
		$(obj)	.val('');
	}
	else
	{
		var type 	= $(obj)	.attr('type');
		var maxVal 	= parseInt($(obj)	.attr('max'));
		var minVal 	= parseInt($(obj)	.attr('min'));
		var value 	= parseInt(inputVal);

		if(type.trim() == 'number')
		{
			if(value > maxVal)
			{		
				$(obj)	.val(maxVal);
			}
			else if(value < minVal)
			{
				$(obj)	.val(minVal);
			}
			else
			{
				$(obj)	.val(value);
			}		
		}
		else
		{
			$(obj)	.val('');
		}
	}
}

function validateNumber(event) 
{
    var key = window.event ? event.keyCode : event.which;	
	if (event.keyCode === 8 || event.keyCode === 46) 
	{
        return true;
	}
	else if ( key < 48 || key > 57 ) 
	{
        return false;
    } 
	else 
	{
		return true;		
    }
}



    