<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Load styles
$document = JFactory::getDocument();
$document->addStyleSheet("components/com_isbnregistry/css/publication.css");
// Add scripts
$document->addScriptDeclaration('
    Joomla.submitbutton = function(task) {
        if(task == "publications.delete") {
            var response = confirm("' . JText::_('COM_ISBNREGISTRY_CONFIRM_DELETE') . '");
            if (response == true) {
                Joomla.submitform(task);
            }
        } else {
            Joomla.submitform(task);
        }
    };
');
?>
<form action="index.php?option=com_isbnregistry&view=publications" method="post" id="adminForm" name="adminForm">
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
        <?php
        // Search tools bar
        echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
        ?>
        <?php if (empty($this->items)) : ?>
            <div class="alert alert-no-items">
                <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
            </div>
        <?php else : ?>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th width="1%"><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATIONS_NUM'); ?></th>
                        <th width="2%">
                            <?php echo JHtml::_('grid.checkall'); ?>
                        </th>
                        <th width="25%">
                            <?php echo JText::_('COM_ISBNREGISTRY_PUBLICATIONS_TITLE'); ?>
                        </th>	          
                        <th width="20%">
                            <?php echo JText::_('COM_ISBNREGISTRY_PUBLICATIONS_OFFICIAL_NAME'); ?>
                        </th>
                        <th width="35%">
                            <?php echo JText::_('COM_ISBNREGISTRY_PUBLICATIONS_COMMENTS'); ?>
                        </th>	
                        <th width="10%">
                            <?php echo JText::_('COM_ISBNREGISTRY_PUBLICATIONS_CREATED'); ?>
                        </th>	
                        <th width="2%">
                            <?php echo JText::_('COM_ISBNREGISTRY_PUBLICATIONS_ID'); ?>
                        </th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="5">
                            <?php echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
                    <?php if (!empty($this->items)) : ?>
                        <?php
                        foreach ($this->items as $i => $row) :
                            $link = JRoute::_('index.php?option=com_isbnregistry&task=publication.edit&id=' . $row->id);
                            ?>
                            <tr>
                                <td><?php echo $this->pagination->getRowOffset($i); ?></td>
                                <td>
                                    <?php echo JHtml::_('grid.id', $i, $row->id); ?>
                                </td>    
                                <td>
                                    <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_ISBNREGISTRY_EDIT_PUBLICATION'); ?>">
                                        <?php echo $row->title; ?>
                                    </a>
                                </td>  
                                <td><?php echo $row->official_name; ?>
                                </td> 
                                <td>
                                    <?php echo (strlen($row->comments) > 50 ? substr($row->comments, 0, 70) . '...' : $row->comments); ?>
                                </td>
                                <td>
                                    <?php echo JHtml::date($row->created, 'd.m.Y'); ?>
                                </td> 	                                
                                <td align="center">
                                    <?php echo $row->id; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <?php echo JHtml::_('form.token'); ?>
</form>

