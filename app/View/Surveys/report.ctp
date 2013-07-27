<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
            <div class="span12">
                    <!-- BEGIN STYLE CUSTOMIZER-->
                    <div class="styler-panel hidden-phone">
                            <i class="icon-cog"></i>
                            <i class="icon-remove"></i>
                            <span class="settings">
                            <span class="text">Style:</span>
                            <span class="colors">
                            <span class="color-default" data-style="default"></span>
                            <span class="color-blue" data-style="blue"></span>
                            <span class="color-light" data-style="light"></span>		
                            </span>
                            <span class="layout">
                            <label class="hidden-phone">
                            <input type="checkbox" class="header" checked="" value="" />Fixed Header
                            </label>							
                            </span>
                            </span>
                    </div>
                    <!-- END STYLE CUSTOMIZER-->    	
                    <!-- BEGIN PAGE TITLE & BREADCRUMB-->		
                    <h3 class="page-title">
                            Dashboard
                            <small>statistics and more</small>
                    </h3>
                    <ul class="breadcrumb">
                            <li>
                                    <i class="icon-home"></i>
                                    <a href="index.html">Home</a> 
                                    <span class="icon-angle-right"></span>
                            </li>
                            <li><a href="#">Dashboard</a></li>
                            <span class="icon-angle-right"></span>
                            <li><a href="#">BR Feedback</a></li>
                    </ul>
                    <!-- END PAGE TITLE & BREADCRUMB-->
            </div>
    </div>
    <!-- END PAGE HEADER-->
    <div id="dashboard">
            <?php echo $this->element('dashboard_stat');?>
            <div class="clearfix"></div>

            <div class="row-fluid">
                    <div class="span12">
                    <!-- BEGAIN MAIN CONTENT-->

                    <!-- BEGAIN FILTERING PORTLET-->
                    <div class="portlet box blue">
                            <div class="portlet-title">
                                    <h4><i class="icon-info-sign"></i>Query Panel</h4>
                                    <div class="tools">
                                            <a href="javascript:;" class="collapse"></a>
                                            <a href="javascript:;" class="reload"></a>
                                            <a href="javascript:;" class="remove"></a>
                                    </div>
                            </div>
                            <div class="portlet-body">
                                    <div style="height:330px;">

                                    <form class="form-horizontal" name="search" method="post" action="" id="">

            <div>
                    <div class="control-group">
    <label class="control-label">House Name</label>
    <div class="controls">
        <select class="span6 m-wrap" data-placeholder="Choose a House" tabindex="1">
        <option value="" />All House

        </select>
    </div>
    </div>


                    <div class="control-group">
    <label class="control-label">Date Ranges</label>
    <div class="controls">
        <div id="form-date-range" class="btn">
        <i class="icon-calendar"></i>
        &nbsp;<span></span> 
        <b class="caret"></b>
        </div>
    </div>
    </div>


                    <!-- 3rd row end -->
                    <hr />
                    <div class="control-group">
    <label class="control-label">Age Group</label>
    <div class="controls">
        <select class="span6 m-wrap" data-placeholder="Choose a House" tabindex="1">
        <option value="" />Any
                                            <option value="" />18 - 22
                                            <option value="" />23 - 25
                                            <option value="" />25 - 30
                                            <option value="" />30+

        </select>
    </div>
    </div>

                    <div class="control-group">
    <label class="control-label">ADC</label>
    <div class="controls">
        <select class="span6 m-wrap" data-placeholder="Choose a House" tabindex="1">
        <option value="" />Any
                                            <option value="" />1 - 5
                                            <option value="" />6 - 8
                                            <option value="" />9 - 11
        <option value="" />11+
        </select>
    </div>
    </div>

                    <div class="control-group">
    <label class="control-label">Occupation</label>
    <div class="controls">
        <select class="span6 m-wrap" data-placeholder="Choose a House" tabindex="1">
        <option value="" />Any
                                            <option value="" />Student
                                            <option value="" />Service
                                            <option value="" />Business
        <option value="" />Others

        </select>
    </div>
    </div>
            </div>

            <div style="margin:0 auto;width:100%;text-align:center">
                    <table><tr>
                    <td><input class="btn yellow btn-block" value="Search" type="submit"/></td>
                    </tr></table>
            </div>
            </form>


                                    </div>
                                    </div>
                            </div>
                    </div>
                    <!-- END FILTERING PORTLET-->

                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet box red">
                            <div class="portlet-title">
                                    <h4><i class="icon-reorder"></i>Consumer Detail</h4>
                                    <div class="tools">
                                            <a href="javascript:;" class="collapse"></a>								
                                            <a href="javascript:;" class="reload"></a>
                                            <a href="javascript:;" class="remove"></a>
                                    </div>
                            </div>
                            <div class="portlet-body">

                                    <table class="table table-striped table-bordered" id="sample_1">
                                            <thead>
                                                <tr>
                                                    <th>SL</th>
                                                    <th>Region</th>
                                                    <th class="hidden-phone">Area</th>
                                                    <th class="hidden-phone">House</th>
                                                    <th class="hidden-phone">BR Name</th>
                                                    <th class="hidden-phone">SUP Name</th>
                                                    <th class="hidden-phone">Consumer Name</th>
                                                    <th class="hidden-phone">Phone No</th>
                                                    <th class="hidden-phone">AGE</th>
                                                    <th class="hidden-phone">ADC</th>
                                                    <th class="hidden-phone">Occupation</th>
                                                    <th class="hidden-phone">Date</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                    <?php
                                    if( isset($Surveys) && !empty($Surveys) ){
                                        //pr($Surveys);
                                        foreach($Surveys as $srv){
                                ?>
                                            <tr class="odd gradeX">
                                                <td><?php echo $srv['Survey']['id'];?></td>
                                                <td><?php echo $srv['Representative']['House']['Area']['Region']['title'];?></td>
                                                <td class="hidden-phone"><?php echo $srv['Representative']['House']['Area']['title'];?></td>
                                                <td class="hidden-phone"><?php echo $srv['Representative']['House']['title'];?></td>
                                                <td class="center hidden-phone"><?php echo $srv['Representative']['name'];?></td>
                                                <td class="hidden-phone"><?php echo $srv['Representative']['superviser_name'];?></td>
                                                <td class="hidden-phone"><?php echo $srv['Survey']['name'];?></td>
                                                <td class="hidden-phone"><?php echo $srv['Survey']['phone'];?></td>
                                                <td class="center hidden-phone"><?php echo $srv['Survey']['age'];?></td>
                                                <td class="hidden-phone"><?php echo $srv['Survey']['adc'];?></td>
                                                <td class="hidden-phone"><?php echo $srv['Occupation']['title'];?></td>
                                                <td class="hidden-phone"><?php echo $srv['Survey']['created'];?></td>
                                            </tr>

                                <?php

                                        }                                                                    
                                    }else{
                                        echo 'Nothing found';
                                    }
                                ?>
                                            </tbody>
                                    </table>
                                <div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
                            </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->

                            <!-- END MAIN CONTENT-->
                    </div>
            </div>


    </div>