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

     <form class="form-horizontal" name="search" method="post" action="report" id="">
         
    

            <div>
                    <div class="control-group">
    <label class="control-label">House Name</label>
    <div class="controls">
        <?php 
            //echo $this->Form->create('Survey',array('type' => 'get', 'action'=>'report', 'class' => 'form-horizontal'));    
            $selected_house_id = isset($this->data['House']['id']) ? $this->data['House']['id'] : '';
            echo $this->Form->input('House.id',array('type' => 'select', 'options' => $houses, 
                'label' => false, 'class' => 'span6 m-wrap', 'empty' => 'Choose a House', 'selected' => $selected_house_id));
        ?>
        <?php
            if( isset($this->data['Region']['id']) ){
                echo $this->Form->input('Region.id',array('type' => 'hidden'));
            }
            if( isset($this->data['Area']['id']) ){
                echo $this->Form->input('Area.id', array('type' => 'hidden'));
            }
        ?>
<!--        <input type="hidden" name="data[Region][id]" value="<?php echo $this->data['Region']['id'];?>"/>
        <input type="hidden" name="data[Area][id]" value="<?php echo $this->data['Area']['id'];?>"/>-->
<!--        <input type="hidden" name="data[House][id]" value="<?php echo $this->data['House']['id'];?>"/>-->
    </div>
    </div>


                    <div class="control-group">
    <label class="control-label">Date Ranges</label>
    <div class="controls">
<!--        <div id="form-date-range" class="btn">
        <i class="icon-calendar"></i>        
        &nbsp;<span></span> 
        <b class="caret"></b>
        </div>-->

        <input size="25" name="start_date" onFocus="this.value=''" onClick="showCalendarControl(this);" type="text"  value="<?php echo isset($this->data['start_date']) ? $this->data['start_date'] : '';?>" />
        <input size="25" name="end_date" onFocus="this.value=''" onClick="showCalendarControl(this);" type="text"  value="<?php echo isset($this->data['end_date']) ? $this->data['end_date'] : '';?>" />   
    </div>
    </div>


                    <!-- 3rd row end -->
                    <hr />
                    <div class="control-group">
    <label class="control-label">Age Group</label>
    <div class="controls">
        <select class="span6 m-wrap" name="age_limit" tabindex="1">
            <option value="0" <?php echo isset($this->data['age_limit'])&&$this->data['age_limit']=='0' ?'selected':'';?>/>Any
            <option value="18.22" <?php echo isset($this->data['age_limit'])&&$this->data['age_limit']=='18.22'?'selected':'';?>/>18 - 22
            <option value="23.25" <?php echo isset($this->data['age_limit']) && $this->data['age_limit']=='23.25'?'selected':'';?>/>23 - 25
            <option value="26.30" <?php echo isset($this->data['age_limit']) && $this->data['age_limit']=='26.30'?'selected':'';?>/>26 - 30
            <option value="31" <?php echo isset($this->data['age_limit']) && $this->data['age_limit']=='31'?'selected':'';?>/>30+
        </select>
    </div>
    </div>

                    <div class="control-group">
    <label class="control-label">ADC</label>
    <div class="controls">
        <select class="span6 m-wrap" name="adc" tabindex="1">
            <?php
                $options = array('0' => 'Any','1.5' => '1 - 5','6.8' => '6 - 8', '9.11' => '9 - 11', '12' => '11+');
                foreach($options as $k => $op){
                    echo '<option value="'.$k.'"';
                    echo isset($this->data['adc']) && $this->data['adc']==$k ? 'selected' : '';
                    echo ' />'.$op;
                }
            ?>
        </select>
    </div>
    </div>

                    <div class="control-group">
    <label class="control-label">Occupation</label>
    <div class="controls">
        <?php 
            echo $this->Form->input('occupation_id',array('type' => 'select', 'class' => 'span6 m-wrap',
                'options' => $occupations, 'empty' => 'Any', 'label' => false));           
                 
     
            $url_params = array();

            if( isset($this->data['start_date']) ){
                $url_params['start_date'] = $this->data['start_date'];
            }
            if( isset($this->data['end_date']) ){
                $url_params['end_date'] = $this->data['end_date'];
            }
            if( isset($this->data['Region']['id']) && $this->data['Region']['id']){
                $url_params['region_id'] = $this->data['Region']['id'];
            }
            if( isset($this->data['Area']['id']) && $this->data['Area']['id']){
                $url_params['area_id'] = $this->data['Area']['id'];
            }
            if( isset($this->data['House']['id']) && $this->data['House']['id']){
                $url_params['house_id'] = $this->data['House']['id'];
            }
            if( isset($this->data['occupation_id']) ){
                $url_params['occupation_id'] = $this->data['occupation_id'];
            }
            if( isset($this->data['age_limit']) ){
                $url_params['age_limit'] = $this->data['age_limit'];
            }
            if( isset($this->data['adc']) ){
                $url_params['adc'] = $this->data['adc'];
            }
            $this->Paginator->options(array('url' => $url_params));            
        ?>
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
            echo $this->Paginator->counter(array(
            'format' => __('Showing {:current} records out of {:count} total')
            ));
            echo '<br/>';
		echo $this->Paginator->prev('< ' . __('previous | '), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ' | '));
		echo $this->Paginator->next(__(' | next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
                            </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                    <form action="export_report" method="post">
                        <input type="hidden" name="data[Region][id]" value="<?php echo isset($this->data['Region']['id']) ? $this->data['Region']['id'] : '';?>"/>
                        <input type="hidden" name="data[Area][id]" value="<?php echo isset($this->data['Area']['id']) ? $this->data['Area']['id'] : '';?>"/>
                        <input type="hidden" name="data[House][id]" value="<?php echo isset($this->data['House']['id']) ? $this->data['House']['id'] : '';?>"/>
                        <input name="start_date" type="hidden" value="<?php echo isset($this->data['start_date']) ? $this->data['start_date'] : '';?>" />
                        <input name="end_date" type="hidden" value="<?php echo isset($this->data['end_date']) ? $this->data['end_date'] : '';?>" />   
                        <input type="hidden" name="adc" value="<?php echo isset($this->data['adc']) ? $this->data['adc']: '';?>"/>
                        <input type="hidden" name="age_limit" value="<?php echo isset($this->data['age_limit']) ? $this->data['age_limit']: 0;?>"/>
                        <input type="hidden" name="occupation_id" value="<?php echo isset($this->data['occupation_id']) ? $this->data['occupation_id']: '';?>"/>                        
                        <input type="submit" name="export_report" value="Export"/>
                    </form>

                            <!-- END MAIN CONTENT-->
                    </div>
            </div>


    </div>

<script>
    $(document).ready(function(){
            
    });
</script>