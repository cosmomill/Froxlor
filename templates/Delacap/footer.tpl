    </div><!-- /content -->
    
    <if isset($userinfo['loginname'])>
        <footer>
        
        </footer>
    </if>
    
    </div><!-- /container -->
    <div class="modal hide fade in" id="dialogabout">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">&times;</a>
            <h3>About</h3>
        </div>
        <div class="modal-body">

            <span>Froxlor 
                <if ($settings['admin']['show_version_login'] == '1' && $filename == 'index.php') || ($filename != 'index.php' && $settings['admin']['show_version_footer'] == '1')>
                    {$version}{$branding}
                </if>
                &copy; 2009-{$current_year} by <a href="http://www.froxlor.org/" rel="external">the Froxlor Team</a>
            </span>
            <if $lng['translator'] != ''>
                <br /><span>{$lng['panel']['translator']}: {$lng['translator']}
            </if>
            <br />
            <span>Theme by <a href="http://www.delacap.com/" rel="external">DELACAP</a>. Based on <a href="http://twitter.github.com/bootstrap/" rel="external">Twitter Bootstrap</a>.</span>
        
        </div>
    </div>
    <div class="modal hide fade in" id="dialogmodal"></div>
    <div class="modal hide fade in" id="dialogerror"></div>
    <script type="text/javascript" src="templates/Delacap/assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="templates/Delacap/assets/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="templates/Delacap/assets/js/prepareNav.js"></script>
	<script type="text/javascript" src="templates/Delacap/assets/js/pagination.js"></script>
	<script type="text/javascript" src="templates/Delacap/assets/js/buttons.js"></script>
	<script type="text/javascript" src="templates/Delacap/assets/js/forwarder.js"></script>
    <script type="text/javascript" src="templates/Delacap/assets/js/select.js"></script>
    <script type="text/javascript" src="templates/Delacap/assets/js/nav.js"></script>
    <script type="text/javascript" src="templates/Delacap/assets/js/form.js"></script>
    <script type="text/javascript" src="templates/Delacap/assets/js/main.js"></script>
</body>
</html>