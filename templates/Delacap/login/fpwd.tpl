$header
	<article class="login hide">
    
		<header class="loginheader">
            <img src="templates/Delacap/assets/img/alpha.gif" alt="Froxlor Server Management Panel" />
		</header>
        
		<section class="loginmsg">

        	<if $message != ''>
                <div class="alert alert-error">
                    <a class="close" data-dismiss="alert">&times;</a>
                    <h4 class="alert-heading">{$lng['error']['error']}</h4>
                    $message
                </div>
            </if>
            
		</section>
            
        <section class="loginsec">
            <form class="form-inline" method="post" action="$filename" enctype="application/x-www-form-urlencoded">
                <div class="control-group">
                    <input class="sp100" type="text" placeholder="{$lng['login']['username']}" name="loginname" id="loginname" value="" required/>
                </div>
                <div class="control-group">
                    <div class="input-append">
                        <table>
                        	<tr>
                            	<td class="left"><input type="text" placeholder="{$lng['login']['email']}" name="loginemail" id="loginemail" required/></td>
                            	<td><input type="submit" class="btn btn-inverse" value="{$lng['login']['remind']}" /></td>
                            </tr>
                        </table>                        
                    </div>
                </div>
                <div class="clearfix">
                    <input type="hidden" name="action" value="$action" />
                    <input type="hidden" name="send" value="send" />
                    <a href="index.php">{$lng['login']['backtologin']}</a>
                </div>
            </form>
        </section>
        
	</article>
$footer