/**
* Mail Accounts
*
* @version     	$Id:$
* @author		Rene Kanzler <me (at) renekanzler (dot) com>
*/
;(function($){
		  
	$.fn.accounts = function()
	{
		var oForm = $(this);
		
		if(oForm.size() == 0) {
			return true;
		}
		
		var oLinks = $('a', oForm);
		
		oForm.empty();
		oForm.append('<table id="mail_accounts" class="table table-bordered"></table>');
		
		$(oLinks).each(function(index) {
			if(index < (oLinks.size() - 1)) {
				$('#mail_accounts').append('<tr></tr>');
				$('#mail_accounts tr').last().append('<td>' + $(this).attr('title') + '</td>');
				$('#mail_accounts tr').last().append('<td>' + $(this)[0].outerHTML + '</td>');
			} else {
				$(this).addClass('btn btn-mini btn-success');
				$('#mail_accounts').after($(this)[0].outerHTML);
			}
		});
		
		return true;
	};
	
})(jQuery);
