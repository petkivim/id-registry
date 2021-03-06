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

// Load styles
$document = JFactory::getDocument();
$document->addStyleSheet("components/com_issnregistry/css/publisher.css");

$document->addScriptDeclaration('
    Joomla.submitbutton = function(task) {
        if(task == "publishers.delete") {
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
<form action="index.php?option=com_issnregistry&view=publishers" method="post" id="adminForm" name="adminForm">
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
                        <th width="1%"><?php echo JText::_('COM_ISSNREGISTRY_PUBLISHERS_NUM'); ?></th>
                        <th width="2%">
                            <?php echo JHtml::_('grid.checkall'); ?>
                        </th>
                        <th width="60%">
                            <?php echo JText::_('COM_ISSNREGISTRY_PUBLISHERS_OFFICIAL_NAME'); ?>
                        </th>
                        <th width="35%">
                            <?php echo JText::_('COM_ISSNREGISTRY_PUBLISHERS_CONTACT_PERSON'); ?>
                        </th>
                        <th width="2%">
                            <?php echo JText::_('COM_ISSNREGISTRY_PUBLISHERS_ID'); ?>
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
                    <?php
                    foreach ($this->items as $i => $row) :
                        $link = JRoute::_('index.php?option=com_issnregistry&task=publisher.edit&id=' . $row->id);
                        ?>
                        <tr>
                            <td><?php echo $this->pagination->getRowOffset($i); ?></td>
                            <td>
                                <?php echo JHtml::_('grid.id', $i, $row->id); ?>
                            </td>
                            <td>
                                <a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_ISSNREGISTRY_EDIT_PUBLISHER'); ?>">
                                    <?php echo $row->official_name; ?>
                                </a>
                            </td>   
                            <td>
                                <?php
                                $json = json_decode($row->contact_person);
                                if (!empty($json)) {
                                    $names = implode(', ', $json->{'name'});
                                    echo $names;
                                }
                                ?>

                            </td> 
                            <td align="center">
                                <?php echo $row->id; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>              
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <?php echo JHtml::_('form.token'); ?>
</form>

