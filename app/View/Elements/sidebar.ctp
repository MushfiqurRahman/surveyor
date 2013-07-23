
    	<!-- Necessary markup, do not remove -->
		<div id="mws-sidebar-stitch"></div>
		<div id="mws-sidebar-bg"></div>
        
        <!-- Sidebar Wrapper -->
        <div id="mws-sidebar">
                  
            <!-- Main Navigation -->
            <div id="mws-navigation">
			<img src="/img/images/logo_w.png" alt="Reflect" width="150" height="70" style="margin-left:15px;"/>
            <br />
			<br />
			<br />
<!-- User Tools (notifications, logout, profile, change password) -->
        <div id="mws-user-tools" class="clearfix">
        
        	
            
            <!-- User Information and functions section -->
            <div id="mws-user-info" class="mws-inset">
            
            	<!-- User Photo -->
            	<div id="mws-user-photo">
                	<img src="/img/example/profile.jpg" alt="User Photo" />
                </div>
                
                <!-- Username and Functions -->
                <div id="mws-user-functions">
                    <div id="mws-username">
                        Hello, Admin
                    </div>
                    <ul>
						
                        <li>
                            <?php echo $this->Html->link('Logout',array('controller' => 'users','action' => 'logout'));?>
<!--                            <a href="logout.html">Logout</a>-->
                        </li>
						
                    </ul>
                </div>
            </div>
        </div>
		
<ul style="margin-top: 20px;">
                	<li class="active">
                            <?php echo $this->Html->link('Dashboard',array('controller' => 'pages', 'action' => 'home'), array('class' => 'mws-i-24 i-home'));?>
<!--                            <a href="index.html" class="mws-i-24 i-home">Dashboard</a>-->
                        </li>
					<li class="active"><a href="#" onclick="window.location.reload(true);" class="mws-i-24 i-refresh">Update</a></li>
					<li class="active">
                    	<a href="#" class="mws-i-24 i-list">Download Zone</a>
                        <ul class="closed">
                        	<li><a href="manual/SMS_Manual.pdf">SMS Manual</a></li>
                        	<li><a href="manual/User_Manual.pdf">Interface User Manual</a></li>
                        </ul>
                    </li>
					<li class="active">
                    	<a href="#" class="mws-i-24 i-user">Online Now: 2</a>
                        <ul class="closed">
                        	<li><a href="#">Admin, Imran</a></li>
                        </ul>
                    </li>
                </ul>	
            </div>            
        </div>