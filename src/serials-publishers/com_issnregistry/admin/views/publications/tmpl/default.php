<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Load styles
$document = JFactory::getDocument();
$document->addStyleSheet("components/com_issnregistry/css/publication.css");

$document->addScriptDeclaration('
    Joomla.submitbutton = function(task) {
        if(task == "publications.delete") {
            var response = confirm("' . JText::_('COM_ISSNREGISTRY_CONFIRM_DELETE') . '");
            if (response == true) {
                Joomla.submitform(task);
            }
        } else {
            Joomla.submitform(task);
        }
    };
');
?>
<form action="index.php?option=com_issnregistry&view=publications" method="post" id="adminForm" name="adminForm">
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
                        <th width="1%"><?php echo JText::_('COM_ISSNREGISTRY_PUBLICATIONS_NUM'); ?></th>
                        <th width="2%">
                            <?php echo JHtml::_('grid.checkall'); ?>
                        </th>
                        <th width="10%">
                            <?php echo JText::_('COM_ISSNREGISTRY_PUBLICATIONS_ISSN'); ?>
                        </th>	          
                        <th width="30%">
                            <?php echo JText::_('COM_ISSNREGISTRY_PUBLICATIONS_TITLE'); ?>
                        </th>
                        <th width="25%">
                            <?php echo JText::_('COM_ISSNREGISTRY_PUBLICATIONS_PUBLISHER'); ?>
                        </th>	
                        <th width="15%">
                            <?php echo JText::_('COM_ISSNREGISTRY_PUBLICATIONS_MEDIUM'); ?>
                        </th>
                        <th width="15%">
                            <?php echo JText::_('COM_ISSNREGISTRY_PUBLICATIONS_STATUS'); ?>
                        </th>	
                        <th width="2%">
                            <?php echo JText::_('COM_ISSNREGISTRY_PUBLICATIONS_ID'); ?>
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
                            $linkPublication = JRoute::_('index.php?option=com_issnregistry&task=publication.edit&id=' . $row->id);
                            $linkPublisher = JRoute::_('index.php?option=com_issnregistry&task=publisher.edit&id=' . $row->publisher_id);
                            $linkForm = JRoute::_('index.php?option=com_issnregistry&task=form.edit&id=' . $row->form_id);
                            ?>
                            <tr>
                                <td><?php echo $this->pagination->getRowOffset($i); ?></td>
                                <td>
                                    <?php echo JHtml::_('grid.id', $i, $row->id); ?>
                                </td>    
                                <td>
                                    <?php echo $row->issn; ?>
                                </td> 
                                <td>
                                    <a href="<?php echo $linkPublication; ?>" title="<?php echo JText::_('COM_ISSNREGISTRY_PUBLICATION_EDIT'); ?>">
                                        <?php echo $row->title; ?>
                                    </a>
                                </td>  
                                <td>
                                    <a href="<?php echo $linkPublisher; ?>" title="<?php echo JText::_('COM_ISSNREGISTRY_PUBLISHER_EDIT'); ?>">
                                        <?php echo $row->official_name; ?>
                                    </a>
                                </td>  
                                <td>
                                    <?php echo (empty($row->medium) ? '' : JText::_('COM_ISSNREGISTRY_PUBLICATION_MEDIUM_' . $row->medium)); ?>
                                </td>
                                <td>
                                    <?php echo JText::_('COM_ISSNREGISTRY_PUBLICATION_STATUS_' . $row->status); ?>
                                </td>
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

