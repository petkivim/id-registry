<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 	Petteri Kivim�ki
 * @copyright	Copyright (C) 2016 Petteri Kivim�ki. All rights reserved.
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHTML::_('behavior.modal');

// Load styles
$document = JFactory::getDocument();
$document->addStyleSheet("components/com_issnregistry/css/form.css");
// Add scripts
$document->addScript("components/com_issnregistry/scripts/forms.js");

$document->addScriptDeclaration('
    Joomla.submitbutton = function(task) {
        if(task == "forms.delete") {
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
<form action="index.php?option=com_issnregistry&view=forms" method="post" id="adminForm" name="adminForm">
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
                        <th width="1%"><?php echo JText::_('COM_ISSNREGISTRY_FORMS_NUM'); ?></th>
                        <th width="2%">
                            <?php echo JHtml::_('grid.checkall'); ?>
                        </th>
                        <th width="25%">
                            <?php echo JText::_('COM_ISBNREGISTRY_FORMS_PUBLISHER'); ?>
                        </th>	
                        <th width="10%">
                            <?php echo JText::_('COM_ISBNREGISTRY_FORMS_PUBLICATION_COUNT'); ?>
                        </th>
                        <th width="15%">
                            <?php echo JText::_('COM_ISBNREGISTRY_FORMS_PUBLICATION_STATUS'); ?>
                        </th>
                        <th width="15%">
                            <?php echo JText::_('COM_ISBNREGISTRY_FORMS_CREATED_BY'); ?>
                        </th>
                        <th width="15%">
                            <?php echo JText::_('COM_ISBNREGISTRY_FORMS_MODIFIED_BY'); ?>
                        </th>
                        <th width="15%">
                            <?php echo JText::_('COM_ISBNREGISTRY_FORMS_CREATED'); ?>
                        </th>
                        <th width="2%">
                            <?php echo JText::_('COM_ISBNREGISTRY_FORMS_ID'); ?>
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
                            $link = JRoute::_('index.php?option=com_issnregistry&task=form.edit&id=' . $row->id);
                            ?>
                            <tr>
                                <td><?php echo $this->pagination->getRowOffset($i); ?></td>
                                <td>
                                    <?php echo JHtml::_('grid.id', $i, $row->id); ?>
                                </td>
                                <td>
                                    <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_ISSNREGISTRY_FORM_EDIT'); ?>">
                                        <?php echo $row->publisher; ?>
                                    </a>
                                </td>
                                <td>
                                    <?php echo $row->publication_count_issn . ' / ' . $row->publication_count; ?>
                                </td>	
                                <td>
                                    <?php echo JText::_('COM_ISSNREGISTRY_FORM_STATUS_' . $row->status); ?>
                                </td>
                                <td>
                                    <?php echo $row->created_by; ?>
                                </td>
                                <td>
                                    <?php echo $row->modified_by; ?>
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

