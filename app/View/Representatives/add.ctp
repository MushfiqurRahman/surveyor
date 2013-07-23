<div class="representatives form">
<?php echo $this->Form->create('Representative'); ?>
	<fieldset>
		<legend><?php echo __('Add SS, SR or TSA'); ?></legend>
	<?php
		echo $this->Form->input('house_id');
		echo $this->Form->input('name');
        echo $this->Form->input('sr_code', array('label' => 'SS/SR/TSA Code'));
    ?>
                <div class="mobile_nos">
                    <label>Mobile No</label>
                    <input type="text" name="data[Mobile][0][mobile_no]" class="mobile_no"/>                
                    <a href="javascript:void(0);" id="add_more_mobile">Add More mobile</a>
                </div>
        <?php
		echo $this->Form->input('type', array('type' => 'select', 'options' => 
                    array('ss' => 'Sales Superviser','sr' => 'Sales Representative', 'tsa' => 'TSA'),
                    'empty' => 'Select type','label' => 'Type'));
	?>
                <div id="div_ss" style="display:none;">
                    <label>Select Superviser</label>
                    <select id="ss_id" name="data[Representative][ss_id]"></select>
                </div>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Representatives'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Houses'), array('controller' => 'houses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New House'), array('controller' => 'houses', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Sales'), array('controller' => 'sales', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Sale'), array('controller' => 'sales', 'action' => 'add')); ?> </li>
	</ul>
</div>
<script>
    $(document).ready(function(){        
        var total_mobile = 1;
        $("#add_more_mobile").click(function(){
            $(".mobile_nos").append('<br /><input type="text" name="data[Mobile]['+total_mobile+'][mobile_no]" class="mobile_no" />');
            total_mobile++;
        });
        
        $("#RepresentativeHouseId").change(function(){
            if($("#RepresentativeType").val()=='sr'){
                select_superviser();
            }
        });
        
        $("#RepresentativeType").change(function(){            
           if( $(this).val()=='sr' ){
               select_superviser();
           }else{
               $("#ss_id").html('');
           } 
        });
        
        function select_superviser(){
            $.ajax({
                  url:'/representatives/ajax_ss_list',
                  type:'post',
                  data:'house_id='+$("#RepresentativeHouseId").val(),
                  success:function(resp){
                      var options = $.parseJSON(resp);
                      
                      var ss = '';

                        if( typeof(options['error']) != "undefined" ){
                            alert(options['error']);
                        }else{
                            $.each(options, function(ind, val){
                                ss += '<option value="'+ind+'">'+val+'</option>';
                            });
                            $("#ss_id").html(ss);
                            $("#div_ss").show();
                        }
                  }
             });
        }
    });
</script>
