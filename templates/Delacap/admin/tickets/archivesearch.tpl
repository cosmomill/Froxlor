$header
	<article>
		<header>
			<h2>
				{$lng['ticket']['archivesearch']}
			</h2>
		</header>
        
		<section>
            <form action="{$linker->getLink(array('section' => 'tickets'))}" method="post">
                <input type="hidden" name="s" value="$s"/>
                <input type="hidden" name="page" value="$page"/>
                <input type="hidden" name="send" value="send" />
                <table cellpadding="5" cellspacing="0" border="0" align="center" class="table table-bordered table-striped">
                    
                    <if 0 < $tickets_count >
                        <tr>
                            <td class="field_display_border_left">{$lng['ticket']['archivedtime']}</td>
                            <td class="field_display">{$lng['ticket']['ticket_answers']}</td>
                            <td class="field_display">{$lng['ticket']['subject']}</td>
                            <td class="field_display">{$lng['ticket']['lastreplier']}</td>
                            <td class="field_display">{$lng['ticket']['priority']}</td>
                                <td class="field_display_search">&nbsp;</td>
                        </tr>
                        $tickets
                    </if>
                    <if $tickets_count < 1 >
                        <tr>
                        <td class="field_display_border_left" colspan="6">{$lng['ticket']['noresults']}</td>
                        </tr>
                    </if>
                </table>
            </form>
		</section>
	</article>
$footer
