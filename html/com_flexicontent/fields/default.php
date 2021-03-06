<?php
/**
 * @version 1.5 stable $Id: default.php 1907 2014-05-27 12:35:36Z ggppdk $
 * @package Joomla
 * @subpackage FLEXIcontent
 * @copyright (C) 2009 Emmanuel Danan - www.vistamedia.fr
 * @license GNU/GPL v2
 * 
 * FLEXIcontent is a derivative work of the excellent QuickFAQ component
 * @copyright (C) 2008 Christoph Lukes
 * see www.schlu.net for more information
 *
 * FLEXIcontent is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

defined('_JEXEC') or die('Restricted access');

$user    = JFactory::getUser();
$cparams = JComponentHelper::getParams( 'com_flexicontent' );
$ctrl = FLEXI_J16GE ? 'fields.' : '';
$fields_task = FLEXI_J16GE ? 'task=fields.' : 'controller=fields&amp;task=';
//$field_model = JModel::getInstance('field', 'FlexicontentModel'); 

$flexi_yes = JText::_( 'FLEXI_YES' );
$flexi_no  = JText::_( 'FLEXI_NO' );
$flexi_nosupport = JText::_( 'FLEXI_PROPERTY_NOT_SUPPORTED', true );
$flexi_rebuild   = JText::_( 'FLEXI_REBUILD_SEARCH_INDEX', true );
$flexi_toggle    = JText::_( 'FLEXI_CLICK_TO_TOGGLE', true );


$ordering_draggable = $cparams->get('draggable_reordering', 1);
if ($this->ordering) {
	$image_ordering_tip = '<i class="icon-info hasTip purple" title="'.JText::_('FLEXI_REORDERING_ENABLED_TIP').'" /></i>' .' ';
	$drag_handle_box = '<div class="fc_drag_handle%s" title="'.JText::_('FLEXI_ORDER_SAVE_WHEN_DONE').'"></div>';
} else {
	$image_ordering_tip = '<i class="icon-info hasTip purple" title="'.JText::_('FLEXI_REORDERING_DISABLED_TIP').'" /></i>' .' ';
	$drag_handle_box = '<div class="fc_drag_handle%s" title="'.JText::_('FLEXI_ORDER_COLUMN_FIRST').'" ></div>';
	$image_saveorder    = '';
}

if ($this->filter_type == '' || $this->filter_type == 0) {
	$ordering_type_tip  = '<img src="templates/flexi/images/flexi/comment.png" class="hasTip" title="'.JText::_('FLEXI_ORDER_JOOMLA').'::'.JText::sprintf('FLEXI_CURRENT_ORDER_IS',JText::_('FLEXI_ORDER_JOOMLA')).' '.JText::_('FLEXI_ITEM_ORDER_EXPLANATION_TIP').'" />';
	$ord_col = 'ordering';
} else {
	$ordering_type_tip  = '<img src="templates/flexi/images/flexi/comment.png" class="hasTip" title="'.JText::_('FLEXI_ORDER_FLEXICONTENT').'::'.JText::sprintf('FLEXI_CURRENT_ORDER_IS',JText::_('FLEXI_ORDER_FLEXICONTENT')).' '.JText::_('FLEXI_ITEM_ORDER_EXPLANATION_TIP').'" />';
	$ord_col = 'typeordering';
}
$ord_grp = 1;

?>

<div class="flexicontent m20">
  <form action="index.php" method="post" name="adminForm" id="adminForm">
    <div class="row-fluid">
      <div class="span12">
        <div class="block-flat"> 
          <!--Content-->
          <div class="row-fluid">
            <div class="span5 w100"> 
              <!--SEARCH-->
              <label class="label searchx hidden-tablet hidden-phone"><?php echo JText::_( 'FLEXI_SEARCH' ); ?></label>
              <input type="text" name="search" id="search" value="<?php echo $this->lists['search']; ?>" onchange="document.adminForm.submit();" class="form-control" placeholder="<?php echo JText::_( 'FLEXI_SEARCH' ); ?>">
              <button class="btn btn-primary btn-rad" onclick="this.form.submit();"><?php echo JText::_( 'FLEXI_GO' ); ?></button>
              <button class="btn btn-rad" onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'FLEXI_RESET' ); ?></button>
              <!--/SEARCH--> 
            </div>
            <div class="span7 text-right line w100"> 
              <!--FILTERS-->
              <div class="smallx">
                <div class="s-filter"><i class="icon-filter"></i> <?php echo $this->lists['assigned'] ."\n"; ?></div>
                <div class="s-filter"><i class="icon-database"></i> <?php echo $this->lists['fftype'] ."\n"; ?></div>
                <div class="s-type"><i class="icon-book"></i> <?php echo $this->lists['filter_type'] ."\n"; ?></div>
                <div class="s-state"><i class="icon-search"></i> <?php echo $this->lists['state'] ."\n"; ?></div>
              </div>
              <!--/FILTERS--> 
            </div>
          </div>
          <!--Content--> 
          
        </div>
      </div>
    </div>
    
    <!--LIMIT-->
    <div class="row-fluid">
      <div class="span6">
        <div style="display:none;" class="alert alert-warning alert-white rounded" id="fcorder_save_warn_box">
          <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
          <div class="icon"><i class="fa icon-warning"></i></div>
          <?php echo JText::_('FLEXI_FCORDER_CLICK_TO_SAVE'); ?> </div>
      </div>
      <div class="span6 text-right">
        <div class="limit m10l hidden-tablet hidden-phone">
          <?php
					echo JText::_(FLEXI_J16GE ? 'JGLOBAL_DISPLAY_NUM' : 'DISPLAY NUM');
					$pagination_footer = $this->pagination->getListFooter();
					if (strpos($pagination_footer, '"limit"') === false) echo $this->pagination->getLimitBox();
					?>
          <span class="fc_item_total_data fc_nice_box m10l" > <?php echo @$this->resultsCounter ? $this->resultsCounter : $this->pagination->getResultsCounter(); // custom Results Counter ?> </span> <span class="fc_pages_counter"> <?php echo $this->pagination->getPagesCounter(); ?> </span> </div>
      </div>
    </div>
    <!--/LIMIT--> 
    
    <!--MAIN TABLE-->
    <div class="row-fluid">
      <div class="span12">
        <div class="block-flat">
          <div class="table-responsive">
            <table class="table no-border no-border-x hover tbl-fields">
              <thead class="no-border">
                <tr class="header">
                  <th rowspan="2" class="h1450"><?php echo JText::_( 'FLEXI_NUM' ); ?></th>
                  <th rowspan="2"><input type="checkbox" name="toggle" value="" onclick="<?php echo FLEXI_J30GE ? 'Joomla.checkAll(this);' : 'checkAll('.count( $this->rows).');'; ?>" /></th>
                  <th rowspan="2" class="nowrap"> <?php if ( !$this->filter_type ) : ?>
                    <?php echo JHTML::_('grid.sort', 'FLEXI_GLOBAL_ORDER', 't.ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>
                    <?php
					if ($this->permission->CanOrderFields) :
						echo $this->ordering ? JHTML::_('grid.order', $this->rows, 'filesave.png', $ctrl.'saveorder' ) : '';
					endif;
					?>
                    <?php else : ?>
                    <?php echo JHTML::_('grid.sort', 'FLEXI_TYPE_ORDER', 'typeordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>
                    <?php
					if ($this->permission->CanOrderFields) :
						echo $this->ordering ? JHTML::_('grid.order', $this->rows, 'filesave.png', $ctrl.'saveorder' ) : '';
					endif;
					?>
                    <?php endif; ?>
                  </th>
                  <th rowspan="2" class="h1200"><?php /*echo JHTML::_('grid.sort', 'FLEXI_FIELD_DESCRIPTION', 't.description', $this->lists['order_Dir'], $this->lists['order'] );*/ ?></th>
                  <th rowspan="2" class="title"><?php echo JHTML::_('grid.sort', 'FLEXI_FIELD_LABEL', 't.label', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                  <th rowspan="2" class="title h1200"><?php echo JHTML::_('grid.sort', 'FLEXI_FIELD_NAME', 't.name', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                  <th rowspan="2" class="title hidden-tablet hidden-phone"><?php echo JHTML::_('grid.sort', 'FLEXI_FIELD_TYPE', 't.field_type', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                  <th colspan="2" class="center nowrap"><small><?php echo JText::_( 'FLEXI_BASIC_INDEX_S' ); ?></small></th>
                  <th colspan="2" class="center nowrap"><small><?php echo JText::_( 'FLEXI_ADV_INDEX_S' ); ?></small></th>
                  <th rowspan="2" class="h1400"><?php echo JHTML::_('grid.sort', 'FLEXI_ASSIGNED_TYPES', 'nrassigned', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                  <th rowspan="2" class="hidden-tablet hidden-phone"><?php echo JHTML::_('grid.sort', 'FLEXI_ACCESS', 't.access', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                  <th rowspan="2" class="nowrap"><?php echo JHTML::_('grid.sort', 'FLEXI_PUBLISHED', 't.published', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                  <th rowspan="2" class="nowrap h1450"><?php echo JHTML::_('grid.sort', 'FLEXI_ID', 't.id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                </tr>
                <tr>
                  <th class="nowrap"><small><?php echo JHTML::_('grid.sort', 'FLEXI_FIELD_CONTENT_LIST_TEXT_SEARCHABLE', 't.issearch', $this->lists['order_Dir'], $this->lists['order'] ); ?></small></th>
                  <th class="nowrap"><small><?php echo JHTML::_('grid.sort', 'FLEXI_FIELD_CONTENT_LIST_FILTERABLE', 't.isfilter', $this->lists['order_Dir'], $this->lists['order'] ); ?></small></th>
                  <th class="nowrap"><small><?php echo JHTML::_('grid.sort', 'FLEXI_FIELD_ADVANCED_TEXT_SEARCHABLE', 't.isadvsearch', $this->lists['order_Dir'], $this->lists['order'] ); ?></small></th>
                  <th class="nowrap"><small><?php echo JHTML::_('grid.sort', 'FLEXI_FIELD_ADVANCED_FILTERABLE', 't.isadvfilter', $this->lists['order_Dir'], $this->lists['order'] ); ?></small></th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <td colspan="15"><?php echo $pagination_footer; ?></td>
                </tr>
              </tfoot>
              <tbody <?php echo $ordering_draggable && $this->permission->CanOrderFields && $this->ordering ? 'id="sortable_fcitems"' : ''; ?> >
                <?php
		$k = 0;
		$i = 0;
		$padcount = 0;
		
		$_desc_label = JText::_('FLEXI_FIELD_DESCRIPTION');
		
		for ($i=0, $n=count($this->rows); $i < $n; $i++)
		{
			$row = & $this->rows[$i];
			
			$padspacer = '';
			$row_css = '';
			
			if ($row->field_type=='groupmarker' || $row->field_type=='coreprops') {
				$fld_params = FLEXI_J16GE ? new JRegistry($row->attribs) : new JParameter($row->attribs);
			}
			if ( $this->filter_type ) // Create coloring and padding for groupmarker fields if filtering by specific type is enabled
			{
				if ($row->field_type=='groupmarker') {
					if ( in_array ($fld_params->get('marker_type'), array( 'tabset_start', 'tabset_end' ) ) ) {
						$row_css = 'color:black;';
					} else if ( in_array ($fld_params->get('marker_type'), array( 'tab_open', 'fieldset_open' ) ) ) {
						$row_css = 'color:darkgreen;';
						for ($icnt=0; $icnt < $padcount; $icnt++) $padspacer .= "&nbsp;|_&nbsp;";
						$padcount++;
					} else if ( in_array ($fld_params->get('marker_type'), array( 'tab_close', 'fieldset_close' ) ) ) {
						$row_css = 'color:darkred;';
						$padcount--;
						for ($icnt=0; $icnt < $padcount; $icnt++) $padspacer .= "&nbsp;|_&nbsp;";
					}
				} else {
					$row_css = '';
					for ($icnt=0; $icnt < $padcount; $icnt++) $padspacer .= "&nbsp;|_&nbsp;";
				}
			}
			
			if (FLEXI_J16GE) {
				$rights = FlexicontentHelperPerm::checkAllItemAccess($user->id, 'field', $row->id);
				$canEdit			= in_array('editfield', $rights);
				$canPublish		= in_array('publishfield', $rights);
				$canDelete		= in_array('deletefield', $rights);
			} else if (FLEXI_ACCESS) {
				$canEdit		= $user->gid==25 ? 1 : FAccess::checkAllContentAccess('com_content','edit','users', $user->gmid, 'field', $row->id);
				$canPublish	= $user->gid==25 ? 1 : FAccess::checkAllContentAccess('com_content','publish','users', $user->gmid, 'field', $row->id);
				$canDelete	= $user->gid==25 ? 1 : FAccess::checkAllContentAccess('com_content','delete','users', $user->gmid, 'field', $row->id);
			} else {
				$canEdit			= $user->gid >= 24;
				$canPublish		= $user->gid >= 24;
				$canDelete		= $user->gid >= 24;
			}
			
			$link 		= 'index.php?option=com_flexicontent&amp;'.$fields_task.'edit&amp;cid[]='. $row->id;
			if ($row->id < 7) {  // First 6 core field are not unpublishable
				$published 	= JHTML::image( 'administrator/templates/flexi/images/flexi/tick_f2.png', JText::_ ( 'FLEXI_NOT_AVAILABLE' ) );
			} else if (!$canPublish && $row->published) {   // No privilige published
				$published 	= JHTML::image( 'administrator/templates/flexi/images/flexi/tick_f2.png', JText::_ ( 'FLEXI_NOT_AVAILABLE' ) );
			} else if (!$canPublish && !$row->published) {   // No privilige unpublished
				$published 	= JHTML::image( 'administrator/templates/flexi/images/flexi/publish_x_f2.png', JText::_ ( 'FLEXI_NOT_AVAILABLE' ) );
			} else {
				if (FLEXI_J16GE)
					$published 	= JHTML::_('jgrid.published', $row->published, $i, $ctrl );
				else
					$published 	= JHTML::_('grid.published', $row, $i );
			}
			
			//check which properties are supported by current field
			$ft_support = FlexicontentFields::getPropertySupport($row->field_type, $row->iscore);
			
			$supportsearch    = $ft_support->supportsearch;
			$supportfilter    = $ft_support->supportfilter;
			$supportadvsearch = $ft_support->supportadvsearch;
			$supportadvfilter = $ft_support->supportadvfilter;
			
			if ($row->issearch==0 || $row->issearch==1 || !$supportsearch) {
				$search_dirty = 0;
				$issearch = ($row->issearch && $supportsearch) ? "tick.png" : "publish_x".(!$supportsearch ? '_f2' : '').".png";
				$issearch_tip = ($row->issearch && $supportsearch) ? $flexi_yes.", ".$flexi_toggle : ($supportsearch ? $flexi_no.", ".$flexi_toggle : $flexi_nosupport);
			} else {
				$search_dirty = 1;
				$issearch = $row->issearch==-1 ? "disconnect.png" : "connect.png";
				$issearch_tip = ($row->issearch==2 ? $flexi_yes : $flexi_no) .", ".$flexi_toggle.", ". $flexi_rebuild;
			}
			
			$isfilter = ($row->isfilter && $supportfilter) ? "tick.png" : "publish_x".(!$supportfilter ? '_f2' : '').".png";	
			$isfilter_tip = ($row->isfilter && $supportfilter) ? $flexi_yes.", ".$flexi_toggle : ($supportsearch ? $flexi_no.", ".$flexi_toggle : $flexi_nosupport);
			
			if ($row->isadvsearch==0 || $row->isadvsearch==1 || !$supportadvsearch) {
				$advsearch_dirty = 0;
				$isadvsearch = ($row->isadvsearch && $supportadvsearch) ? "tick.png" : "publish_x".(!$supportadvsearch ? '_f2' : '').".png";
				$isadvsearch_tip = ($row->isadvsearch && $supportadvsearch) ? $flexi_yes.", ".$flexi_toggle : ($supportadvsearch ? $flexi_no.", ".$flexi_toggle : $flexi_nosupport);
			} else {
				$advsearch_dirty = 1;
				$isadvsearch = $row->isadvsearch==-1 ? "disconnect.png" : "connect.png";
				$isadvsearch_tip = ($row->isadvsearch==2 ? $flexi_yes : $flexi_no) .", ".$flexi_toggle.", ". $flexi_rebuild;
			}
			
			if ($row->isadvfilter==0 || $row->isadvfilter==1 || !$supportadvfilter) {
				$advfilter_dirty = 0;
				$isadvfilter = ($row->isadvfilter && $supportadvfilter) ? "tick.png" : "publish_x".(!$supportadvfilter ? '_f2' : '').".png";
				$isadvfilter_tip = ($row->isadvfilter && $supportadvfilter) ? $flexi_yes : ($supportadvfilter ? $flexi_no : $flexi_nosupport);
			} else {
				$advfilter_dirty = 1;
				$isadvfilter = $row->isadvfilter==-1 ? "disconnect.png" : "connect.png";
				$isadvfilter_tip = ($row->isadvfilter==2 ? $flexi_yes : $flexi_no) .", ". $flexi_rebuild;
			}
			
			if (FLEXI_J16GE) {
				if ($canPublish) {
					$access = flexicontent_html::userlevel('access['.$row->id.']', $row->access, 'onchange="return listItemTask(\'cb'.$i.'\',\''.$ctrl.'access\')"');
				} else {
					$access = $this->escape($row->access_level);
				}
			} else if (FLEXI_ACCESS) {
				$access 	= FAccess::accessswitch('field', $row, $i);
			} else {
				$access 	= JHTML::_('grid.access', $row, $i );
			}
			
			$checked 	= @ JHTML::_('grid.checkedout', $row, $i );
			$warning	= '<span class="hasTip purple" title="'. JText::_ ( 'FLEXI_WARNING' ) .'::'. JText::_ ( 'FLEXI_NO_TYPES_ASSIGNED' ) .'">' . JHTML::image ( 'administrator/templates/flexi/images/flexi/warning.png', JText::_ ( 'FLEXI_NO_TYPES_ASSIGNED' ) ) . '</span>';
   		?>
                <tr class="<?php echo "row$k"; ?>" style="<?php echo $row_css; ?>">
                  <td class="h1450"><?php echo $this->pagination->getRowOffset( $i ); ?></td>
                  <td><?php echo $checked; ?></td>
                  <?php if ($this->permission->CanOrderFields) : ?>
                  <td class="order"><?php
					$show_orderUp   = $i > 0;
					$show_orderDown = $i < $n-1;
				?>
                    <?php if ($ordering_draggable) : ?>
                    <?php
						if (!$this->ordering) echo sprintf($drag_handle_box,' fc_drag_handle_disabled');
						else if ($show_orderUp && $show_orderDown) echo sprintf($drag_handle_box,' fc_drag_handle_both');
						else if ($show_orderUp) echo sprintf($drag_handle_box,' fc_drag_handle_uponly');
						else if ($show_orderDown) echo sprintf($drag_handle_box,' fc_drag_handle_downonly');
						else echo sprintf($drag_handle_box,'_none');
					?>
                    <?php else: ?>
                    <span><?php echo $this->pagination->orderUpIcon( $i, true, $ctrl.'orderup', 'Move Up', $this->ordering ); ?></span> <span><?php echo $this->pagination->orderDownIcon( $i, $n, true, $ctrl.'orderdown', 'Move Down', $this->ordering );?></span>
                    <?php endif; ?>
                    <?php $disabled = $this->ordering ?  '' : '"disabled=disabled"'; ?>
                    <input class="fcitem_order_no" type="text" name="order[]" size="5" value="<?php echo $row->$ord_col; ?>" <?php echo $disabled; ?> style="text-align: center" />
                    <input type="hidden" name="item_cb[]" style="display:none;" value="<?php echo $row->id; ?>" />
                    <input type="hidden" name="prev_order[]" style="display:none;" value="<?php echo $row->$ord_col; ?>" />
                    <input type="hidden" name="ord_grp[]" style="display:none;" value="<?php echo $show_orderDown ? $ord_grp : $ord_grp++; ?>" /></td>
                  <?php else : ?>
                  <td class="center"><?php
				if ($this->filter_type == '' || $this->filter_type == 0) {
					echo $row->ordering;
				} else {
					echo $row->typeordering;
				}
				?></td>
                  <?php endif; ?>
                  <td class="h1200"><?php
				$translated_label = JText::_($row->label);
				$original_label_text = ($translated_label != $row->label) ? '<br/><small>'.$row->label.'</small>' : '';
				$escaped_label = htmlspecialchars(JText::_($row->label), ENT_QUOTES, 'UTF-8');
				
				$field_desc = '';
				$field_desc_len = JString::strlen($row->description);
				if ($field_desc_len > 50) {
					$field_desc = JString::substr( htmlspecialchars($row->description, ENT_QUOTES, 'UTF-8'), 0 , 50).'...';
				} else if ($field_desc_len) {
					$field_desc = htmlspecialchars($row->description, ENT_QUOTES, 'UTF-8');
				}
				if ($field_desc) echo ' <i class="icon-info hasTip purple" title="'.$_desc_label.' :: '.$field_desc.'" /></i>';
				?></td>
                  <td><?php
				echo $padspacer;
				
				// Display an icon with checkin link, if current user has checked out current item
				if ($row->checked_out) {
					if (FLEXI_J16GE) {
						$canCheckin = $user->authorise('core.admin', 'checkin');
					} else if (FLEXI_ACCESS) {
						$canCheckin = ($user->gid < 25) ? FAccess::checkComponentAccess('com_checkin', 'manage', 'users', $user->gmid) : 1;
					} else {
						$canCheckin = $user->gid >= 24;
					}
					if ($canCheckin) {
						//if (FLEXI_J16GE && $row->checked_out == $user->id) echo JHtml::_('jgrid.checkedout', $i, $row->editor, $row->checked_out_time, 'types.', $canCheckin);
						$task_str = FLEXI_J16GE ? 'types.checkin' : 'checkin';
						if ($row->checked_out == $user->id) {
							echo JText::sprintf('FLEXI_CLICK_TO_RELEASE_YOUR_LOCK', $row->editor, $row->checked_out_time, '"cb'.$i.'"', '"'.$task_str.'"');
						} else {
							echo '<input id="cb'.$i.'" type="checkbox" value="'.$row->id.'" name="cid[]" style="display:none;">';
							echo JText::sprintf('FLEXI_CLICK_TO_RELEASE_FOREIGN_LOCK', $row->editor, $row->checked_out_time, '"cb'.$i.'"', '"'.$task_str.'"');
						}
					}
				}
				
				// Display title with no edit link ... if row checked out by different user -OR- is uneditable
				if ( ( $row->checked_out && $row->checked_out != $user->id ) || ( !$canEdit ) ) {
					echo $translated_label;
					echo $original_label_text;
				
				// Display title with edit link ... (row editable and not checked out)
				} else {
				?>
                    <span class="editlinktip hasTip" title="<?php echo JText::_( 'FLEXI_EDIT_FIELD' );?>::<?php echo $escaped_label; ?>"> <a href="<?php echo $link; ?>"> <?php echo $translated_label; ?> </a></span> <?php echo $original_label_text;?>
                    <?php
				}
				?></td>
                  <td class="h1200"><?php echo $row->name; ?></td>
                  <td class="hidden-tablet hidden-phone"><?php $row->field_friendlyname = str_ireplace("FLEXIcontent - ","",$row->field_friendlyname); ?>
                    <?php
				echo "<strong>".$row->type."</strong><br/><small class=\"h1200\">-&nbsp;";
				if ($row->field_type=='groupmarker') {
					echo $fld_params->get('marker_type');
				} else if ($row->field_type=='coreprops') {
					echo $fld_params->get('props_type');
				} else {
					echo $row->iscore?"[Core]" : "{$row->field_friendlyname}";
				}
				echo "&nbsp;-</small>";
				?></td>
                  <td class="center"><?php if($supportsearch) :?>
                    <a title="Toggle property" onclick="document.adminForm.propname.value='issearch'; return listItemTask('cb<?php echo $i;?>','toggleprop')" href="javascript:void(0);"> <img src="templates/flexi/images/flexi/<?php echo $issearch;?>" width="16" height="16" border="0" title="<?php echo $issearch_tip;?>" alt="<?php echo $issearch_tip;?>" /> </a>
                    <?php endif; ?></td>
                  <td class="center"><?php if($supportfilter) :?>
                    <a title="Toggle property" onclick="document.adminForm.propname.value='isfilter'; return listItemTask('cb<?php echo $i;?>','toggleprop')" href="javascript:void(0);"> <img src="templates/flexi/images/flexi/<?php echo $isfilter;?>" width="16" height="16" border="0" title="<?php echo $isfilter_tip;?>" alt="<?php echo $isfilter_tip;?>" /> </a>
                    <?php endif; ?></td>
                  <td class="center"><?php if($supportadvsearch) :?>
                    <a title="Toggle property" onclick="document.adminForm.propname.value='isadvsearch'; return listItemTask('cb<?php echo $i;?>','toggleprop')" href="javascript:void(0);"> <img src="templates/flexi/images/flexi/<?php echo $isadvsearch;?>" width="16" height="16" border="0" title="<?php echo $isadvsearch_tip;?>" alt="<?php echo $isadvsearch_tip;?>" /> </a>
                    <?php endif; ?></td>
                  <td class="center"><?php if($supportadvfilter) :?>
                    <a title="Toggle property" onclick="document.adminForm.propname.value='isadvfilter'; return listItemTask('cb<?php echo $i;?>','toggleprop')" href="javascript:void(0);"> <img src="templates/flexi/images/flexi/<?php echo $isadvfilter;?>" width="16" height="16" border="0" title="<?php echo $isadvfilter_tip;?>" alt="<?php echo $isadvfilter_tip;?>" /> </a>
                    <?php endif; ?></td>
                  <td class="center h1400"><?php echo $row->nrassigned ? $row->nrassigned : $warning; ?></td>
                  <td class="center hidden-tablet hidden-phone"><?php echo $access; ?></td>
                  <td class="center"><?php echo $published; ?></td>
                  <td class="center h1450"><?php echo $row->id; ?></td>
                </tr>
                <?php $k = 1 - $k; } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <p><sup>[1]</sup> <?php echo JText::_('FLEXI_DEFINE_FIELD_ORDER_FILTER_BY_TYPE'); ?><br />
      <sup>[2]</sup> <?php echo JText::_('FLEXI_DEFINE_FIELD_ORDER_FILTER_WITHOUT_TYPE'); ?><br />
    </p>
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="option" value="com_flexicontent" />
    <input type="hidden" name="controller" value="fields" />
    <input type="hidden" name="view" value="fields" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="propname" value="" />
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
    <?php echo JHTML::_( 'form.token' ); ?>
  </form>
</div>
