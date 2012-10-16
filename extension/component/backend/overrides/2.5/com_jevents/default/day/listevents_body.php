<?php
defined('_JEXEC') or die('Restricted access');

$cfg	 = & JEVConfig::getInstance();

$this->data = $data = $this->datamodel->getDayData( $this->year, $this->month, $this->day );

$cfg = & JEVConfig::getInstance();
$Itemid = JEVHelper::getItemid();
$cfg = & JEVConfig::getInstance();
$hasevents = false;

echo '<fieldset><legend class="ev_fieldset">' . JText::_('JEV_EVENTSFORTHE') .'</legend><br />' . "\n";
echo '<table align="center" width="90%" cellspacing="0" cellpadding="5" class="ev_table">' . "\n";
?>
    <tr valign="top">
        <td colspan="2"  align="center" class="cal_td_daysnames">
           <!-- <div class="cal_daysnames"> -->
            <?php echo JEventsHTML::getDateFormat( $this->year, $this->month, $this->day, 0) ;?>
            <!-- </div> -->
        </td>
    </tr>
<?php
// Timeless Events First
########################
# FABBRICABINARIA HACK #
#       J4Schema       #
########################
if (count($data['hours']['timeless']['events'])>0){
	$start_time = JText::_( 'TIMELESS' );
        $hasevents = true;

	echo '<tr><td class="ev_td_left">' . $start_time . '</td>' . "\n";
	echo '<td class="ev_td_right"><ul class="ev_ul" {JE_EVENT_WRAPPER}>' . "\n";
	foreach ($data['hours']['timeless']['events'] as $row) {
		$listyle = 'style="border-color:'.$row->bgcolor().';"';
		echo "<li {JE_EVENT_NAME} class='ev_td_li' $listyle>\n";

		$j4sTime = date('Y-m-d H:i:s', $row->_unixstarttime);
		echo '<time {JE_EVENT_STARTTIME:'.$j4sTime.'}>';

		if (!$this->loadedFromTemplate('icalevent.list_row', $row, 0)){
			$this->viewEventRowNew ( $row);
			echo '&nbsp;::&nbsp;';
			$this->viewEventCatRowNew($row);
		}
		echo '</time>';

		$endTime = date('Y-m-d H:i:s', $row->_unixendtime);
		$duration = $row->_unixendtime - $row->_unixstarttime;

		echo '{JE_EVENT_ENDTIME:'.$endTime.'} ';
		echo '{JE_EVENT_DURATION:P'.$duration.'}';

		echo "</li>\n";
	}
	echo "</ul></td></tr>\n";
}

for ($h=0;$h<24;$h++){
	if (count($data['hours'][$h]['events'])>0){
                $hasevents = true;
		$start_time = JEVHelper::getTime($data['hours'][$h]['hour_start']);

		########################
		# FABBRICABINARIA HACK #
		#       J4Schema       #
		########################
		echo '<tr><td class="ev_td_left">' . $start_time . '</td>' . "\n";
		echo '<td class="ev_td_right"><ul class="ev_ul" {JE_EVENT_WRAPPER}>' . "\n";
		foreach ($data['hours'][$h]['events'] as $row) {
			$listyle = 'style="border-color:'.$row->bgcolor().';"';
			echo "<li {JE_EVENT_NAME} class='ev_td_li' $listyle>\n";

			$j4sTime = date('Y-m-d H:i:s', $row->_unixstarttime);
			echo '<time {JE_EVENT_STARTTIME:'.$j4sTime.'}>';

			if (!$this->loadedFromTemplate('icalevent.list_row', $row, 0)){
				$this->viewEventRowNew ( $row);
				echo '&nbsp;::&nbsp;';
				$this->viewEventCatRowNew($row);
			}
			echo '</time>';

			$endTime = date('Y-m-d H:i:s', $row->_unixendtime);
			$duration = $row->_unixendtime - $row->_unixstarttime;

			echo '{JE_EVENT_ENDTIME:'.$endTime.'} ';
			echo '{JE_EVENT_DURATION:P'.$duration.'}';

			echo "</li>\n";
		}
		echo "</ul></td></tr>\n";
	}
}
if (!$hasevents) {
		echo '<tr><td class="ev_td_right" colspan="3"><ul class="ev_ul" style="list-style: none;">' . "\n";
		echo "<li class='ev_td_li' style='border:0px;'>\n";
		echo JText::_('JEV_NO_EVENTS') ;
		echo "</li>\n";
		echo "</ul></td></tr>\n";
}
echo '</table><br />' . "\n";
echo '</fieldset><br /><br />' . "\n";
//  $this->showNavTableText(10, 10, $num_events, $offset, '');
