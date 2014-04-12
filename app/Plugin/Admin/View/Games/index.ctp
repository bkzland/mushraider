<div class="box dark">
    <header>
        <div class="icons"><i class="icon-list icon-white"></i></div>
        <h5><?php echo __('Games list');?></h5>
        <div class="toolbar">
            <ul class="nav">
                <li><?php echo $this->Html->link('<i class="icon-plus"></i> '.__('Add game'), '/admin/games/add', array('escape' => false));?></li>
            </ul>
        </div>
    </header>
    <div class="accordion-body body in collapse">
        <table class="table table-bordered table-striped responsive">
            <thead>
                <tr>
                    <th><?php echo __('Title');?></th>                    
                    <th><?php echo __('Dungeons');?></th>                    
                    <th><?php echo __('Classes');?></th>                    
                    <th><?php echo __('Races');?></th>                    
                    <th><?php echo __('Events');?></th>                    
                    <th><?php echo __('Roster');?></th>
                    <th class="actions"><?php echo __('Actions');?></th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($games)):?>
                    <?php foreach($games as $game):?>
                        <tr>
                            <td>
                                <?php if(!empty($game['Game']['logo'])):?>
                                    <?php echo $this->Html->image('/files/logos/'.$game['Game']['logo'], array('width' => 32));?>
                                <?php endif;?>
                                <?php echo $game['Game']['title'];?>
                            </td>
                            <td class="listing">
                                <?php if(!empty($game['Dungeon'])):?>
                                    <?php foreach($game['Dungeon'] as $key => $dungeon):?>
                                        <span><?php echo $key?', ':'';?><?php echo $dungeon['title'];?></span>                                      
                                    <?php endforeach;?>
                                <?php endif;?>
                            </td>
                            <td class="listing">
                                <?php if(!empty($game['Classe'])):?>
                                    <?php foreach($game['Classe'] as $key => $classe):?>
                                        <span><?php echo $key?', ':'';?><span style="color:<?php echo $classe['color'];?>"><?php echo $classe['title'];?></span></span>
                                    <?php endforeach;?>
                                <?php endif;?>                                
                            </td>
                            <td class="listing">
                                <?php if(!empty($game['Race'])):?>
                                    <?php foreach($game['Race'] as $key => $race):?>
                                        <span><?php echo $key?', ':'';?><?php echo $race['title'];?></span>
                                    <?php endforeach;?>
                                <?php endif;?>                                
                            </td>
                            <td><?php echo count($game['Event']);?></td>
                            <td><?php echo count($game['Character']);?></td>
                            <td class="actions">
                                <?php echo $this->Html->link('<i class="icon-edit"></i>', '/admin/games/edit/'.$game['Game']['id'], array('class' => 'btn btn-info btn-mini tt', 'title' => __('Edit'), 'escape' => false))?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
            </tbody>
        </table>
        <?php echo $this->Tools->pagination('Game');?>
    </div>
</div>